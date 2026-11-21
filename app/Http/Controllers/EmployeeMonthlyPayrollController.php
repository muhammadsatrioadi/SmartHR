<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\EmployeeMonthlyPayroll;
use App\Models\Karyawan;
use App\Support\ActivityLogger;
use App\Support\PanduanGajiCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class EmployeeMonthlyPayrollController extends Controller
{
    /**
     * Daftar rekap gaji bulanan per pegawai.
     */
    public function index(Request $request)
    {
        $periode = $request->query('periode');

        $rekap = EmployeeMonthlyPayroll::with(['karyawan', 'penyusun'])
            ->when($periode, fn ($q) => $q->where('periode', $periode))
            ->orderByDesc('periode')
            ->orderBy('karyawan_id')
            ->paginate(20)
            ->withQueryString();

        $periodes = EmployeeMonthlyPayroll::query()
            ->select('periode')
            ->distinct()
            ->orderByDesc('periode')
            ->pluck('periode');

        return view('payroll_monthly.index', compact('rekap', 'periodes', 'periode'));
    }

    public function create(Request $request)
    {
        $karyawans = Karyawan::with(['jabatan', 'employeeStatus', 'salaryHistories'])
            ->orderBy('name')
            ->get();

        $selected = $request->query('karyawan_id');
        $periode = $request->query('periode', now('Asia/Jakarta')->format('Y-m'));
        $rekap = null;
        $usulanPph21 = $this->usulanPph21($selected ? (int) $selected : null, $periode);

        return view('payroll_monthly.create', compact('karyawans', 'selected', 'periode', 'rekap', 'usulanPph21'));
    }

    public function store(Request $request)
    {
        $validated = $this->validasi($request);

        $rekap = EmployeeMonthlyPayroll::updateOrCreate(
            ['karyawan_id' => $validated['karyawan_id'], 'periode' => $validated['periode']],
            array_merge($validated, ['disiapkan_oleh' => Auth::id()])
        );

        $this->catat($rekap, 'simpan', 'Rekap gaji bulanan disimpan');

        return redirect()->route('payrollMonthly.index', ['periode' => $validated['periode']])
            ->with('success', 'Rekap gaji bulanan berhasil disimpan.');
    }

    public function edit(EmployeeMonthlyPayroll $payrollMonthly)
    {
        $karyawans = Karyawan::with(['jabatan', 'employeeStatus', 'salaryHistories'])
            ->orderBy('name')
            ->get();

        $rekap = $payrollMonthly;
        $selected = $rekap->karyawan_id;
        $periode = $rekap->periode;
        $usulanPph21 = $this->usulanPph21($selected, $periode);

        return view('payroll_monthly.edit', compact('rekap', 'karyawans', 'selected', 'periode', 'usulanPph21'));
    }

    public function update(Request $request, EmployeeMonthlyPayroll $payrollMonthly)
    {
        abort_if($payrollMonthly->status === 'final', 403, 'Slip sudah difinalkan. Batalkan finalisasi terlebih dahulu.');

        $validated = $this->validasi($request);

        $payrollMonthly->update($validated);

        $this->catat($payrollMonthly, 'perbarui', 'Rekap gaji bulanan diperbarui');

        return redirect()->route('payrollMonthly.index', ['periode' => $payrollMonthly->periode])
            ->with('success', 'Rekap gaji bulanan berhasil diperbarui.');
    }

    public function destroy(EmployeeMonthlyPayroll $payrollMonthly)
    {
        abort_if($payrollMonthly->status === 'final', 403, 'Slip sudah difinalkan dan tidak dapat dihapus.');

        $periode = $payrollMonthly->periode;
        $this->catat($payrollMonthly, 'hapus', 'Rekap gaji bulanan dihapus');
        $payrollMonthly->delete();

        return redirect()->route('payrollMonthly.index', ['periode' => $periode])
            ->with('success', 'Rekap gaji bulanan berhasil dihapus.');
    }

    /**
     * Halaman proses rekap massal: menampilkan seluruh pegawai untuk satu periode
     * beserta status rekap dan perkiraan slip, serta tombol buat & ekspor.
     */
    public function batch(Request $request)
    {
        $periode = $this->resolvePeriode($request->query('periode'));

        $baris = $this->susunBarisMassal($periode);

        return view('payroll_monthly.batch', compact('baris', 'periode'));
    }

    /**
     * Buat rekap (draft) untuk semua pegawai yang belum punya rekap pada periode ini.
     */
    public function batchStore(Request $request)
    {
        $data = $request->validate([
            'periode' => 'required|regex:/^\d{4}-\d{2}$/',
        ]);

        $periode = $data['periode'];

        $sudahAda = EmployeeMonthlyPayroll::where('periode', $periode)->pluck('karyawan_id')->all();

        $karyawans = Karyawan::with('salaryHistories')
            ->whereNotIn('id', $sudahAda)
            ->orderBy('name')
            ->get();

        $dibuat = 0;
        foreach ($karyawans as $karyawan) {
            // Lewati pegawai yang belum punya data gaji dasar (tak bisa dihitung).
            if (!$karyawan->salaryHistories->count()) {
                continue;
            }

            EmployeeMonthlyPayroll::create([
                'karyawan_id' => $karyawan->id,
                'periode' => $periode,
                'hari_pagi' => 0,
                'hari_siang' => 0,
                'hari_malam' => 0,
                'hari_telat_pulang_awal' => 0,
                'status' => 'draft',
                'disiapkan_oleh' => Auth::id(),
            ]);
            $dibuat++;
        }

        ActivityLogger::catat('rekap_gaji_bulanan', 'buat_massal', [
            'referensi' => 'Rekap massal ' . $periode,
            'keterangan' => 'Membuat ' . $dibuat . ' rekap draft untuk periode ' . $periode,
        ]);

        return redirect()->route('payrollMonthly.batch', ['periode' => $periode])
            ->with('success', $dibuat . ' rekap draft berhasil dibuat. Lengkapi kehadiran & potongan sebelum difinalkan.');
    }

    /**
     * Ekspor rekap massal (hasil hitung slip) ke CSV.
     */
    public function exportBatch(Request $request)
    {
        $periode = $this->resolvePeriode($request->query('periode'));
        $baris = $this->susunBarisMassal($periode);

        $namaFile = 'rekap-gaji-' . $periode . '.csv';

        ActivityLogger::catat('rekap_gaji_bulanan', 'ekspor', [
            'referensi' => 'Ekspor rekap ' . $periode,
            'keterangan' => 'Ekspor rekap gaji periode ' . $periode . ' ke CSV',
        ]);

        return response()->streamDownload(function () use ($baris) {
            $out = fopen('php://output', 'w');
            fputcsv($out, [
                'NIK', 'Nama', 'Jabatan', 'Status',
                'Penghasilan (A)', 'Potongan Resmi (B)', 'Potongan Lain (D)', 'Gaji Bersih', 'Terima Bersih',
            ]);
            foreach ($baris as $row) {
                fputcsv($out, [
                    $row['karyawan']->nik,
                    $row['karyawan']->name,
                    $row['karyawan']->jabatan->nama_jabatan ?? '-',
                    $row['rekap']->status ?? 'belum',
                    $row['slip']['total_pendapatan'],
                    $row['slip']['total_potongan_resmi'],
                    $row['slip']['total_potongan_lain'],
                    $row['slip']['gaji_bersih'],
                    $row['slip']['terima_bersih'],
                ]);
            }
            fclose($out);
        }, $namaFile, ['Content-Type' => 'text/csv']);
    }

    /**
     * Susun daftar pegawai + rekap + perkiraan slip untuk periode tertentu.
     */
    private function susunBarisMassal(string $periode): array
    {
        $karyawans = Karyawan::with([
            'jabatan',
            'employeeStatus',
            'salaryHistories',
            'monthlyPayrolls' => fn ($q) => $q->where('periode', $periode),
        ])->orderBy('name')->get();

        return $karyawans->map(function ($karyawan) use ($periode) {
            $rekap = $karyawan->monthlyPayrolls->first();

            return [
                'karyawan' => $karyawan,
                'rekap' => $rekap,
                'slip' => PanduanGajiCalculator::slip($karyawan, $periode, $rekap),
            ];
        })->all();
    }

    private function resolvePeriode(?string $periode): string
    {
        if (is_string($periode) && preg_match('/^\d{4}-\d{2}$/', $periode)) {
            return $periode;
        }

        return now('Asia/Jakarta')->format('Y-m');
    }

    /**
     * Usulan PPh 21 untuk pegawai & periode terpilih; null bila pegawai belum dipilih.
     */
    private function usulanPph21(?int $karyawanId, string $periode): ?array
    {
        if (!$karyawanId) {
            return null;
        }

        $karyawan = Karyawan::with(['jabatan', 'employeeStatus', 'salaryHistories'])->find($karyawanId);

        if (!$karyawan) {
            return null;
        }

        $rekap = EmployeeMonthlyPayroll::where('karyawan_id', $karyawanId)
            ->where('periode', $periode)
            ->first();

        return PanduanGajiCalculator::slip($karyawan, $periode, $rekap)['usulan_pph21'];
    }

    /**
     * Finalisasi: mengunci rekap & slip agar tidak berubah setelah dibayarkan.
     */
    public function finalize(EmployeeMonthlyPayroll $payrollMonthly)
    {
        if ($payrollMonthly->status === 'final') {
            return back()->with('success', 'Slip periode ini sudah berstatus final.');
        }

        $payrollMonthly->update([
            'status' => 'final',
            'difinalkan_pada' => now('Asia/Jakarta'),
        ]);

        $this->catat($payrollMonthly, 'finalkan', 'Slip difinalkan dan dikunci');

        return back()->with('success', 'Slip berhasil difinalkan. Data terkunci sampai finalisasi dibatalkan.');
    }

    /**
     * Batalkan finalisasi bila masih ada koreksi.
     */
    public function batalkanFinal(EmployeeMonthlyPayroll $payrollMonthly)
    {
        $payrollMonthly->update([
            'status' => 'draft',
            'difinalkan_pada' => null,
        ]);

        $this->catat($payrollMonthly, 'buka_finalisasi', 'Finalisasi slip dibatalkan');

        return back()->with('success', 'Finalisasi dibatalkan. Slip kembali berstatus draft.');
    }

    /**
     * Tarik hitungan kehadiran dari modul Absensi untuk pegawai & periode terpilih.
     * Shift ditentukan dari jam absen masuk: <12 pagi, 12-18 siang, >=18 malam.
     */
    public function tarikAbsensi(Request $request)
    {
        $data = $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'periode' => 'required|regex:/^\d{4}-\d{2}$/',
        ]);

        [$tahun, $bulan] = array_pad(explode('-', $data['periode']), 2, '1');

        $absensis = Absensi::query()
            ->where('karyawan_id', $data['karyawan_id'])
            ->where('status_absen', 'hadir')
            ->where('tipe_absen', 'masuk')
            ->whereYear('tanggal_absensi', (int) $tahun)
            ->whereMonth('tanggal_absensi', (int) $bulan)
            ->get(['tanggal_absensi', 'time']);

        $pagi = $siang = $malam = 0;

        foreach ($absensis as $absen) {
            $jam = (int) substr((string) $absen->time, 0, 2);
            if ($jam < 12) {
                $pagi++;
            } elseif ($jam < 18) {
                $siang++;
            } else {
                $malam++;
            }
        }

        return response()->json([
            'success' => true,
            'hari_pagi' => $pagi,
            'hari_siang' => $siang,
            'hari_malam' => $malam,
            'total_hari' => $pagi + $siang + $malam,
            'catatan' => 'Jumlah keterlambatan/pulang awal tetap diisi manual karena belum tersimpan di data absensi.',
        ]);
    }

    /**
     * Parameter validasi rekap bulanan; aturan periode unik per pegawai
     * mengikuti model agar updateOrCreate tidak menggandakan baris.
     */
    private function validasi(Request $request): array
    {
        $validated = $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'periode' => 'required|regex:/^\d{4}-\d{2}$/',
            'hari_pagi' => 'nullable|integer|min:0|max:31',
            'hari_siang' => 'nullable|integer|min:0|max:31',
            'hari_malam' => 'nullable|integer|min:0|max:31',
            'hari_telat_pulang_awal' => 'nullable|integer|min:0|max:31',
            'is_koordinator' => 'nullable|boolean',
            'nominal_on_call' => 'nullable|numeric|min:0',
            'nominal_cuti_besar' => 'nullable|numeric|min:0',
            'nominal_rapel' => 'nullable|numeric|min:0',
            'nominal_insentif_hari_raya' => 'nullable|numeric|min:0',
            'nominal_pendapatan_lain' => 'nullable|numeric|min:0',
            'nominal_pph21' => 'nullable|numeric|min:0',
            'potongan_infaq' => 'nullable|numeric|min:0',
            'potongan_angsuran_pinjaman' => 'nullable|numeric|min:0',
            'potongan_bank_syariah' => 'nullable|numeric|min:0',
            'potongan_koperasi' => 'nullable|numeric|min:0',
            'potongan_farmasi' => 'nullable|numeric|min:0',
            'potongan_bsi' => 'nullable|numeric|min:0',
            'potongan_perumahan' => 'nullable|numeric|min:0',
            'potongan_bsm' => 'nullable|numeric|min:0',
            'potongan_lain' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string|max:500',
        ]);

        $angka = [
            'hari_pagi', 'hari_siang', 'hari_malam', 'hari_telat_pulang_awal',
            'nominal_on_call', 'nominal_cuti_besar', 'nominal_rapel',
            'nominal_insentif_hari_raya', 'nominal_pendapatan_lain', 'nominal_pph21',
            'potongan_angsuran_pinjaman', 'potongan_bank_syariah', 'potongan_koperasi',
            'potongan_farmasi', 'potongan_bsi', 'potongan_perumahan', 'potongan_bsm',
            'potongan_lain',
        ];
        foreach ($angka as $kolom) {
            $validated[$kolom] = (float) ($validated[$kolom] ?? 0);
        }

        // Infaq dikosongkan berarti dihitung otomatis 2,5% x dasar perhitungan.
        $validated['potongan_infaq'] = $request->filled('potongan_infaq')
            ? (float) $validated['potongan_infaq']
            : null;

        $validated['is_koordinator'] = $request->boolean('is_koordinator');

        // Validasi kehadiran: total hari kerja tidak boleh melebihi jumlah hari periode.
        $jumlahHari = Carbon::createFromFormat('Y-m', $validated['periode'])->daysInMonth;
        $totalHariKerja = (int) $validated['hari_pagi'] + (int) $validated['hari_siang'] + (int) $validated['hari_malam'];

        if ($totalHariKerja > $jumlahHari) {
            throw ValidationException::withMessages([
                'hari_pagi' => "Total hari kehadiran ($totalHariKerja hari) melebihi jumlah hari pada periode ini ($jumlahHari hari).",
            ]);
        }

        return $validated;
    }

    /**
     * Catat perubahan rekap gaji bulanan ke log audit.
     */
    private function catat(EmployeeMonthlyPayroll $rekap, string $aksi, string $keterangan): void
    {
        ActivityLogger::catat('rekap_gaji_bulanan', $aksi, [
            'referensi' => 'Rekap #' . $rekap->id . ' (' . $rekap->periode . ')',
            'karyawan_id' => $rekap->karyawan_id,
            'keterangan' => $keterangan,
            'perubahan' => $rekap->only([
                'hari_pagi', 'hari_siang', 'hari_malam', 'hari_telat_pulang_awal', 'is_koordinator',
                'nominal_on_call', 'nominal_cuti_besar', 'nominal_rapel', 'nominal_insentif_hari_raya',
                'nominal_pendapatan_lain', 'nominal_pph21', 'potongan_infaq', 'potongan_angsuran_pinjaman',
                'potongan_bank_syariah', 'potongan_koperasi', 'potongan_farmasi', 'potongan_bsi',
                'potongan_perumahan', 'potongan_bsm', 'potongan_lain', 'status',
            ]),
        ]);
    }
}

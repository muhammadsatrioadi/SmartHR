<?php

namespace App\Http\Controllers;

use App\Models\EmployeeSalaryHistory;
use App\Models\Karyawan;
use App\Models\SalaryStep;
use App\Support\ActivityLogger;
use App\Support\PayrollCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeSalaryHistoryController extends Controller
{
    public function index()
    {
        $histories = EmployeeSalaryHistory::with(['karyawan', 'salaryStep'])
            ->orderByDesc('tanggal_berlaku')
            ->paginate(15);
        $histories->getCollection()->transform(function ($history) {
            $components = self::komponenLembur($history);
            $history->dasar_upah_lembur = PayrollCalculator::calculateOvertimeBase($components);
            $history->upah_per_jam = PayrollCalculator::calculateHourlyWage($components);
            return $history;
        });
        return view('salary_histories.index', compact('histories'));
    }

    public function create()
    {
        $karyawans = Karyawan::with('jabatan')->orderBy('name')->get();
        $steps = SalaryStep::orderBy('kode')->get();

        // Data untuk auto-fill form di sisi klien ketika pegawai dipilih.
        $karyawanData = $karyawans->map(function (Karyawan $k) {
            $jabatan = $k->jabatan;
            return [
                'id' => $k->id,
                'gaji_pokok' => (float) ($jabatan->gaji_pokok ?? 0),
                'tunjangan_jabatan' => (float) ($jabatan->tunjangan_jabatan ?? 0),
                'tunjangan_fungsional' => (float) ($jabatan->tunjangan_fungsional ?? 0),
                'tunjangan_profesi' => (float) ($jabatan->tunjangan_profesi ?? 0),
                'tunjangan_makan' => (float) ($jabatan->tunjangan_makan ?? 0),
                'tunjangan_pulsa' => (float) ($jabatan->tunjangan_pulsa ?? 0),
                'tkp' => (float) ($jabatan->tkp ?? 0),
                'hari_kerja_per_minggu' => (int) ($jabatan->hari_kerja_per_minggu ?: 5),
                'pasangan_ditanggung' => (bool) $k->ditanggung,
                'jumlah_anak_ditanggung' => min((int) $k->anak_isteri_ke, 3),
                'tanggal_masuk' => optional($k->tanggal_masuk)->toDateString(),
            ];
        })->values();

        $stepData = $steps->map(function (SalaryStep $s) {
            return [
                'id' => $s->id,
                'masa_kerja_min' => (float) $s->masa_kerja_min,
                'masa_kerja_maks' => $s->masa_kerja_maks === null ? null : (float) $s->masa_kerja_maks,
            ];
        })->values();

        return view('salary_histories.create', compact('karyawans', 'steps', 'karyawanData', 'stepData'));
    }

    /**
     * Tentukan skala gaji yang berlaku berdasarkan masa kerja karyawan.
     * Skala dipilih yang masa_kerja_min-nya paling besar tetapi masih
     * kurang dari/ sama dengan masa kerja karyawan (dan masih dalam
     * rentang masa_kerja_maks bila diisi). Skala tidak bisa diubah user.
     */
    protected function tentukanSkalaGaji(Karyawan $karyawan, $steps = null): ?int
    {
        if (!$karyawan->tanggal_masuk) {
            return null;
        }

        $masaKerja = (float) $karyawan->tanggal_masuk->diffInYears(now('Asia/Jakarta'));
        $steps = $steps ?? SalaryStep::orderByDesc('masa_kerja_min')->get();

        foreach ($steps as $step) {
            if ((float) $step->masa_kerja_min <= $masaKerja
                && ($step->masa_kerja_maks === null || $masaKerja < (float) $step->masa_kerja_maks)) {
                return $step->id;
            }
        }

        return null;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'salary_step_id' => 'nullable|exists:salary_steps,id',
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangan_jabatan' => 'nullable|numeric|min:0',
            'tunjangan_fungsional' => 'nullable|numeric|min:0',
            'tunjangan_profesi' => 'nullable|numeric|min:0',
            'tunjangan_makan' => 'nullable|numeric|min:0',
            'tunjangan_pulsa' => 'nullable|numeric|min:0',
            'tkp' => 'nullable|numeric|min:0',
            'pasangan_ditanggung' => 'nullable|boolean',
            'jumlah_anak_ditanggung' => 'nullable|integer|min:0|max:10',
            'tunjangan_lain' => 'nullable|numeric|min:0',
            'hari_kerja_per_minggu' => 'required|in:5,6',
            'tanggal_berlaku' => 'required|date',
            'alasan' => 'nullable|string|max:150',
        ]);

        $rinci = ['tunjangan_jabatan', 'tunjangan_fungsional', 'tunjangan_profesi', 'tunjangan_makan', 'tunjangan_pulsa', 'tkp'];
        $totalRinci = 0;
        foreach ($rinci as $kolom) {
            $validated[$kolom] = (float) ($validated[$kolom] ?? 0);
            $totalRinci += $validated[$kolom];
        }

        // Kolom tunjangan_tetap lama diisi dari jumlah komponen rinci agar modul
        // lain (lembur & estimasi) tetap mendapat angka tunjangan yang benar.
        $validated['tunjangan_tetap'] = $totalRinci;
        $validated['tunjangan_lain'] = $validated['tunjangan_lain'] ?? 0;
        $validated['pasangan_ditanggung'] = $request->boolean('pasangan_ditanggung');
        $validated['jumlah_anak_ditanggung'] = (int) ($validated['jumlah_anak_ditanggung'] ?? 0);
        $validated['disetujui_oleh'] = Auth::id();

        // Skala gaji bersifat otomatis berdasarkan masa kerja karyawan,
        // tidak bisa ditentukan/diubah oleh user.
        $karyawan = Karyawan::findOrFail($validated['karyawan_id']);
        $validated['salary_step_id'] = $this->tentukanSkalaGaji($karyawan);

        $history = EmployeeSalaryHistory::create($validated);

        ActivityLogger::catat('data_gaji_dasar', 'buat', [
            'referensi' => 'Karyawan #' . $history->karyawan_id,
            'karyawan_id' => $history->karyawan_id,
            'keterangan' => 'Data gaji dasar baru ditambahkan (berlaku ' . $history->tanggal_berlaku?->format('d/m/Y') . ')',
            'perubahan' => $history->only([
                'gaji_pokok', 'tunjangan_jabatan', 'tunjangan_fungsional', 'tunjangan_profesi',
                'tunjangan_makan', 'tunjangan_pulsa', 'tkp', 'pasangan_ditanggung',
                'jumlah_anak_ditanggung', 'hari_kerja_per_minggu',
            ]),
        ]);

        return redirect()->route('salaryHistory.index')->with('success', 'Data gaji dasar pegawai berhasil ditambahkan.');
    }

    /**
     * Komponen untuk perhitungan upah lembur memakai komponen rinci bila ada,
     * atau kolom lama sebagai cadangan.
     */
    public static function komponenLembur(EmployeeSalaryHistory $history): array
    {
        return [
            'gaji_pokok' => $history->gaji_pokok,
            'tunjangan_tetap' => $history->tunjanganTetapTotal(),
            'tunjangan_lain' => $history->tunjangan_lain,
        ];
    }
}

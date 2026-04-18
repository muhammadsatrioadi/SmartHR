<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\Golongan;
use App\Models\Pangkat;
use App\Models\EmployeeStatus;
use App\Models\Department;
use App\Models\WorkUnit;
use App\Models\ShiftGroup;
use App\Models\EmployeeEmploymentHistory;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawans = Karyawan::all();
        return view('karyawans.index', compact('karyawans'));
    }

    public function create()
    {
        $jabatans = Jabatan::orderBy('nama_jabatan')->get();
        $golongans = Golongan::orderBy('kode')->get();
        $pangkats = Pangkat::orderBy('kode')->get();
        $employeeStatuses = EmployeeStatus::orderBy('kode')->get();
        $departments = Department::orderBy('kode')->get();
        $workUnits = WorkUnit::orderBy('kode')->get();
        $shiftGroups = ShiftGroup::orderBy('kode')->get();
        $allKaryawan = Karyawan::orderBy('name')->get();
        return view('karyawans.create', compact(
            'jabatans',
            'golongans',
            'pangkats',
            'employeeStatuses',
            'departments',
            'workUnits',
            'shiftGroups',
            'allKaryawan'
        ));
    }

    public function store(Request $request)
    {
        $rules = $this->karyawanValidationRules($request, null);
        $validatedData = $request->validate($rules);

        $employmentHistories = $validatedData['employment_histories'] ?? [];
        unset($validatedData['employment_histories']);
        $validatedData['is_aktif_dinas'] = $request->boolean('is_aktif_dinas');
        $validatedData['ditanggung'] = $request->boolean('ditanggung');
        $validatedData['is_full_time'] = $request->boolean('is_full_time');
        $validatedData['ptkp_tak_penuh'] = $request->boolean('ptkp_tak_penuh');

        $karyawan = Karyawan::create($validatedData);

        // Sinkron user login untuk karyawan (dibuat/diupdate oleh Admin HR)
        if (!empty($karyawan->email)) {
            User::updateOrCreate(
                ['email' => $karyawan->email],
                [
                    'name' => $karyawan->name,
                    'role' => $request->input('role', 'karyawan'),
                    // Password awal: NIK atau 'karyawan123' jika NIK kosong
                    'password' => Hash::make($karyawan->nik ?: 'karyawan123'),
                    'imgProfile' => 'user.jpg',
                ]
            );
        }

        foreach ($employmentHistories as $row) {
            if (array_filter($row)) {
                $karyawan->employmentHistories()->create($row);
            }
        }

        return redirect()->route('karyawans.index')->with('success', 'Data Karyawan berhasil dibuat.');
    }

    protected function karyawanValidationRules(Request $request, $id)
    {
        $base = [
            'nik' => 'nullable|string|max:50|unique:karyawans,nik,' . (int) $id,
            'name' => 'required|string|max:255',
            'nama_lengkap' => 'nullable|string|max:255',
            'email' => 'required|email|unique:karyawans,email,' . (int) $id,
            'role' => 'nullable|string|in:admin_hr,atasan,karyawan',
            'jenis_kelamin' => 'required|string|max:50',
            'telephone' => 'required|string|max:30',
            'handphone' => 'nullable|string|max:30',
            'status' => 'required|string|max:100',
            'jabatan_id' => 'required|exists:jabatans,id',
            'golongan_id' => 'nullable|exists:golongans,id',
            'pangkat_id' => 'nullable|exists:pangkats,id',
            'employee_status_id' => 'nullable|exists:employee_statuses,id',
            'department_id' => 'nullable|exists:departments,id',
            'work_unit_id' => 'nullable|exists:work_units,id',
            'shift_group_id' => 'nullable|exists:shift_groups,id',
            'ktp' => 'required|string|max:50',
            'NPWP' => 'required|string|max:50',
            'total_kontak' => 'required|integer|min:0',
            'tempat_lahir' => 'nullable|string|max:150',
            'tanggal_lahir' => 'nullable|date',
            'agama' => 'nullable|string|max:100',
            'status_perkawinan' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'alamat_tinggal' => 'nullable|string',
            'provinsi' => 'nullable|string|max:150',
            'kota' => 'nullable|string|max:150',
            'kabupaten' => 'nullable|string|max:150',
            'kecamatan' => 'nullable|string|max:150',
            'kelurahan' => 'nullable|string|max:150',
            'kode_pos' => 'nullable|string|max:10',
            'tanggal_masuk' => 'nullable|date',
            'tanggal_keluar' => 'nullable|date',
            'alasan_keluar' => 'nullable|string',
            'cara_keluar' => 'nullable|string|max:100',
            'bank' => 'nullable|string|max:100',
            'no_rekening' => 'nullable|string|max:100',
            'bpjs_kesehatan' => 'nullable|string|max:100',
            'bpjs_ketenagakerjaan' => 'nullable|string|max:100',
            'nbm' => 'nullable|string|max:50',
            'is_aktif_dinas' => 'nullable|boolean',
            'hub_dengan_nik' => 'nullable|string|max:50',
            'nik_utama' => 'nullable|string|max:50',
            'pegawai_text' => 'nullable|string|max:100',
            'anak_isteri_ke' => 'nullable|string|max:20',
            'ditanggung' => 'nullable|boolean',
            'no_identitas' => 'nullable|string|max:50',
            'tgl_ed_id' => 'nullable|date',
            'nama_ayah' => 'nullable|string|max:150',
            'nama_ibu' => 'nullable|string|max:150',
            'ayah_mertua' => 'nullable|string|max:150',
            'ibu_mertua' => 'nullable|string|max:150',
            'tgl_menikah' => 'nullable|date',
            'tgl_cerai' => 'nullable|date',
            'pekerjaan_suami_istri' => 'nullable|string|max:150',
            'golongan_darah' => 'nullable|string|max:10',
            'no_kartu_pegawai' => 'nullable|string|max:50',
            'no_sk_penempatan' => 'nullable|string|max:100',
            'divisi' => 'nullable|string|max:100',
            'bagian' => 'nullable|string|max:100',
            'tgl_unit_kerja' => 'nullable|date',
            'tgl_orientasi_mulai' => 'nullable|date',
            'tgl_orientasi_akhir' => 'nullable|date',
            'tgl_kontrak_1_mulai' => 'nullable|date',
            'tgl_kontrak_1_akhir' => 'nullable|date',
            'tgl_kontrak_2_mulai' => 'nullable|date',
            'tgl_kontrak_2_akhir' => 'nullable|date',
            'tgl_pegawai_tetap' => 'nullable|date',
            'no_sk_tetap' => 'nullable|string|max:100',
            'arsip' => 'nullable|string|max:100',
            'jaminan_polis' => 'nullable|string|max:100',
            'nik_atasan' => 'nullable|string|max:50',
            'lokasi_tugas' => 'nullable|string|max:150',
            'gelar' => 'nullable|string|max:50',
            'jenis_pegawai' => 'nullable|string|max:50',
            'grup_kinerja' => 'nullable|string|max:50',
            'is_full_time' => 'nullable|boolean',
            'no_surat_registrasi' => 'nullable|string|max:100',
            'tgl_register_mulai' => 'nullable|date',
            'tgl_register_akhir' => 'nullable|date',
            'sk_ijin_kerja' => 'nullable|string|max:100',
            'no_sk_ijin_praktek' => 'nullable|string|max:100',
            'tgl_sk_ijin_praktek' => 'nullable|date',
            'tgl_sk_ijin_praktek_berlaku_sd' => 'nullable|date',
            'tgl_sk_ijin_kerja' => 'nullable|date',
            'tgl_sk_ijin_kerja_berlaku_sd' => 'nullable|date',
            'hak_kelas_ranap' => 'nullable|string|max:50',
            'no_amplop' => 'nullable|string|max:50',
            'hak_plafon' => 'nullable|string|max:50',
            'nama_account' => 'nullable|string|max:100',
            'jenis_profesi' => 'nullable|string|max:100',
            'keterangan_jabatan' => 'nullable|string|max:255',
            'tgl_daftar_npwp' => 'nullable|date',
            'tanggungan_pajak' => 'nullable|string|max:50',
            'ptkp_tak_penuh' => 'nullable|boolean',
            'status_kawin_tax' => 'nullable|string|max:50',
            'employment_histories' => 'nullable|array',
            'employment_histories.*.pangkat' => 'nullable|string|max:100',
            'employment_histories.*.golongan' => 'nullable|string|max:100',
            'employment_histories.*.status' => 'nullable|string|max:100',
            'employment_histories.*.keterangan' => 'nullable|string|max:255',
            'employment_histories.*.sk_tmt' => 'nullable|date',
            'employment_histories.*.tgl_kontrak_mulai' => 'nullable|date',
            'employment_histories.*.tgl_kontrak_akhir' => 'nullable|date',
            'employment_histories.*.masa_kerja_tahun' => 'nullable|integer|min:0',
            'employment_histories.*.masa_kerja_bulan' => 'nullable|integer|min:0',
            'employment_histories.*.pejabat' => 'nullable|string|max:150',
            'employment_histories.*.no_sk' => 'nullable|string|max:100',
        ];
        return $base;
    }

    public function edit($id)
    {
        $karyawan = Karyawan::with('employmentHistories')->findOrFail($id);
        $user = User::where('email', $karyawan->email)->first();
        $currentRole = $user ? $user->role : 'karyawan';

        $jabatans = Jabatan::orderBy('nama_jabatan')->get();
        $golongans = Golongan::orderBy('kode')->get();
        $pangkats = Pangkat::orderBy('kode')->get();
        $employeeStatuses = EmployeeStatus::orderBy('kode')->get();
        $departments = Department::orderBy('kode')->get();
        $workUnits = WorkUnit::orderBy('kode')->get();
        $shiftGroups = ShiftGroup::orderBy('kode')->get();
        $allKaryawan = Karyawan::where('id', '!=', $id)->orderBy('name')->get();
        return view('karyawans.edit', compact(
            'karyawan',
            'currentRole',
            'jabatans',
            'golongans',
            'pangkats',
            'employeeStatuses',
            'departments',
            'workUnits',
            'shiftGroups',
            'allKaryawan'
        ));
    }

    public function update(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $rules = $this->karyawanValidationRules($request, $id);
        $validatedData = $request->validate($rules);

        $employmentHistories = $validatedData['employment_histories'] ?? [];
        unset($validatedData['employment_histories']);
        $validatedData['is_aktif_dinas'] = $request->boolean('is_aktif_dinas');
        $validatedData['ditanggung'] = $request->boolean('ditanggung');
        $validatedData['is_full_time'] = $request->boolean('is_full_time');
        $validatedData['ptkp_tak_penuh'] = $request->boolean('ptkp_tak_penuh');

        $karyawan->update($validatedData);

        // Update user login jika email/nama karyawan berubah
        if (!empty($karyawan->email)) {
            User::updateOrCreate(
                ['email' => $karyawan->email],
                [
                    'name' => $karyawan->name,
                    'role' => $request->input('role', 'karyawan'),
                ]
            );
        }

        $karyawan->employmentHistories()->delete();
        foreach ($employmentHistories as $row) {
            if (array_filter($row)) {
                $karyawan->employmentHistories()->create($row);
            }
        }

        return redirect()->route('karyawans.index')->with('success', 'Data Karyawan berhasil diubah.');
    }


    public function show($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        return view('karyawans.show', compact('karyawan'));
    }

    public function destroy($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $karyawan->delete();
        return redirect()->route('karyawans.index')->with('success', 'Data Karyawan berhasil dihapus.');
    }
}

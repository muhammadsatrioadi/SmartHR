<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Karyawan;
use App\Models\User;
use App\Services\DepartmentManagerSyncService;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Kandidat manajer departemen: karyawan yang akunnya berperan
     * sebagai manajer atau atasan. Bila belum ada, seluruh karyawan
     * ditampilkan agar pengaturan tetap bisa dilakukan.
     */
    protected function managerCandidates()
    {
        $emails = User::whereIn('role', ['manajer', 'atasan'])->pluck('email')->filter();

        $candidates = Karyawan::whereIn('email', $emails)->orderBy('name')->get();

        return $candidates->isNotEmpty() ? $candidates : Karyawan::orderBy('name')->get();
    }

    public function index()
    {
        $data = Department::with('manager')->orderBy('kode')->paginate(10);
        return view('master.department.index', compact('data'));
    }

    public function create()
    {
        $managers = $this->managerCandidates();
        return view('master.department.create', compact('managers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|alpha_num|max:20|unique:departments,kode',
            'nama' => 'required|regex:/^[A-Za-z0-9 ]+$/|max:150',
            'manager_karyawan_id' => 'required|exists:karyawans,id',
            'is_active' => 'nullable|boolean',
        ], [
            'kode.alpha_num' => 'Kode hanya boleh berisi huruf dan angka.',
            'nama.regex' => 'Nama hanya boleh berisi huruf, angka, dan spasi.',
        ]);
        $validated['is_active'] = (bool)($request->input('is_active', true));

        $department = Department::create($validated);
        $this->ensureManagerRole($department->manager_karyawan_id);
        app(DepartmentManagerSyncService::class)->syncDepartment($department);
        return redirect()->route('department.index')->with('success', 'Departemen berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = Department::with('manager')->findOrFail($id);

        $managers = $this->managerCandidates();
        if ($item->manager && !$managers->contains('id', $item->manager->id)) {
            $managers = $managers->push($item->manager)->sortBy('name')->values();
        }

        return view('master.department.edit', compact('item', 'managers'));
    }

    public function update(Request $request, $id)
    {
        $item = Department::findOrFail($id);
        $validated = $request->validate([
            'kode' => 'required|alpha_num|max:20|unique:departments,kode,' . $item->id,
            'nama' => 'required|regex:/^[A-Za-z0-9 ]+$/|max:150',
            'manager_karyawan_id' => 'required|exists:karyawans,id',
            'is_active' => 'nullable|boolean',
        ], [
            'kode.alpha_num' => 'Kode hanya boleh berisi huruf dan angka.',
            'nama.regex' => 'Nama hanya boleh berisi huruf, angka, dan spasi.',
        ]);
        $validated['is_active'] = (bool)($request->input('is_active', true));

        $previousManagerId = $item->manager_karyawan_id;

        $item->update($validated);
        $this->ensureManagerRole($item->manager_karyawan_id);
        app(DepartmentManagerSyncService::class)->syncDepartment($item);

        if ((int) $previousManagerId !== (int) $item->manager_karyawan_id) {
            $this->demoteIfNoLongerManager($previousManagerId);
        }

        return redirect()->route('department.index')->with('success', 'Departemen berhasil diperbarui.');
    }

    /**
     * Pastikan akun user dari manajer departemen memiliki role yang bisa
     * mengakses halaman approval (manajer/atasan). Tanpa ini, manajer yang
     * baru ditunjuk tidak dapat melihat pengajuan meski sudah dipilih.
     */
    protected function ensureManagerRole($karyawanId): void
    {
        if (!$karyawanId) {
            return;
        }

        $karyawan = Karyawan::find($karyawanId);
        if (!$karyawan || empty($karyawan->email)) {
            return;
        }

        User::where('email', $karyawan->email)
            ->whereNotIn('role', ['admin_hr'])
            ->update(['role' => 'manajer']);
    }

    /**
     * Turunkan kembali role manajer lama bila ia sudah tidak memimpin
     * departemen mana pun, agar tampilan portal mengikuti role akun.
     */
    protected function demoteIfNoLongerManager($karyawanId): void
    {
        if (!$karyawanId) {
            return;
        }

        if (Department::where('manager_karyawan_id', $karyawanId)->exists()) {
            return;
        }

        $karyawan = Karyawan::find($karyawanId);
        if (!$karyawan || empty($karyawan->email)) {
            return;
        }

        User::where('email', $karyawan->email)
            ->where('role', 'manajer')
            ->update(['role' => 'karyawan']);
    }

    public function destroy($id)
    {
        $item = Department::findOrFail($id);

        if ($item->workUnits()->exists() || $item->karyawans()->exists()) {
            return redirect()->route('department.index')->with('error', 'Gagal menghapus! Departemen ini masih memiliki Unit Kerja atau Karyawan.');
        }

        $item->delete();
        return redirect()->route('department.index')->with('success', 'Departemen berhasil dihapus.');
    }
}

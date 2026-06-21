<?php

namespace App\Http\Controllers;

use App\Models\EmployeeLeaveBalance;
use App\Models\Karyawan;
use App\Models\LeaveType;
use App\Services\LeaveBalanceService;
use Illuminate\Http\Request;

class LeaveBalanceController extends Controller
{
    public function __construct(
        protected LeaveBalanceService $leaveBalanceService
    ) {}

    public function index(Request $request)
    {
        $tahun = (int) $request->get('tahun', date('Y'));

        $query = Karyawan::query()
            ->whereHas('leaveBalances', fn ($q) => $q->where('tahun', $tahun))
            ->with(['leaveBalances' => fn ($q) => $q->where('tahun', $tahun)->with('leaveType')]);

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $data = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('master.leave_balance.index', compact('data', 'tahun'));
    }

    public function show(Request $request, Karyawan $karyawan)
    {
        $tahun = (int) $request->get('tahun', date('Y'));

        $this->leaveBalanceService->ensureBalancesForYear($karyawan, $tahun);

        $balances = EmployeeLeaveBalance::with('leaveType')
            ->where('karyawan_id', $karyawan->id)
            ->where('tahun', $tahun)
            ->orderBy('leave_type_id')
            ->get();

        return view('master.leave_balance.show', compact('karyawan', 'balances', 'tahun'));
    }

    public function create(Request $request, Karyawan $karyawan)
    {
        $tahun = (int) $request->get('tahun', date('Y'));

        $existingTypeIds = EmployeeLeaveBalance::where('karyawan_id', $karyawan->id)
            ->where('tahun', $tahun)
            ->pluck('leave_type_id');

        $leaveTypes = LeaveType::whereNotIn('id', $existingTypeIds)
            ->orderBy('nama')
            ->get();

        if ($leaveTypes->isEmpty()) {
            return redirect()
                ->route('leaveBalance.show', ['karyawan' => $karyawan->id, 'tahun' => $tahun])
                ->with('success', 'Semua jenis cuti sudah memiliki saldo untuk pegawai ini.');
        }

        return view('master.leave_balance.create', compact('karyawan', 'leaveTypes', 'tahun'));
    }

    public function store(Request $request, Karyawan $karyawan)
    {
        $tahun = (int) $request->get('tahun', date('Y'));

        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'kuota' => 'required|numeric|min:0',
            'terpakai' => 'required|numeric|min:0',
        ]);

        $exists = EmployeeLeaveBalance::where('karyawan_id', $karyawan->id)
            ->where('leave_type_id', $validated['leave_type_id'])
            ->where('tahun', $tahun)
            ->exists();

        if ($exists) {
            return back()->withErrors(['leave_type_id' => 'Saldo untuk jenis cuti ini sudah ada.'])->withInput();
        }

        $terpakai = (float) $validated['terpakai'];
        $kuota = (float) $validated['kuota'];

        EmployeeLeaveBalance::create([
            'karyawan_id' => $karyawan->id,
            'leave_type_id' => $validated['leave_type_id'],
            'tahun' => $tahun,
            'kuota' => $kuota,
            'terpakai' => $terpakai,
            'sisa' => max($kuota - $terpakai, 0),
        ]);

        return redirect()
            ->route('leaveBalance.show', ['karyawan' => $karyawan->id, 'tahun' => $tahun])
            ->with('success', 'Saldo cuti berhasil ditambahkan.');
    }

    public function edit(Request $request, Karyawan $karyawan)
    {
        $tahun = (int) $request->get('tahun', date('Y'));

        $this->leaveBalanceService->ensureBalancesForYear($karyawan, $tahun);

        $balances = EmployeeLeaveBalance::with('leaveType')
            ->where('karyawan_id', $karyawan->id)
            ->where('tahun', $tahun)
            ->orderBy('leave_type_id')
            ->get();

        return view('master.leave_balance.edit', compact('karyawan', 'balances', 'tahun'));
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $tahun = (int) $request->get('tahun', date('Y'));

        $validated = $request->validate([
            'balances' => 'required|array|min:1',
            'balances.*.id' => 'required|exists:employee_leave_balances,id',
            'balances.*.kuota' => 'required|numeric|min:0',
            'balances.*.terpakai' => 'required|numeric|min:0',
        ]);

        foreach ($validated['balances'] as $row) {
            $balance = EmployeeLeaveBalance::where('id', $row['id'])
                ->where('karyawan_id', $karyawan->id)
                ->where('tahun', $tahun)
                ->firstOrFail();

            $kuota = (float) $row['kuota'];
            $terpakai = (float) $row['terpakai'];

            $balance->update([
                'kuota' => $kuota,
                'terpakai' => $terpakai,
                'sisa' => max($kuota - $terpakai, 0),
            ]);
        }

        return redirect()
            ->route('leaveBalance.show', ['karyawan' => $karyawan->id, 'tahun' => $tahun])
            ->with('success', 'Semua saldo cuti berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = EmployeeLeaveBalance::findOrFail($id);
        $karyawanId = $item->karyawan_id;
        $tahun = $item->tahun;

        $item->delete();

        return redirect()
            ->route('leaveBalance.show', ['karyawan' => $karyawanId, 'tahun' => $tahun])
            ->with('success', 'Saldo cuti berhasil dihapus.');
    }

    /**
     * Sinkronisasi saldo cuti untuk semua karyawan di tahun tertentu.
     */
    public function sync(Request $request)
    {
        $tahun = (int) $request->input('tahun', date('Y'));
        $karyawans = Karyawan::all();

        foreach ($karyawans as $karyawan) {
            $this->leaveBalanceService->ensureBalancesForYear($karyawan, $tahun);
        }

        return redirect()
            ->route('leaveBalance.index', ['tahun' => $tahun])
            ->with('success', "Sinkronisasi saldo cuti tahun {$tahun} berhasil.");
    }
}

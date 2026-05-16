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
        $query = EmployeeLeaveBalance::with(['karyawan', 'leaveType']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->whereHas('karyawan', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        if ($request->has('tahun')) {
            $query->where('tahun', $request->get('tahun'));
        } else {
            $query->where('tahun', date('Y'));
        }

        $data = $query->latest()->paginate(15);
        return view('master.leave_balance.index', compact('data'));
    }

    public function edit($id)
    {
        $item = EmployeeLeaveBalance::with(['karyawan', 'leaveType'])->findOrFail($id);
        return view('master.leave_balance.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = EmployeeLeaveBalance::findOrFail($id);
        
        $validated = $request->validate([
            'kuota' => 'required|numeric|min:0',
            'terpakai' => 'required|numeric|min:0',
        ]);

        $validated['sisa'] = max($validated['kuota'] - $validated['terpakai'], 0);

        $item->update($validated);

        return redirect()->route('leaveBalance.index')
            ->with('success', 'Saldo cuti berhasil diperbarui.');
    }

    /**
     * Sinkronisasi saldo cuti untuk semua karyawan di tahun tertentu.
     */
    public function sync(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $karyawans = Karyawan::all();

        foreach ($karyawans as $karyawan) {
            $this->leaveBalanceService->ensureBalancesForYear($karyawan, $tahun);
        }

        return redirect()->route('leaveBalance.index')
            ->with('success', "Sinkronisasi saldo cuti tahun {$tahun} berhasil.");
    }
}

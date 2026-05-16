<?php

namespace App\Services;

use App\Models\Cuti;
use App\Models\EmployeeLeaveBalance;
use App\Models\Karyawan;
use App\Models\LeaveType;

class LeaveBalanceService
{
    public function ensureBalancesForYear(Karyawan $karyawan, int $tahun): void
    {
        $types = LeaveType::where('kuota_hari', '>', 0)->get();

        foreach ($types as $type) {
            EmployeeLeaveBalance::firstOrCreate(
                [
                    'karyawan_id' => $karyawan->id,
                    'leave_type_id' => $type->id,
                    'tahun' => $tahun,
                ],
                [
                    'kuota' => $type->kuota_hari,
                    'terpakai' => 0,
                    'sisa' => $type->kuota_hari,
                ]
            );
        }
    }

    public function balancesFor(Karyawan $karyawan, ?int $tahun = null): array
    {
        $tahun = $tahun ?? (int) date('Y');
        $this->ensureBalancesForYear($karyawan, $tahun);

        return EmployeeLeaveBalance::with('leaveType')
            ->where('karyawan_id', $karyawan->id)
            ->where('tahun', $tahun)
            ->orderBy('leave_type_id')
            ->get()
            ->all();
    }

    public function deduct(Karyawan $karyawan, int $leaveTypeId, float $hari, int $tahun): bool
    {
        $balance = EmployeeLeaveBalance::where('karyawan_id', $karyawan->id)
            ->where('leave_type_id', $leaveTypeId)
            ->where('tahun', $tahun)
            ->first();

        if (!$balance || $balance->sisa < $hari) {
            return false;
        }

        $balance->terpakai += $hari;
        $balance->sisa = max($balance->kuota - $balance->terpakai, 0);
        $balance->save();

        return true;
    }

    public function recalculateUsed(Karyawan $karyawan, int $leaveTypeId, int $tahun): void
    {
        $type = LeaveType::find($leaveTypeId);
        if (!$type) {
            return;
        }

        $terpakai = Cuti::where('karyawan_id', $karyawan->id)
            ->where('leave_type_id', $leaveTypeId)
            ->whereYear('tanggal_mulai', $tahun)
            ->where('status', 'disetujui')
            ->sum('hak_diambil');

        $balance = EmployeeLeaveBalance::firstOrCreate(
            [
                'karyawan_id' => $karyawan->id,
                'leave_type_id' => $leaveTypeId,
                'tahun' => $tahun,
            ],
            [
                'kuota' => $type->kuota_hari,
                'terpakai' => 0,
                'sisa' => $type->kuota_hari,
            ]
        );

        $balance->terpakai = $terpakai;
        $balance->sisa = max($balance->kuota - $terpakai, 0);
        $balance->save();
    }
}

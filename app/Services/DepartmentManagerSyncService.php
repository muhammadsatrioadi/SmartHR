<?php

namespace App\Services;

use App\Models\Department;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DepartmentManagerSyncService
{
    public function syncAfterRoleChange(Karyawan $karyawan, string $role): void
    {
        DB::transaction(function () use ($karyawan, $role) {
            $karyawan->refresh();
            $departmentsManaged = Department::where('manager_karyawan_id', $karyawan->id)->get();

            if (in_array($role, ['atasan', 'manajer'], true) && $karyawan->department_id) {
                foreach ($departmentsManaged as $department) {
                    if ((int) $department->id !== (int) $karyawan->department_id) {
                        $this->replaceManager($department, $karyawan->id);
                    }
                }

                $department = Department::find($karyawan->department_id);
                if ($department) {
                    $department->update(['manager_karyawan_id' => $karyawan->id]);
                    $this->syncDirectSupervisor($department, $karyawan);
                }

                return;
            }

            foreach ($departmentsManaged as $department) {
                $this->replaceManager($department, $karyawan->id);
            }
        });
    }

    public function syncDepartment(Department $department): void
    {
        $department->refresh()->load('manager');
        $this->syncDirectSupervisor($department, $department->manager);
    }

    protected function replaceManager(Department $department, int $departingManagerId): void
    {
        $managerEmails = User::whereIn('role', ['atasan', 'manajer'])
            ->whereNotNull('email')
            ->select('email');

        $candidates = Karyawan::where('department_id', $department->id)
            ->where('id', '!=', $departingManagerId)
            ->whereIn('email', $managerEmails)
            ->get();

        $newManager = $candidates->count() === 1 ? $candidates->first() : null;

        $department->update(['manager_karyawan_id' => $newManager?->id]);
        $this->syncDirectSupervisor($department, $newManager);
    }

    protected function syncDirectSupervisor(Department $department, ?Karyawan $manager): void
    {
        Karyawan::where('department_id', $department->id)
            ->when($manager, fn ($query) => $query->where('id', '!=', $manager->id))
            ->update(['nik_atasan' => $manager?->nik]);

        if ($manager && (int) $manager->department_id === (int) $department->id) {
            $manager->update(['nik_atasan' => null]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\EmployeeSchedule;
use App\Models\Holiday;
use App\Models\Karyawan;
use App\Models\ShiftGroup;
use App\Models\WorkShift;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class EmployeeScheduleController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $search = $request->input('search');
        $start = Carbon::parse($month . '-01');
        $end = (clone $start)->endOfMonth();

        $query = Karyawan::query();

        // Filter berdasarkan atasan jika user yang login adalah karyawan/atasan (bukan admin_hr)
        $user = auth()->user();
        if ($user->role !== 'admin_hr') {
            $me = Karyawan::where('email', $user->email)->first();
            if ($me) {
                // Hanya tampilkan diri sendiri dan bawahan langsung
                $query->where(function($q) use ($me) {
                    $q->where('id', $me->id)
                      ->orWhere('nik_atasan', $me->nik);
                });
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('nik', 'like', '%' . $search . '%');
            });
        }

        $karyawans = $query->orderBy('name')->get();
        $shiftGroups = ShiftGroup::orderBy('kode')->get();
        $workShifts = WorkShift::orderBy('kode')->get();

        $schedules = EmployeeSchedule::whereBetween('tanggal', [$start, $end])
            ->get()
            ->groupBy('karyawan_id');

        $holidays = Holiday::whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])
            ->orderBy('tanggal')
            ->get()
            ->groupBy(function ($holiday) {
                return $holiday->tanggal->toDateString();
            });

        return view('jadwal.employee.index', compact(
            'month',
            'search',
            'start',
            'end',
            'karyawans',
            'shiftGroups',
            'workShifts',
            'schedules',
            'holidays'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'tanggal' => 'required|date',
            'shift_group_id' => 'nullable|exists:shift_groups,id',
            'work_shift_id' => 'nullable|exists:work_shifts,id',
            'is_libur' => 'nullable|boolean',
        ]);

        $validated['is_libur'] = (bool)($request->input('is_libur', false));

        EmployeeSchedule::updateOrCreate(
            [
                'karyawan_id' => $validated['karyawan_id'],
                'tanggal' => $validated['tanggal'],
            ],
            $validated,
        );

        return back()->with('success', 'Jadwal pegawai berhasil disimpan.');
    }

    public function massAssign(Request $request)
    {
        $validated = $request->validate([
            'shift_group_id' => 'nullable|exists:shift_groups,id',
            'work_shift_id' => 'required_without:shift_group_id|exists:work_shifts,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'karyawan_ids' => 'required|array',
            'karyawan_ids.*' => 'exists:karyawans,id',
        ]);

        $start = Carbon::parse($validated['start_date']);
        $end = Carbon::parse($validated['end_date']);

        $dates = [];
        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            $dates[] = $cursor->toDateString();
            $cursor->addDay();
        }

        foreach ($validated['karyawan_ids'] as $karyawanId) {
            foreach ($dates as $date) {
                EmployeeSchedule::updateOrCreate(
                    [
                        'karyawan_id' => $karyawanId,
                        'tanggal' => $date,
                    ],
                    [
                        'shift_group_id' => $validated['shift_group_id'] ?? null,
                        'work_shift_id' => $validated['work_shift_id'],
                        'is_libur' => false,
                    ],
                );
            }
        }

        return back()->with('success', 'Jadwal massal berhasil dibuat.');
    }
}

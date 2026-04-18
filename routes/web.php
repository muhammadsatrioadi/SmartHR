<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\GajiController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\GolonganController;
use App\Http\Controllers\PangkatController;
use App\Http\Controllers\EmployeeStatusController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\WorkUnitController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\ShiftGroupController;
use App\Http\Controllers\WorkShiftController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\EmployeeScheduleController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\SalaryStepController;
use App\Http\Controllers\EmployeeSalaryHistoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\IndonesiaController;




// route login/logout
Route::get('/', [LoginController::class,'login'] )->name('login');
Route::post('/', [LoginController::class,'actionlogin'] )->name('actionlogin');
Route::get('actionlogout', [LoginController::class, 'actionlogout'])->name('actionlogout');
Route::post('actionAdminUpdate', [LoginController::class, 'actionAdminUpdate'])->name('actionAdminUpdate');

// Group routes under 'auth' middleware
Route::middleware(['auth'])->group(function () {

//route Dashboard
Route::get('/dashboard', [Dashboard::class,'index'] )->name('index');
Route::get('/profile', [Dashboard::class,'profile'] )->name('profile');

//route jabatan
Route::get('/jabatans', [JabatanController::class,'index'])->name('jabatan.read');
Route::get('/create', [JabatanController::class,'create'])->name('jabatan.create');
Route::post('/create-proses', [JabatanController::class,'store'])->name('jabatan.createProses');
Route::get('/edit/{id}', [JabatanController::class,'edit'])->name('jabatan.edit');
Route::put('/edit-proses/{id}', [JabatanController::class,'update'])->name('jabatan.update');
Route::delete('/delete/{id}', [JabatanController::class,'destroy'])->name('jabatan.delete');
Route::get('/search', [JabatanController::class,'search'])->name('jabatan.search');

//route cuti
Route::get('/cuti', [CutiController::class,'index'])->name('cuti.read');
Route::get('/cuti/create', [CutiController::class,'create'])->name('cuti.create');
Route::post('/cuti/create-proses', [CutiController::class,'store'])->name('cuti.createProses');
Route::get('/cuti/edit/{id}', [CutiController::class,'edit'])->name('cuti.edit');
Route::put('/cuti/edit-proses/{id}', [CutiController::class,'update'])->name('cuti.update');
Route::delete('/cuti/delete/{id}', [CutiController::class,'destroy'])->name('cuti.delete');
Route::post('/cuti/{id}/approve-supervisor', [CutiController::class,'approveSupervisor'])->name('cuti.approveSupervisor');
Route::post('/cuti/{id}/approve-hr', [CutiController::class,'approveHr'])->name('cuti.approveHr');
Route::post('/cuti/{id}/reject', [CutiController::class,'reject'])->name('cuti.reject');

// route karyawan
Route::get('/karyawans', [KaryawanController::class,'index'])->name('karyawans.index');
Route::get('/karyawans/create', [KaryawanController::class,'create'])->name('karyawans.create');
Route::post('/karyawans/create-proses', [KaryawanController::class,'store'])->name('karyawans.store');
Route::get('/karyawans/edit/{id}', [KaryawanController::class,'edit'])->name('karyawans.edit');
Route::put('/karyawans/edit-proses/{id}', [KaryawanController::class,'update'])->name('karyawans.update');
Route::delete('/karyawans/delete/{id}', [KaryawanController::class,'destroy'])->name('karyawans.delete');

// Setup master: Jabatan, Golongan, Pangkat, Status Pegawai, Departemen, Unit Kerja
Route::get('/golongans', [GolonganController::class, 'index'])->name('golongan.index');
Route::get('/golongans/create', [GolonganController::class, 'create'])->name('golongan.create');
Route::post('/golongans', [GolonganController::class, 'store'])->name('golongan.store');
Route::get('/golongans/{id}/edit', [GolonganController::class, 'edit'])->name('golongan.edit');
Route::put('/golongans/{id}', [GolonganController::class, 'update'])->name('golongan.update');
Route::delete('/golongans/{id}', [GolonganController::class, 'destroy'])->name('golongan.delete');

Route::get('/pangkats', [PangkatController::class, 'index'])->name('pangkat.index');
Route::get('/pangkats/create', [PangkatController::class, 'create'])->name('pangkat.create');
Route::post('/pangkats', [PangkatController::class, 'store'])->name('pangkat.store');
Route::get('/pangkats/{id}/edit', [PangkatController::class, 'edit'])->name('pangkat.edit');
Route::put('/pangkats/{id}', [PangkatController::class, 'update'])->name('pangkat.update');
Route::delete('/pangkats/{id}', [PangkatController::class, 'destroy'])->name('pangkat.delete');

Route::get('/employee-statuses', [EmployeeStatusController::class, 'index'])->name('employeeStatus.index');
Route::get('/employee-statuses/create', [EmployeeStatusController::class, 'create'])->name('employeeStatus.create');
Route::post('/employee-statuses', [EmployeeStatusController::class, 'store'])->name('employeeStatus.store');
Route::get('/employee-statuses/{id}/edit', [EmployeeStatusController::class, 'edit'])->name('employeeStatus.edit');
Route::put('/employee-statuses/{id}', [EmployeeStatusController::class, 'update'])->name('employeeStatus.update');
Route::delete('/employee-statuses/{id}', [EmployeeStatusController::class, 'destroy'])->name('employeeStatus.delete');

Route::get('/departments', [DepartmentController::class, 'index'])->name('department.index');
Route::get('/departments/create', [DepartmentController::class, 'create'])->name('department.create');
Route::post('/departments', [DepartmentController::class, 'store'])->name('department.store');
Route::get('/departments/{id}/edit', [DepartmentController::class, 'edit'])->name('department.edit');
Route::put('/departments/{id}', [DepartmentController::class, 'update'])->name('department.update');
Route::delete('/departments/{id}', [DepartmentController::class, 'destroy'])->name('department.delete');

Route::get('/work-units', [WorkUnitController::class, 'index'])->name('workUnit.index');
Route::get('/work-units/create', [WorkUnitController::class, 'create'])->name('workUnit.create');
Route::post('/work-units', [WorkUnitController::class, 'store'])->name('workUnit.store');
Route::get('/work-units/{id}/edit', [WorkUnitController::class, 'edit'])->name('workUnit.edit');
Route::put('/work-units/{id}', [WorkUnitController::class, 'update'])->name('workUnit.update');
Route::delete('/work-units/{id}', [WorkUnitController::class, 'destroy'])->name('workUnit.delete');

// Setup Hari Libur, Group Shift, Shift Kerja, Jenis Cuti
Route::get('/holidays', [HolidayController::class, 'index'])->name('holiday.index');
Route::get('/holidays/create', [HolidayController::class, 'create'])->name('holiday.create');
Route::post('/holidays', [HolidayController::class, 'store'])->name('holiday.store');
Route::post('/holidays/sync', [HolidayController::class, 'sync'])->name('holiday.sync');
Route::get('/holidays/{id}/edit', [HolidayController::class, 'edit'])->name('holiday.edit');
Route::put('/holidays/{id}', [HolidayController::class, 'update'])->name('holiday.update');
Route::delete('/holidays/{id}', [HolidayController::class, 'destroy'])->name('holiday.delete');

Route::get('/shift-groups', [ShiftGroupController::class, 'index'])->name('shiftGroup.index');
Route::get('/shift-groups/create', [ShiftGroupController::class, 'create'])->name('shiftGroup.create');
Route::post('/shift-groups', [ShiftGroupController::class, 'store'])->name('shiftGroup.store');
Route::get('/shift-groups/{id}/edit', [ShiftGroupController::class, 'edit'])->name('shiftGroup.edit');
Route::put('/shift-groups/{id}', [ShiftGroupController::class, 'update'])->name('shiftGroup.update');
Route::delete('/shift-groups/{id}', [ShiftGroupController::class, 'destroy'])->name('shiftGroup.delete');

Route::get('/work-shifts', [WorkShiftController::class, 'index'])->name('workShift.index');
Route::get('/work-shifts/create', [WorkShiftController::class, 'create'])->name('workShift.create');
Route::post('/work-shifts', [WorkShiftController::class, 'store'])->name('workShift.store');
Route::get('/work-shifts/{id}/edit', [WorkShiftController::class, 'edit'])->name('workShift.edit');
Route::put('/work-shifts/{id}', [WorkShiftController::class, 'update'])->name('workShift.update');
Route::delete('/work-shifts/{id}', [WorkShiftController::class, 'destroy'])->name('workShift.delete');

Route::get('/leave-types', [LeaveTypeController::class, 'index'])->name('leaveType.index');
Route::get('/leave-types/create', [LeaveTypeController::class, 'create'])->name('leaveType.create');
Route::post('/leave-types', [LeaveTypeController::class, 'store'])->name('leaveType.store');
Route::get('/leave-types/{id}/edit', [LeaveTypeController::class, 'edit'])->name('leaveType.edit');
Route::put('/leave-types/{id}', [LeaveTypeController::class, 'update'])->name('leaveType.update');
Route::delete('/leave-types/{id}', [LeaveTypeController::class, 'destroy'])->name('leaveType.delete');

// Jadwal Shift per Pegawai
Route::get('/employee-schedules', [EmployeeScheduleController::class, 'index'])->name('employeeSchedule.index');
Route::post('/employee-schedules', [EmployeeScheduleController::class, 'store'])->name('employeeSchedule.store');
Route::post('/employee-schedules/mass-assign', [EmployeeScheduleController::class, 'massAssign'])->name('employeeSchedule.massAssign');

// Lembur
Route::get('/overtimes', [OvertimeController::class, 'index'])->name('overtime.index');
Route::get('/overtimes/create', [OvertimeController::class, 'create'])->name('overtime.create');
Route::post('/overtimes', [OvertimeController::class, 'store'])->name('overtime.store');
Route::post('/overtimes/{id}/approve-supervisor', [OvertimeController::class, 'approveSupervisor'])->name('overtime.approveSupervisor');
Route::post('/overtimes/{id}/approve-hr', [OvertimeController::class, 'approveHr'])->name('overtime.approveHr');
Route::post('/overtimes/{id}/reject', [OvertimeController::class, 'reject'])->name('overtime.reject');

// Gaji Berkala
Route::get('/salary-steps', [SalaryStepController::class, 'index'])->name('salaryStep.index');
Route::get('/salary-steps/create', [SalaryStepController::class, 'create'])->name('salaryStep.create');
Route::post('/salary-steps', [SalaryStepController::class, 'store'])->name('salaryStep.store');
Route::get('/salary-steps/{id}/edit', [SalaryStepController::class, 'edit'])->name('salaryStep.edit');
Route::put('/salary-steps/{id}', [SalaryStepController::class, 'update'])->name('salaryStep.update');
Route::delete('/salary-steps/{id}', [SalaryStepController::class, 'destroy'])->name('salaryStep.delete');

Route::get('/salary-histories', [EmployeeSalaryHistoryController::class, 'index'])->name('salaryHistory.index');
Route::get('/salary-histories/create', [EmployeeSalaryHistoryController::class, 'create'])->name('salaryHistory.create');
Route::post('/salary-histories', [EmployeeSalaryHistoryController::class, 'store'])->name('salaryHistory.store');


//route absensi
Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.read');
Route::post('/absensi/create-proses', [AbsensiController::class, 'store'])->name('absensi.createProses');
Route::get('/absensi/absensiHadir', [AbsensiController::class, 'getHadir'])->name('absensi.absensiHadir');
Route::get('/absensi/absensiAlpha', [AbsensiController::class, 'getAlpha'])->name('absensi.absensiAlpha');

// route gaji
Route::get('/gajis', [GajiController::class, 'index'])->name('gaji.index');

// API alamat Indonesia (untuk chaining alamat master karyawan)
Route::get('/indonesia/provinces', [IndonesiaController::class, 'provinces'])->name('indonesia.provinces');
Route::get('/indonesia/regencies/{province}', [IndonesiaController::class, 'regencies'])->name('indonesia.regencies');
Route::get('/indonesia/districts/{regency}', [IndonesiaController::class, 'districts'])->name('indonesia.districts');
Route::get('/indonesia/villages/{district}', [IndonesiaController::class, 'villages'])->name('indonesia.villages');

});

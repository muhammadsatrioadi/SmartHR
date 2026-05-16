<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\AttendanceConsent;
use App\Models\Cuti;
use App\Models\EmployeeSchedule;
use App\Models\Gaji;
use App\Models\Karyawan;
use App\Models\LeaveType;
use App\Models\Overtime;
use App\Models\Reimbursement;
use App\Services\LeaveBalanceService;
use App\Support\KaryawanResolver;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PortalController extends Controller
{
    public function __construct(
        protected LeaveBalanceService $leaveBalance
    ) {}

    protected function karyawanOrAbort()
    {
        $karyawan = KaryawanResolver::fromUser(Auth::user());
        abort_unless($karyawan, 403, 'Data karyawan tidak ditemukan untuk akun ini.');

        return $karyawan;
    }

    public function home()
    {
        $karyawan = $this->karyawanOrAbort()->load(['workUnit', 'department']);
        $today = Carbon::today('Asia/Jakarta');
        $bulanIni = Carbon::now('Asia/Jakarta');

        $absensiHariIni = Absensi::where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal_absensi', $today)
            ->orderBy('time')
            ->get();

        $masuk = $absensiHariIni->firstWhere('tipe_absen', 'masuk');
        $pulang = $absensiHariIni->firstWhere('tipe_absen', 'pulang');

        $ringkasan = [
            'hadir' => Absensi::where('karyawan_id', $karyawan->id)
                ->where('status_absen', 'hadir')
                ->whereMonth('tanggal_absensi', $bulanIni->month)
                ->whereYear('tanggal_absensi', $bulanIni->year)
                ->count(),
            'terlambat' => 0,
            'cuti_pending' => Cuti::where('karyawan_id', $karyawan->id)
                ->whereIn('status', ['menunggu_atasan', 'menunggu_hr'])
                ->count(),
            'reimburse' => Reimbursement::where('karyawan_id', $karyawan->id)
                ->whereIn('status', ['pending', 'disetujui_atasan'])
                ->count() + 
                Overtime::where('karyawan_id', $karyawan->id)
                ->whereIn('status', ['menunggu_approval', 'disetujui_atasan'])
                ->count(),
        ];

        $saldoCuti = $this->leaveBalance->balancesFor($karyawan, (int) $bulanIni->year);
        $user = Auth::user();
        $greeting = $this->greeting();

        $pendingApprovals = collect();
        if (in_array($user->role, ['atasan', 'manajer', 'admin_hr'], true)) {
            $pendingApprovals = Cuti::with(['karyawan', 'leaveType'])
                ->where('status', 'menunggu_atasan')
                ->when(in_array($user->role, ['atasan', 'manajer'], true), function ($q) use ($karyawan) {
                    $q->whereHas('karyawan', fn ($k) => $k->where('nik_atasan', $karyawan->nik));
                })
                ->latest()
                ->limit(5)
                ->get();
        }

        $managerStats = null;
        if (in_array($user->role, ['atasan', 'manajer'], true)) {
            $subordinateIds = Karyawan::where('nik_atasan', $karyawan->nik)->pluck('id');
            $managerStats = [
                'total_tim' => $subordinateIds->count(),
                'hadir_hari_ini' => Absensi::whereIn('karyawan_id', $subordinateIds)
                    ->whereDate('tanggal_absensi', $today)
                    ->distinct('karyawan_id')
                    ->count(),
                'cuti_pending' => Cuti::whereIn('karyawan_id', $subordinateIds)
                    ->where('status', 'menunggu_atasan')
                    ->count(),
                'lembur_pending' => Overtime::whereIn('karyawan_id', $subordinateIds)
                    ->where('status', 'menunggu_approval')
                    ->count(),
            ];
        }

        return view('portal.home', compact(
            'karyawan',
            'masuk',
            'pulang',
            'ringkasan',
            'saldoCuti',
            'user',
            'greeting',
            'pendingApprovals',
            'today',
            'managerStats'
        ));
    }

    public function absensiHistory()
    {
        $karyawan = $this->karyawanOrAbort();
        $items = Absensi::where('karyawan_id', $karyawan->id)
            ->orderByDesc('tanggal_absensi')
            ->orderByDesc('time')
            ->paginate(15);

        return view('portal.absensi', compact('karyawan', 'items'));
    }

    public function subordinateAttendance()
    {
        $karyawan = $this->karyawanOrAbort();
        $user = Auth::user();

        abort_unless(in_array($user->role, ['atasan', 'manajer', 'admin_hr'], true), 403);

        $items = Absensi::with('karyawan')
            ->when(in_array($user->role, ['atasan', 'manajer'], true), function ($q) use ($karyawan) {
                $q->whereHas('karyawan', fn ($k) => $k->where('nik_atasan', $karyawan->nik));
            })
            ->orderByDesc('tanggal_absensi')
            ->orderByDesc('time')
            ->paginate(20);

        return view('portal.subordinate_attendance', compact('karyawan', 'items'));
    }

    public function cuti()
    {
        $karyawan = $this->karyawanOrAbort();
        $items = Cuti::with('leaveType')
            ->where('karyawan_id', $karyawan->id)
            ->orderByDesc('created_at')
            ->paginate(10);
        $saldoCuti = $this->leaveBalance->balancesFor($karyawan);

        return view('portal.cuti', compact('karyawan', 'items', 'saldoCuti'));
    }

    public function approvals()
    {
        $karyawan = $this->karyawanOrAbort();
        $user = Auth::user();

        abort_unless(in_array($user->role, ['atasan', 'manajer', 'admin_hr'], true), 403);

        $cutis = Cuti::with(['karyawan', 'leaveType'])
            ->whereIn('status', ['menunggu_atasan', 'disetujui', 'ditolak'])
            ->when(in_array($user->role, ['atasan', 'manajer'], true), function ($q) use ($karyawan) {
                $q->whereHas('karyawan', fn ($k) => $k->where('nik_atasan', $karyawan->nik));
            })
            ->orderByRaw("FIELD(status, 'menunggu_atasan', 'disetujui', 'ditolak')")
            ->orderByDesc('created_at')
            ->get();

        $overtimes = Overtime::with(['karyawan'])
            ->whereIn('status', ['menunggu_approval', 'disetujui', 'ditolak'])
            ->when(in_array($user->role, ['atasan', 'manajer'], true), function ($q) use ($karyawan) {
                $q->whereHas('karyawan', fn ($k) => $k->where('nik_atasan', $karyawan->nik));
            })
            ->orderByRaw("FIELD(status, 'menunggu_approval', 'disetujui', 'ditolak')")
            ->orderByDesc('created_at')
            ->get();

        return view('portal.approvals', compact('karyawan', 'cutis', 'overtimes'));
    }

    public function ajukanCuti()
    {
        $karyawan = $this->karyawanOrAbort();
        $leaveTypes = LeaveType::orderBy('nama')->get();
        $saldoCuti = $this->leaveBalance->balancesFor($karyawan);

        return view('portal.cuti_create', compact('karyawan', 'leaveTypes', 'saldoCuti'));
    }

    public function simpanCuti(Request $request)
    {
        $karyawan = $this->karyawanOrAbort();
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'required|string|max:255',
        ]);

        $tanggalMulai = Carbon::parse($validated['tanggal_mulai']);
        $tanggalBerakhir = Carbon::parse($validated['tanggal_berakhir']);
        $jumlahHari = $tanggalMulai->diffInDays($tanggalBerakhir) + 1;
        $tahun = $tanggalMulai->year;

        $leaveType = LeaveType::findOrFail($validated['leave_type_id']);
        $this->leaveBalance->ensureBalancesForYear($karyawan, $tahun);

        $balance = \App\Models\EmployeeLeaveBalance::where('karyawan_id', $karyawan->id)
            ->where('leave_type_id', $leaveType->id)
            ->where('tahun', $tahun)
            ->first();

        if (!$balance || $balance->sisa < $jumlahHari) {
            return back()->withInput()->with('error', 'Saldo cuti tidak mencukupi.');
        }

        Cuti::create([
            'karyawan_id' => $karyawan->id,
            'leave_type_id' => $leaveType->id,
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_berakhir' => $validated['tanggal_berakhir'],
            'keterangan' => $validated['keterangan'],
            'jenis_cuti' => $leaveType->nama,
            'status' => 'menunggu_atasan',
            'saldo_awal' => $balance->kuota,
            'hak_diambil' => $jumlahHari,
            'saldo_sisa' => $balance->sisa - $jumlahHari,
        ]);

        return redirect()->route('portal.cuti')->with('success', 'Pengajuan cuti berhasil dikirim.');
    }

    public function gaji()
    {
        $karyawan = $this->karyawanOrAbort();
        $items = Gaji::where('karyawan_id', $karyawan->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('portal.gaji', compact('karyawan', 'items'));
    }

    public function profil()
    {
        $karyawan = $this->karyawanOrAbort()->load(['jabatan', 'department', 'workUnit']);

        return view('portal.profil', [
            'karyawan' => $karyawan,
            'user' => Auth::user(),
        ]);
    }

    public function ubahPassword()
    {
        return view('portal.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password lama salah.');
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('portal.profil')->with('success', 'Password berhasil diubah.');
    }

    public function consent()
    {
        $user = Auth::user();
        $consents = AttendanceConsent::where('user_id', $user->id)->get()->keyBy('jenis');

        return view('portal.consent', compact('consents'));
    }

    public function storeConsent(Request $request)
    {
        $validated = $request->validate([
            'jenis' => 'required|in:perjanjian_absensi,task_list_flowchart',
            'disetujui' => 'required|accepted',
        ]);

        AttendanceConsent::updateOrCreate(
            ['user_id' => Auth::id(), 'jenis' => $validated['jenis']],
            [
                'disetujui' => true,
                'disetujui_pada' => now(),
                'ip_address' => $request->ip(),
            ]
        );

        return redirect()
            ->route('portal.consent')
            ->with('success', 'Dokumen berhasil disetujui.');
    }

    protected function greeting(): string
    {
        $hour = (int) Carbon::now('Asia/Jakarta')->format('H');
        if ($hour < 11) {
            return 'Selamat Pagi';
        }
        if ($hour < 15) {
            return 'Selamat Siang';
        }
        if ($hour < 18) {
            return 'Selamat Sore';
        }

        return 'Selamat Malam';
    }
}

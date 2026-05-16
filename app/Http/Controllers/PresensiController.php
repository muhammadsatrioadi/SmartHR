<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\AttendanceConsent;
use App\Models\AttendanceLocation;
use App\Models\EmployeeSchedule;
use App\Services\DeviceBindingService;
use App\Support\GeolocationService;
use App\Support\KaryawanResolver;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiController extends Controller
{
    public function __construct(
        protected DeviceBindingService $deviceBinding
    ) {}

    public function index()
    {
        $karyawan = KaryawanResolver::fromUser(Auth::user());
        abort_unless($karyawan, 403);

        $today = Carbon::today('Asia/Jakarta');
        $schedule = EmployeeSchedule::with(['workShift', 'shiftGroup'])
            ->where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal', $today)
            ->first();

        $shift = $schedule?->workShift;
        $absensiHariIni = Absensi::where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal_absensi', $today)
            ->get();

        $masuk = $absensiHariIni->firstWhere('tipe_absen', 'masuk');
        $pulang = $absensiHariIni->firstWhere('tipe_absen', 'pulang');
        $nextType = !$masuk ? 'masuk' : (!$pulang ? 'pulang' : null);

        $location = $karyawan->attendanceLocation
            ?? AttendanceLocation::where('is_aktif', true)->first();

        $consentsOk = $this->hasRequiredConsents();
        $hour = (int) Carbon::now('Asia/Jakarta')->format('H');
        $greeting = $hour < 11 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 18 ? 'Selamat Sore' : 'Selamat Malam'));

        return view('portal.presensi', compact(
            'karyawan',
            'shift',
            'schedule',
            'masuk',
            'pulang',
            'nextType',
            'location',
            'consentsOk',
            'greeting'
        ));
    }

    public function registerDevice(Request $request)
    {
        $validated = $request->validate([
            'device_fingerprint' => 'required|string|max:128',
            'device_label' => 'nullable|string|max:255',
            'platform' => 'nullable|string|max:80',
        ]);

        $result = $this->deviceBinding->registerOrValidate(
            Auth::user(),
            $validated['device_fingerprint'],
            $request
        );

        if (!$result['ok']) {
            return response()->json(['success' => false, 'message' => $result['message']], 403);
        }

        return response()->json([
            'success' => true,
            'registered' => $result['registered'],
            'message' => $result['registered']
                ? 'Perangkat berhasil didaftarkan.'
                : 'Perangkat terverifikasi.',
        ]);
    }

    public function checkin(Request $request)
    {
        if (!$this->hasRequiredConsents()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus menyetujui Surat Perjanjian dan Task List terlebih dahulu.',
                'redirect' => route('portal.consent'),
            ], 422);
        }

        $validated = $request->validate([
            'tipe_absen' => 'required|in:masuk,pulang',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'accuracy' => 'nullable|numeric',
            'device_fingerprint' => 'required|string|max:128',
            'biometric_credential_id' => 'nullable|string|max:255',
            'biometric_verified' => 'required|boolean',
            'lokasi_dinas' => 'nullable|boolean',
            'catatan' => 'nullable|string|max:500',
            'offline_queue_id' => 'nullable|string|max:64',
        ]);

        $karyawan = KaryawanResolver::fromUser(Auth::user());
        if (!$karyawan) {
            return response()->json(['success' => false, 'message' => 'Data karyawan tidak ditemukan.'], 403);
        }

        $deviceResult = $this->deviceBinding->registerOrValidate(
            Auth::user(),
            $validated['device_fingerprint'],
            $request
        );
        if (!$deviceResult['ok']) {
            return response()->json(['success' => false, 'message' => $deviceResult['message']], 403);
        }

        // Ambil lokasi kantor
        $location = $karyawan->attendanceLocation
            ?? AttendanceLocation::where('is_aktif', true)->first();

        // Cek Jarak (Verifikasi Lokasi)
        $jarak = null;
        $locationOk = false;
        if ($location) {
            $jarak = GeolocationService::distanceMeters(
                (float) $validated['latitude'],
                (float) $validated['longitude'],
                (float) $location->latitude,
                (float) $location->longitude
            );
            if ($jarak <= $location->radius_meter) {
                $locationOk = true;
            }
        }

        // Verifikasi Biometrik
        $biometricOk = (bool) ($validated['biometric_verified'] ?? false);

        // Jika lokasi dinas dicentang, kita anggap verifikasi lokasi terpenuhi (dengan catatan)
        if ($validated['lokasi_dinas'] ?? false) {
            $locationOk = true;
        }

        // Validasi: Salah satu harus terpenuhi (Lokasi ATAU Biometrik)
        if (!$locationOk && !$biometricOk) {
            $msg = "Verifikasi gagal. Anda harus berada di radius lokasi kantor";
            if ($location) $msg .= " ({$location->radius_meter}m)";
            $msg .= " atau menggunakan verifikasi Biometrik (Sidik Jari/Face ID).";
            
            if ($jarak !== null) {
                $msg .= " Jarak Anda saat ini: " . round($jarak) . "m.";
            }

            return response()->json([
                'success' => false,
                'message' => $msg,
            ], 422);
        }

        $today = Carbon::today('Asia/Jakarta')->toDateString();
        $now = Carbon::now('Asia/Jakarta');

        $exists = Absensi::where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal_absensi', $today)
            ->where('tipe_absen', $validated['tipe_absen'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Absensi ' . $validated['tipe_absen'] . ' hari ini sudah tercatat.',
            ], 422);
        }

        if ($validated['tipe_absen'] === 'pulang') {
            $hasMasuk = Absensi::where('karyawan_id', $karyawan->id)
                ->whereDate('tanggal_absensi', $today)
                ->where('tipe_absen', 'masuk')
                ->exists();
            if (!$hasMasuk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lakukan absen masuk terlebih dahulu.',
                ], 422);
            }
        }

        $absensi = Absensi::create([
            'karyawan_id' => $karyawan->id,
            'status_absen' => 'hadir',
            'tipe_absen' => $validated['tipe_absen'],
            'keterangan' => $validated['tipe_absen'] === 'masuk' ? 'Check-in portal' : 'Check-out portal',
            'tanggal_absensi' => $today,
            'time' => $now->format('H:i:s'),
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'accuracy' => $validated['accuracy'] ?? null,
            'jarak_meter' => $jarak ? (int) round($jarak) : null,
            'attendance_location_id' => $location?->id,
            'device_fingerprint' => $validated['device_fingerprint'],
            'biometric_credential_id' => $validated['biometric_credential_id'],
            'biometric_verified' => true,
            'lokasi_dinas' => (bool) ($validated['lokasi_dinas'] ?? false),
            'catatan' => $validated['catatan'],
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
        ]);

        return response()->json([
            'success' => true,
            'message' => $validated['tipe_absen'] === 'masuk'
                ? 'Check-in berhasil.'
                : 'Check-out berhasil.',
            'absensi' => $absensi,
            'waktu' => $now->format('H:i:s'),
        ]);
    }

    protected function hasRequiredConsents(): bool
    {
        $userId = Auth::id();
        $required = ['perjanjian_absensi', 'task_list_flowchart'];

        foreach ($required as $jenis) {
            $ok = AttendanceConsent::where('user_id', $userId)
                ->where('jenis', $jenis)
                ->where('disetujui', true)
                ->exists();
            if (!$ok) {
                return false;
            }
        }

        return true;
    }
}

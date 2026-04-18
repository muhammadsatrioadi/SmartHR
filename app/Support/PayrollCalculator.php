<?php

namespace App\Support;

use App\Models\Karyawan;
use App\Models\Overtime;
use Illuminate\Support\Carbon;

class PayrollCalculator
{
    /** Batas lembur total per minggu (di luar istirahat/libur nasional) menurut UU Ketenagakerjaan / penjelasan umum di Kepmen 102. */
    public const MAX_OVERTIME_HOURS_PER_WEEK = 14.0;

    public static function resolveSalaryComponents(Karyawan $karyawan): array
    {
        $history = $karyawan->salaryHistories()
            ->orderByDesc('tanggal_berlaku')
            ->orderByDesc('id')
            ->first();

        if ($history) {
            return [
                'gaji_pokok' => (float) $history->gaji_pokok,
                'tunjangan_tetap' => (float) $history->tunjangan_tetap,
                'tunjangan_lain' => (float) $history->tunjangan_lain,
                'hari_kerja_per_minggu' => (int) ($history->hari_kerja_per_minggu ?: 5),
                'sumber' => 'salary_history',
            ];
        }

        return [
            'gaji_pokok' => (float) ($karyawan->jabatan->gaji_pokok ?? 0),
            'tunjangan_tetap' => (float) ($karyawan->jabatan->tunjangan ?? 0),
            'tunjangan_lain' => 0.0,
            'hari_kerja_per_minggu' => 5,
            'sumber' => 'jabatan',
        ];
    }

    public static function calculateOvertimeBase(array $components): float
    {
        $gajiPokok = (float) ($components['gaji_pokok'] ?? 0);
        $tunjanganTetap = (float) ($components['tunjangan_tetap'] ?? 0);
        $tunjanganLain = (float) ($components['tunjangan_lain'] ?? 0);

        $upahTetap = $gajiPokok + $tunjanganTetap;
        $upahBulanan = $upahTetap + $tunjanganLain;

        if ($upahBulanan <= 0) {
            return 0.0;
        }

        if ($tunjanganLain > 0) {
            return max($upahTetap, $upahBulanan * 0.75);
        }

        return $upahTetap;
    }

    public static function calculateHourlyWage(array $components): float
    {
        return self::calculateOvertimeBase($components) / 173;
    }

    public static function calculateOvertimePay(
        float $hours,
        string $jenisHari,
        int $hariKerjaPerMinggu,
        bool $isHariKerjaTerpendek,
        array $components
    ): array {
        $hourlyWage = self::calculateHourlyWage($components);
        $remainingHours = max(0, round($hours, 2));
        $total = 0.0;

        foreach (self::getSegments($jenisHari, $hariKerjaPerMinggu, $isHariKerjaTerpendek) as $segment) {
            if ($remainingHours <= 0) {
                break;
            }

            $allocatedHours = min($remainingHours, $segment['hours']);
            $total += $allocatedHours * $segment['multiplier'] * $hourlyWage;
            $remainingHours -= $allocatedHours;
        }

        return [
            'dasar_upah_lembur' => round(self::calculateOvertimeBase($components), 2),
            'upah_per_jam' => round($hourlyWage, 2),
            'nominal_lembur' => round($total, 2),
        ];
    }

    public static function maxOvertimeHours(
        string $jenisHari,
        int $hariKerjaPerMinggu,
        bool $isHariKerjaTerpendek
    ): float {
        if ($jenisHari === 'hari_kerja') {
            return 3.0;
        }

        if ($hariKerjaPerMinggu === 6 && $isHariKerjaTerpendek) {
            return 8.0;
        }

        return $hariKerjaPerMinggu === 6 ? 10.0 : 11.0;
    }

    private static function getSegments(
        string $jenisHari,
        int $hariKerjaPerMinggu,
        bool $isHariKerjaTerpendek
    ): array {
        if ($jenisHari === 'hari_kerja') {
            return [
                ['hours' => 1, 'multiplier' => 1.5],
                ['hours' => 24, 'multiplier' => 2.0],
            ];
        }

        if ($hariKerjaPerMinggu === 6 && $isHariKerjaTerpendek) {
            return [
                ['hours' => 5, 'multiplier' => 2.0],
                ['hours' => 1, 'multiplier' => 3.0],
                ['hours' => 2, 'multiplier' => 4.0],
            ];
        }

        if ($hariKerjaPerMinggu === 6) {
            return [
                ['hours' => 7, 'multiplier' => 2.0],
                ['hours' => 1, 'multiplier' => 3.0],
                ['hours' => 2, 'multiplier' => 4.0],
            ];
        }

        return [
            ['hours' => 8, 'multiplier' => 3.0],
            ['hours' => 1, 'multiplier' => 3.0],
            ['hours' => 2, 'multiplier' => 4.0],
        ];
    }

    /**
     * Jumlah jam lembur pegawai dalam satu minggu kalender (Senin–Minggu), tidak termasuk yang ditolak.
     */
    public static function overtimeHoursInWeek(int $karyawanId, Carbon|string $tanggalLembur, ?int $excludeOvertimeId = null): float
    {
        $tanggal = $tanggalLembur instanceof Carbon
            ? $tanggalLembur->copy()->startOfDay()
            : Carbon::parse($tanggalLembur)->startOfDay();

        $start = $tanggal->copy()->startOfWeek(Carbon::MONDAY)->toDateString();
        $end = $tanggal->copy()->endOfWeek(Carbon::SUNDAY)->toDateString();

        $query = Overtime::query()
            ->where('karyawan_id', $karyawanId)
            ->whereBetween('tanggal', [$start, $end])
            ->where('status', '!=', 'ditolak');

        if ($excludeOvertimeId !== null) {
            $query->where('id', '!=', $excludeOvertimeId);
        }

        return (float) $query->sum('jumlah_jam');
    }
}

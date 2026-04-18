<?php

namespace App\Services;

use App\Models\Holiday;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class HolidaySyncService
{
    public function syncRange(int $fromYear, int $toYear): array
    {
        if ($fromYear > $toYear) {
            throw new RuntimeException('Tahun awal tidak boleh lebih besar dari tahun akhir.');
        }

        $summary = [
            'created' => 0,
            'updated' => 0,
            'years' => [],
        ];

        for ($year = $fromYear; $year <= $toYear; $year++) {
            $result = $this->syncYear($year);
            $summary['created'] += $result['created'];
            $summary['updated'] += $result['updated'];
            $summary['years'][] = $year;
        }

        return $summary;
    }

    public function syncYear(int $year): array
    {
        $response = Http::timeout(20)
            ->acceptJson()
            ->get("https://tallyfy.com/national-holidays/api/ID/{$year}.json");

        if (! $response->successful()) {
            throw new RuntimeException("Gagal mengambil data hari libur tahun {$year}.");
        }

        $payload = $response->json();
        $holidays = $payload['holidays'] ?? [];
        $created = 0;
        $updated = 0;

        foreach ($holidays as $index => $holidayData) {
            if (($holidayData['type'] ?? null) !== 'national' || empty($holidayData['date'])) {
                continue;
            }

            $name = trim((string) ($holidayData['local_name'] ?? $holidayData['name'] ?? 'Hari Libur Nasional'));
            $description = trim((string) ($holidayData['description'] ?? ''));
            $date = $holidayData['date'];
            $observedDate = $holidayData['observed_date'] ?? $date;
            $isShifted = (bool) ($holidayData['is_observed_shifted'] ?? false);

            $attributes = [
                'kode_libur' => $this->buildCode($date, $name, $index),
                'keterangan' => $name . ($description !== '' ? ' - ' . $description : ''),
                'is_tetap' => $this->isFixedHoliday($name),
                'is_hari_raya' => $this->isReligiousHoliday($name),
            ];

            if ($isShifted && $observedDate !== $date) {
                $attributes['keterangan'] .= " (diamati {$observedDate})";
            }

            $existing = Holiday::where('tanggal', $date)
                ->where('keterangan', $attributes['keterangan'])
                ->first();

            if ($existing) {
                $existing->update($attributes);
                $updated++;
                continue;
            }

            Holiday::create(array_merge(['tanggal' => $date], $attributes));
            $created++;
        }

        return [
            'year' => $year,
            'created' => $created,
            'updated' => $updated,
        ];
    }

    private function buildCode(string $date, string $name, int $index): string
    {
        $hash = strtoupper(substr(md5($date . '|' . $name . '|' . $index), 0, 7));
        return 'HN' . str_replace('-', '', $date) . $hash;
    }

    private function isFixedHoliday(string $name): bool
    {
        $fixedHolidays = [
            'Tahun Baru Masehi',
            'Hari Buruh Internasional',
            'Hari Lahir Pancasila',
            'Hari Kemerdekaan Republik Indonesia',
            'Hari Raya Natal',
        ];

        return in_array($name, $fixedHolidays, true);
    }

    private function isReligiousHoliday(string $name): bool
    {
        $keywords = [
            'Isra Mi',
            'Idul Fitri',
            'Nyepi',
            'Wafat Isa',
            'Waisak',
            'Kenaikan Isa',
            'Idul Adha',
            'Tahun Baru Islam',
            'Maulid Nabi',
            'Natal',
            'Imlek',
        ];

        foreach ($keywords as $keyword) {
            if (stripos($name, $keyword) !== false) {
                return true;
            }
        }

        return false;
    }
}

<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Services\HolidaySyncService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('holidays:sync {fromYear?} {toYear?}', function (?string $fromYear = null, ?string $toYear = null) {
    $currentYear = (int) now()->format('Y');
    $from = (int) ($fromYear ?: $currentYear);
    $to = (int) ($toYear ?: ($from + 4));

    $summary = app(HolidaySyncService::class)->syncRange($from, $to);

    $this->info('Sinkronisasi hari libur berhasil.');
    $this->line('Tahun: ' . implode(', ', $summary['years']));
    $this->line('Data baru: ' . $summary['created']);
    $this->line('Data diperbarui: ' . $summary['updated']);
})->purpose('Sinkronkan hari libur nasional Indonesia');

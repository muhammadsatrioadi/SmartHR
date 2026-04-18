@extends('layouts.main')

@section('title', 'Jadwal Shift Pegawai')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <h6 class="mb-3">Jadwal Shift Pegawai</h6>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="GET" class="row g-3 mb-4">
                <div class="col-auto">
                    <label class="form-label">Bulan</label>
                    <input type="month" name="month" value="{{ $month }}" class="form-control">
                </div>
                <div class="col-auto">
                    <label class="form-label">Cari Pegawai (Nama/NIK)</label>
                    <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Masukkan nama atau nik...">
                </div>
                <div class="col-auto align-self-end">
                    <button class="btn btn-primary" type="submit">Tampilkan</button>
                </div>
            </form>

            <form method="POST" action="{{ route('employeeSchedule.massAssign') }}" class="mb-4">
                @csrf
                <div class="card bg-dark border-secondary mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold">Kalender Setup Jadwal</span>
                            <small class="text-muted">
                                Klik tanggal untuk langsung set tanggal dan pilih shift.
                            </small>
                        </div>
                        <div class="d-flex gap-3 flex-wrap mb-3">
                            <small><span class="schedule-legend schedule-legend-selected"></span> Tanggal dipilih</small>
                            <small><span class="schedule-legend schedule-legend-holiday"></span> Hari libur Indonesia</small>
                        </div>
                        <div id="schedule-calendar" class="schedule-calendar"></div>
                    </div>
                </div>
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input id="start-date-input" type="date" name="start_date" class="form-control" value="{{ $start->toDateString() }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Akhir</label>
                        <input id="end-date-input" type="date" name="end_date" class="form-control" value="{{ $end->toDateString() }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Shift Kerja</label>
                        <select id="mass-work-shift-select" name="work_shift_id" class="form-select">
                            @foreach ($workShifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->kode }} - {{ $shift->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Untuk Pegawai</label>
                        <select name="karyawan_ids[]" class="form-select" multiple size="3">
                            @foreach ($karyawans as $k)
                                <option value="{{ $k->id }}">{{ $k->nik ?? $k->name }} - {{ $k->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-2 d-flex justify-content-end">
                    <button class="btn btn-outline-light" type="submit">Buat Jadwal Massal</button>
                </div>
            </form>

            <div class="table-responsive">
                @php
                    $period = new DatePeriod($start, new DateInterval('P1D'), $end->copy()->addDay());
                @endphp
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Pegawai</th>
                            @foreach ($period as $date)
                                @php
                                    $holidayList = $holidays[$date->format('Y-m-d')] ?? collect();
                                @endphp
                                <th style="min-width: 120px; text-align:center;">
                                    <div>{{ $date->format('d') }}</div>
                                    <small class="text-muted">{{ $date->format('M Y') }}</small>
                                    @if ($holidayList->isNotEmpty())
                                        <div class="mt-1">
                                            <span class="badge bg-danger" title="{{ $holidayList->pluck('keterangan')->join(' | ') }}">
                                                {{ $holidayList->count() > 1 ? $holidayList->count() . ' Libur' : 'Libur' }}
                                            </span>
                                        </div>
                                        <div><small class="text-warning">{{ $holidayList->pluck('kode_libur')->join(', ') }}</small></div>
                                    @endif
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($karyawans as $karyawan)
                            <tr>
                                <td>{{ $karyawan->name }}</td>
                                @php
                                    $rowSchedules = $schedules[$karyawan->id] ?? collect();
                                @endphp
                                @foreach ($period as $date)
                                    @php
                                        $tglStr = $date->format('Y-m-d');
                                        $sc = $rowSchedules->firstWhere('tanggal', $tglStr);
                                        $holidayList = $holidays[$tglStr] ?? collect();
                                    @endphp
                                    <td style="padding: 0.15rem;" class="{{ $holidayList->isNotEmpty() ? 'schedule-holiday-cell' : '' }}">
                                        <form method="POST" action="{{ route('employeeSchedule.store') }}">
                                            @csrf
                                            <input type="hidden" name="karyawan_id" value="{{ $karyawan->id }}">
                                            <input type="hidden" name="tanggal" value="{{ $tglStr }}">
                                            <input type="hidden" name="is_libur" value="{{ $holidayList->isNotEmpty() ? 1 : 0 }}">
                                            @if ($holidayList->isNotEmpty())
                                                <div class="px-1 pt-1">
                                                    <small class="text-warning d-block" title="{{ $holidayList->pluck('keterangan')->join(' | ') }}">
                                                        {{ $holidayList->pluck('kode_libur')->join(', ') }}
                                                    </small>
                                                </div>
                                            @endif
                                            <select name="work_shift_id" class="form-select form-select-sm"
                                                onchange="this.form.submit()">
                                                <option value="">-</option>
                                                @foreach ($workShifts as $shift)
                                                    <option value="{{ $shift->id }}"
                                                        {{ $sc && $sc->work_shift_id == $shift->id ? 'selected' : '' }}>
                                                        {{ $shift->kode }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </form>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('links')
    <style>
    .schedule-calendar {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 0.5rem;
    }

    .schedule-calendar-day {
        border: 1px solid #6c757d;
        border-radius: 0.375rem;
        background: #191c24;
        color: #fff;
        min-height: 54px;
        transition: all 0.15s ease;
    }

    .schedule-calendar-day.is-label {
        background: transparent;
        border-color: transparent;
        color: #adb5bd;
        min-height: auto;
        font-size: 0.85rem;
        padding: 0.2rem 0;
        text-align: center;
        cursor: default;
    }

    .schedule-calendar-day.is-spacer {
        border-color: transparent;
        background: transparent;
        cursor: default;
    }

    .schedule-calendar-day button {
        width: 100%;
        height: 100%;
        border: none;
        border-radius: 0.375rem;
        background: transparent;
        color: inherit;
        padding: 0.55rem;
        font-size: 0.9rem;
        text-align: left;
        cursor: pointer;
    }

    .schedule-calendar-day .day-number {
        display: block;
        font-weight: 700;
        line-height: 1.2;
    }

    .schedule-calendar-day .day-date {
        display: block;
        font-size: 0.72rem;
        color: #ced4da;
        line-height: 1.2;
        margin-top: 0.15rem;
    }

    .schedule-calendar-day button:hover {
        background: #0d6efd;
    }

    .schedule-calendar-day.is-selected,
    .schedule-calendar-day.is-in-range {
        background: #0d6efd;
        border-color: #0d6efd;
    }

    .schedule-calendar-day.is-holiday {
        border-color: #dc3545;
        background: rgba(220, 53, 69, 0.18);
    }

    .schedule-calendar-day.is-holiday .day-note {
        display: block;
        font-size: 0.68rem;
        color: #ffb3bd;
        line-height: 1.2;
        margin-top: 0.2rem;
    }

    .schedule-legend {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 3px;
        margin-right: 0.35rem;
        vertical-align: -1px;
    }

    .schedule-legend-selected {
        background: #0d6efd;
    }

    .schedule-legend-holiday {
        background: rgba(220, 53, 69, 0.6);
        border: 1px solid #dc3545;
    }

    .schedule-holiday-cell {
        background: rgba(220, 53, 69, 0.08);
    }
    </style>
@endsection

@section('script')
    @php
        $holidayMap = $holidays->mapWithKeys(function ($holiday) {
            return [
                $holiday->first()->tanggal->toDateString() => $holiday->map(function ($item) {
                    return [
                        'kode' => $item->kode_libur,
                        'keterangan' => $item->keterangan,
                        'is_hari_raya' => (bool) $item->is_hari_raya,
                    ];
                })->values()->all(),
            ];
        });
    @endphp
    <script>
        (function() {
            const monthValue = @json($month);
            const calendar = document.getElementById('schedule-calendar');
            const startInput = document.getElementById('start-date-input');
            const endInput = document.getElementById('end-date-input');
            const shiftSelect = document.getElementById('mass-work-shift-select');
            const holidays = @json($holidayMap);

            if (!calendar || !startInput || !endInput || !monthValue) {
                return;
            }

            const [year, month] = monthValue.split('-').map(Number);
            const firstDay = new Date(year, month - 1, 1);
            const daysInMonth = new Date(year, month, 0).getDate();
            const startWeekday = firstDay.getDay();
            const weekdayLabels = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
            const dateLabelFormatter = new Intl.DateTimeFormat('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });

            function normalizeRange() {
                if (!startInput.value || !endInput.value) return;
                if (startInput.value > endInput.value) {
                    const swap = startInput.value;
                    startInput.value = endInput.value;
                    endInput.value = swap;
                }
            }

            function rerenderStates() {
                normalizeRange();
                const selectedStart = startInput.value;
                const selectedEnd = endInput.value;

                calendar.querySelectorAll('.schedule-calendar-day[data-date]').forEach((el) => {
                    const date = el.dataset.date;
                    el.classList.toggle('is-selected', date === selectedStart || date === selectedEnd);
                    el.classList.toggle(
                        'is-in-range',
                        !!selectedStart && !!selectedEnd && date > selectedStart && date < selectedEnd
                    );
                });
            }

            function pickDate(dateValue) {
                startInput.value = dateValue;
                endInput.value = dateValue;
                rerenderStates();

                if (shiftSelect) {
                    shiftSelect.focus();
                }
            }

            calendar.innerHTML = '';
            weekdayLabels.forEach((label) => {
                const dayLabel = document.createElement('div');
                dayLabel.className = 'schedule-calendar-day is-label';
                dayLabel.textContent = label;
                calendar.appendChild(dayLabel);
            });

            for (let i = 0; i < startWeekday; i++) {
                const spacer = document.createElement('div');
                spacer.className = 'schedule-calendar-day is-spacer';
                calendar.appendChild(spacer);
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const dayValue = String(day).padStart(2, '0');
                const dateValue = `${monthValue}-${dayValue}`;
                const dayWrap = document.createElement('div');
                dayWrap.className = 'schedule-calendar-day';
                dayWrap.dataset.date = dateValue;
                const holidayEntries = holidays[dateValue] ?? [];

                if (holidayEntries.length > 0) {
                    dayWrap.classList.add('is-holiday');
                    dayWrap.title = holidayEntries.map((item) => `${item.kode} - ${item.keterangan}`).join(' | ');
                }

                const button = document.createElement('button');
                button.type = 'button';
                button.innerHTML = `
                    <span class="day-number">${day}</span>
                    <span class="day-date">${dateLabelFormatter.format(new Date(year, month - 1, day))}</span>
                    ${holidayEntries.length > 0 ? `<span class="day-note">${holidayEntries.map((item) => item.kode).join(', ')}</span>` : ''}
                `;
                button.addEventListener('click', () => pickDate(dateValue));

                dayWrap.appendChild(button);
                calendar.appendChild(dayWrap);
            }

            startInput.addEventListener('change', () => {
                rerenderStates();
            });

            endInput.addEventListener('change', rerenderStates);
            rerenderStates();
        })();
    </script>
@endsection


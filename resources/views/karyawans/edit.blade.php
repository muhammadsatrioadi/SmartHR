@extends('layouts.main')

@section('title', 'Master Karyawan - Edit')

@section('navKaryawan')
    active
@endsection

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <h6 class="mb-4">{{ __("Master Karyawan - Edit Data") }}</h6>

            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-data-umum" data-bs-toggle="tab" data-bs-target="#pane-data-umum" type="button" role="tab">{{ __("Data Umum") }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-kepegawaian" data-bs-toggle="tab" data-bs-target="#pane-kepegawaian" type="button" role="tab">{{ __("Data Kepegawaian") }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-payroll" data-bs-toggle="tab" data-bs-target="#pane-payroll" type="button" role="tab">{{ __("Data PayRoll & TAX") }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-riwayat-kepegawaian" data-bs-toggle="tab" data-bs-target="#pane-riwayat-kepegawaian" type="button" role="tab">{{ __("Riwayat Kepegawaian") }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-riwayat-keluar" data-bs-toggle="tab" data-bs-target="#pane-riwayat-keluar" type="button" role="tab">{{ __("Riwayat Keluar") }}</button>
                </li>
            </ul>

            <form action="{{ route('karyawans.update', $karyawan->id) }}" method="POST" class="js-validate" novalidate>
                @csrf
                @method('PUT')

                <div class="tab-content">
                    {{-- Tab 1: Data Umum --}}
                    <div class="tab-pane fade show active" id="pane-data-umum" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">{{ __("NBM") }}</label>
                                    <input type="text" name="nbm" value="{{ old('nbm', $karyawan->nbm) }}" class="form-control">
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" name="is_aktif_dinas" value="1" class="form-check-input" id="is_aktif_dinas" {{ old('is_aktif_dinas', $karyawan->is_aktif_dinas) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_aktif_dinas">{{ __("Aktif Dinas") }}</label>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("NIK") }}</label>
                                    <input type="text" name="nik" value="{{ old('nik', $karyawan->nik) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Hub dengan NIK") }}</label>
                                    <input type="text" name="hub_dengan_nik" value="{{ old('hub_dengan_nik', $karyawan->hub_dengan_nik) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("NIK Utama") }}</label>
                                    <input type="text" name="nik_utama" value="{{ old('nik_utama', $karyawan->nik_utama) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Atasan Langsung (otomatis)") }}</label>
                                    <input type="text" id="atasan-otomatis" class="form-control" value="" readonly
                                        placeholder="Terisi otomatis dari manajer departemen">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Pegawai") }}</label>
                                    <input type="text" name="pegawai_text" value="{{ old('pegawai_text', $karyawan->pegawai_text) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Anak / Isteri ke-") }}</label>
                                    <input type="text" name="anak_isteri_ke" value="{{ old('anak_isteri_ke', $karyawan->anak_isteri_ke) }}" class="form-control">
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" name="ditanggung" value="1" class="form-check-input" id="ditanggung" {{ old('ditanggung', $karyawan->ditanggung) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="ditanggung">{{ __("Ditanggung") }}</label>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Nama Panggilan") }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ old('name', $karyawan->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Nama Lengkap") }}</label>
                                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $karyawan->nama_lengkap) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Email") }} <span class="text-danger">*</span></label>
                                    <input type="email" name="email" value="{{ old('email', $karyawan->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Role Akun") }} <span class="text-danger">*</span></label>
                                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                        <option value="karyawan" {{ old('role', $currentRole) == 'karyawan' ? 'selected' : '' }}>{{ __("Karyawan (Hanya lihat jadwal sendiri)") }}</option>
                                        <option value="atasan" {{ old('role', $currentRole) == 'atasan' ? 'selected' : '' }}>{{ __("Supervisor / Atasan (Bisa atur jadwal bawahan)") }}</option>
                                        <option value="manajer" {{ old('role', $currentRole) == 'manajer' ? 'selected' : '' }}>{{ __("Manajer (Approval & Statistik Tim)") }}</option>
                                        <option value="admin_hr" {{ old('role', $currentRole) == 'admin_hr' ? 'selected' : '' }}>{{ __("Admin HR (Akses Penuh)") }}</option>
                                    </select>
                                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Telephone") }} <span class="text-danger">*</span></label>
                                    <input type="text" name="telephone" value="{{ old('telephone', $karyawan->telephone) }}" class="form-control @error('telephone') is-invalid @enderror" required>
                                    @error('telephone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Handphone") }}</label>
                                    <input type="text" name="handphone" value="{{ old('handphone', $karyawan->handphone) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Jenis Kelamin") }} <span class="text-danger">*</span></label>
                                    <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                                        <option value="Laki-laki" {{ old('jenis_kelamin', $karyawan->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>{{ __("Laki-laki") }}</option>
                                        <option value="Perempuan" {{ old('jenis_kelamin', $karyawan->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>{{ __("Perempuan") }}</option>
                                    </select>
                                    @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Tempat Lahir") }}</label>
                                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $karyawan->tempat_lahir) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Tanggal Lahir") }}</label>
                                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $karyawan->tanggal_lahir ? $karyawan->tanggal_lahir->format('Y-m-d') : '') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Agama") }}</label>
                                    <input type="text" name="agama" value="{{ old('agama', $karyawan->agama) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Status Perkawinan") }}</label>
                                    <input type="text" name="status_perkawinan" value="{{ old('status_perkawinan', $karyawan->status_perkawinan) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Grup Shift Kerja") }}</label>
                                    <select name="shift_group_id" class="form-select">
                                        <option value="">-</option>
                                        @foreach($shiftGroups ?? [] as $sg)
                                            <option value="{{ $sg->id }}" {{ (string)old('shift_group_id', $karyawan->shift_group_id) === (string)$sg->id ? 'selected' : '' }}>{{ $sg->kode }} - {{ $sg->nama ?? '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("No Identitas") }}</label>
                                    <input type="text" name="no_identitas" value="{{ old('no_identitas', $karyawan->no_identitas) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Tgl ED ID") }}</label>
                                    <input type="date" name="tgl_ed_id" value="{{ old('tgl_ed_id', $karyawan->tgl_ed_id ? $karyawan->tgl_ed_id->format('Y-m-d') : '') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Nama Ayah") }}</label>
                                    <input type="text" name="nama_ayah" value="{{ old('nama_ayah', $karyawan->nama_ayah) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Nama Ibu") }}</label>
                                    <input type="text" name="nama_ibu" value="{{ old('nama_ibu', $karyawan->nama_ibu) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Ayah Mertua") }}</label>
                                    <input type="text" name="ayah_mertua" value="{{ old('ayah_mertua', $karyawan->ayah_mertua) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Ibu Mertua") }}</label>
                                    <input type="text" name="ibu_mertua" value="{{ old('ibu_mertua', $karyawan->ibu_mertua) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Alamat Tempat Tinggal") }}</label>
                                    <textarea name="alamat_tinggal" class="form-control" rows="2">{{ old('alamat_tinggal', $karyawan->alamat_tinggal) }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Tgl Menikah") }}</label>
                                    <input type="date" name="tgl_menikah" value="{{ old('tgl_menikah', $karyawan->tgl_menikah ? $karyawan->tgl_menikah->format('Y-m-d') : '') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Tgl Cerai") }}</label>
                                    <input type="date" name="tgl_cerai" value="{{ old('tgl_cerai', $karyawan->tgl_cerai ? $karyawan->tgl_cerai->format('Y-m-d') : '') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Pekerjaan Suami/Istri") }}</label>
                                    <input type="text" name="pekerjaan_suami_istri" value="{{ old('pekerjaan_suami_istri', $karyawan->pekerjaan_suami_istri) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Golongan Darah") }}</label>
                                    <input type="text" name="golongan_darah" value="{{ old('golongan_darah', $karyawan->golongan_darah) }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Alamat") }}</label>
                                    <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $karyawan->alamat) }}</textarea>
                                </div>
                                {{-- Chaining alamat Indonesia --}}
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __("Pilih Provinsi") }}</label>
                                        <select id="select-provinsi" class="form-select">
                                            <option value="">{{ __("- Pilih Provinsi -") }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __("Pilih Kabupaten/Kota") }}</label>
                                        <select id="select-kabupaten" class="form-select" disabled>
                                            <option value="">{{ __("- Pilih Kabupaten/Kota -") }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __("Pilih Kecamatan") }}</label>
                                        <select id="select-kecamatan" class="form-select" disabled>
                                            <option value="">{{ __("- Pilih Kecamatan -") }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __("Pilih Kelurahan") }}</label>
                                        <select id="select-kelurahan" class="form-select" disabled>
                                            <option value="">{{ __("- Pilih Kelurahan -") }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __("Kelurahan (otomatis)") }}</label>
                                        <input type="text" name="kelurahan" id="input-kelurahan" value="{{ old('kelurahan', $karyawan->kelurahan) }}" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __("Kecamatan (otomatis)") }}</label>
                                        <input type="text" name="kecamatan" id="input-kecamatan" value="{{ old('kecamatan', $karyawan->kecamatan) }}" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __("Kabupaten/Kota (otomatis)") }}</label>
                                        <input type="text" name="kabupaten" id="input-kabupaten" value="{{ old('kabupaten', $karyawan->kabupaten) }}" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __("Provinsi (otomatis)") }}</label>
                                        <input type="text" name="provinsi" id="input-provinsi" value="{{ old('provinsi', $karyawan->provinsi) }}" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __("Kode Pos") }}</label>
                                        <input type="text" name="kode_pos" id="input-kode-pos" value="{{ old('kode_pos', $karyawan->kode_pos) }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tab 2: Data Kepegawaian --}}
                    <div class="tab-pane fade" id="pane-kepegawaian" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">{{ __("No Kartu Pegawai") }}</label>
                                    <input type="text" name="no_kartu_pegawai" value="{{ old('no_kartu_pegawai', $karyawan->no_kartu_pegawai) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Tgl Mulai Bekerja") }}</label>
                                    <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk', $karyawan->tanggal_masuk ? $karyawan->tanggal_masuk->format('Y-m-d') : '') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Status Karyawan") }}</label>
                                    <select name="employee_status_id" class="form-select">
                                        <option value="">-</option>
                                        @foreach($employeeStatuses as $es)
                                            <option value="{{ $es->id }}" {{ (string)old('employee_status_id', $karyawan->employee_status_id) === (string)$es->id ? 'selected' : '' }}>{{ $es->kode }} - {{ $es->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                <div class="mb-3">
                                    <label class="form-label">{{ __("Jabatan") }} <span class="text-danger">*</span></label>
                                    <select name="jabatan_id" class="form-select @error('jabatan_id') is-invalid @enderror" required>
                                        @foreach($jabatans as $j)
                                            <option value="{{ $j->id }}" {{ (string)old('jabatan_id', $karyawan->jabatan_id) === (string)$j->id ? 'selected' : '' }}>{{ $j->nama_jabatan }}</option>
                                        @endforeach
                                    </select>
                                    @error('jabatan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                                    <label class="form-label">{{ __("Golongan") }}</label>
                                    <select name="golongan_id" class="form-select">
                                        <option value="">-</option>
                                        @foreach($golongans as $g)
                                            <option value="{{ $g->id }}" {{ (string)old('golongan_id', $karyawan->golongan_id) === (string)$g->id ? 'selected' : '' }}>{{ $g->kode }} - {{ $g->nama }}</option>
                                        @endforeach
                                    </select>
                </div>
                <div class="mb-3">
                                    <label class="form-label">{{ __("Pangkat") }}</label>
                                    <select name="pangkat_id" class="form-select">
                                        <option value="">-</option>
                                        @foreach($pangkats as $p)
                                            <option value="{{ $p->id }}" {{ (string)old('pangkat_id', $karyawan->pangkat_id) === (string)$p->id ? 'selected' : '' }}>{{ $p->kode }} - {{ $p->nama }}</option>
                                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                                    <label class="form-label">{{ __("No SK Penempatan") }}</label>
                                    <input type="text" name="no_sk_penempatan" value="{{ old('no_sk_penempatan', $karyawan->no_sk_penempatan) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Departemen") }} <span class="text-danger">*</span></label>
                                    <select name="department_id" id="select-departemen"
                                        class="form-select @error('department_id') is-invalid @enderror" required>
                                        <option value="">{{ __("- Pilih Departemen -") }}</option>
                                        @foreach ($departments as $d)
                                            <option value="{{ $d->id }}"
                                                data-manager-name="{{ $d->manager->name ?? '' }}"
                                                data-manager-nik="{{ $d->manager->nik ?? '' }}"
                                                {{ (string) old('department_id', $karyawan->department_id) === (string) $d->id ? 'selected' : '' }}>
                                                {{ $d->kode }} - {{ $d->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Divisi") }}</label>
                                    <input type="text" name="divisi" value="{{ old('divisi', $karyawan->divisi) }}" class="form-control">
                </div>
                <div class="mb-3">
                                    <label class="form-label">{{ __("Bagian") }}</label>
                                    <input type="text" name="bagian" value="{{ old('bagian', $karyawan->bagian) }}" class="form-control">
                </div>
                <div class="mb-3">
                                    <label class="form-label">{{ __("Unit Kerja") }}</label>
                                    <select name="work_unit_id" class="form-select">
                                        <option value="">-</option>
                                        @foreach($workUnits as $wu)
                                            <option value="{{ $wu->id }}" {{ (string)old('work_unit_id', $karyawan->work_unit_id) === (string)$wu->id ? 'selected' : '' }}>{{ $wu->kode }} - {{ $wu->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                                    <label class="form-label">{{ __("Tgl Unit Kerja") }}</label>
                                    <input type="date" name="tgl_unit_kerja" value="{{ old('tgl_unit_kerja', $karyawan->tgl_unit_kerja ? $karyawan->tgl_unit_kerja->format('Y-m-d') : '') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Tgl Orientasi Mulai") }}</label>
                                    <input type="date" name="tgl_orientasi_mulai" value="{{ old('tgl_orientasi_mulai', $karyawan->tgl_orientasi_mulai ? $karyawan->tgl_orientasi_mulai->format('Y-m-d') : '') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Tgl Orientasi Akhir") }}</label>
                                    <input type="date" name="tgl_orientasi_akhir" value="{{ old('tgl_orientasi_akhir', $karyawan->tgl_orientasi_akhir ? $karyawan->tgl_orientasi_akhir->format('Y-m-d') : '') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Tgl Kontrak 1 Mulai") }}</label>
                                    <input type="date" name="tgl_kontrak_1_mulai" value="{{ old('tgl_kontrak_1_mulai', $karyawan->tgl_kontrak_1_mulai ? $karyawan->tgl_kontrak_1_mulai->format('Y-m-d') : '') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Tgl Kontrak 1 Akhir") }}</label>
                                    <input type="date" name="tgl_kontrak_1_akhir" value="{{ old('tgl_kontrak_1_akhir', $karyawan->tgl_kontrak_1_akhir ? $karyawan->tgl_kontrak_1_akhir->format('Y-m-d') : '') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Tgl Kontrak 2 Mulai") }}</label>
                                    <input type="date" name="tgl_kontrak_2_mulai" value="{{ old('tgl_kontrak_2_mulai', $karyawan->tgl_kontrak_2_mulai ? $karyawan->tgl_kontrak_2_mulai->format('Y-m-d') : '') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Tgl Kontrak 2 Akhir") }}</label>
                                    <input type="date" name="tgl_kontrak_2_akhir" value="{{ old('tgl_kontrak_2_akhir', $karyawan->tgl_kontrak_2_akhir ? $karyawan->tgl_kontrak_2_akhir->format('Y-m-d') : '') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Tgl Pegawai Tetap") }}</label>
                                    <input type="date" name="tgl_pegawai_tetap" value="{{ old('tgl_pegawai_tetap', $karyawan->tgl_pegawai_tetap ? $karyawan->tgl_pegawai_tetap->format('Y-m-d') : '') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("No SK Tetap") }}</label>
                                    <input type="text" name="no_sk_tetap" value="{{ old('no_sk_tetap', $karyawan->no_sk_tetap) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Arsip") }}</label>
                                    <input type="text" name="arsip" value="{{ old('arsip', $karyawan->arsip) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Jaminan Polis") }}</label>
                                    <input type="text" name="jaminan_polis" value="{{ old('jaminan_polis', $karyawan->jaminan_polis) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Lokasi Tugas") }}</label>
                                    <input type="text" name="lokasi_tugas" value="{{ old('lokasi_tugas', $karyawan->lokasi_tugas) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Gelar") }}</label>
                                    <input type="text" name="gelar" value="{{ old('gelar', $karyawan->gelar) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Status (Aktif/Kontrak/dll)") }} <span class="text-danger">*</span></label>
                                    <input type="text" name="status" value="{{ old('status', $karyawan->status) }}" class="form-control @error('status') is-invalid @enderror" required>
                                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Jenis") }}</label>
                                    <input type="text" name="jenis_pegawai" value="{{ old('jenis_pegawai', $karyawan->jenis_pegawai) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Grup Kinerja") }}</label>
                                    <input type="text" name="grup_kinerja" value="{{ old('grup_kinerja', $karyawan->grup_kinerja) }}" class="form-control">
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" name="is_full_time" value="1" class="form-check-input" id="is_full_time" {{ old('is_full_time', $karyawan->is_full_time) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_full_time">{{ __("Full Time") }}</label>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("No Surat Registrasi") }}</label>
                                    <input type="text" name="no_surat_registrasi" value="{{ old('no_surat_registrasi', $karyawan->no_surat_registrasi) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Tgl Register Mulai") }}</label>
                                    <input type="date" name="tgl_register_mulai" value="{{ old('tgl_register_mulai', $karyawan->tgl_register_mulai ? $karyawan->tgl_register_mulai->format('Y-m-d') : '') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Tgl Register Akhir") }}</label>
                                    <input type="date" name="tgl_register_akhir" value="{{ old('tgl_register_akhir', $karyawan->tgl_register_akhir ? $karyawan->tgl_register_akhir->format('Y-m-d') : '') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("SK Ijin Kerja") }}</label>
                                    <input type="text" name="sk_ijin_kerja" value="{{ old('sk_ijin_kerja', $karyawan->sk_ijin_kerja) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("No SK Ijin Praktek") }}</label>
                                    <input type="text" name="no_sk_ijin_praktek" value="{{ old('no_sk_ijin_praktek', $karyawan->no_sk_ijin_praktek) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Tgl SK Ijin Praktek") }}</label>
                                    <input type="date" name="tgl_sk_ijin_praktek" value="{{ old('tgl_sk_ijin_praktek', $karyawan->tgl_sk_ijin_praktek ? $karyawan->tgl_sk_ijin_praktek->format('Y-m-d') : '') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Tgl SK Ijin Praktek Berlaku SD") }}</label>
                                    <input type="date" name="tgl_sk_ijin_praktek_berlaku_sd" value="{{ old('tgl_sk_ijin_praktek_berlaku_sd', $karyawan->tgl_sk_ijin_praktek_berlaku_sd ? $karyawan->tgl_sk_ijin_praktek_berlaku_sd->format('Y-m-d') : '') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Tgl SK Ijin Kerja") }}</label>
                                    <input type="date" name="tgl_sk_ijin_kerja" value="{{ old('tgl_sk_ijin_kerja', $karyawan->tgl_sk_ijin_kerja ? $karyawan->tgl_sk_ijin_kerja->format('Y-m-d') : '') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Tgl SK Ijin Kerja Berlaku SD") }}</label>
                                    <input type="date" name="tgl_sk_ijin_kerja_berlaku_sd" value="{{ old('tgl_sk_ijin_kerja_berlaku_sd', $karyawan->tgl_sk_ijin_kerja_berlaku_sd ? $karyawan->tgl_sk_ijin_kerja_berlaku_sd->format('Y-m-d') : '') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Hak Kelas Ranap") }}</label>
                                    <input type="text" name="hak_kelas_ranap" value="{{ old('hak_kelas_ranap', $karyawan->hak_kelas_ranap) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("No Amplop") }}</label>
                                    <input type="text" name="no_amplop" value="{{ old('no_amplop', $karyawan->no_amplop) }}" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tab 3: Data PayRoll & TAX --}}
                    <div class="tab-pane fade" id="pane-payroll" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Bank") }}</label>
                                    <input type="text" name="bank" value="{{ old('bank', $karyawan->bank) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("No Rekening") }}</label>
                                    <input type="text" name="no_rekening" value="{{ old('no_rekening', $karyawan->no_rekening) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("BPJS Kesehatan") }}</label>
                                    <input type="text" name="bpjs_kesehatan" value="{{ old('bpjs_kesehatan', $karyawan->bpjs_kesehatan) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("BPJS Ketenagakerjaan") }}</label>
                                    <input type="text" name="bpjs_ketenagakerjaan" value="{{ old('bpjs_ketenagakerjaan', $karyawan->bpjs_ketenagakerjaan) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("KTP") }} <span class="text-danger">*</span></label>
                                    <input type="text" name="ktp" value="{{ old('ktp', $karyawan->ktp) }}" class="form-control @error('ktp') is-invalid @enderror" required>
                                    @error('ktp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("NPWP") }} <span class="text-danger">*</span></label>
                                    <input type="text" name="NPWP" value="{{ old('NPWP', $karyawan->NPWP) }}" class="form-control @error('NPWP') is-invalid @enderror" required>
                                    @error('NPWP')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Hak Plafon") }}</label>
                                    <input type="text" name="hak_plafon" value="{{ old('hak_plafon', $karyawan->hak_plafon) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Nama Account") }}</label>
                                    <input type="text" name="nama_account" value="{{ old('nama_account', $karyawan->nama_account) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Jenis Profesi") }}</label>
                                    <input type="text" name="jenis_profesi" value="{{ old('jenis_profesi', $karyawan->jenis_profesi) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Keterangan Jabatan") }}</label>
                                    <input type="text" name="keterangan_jabatan" value="{{ old('keterangan_jabatan', $karyawan->keterangan_jabatan) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Tgl Daftar NPWP") }}</label>
                                    <input type="date" name="tgl_daftar_npwp" value="{{ old('tgl_daftar_npwp', $karyawan->tgl_daftar_npwp ? $karyawan->tgl_daftar_npwp->format('Y-m-d') : '') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Tanggungan Pajak") }}</label>
                                    <input type="text" name="tanggungan_pajak" value="{{ old('tanggungan_pajak', $karyawan->tanggungan_pajak) }}" class="form-control">
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" name="ptkp_tak_penuh" value="1" class="form-check-input" id="ptkp_tak_penuh" {{ old('ptkp_tak_penuh', $karyawan->ptkp_tak_penuh) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="ptkp_tak_penuh">{{ __("PTKP Tak Penuh") }}</label>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Status Kawin (Tax)") }}</label>
                                    <input type="text" name="status_kawin_tax" value="{{ old('status_kawin_tax', $karyawan->status_kawin_tax) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Total Kontak") }} <span class="text-danger">*</span></label>
                                    <input type="number" name="total_kontak" value="{{ old('total_kontak', $karyawan->total_kontak) }}" class="form-control @error('total_kontak') is-invalid @enderror" required min="0">
                                    @error('total_kontak')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tab 4: Riwayat Kepegawaian --}}
                    <div class="tab-pane fade" id="pane-riwayat-kepegawaian" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="riwayat-kepegawaian-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __("Pangkat") }}</th>
                                        <th>{{ __("Golongan") }}</th>
                                        <th>{{ __("Status") }}</th>
                                        <th>{{ __("Keterangan") }}</th>
                                        <th>{{ __("SK TMT") }}</th>
                                        <th>{{ __("Tgl Kontrak Mulai") }}</th>
                                        <th>{{ __("Tgl Kontrak Akhir") }}</th>
                                        <th>{{ __("Masa Kerja Tahun") }}</th>
                                        <th>{{ __("Masa Kerja Bulan") }}</th>
                                        <th>{{ __("Pejabat") }}</th>
                                        <th>{{ __("No SK") }}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="riwayat-kepegawaian-tbody">
                                    @php $ehIndex = 0; @endphp
                                    @foreach(old('employment_histories', $karyawan->employmentHistories ?? []) as $eh)
                                    <tr class="riwayat-row">
                                        <td><input type="text" name="employment_histories[{{ $ehIndex }}][pangkat]" value="{{ is_object($eh) ? $eh->pangkat : ($eh['pangkat'] ?? '') }}" class="form-control form-control-sm"></td>
                                        <td><input type="text" name="employment_histories[{{ $ehIndex }}][golongan]" value="{{ is_object($eh) ? $eh->golongan : ($eh['golongan'] ?? '') }}" class="form-control form-control-sm"></td>
                                        <td><input type="text" name="employment_histories[{{ $ehIndex }}][status]" value="{{ is_object($eh) ? $eh->status : ($eh['status'] ?? '') }}" class="form-control form-control-sm"></td>
                                        <td><input type="text" name="employment_histories[{{ $ehIndex }}][keterangan]" value="{{ is_object($eh) ? $eh->keterangan : ($eh['keterangan'] ?? '') }}" class="form-control form-control-sm"></td>
                                        <td><input type="date" name="employment_histories[{{ $ehIndex }}][sk_tmt]" value="{{ is_object($eh) && $eh->sk_tmt ? $eh->sk_tmt->format('Y-m-d') : (isset($eh['sk_tmt']) ? $eh['sk_tmt'] : '') }}" class="form-control form-control-sm"></td>
                                        <td><input type="date" name="employment_histories[{{ $ehIndex }}][tgl_kontrak_mulai]" value="{{ is_object($eh) && $eh->tgl_kontrak_mulai ? $eh->tgl_kontrak_mulai->format('Y-m-d') : (isset($eh['tgl_kontrak_mulai']) ? $eh['tgl_kontrak_mulai'] : '') }}" class="form-control form-control-sm"></td>
                                        <td><input type="date" name="employment_histories[{{ $ehIndex }}][tgl_kontrak_akhir]" value="{{ is_object($eh) && $eh->tgl_kontrak_akhir ? $eh->tgl_kontrak_akhir->format('Y-m-d') : (isset($eh['tgl_kontrak_akhir']) ? $eh['tgl_kontrak_akhir'] : '') }}" class="form-control form-control-sm"></td>
                                        <td><input type="number" name="employment_histories[{{ $ehIndex }}][masa_kerja_tahun]" value="{{ is_object($eh) ? $eh->masa_kerja_tahun : ($eh['masa_kerja_tahun'] ?? '') }}" class="form-control form-control-sm" min="0"></td>
                                        <td><input type="number" name="employment_histories[{{ $ehIndex }}][masa_kerja_bulan]" value="{{ is_object($eh) ? $eh->masa_kerja_bulan : ($eh['masa_kerja_bulan'] ?? '') }}" class="form-control form-control-sm" min="0"></td>
                                        <td><input type="text" name="employment_histories[{{ $ehIndex }}][pejabat]" value="{{ is_object($eh) ? $eh->pejabat : ($eh['pejabat'] ?? '') }}" class="form-control form-control-sm"></td>
                                        <td><input type="text" name="employment_histories[{{ $ehIndex }}][no_sk]" value="{{ is_object($eh) ? $eh->no_sk : ($eh['no_sk'] ?? '') }}" class="form-control form-control-sm"></td>
                                        <td><button type="button" class="btn btn-sm btn-outline-danger remove-riwayat">{{ __("Hapus") }}</button></td>
                                    </tr>
                                    @php $ehIndex++; @endphp
                                    @endforeach
                                    @if($ehIndex === 0)
                                    <tr class="riwayat-row">
                                        <td><input type="text" name="employment_histories[0][pangkat]" class="form-control form-control-sm"></td>
                                        <td><input type="text" name="employment_histories[0][golongan]" class="form-control form-control-sm"></td>
                                        <td><input type="text" name="employment_histories[0][status]" class="form-control form-control-sm"></td>
                                        <td><input type="text" name="employment_histories[0][keterangan]" class="form-control form-control-sm"></td>
                                        <td><input type="date" name="employment_histories[0][sk_tmt]" class="form-control form-control-sm"></td>
                                        <td><input type="date" name="employment_histories[0][tgl_kontrak_mulai]" class="form-control form-control-sm"></td>
                                        <td><input type="date" name="employment_histories[0][tgl_kontrak_akhir]" class="form-control form-control-sm"></td>
                                        <td><input type="number" name="employment_histories[0][masa_kerja_tahun]" class="form-control form-control-sm" min="0"></td>
                                        <td><input type="number" name="employment_histories[0][masa_kerja_bulan]" class="form-control form-control-sm" min="0"></td>
                                        <td><input type="text" name="employment_histories[0][pejabat]" class="form-control form-control-sm"></td>
                                        <td><input type="text" name="employment_histories[0][no_sk]" class="form-control form-control-sm"></td>
                                        <td><button type="button" class="btn btn-sm btn-outline-danger remove-riwayat">{{ __("Hapus") }}</button></td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="add-riwayat-row">{{ __("+ Tambah Baris") }}</button>
                        </div>
                    </div>

                    {{-- Tab 5: Riwayat Keluar --}}
                    <div class="tab-pane fade" id="pane-riwayat-keluar" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Cara Keluar") }}</label>
                                    <input type="text" name="cara_keluar" value="{{ old('cara_keluar', $karyawan->cara_keluar) }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Tgl Keluar") }}</label>
                                    <input type="date" name="tanggal_keluar" value="{{ old('tanggal_keluar', $karyawan->tanggal_keluar ? $karyawan->tanggal_keluar->format('Y-m-d') : '') }}" class="form-control">
                </div>
                <div class="mb-3">
                                    <label class="form-label">{{ __("Alasan Keluar") }}</label>
                                    <textarea name="alasan_keluar" class="form-control" rows="3">{{ old('alasan_keluar', $karyawan->alasan_keluar) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Navigasi chaining antar tab --}}
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <button type="button" class="btn btn-outline-light" id="prev-tab">{{ __("Sebelumnya") }}</button>
                    <small class="text-light-50" id="tab-step-indicator"></small>
                    <button type="button" class="btn btn-primary" id="next-tab">{{ __("Lanjut") }}</button>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('karyawans.index') }}" class="btn btn-secondary me-2">{{ __("Batal") }}</a>
                    <button type="submit" class="btn btn-primary">{{ __("Simpan") }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Atasan langsung otomatis mengikuti manajer departemen terpilih
    var selectDepartemen = document.getElementById('select-departemen');
    var atasanOtomatis = document.getElementById('atasan-otomatis');

    function syncAtasanOtomatis() {
        if (!selectDepartemen || !atasanOtomatis) return;
        var opt = selectDepartemen.options[selectDepartemen.selectedIndex];
        if (!opt || !opt.value) {
            atasanOtomatis.value = '';
            return;
        }
        var managerName = opt.getAttribute('data-manager-name') || '';
        var managerNik = opt.getAttribute('data-manager-nik') || '';
        atasanOtomatis.value = managerName ?
            (managerNik ? managerNik + ' - ' + managerName : managerName) :
            'Belum ada manajer departemen';
    }

    if (selectDepartemen) {
        selectDepartemen.addEventListener('change', syncAtasanOtomatis);
        syncAtasanOtomatis();
    }

    // Dinamis riwayat kepegawaian
    var tbody = document.getElementById('riwayat-kepegawaian-tbody');
    var rowIndex = tbody.querySelectorAll('tr.riwayat-row').length;
    document.getElementById('add-riwayat-row').addEventListener('click', function() {
        var tr = document.createElement('tr');
        tr.className = 'riwayat-row';
        tr.innerHTML = '<td><input type="text" name="employment_histories[' + rowIndex + '][pangkat]" class="form-control form-control-sm"></td>' +
            '<td><input type="text" name="employment_histories[' + rowIndex + '][golongan]" class="form-control form-control-sm"></td>' +
            '<td><input type="text" name="employment_histories[' + rowIndex + '][status]" class="form-control form-control-sm"></td>' +
            '<td><input type="text" name="employment_histories[' + rowIndex + '][keterangan]" class="form-control form-control-sm"></td>' +
            '<td><input type="date" name="employment_histories[' + rowIndex + '][sk_tmt]" class="form-control form-control-sm"></td>' +
            '<td><input type="date" name="employment_histories[' + rowIndex + '][tgl_kontrak_mulai]" class="form-control form-control-sm"></td>' +
            '<td><input type="date" name="employment_histories[' + rowIndex + '][tgl_kontrak_akhir]" class="form-control form-control-sm"></td>' +
            '<td><input type="number" name="employment_histories[' + rowIndex + '][masa_kerja_tahun]" class="form-control form-control-sm" min="0"></td>' +
            '<td><input type="number" name="employment_histories[' + rowIndex + '][masa_kerja_bulan]" class="form-control form-control-sm" min="0"></td>' +
            '<td><input type="text" name="employment_histories[' + rowIndex + '][pejabat]" class="form-control form-control-sm"></td>' +
            '<td><input type="text" name="employment_histories[' + rowIndex + '][no_sk]" class="form-control form-control-sm"></td>' +
            '<td><button type="button" class="btn btn-sm btn-outline-danger remove-riwayat">{{ __("Hapus") }}</button></td>';
        tbody.appendChild(tr);
        rowIndex++;
    });
    tbody.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-riwayat')) e.target.closest('tr').remove();
    });

    // Chaining tab (Next / Prev)
    var tabButtons = Array.prototype.slice.call(document.querySelectorAll('.nav-tabs .nav-link'));
    var prevBtn = document.getElementById('prev-tab');
    var nextBtn = document.getElementById('next-tab');
    var indicator = document.getElementById('tab-step-indicator');

    function getActiveIndex() {
        return tabButtons.findIndex(function(btn) { return btn.classList.contains('active'); });
    }

    function updateIndicator() {
        var idx = getActiveIndex();
        if (indicator && idx >= 0) {
            indicator.textContent = 'Langkah ' + (idx + 1) + ' dari ' + tabButtons.length;
        }
        if (prevBtn) prevBtn.disabled = (idx <= 0);
        if (nextBtn) nextBtn.disabled = (idx >= tabButtons.length - 1);
    }

    function goTo(index) {
        if (index < 0 || index >= tabButtons.length) return;
        tabButtons[index].click();
        setTimeout(updateIndicator, 50);
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            var idx = getActiveIndex();
            if (idx > 0) goTo(idx - 1);
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            var idx = getActiveIndex();
            if (idx < tabButtons.length - 1) {
                goTo(idx + 1);
            }
        });
    }

    updateIndicator();

    // Chaining alamat Indonesia (provinsi -> kabupaten -> kecamatan -> kelurahan)
    const selectProv = document.getElementById('select-provinsi');
    const selectKab = document.getElementById('select-kabupaten');
    const selectKec = document.getElementById('select-kecamatan');
    const selectKel = document.getElementById('select-kelurahan');
    const inputProv = document.getElementById('input-provinsi');
    const inputKab = document.getElementById('input-kabupaten');
    const inputKec = document.getElementById('input-kecamatan');
    const inputKel = document.getElementById('input-kelurahan');

    function clearSelect(sel, placeholder) {
        if (!sel) return;
        sel.innerHTML = '';
        var opt = document.createElement('option');
        opt.value = '';
        opt.textContent = placeholder;
        sel.appendChild(opt);
        sel.disabled = true;
    }

    function populateSelect(sel, items, placeholder) {
        clearSelect(sel, placeholder);
        sel.disabled = false;
        items.forEach(function(item) {
            var opt = document.createElement('option');
            opt.value = item.id;
            opt.textContent = item.name;
            sel.appendChild(opt);
        });
    }

    if (selectProv) {
        // Load provinsi dari database lokal
        fetch('{{ route('indonesia.provinces') }}')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                populateSelect(selectProv, data, '- Pilih Provinsi -');
            })
            .catch(function() {});

        selectProv.addEventListener('change', function() {
            var provId = this.value;
            inputProv.value = this.options[this.selectedIndex].text || '';
            inputKab.value = '';
            inputKec.value = '';
            inputKel.value = '';
            clearSelect(selectKab, '- Pilih Kabupaten/Kota -');
            clearSelect(selectKec, '- Pilih Kecamatan -');
            clearSelect(selectKel, '- Pilih Kelurahan -');
            if (!provId) return;
            fetch('{{ url('/indonesia/regencies') }}/' + provId)
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    populateSelect(selectKab, data, '- Pilih Kabupaten/Kota -');
                })
                .catch(function() {});
        });
    }

    if (selectKab) {
        selectKab.addEventListener('change', function() {
            var kabId = this.value;
            inputKab.value = this.options[this.selectedIndex].text || '';
            inputKec.value = '';
            inputKel.value = '';
            clearSelect(selectKec, '- Pilih Kecamatan -');
            clearSelect(selectKel, '- Pilih Kelurahan -');
            if (!kabId) return;
            fetch('{{ url('/indonesia/districts') }}/' + kabId)
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    populateSelect(selectKec, data, '- Pilih Kecamatan -');
                })
                .catch(function() {});
        });
    }

    if (selectKec) {
        selectKec.addEventListener('change', function() {
            var kecId = this.value;
            inputKec.value = this.options[this.selectedIndex].text || '';
            inputKel.value = '';
            clearSelect(selectKel, '- Pilih Kelurahan -');
            if (!kecId) return;
            fetch('{{ url('/indonesia/villages') }}/' + kecId)
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    populateSelect(selectKel, data, '- Pilih Kelurahan -');
                })
                .catch(function() {});
        });
    }

    if (selectKel) {
        selectKel.addEventListener('change', function() {
            inputKel.value = this.options[this.selectedIndex].text || '';
        });
    }
});
</script>
@endsection

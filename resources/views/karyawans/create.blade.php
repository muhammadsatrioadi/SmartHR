@extends('layouts.main')

@section('title', 'Master Pegawai - Data Baru')

@section('navKaryawan')
    active
@endsection

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <h6 class="mb-4">Setup Pegawai - Data Baru</h6>

            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-data-umum" data-bs-toggle="tab" data-bs-target="#pane-data-umum"
                        type="button" role="tab">Data Umum</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-kepegawaian" data-bs-toggle="tab" data-bs-target="#pane-kepegawaian"
                        type="button" role="tab">Data Kepegawaian</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-payroll" data-bs-toggle="tab" data-bs-target="#pane-payroll"
                        type="button" role="tab">Data PayRoll & TAX</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-riwayat-kepegawaian" data-bs-toggle="tab"
                        data-bs-target="#pane-riwayat-kepegawaian" type="button" role="tab">Riwayat Kepegawaian</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-riwayat-keluar" data-bs-toggle="tab" data-bs-target="#pane-riwayat-keluar"
                        type="button" role="tab">Riwayat Keluar</button>
                </li>
            </ul>

            <form action="{{ route('karyawans.store') }}" method="POST">
                @csrf
                <div class="tab-content">
                    {{-- Tab 1: Data Umum --}}
                    <div class="tab-pane fade show active" id="pane-data-umum" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">NBM</label>
                                    <input type="text" name="nbm" value="{{ old('nbm') }}" class="form-control">
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" name="is_aktif_dinas" value="1" class="form-check-input"
                                        id="is_aktif_dinas" {{ old('is_aktif_dinas') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_aktif_dinas">Aktif Dinas</label>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">NIK</label>
                                    <input type="text" name="nik" value="{{ old('nik') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Hub dengan NIK</label>
                                    <input type="text" name="hub_dengan_nik" value="{{ old('hub_dengan_nik') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">NIK Utama</label>
                                    <input type="text" name="nik_utama" value="{{ old('nik_utama') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Atasan Langsung</label>
                                    <select name="nik_atasan" class="form-select">
                                        <option value="">- Pilih Atasan -</option>
                                        @foreach ($allKaryawan ?? [] as $ak)
                                            <option value="{{ $ak->nik }}"
                                                {{ old('nik_atasan') == $ak->nik ? 'selected' : '' }}>
                                                {{ $ak->nik }} - {{ $ak->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Pegawai</label>
                                    <input type="text" name="pegawai_text" value="{{ old('pegawai_text') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Anak / Isteri ke-</label>
                                    <input type="text" name="anak_isteri_ke" value="{{ old('anak_isteri_ke') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" name="ditanggung" value="1" class="form-check-input"
                                        id="ditanggung" {{ old('ditanggung') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="ditanggung">Ditanggung</label>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama Panggilan <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ old('name') }}"
                                        class="form-control @error('name') is-invalid @enderror" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" value="{{ old('email') }}"
                                        class="form-control @error('email') is-invalid @enderror" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Role Akun <span class="text-danger">*</span></label>
                                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                        <option value="karyawan" {{ old('role') == 'karyawan' ? 'selected' : '' }}>Karyawan
                                            (Hanya lihat jadwal sendiri)</option>
                                        <option value="atasan" {{ old('role') == 'atasan' ? 'selected' : '' }}>Supervisor /
                                            Atasan (Bisa atur jadwal bawahan)</option>
                                        <option value="admin_hr" {{ old('role') == 'admin_hr' ? 'selected' : '' }}>Admin HR
                                            (Akses Penuh)</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Telephone <span class="text-danger">*</span></label>
                                    <input type="text" name="telephone" value="{{ old('telephone') }}"
                                        class="form-control @error('telephone') is-invalid @enderror" required>
                                    @error('telephone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Handphone</label>
                                    <input type="text" name="handphone" value="{{ old('handphone') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select name="jenis_kelamin"
                                        class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                                        <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>
                                            Laki-laki</option>
                                        <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                                            Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Agama</label>
                                    <input type="text" name="agama" value="{{ old('agama') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status Perkawinan</label>
                                    <input type="text" name="status_perkawinan" value="{{ old('status_perkawinan') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Grup Shift Kerja</label>
                                    <select name="shift_group_id" class="form-select">
                                        <option value="">-</option>
                                        @foreach ($shiftGroups ?? [] as $sg)
                                            <option value="{{ $sg->id }}"
                                                {{ old('shift_group_id') == $sg->id ? 'selected' : '' }}>{{ $sg->kode }} -
                                                {{ $sg->nama ?? '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">No Identitas</label>
                                    <input type="text" name="no_identitas" value="{{ old('no_identitas') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tgl ED ID</label>
                                    <input type="date" name="tgl_ed_id" value="{{ old('tgl_ed_id') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama Ayah</label>
                                    <input type="text" name="nama_ayah" value="{{ old('nama_ayah') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama Ibu</label>
                                    <input type="text" name="nama_ibu" value="{{ old('nama_ibu') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ayah Mertua</label>
                                    <input type="text" name="ayah_mertua" value="{{ old('ayah_mertua') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ibu Mertua</label>
                                    <input type="text" name="ibu_mertua" value="{{ old('ibu_mertua') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Alamat Tempat Tinggal</label>
                                    <textarea name="alamat_tinggal" class="form-control" rows="2">{{ old('alamat_tinggal') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tgl Menikah</label>
                                    <input type="date" name="tgl_menikah" value="{{ old('tgl_menikah') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tgl Cerai</label>
                                    <input type="date" name="tgl_cerai" value="{{ old('tgl_cerai') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Pekerjaan Suami/Istri</label>
                                    <input type="text" name="pekerjaan_suami_istri"
                                        value="{{ old('pekerjaan_suami_istri') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Golongan Darah</label>
                                    <input type="text" name="golongan_darah" value="{{ old('golongan_darah') }}"
                                        class="form-control" placeholder="A/B/AB/O">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <textarea name="alamat" class="form-control" rows="2">{{ old('alamat') }}</textarea>
                                </div>
                                {{-- Chaining alamat Indonesia --}}
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Pilih Provinsi</label>
                                        <select id="select-provinsi" class="form-select">
                                            <option value="">- Pilih Provinsi -</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Pilih Kabupaten/Kota</label>
                                        <select id="select-kabupaten" class="form-select" disabled>
                                            <option value="">- Pilih Kabupaten/Kota -</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Pilih Kecamatan</label>
                                        <select id="select-kecamatan" class="form-select" disabled>
                                            <option value="">- Pilih Kecamatan -</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Pilih Kelurahan</label>
                                        <select id="select-kelurahan" class="form-select" disabled>
                                            <option value="">- Pilih Kelurahan -</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kelurahan (otomatis)</label>
                                        <input type="text" name="kelurahan" id="input-kelurahan"
                                            value="{{ old('kelurahan') }}" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kecamatan (otomatis)</label>
                                        <input type="text" name="kecamatan" id="input-kecamatan"
                                            value="{{ old('kecamatan') }}" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kabupaten/Kota (otomatis)</label>
                                        <input type="text" name="kabupaten" id="input-kabupaten"
                                            value="{{ old('kabupaten') }}" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Provinsi (otomatis)</label>
                                        <input type="text" name="provinsi" id="input-provinsi"
                                            value="{{ old('provinsi') }}" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kode Pos</label>
                                        <input type="text" name="kode_pos" id="input-kode-pos"
                                            value="{{ old('kode_pos') }}" class="form-control">
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
                                    <label class="form-label">No Kartu Pegawai</label>
                                    <input type="text" name="no_kartu_pegawai" value="{{ old('no_kartu_pegawai') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tgl Mulai Bekerja</label>
                                    <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status Pegawai RS</label>
                                    <select name="employee_status_id" class="form-select">
                                        <option value="">-</option>
                                        @foreach ($employeeStatuses as $es)
                                            <option value="{{ $es->id }}"
                                                {{ old('employee_status_id') == $es->id ? 'selected' : '' }}>{{ $es->kode }}
                                                - {{ $es->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                                    <select name="jabatan_id" class="form-select @error('jabatan_id') is-invalid @enderror"
                                        required>
                                        @foreach ($jabatans as $j)
                                            <option value="{{ $j->id }}"
                                                {{ old('jabatan_id') == $j->id ? 'selected' : '' }}>{{ $j->nama_jabatan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('jabatan_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Golongan</label>
                                    <select name="golongan_id" class="form-select">
                                        <option value="">-</option>
                                        @foreach ($golongans as $g)
                                            <option value="{{ $g->id }}"
                                                {{ old('golongan_id') == $g->id ? 'selected' : '' }}>{{ $g->kode }} -
                                                {{ $g->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Pangkat</label>
                                    <select name="pangkat_id" class="form-select">
                                        <option value="">-</option>
                                        @foreach ($pangkats as $p)
                                            <option value="{{ $p->id }}"
                                                {{ old('pangkat_id') == $p->id ? 'selected' : '' }}>{{ $p->kode }} -
                                                {{ $p->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">No SK Penempatan</label>
                                    <input type="text" name="no_sk_penempatan" value="{{ old('no_sk_penempatan') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Divisi</label>
                                    <input type="text" name="divisi" value="{{ old('divisi') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Bagian</label>
                                    <input type="text" name="bagian" value="{{ old('bagian') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Unit Kerja</label>
                                    <select name="work_unit_id" class="form-select">
                                        <option value="">-</option>
                                        @foreach ($workUnits as $wu)
                                            <option value="{{ $wu->id }}"
                                                {{ old('work_unit_id') == $wu->id ? 'selected' : '' }}>{{ $wu->kode }} -
                                                {{ $wu->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tgl Unit Kerja</label>
                                    <input type="date" name="tgl_unit_kerja" value="{{ old('tgl_unit_kerja') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tgl Orientasi Mulai</label>
                                    <input type="date" name="tgl_orientasi_mulai" value="{{ old('tgl_orientasi_mulai') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tgl Orientasi Akhir</label>
                                    <input type="date" name="tgl_orientasi_akhir" value="{{ old('tgl_orientasi_akhir') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tgl Kontrak 1 Mulai</label>
                                    <input type="date" name="tgl_kontrak_1_mulai" value="{{ old('tgl_kontrak_1_mulai') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tgl Kontrak 1 Akhir</label>
                                    <input type="date" name="tgl_kontrak_1_akhir" value="{{ old('tgl_kontrak_1_akhir') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tgl Kontrak 2 Mulai</label>
                                    <input type="date" name="tgl_kontrak_2_mulai" value="{{ old('tgl_kontrak_2_mulai') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tgl Kontrak 2 Akhir</label>
                                    <input type="date" name="tgl_kontrak_2_akhir" value="{{ old('tgl_kontrak_2_akhir') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tgl Pegawai Tetap</label>
                                    <input type="date" name="tgl_pegawai_tetap" value="{{ old('tgl_pegawai_tetap') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">No SK Tetap</label>
                                    <input type="text" name="no_sk_tetap" value="{{ old('no_sk_tetap') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Arsip</label>
                                    <input type="text" name="arsip" value="{{ old('arsip') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jaminan Polis</label>
                                    <input type="text" name="jaminan_polis" value="{{ old('jaminan_polis') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Lokasi Tugas</label>
                                    <input type="text" name="lokasi_tugas" value="{{ old('lokasi_tugas') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Gelar</label>
                                    <input type="text" name="gelar" value="{{ old('gelar') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status (Aktif/Kontrak/dll) <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="status" value="{{ old('status') }}"
                                        class="form-control @error('status') is-invalid @enderror" required>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jenis</label>
                                    <input type="text" name="jenis_pegawai" value="{{ old('jenis_pegawai') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Grup Kinerja</label>
                                    <input type="text" name="grup_kinerja" value="{{ old('grup_kinerja') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" name="is_full_time" value="1" class="form-check-input"
                                        id="is_full_time" {{ old('is_full_time') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_full_time">Full Time</label>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">No Surat Registrasi</label>
                                    <input type="text" name="no_surat_registrasi" value="{{ old('no_surat_registrasi') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tgl Register Mulai</label>
                                    <input type="date" name="tgl_register_mulai" value="{{ old('tgl_register_mulai') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tgl Register Akhir</label>
                                    <input type="date" name="tgl_register_akhir" value="{{ old('tgl_register_akhir') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">SK Ijin Kerja</label>
                                    <input type="text" name="sk_ijin_kerja" value="{{ old('sk_ijin_kerja') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">No SK Ijin Praktek</label>
                                    <input type="text" name="no_sk_ijin_praktek" value="{{ old('no_sk_ijin_praktek') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tgl SK Ijin Praktek</label>
                                    <input type="date" name="tgl_sk_ijin_praktek" value="{{ old('tgl_sk_ijin_praktek') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tgl SK Ijin Praktek Berlaku SD</label>
                                    <input type="date" name="tgl_sk_ijin_praktek_berlaku_sd"
                                        value="{{ old('tgl_sk_ijin_praktek_berlaku_sd') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tgl SK Ijin Kerja</label>
                                    <input type="date" name="tgl_sk_ijin_kerja" value="{{ old('tgl_sk_ijin_kerja') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tgl SK Ijin Kerja Berlaku SD</label>
                                    <input type="date" name="tgl_sk_ijin_kerja_berlaku_sd"
                                        value="{{ old('tgl_sk_ijin_kerja_berlaku_sd') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Hak Kelas Ranap</label>
                                    <input type="text" name="hak_kelas_ranap" value="{{ old('hak_kelas_ranap') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">No Amplop</label>
                                    <input type="text" name="no_amplop" value="{{ old('no_amplop') }}"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tab 3: Data PayRoll & TAX --}}
                    <div class="tab-pane fade" id="pane-payroll" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Bank</label>
                                    <input type="text" name="bank" value="{{ old('bank') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">No Rekening</label>
                                    <input type="text" name="no_rekening" value="{{ old('no_rekening') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">BPJS Kesehatan</label>
                                    <input type="text" name="bpjs_kesehatan" value="{{ old('bpjs_kesehatan') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">BPJS Ketenagakerjaan</label>
                                    <input type="text" name="bpjs_ketenagakerjaan"
                                        value="{{ old('bpjs_ketenagakerjaan') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">KTP <span class="text-danger">*</span></label>
                                    <input type="text" name="ktp" value="{{ old('ktp') }}"
                                        class="form-control @error('ktp') is-invalid @enderror" required>
                                    @error('ktp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">NPWP <span class="text-danger">*</span></label>
                                    <input type="text" name="NPWP" value="{{ old('NPWP') }}"
                                        class="form-control @error('NPWP') is-invalid @enderror" required>
                                    @error('NPWP')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Hak Plafon</label>
                                    <input type="text" name="hak_plafon" value="{{ old('hak_plafon') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama Account</label>
                                    <input type="text" name="nama_account" value="{{ old('nama_account') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jenis Profesi</label>
                                    <input type="text" name="jenis_profesi" value="{{ old('jenis_profesi') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Keterangan Jabatan</label>
                                    <input type="text" name="keterangan_jabatan" value="{{ old('keterangan_jabatan') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tgl Daftar NPWP</label>
                                    <input type="date" name="tgl_daftar_npwp" value="{{ old('tgl_daftar_npwp') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tanggungan Pajak</label>
                                    <input type="text" name="tanggungan_pajak" value="{{ old('tanggungan_pajak') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" name="ptkp_tak_penuh" value="1" class="form-check-input"
                                        id="ptkp_tak_penuh" {{ old('ptkp_tak_penuh') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="ptkp_tak_penuh">PTKP Tak Penuh</label>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status Kawin (Tax)</label>
                                    <input type="text" name="status_kawin_tax" value="{{ old('status_kawin_tax') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Total Kontak <span class="text-danger">*</span></label>
                                    <input type="number" name="total_kontak" value="{{ old('total_kontak', 0) }}"
                                        class="form-control @error('total_kontak') is-invalid @enderror" required
                                        min="0">
                                    @error('total_kontak')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
                                        <th>Pangkat</th>
                                        <th>Golongan</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                        <th>SK TMT</th>
                                        <th>Tgl Kontrak Mulai</th>
                                        <th>Tgl Kontrak Akhir</th>
                                        <th>Masa Kerja Tahun</th>
                                        <th>Masa Kerja Bulan</th>
                                        <th>Pejabat</th>
                                        <th>No SK</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="riwayat-kepegawaian-tbody">
                                    <tr class="riwayat-row">
                                        <td><input type="text" name="employment_histories[0][pangkat]"
                                                class="form-control form-control-sm"></td>
                                        <td><input type="text" name="employment_histories[0][golongan]"
                                                class="form-control form-control-sm"></td>
                                        <td><input type="text" name="employment_histories[0][status]"
                                                class="form-control form-control-sm"></td>
                                        <td><input type="text" name="employment_histories[0][keterangan]"
                                                class="form-control form-control-sm"></td>
                                        <td><input type="date" name="employment_histories[0][sk_tmt]"
                                                class="form-control form-control-sm"></td>
                                        <td><input type="date" name="employment_histories[0][tgl_kontrak_mulai]"
                                                class="form-control form-control-sm"></td>
                                        <td><input type="date" name="employment_histories[0][tgl_kontrak_akhir]"
                                                class="form-control form-control-sm"></td>
                                        <td><input type="number" name="employment_histories[0][masa_kerja_tahun]"
                                                class="form-control form-control-sm" min="0"></td>
                                        <td><input type="number" name="employment_histories[0][masa_kerja_bulan]"
                                                class="form-control form-control-sm" min="0"></td>
                                        <td><input type="text" name="employment_histories[0][pejabat]"
                                                class="form-control form-control-sm"></td>
                                        <td><input type="text" name="employment_histories[0][no_sk]"
                                                class="form-control form-control-sm"></td>
                                        <td><button type="button"
                                                class="btn btn-sm btn-outline-danger remove-riwayat">Hapus</button></td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="add-riwayat-row">+ Tambah
                                Baris</button>
                        </div>
                    </div>

                    {{-- Tab 5: Riwayat Keluar --}}
                    <div class="tab-pane fade" id="pane-riwayat-keluar" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Cara Keluar</label>
                                    <input type="text" name="cara_keluar" value="{{ old('cara_keluar') }}"
                                        class="form-control" placeholder="Resign / PHK / dll">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tgl Keluar</label>
                                    <input type="date" name="tanggal_keluar" value="{{ old('tanggal_keluar') }}"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Alasan Keluar</label>
                                    <textarea name="alasan_keluar" class="form-control" rows="3">{{ old('alasan_keluar') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Navigasi chaining antar tab --}}
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <button type="button" class="btn btn-outline-light" id="prev-tab">Sebelumnya</button>
                    <small class="text-light-50" id="tab-step-indicator"></small>
                    <button type="button" class="btn btn-primary" id="next-tab">Lanjut</button>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('karyawans.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dinamis riwayat kepegawaian
            let rowIndex = 1;
            document.getElementById('add-riwayat-row').addEventListener('click', function() {
                var tbody = document.getElementById('riwayat-kepegawaian-tbody');
                var tr = document.createElement('tr');
                tr.className = 'riwayat-row';
                tr.innerHTML = '<td><input type="text" name="employment_histories[' + rowIndex +
                    '][pangkat]" class="form-control form-control-sm"></td>' +
                    '<td><input type="text" name="employment_histories[' + rowIndex +
                    '][golongan]" class="form-control form-control-sm"></td>' +
                    '<td><input type="text" name="employment_histories[' + rowIndex +
                    '][status]" class="form-control form-control-sm"></td>' +
                    '<td><input type="text" name="employment_histories[' + rowIndex +
                    '][keterangan]" class="form-control form-control-sm"></td>' +
                    '<td><input type="date" name="employment_histories[' + rowIndex +
                    '][sk_tmt]" class="form-control form-control-sm"></td>' +
                    '<td><input type="date" name="employment_histories[' + rowIndex +
                    '][tgl_kontrak_mulai]" class="form-control form-control-sm"></td>' +
                    '<td><input type="date" name="employment_histories[' + rowIndex +
                    '][tgl_kontrak_akhir]" class="form-control form-control-sm"></td>' +
                    '<td><input type="number" name="employment_histories[' + rowIndex +
                    '][masa_kerja_tahun]" class="form-control form-control-sm" min="0"></td>' +
                    '<td><input type="number" name="employment_histories[' + rowIndex +
                    '][masa_kerja_bulan]" class="form-control form-control-sm" min="0"></td>' +
                    '<td><input type="text" name="employment_histories[' + rowIndex +
                    '][pejabat]" class="form-control form-control-sm"></td>' +
                    '<td><input type="text" name="employment_histories[' + rowIndex +
                    '][no_sk]" class="form-control form-control-sm"></td>' +
                    '<td><button type="button" class="btn btn-sm btn-outline-danger remove-riwayat">Hapus</button></td>';
                tbody.appendChild(tr);
                rowIndex++;
            });
            document.getElementById('riwayat-kepegawaian-tbody').addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-riwayat')) e.target.closest('tr').remove();
            });

            // Chaining tab (Next / Prev)
            var tabButtons = Array.prototype.slice.call(document.querySelectorAll('.nav-tabs .nav-link'));
            var prevBtn = document.getElementById('prev-tab');
            var nextBtn = document.getElementById('next-tab');
            var indicator = document.getElementById('tab-step-indicator');

            function getActiveIndex() {
                return tabButtons.findIndex(function(btn) {
                    return btn.classList.contains('active');
                });
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
                    .then(function(r) {
                        return r.json();
                    })
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
                        .then(function(r) {
                            return r.json();
                        })
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
                        .then(function(r) {
                            return r.json();
                        })
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
                        .then(function(r) {
                            return r.json();
                        })
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

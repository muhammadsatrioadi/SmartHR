@extends('layouts.main')

@section('title', 'Manajemen Reimbursement Nota')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Daftar Pengajuan Reimburse</h4>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Pegawai</th>
                                <th>Tanggal</th>
                                <th>Nominal</th>
                                <th>Keterangan</th>
                                <th>Lampiran</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $row)
                                <tr>
                                    <td>
                                        <strong>{{ $row->karyawan->name }}</strong><br>
                                        <small>{{ $row->karyawan->nik }}</small>
                                    </td>
                                    <td>{{ $row->tanggal->format('d/m/Y') }}</td>
                                    <td>Rp {{ number_format($row->nominal, 0, ',', '.') }}</td>
                                    <td>{{ $row->keterangan }}</td>
                                    <td>
                                        @if($row->lampiran)
                                            <a href="{{ asset('storage/' . $row->lampiran) }}" target="_blank" class="btn btn-sm btn-info">Lihat Nota</a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $row->status === 'disetujui' ? 'success' : ($row->status === 'ditolak' ? 'danger' : 'warning') }}">
                                            {{ strtoupper(str_replace('_', ' ', $row->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($row->status === 'pending' && auth()->user()->role === 'atasan')
                                            <form action="{{ route('reimbursement.approveSupervisor', $row->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-success">Setuju (Atasan)</button>
                                            </form>
                                        @elseif($row->status === 'disetujui_atasan' && auth()->user()->role === 'admin_hr')
                                            <form action="{{ route('reimbursement.approveHr', $row->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-success">Setuju (HR)</button>
                                            </form>
                                        @endif

                                        @if(in_array($row->status, ['pending', 'disetujui_atasan']))
                                            <button class="btn btn-sm btn-danger btn-reject" data-id="{{ $row->id }}">Tolak</button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada pengajuan reimburse.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="rejectForm" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Reimbursement</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Alasan Penolakan</label>
                        <textarea name="alasan" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$('.btn-reject').click(function() {
    const id = $(this).data('id');
    $('#rejectForm').attr('action', `/reimbursements/${id}/reject`);
    $('#rejectModal').modal('show');
});
</script>
@endpush

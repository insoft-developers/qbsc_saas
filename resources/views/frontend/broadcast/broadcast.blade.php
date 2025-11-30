@extends('frontend.master')

@section('content')
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">

                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0 fw-bold">Data Broadcast</h4>
                    @if(Auth::user()->level == 'owner')
                    <button type="button" class="btn btn-info btn-sm rounded-pill" onclick="tambah_data()">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Pesan
                    </button>
                    @endif
                </div>

                <!-- Data Table -->
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="list-table" class="table table-striped table-bordered w-100 align-middle nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th class="text-center" width="5%">No</th>
                                        <th class="text-center">Aksi</th>
                                        <th>Tanggal</th>
                                        <th>Judul</th>
                                        <th>Pesan</th>
                                        <th>Gambar</th>
                                        <th>Pengirim</th>
                                        <th>Status</th>
                                        <th>Perusahaan</th>
                                        
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div> <!-- end container-fluid -->
        </div> <!-- end content -->

        @include('frontend.footer')
    </div>
    <!-- ============================================================== -->
    <!-- End Page Content -->
    <!-- ============================================================== -->

    @include('frontend.broadcast.modal')
@endsection

@push('scripts')
    @include('frontend.broadcast.broadcast_js')
@endpush

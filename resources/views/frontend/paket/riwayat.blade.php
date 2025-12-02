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
                    <h4 class="mb-0 fw-bold">Riwayat Pembelian</h4>

                </div>

                <!-- Data Table -->
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        @if($com->paket_id !== null)
                        <div class="card border-primary border">
                            <div class="card-header">
                                <h4 class="text-info">Paket Aktif <i class="bi bi-check-circle-fill"></i></h4>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-primary">{{ $com->paket->nama_paket ?? '' }}</h5>
                                <p class="card-text">Expired Date :
                                    <strong>{{ date('d F Y', strtotime($com->expired_date)) }}</strong></p>
                                @if ($com->is_active == 1)
                                    @if (now()->gt($com->expired_date))
                                        <a href="javascript: void(0);" class="btn btn-danger btn-sm">Expired</a>
                                    @else
                                        <a href="javascript: void(0);" class="btn btn-success btn-sm">Active</a>
                                    @endif
                                @else
                                    <a href="javascript: void(0);" class="btn btn-danger btn-sm">Not Active</a>
                                @endif
                            </div> <!-- end card-body-->
                        </div>
                        @else
                            <div class="card border-danger border">
                            <div class="card-body">
                            <center><h4 class="text-danger">Anda Belum Memiliki Paket Aktif !!</h4><br><a href="{{ url('paket_langganan') }}">Beli Paket Sekarang</a></center>
                            </div>
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table id="list-table" class="table table-striped table-bordered w-100 align-middle nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th class="text-center" width="5%">No</th>
                                        <th class="text-center">Aksi</th>
                                        <th>Tanggal</th>
                                        <th>Invoice</th>
                                        <th>Nama Paket</th>
                                        <th>User</th>
                                        <th>Perusahaan</th>
                                        <th>Harga</th>
                                        <th>Pembayaran</th>
                                        <th>Status</th>
                                        <th>Kode Bayar</th>
                                        <th>Tgl Bayar</th>
                                        <th>Referensi</th>

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
@endsection

@push('scripts')
    @include('frontend.paket.riwayat_js')
@endpush

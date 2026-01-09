@extends('reseller.master')

@section('reseller')
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">

                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0 fw-bold">Data Withdraw Anda</h4>
                    <button type="button" class="btn btn-info btn-sm rounded-pill" onclick="tambah_data()">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Withdraw
                    </button>
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
                                        <th>Reseller</th>
                                        <th>Invoice</th>
                                        <th>Jumlah</th>
                                        <th>Rekening</th>
                                        <th>Keterangan</th>
                                        <th>Status</th>
                                        <th>Bukti Transfer QBSC</th>
                                        <th>Tanggal</th>
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

  @include('reseller.withdraw.modal')
@endsection

@push('reseller_scripts')
    @include('reseller.withdraw.withdraw_js')
@endpush

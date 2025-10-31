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
                    <h4 class="mb-0 fw-bold">Pengaturan Lokasi Absen</h4>
                    
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
                                        <th>Nama Lokasi</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Jarak Maks.</th>
                                        <th>Company</th>
                                        
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

    @include('frontend.setting.absen.modal')
@endsection

@push('scripts')
    @include('frontend.setting.absen.absen_js')
@endpush

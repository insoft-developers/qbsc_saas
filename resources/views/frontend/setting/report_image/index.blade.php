@extends('frontend.master')

@section('content')
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0 fw-bold">Report Image Setting</h4>

                </div>

                <!-- Data Table -->
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Show Image in Export Report</label>
                                    <select class="form-control" id="report_image_setting_status"
                                        name="report_image_setting_status">
                                        <option <?php if($data->export_report_with_image == "" || $data->export_report_with_image == null) echo 'selected' ;?> value="">No</option>
                                        <option <?php if($data->export_report_with_image =='1') echo 'selected' ;?> value="1">Yes</option>

                                    </select>
                                    <br>
                                    <small class="text-danger"><strong>Jika anda pilih [Yes] maka sewaktu anda export Laporan
                                            baik itu Excel maupun PDF maka Gambar akan ikut di export juga dengan
                                            konsekwensi Proses Export akan memakan waktu yang lama atau akan timeout jika
                                            data terlalu banyak. <br>Sebaliknya Jika Pilih [No] Maka hanya text saja yang di
                                            export di laporan</strong></small>
                                </div>
                            </div>
                        </div>

                        <br>
                        <button id="btn-save-report-image" class="btn btn-success btn-sm">Simpan Perubahan </button>
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
    @include('frontend.setting.report_image.js')
@endpush

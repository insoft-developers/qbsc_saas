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
                    <h4 class="mb-0 fw-bold">Pengaturan Running Text</h4>
                   
                </div>

                <!-- Data Table -->
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Running Text</label>
                            <textarea class="form-control" id="running_text_id">{{ $running_text }}</textarea>
                            <small class="text-danger"><strong>Buat atau Ganti Text ini sesuai dengan kebutuhan anda. Text ini akan tampil sebagai text berjalan di aplikasi satpam</strong></small>
                        </div>
                        <br>
                        <button id="btn-running-text" class="btn btn-success btn-sm">Buat/Update Running Text</button>
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
    @include('frontend.setting.running_text.running_text_js')
@endpush

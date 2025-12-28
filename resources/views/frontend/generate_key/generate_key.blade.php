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
                    <h4 class="mb-0 fw-bold">Generate Key ID</h4>
                   
                </div>

                <!-- Data Table -->
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="form-group">
                            <label>User Key ID</label>
                            <input readonly value="{{ $company->user_key_id }}" type="text" class="form-control" id="user_key_id">
                            <small class="text-danger"><strong>Klik tombol generate untuk membuat key id dan bisa di bagikan kepada administrator untuk keperluan monitoring User Area agar bisa memonitor segala aktifitas user yang tergabung dalam perusahaan Anda. Jangan bagikan kepada pihak yang tidak memiliki otoritas atas perusahaan anda.</strong></small>
                        </div>
                        <br>
                        <button id="btn-generate" class="btn btn-success btn-sm">Generate</button>
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
    @include('frontend.generate_key.generate_key_js')
@endpush

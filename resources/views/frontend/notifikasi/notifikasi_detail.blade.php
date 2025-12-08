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
                    <h4 class="mb-0 fw-bold">Notifikasi </h4>

                </div>

                <!-- Data Table -->
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        @if ($data->image == null)
                        @else
                            <img class="img-fluid" style="width:100%" src="{{ asset('storage/' . $data->image) }}" alt="image notifikasi">
                        @endif
                    
                        <h4 style="margin-top: 20px;">{{ $data->judul }}</h4>

                        <div><?= $data->pesan ?></div>
                        <hr />
                        <small><strong>{{ $data->pengirim }}</strong> - {{ date('d F Y H:i', strtotime($data->created_at)) }}</small>
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

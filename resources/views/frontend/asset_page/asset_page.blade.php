@extends('frontend.master')

@section('content')
    <style>
        .download-card {
            border-radius: 16px;
            transition: all .3s ease;
        }

        .download-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, .15);
        }

        .download-card .btn {
            border-radius: 12px;
            font-weight: 600;
        }

        .img-asset-download {
            height: 200px;
            width: 100px;
            background: black;
            padding: 7px 0px;
            object-fit: contain;
            border-radius: 10px;
        }
    </style>
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">

                {{-- HEADER --}}
                <div class="text-center mb-5">
                    <h2 class="fw-bold mb-2">Download Aplikasi Mobile QBSC</h2>
                    <p class="text-muted">
                        Aplikasi patroli satpam & operasional keamanan berbasis Android
                    </p>
                </div>



                {{-- DOWNLOAD LIST --}}
                <div class="row g-4">

                    {{-- CARD --}}
                    @foreach ($data as $d)
                        <div class="col-lg-4 col-md-6">
                            <div class="card download-card h-100 border-0 shadow-sm">
                                <div class="card-body d-flex flex-column">



                                    <h5 class="fw-bold mb-1">{{ $d->asset_name }}</h5>
                                    <small class="text-muted mb-3">Released:
                                        {{ date('d F Y', strtotime($d->created_at)) }}</small>

                                    <p class="text-muted flex-grow-1">
                                        {{ $d->asset_description }}
                                    </p>
                                    <img class="img-asset-download" src="{{ asset('images/' . $d->icon) }}">
                                    <div class="d-grid gap-2 mt-3">

                                        {{-- ANDROID --}}
                                        <a href="{{ $d->android_link }}" class="btn btn-success btn-lg">
                                            <i class="bi bi-android2 me-2"></i> Download Android
                                        </a>

                                        <button class="btn btn-outline-success android-link" data-link="{{ $d->android_link }}">
                                            <i class="bi bi-clipboard me-2"></i> Copy Link Android
                                        </button>
                                        <div style="margin-top: 10px;"></div>
                                        {{-- IOS --}}
                                        @if ($d->ios_link)
                                            <a href="{{ $d->ios_link }}" class="btn btn-dark btn-lg">
                                                <i class="bi bi-apple me-2"></i> Download iOS
                                            </a>

                                            <button class="btn btn-outline-dark ios-link" data-link="{{ $d->ios_link }}"
                                                >
                                                <i class="bi bi-clipboard me-2"></i> Copy Link iOS
                                            </button>
                                        @else
                                            <button disabled class="btn btn-dark btn-lg">
                                                <i class="bi bi-apple me-2"></i> Download iOS
                                            </button>
                                            <button disabled class="btn btn-outline-dark">
                                                <i class="bi bi-clipboard me-2"></i> Copy Link iOS
                                            </button>
                                        @endif

                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach


                </div>

                {{-- INFO SECTION --}}
                <div class="row justify-content-center mt-5">
                    <div class="col-lg-8">
                        <div class="alert alert-info border-0 shadow-sm text-center">
                            <i class="bi bi-info-circle me-2"></i>
                            Gunakan aplikasi sesuai hak akses yang diberikan oleh administrator sistem QBSC
                        </div>
                    </div>
                </div>

            </div>
        </div>

        @include('frontend.footer')
    </div>
@endsection
@push('scripts')
    @include('frontend.asset_page.asset_page_js')
@endpush


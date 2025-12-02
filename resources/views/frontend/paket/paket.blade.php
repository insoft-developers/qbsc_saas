@extends('frontend.master')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">

                <div class="text-center mb-5">
                    <h2 class="fw-bold mb-1">Paket Berlangganan</h2>
                    <p class="text-muted fs-6">Pilih paket yang sesuai dengan kebutuhan bisnis Anda.</p>
                </div>

                <div class="row justify-content-center">

                    {{-- Professional Pack --}}
                    <div class="col-xl-4 col-lg-5 col-md-6 mb-4">
                        <div class="card pricing-card h-100 shadow-sm border-0 rounded-4">
                            <div class="card-body p-4 d-flex flex-column">

                                <span
                                    class="paket-title badge bg-info-subtle text-info px-3 py-2 rounded-pill fw-semibold mb-3">
                                    Paket Farm M-Basic
                                </span>


                                <h2 class="fw-bold mb-0">Rp. 249.000 <span class="fs-6 fw-medium">/ bulan</span></h2>
                                <p class="text-muted">Paket ideal untuk kebutuhan dasar Kontrol Keamanan Anda.</p>

                                <ul class="list-unstyled flex-grow-1 mt-3 mb-4">
                                    <x-pricing-check text="Absen Scan Wajah + GPS" />
                                    <x-pricing-check text="4 Personil Satpam" />
                                    <x-pricing-check text="5 Titik Lokasi" />
                                    <x-pricing-check text="Cek Kontrol 6 Kandang" />
                                    <x-pricing-check text="Catat Pengeluaran DOC" />
                                    <x-pricing-minus text="Broadcast Message" />
                                    <x-pricing-check text="1 User Admin" />
                                    <x-pricing-check text="Input Tamu Manual" />
                                    <x-pricing-minus text="Scan QR Tamu" />
                                    <x-pricing-minus text="Area User" />
                                    <x-pricing-minus text="Mobile Monitoring App" />
                                    <x-pricing-check text="24/7 Support" />
                                    <x-pricing-check text="Email Support" />
                                    <x-pricing-minus text="Support Via Google Meet" />
                                    <x-pricing-minus text="Custom Request Fitur" />
                                </ul>

                                <button onclick="beli_paket(1)" class="btn btn-info w-100 fw-semibold rounded-3 mt-auto">
                                    Pilih Paket
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Business Pack --}}
                    <div class="col-xl-4 col-lg-5 col-md-6 mb-4">
                        <div class="card pricing-card h-100 shadow-lg border-0 rounded-4 premium-card">
                            <div class="card-body p-4 d-flex flex-column">

                                <span
                                    class="paket-title badge bg-danger-subtle text-danger px-3 py-2 rounded-pill fw-semibold mb-3">
                                    Paket Farm M-Medium
                                </span>



                                <h2 class="fw-bold mb-0">Rp. 369.000 <span class="fs-6 fw-medium">/ bulan</span></h2>
                                <p class="text-muted">Paket ideal untuk kebutuhan lanjut Kontrol Keamanan Anda.</p>

                                <ul class="list-unstyled flex-grow-1 mt-3 mb-4">
                                    <x-pricing-check text="Absen Scan Wajah + GPS" />
                                    <x-pricing-check text="8 Personil Satpam" />
                                    <x-pricing-check text="8 Titik Lokasi" />
                                    <x-pricing-check text="Cek Kontrol 8 Kandang" />
                                    <x-pricing-check text="Catat Pengeluaran DOC" />
                                    <x-pricing-minus text="Broadcast Message" />
                                    <x-pricing-check text="5 User Admin" />
                                    <x-pricing-check text="Input Tamu Manual" />
                                    <x-pricing-minus text="Scan QR Tamu" />
                                    <x-pricing-minus text="Area User" />
                                    <x-pricing-check text="Mobile Monitoring App" />
                                    <x-pricing-check text="24/7 Support" />
                                    <x-pricing-check text="Email Support" />
                                    <x-pricing-minus text="Support Via Google Meet" />
                                    <x-pricing-minus text="Custom Request Fitur" />
                                </ul>

                                <button onclick="beli_paket(2)" class="btn btn-danger w-100 fw-semibold rounded-3 mt-auto">
                                    Pilih Paket
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Enterprise Pack --}}
                    <div class="col-xl-4 col-lg-5 col-md-6 mb-4">
                        <div class="card pricing-card h-100 shadow-sm border-0 rounded-4">
                            <div class="card-body p-4 d-flex flex-column">

                                <span
                                    class="paket-title badge bg-info-subtle text-info px-3 py-2 rounded-pill fw-semibold mb-3">
                                    Paket Farm M-Ultimate
                                </span>



                                <h2 class="fw-bold mb-0">Rp. 499.000 <span class="fs-6 fw-medium">/ bulan</span></h2>
                                <p class="text-muted">Paket ideal untuk kebutuhan Lengkap Kontrol Keamanan Anda.</p>

                                <ul class="list-unstyled flex-grow-1 mt-3 mb-4">
                                    <x-pricing-check text="Absen Scan Wajah + GPS" />
                                    <x-pricing-check text="Unlimited Personil Satpam" />
                                    <x-pricing-check text="Unimited Titik Lokasi" />
                                    <x-pricing-check text="Cek Unlimited Kandang" />
                                    <x-pricing-check text="Catat Pengeluaran DOC" />
                                    <x-pricing-check text="Broadcast Message" />
                                    <x-pricing-check text="Unlimited User Admin" />
                                    <x-pricing-check text="Input Tamu Manual" />
                                    <x-pricing-check text="Scan QR Tamu" />
                                    <x-pricing-check text="Area User" />
                                    <x-pricing-check text="Mobile Monitoring App" />
                                    <x-pricing-check text="24/7 Support" />
                                    <x-pricing-check text="Email Support" />
                                    <x-pricing-check text="Support Via Google Meet" />
                                    <x-pricing-check text="Custom Request Fitur" />
                                </ul>

                                <button onclick="beli_paket(3)" class="btn btn-info w-100 fw-semibold rounded-3 mt-auto">
                                    Pilih Paket
                                </button>
                            </div>
                        </div>
                    </div>


                    {{-- Professional Pack --}}
                    <div class="col-xl-4 col-lg-5 col-md-6 mb-4">
                        <div class="card pricing-card h-100 shadow-sm border-0 rounded-4">
                            <div class="card-body p-4 d-flex flex-column">

                                <span
                                    class="paket-title badge bg-info-subtle text-info px-3 py-2 rounded-pill fw-semibold mb-3">
                                    Paket Farm T-Basic
                                </span>


                                <h2 class="fw-bold mb-0">Rp. 2.490.000 <span class="fs-6 fw-medium">/ tahun</span></h2>
                                <p class="text-muted">Paket ideal untuk kebutuhan dasar Kontrol Keamanan Anda.</p>

                                <ul class="list-unstyled flex-grow-1 mt-3 mb-4">
                                    <x-pricing-check text="Absen Scan Wajah + GPS" />
                                    <x-pricing-check text="4 Personil Satpam" />
                                    <x-pricing-check text="5 Titik Lokasi" />
                                    <x-pricing-check text="Cek Kontrol 6 Kandang" />
                                    <x-pricing-check text="Catat Pengeluaran DOC" />
                                    <x-pricing-check text="Broadcast Message" />
                                    <x-pricing-check text="1 User Admin" />
                                    <x-pricing-check text="Input Tamu Manual" />
                                    <x-pricing-minus text="Scan QR Tamu" />
                                    <x-pricing-minus text="Area User" />
                                    <x-pricing-minus text="Mobile Monitoring App" />
                                    <x-pricing-check text="24/7 Support" />
                                    <x-pricing-check text="Email Support" />
                                    <x-pricing-check text="Support Via Google Meet" />
                                    <x-pricing-check text="Custom Request Fitur" />
                                </ul>

                                <button onclick="beli_paket(4)" class="btn btn-info w-100 fw-semibold rounded-3 mt-auto">
                                    Pilih Paket
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Business Pack --}}
                    <div class="col-xl-4 col-lg-5 col-md-6 mb-4">
                        <div class="card pricing-card h-100 shadow-lg border-0 rounded-4 premium-card">
                            <div class="card-body p-4 d-flex flex-column">

                                <span
                                    class="paket-title badge bg-danger-subtle text-danger px-3 py-2 rounded-pill fw-semibold mb-3">
                                    Paket Farm T-Medium
                                </span>



                                <h2 class="fw-bold mb-0">Rp. 3.690.000 <span class="fs-6 fw-medium">/ tahun</span></h2>
                                <p class="text-muted">Paket ideal untuk kebutuhan lanjut Kontrol Keamanan Anda.</p>

                                <ul class="list-unstyled flex-grow-1 mt-3 mb-4">
                                    <x-pricing-check text="Absen Scan Wajah + GPS" />
                                    <x-pricing-check text="8 Personil Satpam" />
                                    <x-pricing-check text="8 Titik Lokasi" />
                                    <x-pricing-check text="Cek Kontrol 8 Kandang" />
                                    <x-pricing-check text="Catat Pengeluaran DOC" />
                                    <x-pricing-check text="Broadcast Message" />
                                    <x-pricing-check text="5 User Admin" />
                                    <x-pricing-check text="Input Tamu Manual" />
                                    <x-pricing-minus text="Scan QR Tamu" />
                                    <x-pricing-minus text="Area User" />
                                    <x-pricing-check text="Mobile Monitoring App" />
                                    <x-pricing-check text="24/7 Support" />
                                    <x-pricing-check text="Email Support" />
                                    <x-pricing-check text="Support Via Google Meet" />
                                    <x-pricing-check text="Custom Request Fitur" />
                                </ul>

                                <button onclick="beli_paket(5)" class="btn btn-danger w-100 fw-semibold rounded-3 mt-auto">
                                    Pilih Paket
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Enterprise Pack --}}
                    <div class="col-xl-4 col-lg-5 col-md-6 mb-4">
                        <div class="card pricing-card h-100 shadow-sm border-0 rounded-4">
                            <div class="card-body p-4 d-flex flex-column">

                                <span
                                    class="paket-title badge bg-info-subtle text-info px-3 py-2 rounded-pill fw-semibold mb-3">
                                    Paket Farm T-Ultimate
                                </span>



                                <h2 class="fw-bold mb-0">Rp. 4.990.000 <span class="fs-6 fw-medium">/ tahun</span></h2>
                                <p class="text-muted">Paket ideal untuk kebutuhan Lengkap Kontrol Keamanan Anda.</p>

                                <ul class="list-unstyled flex-grow-1 mt-3 mb-4">
                                    <x-pricing-check text="Absen Scan Wajah + GPS" />
                                    <x-pricing-check text="Unlimited Personil Satpam" />
                                    <x-pricing-check text="Unimited Titik Lokasi" />
                                    <x-pricing-check text="Cek Unlimited Kandang" />
                                    <x-pricing-check text="Catat Pengeluaran DOC" />
                                    <x-pricing-check text="Broadcast Message" />
                                    <x-pricing-check text="Unlimited User Admin" />
                                    <x-pricing-check text="Input Tamu Manual" />
                                    <x-pricing-check text="Scan QR Tamu" />
                                    <x-pricing-check text="Area User" />
                                    <x-pricing-check text="Mobile Monitoring App" />
                                    <x-pricing-check text="24/7 Support" />
                                    <x-pricing-check text="Email Support" />
                                    <x-pricing-check text="Support Via Google Meet" />
                                    <x-pricing-check text="Custom Request Fitur" />
                                </ul>

                                <button onclick="beli_paket(6)" class="btn btn-info w-100 fw-semibold rounded-3 mt-auto">
                                    Pilih Paket
                                </button>
                            </div>
                        </div>
                    </div>


                </div>

            </div>
        </div>

        @include('frontend.footer')
    </div>

    {{-- Extra CSS --}}
    <style>
        .paket-title {
            font-size: 16px;
        }

        .pricing-card {
            transition: .25s ease;
        }

        .pricing-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 34px rgba(0, 0, 0, 0.12);
        }

        .premium-card {
            border: 2px solid #dc3545;
        }

        .icon-wrapper {
            width: 70px;
            height: 70px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid currentColor;
        }
    </style>
@endsection

@push('scripts')
    @include('frontend.paket.paket_js')
@endpush


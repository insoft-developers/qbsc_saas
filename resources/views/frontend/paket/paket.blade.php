@extends('frontend.master')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif


                <div class="text-center mb-5">
                    <h2 class="fw-bold mb-1">Paket Berlangganan</h2>
                    <p class="text-muted fs-6">Pilih paket yang sesuai dengan kebutuhan bisnis Anda.</p>
                </div>

                <div class="row justify-content-center">

                    @if ($com->is_peternakan == 1)
                        {{-- Professional Pack --}}

                        @if($com->has_trial != 1)
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-4">
                            <div class="card pricing-card h-100 shadow-sm border-0 rounded-4">
                                <div class="card-body p-4 d-flex flex-column">


                                    <h2 class="fw-bold mb-0">GRATIS <span class="fs-6 fw-medium">/ 14 Hari - Paket Farm
                                            Ultimate</span></h2>
                                    <p class="text-muted">Paket Ideal untuk kebutuhan Lengkap Kontrol Keamanan Anda.</p>


                                    <button onclick="paket_free(14)"
                                        class="btn btn-info w-100 fw-semibold rounded-3 mt-auto btn-color">
                                        Coba Gratis Paket Farm Ultimate Sekarang
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endif

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

                                    <button onclick="beli_paket(1)"
                                        class="btn btn-info w-100 fw-semibold rounded-3 mt-auto">
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

                                    <button onclick="beli_paket(2)"
                                        class="btn btn-danger w-100 fw-semibold rounded-3 mt-auto">
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
                                        <x-pricing-check text="30 Personil Satpam" />
                                        <x-pricing-check text="Unimited Titik Lokasi" />
                                        <x-pricing-check text="Cek 30 Kandang" />
                                        <x-pricing-check text="Catat Pengeluaran DOC" />
                                        <x-pricing-check text="Broadcast Message" />
                                        <x-pricing-check text="30 User Admin" />
                                        <x-pricing-check text="Input Tamu Manual" />
                                        <x-pricing-check text="Scan QR Tamu" />
                                        <x-pricing-check text="Area User" />
                                        <x-pricing-check text="Mobile Monitoring App" />
                                        <x-pricing-check text="24/7 Support" />
                                        <x-pricing-check text="Email Support" />
                                        <x-pricing-check text="Support Via Google Meet" />
                                        <x-pricing-check text="Custom Request Fitur" />
                                    </ul>

                                    <button onclick="beli_paket(3)"
                                        class="btn btn-info w-100 fw-semibold rounded-3 mt-auto">
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

                                    <button onclick="beli_paket(4)"
                                        class="btn btn-info w-100 fw-semibold rounded-3 mt-auto">
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



                                    <h2 class="fw-bold mb-0">Rp. 3.690.000 <span class="fs-6 fw-medium">/ tahun</span>
                                    </h2>
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

                                    <button onclick="beli_paket(5)"
                                        class="btn btn-danger w-100 fw-semibold rounded-3 mt-auto">
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



                                    <h2 class="fw-bold mb-0">Rp. 4.990.000 <span class="fs-6 fw-medium">/ tahun</span>
                                    </h2>
                                    <p class="text-muted">Paket ideal untuk kebutuhan Lengkap Kontrol Keamanan Anda.</p>

                                    <ul class="list-unstyled flex-grow-1 mt-3 mb-4">
                                        <x-pricing-check text="Absen Scan Wajah + GPS" />
                                        <x-pricing-check text="30 Personil Satpam" />
                                        <x-pricing-check text="Unimited Titik Lokasi" />
                                        <x-pricing-check text="Cek 30 Kandang" />
                                        <x-pricing-check text="Catat Pengeluaran DOC" />
                                        <x-pricing-check text="Broadcast Message" />
                                        <x-pricing-check text="30 User Admin" />
                                        <x-pricing-check text="Input Tamu Manual" />
                                        <x-pricing-check text="Scan QR Tamu" />
                                        <x-pricing-check text="Area User" />
                                        <x-pricing-check text="Mobile Monitoring App" />
                                        <x-pricing-check text="24/7 Support" />
                                        <x-pricing-check text="Email Support" />
                                        <x-pricing-check text="Support Via Google Meet" />
                                        <x-pricing-check text="Custom Request Fitur" />
                                    </ul>

                                    <button onclick="beli_paket(6)"
                                        class="btn btn-info w-100 fw-semibold rounded-3 mt-auto">
                                        Pilih Paket
                                    </button>
                                </div>
                            </div>
                        </div>
                    @elseif($com->is_peternakan == 2)
                        {{-- Professional Pack --}}

                        @if($com->has_trial != 1)
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-4">
                            <div class="card pricing-card h-100 shadow-sm border-0 rounded-4">
                                <div class="card-body p-4 d-flex flex-column">


                                    <h2 class="fw-bold mb-0">GRATIS <span class="fs-6 fw-medium">/ 14 Hari - Paket Ultimate</span></h2>
                                    <p class="text-muted">Paket Ideal untuk kebutuhan Lengkap Kontrol Keamanan Anda.</p>


                                    <button onclick="paket_free(13)"
                                        class="btn btn-info w-100 fw-semibold rounded-3 mt-auto btn-color">
                                        Coba Gratis Paket Ultimate Sekarang
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endif


                        <div class="col-xl-4 col-lg-5 col-md-6 mb-4">
                            <div class="card pricing-card h-100 shadow-sm border-0 rounded-4">
                                <div class="card-body p-4 d-flex flex-column">

                                    <span
                                        class="paket-title badge bg-info-subtle text-info px-3 py-2 rounded-pill fw-semibold mb-3">
                                        Paket M-Basic
                                    </span>


                                    <h2 class="fw-bold mb-0">Rp. 149.000 <span class="fs-6 fw-medium">/ bulan</span></h2>
                                    <p class="text-muted">Paket ideal untuk kebutuhan dasar Kontrol Keamanan Anda.</p>

                                    <ul class="list-unstyled flex-grow-1 mt-3 mb-4">
                                        <x-pricing-check text="Absen Scan Wajah + GPS" />
                                        <x-pricing-check text="4 Personil Satpam" />
                                        <x-pricing-check text="5 Titik Lokasi" />
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

                                    <button onclick="beli_paket(7)"
                                        class="btn btn-info w-100 fw-semibold rounded-3 mt-auto">
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
                                        Paket M-Medium
                                    </span>



                                    <h2 class="fw-bold mb-0">Rp. 269.000 <span class="fs-6 fw-medium">/ bulan</span></h2>
                                    <p class="text-muted">Paket ideal untuk kebutuhan lanjut Kontrol Keamanan Anda.</p>

                                    <ul class="list-unstyled flex-grow-1 mt-3 mb-4">
                                        <x-pricing-check text="Absen Scan Wajah + GPS" />
                                        <x-pricing-check text="8 Personil Satpam" />
                                        <x-pricing-check text="8 Titik Lokasi" />
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

                                    <button onclick="beli_paket(8)"
                                        class="btn btn-danger w-100 fw-semibold rounded-3 mt-auto">
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
                                        Paket M-Ultimate
                                    </span>



                                    <h2 class="fw-bold mb-0">Rp. 399.000 <span class="fs-6 fw-medium">/ bulan</span></h2>
                                    <p class="text-muted">Paket ideal untuk kebutuhan Lengkap Kontrol Keamanan Anda.</p>

                                    <ul class="list-unstyled flex-grow-1 mt-3 mb-4">
                                        <x-pricing-check text="Absen Scan Wajah + GPS" />
                                        <x-pricing-check text="30 Personil Satpam" />
                                        <x-pricing-check text="Unimited Titik Lokasi" />
                                        <x-pricing-check text="Broadcast Message" />
                                        <x-pricing-check text="30 User Admin" />
                                        <x-pricing-check text="Input Tamu Manual" />
                                        <x-pricing-check text="Scan QR Tamu" />
                                        <x-pricing-check text="Area User" />
                                        <x-pricing-check text="Mobile Monitoring App" />
                                        <x-pricing-check text="24/7 Support" />
                                        <x-pricing-check text="Email Support" />
                                        <x-pricing-check text="Support Via Google Meet" />
                                        <x-pricing-check text="Custom Request Fitur" />
                                    </ul>

                                    <button onclick="beli_paket(9)"
                                        class="btn btn-info w-100 fw-semibold rounded-3 mt-auto">
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
                                        Paket T-Basic
                                    </span>


                                    <h2 class="fw-bold mb-0">Rp. 1.490.000 <span class="fs-6 fw-medium">/ tahun</span>
                                    </h2>
                                    <p class="text-muted">Paket ideal untuk kebutuhan dasar Kontrol Keamanan Anda.</p>

                                    <ul class="list-unstyled flex-grow-1 mt-3 mb-4">
                                        <x-pricing-check text="Absen Scan Wajah + GPS" />
                                        <x-pricing-check text="4 Personil Satpam" />
                                        <x-pricing-check text="5 Titik Lokasi" />
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

                                    <button onclick="beli_paket(10)"
                                        class="btn btn-info w-100 fw-semibold rounded-3 mt-auto">
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
                                        Paket T-Medium
                                    </span>



                                    <h2 class="fw-bold mb-0">Rp. 2.690.000 <span class="fs-6 fw-medium">/ tahun</span>
                                    </h2>
                                    <p class="text-muted">Paket ideal untuk kebutuhan lanjut Kontrol Keamanan Anda.</p>

                                    <ul class="list-unstyled flex-grow-1 mt-3 mb-4">
                                        <x-pricing-check text="Absen Scan Wajah + GPS" />
                                        <x-pricing-check text="8 Personil Satpam" />
                                        <x-pricing-check text="8 Titik Lokasi" />
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

                                    <button onclick="beli_paket(11)"
                                        class="btn btn-danger w-100 fw-semibold rounded-3 mt-auto">
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
                                        Paket T-Ultimate
                                    </span>



                                    <h2 class="fw-bold mb-0">Rp. 3.990.000 <span class="fs-6 fw-medium">/ tahun</span>
                                    </h2>
                                    <p class="text-muted">Paket ideal untuk kebutuhan Lengkap Kontrol Keamanan Anda.</p>

                                    <ul class="list-unstyled flex-grow-1 mt-3 mb-4">
                                        <x-pricing-check text="Absen Scan Wajah + GPS" />
                                        <x-pricing-check text="30 Personil Satpam" />
                                        <x-pricing-check text="Unimited Titik Lokasi" />
                                        <x-pricing-check text="Broadcast Message" />
                                        <x-pricing-check text="30 User Admin" />
                                        <x-pricing-check text="Input Tamu Manual" />
                                        <x-pricing-check text="Scan QR Tamu" />
                                        <x-pricing-check text="Area User" />
                                        <x-pricing-check text="Mobile Monitoring App" />
                                        <x-pricing-check text="24/7 Support" />
                                        <x-pricing-check text="Email Support" />
                                        <x-pricing-check text="Support Via Google Meet" />
                                        <x-pricing-check text="Custom Request Fitur" />
                                    </ul>

                                    <button onclick="beli_paket(12)"
                                        class="btn btn-info w-100 fw-semibold rounded-3 mt-auto">
                                        Pilih Paket
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>

            </div>
        </div>

        <div id="modal-pembayaran" class="modal fade" tabindex="-1" role="dialog"
            aria-labelledby="standard-modalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="standard-modalLabel">Pilih Bayar Melalui Apa</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-2">
                                    <input type="hidden" id="id_pembayaran">
                                    <label for="jenis_pembayaran" class="form-label">Metode Pembayaran</label>
                                    <select id="jenis_pembayaran" name="jenis_pembayaran" class="form-control">
                                        <option value="">Pilih Metode Pembayaran</option>
                                        <option value="1">Bank|VA|E-Wallet|Dll</option>
                                        <option value="2">Transfer Manual</option>
                                    </select>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button id="btn-payment" type="button" class="btn btn-primary">Bayar</button>
                    </div>

                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->


        <div id="modal-gratis" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="standard-modalLabel">Paket 14 Hari Gratis</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-2">
                                    <input type="hidden" id="paket-gratis-id">
                                    <h2 data-start="157" data-end="206">📄 <strong data-start="163"
                                            data-end="206">Syarat &amp; Ketentuan Paket Gratis 14 Hari</strong></h2>
                                    <ol data-start="208" data-end="1306">
                                        <li data-start="208" data-end="314">
                                            <p data-start="211" data-end="314">Paket Gratis 14 Hari hanya berlaku untuk
                                                <strong data-start="252" data-end="269">pengguna baru</strong> yang belum
                                                pernah berlangganan layanan QBSC.</p>
                                        </li>
                                        <li data-start="315" data-end="410">
                                            <p data-start="318" data-end="410">Masa uji coba <strong data-start="332"
                                                    data-end="381">berlaku selama 14 (empat belas) hari kalender</strong>
                                                sejak tanggal aktivasi akun.</p>
                                        </li>
                                        <li data-start="411" data-end="512">
                                            <p data-start="414" data-end="512">Selama masa gratis, pengguna dapat
                                                menikmati <strong data-start="459" data-end="490">fitur sesuai paket
                                                    Ultimate</strong> tanpa dipungut biaya.</p>
                                        </li>
                                        <li data-start="513" data-end="601">
                                            <p data-start="516" data-end="601">Tidak diperlukan metode pembayaran atau
                                                kartu kredit untuk mengaktifkan paket gratis.</p>
                                        </li>
                                        <li data-start="602" data-end="724">
                                            <p data-start="605" data-end="724">Setelah masa uji coba berakhir, akun akan
                                                <strong data-start="647" data-end="673">otomatis dinonaktifkan</strong>
                                                apabila tidak melakukan upgrade ke paket berbayar.</p>
                                        </li>
                                        <li data-start="725" data-end="840">
                                            <p data-start="728" data-end="840">Data pengguna selama masa uji coba akan
                                                <strong data-start="768" data-end="787">tetap tersimpan</strong> dan dapat
                                                diakses kembali setelah melakukan upgrade.</p>
                                        </li>
                                        <li data-start="841" data-end="947">
                                            <p data-start="844" data-end="947">Satu perusahaan / satu nomor WhatsApp /
                                                satu alamat email <strong data-start="902" data-end="946">hanya berhak
                                                    atas satu kali paket gratis</strong>.</p>
                                        </li>
                                        <li data-start="948" data-end="1082">
                                            <p data-start="951" data-end="1082">Penyalahgunaan, pendaftaran ganda, atau
                                                indikasi kecurangan dapat menyebabkan <strong data-start="1029"
                                                    data-end="1065">penghentian akses secara sepihak</strong> oleh pihak
                                                QBSC.</p>
                                        </li>
                                        <li data-start="1083" data-end="1179">
                                            <p data-start="1086" data-end="1179">QBSC berhak <strong data-start="1098"
                                                    data-end="1142">mengubah atau menghentikan program promo</strong> tanpa
                                                pemberitahuan terlebih dahulu.</p>
                                        </li>
                                        <li data-start="1180" data-end="1306">
                                            <p data-start="1184" data-end="1306">Dengan mendaftar, pengguna dianggap
                                                <strong data-start="1220" data-end="1263">telah membaca, memahami, dan
                                                    menyetujui</strong> seluruh syarat dan ketentuan yang berlaku.</p>
                                        </li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                        <button id="btn-coba-gratis" type="button" class="btn btn-primary">Coba Sekarang</button>
                    </div>

                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

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

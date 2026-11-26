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

            {{-- Perpanjangan Paket Aktif: tampil jika expired dalam 7 hari --}}
            @if(!empty($tampilkanTombolPerpanjang) && $tampilkanTombolPerpanjang && !empty($paketAktif))
                <div class="row justify-content-center mb-4">
                    <div class="col-xl-12">
                        <div class="card border-warning shadow-sm rounded-4">
                            <div class="card-body p-4">
                                <div class="row align-items-center">
                                    <div class="col-md">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                                Masa Aktif Segera Berakhir
                                            </span>
                                        </div>
                                        <h4 class="fw-bold mb-1">{{ $paketAktif->nama_paket }}</h4>
                                        <p class="text-muted mb-1">
                                            Paket yang sedang Anda gunakan akan berakhir pada
                                            <strong class="text-danger">
                                                {{ \Carbon\Carbon::parse($com->expired_date)->translatedFormat('d F Y') }}
                                            </strong>.
                                        </p>
                                        <small class="text-muted">
                                            Perpanjang sekarang agar layanan QBSC tetap aktif tanpa terputus.
                                        </small>
                                    </div>
                                    <div class="col-md-auto mt-3 mt-md-0">
                                        <button type="button"
                                            onclick="beli_paket({{ $paketAktif->id }})"
                                            class="btn btn-warning fw-semibold rounded-3 px-4 py-3">
                                            <i class="mdi mdi-refresh me-1"></i>
                                            Perpanjang Paket
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Paket Gratis --}}
          @if($com->has_trial != 1 && (is_null($com->paket_id) || in_array($com->paket_id, [13, 14])))
            <div class="row justify-content-center mb-4">
                <div class="col-xl-12 col-lg-12 col-md-12 mb-4">
                    <div class="card pricing-card h-100 shadow-sm border-0 rounded-4">
                        <div class="card-body p-4 d-flex flex-column">
                            <h2 class="fw-bold mb-0">
                                GRATIS
                                <span class="fs-6 fw-medium">/ Paket Free Selamanya</span>
                            </h2>

                            @if($com->is_peternakan == 1)
                            <p class="text-muted">
                                Paket Dasar untuk kebutuhan Kontrol Keamanan Peternakan Anda.
                            </p>
                            <button onclick="paket_free(14)"
                                class="btn btn-info w-100 fw-semibold rounded-3 mt-auto btn-color">
                                Coba Sekarang Gratis Selamanya
                            </button>
                            @elseif($com->is_peternakan == 2)
                            <p class="text-muted">
                                Paket Dasar untuk kebutuhan Kontrol Keamanan Anda.
                            </p>
                            <button onclick="paket_free(13)"
                                class="btn btn-info w-100 fw-semibold rounded-3 mt-auto btn-color">
                                Coba Sekarang Gratis Selamanya
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Paket Bulanan --}}
            <div class="text-center mb-4">
                <h3 class="fw-bold">Paket Bulanan</h3>
                <p class="text-muted">Berlangganan dengan pembayaran setiap bulan.</p>
            </div>

            <div class="row justify-content-center">
                @forelse($paketBulanan as $paket)
                @php
                $colors = ['info', 'danger', 'warning'];
                $color = $colors[($loop->iteration - 1) % count($colors)];
                @endphp

                <div class="col-xl-4 col-lg-5 col-md-6 mb-4">
                    <div class="card pricing-card h-100 shadow-sm border-0 rounded-4">
                        <div class="card-body p-4 d-flex flex-column">

                            <span class="paket-title badge bg-{{ $color }}-subtle text-{{ $color }} px-3 py-2 rounded-pill fw-semibold mb-3">
                                {{ $paket->nama_paket }}
                            </span>

                            <h2 class="fw-bold mb-0">
                                Rp. {{ number_format($paket->harga, 0, ',', '.') }}
                                <span class="fs-6 fw-medium">/ bulan</span>
                            </h2>

                            <p class="text-muted">{{ $paket->subtitle }}</p>

                            <ul class="list-unstyled flex-grow-1 mt-3 mb-4">
                                <x-pricing-check text="Absen Scan Wajah + GPS" />
                                <x-pricing-check text="{{ $paket->jumlah_satpam }} Personil Satpam" />
                                <x-pricing-check text="{{ $paket->jumlah_lokasi }} Titik Lokasi" />

                                @if($com->is_peternakan == 1)
                                <x-pricing-check text="Cek Kontrol {{ $paket->jumlah_farm ?? 0 }} Kandang" />
                                <x-pricing-check text="Catat Pengeluaran DOC" />
                                @endif

                                <x-pricing-check text="{{ $paket->jumlah_user_admin ?? 0 }} User Admin" />
                                <x-pricing-check text="Input Tamu Manual" />

                                @php
                                $fiturs = [
                                'is_broadcast' => 'Broadcast Message',
                                'is_scan_tamu' => 'Scan QR Tamu',
                                'is_user_area' => 'Area User',
                                'is_mobile_app' => 'Mobile Monitoring App',
                                'is_google_meet' => 'Support Via Google Meet',
                                'is_request_feature' => 'Custom Request Fitur',
                                ];
                                @endphp

                                @foreach($fiturs as $field => $namaFitur)
                                @if($paket->$field == 1)
                                <x-pricing-check text="{{ $namaFitur }}" />
                                @else
                                <x-pricing-minus text="{{ $namaFitur }}" />
                                @endif
                                @endforeach

                                <x-pricing-check text="24/7 Support" />
                                <x-pricing-check text="Email Support" />
                            </ul>

                            <button onclick="beli_paket({{ $paket->id }})"
                                class="btn btn-{{ $color }} w-100 fw-semibold rounded-3 mt-auto">
                                Pilih Paket
                            </button>

                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center mb-4">
                    <p class="text-muted">Belum ada paket bulanan yang tersedia.</p>
                </div>
                @endforelse
            </div>

            {{-- Paket Tahunan --}}
            <hr class="my-5">

            <div class="text-center mb-4">
                <h3 class="fw-bold">Paket Tahunan</h3>
                <p class="text-muted">Berlangganan dengan pembayaran setiap tahun.</p>
            </div>

            <div class="row justify-content-center">
                @forelse($paketTahunan as $paket)
                @php
                $colors = ['info', 'danger', 'warning'];
                $color = $colors[($loop->iteration - 1) % count($colors)];
                @endphp

                <div class="col-xl-4 col-lg-5 col-md-6 mb-4">
                    <div class="card pricing-card h-100 shadow-sm border-0 rounded-4">
                        <div class="card-body p-4 d-flex flex-column">

                            <span class="paket-title badge bg-{{ $color }}-subtle text-{{ $color }} px-3 py-2 rounded-pill fw-semibold mb-3">
                                {{ $paket->nama_paket }}
                            </span>

                            <h2 class="fw-bold mb-0">
                                Rp. {{ number_format($paket->harga, 0, ',', '.') }}
                                <span class="fs-6 fw-medium">/ tahun</span>
                            </h2>

                            <p class="text-muted">{{ $paket->subtitle }}</p>

                            <ul class="list-unstyled flex-grow-1 mt-3 mb-4">
                                <x-pricing-check text="Absen Scan Wajah + GPS" />
                                <x-pricing-check text="{{ $paket->jumlah_satpam }} Personil Satpam" />
                                <x-pricing-check text="{{ $paket->jumlah_lokasi }} Titik Lokasi" />

                                @if($com->is_peternakan == 1)
                                <x-pricing-check text="Cek Kontrol {{ $paket->jumlah_farm ?? 0 }} Kandang" />
                                <x-pricing-check text="Catat Pengeluaran DOC" />
                                @endif

                                <x-pricing-check text="{{ $paket->jumlah_user_admin ?? 0 }} User Admin" />
                                <x-pricing-check text="Input Tamu Manual" />

                                @php
                                $fiturs = [
                                'is_broadcast' => 'Broadcast Message',
                                'is_scan_tamu' => 'Scan QR Tamu',
                                'is_user_area' => 'Area User',
                                'is_mobile_app' => 'Mobile Monitoring App',
                                'is_google_meet' => 'Support Via Google Meet',
                                'is_request_feature' => 'Custom Request Fitur',
                                ];
                                @endphp

                                @foreach($fiturs as $field => $namaFitur)
                                @if($paket->$field == 1)
                                <x-pricing-check text="{{ $namaFitur }}" />
                                @else
                                <x-pricing-minus text="{{ $namaFitur }}" />
                                @endif
                                @endforeach

                                <x-pricing-check text="24/7 Support" />
                                <x-pricing-check text="Email Support" />
                            </ul>

                            <button onclick="beli_paket({{ $paket->id }})"
                                class="btn btn-{{ $color }} w-100 fw-semibold rounded-3 mt-auto">
                                Pilih Paket
                            </button>

                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center mb-4">
                    <p class="text-muted">Belum ada paket tahunan yang tersedia.</p>
                </div>
                @endforelse
            </div>

        </div>
    </div>

    {{-- Modal Pembayaran --}}
    <div id="modal-pembayaran" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="modalPembayaranLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="modalPembayaranLabel">Pilih Bayar Melalui Apa</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-2">
                        <input type="hidden" id="id_pembayaran">
                        <label for="jenis_pembayaran" class="form-label">Metode Pembayaran</label>
                        <select id="jenis_pembayaran" name="jenis_pembayaran" class="form-control">
                            <option value="">Pilih Metode Pembayaran</option>
                            <option value="1">Bank-VA-Wallet-QRIS-dan lain lain</option>
                            <option value="2">Transfer Manual</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button id="btn-payment" type="button" class="btn btn-primary">Bayar</button>
                </div>

            </div>
        </div>
    </div>

    {{-- Modal Paket Gratis --}}
    <div id="modal-gratis" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="modalGratisLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="modalGratisLabel">Paket Gratis Selamanya</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="paket-gratis-id">

                    <h5 class="fw-bold">Syarat &amp; Ketentuan Paket Gratis Selamanya</h5>
                    <ol>
                        <li>Paket Gratis Selamanya hanya berlaku untuk pengguna baru yang belum pernah berlangganan layanan QBSC.</li>
                        <li>Masa penggunaan berlaku tanpa batas waktu sejak aktivasi akun.</li>
                        <li>Selama masa gratis, pengguna dapat menikmati fitur sesuai paket FREE tanpa dipungut biaya.</li>
                        <li>Tidak diperlukan metode pembayaran atau kartu kredit untuk mengaktifkan paket gratis.</li>
                        <li>Data pengguna selama masa gratis tetap tersimpan dan dapat diakses walaupun pelanggan melakukan upgrade.</li>
                        <li>Satu perusahaan, satu nomor WhatsApp, atau satu alamat email hanya berhak atas satu kali paket gratis.</li>
                        <li>Penyalahgunaan, pendaftaran ganda, atau indikasi kecurangan dapat menyebabkan penghentian akses secara sepihak oleh QBSC.</li>
                        <li>QBSC berhak mengubah atau menghentikan program promo tanpa pemberitahuan terlebih dahulu.</li>
                        <li>Dengan mendaftar, pengguna dianggap telah membaca, memahami, dan menyetujui seluruh syarat dan ketentuan yang berlaku.</li>
                    </ol>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                    <button id="btn-coba-gratis" type="button" class="btn btn-primary">Coba Sekarang</button>
                </div>

            </div>
        </div>
    </div>

    @include('frontend.footer')
</div>

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
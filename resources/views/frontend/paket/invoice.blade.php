@extends('frontend.master')

@section('content')

<style>
    .invoice-page {
        padding: 30px 0 50px;
        background: #f4f6fa;
        min-height: 100vh;
    }

    .invoice-card {
        background: #fff;
        border: 1px solid #e8edf3;
        border-radius: 14px;
        box-shadow: 0 8px 30px rgba(20, 40, 70, .07);
        overflow: hidden;
    }

    .invoice-topbar {
        height: 7px;
        background: linear-gradient(90deg, #174ea6, #21a1d6);
    }

    .invoice-inner {
        padding: 42px 48px;
    }

    .invoice-brand {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .invoice-brand img {
        max-height: 54px;
        max-width: 190px;
        object-fit: contain;
    }

    .invoice-title {
        margin: 0;
        color: #173b70;
        font-size: 30px;
        font-weight: 800;
        letter-spacing: 2px;
    }

    .invoice-subtitle {
        margin-top: 5px;
        color: #8290a3;
        font-size: 11px;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .company-details {
        color: #68778b;
        font-size: 12px;
        line-height: 1.8;
    }

    .company-details strong {
        color: #24364b;
        font-size: 15px;
    }

    .invoice-divider {
        border: 0;
        border-top: 1px solid #e9eef4;
        margin: 28px 0;
        opacity: 1;
    }

    .section-label {
        color: #8290a3;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .billing-name {
        color: #24364b;
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .billing-details {
        color: #68778b;
        font-size: 13px;
        line-height: 1.8;
    }

    .invoice-meta {
        display: grid;
        grid-template-columns: auto auto;
        gap: 8px 22px;
        justify-content: end;
        font-size: 13px;
    }

    .invoice-meta span {
        color: #8290a3;
    }

    .invoice-meta strong {
        color: #26384d;
        text-align: right;
    }

    .status-pill {
        display: inline-block;
        padding: 6px 13px;
        border-radius: 30px;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .8px;
    }

    .status-paid {
        background: #e7f7ef;
        color: #16834b;
    }

    .status-unpaid {
        background: #fff0ed;
        color: #cf4937;
    }

    .invoice-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border: 1px solid #e8edf3;
        border-radius: 9px;
        overflow: hidden;
    }

    .invoice-table thead th {
        padding: 14px 15px;
        background: #f4f7fb;
        color: #63748a;
        border-bottom: 1px solid #e8edf3;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .invoice-table tbody td {
        padding: 19px 15px;
        color: #33465d;
        font-size: 13px;
        border-bottom: 1px solid #edf1f5;
        vertical-align: middle;
    }

    .invoice-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .item-name {
        color: #24364b;
        font-weight: 700;
    }

    .item-description {
        display: block;
        color: #8996a7;
        font-size: 11px;
        margin-top: 4px;
    }

    .summary-panel {
        width: 100%;
        max-width: 340px;
        margin-left: auto;
        padding: 20px 22px;
        background: #f7f9fc;
        border: 1px solid #e9eef4;
        border-radius: 10px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        color: #68778b;
        font-size: 13px;
        margin-bottom: 12px;
    }

    .summary-row strong {
        color: #33465d;
        font-weight: 600;
    }

    .summary-total {
        border-top: 1px solid #dfe6ee;
        padding-top: 15px;
        margin-top: 5px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #173b70;
        font-size: 16px;
        font-weight: 800;
    }

    .summary-total strong {
        font-size: 22px;
    }

    .payment-note {
        padding: 18px 20px;
        border-left: 3px solid #2582c4;
        background: #f3f8fc;
        border-radius: 0 8px 8px 0;
        color: #68778b;
        font-size: 12px;
        line-height: 1.8;
    }

    .payment-note strong {
        display: block;
        color: #2d4560;
        margin-bottom: 4px;
    }

    .invoice-footer {
        margin-top: 35px;
        padding-top: 20px;
        border-top: 1px solid #e9eef4;
        text-align: center;
        color: #94a0af;
        font-size: 11px;
        line-height: 1.8;
    }

    .paid-stamp {
        position: absolute;
        top: 48%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-15deg);
        z-index: 5;
        border: 5px double rgba(22, 131, 75, .32);
        color: rgba(22, 131, 75, .32);
        border-radius: 9px;
        padding: 10px 25px;
        font-size: 58px;
        line-height: 1;
        font-weight: 900;
        letter-spacing: 5px;
        pointer-events: none;
        white-space: nowrap;
    }

    .invoice-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 22px;
    }

    @media (max-width: 767px) {
        .invoice-page { padding: 15px 0 30px; }
        .invoice-inner { padding: 25px 20px; }
        .invoice-title { font-size: 25px; }
        .invoice-header { gap: 22px; }
        .invoice-meta {
            justify-content: start;
            grid-template-columns: auto auto;
            margin-top: 18px;
        }
        .invoice-meta strong { text-align: right; }
        .paid-stamp { font-size: 36px; border-width: 4px; }
        .summary-panel { max-width: 100%; margin-top: 15px; }
        .invoice-actions { justify-content: stretch; }
        .invoice-actions .btn { flex: 1; }
    }

    @media print {
        @page {
            size: A4 portrait;
            margin: 8mm;
        }

        html, body {
            width: 100% !important;
            height: auto !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
            overflow: visible !important;
        }

        /* Hanya area invoice yang boleh masuk ke hasil cetak. */
        body * {
            visibility: hidden !important;
        }

        #invoice-print-area,
        #invoice-print-area * {
            visibility: visible !important;
        }

        #invoice-print-area {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
            border: 0 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            overflow: visible !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }

        /* Sembunyikan tombol dan seluruh elemen kontrol secara paksa. */
        .invoice-actions,
        .invoice-actions *,
        .d-print-none,
        button,
        .btn,
        .invoice-topbar {
            display: none !important;
            visibility: hidden !important;
        }

        .invoice-inner {
            padding: 5mm 6mm !important;
        }

        .invoice-title {
            font-size: 23px !important;
            margin-top: 8px !important;
        }

        .invoice-subtitle {
            font-size: 8px !important;
        }

        .company-details,
        .billing-details,
        .invoice-meta,
        .invoice-table tbody td,
        .summary-row,
        .payment-note {
            font-size: 9px !important;
            line-height: 1.45 !important;
        }

        .company-details strong,
        .billing-name {
            font-size: 11px !important;
        }

        .invoice-divider {
            margin: 12px 0 !important;
        }

        .section-label {
            font-size: 8px !important;
            margin-bottom: 5px !important;
        }

        .invoice-table thead th {
            padding: 7px 8px !important;
            font-size: 8px !important;
        }

        .invoice-table tbody td {
            padding: 9px 8px !important;
        }

        .item-description {
            font-size: 8px !important;
        }

        .summary-panel {
            padding: 11px 13px !important;
            max-width: 280px !important;
        }

        .summary-row {
            margin-bottom: 6px !important;
        }

        .summary-total {
            padding-top: 8px !important;
            font-size: 12px !important;
        }

        .summary-total strong {
            font-size: 16px !important;
        }

        .payment-note {
            padding: 10px 12px !important;
        }

        .invoice-footer {
            margin-top: 15px !important;
            padding-top: 10px !important;
            font-size: 8px !important;
            line-height: 1.5 !important;
        }

        .paid-stamp {
            position: absolute !important;
            top: 48% !important;
            font-size: 42px !important;
            border-width: 4px !important;
        }

        .row,
        .invoice-table,
        .summary-panel,
        .payment-note,
        .invoice-footer {
            break-inside: avoid !important;
            page-break-inside: avoid !important;
        }

        .container-fluid,
        .content,
        .content-page {
            width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }
    }
</style>

<div class="content-page">
    <div class="content">
        <div class="container-fluid invoice-page">

            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-11">

                    <div class="invoice-card position-relative" id="invoice-print-area">
                        <div class="invoice-topbar"></div>

                        <div class="invoice-inner">

                            @if($data->payment_status == 'PAID')
                                <div class="paid-stamp">LUNAS</div>
                            @endif

                            {{-- HEADER --}}
                            <div class="row align-items-start invoice-header">
                                <div class="col-md-6">
                                    <div class="invoice-brand">
                                        <img src="{{ asset('images/logotrans.png') }}" alt="Insoft Developers">
                                    </div>
                                    <h1 class="invoice-title mt-3">INVOICE</h1>
                                    <div class="invoice-subtitle">QBSC Subscription</div>
                                </div>

                                <div class="col-md-6 text-md-end mt-4 mt-md-0">
                                    <div class="company-details">
                                        <strong>Insoft Developers</strong><br>
                                        Jl. Tengku Bergalit Dusun II Desa Bandar Labuhan<br>
                                        Tanjung Morawa, Deli Serdang, Sumatera Utara<br>
                                        Phone: 0821-6517-4835
                                    </div>
                                </div>
                            </div>

                            <hr class="invoice-divider">

                            {{-- CUSTOMER & INVOICE INFORMATION --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="section-label">Ditagihkan Kepada</div>
                                    <div class="billing-name">
                                        {{ $data->company->company_name ?? 'Customer Name' }}
                                    </div>
                                    <div class="billing-details">
                                        {{ $data->company->company_address ?? 'Customer Address' }}<br>
                                        {{ $data->company->company_phone ?? '-' }}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="invoice-meta">
                                        <span>Nomor Invoice</span>
                                        <strong>{{ $data->invoice ?? '#INV-001' }}</strong>

                                        <span>Tanggal Invoice</span>
                                        <strong>{{ $data->created_at ? date('d M Y', strtotime($data->created_at)) : '-' }}</strong>

                                        <span>Status Pembayaran</span>
                                        <strong>
                                            @if($data->payment_status == 'PAID')
                                                <span class="status-pill status-paid">LUNAS</span>
                                            @else
                                                <span class="status-pill status-unpaid">{{ $data->payment_status }}</span>
                                            @endif
                                        </strong>
                                    </div>
                                </div>
                            </div>

                            {{-- ITEM TABLE --}}
                            <div class="mt-4">
                                <div class="section-label">Rincian Tagihan</div>
                                <div class="table-responsive">
                                    <table class="invoice-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 55px;">No.</th>
                                                <th>Deskripsi</th>
                                                <th class="text-center" style="width: 90px;">Qty</th>
                                                <th class="text-end" style="width: 155px;">Harga</th>
                                                <th class="text-end" style="width: 165px;">Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>01</td>
                                                <td>
                                                    <span class="item-name">
                                                        QBSC - {{ $data->paket->nama_paket ?? 'Paket Langganan' }}
                                                    </span>
                                                    <span class="item-description">
                                                        Langganan aplikasi
                                                        @if(isset($data->paket) && $data->paket->periode == 1)
                                                            periode bulanan
                                                        @elseif(isset($data->paket) && $data->paket->periode == 2)
                                                            periode tahunan
                                                        @endif
                                                        <br>
                                                        <strong>Berlaku sampai:</strong>
                                                        @if($data->expired_at)
                                                            {{ \Carbon\Carbon::parse($data->expired_at)->locale('id')->translatedFormat('d F Y') }}
                                                        @else
                                                            -
                                                        @endif
                                                    </span>
                                                </td>
                                                <td class="text-center">1</td>
                                                <td class="text-end">Rp {{ number_format($data->amount, 0, ',', '.') }}</td>
                                                <td class="text-end fw-bold">Rp {{ number_format($data->amount, 0, ',', '.') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- NOTES & TOTAL --}}
                            <div class="row mt-4 align-items-start">
                                <div class="col-md-6">
                                    <div class="payment-note">
                                        <strong>Terima kasih telah berlangganan QBSC.</strong>
                                        Terima kasih atas kepercayaan Anda menggunakan layanan kami.
                                        Untuk pertanyaan mengenai invoice atau layanan, silakan hubungi tim Insoft Developers.
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="summary-panel">
                                        <div class="summary-row">
                                            <span>Subtotal</span>
                                            <strong>Rp {{ number_format($data->amount, 0, ',', '.') }}</strong>
                                        </div>
                                        <div class="summary-row">
                                            <span>Pajak</span>
                                            <strong>Rp 0</strong>
                                        </div>
                                        <div class="summary-total">
                                            <span>Total</span>
                                            <strong>Rp {{ number_format($data->amount, 0, ',', '.') }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="invoice-footer">
                                <strong>Insoft Developers</strong><br>
                                Invoice ini diterbitkan secara elektronik dan sah tanpa tanda tangan.<br>
                                Terima kasih telah mempercayakan kebutuhan Anda kepada kami.
                            </div>

                            <div class="invoice-actions d-print-none">
                                <button type="button" onclick="window.print()" class="btn btn-primary px-4">
                                    <i class="mdi mdi-printer me-1"></i> Cetak Invoice
                                </button>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    @include('frontend.footer')
</div>

@endsection

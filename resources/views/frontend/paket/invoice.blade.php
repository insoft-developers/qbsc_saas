@extends('frontend.master')

@section('content')

<style>
    .invoice-title {
        font-size: 28px;
        font-weight: 700;
        letter-spacing: 1px;
    }
    .table-invoice th {
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6 !important;
    }
    .summary-box p {
        font-size: 14px;
        margin-bottom: 6px;
    }
    .summary-box h3 {
        font-weight: 700;
    }
    .invoice-footer {
        border-top: 1px solid #e9ecef;
        padding-top: 15px;
        margin-top: 40px;
        text-align: center;
        font-size: 12px;
        color: #6c757d;
    }

    /* STAMPEL LUNAS */
    .stamp-paid {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-15deg);
        font-size: 90px;
        font-weight: 900;
        color: rgba(0, 128, 0, 0.25); /* hijau transparan */
        border: 8px solid rgba(0, 128, 0, 0.25);
        padding: 20px 40px;
        border-radius: 10px;
        text-transform: uppercase;
        pointer-events: none;
        z-index: 10;
    }
</style>

<div class="content-page">
    <div class="content">
        <div class="container-fluid">

            <div class="row justify-content-center">
                <div class="col-lg-10">

                    <div class="card position-relative">
                        <div class="card-body">

                            {{-- STAMPEL LUNAS --}}
                            @if($data->payment_status == 'PAID')
                                <div class="stamp-paid">LUNAS</div>
                            @endif

                            <!-- Header -->
                            <div class="d-flex justify-content-between">
                                <div>
                                    <img src="{{ asset('images/logotrans.png') }}" alt="" height="50">
                                    <h3 class="invoice-title mt-2">INVOICE</h3>
                                </div>
                                <div class="text-end">
                                    <h5 class="mb-1">Insoft Developers</h5>
                                    <p class="mb-0 text-muted">Jl. Tengku Bergalit Dusun II Desa Bandar Labuhan<br>
                                        Tanjung Morawa, Deli Serdang<br>Sumatera Utara
                                    </p>
                                    <p class="mb-0 text-muted">Phone: 0821-6517-4835</p>
                                </div>
                            </div>

                            <hr>

                            <!-- Invoice Info -->
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-1">Billing To:</h6>
                                    <p class="mb-0">{{ $data->company->company_name ?? 'Customer Name' }}</p>
                                    <p class="mb-0">{{ $data->company->company_address ?? 'Customer Address' }}</p>
                                    <p class="mb-0">{{ $data->company->company_phone ?? '(000) 000-0000' }}</p>
                                </div>
                                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                    <p class="mb-1"><strong>Invoice No:</strong> {{ $data->invoice ?? '#INV-001' }}</p>
                                    <p class="mb-1"><strong>Date:</strong> {{ date('d-m-Y', strtotime($data->created_at)) }}</p>
                                    <p class="mb-1">
                                        <strong>Status:</strong>
                                        @if($data->payment_status == 'PAID')
                                            <span class="badge bg-success">PAID</span>
                                        @else 
                                            <span class="badge bg-danger">{{ $data->payment_status }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Table Items -->
                            <div class="table-responsive mt-4">
                                <table class="table table-bordered table-invoice">
                                    <thead>
                                        <tr>
                                            <th style="width:50px;">#</th>
                                            <th>Description</th>
                                            <th class="text-center" style="width:120px;">Qty</th>
                                            <th class="text-end" style="width:150px;">Unit Price</th>
                                            <th class="text-end" style="width:160px;">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>QBSC - {{ $data->paket->nama_paket ?? '' }} 
                                                {{ $data->paket->periode === 1 ? '(Bulanan)' : '(Tahunan)' }}
                                            </td>
                                            <td class="text-center">1</td>
                                            <td class="text-end">Rp. {{ number_format($data->amount) }}</td>
                                            <td class="text-end">Rp. {{ number_format($data->amount) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Summary -->
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h6 class="fw-bold">Notes:</h6>
                                    <p class="text-muted">
                                        Terima kasih telah menggunakan layanan kami.<br>
                                        Jika ada pertanyaan mengenai invoice ini, silakan hubungi kami kapan saja.
                                    </p>
                                </div>

                                <div class="col-md-6">
                                    <div class="summary-box float-md-end">
                                        <p><strong>Subtotal:</strong> <span class="float-end">Rp. {{ number_format($data->amount) }}</span></p>
                                        <p><strong>Tax :</strong> <span class="float-end">Rp. 0</span></p>
                                        <h3 class="mt-3">Total: Rp. {{ number_format($data->amount) }}</h3>
                                    </div>
                                </div>
                            </div>

                            <div class="d-print-none text-end mt-4">
                                <a href="javascript:window.print()" class="btn btn-primary">
                                    <i class="mdi mdi-printer"></i> Print Invoice
                                </a>
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

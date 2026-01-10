@extends('reseller.master')
@section('reseller')

	
    <div class="content-page">
        <div class="content">

            <!-- Start Content-->
            <div class="container-fluid">

                <div class="row">
                    <div class="col-xl-12">
                        <div class="card overflow-hidden border-top-0">
                            <div class="progress progress-sm rounded-0 bg-light" role="progressbar" aria-valuenow="88"
                                aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar bg-success" style="width: 90%"></div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="">
                                        <p class="text-muted fw-semibold fs-16 mb-1">LInk untuk dibagikan ke calon client
                                            agar masuk ke referal Anda.</p>
                                        <p class="text-muted mb-4">
                                            <input
                                                value="https://app.qbsc.cloud/r/register/{{ Auth::guard('reseller')->user()->referal_code }}"
                                                type="text" class="form-control" id="referal_url" readonly>

                                        <p><a onclick="copy_link()" href="javascript:void(0);">Copy Link</a></p>
                                        </p>
                                    </div>
                                    <div class="avatar-sm mb-4">
                                        <div class="avatar-title bg-primary-subtle text-primary fs-24 rounded">
                                            <i class="bi bi-receipt"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap flex-lg-nowrap justify-content-between align-items-end">


                                </div>
                            </div><!-- end card-body -->
                        </div><!-- end card -->
                    </div><!-- end col -->


                    <div class="col-xl-4">
                        <div class="card overflow-hidden border-top-0">
                            <div class="progress progress-sm rounded-0 bg-light" role="progressbar" aria-valuenow="88"
                                aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar bg-primary" style="width: 90%"></div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="">
                                        <p class="text-muted fw-semibold fs-16 mb-1">User Terdaftar</p>
                                        <p class="text-muted mb-4">

                                            User Referal Anda yang Daftar
                                        </p>
                                    </div>
                                    <div class="avatar-sm mb-4">
                                        <div class="avatar-title bg-primary-subtle text-primary fs-24 rounded">
                                            <i class="bi bi-receipt"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap flex-lg-nowrap justify-content-between align-items-end">
                                    <h3 class="mb-0 d-flex">{{ $active->count() }} </h3>
                                    <div class="d-flex align-items-end h-100">
                                        <div id="daily-orders" data-colors="#007aff"></div>
                                    </div>
                                </div>
                            </div><!-- end card-body -->
                        </div><!-- end card -->
                    </div><!-- end col -->

                    <div class="col-xl-4">
                        <div class="card overflow-hidden border-top-0">
                            <div class="progress progress-sm rounded-0 bg-light" role="progressbar" aria-valuenow="88"
                                aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar bg-dark" style="width: 40%"></div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="">
                                        <p class="text-muted fw-semibold fs-16 mb-1">User Berbayar</p>
                                        <p class="text-muted mb-4"><span class="badge bg-danger-subtle text-danger">User
                                                Referal Anda yang Berlangganan
                                        </p>
                                    </div>
                                    <div class="avatar-sm mb-4">
                                        <div class="avatar-title bg-dark-subtle text-dark fs-24 rounded">
                                            <i class="bi bi-people"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap flex-lg-nowrap justify-content-between align-items-end">
                                    <h3 class="mb-0 d-flex">{{ $subscriber->count() }}</h3>
                                    <div class="d-flex align-items-end h-100">
                                        <div id="new-leads-chart" data-colors="#404040"></div>
                                    </div>
                                </div>
                            </div><!-- end card-body -->
                        </div><!-- end card -->
                    </div><!-- end col -->

                    <div class="col-xl-4">
                        <div class="card overflow-hidden border-top-0">
                            <div class="progress progress-sm rounded-0 bg-light" role="progressbar" aria-valuenow="88"
                                aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar bg-danger" style="width: 60%"></div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="">
                                        <p class="text-muted fw-semibold fs-16 mb-1">Poin Reward</p>
                                        <p class="text-muted mb-4">
                                            <span class="badge bg-success-subtle text-success">
                                                Jumlah Poin Anda yang bisa Di Withdraw
                                        </p>
                                    </div>
                                    <div class="avatar-sm mb-4">
                                        <div class="avatar-title bg-danger-subtle text-danger fs-24 rounded">
                                            <i class="bi bi-clipboard-data"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap flex-lg-nowrap justify-content-between align-items-end">
                                    <h3 class="mb-0 d-flex">Rp. {{ number_format($fee_remain) }}</h3>
                                    <div class="d-flex align-items-end h-100">
                                        <div id="booked-revenue-chart" data-colors="#bb3939"></div>
                                    </div>
                                </div>
                            </div><!-- end card-body -->
                        </div><!-- end card -->
                    </div><!-- end col -->
                </div><!-- end row -->

                <div class="row">

                    <div class="col-lg-12 order-1 order-lg-2">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div class="">
                                    <h4 class="card-title">Ringkasan</h4>
                                    <p class="text-muted fw-semibold mb-0">Ringkasan Pendapatan Anda</p>
                                </div><!-- end card-header -->

                                <!-- end dropdown -->
                            </div>
                            <div class="card-body">
                                <div class="pt-3 show">
                                    <div id="revenue-report" data-colors="#007aff, #3f3f46" class="apex-charts"
                                        dir="ltr"></div>

                                    <div class="row text-center">
                                        <div class="col">
                                            <p class="text-muted mt-3">Total Subscribe</p>
                                            <h3 class="mb-0">
                                                <span>Rp. {{ number_format($total_subscribe) }}</span>
                                            </h3>
                                        </div>
                                        <div class="col">
                                            <p class="text-muted mt-3">Total Poin {{ $reseller->percent_fee }}% Anda</p>
                                            <h3 class=" mb-0">
                                               
                                                <span>Rp. {{ number_format($total_fee) }}</span>
                                            </h3>
                                        </div>
                                        <div class="col">
                                            <p class="text-muted mt-3">Total Penarikan Anda</p>
                                            <h3 class="mb-0">
                                               
                                                <span>Rp. {{ number_format($total_withdraw) }}</span>
                                            </h3>
                                        </div>
                                        <div class="col">
                                            <p class="text-muted mt-3">Sisa Poin Reward Anda</p>
                                            <h3 class=" mb-0">
                                                <span>Rp. {{ number_format($fee_remain) }}</span>
                                            </h3>
                                        </div>
                                    </div>
                                </div>

                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col-->
                </div><!-- end row -->


                <div class="row">

                    <div class="col-xxl-8 order-2 order-lg-1">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between flex-wrap align-items-center">
                                <div>
                                    <h4 class="card-title">Pendaftaran Terbaru</h4>
                                    <p class="text-muted fw-semibold mb-0">Diurutkan berdasarkan tanggal pendaftaran</p>
                                </div>

                                <div class="">
                                    <a class="btn btn-outline-primary">
                                        Lihat Semua
                                    </a>

                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table align-middle mb-0">
                                        <thead>
                                            <tr class="table-light text-capitalize">
                                                <th>Perusahaan</th>
                                                <th>Jenis</th>
                                                <th>User</th>
                                                <th>Paket</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <!-- end table heading -->

                                        <tbody>
                                            @foreach ($recent as $act)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm">
																@php
																	$src = asset('template/frontend').'/assets/images/users/avatar-9.jpg';
																	if($act->owner->profile_image != null) {
																		$src = asset('storage/'.$act->owner->profile_image);
																	} 
																@endphp

                                                                <img style="width:80px;height:50px;Object-fit:cover;" src="{{ $src }}"
                                                                    alt="" class="img-fluid rounded-circle">
                                                            </div>
                                                            <div class="ps-2">
                                                                <h5 class="mb-1">{{ $act->company_name }}</h5>
                                                                <p class="text-muted fs-6 mb-0">
                                                                    {{ $act->owner->email ?? '' }}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="fw-semibold">{{ $act->is_peternakan == 1 ? 'Peternakan' : 'Reguler' }}</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="mb-0 ms-1">{{ $act->owner->name ?? '' }}</h5>
                                                    </td>
                                                    <td>
                                                        <h5 class="mb-0">{{ $act->paket->nama_paket ?? '-' }}</h5>
                                                        
                                                            <p class="text-muted fs-6 mb-0">{{ $act->expired_date == null  ? '': date('d F Y', strtotime($act->expired_date))}}
                                                            </p>
                                                        
                                                    </td>
                                                    <td>
                                                        <h5 class="mb-0 ms-1"><?= $act->is_active == 1 ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Tidak Aktif</span>' ;?></h5>
                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <!-- end table body -->
                                    </table>
                                    <!-- end table -->
                                </div>
                            </div>
                        </div>
                    </div><!-- end col-->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->

            <!-- Footer Start -->
            @include('frontend.footer')
            <!-- end Footer -->

        </div>
        <!-- content -->
    </div>
@endsection

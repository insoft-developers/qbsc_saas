

@extends('frontend.master')

 @section('content')
 <div class="content-page">
     <div class="content">

         <!-- Start Content-->
         <div class="container-fluid">

             <div class="row">
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                 <div class="col-xl-4">
                     <div class="card overflow-hidden border-top-0">
                         <div class="progress progress-sm rounded-0 bg-light" role="progressbar" aria-valuenow="88"
                             aria-valuemin="0" aria-valuemax="100">
                             <div class="progress-bar bg-primary" style="width: 90%"></div>
                         </div>
                         <div class="card-body">
                             <div class="d-flex align-items-center justify-content-between">
                                 <div class="">
                                     <p class="text-muted fw-semibold fs-16 mb-1">Jumlah Satpam</p>
                                     
                                 </div>
                                 <div class="avatar-sm mb-4">
                                     <div class="avatar-title bg-primary-subtle text-primary fs-24 rounded">
                                         <i class="bi bi-people"></i>
                                     </div>
                                 </div>
                             </div>
                             <div class="d-flex flex-wrap flex-lg-nowrap justify-content-between align-items-end">
                                 <h3 class="mb-0 d-flex">{{$satpams}} </h3>
                                 
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
                                     <p class="text-muted fw-semibold fs-16 mb-1">Jumlah Lokasi</p>
                                     
                                 </div>
                                 <div class="avatar-sm mb-4">
                                     <div class="avatar-title bg-dark-subtle text-dark fs-24 rounded">
                                         <i class="bi bi-building"></i>
                                     </div>
                                 </div>
                             </div>
                             <div class="d-flex flex-wrap flex-lg-nowrap justify-content-between align-items-end">
                                 <h3 class="mb-0 d-flex">{{ $locations }} </h3>
                                 
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
                                     <p class="text-muted fw-semibold fs-16 mb-1">Jumlah User</p>
                                     
                                 </div>
                                 <div class="avatar-sm mb-4">
                                     <div class="avatar-title bg-danger-subtle text-danger fs-24 rounded">
                                         <i class="bi bi-person"></i>
                                     </div>
                                 </div>
                             </div>
                             <div class="d-flex flex-wrap flex-lg-nowrap justify-content-between align-items-end">
                                 <h3 class="mb-0 d-flex">{{ $users }} </h3>
                                 
                             </div>
                         </div><!-- end card-body -->
                     </div><!-- end card -->
                 </div><!-- end col -->
             </div><!-- end row -->

             <div class="row">
                 

                 <div class="col-xxl-8 order-2 order-lg-1">
                     <div class="card">
                         <div class="card-header d-flex justify-content-between flex-wrap align-items-center">
                             <div>
                                 <h4 class="card-title">Absen Terbaru</h4>
                                 
                             </div>

                             <div class="">
                                 <a href="{{ url('/absensi') }}" class="btn btn-outline-primary">
                                     Lihat Semua
                                 </a>
                                 <a onclick="refresh_absensi()" class="btn btn-outline-success">
                                     Refresh
                                 </a>

                             </div>
                         </div>
                         <div class="card-body p-0">
                             <div class="table-responsive">
                                 <table id="table-dashboard-absensi" class="table align-middle mb-0">
                                     <thead>
                                         <tr class="table-light text-capitalize">
                                             <th>Nama Satpam</th>
                                             <th>Tanggal</th>
                                             <th>Shift</th>
                                             <th>Masuk</th>
                                             <th>Pulang</th>
                                             <th>Lokasi</th>
                                             <th>Keterangan</th>
                                         </tr>
                                     </thead>
                                     <!-- end table heading -->

                                     <tbody>
                                     </tbody>
                                     <!-- end table body -->
                                 </table>
                                 <!-- end table -->
                             </div>
                         </div>
                     </div>
                 </div><!-- end col-->
             </div>

             <div class="row">
                 

                 <div class="col-xxl-8 order-2 order-lg-1">
                     <div class="card">
                         <div class="card-header d-flex justify-content-between flex-wrap align-items-center">
                             <div>
                                 <h4 class="card-title">Patroli Terbaru</h4>
                                 
                             </div>

                             <div class="">
                                 <a href="{{ url('/patroli') }}" class="btn btn-outline-primary">
                                     Lihat Semua
                                 </a>
                                 <a onclick="refresh_patroli()" class="btn btn-outline-success">
                                     Refresh
                                 </a>

                             </div>
                         </div>
                         <div class="card-body p-0">
                             <div class="table-responsive">
                                 <table id="table-dashboard-patroli" class="table align-middle mb-0">
                                     <thead>
                                         <tr class="table-light text-capitalize">
                                             <th>Nama Satpam</th>
                                             <th>Tanggal</th>
                                             <th>Jam</th>
                                             <th>Titik Point</th>
                                             <th>Lokasi</th>
                                             <th>Foto</th>
                                             <th>Keterangan</th>
                                         </tr>
                                     </thead>
                                     <!-- end table heading -->

                                     <tbody>
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

         @include('frontend.footer')

     </div>
     <!-- content -->
     
 </div>
 
 @endsection

 @push('scripts')
    @include('frontend.dashboard.dashboard_js')
@endpush


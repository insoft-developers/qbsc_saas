 @extends('frontend.master')

 @section('content')
 <div class="content-page">
     <div class="content">

         <!-- Start Content-->
         <div class="container-fluid">

             <div class="row">
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
                                         <i class="bi bi-receipt"></i>
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
                                         <i class="bi bi-people"></i>
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
                                         <i class="bi bi-clipboard-data"></i>
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
                 <div class="col-xxl-4 order-1 order-lg-2">
                     <div class="card">
                         <div class="card-header d-flex align-items-center">
                             <div class="flex-grow-1">
                                 <h4 class="card-title">Orders Status</h4>
                                 <p class="text-muted fw-semibold mb-0">Your Orders</p>
                             </div>
                             <div class="dropdown">
                                 <a href="#" class="dropdown-toggle arrow-none card-drop"
                                     data-bs-toggle="dropdown" aria-expanded="false">
                                     <i class="ri-more-2-fill"></i>
                                 </a>
                                 <div class="dropdown-menu dropdown-menu-end">
                                     <!-- item-->
                                     <a href="javascript:void(0);" class="dropdown-item">Sales Report</a>
                                     <!-- item-->
                                     <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                     <!-- item-->
                                     <a href="javascript:void(0);" class="dropdown-item">Profit</a>
                                     <!-- item-->
                                     <a href="javascript:void(0);" class="dropdown-item">Action</a>
                                 </div>
                             </div>
                         </div>
                         <div class="card-body p-0">
                             <div class="timeline-alt p-3">
                                 <div class="timeline-item">
                                     <i class="mdi mdi-upload bg-info-subtle text-info timeline-icon"></i>
                                     <div class="timeline-item-info">
                                         <a href="javascript:void(0);" class="text-info fw-bold mb-1 d-block">You sold
                                             an item</a>
                                         <small>Paul Burgess just purchased “Hyper - Admin
                                             Dashboard”!</small>
                                         <p class="mb-0 pb-2">
                                             <small class="text-muted">5 minutes ago</small>
                                         </p>
                                     </div>
                                 </div>

                                 <div class="timeline-item">
                                     <i class="mdi mdi-airplane bg-primary-subtle text-primary timeline-icon"></i>
                                     <div class="timeline-item-info">
                                         <a href="javascript:void(0);"
                                             class="text-primary fw-bold mb-1 d-block">Product on the
                                             Bootstrap Market</a>
                                         <small>Dave Gamache added
                                             <span class="fw-bold">Admin Dashboard</span>
                                         </small>
                                         <p class="mb-0 pb-2">
                                             <small class="text-muted">30 minutes ago</small>
                                         </p>
                                     </div>
                                 </div>

                                 <div class="timeline-item">
                                     <i class="mdi mdi-microphone bg-info-subtle text-info timeline-icon"></i>
                                     <div class="timeline-item-info">
                                         <a href="javascript:void(0);" class="text-info fw-bold mb-1 d-block">Robert
                                             Delaney</a>
                                         <small>Send you message
                                             <span class="fw-bold">"Are you there?"</span>
                                         </small>
                                         <p class="mb-0 pb-2">
                                             <small class="text-muted">2 hours ago</small>
                                         </p>
                                     </div>
                                 </div>

                                 <div class="timeline-item">
                                     <i class="mdi mdi-upload bg-primary-subtle text-primary timeline-icon"></i>
                                     <div class="timeline-item-info">
                                         <a href="javascript:void(0);"
                                             class="text-primary fw-bold mb-1 d-block">Audrey Tobey</a>
                                         <small>Uploaded a photo
                                             <span class="fw-bold">"Error.jpg"</span>
                                         </small>
                                         <p class="mb-0 pb-2">
                                             <small class="text-muted">14 hours ago</small>
                                         </p>
                                     </div>
                                 </div>

                                 <div class="timeline-item">
                                     <i class="mdi mdi-upload bg-info-subtle text-info timeline-icon"></i>
                                     <div class="timeline-item-info">
                                         <a href="javascript:void(0);" class="text-info fw-bold mb-1 d-block">You sold
                                             an item</a>
                                         <small>Paul Burgess just purchased “Hyper - Admin
                                             Dashboard”!</small>
                                         <p class="mb-0 pb-2">
                                             <small class="text-muted">16 hours ago</small>
                                         </p>
                                     </div>
                                 </div>

                                 <div class="timeline-item">
                                     <i class="mdi mdi-airplane bg-primary-subtle text-primary timeline-icon"></i>
                                     <div class="timeline-item-info">
                                         <a href="javascript:void(0);"
                                             class="text-primary fw-bold mb-1 d-block">Product on the
                                             Bootstrap Market</a>
                                         <small>Dave Gamache added
                                             <span class="fw-bold">Admin Dashboard</span>
                                         </small>
                                         <p class="mb-0 pb-2">
                                             <small class="text-muted">22 hours ago</small>
                                         </p>
                                     </div>
                                 </div>

                                 <div class="timeline-item">
                                     <i class="mdi mdi-microphone bg-info-subtle text-info timeline-icon"></i>
                                     <div class="timeline-item-info">
                                         <a href="javascript:void(0);" class="text-info fw-bold mb-1 d-block">Robert
                                             Delaney</a>
                                         <small>Send you message
                                             <span class="fw-bold">"Are you there?"</span>
                                         </small>
                                         <p class="mb-0">
                                             <small class="text-muted">2 days ago</small>
                                         </p>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>

                 <div class="col-xxl-8 order-2 order-lg-1">
                     <div class="card">
                         <div class="card-header d-flex justify-content-between flex-wrap align-items-center">
                             <div>
                                 <h4 class="card-title">Absen Terbaru</h4>
                                 
                             </div>

                             <div class="">
                                 <a class="btn btn-outline-primary">
                                     Lihat Semua
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


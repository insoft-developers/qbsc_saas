<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>QBSC Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully responsive admin theme which can be used to build CRM, CMS,ERP etc." name="description" />
    <meta content="Techzaa" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">


    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('images/satpam128.png') }}">
    <!-- Datatables css -->
    <link href="{{ asset('template/frontend') }}/assets/vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link
        href="{{ asset('template/frontend') }}/assets/vendor/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link
        href="{{ asset('template/frontend') }}/assets/vendor/datatables.net-fixedcolumns-bs5/css/fixedColumns.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link
        href="{{ asset('template/frontend') }}/assets/vendor/datatables.net-fixedheader-bs5/css/fixedHeader.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link
        href="{{ asset('template/frontend') }}/assets/vendor/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('template/frontend') }}/assets/vendor/datatables.net-select-bs5/css/select.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />

    <!-- Daterangepicker css -->
    <link rel="stylesheet" href="{{ asset('template/frontend') }}/assets/vendor/daterangepicker/daterangepicker.css">

    <!-- Vector Map css -->
    <link rel="stylesheet"
        href="{{ asset('template/frontend') }}/assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css">

    <!-- Theme Config Js -->
    <script src="{{ asset('template/frontend') }}/assets/js/config.js"></script>

    <!-- App css -->
    <link href="{{ asset('template/frontend') }}/assets/css/app.min.css" rel="stylesheet" type="text/css"
        id="app-style" />

    <!-- Icons css -->
    <link href="{{ asset('template/frontend') }}/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    @include('frontend/css')



</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        <!-- ========== Topbar Start ========== -->
        <div class="navbar-custom">
            <div class="topbar container-fluid">
                <div class="d-flex align-items-center gap-1">

                    <!-- Topbar Brand Logo -->
                    <div class="logo-topbar">
                        <!-- Logo light -->
                        <a href="index.html" class="logo-light">
                            <span class="logo-lg">
                                <img src="{{ asset('images/satpam500.png') }}" alt="logo">
                            </span>
                            <span class="logo-sm">
                                <img src="{{ asset('images/satpam500.png') }}" alt="small logo">
                            </span>
                        </a>

                        <!-- Logo Dark -->
                        <a href="index.html" class="logo-dark">
                            <span class="logo-lg">
                                <img src="{{ asset('images/satpam500.png') }}" alt="dark logo">
                            </span>
                            <span class="logo-sm">
                                <img src="{{ asset('images/satpam500.png') }}" alt="small logo">
                            </span>
                        </a>
                    </div>

                    <!-- Sidebar Menu Toggle Button -->
                    <button class="button-toggle-menu">
                        <i class="mdi mdi-menu"></i>
                    </button>

                    <!-- Page Title -->
                    @php
                        $dcomid = Auth::user()->company_id;
                        $company = \App\Models\Company::find($dcomid);

                    @endphp
                    <h4 class="page-title d-none d-sm-block">{{ $company->company_name ?? 'Insoft Developers' }}</h4>
                </div>

                <ul class="topbar-menu d-flex align-items-center gap-3">
                    <li class="dropdown d-lg-none">
                        <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#"
                            role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="mdi mdi-magnify fs-2"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-animated dropdown-lg p-0">
                            <form class="p-3">
                                <input type="search" class="form-control" placeholder="Search ..."
                                    aria-label="Recipient's username">
                            </form>
                        </div>
                    </li>

                    <li class="dropdown notification-list notif-list">
                        <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#"
                            role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="ri-notification-3-line fs-22"></i>
                            <span class="noti-icon-badge badge text-bg-pink"><span id="notif_count"></span></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated dropdown-lg py-0">
                            <div class="p-2 border-top-0 border-start-0 border-end-0 border-dashed border">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0 fs-16 fw-semibold"> Notifikasi</h6>
                                    </div>
                                    <div class="col-auto">
                                        <a href="javascript: void(0);" class="text-dark text-decoration-underline">
                                            <small></small>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div style="max-height: 600px;" data-simplebar id="notif-list-container">
                                <!-- item-->
                            </div>

                            <!-- All-->
                            <a href="{{ url('notifikasi') }}"
                                class="dropdown-item text-center text-primary text-decoration-underline fw-bold notify-item border-top border-light py-2">
                                Lihat Semua
                            </a>

                        </div>
                    </li>

                    <li class="d-none d-sm-inline-block">
                        <a class="nav-link" data-bs-toggle="offcanvas" href="#theme-settings-offcanvas">
                            <span class="ri-settings-3-line fs-22"></span>
                        </a>
                    </li>

                    <li class="d-none d-sm-inline-block">
                        <div class="nav-link" id="light-dark-mode">
                            <i class="ri-moon-line fs-22"></i>
                        </div>
                    </li>
                    @php
                        $users = \App\Models\User::find(Auth::user()->id);
                    @endphp

                    <li class="dropdown">
                        <a class="nav-link dropdown-toggle arrow-none nav-user" data-bs-toggle="dropdown"
                            href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <span class="account-user-avatar">
                                <img src="{{ $users->profile_image == null ? asset('images/default.png') : asset('storage/' . $users->profile_image) }}"
                                    alt="user-image" width="32" height="32" class="rounded-circle">
                            </span>
                            <span class="d-lg-block d-none">
                                <h5 class="my-0 fw-normal">{{ Auth::user()->name }}<i
                                        class="ri-arrow-down-s-line fs-22 d-none d-sm-inline-block align-middle"></i>
                                </h5>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated profile-dropdown">
                            <!-- item-->
                            <div class=" dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Welcome !</h6>
                            </div>

                            <!-- item-->
                            <a href="{{ url('profile') }}" class="dropdown-item">
                                <i class="ri-account-pin-circle-line fs-16 align-middle me-1 "></i>
                                <span>Profil</span>
                            </a>

                            <a href="{{ url('change_password') }}" class="dropdown-item">
                                <i class="ri-lock-unlock-line fs-16 align-middle me-1"></i>
                                <span>Ganti Password</span>
                            </a>

                            <!-- item-->
                            <a href="{{ url('perusahaan') }}" class="dropdown-item">
                                <i class="ri-building-4-line fs-16 align-middle me-1"></i>
                                <span>Perusahaan</span>
                            </a>

                            <!-- item-->
                            <a href="{{ url('riwayat') }}" class="dropdown-item">
                                <i class="ri-exchange-dollar-line
 fs-16 align-middle me-1"></i>
                                <span>Paket Saya</span>
                            </a>

                            @if (Auth::user()->level == 'owner')
                                <a href="{{ url('generate_key_id') }}" class="dropdown-item">
                                    <i class="ri-shield-keyhole-line
 fs-16 align-middle me-1"></i>
                                    <span>Generate Key ID</span>
                                </a>
                            @endif

                            <!-- item-->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                    href="{{ route('logout') }}" class="dropdown-item">
                                    <i class="ri-logout-circle-r-line align-middle me-1"></i>
                                    <span>Logout</span>
                                </a>
                            </form>

                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <!-- ========== Topbar End ========== -->

        @include('frontend.sidebar')


        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        @yield('content')

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <!-- Theme Settings -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="theme-settings-offcanvas">
        <div class="d-flex align-items-center bg-primary p-3 offcanvas-header">
            <h5 class="text-white m-0">Theme Settings</h5>
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>

        <div class="offcanvas-body p-0">
            <div data-simplebar class="h-100">
                <div class="p-3">
                    <h5 class="mb-3 fs-16 fw-semibold">Color Scheme</h5>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-check mb-1">
                                <input class="form-check-input border-secondary" type="radio" name="data-bs-theme"
                                    id="layout-color-light" value="light">
                                <label class="form-check-label" for="layout-color-light">Light</label>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-check mb-1">
                                <input class="form-check-input border-secondary" type="radio" name="data-bs-theme"
                                    id="layout-color-dark" value="dark">
                                <label class="form-check-label" for="layout-color-dark">Dark</label>
                            </div>
                        </div>
                    </div>

                    <div id="layout-width">
                        <h5 class="my-3 fs-16 fw-semibold">Layout Mode</h5>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-check mb-1">
                                    <input class="form-check-input border-secondary" type="radio"
                                        name="data-layout-mode" id="layout-mode-fluid" value="fluid">
                                    <label class="form-check-label" for="layout-mode-fluid">Fluid</label>
                                </div>
                            </div>

                            <div class="col-6">
                                <div id="layout-boxed">
                                    <div class="form-check mb-1">
                                        <input class="form-check-input border-secondary" type="radio"
                                            name="data-layout-mode" id="layout-mode-boxed" value="boxed">
                                        <label class="form-check-label" for="layout-mode-boxed">Boxed</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 class="my-3 fs-16 fw-semibold">Topbar Color</h5>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-check mb-1">
                                <input class="form-check-input border-secondary" type="radio"
                                    name="data-topbar-color" id="topbar-color-light" value="light">
                                <label class="form-check-label" for="topbar-color-light">Light</label>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-check mb-1">
                                <input class="form-check-input border-secondary" type="radio"
                                    name="data-topbar-color" id="topbar-color-dark" value="dark">
                                <label class="form-check-label" for="topbar-color-dark">Dark</label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h5 class="my-3 fs-16 fw-semibold">Menu Color</h5>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-check mb-1">
                                    <input class="form-check-input border-secondary" type="radio"
                                        name="data-menu-color" id="leftbar-color-light" value="light">
                                    <label class="form-check-label" for="leftbar-color-light">Light</label>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-check mb-1">
                                    <input class="form-check-input border-secondary" type="radio"
                                        name="data-menu-color" id="leftbar-color-dark" value="dark">
                                    <label class="form-check-label" for="leftbar-color-dark">Dark</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="sidebar-size">
                        <h5 class="my-3 fs-16 fw-semibold">Sidebar Size</h5>

                        <div class="row gap-2">
                            <div class="col-12">
                                <div class="form-check mb-1">
                                    <input class="form-check-input border-secondary" type="radio"
                                        name="data-sidenav-size" id="leftbar-size-default" value="default">
                                    <label class="form-check-label" for="leftbar-size-default">Default</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-check mb-1">
                                    <input class="form-check-input border-secondary" type="radio"
                                        name="data-sidenav-size" id="leftbar-size-compact" value="compact">
                                    <label class="form-check-label" for="leftbar-size-compact">Compact</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-check mb-1">
                                    <input class="form-check-input border-secondary" type="radio"
                                        name="data-sidenav-size" id="leftbar-size-small" value="condensed">
                                    <label class="form-check-label" for="leftbar-size-small">Condensed</label>
                                </div>
                            </div>


                            <div class="col-12">
                                <div class="form-check mb-1">
                                    <input class="form-check-input border-secondary" type="radio"
                                        name="data-sidenav-size" id="leftbar-size-full" value="full">
                                    <label class="form-check-label" for="leftbar-size-full">Full Layout</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="layout-position">
                        <h5 class="my-3 fs-16 fw-semibold">Layout Position</h5>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="data-layout-position"
                                        id="layout-position-fixed" value="fixed">
                                    <label class="form-check-label" for="layout-position-fixed">Fixed</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="data-layout-position"
                                        id="layout-position-scrollable" value="scrollable">
                                    <label class="form-check-label"
                                        for="layout-position-scrollable">Scrollable</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


       


        <div class="offcanvas-footer border-top p-3 text-center">
            <div class="row">
                <div class="col-6">
                    {{-- <button type="button" class="btn btn-light w-100" id="reset-layout">Reset</button> --}}
                </div>
                <div class="col-6">
                    {{-- <a href="#" role="button" class="btn btn-primary w-100">Buy Now</a> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="wa-container">
        <div class="wa-bubble">
            <strong>Butuh bantuan?</strong>
            <span>Chat Admin QBSC</span>
        </div>

        <a href="https://wa.me/6282165174835?text=Halo%20Admin%20QBSC,%20saya%20butuh%20bantuan" target="_blank"
            class="wa-float" aria-label="Chat WhatsApp">
            <i class="mdi mdi-whatsapp"></i>
            <span class="wa-status"></span>
        </a>
    </div>

     <!-- Standard modal content -->
        <div id="modal-peternakan" class="modal fade" tabindex="-1" role="dialog"
            aria-labelledby="standard-modalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="form-peternakan" method="POST">
                        {{ csrf_field() }}
                        <div class="modal-header">
                            <h4 class="modal-title" id="standard-modalLabel">Pilih Jenis Perusahaan Anda</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-2">
                                        <label for="name" class="form-label">Jenis Perusahaan</label>
                                        <select id="jenis_perusahaan" name="jenis_perusahaan" class="form-control">
                                            <option value="1">Perusahaan Peternakan</option>
                                            <option value="2">Perusahaan Lainnya</option>
                                        </select>

                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button id="btn-save-data" type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->


    <!-- Vendor js -->
    <script src="{{ asset('template/frontend') }}/assets/js/vendor.min.js"></script>

    <script src="{{ asset('template/frontend') }}/assets/vendor/lucide/umd/lucide.min.js"></script>

    <!-- Datatables js -->
    <script src="{{ asset('template/frontend') }}/assets/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('template/frontend') }}/assets/vendor/datatables.net-bs5/js/dataTables.bootstrap5.min.js">
    </script>
    <script src="{{ asset('template/frontend') }}/assets/vendor/datatables.net-responsive/js/dataTables.responsive.min.js">
    </script>
    <script
        src="{{ asset('template/frontend') }}/assets/vendor/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js">
    </script>
    <script
        src="{{ asset('template/frontend') }}/assets/vendor/datatables.net-fixedcolumns-bs5/js/fixedColumns.bootstrap5.min.js">
    </script>
    <script
        src="{{ asset('template/frontend') }}/assets/vendor/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js">
    </script>
    <script src="{{ asset('template/frontend') }}/assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js">
    </script>
    <script src="{{ asset('template/frontend') }}/assets/vendor/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js">
    </script>
    <script src="{{ asset('template/frontend') }}/assets/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="{{ asset('template/frontend') }}/assets/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="{{ asset('template/frontend') }}/assets/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="{{ asset('template/frontend') }}/assets/vendor/datatables.net-keytable/js/dataTables.keyTable.min.js">
    </script>
    <script src="{{ asset('template/frontend') }}/assets/vendor/datatables.net-select/js/dataTables.select.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <!-- Apex Charts js -->
    @if ($view == 'dashboard')
        {{-- <script src="{{ asset('template/frontend') }}/assets/vendor/apexcharts/apexcharts.min.js"></script> --}}

        <!-- Vector Map js -->
        <script
            src="{{ asset('template/frontend') }}/assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js">
        </script>
        <script
            src="{{ asset('template/frontend') }}/assets/vendor/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js">
        </script>



        <!-- Dashboard App js -->
        {{-- <script src="{{ asset('template/frontend') }}/assets/js/pages/dashboard.js"></script> --}}
    @endif
    <!-- App js -->
    <script src="{{ asset('template/frontend') }}/assets/js/app.min.js"></script>

    <script>
        check_perusahaan();
        function check_perusahaan() {
            $.ajax({
                url: "{{ route('check.jenis.perusahaan') }}",
                type: "POST",
                dataType: "JSON",
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    if(data.success) {
                        $("#modal-peternakan").modal("show");
                    }
                }
            })
        }


        $("#form-peternakan").submit(function(e){
            e.preventDefault();
            var jenis_perusahaan = $("#jenis_perusahaan").val();
            $.ajax({
                url:"{{ route('update.jenis.perusahaan') }}",
                type:"POST",
                dataType:"JSON",
                data: $(this).serialize(),
                success: function(data) {
                      location.reload(true);

                }
            });
        })

        function formatRupiah(angka) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
        }

        function loading(id) {
            $("#" + id).text("Processing.....");
            $("#" + id).attr("disabled", true);
        }

        function unloading(id, text) {
            $("#" + id).text(text);
            $("#" + id).removeAttr("disabled");
        }

        function generateCode(prefix) {
            const randomNumber = Math.floor(10000000 + Math.random() * 90000000); // 8 digit random
            return prefix + randomNumber;
        }

        function formatTanggal(tanggal) {
            const [tahun, bulan, hari] = tanggal.split("-");
            return `${hari}-${bulan}-${tahun}`;
        }

        function formatTanggalWaktu(tanggalWaktu) {
            const [tanggal, waktu] = tanggalWaktu.split(" ");
            const [tahun, bulan, hari] = tanggal.split("-");
            return `${hari}-${bulan}-${tahun} ${waktu}`;
        }

        function formatTgl(tgl) {
            const d = new Date(tgl);

            const hari = String(d.getDate()).padStart(2, '0');
            const bulan = String(d.getMonth() + 1).padStart(2, '0'); // bulan mulai 0
            const tahun = d.getFullYear();

            const jam = String(d.getHours()).padStart(2, '0');
            const menit = String(d.getMinutes()).padStart(2, '0');

            return `${hari}-${bulan}-${tahun} ${jam}:${menit}`;
        }

        setInterval(() => {
            check_notif();
        }, 10000);

        function check_notif() {
            $.ajax({
                url: "{{ route('check.notif') }}",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    console.log(data);
                    $("#notif_count").text(data.count);
                }

            });
        }

        $(".notif-list").click(function() {
            $.ajax({
                url: "{{ route('notif.list') }}",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    var html = '';
                    for (var i = 0; i < data.length; i++) {
                        const is_read = data[i].is_read;
                        const comid = "{{ $dcomid }}";

                        const list = is_read.split(','); // ubah string jadi array
                        const isExist = list.includes(comid); // cek apakah ada

                        var warna = 'whitesmoke';
                        if (isExist) {
                            warna = 'white';
                        }


                        html += `<a href="{{ url('/notifikasi') }}/${data[i].id}" class="dropdown-item notify-item" style="background:${warna};border-bottom: 1px solid rgb(213, 209, 209);">
                                    <div class="notify-icon bg-primary-subtle">

                                       <i class="mdi mdi-account text-primary"></i>
                                    </div>
                                    <p class="notify-details">${data[i].judul}
                                        <small class="noti-time">${ formatTgl(data[i].created_at) }</small>
                                    </p>
                                </a>`;
                    }

                    $("#notif-list-container").html(html);
                }
            })
        });
    </script>
    @stack('scripts')

</body>

</html>

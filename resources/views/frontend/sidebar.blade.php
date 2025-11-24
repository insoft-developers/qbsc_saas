<!-- Left Sidebar Start -->
<div class="leftside-menu">

    <!-- Logo Light -->
    <a href="{{ url('/') }}" class="logo logo-light">
        <span class="logo-lg">
            <img src="{{ asset('images/satpam500.png') }}" alt="logo">
        </span>
        <span class="logo-sm">
            <img src="{{ asset('images/satpam512.png') }}" alt="small logo">
        </span>
    </a>

    <!-- Logo Dark -->
    <a href="{{ url('/') }}" class="logo logo-dark">
        <span class="logo-lg">
            <img src="{{ asset('images/satpam500.png') }}" alt="logo">
        </span>
        <span class="logo-sm">
            <img src="{{ asset('images/satpam512.png') }}" alt="small logo">
        </span>
    </a>

    <!-- Sidebar -->
    <div class="simplebar" data-simplebar>
        <ul class="side-nav">
            <li class="side-nav-item">
                <a href="{{ url('/') }}" class="side-nav-link">
                    <i class="ri-dashboard-2-line"></i>
                    <span> Dashboard </span>

                </a>
            </li>

            
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarPagesinvoice" aria-expanded="false"
                    aria-controls="sidebarPagesinvoice" class="side-nav-link">
                    <i class=" ri-database-2-line"></i>
                    <span>Master Data</span>
                    <span class="menu-arrow"></span>

                </a>
                <div class="collapse" id="sidebarPagesinvoice">
                    <ul class="side-nav-second-level">
                        <li class="side-nav-item">
                            <a class="side-nav-link" href="{{ url('satpam') }}">Data Satpam</a>
                        </li>
                        <li class="side-nav-item">
                            <a class="side-nav-link" href="{{ url('lokasi') }}">Data Lokasi</a>
                        </li>
                        <li class="side-nav-item">
                            <a class="side-nav-link" href="{{ url('user') }}">Data User</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarIcons" aria-expanded="false"
                    aria-controls="sidebarIcons" class="side-nav-link">
                    <i class="ri-home-office-line"></i>
                    <span>Farm</span>
                    <span class="menu-arrow"></span>

                </a>
                <div class="collapse" id="sidebarIcons">
                    <ul class="side-nav-second-level">
                        <li class="side-nav-item">
                            <a class="side-nav-link" href="{{ url('kandang') }}">Data Kandang</a>
                        </li>
                       
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarExtendedUI" aria-expanded="false"
                    aria-controls="sidebarExtendedUI" class="side-nav-link">
                    <i class="ri-home-gear-line"></i>
                    <span>Hatchery</span>
                    <span class="menu-arrow"></span>

                </a>
                <div class="collapse" id="sidebarExtendedUI">
                    <ul class="side-nav-second-level">
                        {{-- <li class="side-nav-item">
                            <a class="side-nav-link" href="{{ url('mesin') }}">Data Mesin</a>
                        </li> --}}
                        <li class="side-nav-item">
                            <a class="side-nav-link" href="{{ url('ekspedisi') }}">Data Ekspedisi</a>
                        </li>
                        
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarPagesAuth" aria-expanded="false"
                    aria-controls="sidebarPagesAuth" class="side-nav-link">
                    <i class=" ri-todo-line"></i>
                    <span>Aktivitas</span>
                    <span class="menu-arrow"></span>

                </a>
                <div class="collapse" id="sidebarPagesAuth">
                    <ul class="side-nav-second-level">
                        <li class="side-nav-item">
                            <a class="side-nav-link" href="{{ url('absensi') }}">Absensi</a>
                        </li>
                        <li class="side-nav-item">
                            <a class="side-nav-link" href="{{  url('patroli') }}">Patroli</a>
                        </li>
                        <li class="side-nav-item">
                            <a class="side-nav-link" href="{{  url('patroli_kandang') }}">Patroli Kandang</a>
                        </li>
                        <li class="side-nav-item">
                            <a class="side-nav-link" href="{{  url('doc_out') }}">DOC Keluar</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarLayouts" aria-expanded="false"
                    aria-controls="sidebarLayouts" class="side-nav-link">
                    <i class="ri-article-line"></i>
                    <span>Laporan</span>
                    <span class="menu-arrow"></span>

                </a>
                <div class="collapse" id="sidebarLayouts">
                    <ul class="side-nav-second-level">
                        <li class="side-nav-item">
                            <a class="side-nav-link" href="{{ url('laporan_situasi') }}">Laporan Situasi</a>
                        </li>
                        <li class="side-nav-item">
                            <a class="side-nav-link" href="apps-invoice.html">Invoice</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidbarBaseUI" aria-expanded="false"
                    aria-controls="sidbarBaseUI" class="side-nav-link">
                    <i class="ri-file-settings-line"></i>
                    <span>Pengaturan</span>
                    <span class="menu-arrow"></span>

                </a>
                <div class="collapse" id="sidbarBaseUI">
                    <ul class="side-nav-second-level">
                        <li class="side-nav-item">
                            <a class="side-nav-link" href="{{ url('absen_location') }}">Lokasi Absen</a>
                        </li>
                        <li class="side-nav-item">
                            <a class="side-nav-link" href="{{ url('jam_shift') }}">Jam Shift</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarTables" aria-expanded="false"
                    aria-controls="sidebarTables" class="side-nav-link">
                    <i class=" ri-money-dollar-circle-line"></i>
                    <span>Beli Paket</span>
                    <span class="menu-arrow"></span>

                </a>
                <div class="collapse" id="sidebarTables">
                    <ul class="side-nav-second-level">
                        <li class="side-nav-item">
                            <a class="side-nav-link" href="apps-invoice-report.html">Invoice Report</a>
                        </li>
                        <li class="side-nav-item">
                            <a class="side-nav-link" href="apps-invoice.html">Invoice</a>
                        </li>
                    </ul>
                </div>
            </li>


        </ul>
    </div>
</div>
<!-- Left Sidebar End -->

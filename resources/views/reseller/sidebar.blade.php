<!-- Left Sidebar Start -->
<div class="leftside-menu">

    <!-- Logo Light -->
    <a href="{{ url('/reseller') }}" class="logo logo-light">
        <span class="logo-lg">
            <img src="{{ asset('images/new_icon.webp') }}" alt="logo">
        </span>
        <span class="logo-sm">
            <img src="{{ asset('images/satpam512.png') }}" alt="small logo">
        </span>
    </a>

    <!-- Logo Dark -->
    <a href="{{ url('/') }}" class="logo logo-dark">
        <span class="logo-lg">
            <img src="{{ asset('images/new_icon.webp') }}" alt="logo">
        </span>
        <span class="logo-sm">
            <img src="{{ asset('images/satpam512.png') }}" alt="small logo">
        </span>
    </a>

    <!-- Sidebar -->
    <div class="simplebar" data-simplebar>
        <ul class="side-nav">
            <li class="side-nav-item">
                <a href="{{ url('/reseller') }}" class="side-nav-link">
                    <i class="ri-dashboard-2-line"></i>
                    <span> Dashboard </span>

                </a>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarLayouts" aria-expanded="false"
                    aria-controls="sidebarLayouts" class="side-nav-link">
                    <i class="ri-article-line"></i>
                    <span>Referal</span>
                    <span class="menu-arrow"></span>

                </a>
                <div class="collapse" id="sidebarLayouts">
                    <ul class="side-nav-second-level">
                        <li class="side-nav-item">
                            <a class="side-nav-link" href="{{ url('reseller/user') }}">User</a>
                        </li>
                        <li class="side-nav-item">
                            <a class="side-nav-link" href="{{ url('tamu') }}">Transaksi</a>
                        </li>
                        <li class="side-nav-item">
                            <a class="side-nav-link" href="{{ url('tamu') }}">Withdraw</a>
                        </li>
                        
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a href="{{ url('/notifikasi') }}" class="side-nav-link">
                    <i class="ri-notification-line"></i>
                    <span> Notifikasi </span>

                </a>
            </li>
           
            <li class="side-nav-item">
                <a href="{{ url('/asset_page') }}" class="side-nav-link">
                    <i class="ri-newspaper-line"></i>
                    <span> Download Aplikasi </span>

                </a>
            </li>

        </ul>
        <div style="margin-top: 200px;"></div>
    </div>
</div>
<!-- Left Sidebar End -->

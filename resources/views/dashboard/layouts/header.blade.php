<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{ route('admin.dashboard') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('dash/assets/images/logo-sm.svg') }}" alt="" height="30">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('dash/assets/images/logo-sm.svg') }}" alt="" height="24"> <span class="logo-txt">صوتك أمانة</span>
                    </span>
                </a>

                <a href="{{ route('admin.dashboard') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('dash/assets/images/logo-sm.svg') }}" alt="" height="30">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('dash/assets/images/logo-sm.svg') }}" alt="" height="24"> <span class="logo-txt">صوتك أمانة</span>
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>


        </div>

        <div class="d-flex">

 

     

            <div class="dropdown d-none d-sm-inline-block">
                <button type="button" class="btn header-item" id="mode-setting-btn">
                    <i data-feather="moon" class="icon-lg layout-mode-dark"></i>
                    <i data-feather="sun" class="icon-lg layout-mode-light"></i>
                </button>
            </div>


            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item bg-light-subtle border-start border-end" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{-- <img class="rounded-circle header-profile-user" src="{{ Auth::user()->profile_image ? asset(Auth::user()->profile_image) : asset('dash/assets/images/users/avatar-1.jpg') }}" alt="Header Avatar"> --}}
                    <span class="d-none d-xl-inline-block ms-1 fw-medium">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    {{-- <a class="dropdown-item" ><i class="mdi mdi-face-profile font-size-16 align-middle me-1"></i> الملف الشخصي</a> --}}
                    {{-- <div class="dropdown-divider"></div> --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="mdi mdi-logout font-size-16 align-middle me-1"></i> تسجيل الخروج
                        </a>
                    </form>
                </div>
            </div>

        </div>
    </div>
</header>
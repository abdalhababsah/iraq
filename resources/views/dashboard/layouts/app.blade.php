<!doctype html>
<html lang="en" dir="rtl">
    <head>
        <meta charset="utf-8" />
        <title>لوحة التحكم | صوتك أمانة - نظام إدارة الانتخابات</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="نظام إدارة الانتخابات المتقدم - صوتك أمانة. إدارة شاملة للمرشحين والدوائر الانتخابية بأعلى معايير الشفافية والأمان" name="description" />
        <meta content="نظام صوتك أمانة للانتخابات" name="author" />
        <meta name="keywords" content="انتخابات, إدارة انتخابات, مرشحين, دوائر انتخابية, صوتك أمانة, نظام انتخابي, شفافية انتخابية" />
        <meta property="og:title" content="لوحة التحكم | صوتك أمانة - نظام إدارة الانتخابات" />
        <meta property="og:description" content="نظام إدارة الانتخابات المتقدم لضمان الشفافية والأمان في العملية الانتخابية" />
        <meta property="og:type" content="website" />
        <meta property="og:locale" content="ar_AR" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="صوتك أمانة - نظام إدارة الانتخابات" />
        <meta name="twitter:description" content="نظام إدارة الانتخابات المتقدم لضمان الشفافية والأمان" />
        <meta name="robots" content="noindex, nofollow" />
        <meta name="theme-color" content="#556ee6" />
        
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('dash/assets/images/favicon.ico') }}">
    
        <!-- plugin css -->
        <link href="{{ asset('dash/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}"
            rel="stylesheet" type="text/css" />
    
        <!-- preloader css -->
        <link rel="stylesheet" href="{{ asset('dash/assets/css/preloader.min.css') }}" type="text/css" />
    
        <!-- Bootstrap Css -->
        <link href="{{ asset('dash/assets/css/bootstrap-rtl.min.css') }}" id="bootstrap-style" rel="stylesheet"
            type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('dash/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset('dash/assets/css/app-rtl.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    </head>

<body data-topbar="dark">

    <!-- <body data-layout="horizontal"> -->

    <!-- Begin page -->
    <div id="layout-wrapper">


        @include('dashboard.layouts.header')

        <!-- ========== Left Sidebar Start ========== -->

        <!-- Left Sidebar End -->
        @include('dashboard.layouts.sidebar')
        <div class="main-content">

            @yield('content')
            {{-- <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>
                                document.write(new Date().getFullYear())
                            </script> © Dason.
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Design & Develop by <a href="#!" class="text-decoration-underline">Themesbrand</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer> --}}
        </div>
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
    
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->


    <!-- Right Sidebar -->
    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    <script src="{{ asset('dash/assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('dash/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dash/assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('dash/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('dash/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('dash/assets/libs/feather-icons/feather.min.js') }}"></script>
    <!-- pace js -->
    <script src="{{ asset('dash/assets/libs/pace-js/pace.min.js') }}"></script>

    <!-- apexcharts -->
    <script src="{{ asset('dash/assets/libs/apexcharts/apexcharts.min.js') }}"></script>

    <!-- Plugins js-->
    <script src="{{ asset('dash/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ asset('dash/assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js') }}">
    </script>

    <script src="{{ asset('dash/assets/js/pages/allchart.js') }}"></script>
    <!-- dashboard init -->
    <script src="{{ asset('dash/assets/js/pages/dashboard.init.js') }}"></script>

    <script src="{{ asset('dash/assets/js/app.js') }}"></script>

</body>

</html>

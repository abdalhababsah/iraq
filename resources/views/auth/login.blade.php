<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8" />
    <title>تسجيل الدخول | نظام إدارة الانتخابات</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="نظام إدارة الانتخابات والمرشحين" name="description" />
    <meta content="Iraq Elections" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('dash/assets/images/favicon.ico') }}">

    <!-- preloader css -->
    <link rel="stylesheet" href="{{ asset('dash/assets/css/preloader.min.css') }}" type="text/css" />

    <!-- Bootstrap Css -->
    <link href="{{ asset('dash/assets/css/bootstrap-rtl.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('dash/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('dash/assets/css/app-rtl.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

</head>

<body data-topbar="dark">
    <div class="auth-page">
        <div class="container-fluid p-0">
            <div class="row g-0">
                <div class="col-xxl-3 col-lg-4 col-md-5">
                    <div class="auth-full-page-content d-flex p-sm-5 p-4">
                        <div class="w-100">
                            <div class="d-flex flex-column h-100">
                                <div class="mb-4 mb-md-5 text-center">
                                    <a href="{{ route('login') }}" class="d-block auth-logo">
                                        <img src="{{ asset('dash/assets/images/logo-sm.svg') }}" alt="" height="28"> 
                                        <span class="logo-txt">نظام الانتخابات</span>
                                    </a>
                                </div>
                                <div class="auth-content my-auto">
                                    <div class="text-center">
                                        <h5 class="mb-0">أهلاً بك مرة أخرى!</h5>
                                        <p class="text-muted mt-2">سجل دخولك للمتابعة إلى النظام</p>
                                    </div>

                                    <!-- Session Status -->
                                    @if (session('status'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{ session('status') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif

                                    <!-- Validation Errors -->
                                    @if ($errors->any())
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif

                                    <form class="mt-4 pt-2" method="POST" action="{{ route('login') }}">
                                        @csrf
                                        
                                        <div class="form-floating form-floating-custom mb-4">
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                   id="input-email" name="email" value="{{ old('email') }}" 
                                                   placeholder="أدخل البريد الإلكتروني" required autofocus autocomplete="username">
                                            <label for="input-email">البريد الإلكتروني</label>
                                            <div class="form-floating-icon">
                                               <i data-feather="mail"></i>
                                            </div>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-floating form-floating-custom mb-4 auth-pass-inputgroup">
                                            <input type="password" class="form-control pe-5 @error('password') is-invalid @enderror" 
                                                   id="password-input" name="password" placeholder="أدخل كلمة المرور" required autocomplete="current-password">
                                            
                                            <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" id="password-addon">
                                                <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                                            </button>
                                            <label for="password-input">كلمة المرور</label>
                                            <div class="form-floating-icon">
                                                <i data-feather="lock"></i>
                                            </div>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col">
                                                <div class="form-check font-size-15">
                                                    <input class="form-check-input" type="checkbox" id="remember-check" name="remember">
                                                    <label class="form-check-label font-size-13" for="remember-check">
                                                        تذكرني
                                                    </label>
                                                </div>  
                                            </div>
                                            <div class="col-auto">
                                                @if (Route::has('password.request'))
                                                    <a href="{{ route('password.request') }}" class="text-muted font-size-13">
                                                        نسيت كلمة المرور؟
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">تسجيل الدخول</button>
                                        </div>
                                    </form>

                                    <div class="mt-5 text-center">
                                        <p class="text-muted mb-0">ليس لديك حساب؟ <a href="#" class="text-primary fw-semibold">تواصل مع الإدارة</a></p>
                                    </div>
                                </div>
                                {{-- <div class="mt-4 mt-md-5 text-center">
                                    <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> نظام إدارة الانتخابات. تم التطوير بواسطة <i class="mdi mdi-heart text-danger"></i> فريق التطوير</p>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                    <!-- end auth full page content -->
                </div>
                <!-- end col -->
                <div class="col-xxl-9 col-lg-8 col-md-7">
                    <div class="auth-bg pt-md-5 p-4 d-flex">
                        <div class="bg-overlay"></div>
                        <ul class="bg-bubbles">
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                        </ul>
                  
                    </div>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container fluid -->
    </div>

    <!-- JAVASCRIPT -->
    <script src="{{ asset('dash/assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('dash/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dash/assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('dash/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('dash/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('dash/assets/libs/feather-icons/feather.min.js') }}"></script>
    <!-- pace js -->
    <script src="{{ asset('dash/assets/libs/pace-js/pace.min.js') }}"></script>

    <script src="{{ asset('dash/assets/js/pages/pass-addon.init.js') }}"></script>
    <script src="{{ asset('dash/assets/js/pages/feather-icon.init.js') }}"></script>

</body>

</html>
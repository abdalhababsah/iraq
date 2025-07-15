<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8" />
    <title>إنشاء حساب جديد | نظام إدارة الانتخابات</title>
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
                                        <h5 class="mb-0">إنشاء حساب جديد</h5>
                                        <p class="text-muted mt-2">أنشئ حسابك للوصول إلى النظام</p>
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

                                    {{-- <form class="mt-4 pt-2" method="POST" action="{{ route('register') }}">
                                        @csrf
                                        
                                        <!-- First Name & Last Name -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating form-floating-custom mb-4">
                                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                                           id="input-firstname" name="first_name" value="{{ old('first_name') }}" 
                                                           placeholder="أدخل الاسم الأول" required autofocus autocomplete="given-name">
                                                    <label for="input-firstname">الاسم الأول</label>
                                                    <div class="form-floating-icon">
                                                       <i data-feather="user"></i>
                                                    </div>
                                                    @error('first_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating form-floating-custom mb-4">
                                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                                           id="input-lastname" name="last_name" value="{{ old('last_name') }}" 
                                                           placeholder="أدخل الاسم الأخير" required autocomplete="family-name">
                                                    <label for="input-lastname">الاسم الأخير</label>
                                                    <div class="form-floating-icon">
                                                       <i data-feather="user"></i>
                                                    </div>
                                                    @error('last_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Email -->
                                        <div class="form-floating form-floating-custom mb-4">
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                   id="input-email" name="email" value="{{ old('email') }}" 
                                                   placeholder="أدخل البريد الإلكتروني" required autocomplete="username">
                                            <label for="input-email">البريد الإلكتروني</label>
                                            <div class="form-floating-icon">
                                               <i data-feather="mail"></i>
                                            </div>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Password -->
                                        <div class="form-floating form-floating-custom mb-4 auth-pass-inputgroup">
                                            <input type="password" class="form-control pe-5 @error('password') is-invalid @enderror" 
                                                   id="password-input" name="password" placeholder="أدخل كلمة المرور" required autocomplete="new-password">
                                            
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

                                        <!-- Confirm Password -->
                                        <div class="form-floating form-floating-custom mb-4 auth-pass-inputgroup">
                                            <input type="password" class="form-control pe-5 @error('password_confirmation') is-invalid @enderror" 
                                                   id="password-confirm-input" name="password_confirmation" placeholder="أكد كلمة المرور" required autocomplete="new-password">
                                            
                                            <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" id="password-confirm-addon">
                                                <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                                            </button>
                                            <label for="password-confirm-input">تأكيد كلمة المرور</label>
                                            <div class="form-floating-icon">
                                                <i data-feather="lock"></i>
                                            </div>
                                            @error('password_confirmation')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Terms & Conditions -->
                                        <div class="row mb-4">
                                            <div class="col">
                                                <div class="form-check font-size-15">
                                                    <input class="form-check-input" type="checkbox" id="terms-check" required>
                                                    <label class="form-check-label font-size-13" for="terms-check">
                                                        أوافق على <a href="#" class="text-primary">الشروط والأحكام</a>
                                                    </label>
                                                </div>  
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">إنشاء الحساب</button>
                                        </div>
                                    </form> --}}

                                    <div class="mt-4 text-center">
                                        <p class="text-muted mb-0">لديك حساب بالفعل؟ <a href="{{ route('login') }}" class="text-primary fw-semibold">سجل دخولك هنا</a></p>
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
                        <!-- end bubble effect -->
                        {{-- <div class="row justify-content-center align-items-end">
                            <div class="col-xl-7">
                                <div class="p-0 p-sm-4 px-xl-0">
                                    <div id="reviewcarouselIndicators" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-indicators auth-carousel carousel-indicators-rounded justify-content-center mb-0">
                                            <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1">
                                                <img src="{{ asset('dash/assets/images/users/avatar-1.jpg') }}" class="avatar-md img-fluid rounded-circle d-block" alt="...">
                                            </button>
                                            <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="1" aria-label="Slide 2">
                                                <img src="{{ asset('dash/assets/images/users/avatar-2.jpg') }}" class="avatar-md img-fluid rounded-circle d-block" alt="...">
                                            </button>
                                            <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="2" aria-label="Slide 3">
                                                <img src="{{ asset('dash/assets/images/users/avatar-3.jpg') }}" class="avatar-md img-fluid rounded-circle d-block" alt="...">
                                            </button>
                                        </div>
                                        <!-- end carouselIndicators -->
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <div class="testi-contain text-center text-white">
                                                    <i class="bx bxs-quote-alt-left text-success display-6"></i>
                                                    <h4 class="mt-4 fw-medium lh-base text-white">
                                                        "انضم إلى نظام إدارة الانتخابات المتقدم واحصل على حساب آمن وموثوق"
                                                    </h4>
                                                    <div class="mt-4 pt-1 pb-5 mb-5">
                                                        <h5 class="font-size-16 text-white">سارة أحمد</h5>
                                                        <p class="mb-0 text-white-50">مديرة الحسابات</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="carousel-item">
                                                <div class="testi-contain text-center text-white">
                                                    <i class="bx bxs-quote-alt-left text-success display-6"></i>
                                                    <h4 class="mt-4 fw-medium lh-base text-white">
                                                        "عملية التسجيل سهلة وسريعة مع ضمان حماية البيانات الشخصية بأعلى معايير الأمان"
                                                    </h4>
                                                    <div class="mt-4 pt-1 pb-5 mb-5">
                                                        <h5 class="font-size-16 text-white">محمد علي</h5>
                                                        <p class="mb-0 text-white-50">مسؤول تقني</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="carousel-item">
                                                <div class="testi-contain text-center text-white">
                                                    <i class="bx bxs-quote-alt-left text-success display-6"></i>
                                                    <h4 class="mt-4 fw-medium lh-base text-white">
                                                        "ابدأ رحلتك في إدارة العملية الانتخابية من خلال حساب موثق ومحمي"
                                                    </h4>
                                                    <div class="mt-4 pt-1 pb-5 mb-5">
                                                        <h5 class="font-size-16 text-white">ليلى حسن</h5>
                                                        <p class="mb-0 text-white-50">مديرة العمليات</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end carousel-inner -->
                                    </div>
                                    <!-- end review carousel -->
                                </div>
                            </div>
                        </div> --}}
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

    <script>
        // Password confirmation show/hide
        document.getElementById('password-confirm-addon').addEventListener('click', function() {
            const passwordInput = document.getElementById('password-confirm-input');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('mdi-eye-outline');
                icon.classList.add('mdi-eye-off-outline');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('mdi-eye-off-outline');
                icon.classList.add('mdi-eye-outline');
            }
        });
    </script>

</body>

</html>
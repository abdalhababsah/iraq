@extends('dashboard.layouts.app')

@section('content')

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">الملف الشخصي</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">لوحة التحكم</a></li>
                            <li class="breadcrumb-item active">الملف الشخصي</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- Profile Completion Alert -->
        @if($profileCompletion < 100)
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-information me-2"></i>
                    اكتمال الملف الشخصي: <strong>{{ $profileCompletion }}%</strong>
                    <small class="d-block mt-1">يرجى إكمال جميع البيانات لزيادة فرص ظهور ملفك الشخصي</small>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        @endif

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="row">
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check-all me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-block-helper me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        @endif

        <form action="{{ route('candidate.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Profile Banner Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="profile-banner-wrapper position-relative">
                                <div class="profile-banner" style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); position: relative; overflow: hidden;">
                                    @if($candidate->profile_banner_image)
                                        <img src="{{ $candidate->profile_banner_image_url }}" alt="صورة الغلاف" 
                                             class="w-100 h-100" style="object-fit: cover;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100">
                                            <div class="text-center text-white">
                                                <i class="mdi mdi-image font-size-48 mb-2"></i>
                                                <p class="mb-0">لا توجد صورة غلاف</p>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Banner Image Controls -->
                                    <div class="position-absolute top-0 end-0 m-3">
                                        <div class="btn-group">
                                            <label for="profile_banner_image" class="btn btn-sm btn-light">
                                                <i class="mdi mdi-camera"></i> تغيير الغلاف
                                            </label>
                                            @if($candidate->profile_banner_image)
                                                <a href="{{ route('candidate.profile.remove-banner') }}" 
                                                   class="btn btn-sm btn-danger"
                                                   onclick="return confirm('هل أنت متأكد من حذف صورة الغلاف؟')">
                                                    <i class="mdi mdi-delete"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Profile Image Section -->
                                <div class="position-absolute" style="bottom: -50px; right: 30px;">
                                    <div class="profile-image-wrapper position-relative">
                                        <div class="avatar-xl profile-user-wid mb-4 position-relative">
                                            @if($candidate->profile_image)
                                                <img src="{{ $candidate->profile_image_url }}" alt="صورة المرشح" 
                                                     class="img-thumbnail rounded-circle" 
                                                     style="width: 100px; height: 100px; object-fit: cover; border: 4px solid white;">
                                            @else
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" 
                                                     style="width: 100px; height: 100px; border: 4px solid white;">
                                                    <i class="mdi mdi-account font-size-24 text-muted"></i>
                                                </div>
                                            @endif
                                            
                                            <!-- Profile Image Controls -->
                                            <div class="position-absolute bottom-0 end-0">
                                                <div class="btn-group">
                                                    <label for="profile_image" class="btn btn-sm btn-primary rounded-circle" 
                                                           style="width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                                        <i class="mdi mdi-camera font-size-12"></i>
                                                    </label>
                                                    @if($candidate->profile_image)
                                                        <a href="{{ route('candidate.profile.remove-image') }}" 
                                                           class="btn btn-sm btn-danger rounded-circle ms-1"
                                                           style="width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center;"
                                                           onclick="return confirm('هل أنت متأكد من حذف الصورة الشخصية؟')">
                                                            <i class="mdi mdi-delete font-size-12"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hidden File Inputs -->
                                <input type="file" id="profile_image" name="profile_image" 
                                       accept="image/*" style="display: none;" 
                                       onchange="previewImage(this, 'profile-preview')">
                                <input type="file" id="profile_banner_image" name="profile_banner_image" 
                                       accept="image/*" style="display: none;"
                                       onchange="previewBanner(this)">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Information -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">المعلومات الأساسية</h4>
                            <p class="card-title-desc">معلومات المرشح الأساسية والمطلوبة</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label">الاسم الأول <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                               id="first_name" name="first_name" 
                                               value="{{ old('first_name', $candidate->user->first_name) }}" required>
                                        @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="last_name" class="form-label">الاسم الأخير <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                               id="last_name" name="last_name" 
                                               value="{{ old('last_name', $candidate->user->last_name) }}" required>
                                        @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" 
                                               value="{{ old('email', $candidate->user->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" name="phone" 
                                               value="{{ old('phone', $candidate->phone) }}" required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="constituency_id" class="form-label">الدائرة الانتخابية <span class="text-danger">*</span></label>
                                        <select class="form-select @error('constituency_id') is-invalid @enderror" 
                                                id="constituency_id" name="constituency_id" required>
                                            <option value="">اختر الدائرة الانتخابية</option>
                                            @foreach($constituencies as $constituency)
                                                <option value="{{ $constituency->id }}" 
                                                        {{ (old('constituency_id', $candidate->constituency_id) == $constituency->id) ? 'selected' : '' }}>
                                                    {{ $constituency->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('constituency_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="party_bloc_name" class="form-label">اسم الكتلة أو الحزب <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('party_bloc_name') is-invalid @enderror" 
                                               id="party_bloc_name" name="party_bloc_name" 
                                               value="{{ old('party_bloc_name', $candidate->party_bloc_name) }}" required>
                                        @error('party_bloc_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="biography" class="form-label">السيرة الذاتية <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('biography') is-invalid @enderror" 
                                          id="biography" name="biography" rows="4" required 
                                          placeholder="اكتب سيرتك الذاتية أو عرف عن نفسك">{{ old('biography', $candidate->biography) }}</textarea>
                                @error('biography')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Optional Information -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">معلومات إضافية</h4>
                            <p class="card-title-desc">معلومات اختيارية لإثراء ملفك الشخصي</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="list_number" class="form-label">رقم القائمة أو الكتلة</label>
                                        <input type="text" class="form-control @error('list_number') is-invalid @enderror" 
                                               id="list_number" name="list_number" 
                                               value="{{ old('list_number', $candidate->list_number) }}">
                                        @error('list_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="current_position" class="form-label">المنصب الحالي أو السابق</label>
                                        <input type="text" class="form-control @error('current_position') is-invalid @enderror" 
                                               id="current_position" name="current_position" 
                                               value="{{ old('current_position', $candidate->current_position) }}">
                                        @error('current_position')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="campaign_slogan" class="form-label">شعار الحملة الانتخابية</label>
                                <input type="text" class="form-control @error('campaign_slogan') is-invalid @enderror" 
                                       id="campaign_slogan" name="campaign_slogan" 
                                       value="{{ old('campaign_slogan', $candidate->campaign_slogan) }}"
                                       placeholder="أدخل شعار حملتك الانتخابية">
                                @error('campaign_slogan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="achievements" class="form-label">إنجازات المرشح</label>
                                <textarea class="form-control @error('achievements') is-invalid @enderror" 
                                          id="achievements" name="achievements" rows="3" 
                                          placeholder="اذكر أهم إنجازاتك">{{ old('achievements', $candidate->achievements) }}</textarea>
                                @error('achievements')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="experience" class="form-label">الخبرة العملية</label>
                                <textarea class="form-control @error('experience') is-invalid @enderror" 
                                          id="experience" name="experience" rows="3" 
                                          placeholder="اذكر خبرتك العملية والمهنية">{{ old('experience', $candidate->experience) }}</textarea>
                                @error('experience')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="skills" class="form-label">المهارات</label>
                                <textarea class="form-control @error('skills') is-invalid @enderror" 
                                          id="skills" name="skills" rows="3" 
                                          placeholder="اذكر مهاراتك وخبراتك">{{ old('skills', $candidate->skills) }}</textarea>
                                @error('skills')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="voter_promises" class="form-label">ماذا ستقدم للناخبين</label>
                                <textarea class="form-control @error('voter_promises') is-invalid @enderror" 
                                          id="voter_promises" name="voter_promises" rows="4" 
                                          placeholder="اذكر وعودك والخدمات التي ستقدمها للناخبين">{{ old('voter_promises', $candidate->voter_promises) }}</textarea>
                                @error('voter_promises')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="additional_info" class="form-label">معلومات إضافية</label>
                                <textarea class="form-control @error('additional_info') is-invalid @enderror" 
                                          id="additional_info" name="additional_info" rows="3" 
                                          placeholder="أي معلومات إضافية تود إضافتها">{{ old('additional_info', $candidate->additional_info) }}</textarea>
                                @error('additional_info')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Summary -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">ملخص الملف الشخصي</h4>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <div class="profile-completion">
                                    <div class="circular-progress" data-percentage="{{ $profileCompletion }}">
                                        <span class="progress-value">{{ $profileCompletion }}%</span>
                                    </div>
                                    <p class="text-muted mt-2">اكتمال الملف الشخصي</p>
                                </div>
                            </div>

                            <div class="profile-summary">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="mdi mdi-account-circle text-primary me-2"></i>
                                    <span class="text-muted">الاسم:</span>
                                    <span class="ms-auto">{{ $candidate->user->full_name }}</span>
                                </div>
                                
                                <div class="d-flex align-items-center mb-2">
                                    <i class="mdi mdi-email text-primary me-2"></i>
                                    <span class="text-muted">البريد الإلكتروني:</span>
                                    <span class="ms-auto">{{ $candidate->user->email }}</span>
                                </div>
                                
                                <div class="d-flex align-items-center mb-2">
                                    <i class="mdi mdi-map-marker text-primary me-2"></i>
                                    <span class="text-muted">الدائرة الانتخابية:</span>
                                    <span class="ms-auto">{{ $candidate->constituency->name ?? 'غير محدد' }}</span>
                                </div>
                                
                                <div class="d-flex align-items-center mb-2">
                                    <i class="mdi mdi-flag text-primary me-2"></i>
                                    <span class="text-muted">الكتلة/الحزب:</span>
                                    <span class="ms-auto">{{ $candidate->party_bloc_name }}</span>
                                </div>

                                @if($candidate->campaign_slogan)
                                <div class="mt-3 p-3 bg-light rounded">
                                    <h6 class="mb-1">شعار الحملة</h6>
                                    <p class="mb-0 text-muted">"{{ $candidate->campaign_slogan }}"</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">إجراءات سريعة</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('candidate.education') }}" class="btn btn-outline-primary">
                                    <i class="mdi mdi-school me-2"></i>إدارة المؤهلات العلمية
                                </a>
                                <a href="{{ route('candidate.dashboard') }}" class="btn btn-outline-info">
                                    <i class="mdi mdi-view-dashboard me-2"></i>العودة للوحة التحكم
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('candidate.dashboard') }}" class="btn btn-secondary">
                                    <i class="mdi mdi-arrow-left me-2"></i>إلغاء
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-content-save me-2"></i>حفظ التغييرات
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div> <!-- container-fluid -->
</div>

@endsection

@section('script')
<script>
    // Image preview functionality
    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = input.closest('.profile-image-wrapper').querySelector('img');
                if (img) {
                    img.src = e.target.result;
                } else {
                    // Create image if doesn't exist
                    var div = input.closest('.profile-image-wrapper').querySelector('.bg-light');
                    if (div) {
                        div.innerHTML = '<img src="' + e.target.result + '" class="img-thumbnail rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">';
                        div.className = '';
                    }
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewBanner(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var banner = input.closest('.profile-banner-wrapper').querySelector('.profile-banner');
                banner.innerHTML = '<img src="' + e.target.result + '" alt="صورة الغلاف" class="w-100 h-100" style="object-fit: cover;">' + 
                                 banner.querySelector('.position-absolute').outerHTML;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Profile completion circular progress
    document.addEventListener('DOMContentLoaded', function() {
        const progressElement = document.querySelector('.circular-progress');
        if (progressElement) {
            const percentage = progressElement.getAttribute('data-percentage');
            progressElement.style.background = `conic-gradient(#556ee6 ${percentage * 3.6}deg, #e9ecef 0deg)`;
        }
    });

    // Auto-resize textareas
    document.querySelectorAll('textarea').forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    });

    // Form validation feedback
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>
@endsection

@section('css')
<style>
    .profile-banner {
        border-radius: 0.375rem 0.375rem 0 0;
    }

    .circular-progress {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        margin: 0 auto;
    }

    .circular-progress::before {
        content: '';
        position: absolute;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: white;
    }

    .progress-value {
        position: relative;
        z-index: 1;
        font-weight: 600;
        color: #556ee6;
    }

    .profile-summary .d-flex {
        font-size: 0.875rem;
    }

    .profile-image-wrapper img {
        transition: all 0.3s ease;
    }

    .profile-image-wrapper:hover img {
        transform: scale(1.05);
    }

    .btn-group .btn {
        transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #556ee6;
        box-shadow: 0 0 0 0.2rem rgba(85, 110, 230, 0.25);
    }

    .card {
        box-shadow: 0 0.75rem 1.5rem rgba(18, 38, 63, 0.03);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .card-header {
        background-color: rgba(85, 110, 230, 0.05);
        border-bottom: 1px solid rgba(85, 110, 230, 0.1);
    }

    @media (max-width: 768px) {
        .profile-banner {
            height: 150px !important;
        }
        
        .position-absolute[style*="bottom: -50px"] {
            position: static !important;
            text-align: center;
            margin-top: -50px;
        }
    }
</style>
@endsection
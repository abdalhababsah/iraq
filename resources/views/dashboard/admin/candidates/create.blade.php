@extends('dashboard.layouts.app')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">إضافة مرشح جديد</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.candidates.index') }}">المرشحين</a></li>
                            <li class="breadcrumb-item active">إضافة مرشح جديد</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-all me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-block-helper me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <form action="{{ route('admin.candidates.store') }}" method="POST" enctype="multipart/form-data" id="candidate-form">
            @csrf
            
            <div class="row">
                <div class="col-xl-8 col-lg-8">
                    <!-- Basic Information Card -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-user me-2"></i>المعلومات الأساسية
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">الاسم الأول <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                               name="first_name" value="{{ old('first_name') }}" required>
                                        @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">الاسم الأخير <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                               name="last_name" value="{{ old('last_name') }}" required>
                                        @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">كلمة المرور <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                               name="password" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">تأكيد كلمة المرور <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                               name="password_confirmation" required>
                                        @error('password_confirmation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                               name="phone" value="{{ old('phone') }}" required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">الدائرة الانتخابية <span class="text-danger">*</span></label>
                                        <select class="form-select @error('constituency_id') is-invalid @enderror" name="constituency_id" required>
                                            <option value="">اختر الدائرة الانتخابية</option>
                                            @foreach($constituencies as $constituency)
                                                <option value="{{ $constituency->id }}" {{ old('constituency_id') == $constituency->id ? 'selected' : '' }}>
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
                                        <label class="form-label">اسم الكتلة أو الحزب <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('party_bloc_name') is-invalid @enderror" 
                                               name="party_bloc_name" value="{{ old('party_bloc_name') }}" required>
                                        @error('party_bloc_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">السيرة الذاتية <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('biography') is-invalid @enderror" 
                                          name="biography" rows="4" required>{{ old('biography') }}</textarea>
                                @error('biography')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Professional Information Card -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-briefcase me-2"></i>المعلومات المهنية
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">المنصب الحالي</label>
                                        <input type="text" class="form-control @error('current_position') is-invalid @enderror" 
                                               name="current_position" value="{{ old('current_position') }}">
                                        @error('current_position')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">رقم القائمة</label>
                                        <input type="text" class="form-control @error('list_number') is-invalid @enderror" 
                                               name="list_number" value="{{ old('list_number') }}">
                                        @error('list_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">الخبرة العملية</label>
                                <textarea class="form-control @error('experience') is-invalid @enderror" 
                                          name="experience" rows="3">{{ old('experience') }}</textarea>
                                @error('experience')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">الإنجازات</label>
                                <textarea class="form-control @error('achievements') is-invalid @enderror" 
                                          name="achievements" rows="3" 
                                          placeholder="اكتب كل إنجاز في سطر منفصل">{{ old('achievements') }}</textarea>
                                @error('achievements')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">المهارات</label>
                                <textarea class="form-control @error('skills') is-invalid @enderror" 
                                          name="skills" rows="2" 
                                          placeholder="افصل المهارات بعلامة (،)">{{ old('skills') }}</textarea>
                                @error('skills')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">معلومات إضافية</label>
                                <textarea class="form-control @error('additional_info') is-invalid @enderror" 
                                          name="additional_info" rows="3">{{ old('additional_info') }}</textarea>
                                @error('additional_info')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Campaign Information Card -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-megaphone me-2"></i>معلومات الحملة الانتخابية
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">شعار الحملة الانتخابية</label>
                                <input type="text" class="form-control @error('campaign_slogan') is-invalid @enderror" 
                                       name="campaign_slogan" value="{{ old('campaign_slogan') }}">
                                @error('campaign_slogan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">وعود للناخبين</label>
                                <textarea class="form-control @error('voter_promises') is-invalid @enderror" 
                                          name="voter_promises" rows="4">{{ old('voter_promises') }}</textarea>
                                @error('voter_promises')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Social Media Links Card -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-share-alt me-2"></i>روابط التواصل الاجتماعي
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">فيسبوك</label>
                                        <input type="url" class="form-control @error('facebook_link') is-invalid @enderror" 
                                               name="facebook_link" value="{{ old('facebook_link') }}" 
                                               placeholder="https://facebook.com/username">
                                        @error('facebook_link')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">لينكد إن</label>
                                        <input type="url" class="form-control @error('linkedin_link') is-invalid @enderror" 
                                               name="linkedin_link" value="{{ old('linkedin_link') }}" 
                                               placeholder="https://linkedin.com/in/username">
                                        @error('linkedin_link')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">إنستجرام</label>
                                        <input type="url" class="form-control @error('instagram_link') is-invalid @enderror" 
                                               name="instagram_link" value="{{ old('instagram_link') }}" 
                                               placeholder="https://instagram.com/username">
                                        @error('instagram_link')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">تويتر</label>
                                        <input type="url" class="form-control @error('twitter_link') is-invalid @enderror" 
                                               name="twitter_link" value="{{ old('twitter_link') }}" 
                                               placeholder="https://twitter.com/username">
                                        @error('twitter_link')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">يوتيوب</label>
                                        <input type="url" class="form-control @error('youtube_link') is-invalid @enderror" 
                                               name="youtube_link" value="{{ old('youtube_link') }}" 
                                               placeholder="https://youtube.com/channel/...">
                                        @error('youtube_link')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">تيك توك</label>
                                        <input type="url" class="form-control @error('tiktok_link') is-invalid @enderror" 
                                               name="tiktok_link" value="{{ old('tiktok_link') }}" 
                                               placeholder="https://tiktok.com/@username">
                                        @error('tiktok_link')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">الموقع الإلكتروني</label>
                                <input type="url" class="form-control @error('website_link') is-invalid @enderror" 
                                       name="website_link" value="{{ old('website_link') }}" 
                                       placeholder="https://example.com">
                                @error('website_link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-xl-4 col-lg-4">
                    <!-- Profile Images Card -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-image me-2"></i>الصور الشخصية
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Profile Image -->
                            <div class="mb-4">
                                <label class="form-label">الصورة الشخصية</label>
                                <div class="text-center mb-3">
                                    <div class="profile-preview" id="profile-preview">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                                             style="width: 120px; height: 120px;">
                                            <i class="mdi mdi-account font-size-48 text-muted"></i>
                                        </div>
                                    </div>
                                </div>
                                <input type="file" class="form-control @error('profile_image') is-invalid @enderror" 
                                       name="profile_image" accept="image/*" onchange="previewImage(this, 'profile-preview')">
                                <small class="text-muted">يُفضل الصور المربعة بحجم 400x400 بكسل</small>
                                @error('profile_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Banner Image -->
                            <div class="mb-3">
                                <label class="form-label">صورة الغلاف</label>
                                <div class="text-center mb-3">
                                    <div class="banner-preview" id="banner-preview">
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="width: 100%; height: 120px; border-radius: 8px;">
                                            <i class="mdi mdi-image font-size-48 text-muted"></i>
                                        </div>
                                    </div>
                                </div>
                                <input type="file" class="form-control @error('profile_banner_image') is-invalid @enderror" 
                                       name="profile_banner_image" accept="image/*" onchange="previewImage(this, 'banner-preview')">
                                <small class="text-muted">يُفضل الصور بحجم 1200x400 بكسل</small>
                                @error('profile_banner_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions Card -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-cog me-2"></i>إجراءات سريعة
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save me-2"></i>إنشاء المرشح
                                </button>
                                <a href="{{ route('admin.candidates.index') }}" class="btn btn-secondary">
                                    <i class="bx bx-arrow-back me-2"></i>العودة للقائمة
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Help Card -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-help-circle me-2"></i>مساعدة
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="help-item mb-3">
                                <h6 class="font-size-14 mb-1">الحقول المطلوبة</h6>
                                <p class="text-muted font-size-13 mb-0">
                                    الاسم الأول والأخير، البريد الإلكتروني، كلمة المرور، الدائرة الانتخابية، 
                                    الكتلة/الحزب، رقم الهاتف، والسيرة الذاتية
                                </p>
                            </div>
                            <div class="help-item mb-3">
                                <h6 class="font-size-14 mb-1">كلمة المرور</h6>
                                <p class="text-muted font-size-13 mb-0">
                                    يجب أن تكون 8 أحرف على الأقل وتتضمن أرقام وحروف
                                </p>
                            </div>
                            <div class="help-item">
                                <h6 class="font-size-14 mb-1">الصور</h6>
                                <p class="text-muted font-size-13 mb-0">
                                    الصور اختيارية ويمكن إضافتها لاحقاً. الحد الأقصى 5 ميجابايت
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">معاينة بيانات المرشح</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="preview-content">
                <!-- Content will be populated by JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                <button type="button" class="btn btn-primary" onclick="submitForm()">تأكيد وإنشاء</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            if (previewId === 'profile-preview') {
                preview.innerHTML = `
                    <img src="${e.target.result}" alt="معاينة الصورة الشخصية" 
                         class="img-fluid rounded-circle" 
                         style="width: 120px; height: 120px; object-fit: cover;">
                `;
            } else {
                preview.innerHTML = `
                    <img src="${e.target.result}" alt="معاينة صورة الغلاف" 
                         class="img-fluid" 
                         style="width: 100%; height: 120px; object-fit: cover; border-radius: 8px;">
                `;
            }
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

function previewForm() {
    const form = document.getElementById('candidate-form');
    const formData = new FormData(form);
    
    let previewHtml = `
        <div class="row">
            <div class="col-md-6">
                <h6>المعلومات الأساسية:</h6>
                <table class="table table-sm">
                    <tr><td><strong>الاسم:</strong></td><td>${formData.get('first_name')} ${formData.get('last_name')}</td></tr>
                    <tr><td><strong>البريد الإلكتروني:</strong></td><td>${formData.get('email')}</td></tr>
                    <tr><td><strong>الهاتف:</strong></td><td>${formData.get('phone')}</td></tr>
                    <tr><td><strong>الدائرة الانتخابية:</strong></td><td>${document.querySelector('[name="constituency_id"] option:checked').textContent}</td></tr>
                    <tr><td><strong>الكتلة/الحزب:</strong></td><td>${formData.get('party_bloc_name')}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>المعلومات المهنية:</h6>
                <table class="table table-sm">
                    <tr><td><strong>المنصب الحالي:</strong></td><td>${formData.get('current_position') || 'غير محدد'}</td></tr>
                    <tr><td><strong>رقم القائمة:</strong></td><td>${formData.get('list_number') || 'غير محدد'}</td></tr>
                    <tr><td><strong>شعار الحملة:</strong></td><td>${formData.get('campaign_slogan') || 'غير محدد'}</td></tr>
                </table>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <h6>السيرة الذاتية:</h6>
                <p class="text-muted">${formData.get('biography')}</p>
            </div>
        </div>
    `;
    
    document.getElementById('preview-content').innerHTML = previewHtml;
    new bootstrap.Modal(document.getElementById('previewModal')).show();
}

function submitForm() {
    document.getElementById('candidate-form').submit();
    bootstrap.Modal.getInstance(document.getElementById('previewModal')).hide();
}

function resetForm() {
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: 'سيتم مسح جميع البيانات المدخلة',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'نعم، امسح البيانات',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('candidate-form').reset();
            
            // Reset image previews
            document.getElementById('profile-preview').innerHTML = `
                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                     style="width: 120px; height: 120px;">
                    <i class="mdi mdi-account font-size-48 text-muted"></i>
                </div>
            `;
            
            document.getElementById('banner-preview').innerHTML = `
                <div class="bg-light d-flex align-items-center justify-content-center" 
                     style="width: 100%; height: 120px; border-radius: 8px;">
                    <i class="mdi mdi-image font-size-48 text-muted"></i>
                </div>
            `;
            
            Swal.fire('تم!', 'تم مسح جميع البيانات', 'success');
        }
    });
}

// Form validation
document.getElementById('candidate-form').addEventListener('submit', function(e) {
    const requiredFields = ['first_name', 'last_name', 'email', 'password', 'password_confirmation', 'phone', 'constituency_id', 'party_bloc_name', 'biography'];
    let isValid = true;
    let firstInvalidField = null;
    
    requiredFields.forEach(field => {
        const input = document.querySelector(`[name="${field}"]`);
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            if (!firstInvalidField) {
                firstInvalidField = input;
            }
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });
    
    // Check password confirmation
    const password = document.querySelector('[name="password"]').value;
    const passwordConfirmation = document.querySelector('[name="password_confirmation"]').value;
    
    if (password !== passwordConfirmation) {
        document.querySelector('[name="password_confirmation"]').classList.add('is-invalid');
        if (!firstInvalidField) {
            firstInvalidField = document.querySelector('[name="password_confirmation"]');
        }
        isValid = false;
    }
    
    if (!isValid) {
        e.preventDefault();
        if (firstInvalidField) {
            firstInvalidField.focus();
            firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        Swal.fire('خطأ!', 'يرجى ملء جميع الحقول المطلوبة بشكل صحيح', 'error');
    }
});

// Auto-hide alerts
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    });
});

// Real-time validation
document.querySelectorAll('input[required], select[required], textarea[required]').forEach(input => {
    input.addEventListener('blur', function() {
        if (this.value.trim()) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        } else {
            this.classList.remove('is-valid');
            this.classList.add('is-invalid');
        }
    });
    
    input.addEventListener('input', function() {
        if (this.classList.contains('is-invalid') && this.value.trim()) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        }
    });
});

// Password confirmation validation
document.querySelector('[name="password_confirmation"]').addEventListener('input', function() {
    const password = document.querySelector('[name="password"]').value;
    const confirmation = this.value;
    
    if (confirmation && password === confirmation) {
        this.classList.remove('is-invalid');
        this.classList.add('is-valid');
    } else if (confirmation) {
        this.classList.remove('is-valid');
        this.classList.add('is-invalid');
    }
});
</script>
@endsection

@section('css')
<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.05);
    margin-bottom: 1.5rem;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.help-item {
    padding: 0.75rem;
    background-color: rgba(86, 110, 230, 0.05);
    border-radius: 0.375rem;
    border-left: 3px solid #566ee6;
}

.profile-preview, .banner-preview {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 1rem;
    transition: border-color 0.15s ease-in-out;
}

.profile-preview:hover, .banner-preview:hover {
    border-color: #566ee6;
}

.form-control.is-valid {
    border-color: #28a745;
}

.form-control.is-invalid {
    border-color: #dc3545;
}

.text-danger {
    color: #dc3545 !important;
}

@media (max-width: 768px) {
    .col-xl-4 {
        margin-top: 1rem;
    }
}
</style>
@endsection
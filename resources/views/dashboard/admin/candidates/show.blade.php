@extends('dashboard.layouts.app')

@section('content')
<div class="page-content">
    <div class="container-fluid">

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

        <!-- Profile Banner Section -->
        <div class="row">
            <div class="col-xl-12">
                <div class="profile-user position-relative" style="height: 300px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); overflow: hidden; border-radius: 8px;">
                    @if($candidate->profile_banner_image)
                        <img src="{{ $candidate->profile_banner_image_url }}" alt="صورة الغلاف" 
                             class="w-100 h-100" style="object-fit: cover; pointer-events: none;">
                    @else
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <div class="text-center text-white">
                                <i class="mdi mdi-image font-size-48 mb-2"></i>
                                <p class="mb-0">لا توجد صورة غلاف</p>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Banner Controls (if editing) -->
                    @if(auth()->user()->id === $candidate->user_id || auth()->user()->isAdmin())
                    <div class="position-absolute top-0 end-0 m-3" style="z-index: 10;">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-light" id="banner-upload-btn">
                                <i class="mdi mdi-camera"></i> تغيير الغلاف
                            </button>
                            @if($candidate->profile_banner_image)
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeBannerImage()">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Profile Info Section -->
        <div class="row">
           <div class="profile-content">
               <div class="row align-items-end">
                    <div class="col-sm">
                        <div class="d-flex align-items-end mt-3 mt-sm-0">
                            <div class="flex-shrink-0">
                                <div class="avatar-xxl me-3 position-relative">
                                    @if($candidate->profile_image)
                                        <img src="{{ $candidate->profile_image_url }}" alt="صورة المرشح" 
                                             class="img-fluid rounded-circle d-block img-thumbnail">
                                    @else
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center img-thumbnail" 
                                             style="width: 120px; height: 120px;">
                                            <i class="mdi mdi-account font-size-48 text-muted"></i>
                                        </div>
                                    @endif
                                    
                                    @if(auth()->user()->id === $candidate->user_id || auth()->user()->isAdmin())
                                    <div class="position-absolute bottom-0 end-0">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-primary rounded-circle" 
                                                   id="profile-upload-btn"
                                                   style="width: 35px; height: 35px;">
                                                <i class="mdi mdi-camera font-size-14"></i>
                                            </button>
                                            @if($candidate->profile_image)
                                                <button type="button" class="btn btn-sm btn-danger rounded-circle ms-1" 
                                                       onclick="removeProfileImage()"
                                                       style="width: 35px; height: 35px;">
                                                    <i class="mdi mdi-delete font-size-14"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div>
                                    <h5 class="font-size-18 mb-1" id="candidate-name">{{ $candidate->user->full_name }}</h5>
                                    <p class="text-muted font-size-14 mb-2" id="candidate-position">{{ $candidate->current_position ?? 'مرشح للانتخابات' }}</p>
                                    <div class="d-flex flex-wrap gap-2 mb-2">
                                        <span class="badge bg-primary-subtle text-primary">{{ $candidate->constituency->name }}</span>
                                        <span class="badge bg-success-subtle text-success">{{ $candidate->party_bloc_name }}</span>
                                        @if($candidate->list_number)
                                            <span class="badge bg-info-subtle text-info">رقم القائمة: {{ $candidate->list_number }}</span>
                                        @endif
                                    </div>
                                    @if($candidate->campaign_slogan)
                                        <p class="text-muted font-size-13 fst-italic">"{{ $candidate->campaign_slogan }}"</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-auto">
                        <div class="d-flex align-items-start justify-content-end gap-2 mb-2">
                            @if(auth()->user()->id === $candidate->user_id || auth()->user()->isAdmin())
                            <div>
                                <button type="button" class="btn btn-success" id="toggle-edit-btn" onclick="toggleEditMode()">
                                    <i class="mdi mdi-pencil me-1"></i> تعديل الملف
                                </button>
                            </div>
                            @endif
                            <div>
                                <button type="button" class="btn btn-primary">
                                    <i class="mdi mdi-phone me-1"></i> {{ $candidate->phone }}
                                </button>
                            </div>
                            <div>
                                <div class="dropdown">
                                    <button class="btn btn-link font-size-16 shadow-none text-muted dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-horizontal-rounded font-size-20"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#"><i class="mdi mdi-share me-2"></i>مشاركة الملف</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="mdi mdi-download me-2"></i>تحميل السيرة</a></li>
                                        {{-- @if(auth()->user()->isAdmin())
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#"><i class="mdi mdi-delete me-2"></i>حذف المرشح</a></li>
                                        @endif --}}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
               </div>
           </div>
        </div>

        <!-- Tabs Section -->
        <div class="row">
            <div class="col-lg-12">
               <div class="card bg-transparent shadow-none">
                   <div class="card-body">
                        <ul class="nav nav-tabs-custom card-header-tabs border-top mt-2" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link px-3 active" data-bs-toggle="tab" href="#overview" role="tab">نظرة عامة</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-3" data-bs-toggle="tab" href="#education" role="tab">المؤهلات العلمية</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-3" data-bs-toggle="tab" href="#experience" role="tab">الخبرة والإنجازات</a>
                            </li>
                        </ul>
                   </div>
               </div>
            </div>
        </div>

        <form id="candidate-form" action="{{ route('admin.candidates.update', $candidate) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Hidden file inputs -->
            <input type="file" id="profile_upload" name="profile_image" accept="image/*" style="display: none;" onchange="uploadImage(this, 'profile')">
            <input type="file" id="banner_upload" name="profile_banner_image" accept="image/*" style="display: none;" onchange="console.log('Banner file selected'); uploadImage(this, 'banner')">

            <div class="row">
                <div class="col-xl-8 col-lg-8">
                    <div class="tab-content">
                        <!-- Overview Tab -->
                        <div class="tab-pane active" id="overview" role="tabpanel">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">المعلومات الأساسية</h5>
                                </div>
                                <div class="card-body">
                                    <!-- Basic Information -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label">الاسم الأول</label>
                                            <div class="view-mode">
                                                <p class="mb-0">{{ $candidate->user->first_name }}</p>
                                            </div>
                                            <div class="edit-mode" style="display: none;">
                                                <input type="text" class="form-control" name="first_name" value="{{ $candidate->user->first_name }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">الاسم الأخير</label>
                                            <div class="view-mode">
                                                <p class="mb-0">{{ $candidate->user->last_name }}</p>
                                            </div>
                                            <div class="edit-mode" style="display: none;">
                                                <input type="text" class="form-control" name="last_name" value="{{ $candidate->user->last_name }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label">البريد الإلكتروني</label>
                                            <div class="view-mode">
                                                <p class="mb-0">{{ $candidate->user->email }}</p>
                                            </div>
                                            <div class="edit-mode" style="display: none;">
                                                <input type="email" class="form-control" name="email" value="{{ $candidate->user->email }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">رقم الهاتف</label>
                                            <div class="view-mode">
                                                <p class="mb-0">{{ $candidate->phone }}</p>
                                            </div>
                                            <div class="edit-mode" style="display: none;">
                                                <input type="tel" class="form-control" name="phone" value="{{ $candidate->phone }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label">الدائرة الانتخابية</label>
                                            <div class="view-mode">
                                                <p class="mb-0">{{ $candidate->constituency->name }}</p>
                                            </div>
                                            <div class="edit-mode" style="display: none;">
                                                <select class="form-select" name="constituency_id">
                                                    @foreach($constituencies as $constituency)
                                                        <option value="{{ $constituency->id }}" {{ $candidate->constituency_id == $constituency->id ? 'selected' : '' }}>
                                                            {{ $constituency->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">اسم الكتلة أو الحزب</label>
                                            <div class="view-mode">
                                                <p class="mb-0">{{ $candidate->party_bloc_name }}</p>
                                            </div>
                                            <div class="edit-mode" style="display: none;">
                                                <input type="text" class="form-control" name="party_bloc_name" value="{{ $candidate->party_bloc_name }}">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Biography Section -->
                                    <div class="pb-3">
                                        <h5 class="font-size-15">السيرة الذاتية:</h5>
                                        <div class="text-muted">
                                            <div class="view-mode">
                                                <p class="mb-2">{!! nl2br(e($candidate->biography)) !!}</p>
                                            </div>
                                            <div class="edit-mode" style="display: none;">
                                                <textarea class="form-control" name="biography" rows="4">{{ $candidate->biography }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Campaign Information -->
                                    <div class="pt-3">
                                        <h5 class="font-size-15">معلومات الحملة الانتخابية:</h5>
                                        <div class="text-muted">
                                            <div class="mb-3">
                                                <strong>شعار الحملة:</strong>
                                                <div class="view-mode">
                                                    <p class="mb-0 fst-italic">{{ $candidate->campaign_slogan ? '"' . $candidate->campaign_slogan . '"' : 'غير محدد' }}</p>
                                                </div>
                                                <div class="edit-mode" style="display: none;">
                                                    <input type="text" class="form-control" name="campaign_slogan" value="{{ $candidate->campaign_slogan }}">
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <strong>وعود للناخبين:</strong>
                                                <div class="view-mode">
                                                    <p class="mb-0">{{ $candidate->voter_promises ? nl2br(e($candidate->voter_promises)) : 'غير محدد' }}</p>
                                                </div>
                                                <div class="edit-mode" style="display: none;">
                                                    <textarea class="form-control" name="voter_promises" rows="3">{{ $candidate->voter_promises }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Social Media Links Section -->
                                    <div class="pt-3">
                                        <div class="text-muted">
                                            <div class="edit-mode" style="display: none;">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">فيسبوك</label>
                                                        <input type="url" class="form-control" name="facebook_link" value="{{ $candidate->facebook_link }}" placeholder="https://facebook.com/username">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">لينكد إن</label>
                                                        <input type="url" class="form-control" name="linkedin_link" value="{{ $candidate->linkedin_link }}" placeholder="https://linkedin.com/in/username">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">إنستجرام</label>
                                                        <input type="url" class="form-control" name="instagram_link" value="{{ $candidate->instagram_link }}" placeholder="https://instagram.com/username">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">تويتر</label>
                                                        <input type="url" class="form-control" name="twitter_link" value="{{ $candidate->twitter_link }}" placeholder="https://twitter.com/username">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">يوتيوب</label>
                                                        <input type="url" class="form-control" name="youtube_link" value="{{ $candidate->youtube_link }}" placeholder="https://youtube.com/channel/...">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">تيك توك</label>
                                                        <input type="url" class="form-control" name="tiktok_link" value="{{ $candidate->tiktok_link }}" placeholder="https://tiktok.com/@username">
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">الموقع الإلكتروني</label>
                                                        <input type="url" class="form-control" name="website_link" value="{{ $candidate->website_link }}" placeholder="https://example.com">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Education Tab -->
                        <div class="tab-pane" id="education" role="tabpanel">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">المؤهلات العلمية</h5>
                                    @if(auth()->user()->id === $candidate->user_id || auth()->user()->isAdmin())
                                    <button type="button" class="btn btn-sm btn-primary" onclick="addEducation()">
                                        <i class="mdi mdi-plus"></i> إضافة مؤهل
                                    </button>
                                    @endif
                                </div>
                                <div class="card-body" id="education-list">
                                    @forelse($candidate->education as $edu)
                                    <div class="education-item border-bottom pb-3 mb-3" data-education-id="{{ $edu->id }}">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $edu->degree }} - {{ $edu->field_of_study }}</h6>
                                                <p class="text-muted mb-1">{{ $edu->institution }}</p>
                                                @if($edu->start_year || $edu->end_year)
                                                <p class="text-muted font-size-12 mb-2">
                                                    {{ $edu->start_year }} - {{ $edu->end_year ?? 'مستمر' }}
                                                </p>
                                                @endif
                                                @if($edu->description)
                                                <p class="text-muted font-size-13">{{ $edu->description }}</p>
                                                @endif
                                            </div>
                                            @if(auth()->user()->id === $candidate->user_id || auth()->user()->isAdmin())
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-link text-muted" data-bs-toggle="dropdown">
                                                    <i class="mdi mdi-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#" onclick="editEducation({{ $edu->id }})">تعديل</a></li>
                                                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteEducation({{ $edu->id }})">حذف</a></li>
                                                </ul>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @empty
                                    <div class="text-center py-4" id="no-education-message">
                                        <i class="mdi mdi-school font-size-48 text-muted"></i>
                                        <p class="text-muted mt-2">لا توجد مؤهلات علمية مضافة</p>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Experience Tab -->
                        <div class="tab-pane" id="experience" role="tabpanel">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">الخبرة والإنجازات</h5>
                                </div>
                                <div class="card-body">
                                    <div class="pb-3">
                                        <h5 class="font-size-15">المنصب الحالي:</h5>
                                        <div class="text-muted">
                                            <div class="view-mode">
                                                <p class="mb-2">{{ $candidate->current_position ?? 'غير محدد' }}</p>
                                            </div>
                                            <div class="edit-mode" style="display: none;">
                                                <input type="text" class="form-control" name="current_position" value="{{ $candidate->current_position }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pb-3">
                                        <h5 class="font-size-15">الخبرة العملية:</h5>
                                        <div class="text-muted">
                                            <div class="view-mode">
                                                <p class="mb-2">{{ $candidate->experience ? nl2br(e($candidate->experience)) : 'غير محدد' }}</p>
                                            </div>
                                            <div class="edit-mode" style="display: none;">
                                                <textarea class="form-control" name="experience" rows="4">{{ $candidate->experience }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pb-3">
                                        <h5 class="font-size-15">الإنجازات:</h5>
                                        <div class="text-muted">
                                            <div class="view-mode">
                                                @if($candidate->achievements)
                                                <div class="achievements-list">
                                                    @foreach(explode("\n", $candidate->achievements) as $achievement)
                                                        @if(trim($achievement))
                                                        <div class="py-1">
                                                            <i class="mdi mdi-check-circle me-2 text-success"></i>{{ trim($achievement) }}
                                                        </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                                @else
                                                <p class="mb-2">غير محدد</p>
                                                @endif
                                            </div>
                                            <div class="edit-mode" style="display: none;">
                                                <textarea class="form-control" name="achievements" rows="4" placeholder="اكتب كل إنجاز في سطر منفصل">{{ $candidate->achievements }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pt-3">
                                        <h5 class="font-size-15">معلومات إضافية:</h5>
                                        <div class="text-muted">
                                            <div class="view-mode">
                                                <p class="mb-2">{{ $candidate->additional_info ? nl2br(e($candidate->additional_info)) : 'غير محدد' }}</p>
                                            </div>
                                            <div class="edit-mode" style="display: none;">
                                                <textarea class="form-control" name="additional_info" rows="3">{{ $candidate->additional_info }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-xl-4 col-lg-4">
                    <!-- Contact Information -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">معلومات التواصل</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="ps-0">
                                                <i class="mdi mdi-email-outline text-primary me-2"></i>
                                                البريد الإلكتروني
                                            </td>
                                            <td class="text-muted">{{ $candidate->user->email }}</td>
                                        </tr>
                                        <tr>
                                            <td class="ps-0">
                                                <i class="mdi mdi-phone text-primary me-2"></i>
                                                رقم الهاتف
                                            </td>
                                            <td class="text-muted">{{ $candidate->phone }}</td>
                                        </tr>
                                        <tr>
                                            <td class="ps-0">
                                                <i class="mdi mdi-map-marker text-primary me-2"></i>
                                                الدائرة الانتخابية
                                            </td>
                                            <td class="text-muted">{{ $candidate->constituency->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="ps-0">
                                                <i class="mdi mdi-flag text-primary me-2"></i>
                                                الكتلة/الحزب
                                            </td>
                                            <td class="text-muted">{{ $candidate->party_bloc_name }}</td>
                                        </tr>
                                        @if($candidate->list_number)
                                        <tr>
                                            <td class="ps-0">
                                                <i class="mdi mdi-format-list-numbered text-primary me-2"></i>
                                                رقم القائمة
                                            </td>
                                            <td class="text-muted">{{ $candidate->list_number }}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media Links -->
                    @if($candidate->facebook_link || $candidate->linkedin_link || $candidate->instagram_link || $candidate->twitter_link || $candidate->youtube_link || $candidate->tiktok_link || $candidate->website_link)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">روابط التواصل الاجتماعي</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-2">
                                @if($candidate->facebook_link)
                                <a href="{{ $candidate->facebook_link }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="mdi mdi-facebook"></i> فيسبوك
                                </a>
                                @endif
                                @if($candidate->linkedin_link)
                                <a href="{{ $candidate->linkedin_link }}" target="_blank" class="btn btn-sm btn-outline-info">
                                    <i class="mdi mdi-linkedin"></i> لينكد إن
                                </a>
                                @endif
                                @if($candidate->instagram_link)
                                <a href="{{ $candidate->instagram_link }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                    <i class="mdi mdi-instagram"></i> إنستجرام
                                </a>
                                @endif
                                @if($candidate->twitter_link)
                                <a href="{{ $candidate->twitter_link }}" target="_blank" class="btn btn-sm btn-outline-info">
                                    <i class="mdi mdi-twitter"></i> تويتر
                                </a>
                                @endif
                                @if($candidate->youtube_link)
                                <a href="{{ $candidate->youtube_link }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                    <i class="mdi mdi-youtube"></i> يوتيوب
                                </a>
                                @endif
                                @if($candidate->tiktok_link)
                                <a href="{{ $candidate->tiktok_link }}" target="_blank" class="btn btn-sm btn-outline-dark">
                                    <i class="mdi mdi-music-note"></i> تيك توك
                                </a>
                                @endif
                                @if($candidate->website_link)
                                <a href="{{ $candidate->website_link }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                    <i class="mdi mdi-web"></i> الموقع الإلكتروني
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Skills -->
                    @if($candidate->skills)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">المهارات</h5>
                        </div>
                        <div class="card-body">
                            <div class="view-mode">
                                <div class="d-flex flex-wrap gap-2 font-size-14">
                                    @foreach(explode('،', $candidate->skills) as $skill)
                                        @if(trim($skill))
                                        <span class="badge bg-primary-subtle text-primary">{{ trim($skill) }}</span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="edit-mode" style="display: none;">
                                <textarea class="form-control" name="skills" rows="3" placeholder="اكتب المهارات مفصولة بعلامة (،)">{{ $candidate->skills }}</textarea>
                                <small class="text-muted">افصل المهارات بعلامة (،)</small>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Statistics -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">إحصائيات سريعة</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-12">
                                    <h4 class="mb-1 text-primary" id="education-count">{{ $candidate->education->count() }}</h4>
                                    <p class="text-muted mb-0">مؤهل علمي</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    @if(auth()->user()->id === $candidate->user_id || auth()->user()->isAdmin())
                    <div class="card edit-mode" style="display: none;">
                        <div class="card-header">
                            <h5 class="card-title mb-0">إجراءات</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-content-save me-2"></i>حفظ التغييرات
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="cancelEdit()">
                                    <i class="mdi mdi-close me-2"></i>إلغاء التعديل
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Add Education Modal -->
<div class="modal fade" id="educationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="educationModalTitle">إضافة مؤهل علمي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="education-form" method="POST">
                @csrf
                <input type="hidden" id="education-method" name="_method" value="">
                <input type="hidden" id="education-id" name="education_id" value="">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">الدرجة العلمية <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="education-degree" name="degree" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">المؤسسة التعليمية <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="education-institution" name="institution" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">مجال الدراسة</label>
                        <input type="text" class="form-control" id="education-field" name="field_of_study">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">سنة البدء</label>
                                <input type="number" class="form-control" id="education-start-year" name="start_year" min="1950" max="{{ date('Y') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">سنة الانتهاء</label>
                                <input type="number" class="form-control" id="education-end-year" name="end_year" min="1950" max="{{ date('Y') }}">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">وصف إضافي</label>
                        <textarea class="form-control" id="education-description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary" id="education-submit-btn">
                        <span class="spinner-border spinner-border-sm d-none me-2" id="education-spinner"></span>
                        حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let isEditMode = false;
let educationModal;

document.addEventListener('DOMContentLoaded', function() {
    educationModal = new bootstrap.Modal(document.getElementById('educationModal'));
    
    // Check if file inputs exist
    const profileUpload = document.getElementById('profile_upload');
    const bannerUpload = document.getElementById('banner_upload');
    
    if (!profileUpload) {
        console.error("Profile upload input not found!");
    }
    
    if (!bannerUpload) {
        console.error("Banner upload input not found!");
    }
    
    // Add direct click handlers to buttons
    const bannerButton = document.getElementById('banner-upload-btn');
    if (bannerButton) {
        bannerButton.addEventListener('click', function(e) {
            console.log("Banner button clicked via event listener");
            e.preventDefault();
            e.stopPropagation();
            if (bannerUpload) {
                bannerUpload.click();
            } else {
                console.error("Banner upload input not found when trying to click it!");
            }
        });
    } else {
        console.error("Banner button not found!");
    }
    
    const profileButton = document.getElementById('profile-upload-btn');
    if (profileButton) {
        profileButton.addEventListener('click', function(e) {
            console.log("Profile button clicked via event listener");
            e.preventDefault();
            e.stopPropagation();
            if (profileUpload) {
                profileUpload.click();
            } else {
                console.error("Profile upload input not found when trying to click it!");
            }
        });
    } else {
        console.error("Profile button not found!");
    }
    
    // Check if we have education data in session to edit
    @if(session('education_edit'))
    const educationData = @json(session('education_edit'));
    document.getElementById('educationModalTitle').textContent = 'تعديل مؤهل علمي';
    document.getElementById('education-submit-btn').textContent = 'تحديث';
    document.getElementById('education-form').action = '{{ route("admin.candidates.education.update", ["candidate" => $candidate, "education" => "__ID__"]) }}'.replace('__ID__', educationData.id);
    document.getElementById('education-method').value = 'PUT';
    document.getElementById('education-id').value = educationData.id;
    
    // Fill in the form fields
    document.getElementById('education-degree').value = educationData.degree || '';
    document.getElementById('education-institution').value = educationData.institution || '';
    document.getElementById('education-field').value = educationData.field_of_study || '';
    document.getElementById('education-start-year').value = educationData.start_year || '';
    document.getElementById('education-end-year').value = educationData.end_year || '';
    document.getElementById('education-description').value = educationData.description || '';
    
    // Show the modal
    educationModal.show();
    @endif
});

function toggleEditMode() {
    isEditMode = !isEditMode;
    
    if (isEditMode) {
        document.querySelectorAll('.view-mode').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.edit-mode').forEach(el => el.style.display = 'block');
        document.getElementById('toggle-edit-btn').innerHTML = '<i class="mdi mdi-close me-1"></i> إلغاء التعديل';
        document.getElementById('toggle-edit-btn').className = 'btn btn-secondary';
    } else {
        document.querySelectorAll('.view-mode').forEach(el => el.style.display = 'block');
        document.querySelectorAll('.edit-mode').forEach(el => el.style.display = 'none');
        document.getElementById('toggle-edit-btn').innerHTML = '<i class="mdi mdi-pencil me-1"></i> تعديل الملف';
        document.getElementById('toggle-edit-btn').className = 'btn btn-success';
    }
}

function cancelEdit() {
    toggleEditMode();
}

function uploadImage(input, type) {
    if (input.files && input.files[0]) {
        console.log("Uploading image:", type, input.files[0].name);
        
        // Instead of creating a new form, use the existing form and submit it
        const mainForm = document.getElementById('candidate-form');
        
        // Create a loading indicator
        Swal.fire({
            title: 'جاري الرفع...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Submit the main form
        mainForm.submit();
    }
}

function removeBannerImage() {
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: 'سيتم حذف صورة الغلاف نهائياً',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'نعم، احذف',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.candidates.remove-banner-image", $candidate) }}';
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = '{{ csrf_token() }}';
            
            form.appendChild(methodInput);
            form.appendChild(tokenInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function removeProfileImage() {
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: 'سيتم حذف الصورة الشخصية نهائياً',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'نعم، احذف',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.candidates.remove-profile-image", $candidate) }}';
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = '{{ csrf_token() }}';
            
            form.appendChild(methodInput);
            form.appendChild(tokenInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// EDUCATION CRUD FUNCTIONS - FIXED

function addEducation() {
    // Reset form for adding
    resetEducationForm();
    document.getElementById('educationModalTitle').textContent = 'إضافة مؤهل علمي';
    document.getElementById('education-submit-btn').textContent = 'إضافة';
    document.getElementById('education-form').action = '{{ route("admin.candidates.education.add", $candidate) }}';
    document.getElementById('education-method').value = '';
    
    // Debug
    console.log("Add education form action:", document.getElementById('education-form').action);
    
    educationModal.show();
}

function editEducation(id) {
    // Redirect to the edit page instead of using AJAX
    window.location.href = '{{ route("admin.candidates.education.edit", ["candidate" => $candidate, "education" => "__ID__"]) }}'.replace('__ID__', id);
}

function deleteEducation(id) {
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: 'سيتم حذف هذا المؤهل العلمي نهائياً',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'نعم، احذف',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create and submit a form for deletion
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.candidates.education.delete", ["candidate" => $candidate, "education" => "__ID__"]) }}'.replace('__ID__', id);
            form.style.display = 'none';
            
            // Add method override
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);
            
            // Submit the form
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function resetEducationForm() {
    document.getElementById('education-form').reset();
    document.getElementById('education-id').value = '';
    document.getElementById('education-method').value = '';
}

function updateEducationCount() {
    const educationItems = document.querySelectorAll('.education-item').length;
    document.getElementById('education-count').textContent = educationItems;
}

function checkEmptyEducationList() {
    const educationItems = document.querySelectorAll('.education-item');
    const noEducationMessage = document.getElementById('no-education-message');
    
    if (educationItems.length === 0) {
        if (!noEducationMessage) {
            const educationList = document.getElementById('education-list');
            educationList.innerHTML = `
                <div class="text-center py-4" id="no-education-message">
                    <i class="mdi mdi-school font-size-48 text-muted"></i>
                    <p class="text-muted mt-2">لا توجد مؤهلات علمية مضافة</p>
                </div>
            `;
        }
    } else {
        if (noEducationMessage) {
            noEducationMessage.remove();
        }
    }
}

// Education form submission
document.getElementById('education-form').addEventListener('submit', function(e) {
    e.preventDefault();
    console.log("Form submitted", this.action);
    
    // Simply submit the form directly
    this.submit();
});

// Auto-resize textareas
document.querySelectorAll('textarea').forEach(textarea => {
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });
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
</script>
@endsection

@section('css')
<style>
.profile-user {
    border-radius: 8px;
}

.avatar-xxl {
    width: 120px;
    height: 120px;
}

.avatar-xxl img {
    width: 120px;
    height: 120px;
    object-fit: cover;
}

.edit-mode {
    transition: all 0.3s ease;
}

.view-mode {
    transition: all 0.3s ease;
}

.achievements-list .py-1 {
    padding: 0.25rem 0;
    border-left: 3px solid #28a745;
    padding-left: 1rem;
    margin-bottom: 0.5rem;
    background-color: rgba(40, 167, 69, 0.05);
    border-radius: 0.25rem;
}

.education-item:last-child {
    border-bottom: none !important;
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
}

.nav-tabs-custom .nav-link {
    border: none;
    border-bottom: 2px solid transparent;
}

.nav-tabs-custom .nav-link.active {
    border-bottom-color: #556ee6;
    background: none;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.table-borderless td {
    border: none;
    padding: 0.75rem 0.75rem 0.75rem 0;
}

@media (max-width: 768px) {
    .avatar-xxl {
        width: 80px;
        height: 80px;
    }
    
    .avatar-xxl img {
        width: 80px;
        height: 80px;
    }
}
</style>
@endsection
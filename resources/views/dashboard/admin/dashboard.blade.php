@extends('dashboard.layouts.app')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">مرحباً بك في نظام إدارة الانتخابات</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">الرئيسية</a></li>
                            <li class="breadcrumb-item active">لوحة التحكم</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <!-- Total Candidates Card -->
                <div class="card card-h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span class="text-muted mb-3 lh-1 d-block text-truncate">إجمالي المرشحين</span>
                                <h4 class="mb-3">
                                    <span class="counter-value" data-target="{{ $totalCandidates }}">0</span>
                                </h4>
                                <div class="text-nowrap">
                                    <span class="badge bg-primary-subtle text-primary">
                                        <i class="mdi mdi-account-multiple me-1"></i>مرشح مسجل
                                    </span>
                                </div>
                            </div>
                            <div class="flex-shrink-0 text-end dash-widget">
                                <div class="avatar-sm rounded-circle bg-primary-subtle">
                                    <span class="avatar-title bg-primary-subtle text-primary rounded-circle font-size-24">
                                        <i class="bx bx-group"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <!-- Active Candidates Card -->
                <div class="card card-h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span class="text-muted mb-3 lh-1 d-block text-truncate">المرشحون النشطون</span>
                                <h4 class="mb-3">
                                    <span class="counter-value" data-target="{{ $activeCandidates }}">0</span>
                                </h4>
                                <div class="text-nowrap">
                                    <span class="badge bg-success-subtle text-success">
                                        <i class="mdi mdi-account-check me-1"></i>نشط
                                    </span>
                                    <span class="ms-1 text-muted font-size-13">
                                        {{ $totalCandidates > 0 ? round(($activeCandidates / $totalCandidates) * 100, 1) : 0 }}% من الإجمالي
                                    </span>
                                </div>
                            </div>
                            <div class="flex-shrink-0 text-end dash-widget">
                                <div class="avatar-sm rounded-circle bg-success-subtle">
                                    <span class="avatar-title bg-success-subtle text-success rounded-circle font-size-24">
                                        <i class="bx bx-user-check"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <!-- Total Constituencies Card -->
                <div class="card card-h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span class="text-muted mb-3 lh-1 d-block text-truncate">الدوائر الانتخابية</span>
                                <h4 class="mb-3">
                                    <span class="counter-value" data-target="{{ $totalConstituencies ?? 0 }}">0</span>
                                </h4>
                                <div class="text-nowrap">
                                    <span class="badge bg-info-subtle text-info">
                                        <i class="mdi mdi-map-marker me-1"></i>دائرة انتخابية
                                    </span>
                                </div>
                            </div>
                            <div class="flex-shrink-0 text-end dash-widget">
                                <div class="avatar-sm rounded-circle bg-info-subtle">
                                    <span class="avatar-title bg-info-subtle text-info rounded-circle font-size-24">
                                        <i class="bx bx-map"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <!-- Registration Rate Card -->
                <div class="card card-h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span class="text-muted mb-3 lh-1 d-block text-truncate">التسجيلات الحديثة</span>
                                <h4 class="mb-3">
                                    <span class="counter-value" data-target="{{ $recentRegistrations ?? 0 }}">0</span>
                                </h4>
                                <div class="text-nowrap">
                                    <span class="badge bg-warning-subtle text-warning">
                                        <i class="mdi mdi-clock me-1"></i>هذا الأسبوع
                                    </span>
                                </div>
                            </div>
                            <div class="flex-shrink-0 text-end dash-widget">
                                <div class="avatar-sm rounded-circle bg-warning-subtle">
                                    <span class="avatar-title bg-warning-subtle text-warning rounded-circle font-size-24">
                                        <i class="bx bx-trending-up"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="row">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">أحدث المرشحين المسجلين</h4>
                    </div>
                    <div class="card-body">
                        @if($recentCandidates && $recentCandidates->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>المرشح</th>
                                            <th>الدائرة الانتخابية</th>
                                            <th>الكتلة/الحزب</th>
                                            <th>الحالة</th>
                                            <th>تاريخ التسجيل</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentCandidates as $candidate)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-xs me-3">
                                                        <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                            {{ substr($candidate->user->first_name, 0, 1) }}{{ substr($candidate->user->last_name, 0, 1) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $candidate->user->full_name }}</h6>
                                                        <small class="text-muted">{{ $candidate->user->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    {{ $candidate->constituency->name ?? 'غير محدد' }}
                                                </span>
                                            </td>
                                            <td>{{ $candidate->party_bloc_name }}</td>
                                            <td>
                                                @if($candidate->user->is_active)
                                                    <span class="badge bg-success">نشط</span>
                                                @else
                                                    <span class="badge bg-danger">غير نشط</span>
                                                @endif
                                            </td>
                                            <td>{{ $candidate->created_at->diffForHumans() }}</td>
                                            <td>
                                                <a href="{{ route('admin.candidates.show', $candidate) }}" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bx bx-user-plus font-size-48 text-muted"></i>
                                <h5 class="mt-3">لا توجد تسجيلات حديثة</h5>
                                <p class="text-muted">لم يتم تسجيل أي مرشحين مؤخراً</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <!-- Quick Actions Card -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">الإجراءات السريعة</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.candidates.index') }}" class="btn btn-primary">
                                <i class="bx bx-list-ul me-2"></i>عرض جميع المرشحين
                            </a>
                            <a href="{{ route('admin.candidates.export') }}" class="btn btn-success">
                                <i class="bx bx-download me-2"></i>تصدير قائمة المرشحين
                            </a>
                            <a href="{{ route('admin.candidates.download-template') }}" class="btn btn-info">
                                <i class="bx bx-file me-2"></i>تحميل نموذج الاستيراد
                            </a>
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#importModal">
                                <i class="bx bx-upload me-2"></i>استيراد مرشحين جدد
                            </button>
                        </div>

                        <hr class="my-4">

                        <h6 class="font-size-14 mb-3">إحصائيات سريعة</h6>
                        <div class="row">
                            <div class="col-6">
                                <div class="text-center">
                                    <h5 class="font-size-18 mb-1">{{ $totalCandidates - $activeCandidates }}</h5>
                                    <p class="text-muted mb-0">غير نشط</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <h5 class="font-size-18 mb-1">{{ $totalConstituencies ?? 0 }}</h5>
                                    <p class="text-muted mb-0">دائرة انتخابية</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Import Modal (Quick Access) -->
        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.candidates.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="importModalLabel">استيراد مرشحين من ملف Excel</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="excel_file" class="form-label">اختر ملف Excel</label>
                                <input type="file" class="form-control" id="excel_file" name="excel_file" 
                                       accept=".xlsx,.xls,.csv" required>
                                <div class="form-text">الصيغ المدعومة: .xlsx, .xls, .csv</div>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="update_existing" name="update_existing" value="1">
                                <label class="form-check-label" for="update_existing">
                                    تحديث المرشحين الموجودين
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-upload me-1"></i>استيراد الملف
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <!-- container-fluid -->
</div>
@endsection

@section('scripts')
<script>
// Counter animation
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.counter-value');
    
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const duration = 2000; // 2 seconds
        const increment = target / (duration / 16); // 60 FPS
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                counter.textContent = target;
                clearInterval(timer);
            } else {
                counter.textContent = Math.floor(current);
            }
        }, 16);
    });
});
</script>
@endsection
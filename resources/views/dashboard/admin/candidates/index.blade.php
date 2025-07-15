@extends('dashboard.layouts.app')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">إدارة المرشحين</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                            <li class="breadcrumb-item active">المرشحين</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- Display Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {!! session('success') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {!! session('warning') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {!! session('error') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Detailed Import Results -->
        @if(session('import_results') && session('show_import_details'))
            @php $results = session('import_results') @endphp
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <h5 class="alert-heading"><i class="bx bx-info-circle"></i> تفاصيل عملية الاستيراد</h5>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6>الإحصائيات:</h6>
                        <ul class="mb-2">
                            <li>إجمالي الصفوف: {{ $results['total'] }}</li>
                            <li class="text-success">نجح: {{ $results['successful'] }}</li>
                            @if($results['updated'] > 0)
                                <li class="text-info">تم تحديثه: {{ $results['updated'] }}</li>
                            @endif
                            @if($results['failed'] > 0)
                                <li class="text-danger">فشل: {{ $results['failed'] }}</li>
                            @endif
                            @if($results['warnings'] > 0)
                                <li class="text-warning">تحذيرات: {{ $results['warnings'] }}</li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>معدل النجاح:</h6>
                        <div class="progress mb-2">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: {{ $results['summary']['success_rate'] ?? 0 }}%"
                                 aria-valuenow="{{ $results['summary']['success_rate'] ?? 0 }}" 
                                 aria-valuemin="0" aria-valuemax="100">
                                {{ $results['summary']['success_rate'] ?? 0 }}%
                            </div>
                        </div>
                    </div>
                </div>

                @if(!empty($results['errors']))
                    <div class="mt-3">
                        <h6 class="text-danger">أول 5 أخطاء:</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>الصف</th>
                                        <th>نوع الخطأ</th>
                                        <th>الرسالة</th>
                                        <th>البريد الإلكتروني</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(array_slice($results['errors'], 0, 5) as $error)
                                        <tr>
                                            <td>{{ $error['row'] }}</td>
                                            <td>
                                                <span class="badge bg-danger">{{ $error['type'] ?? 'خطأ عام' }}</span>
                                            </td>
                                            <td>{{ $error['message'] }}</td>
                                            <td>{{ $error['context']['email'] ?? 'غير محدد' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if(count($results['errors']) > 5)
                            <p class="text-muted">
                                <small>وهناك {{ count($results['errors']) - 5 }} أخطاء أخرى...</small>
                            </p>
                        @endif
                    </div>
                @endif

                @if(!empty($results['warnings_list']))
                    <div class="mt-3">
                        <h6 class="text-warning">التحذيرات:</h6>
                        <ul class="mb-0">
                            @foreach(array_slice($results['warnings_list'], 0, 3) as $warning)
                                <li><small>صف {{ $warning['row'] }}: {{ implode(', ', $warning['warnings']) }}</small></li>
                            @endforeach
                            @if(count($results['warnings_list']) > 3)
                                <li><small>و {{ count($results['warnings_list']) - 3 }} تحذيرات أخرى...</small></li>
                            @endif
                        </ul>
                    </div>
                @endif

                <div class="mt-3 d-flex gap-2">
                    <a href="{{ route('admin.candidates.import-report') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bx bx-download"></i> تحميل التقرير المفصل
                    </a>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        <!-- Filters -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">فلترة المرشحين</h4>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.candidates.index') }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">الدائرة الانتخابية</label>
                                        <select name="constituency" class="form-select">
                                            <option value="">جميع الدوائر</option>
                                            @foreach($constituencies ?? [] as $constituency)
                                                <option value="{{ $constituency->id }}" 
                                                    {{ request('constituency') == $constituency->id ? 'selected' : '' }}>
                                                    {{ $constituency->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">الكتلة أو الحزب</label>
                                        <input type="text" name="party_bloc_name" class="form-control" 
                                               value="{{ request('party_bloc_name') }}" 
                                               placeholder="ابحث عن الكتلة أو الحزب">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">الحالة</label>
                                        <select name="is_active" class="form-select">
                                            <option value="">جميع الحالات</option>
                                            <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>نشط</option>
                                            <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>غير نشط</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">&nbsp;</label>
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bx bx-search-alt"></i> بحث
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Candidates Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">قائمة المرشحين</h4>
                            <p class="card-title-desc">إجمالي المرشحين: {{ $candidates->total() }}</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.candidates.create') }}" class="btn btn-primary">
                                <i class="bx bx-plus"></i> إضافة مرشح جديد
                            </a>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
                                <i class="bx bx-upload"></i> استيراد ملف Excel
                            </button>

                            <a href="{{ route('admin.candidates.export') }}" class="btn btn-info">
                                <i class="bx bx-download"></i> تصدير Excel
                            </a>
                            <a href="{{ route('admin.candidates.download-template') }}" class="btn btn-outline-primary">
                                <i class="bx bx-file"></i> تحميل نموذج Excel
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-rep-plugin">
                            <div class="table-responsive mb-0" data-pattern="priority-columns">
                                <table id="candidates-table" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th data-priority="1">#</th>
                                            <th data-priority="1">الاسم الكامل</th>
                                            <th data-priority="2">البريد الإلكتروني</th>
                                            <th data-priority="3">الدائرة الانتخابية</th>
                                            <th data-priority="4">الكتلة/الحزب</th>
                                            <th data-priority="5">رقم الهاتف</th>
                                            <th data-priority="6">المنصب الحالي</th>
                                            <th data-priority="7">الحالة</th>
                                            <th data-priority="1">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($candidates as $candidate)
                                        <tr>
                                            <td>{{ $loop->iteration + ($candidates->currentPage() - 1) * $candidates->perPage() }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($candidate->profile_photo)
                                                        <img src="{{ asset('storage/' . $candidate->profile_photo) }}" 
                                                             alt="صورة المرشح" class="rounded-circle me-2" 
                                                             style="width: 40px; height: 40px; object-fit: cover;">
                                                    @else
                                                        <div class="rounded-circle bg-primary text-white me-2 d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px; font-size: 14px;">
                                                            {{ substr($candidate->user->first_name, 0, 1) . substr($candidate->user->last_name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0">{{ $candidate->user->full_name }}</h6>
                                                        @if($candidate->campaign_slogan)
                                                            <small class="text-muted">{{ Str::limit($candidate->campaign_slogan, 30) }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $candidate->user->email }}</td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $candidate->constituency->name ?? 'غير محدد' }}
                                                </span>
                                            </td>
                                            <td>{{ $candidate->party_bloc_name }}</td>
                                            <td>
                                                <a href="tel:{{ $candidate->phone }}" class="text-decoration-none">
                                                    {{ $candidate->phone }}
                                                </a>
                                            </td>
                                            <td>{{ $candidate->current_position ?? 'غير محدد' }}</td>
                                            <td>
                                                @if($candidate->user->is_active)
                                                    <span class="badge bg-success">نشط</span>
                                                @else
                                                    <span class="badge bg-danger">غير نشط</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.candidates.show', $candidate) }}" 
                                                       class="btn btn-outline-info btn-sm" title="عرض">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                    <a href="{{ route('admin.candidates.edit', $candidate) }}" 
                                                       class="btn btn-outline-primary btn-sm" title="تعديل">
                                                        <i class="bx bx-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.candidates.toggle-status', $candidate) }}" 
                                                          method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" 
                                                                class="btn btn-outline-warning btn-sm" 
                                                                title="{{ $candidate->user->is_active ? 'إلغاء تفعيل' : 'تفعيل' }}">
                                                            <i class="bx {{ $candidate->user->is_active ? 'bx-user-x' : 'bx-user-check' }}"></i>
                                                        </button>
                                                    </form>
                                                    <button type="button" 
                                                            class="btn btn-outline-danger btn-sm" 
                                                            title="حذف"
                                                            onclick="confirmDelete('{{ $candidate->id }}', '{{ $candidate->full_name }}')">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="bx bx-search" style="font-size: 48px; color: #ccc;"></i>
                                                    <h5 class="mt-2">لا توجد نتائج</h5>
                                                    <p class="text-muted">لم يتم العثور على أي مرشحين مطابقين للبحث</p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Pagination -->
                        @if($candidates->hasPages())
                            <div class="row mt-4">
                                <div class="col-sm-6">
                                    <div>
                                        <p class="mb-sm-0 text-muted">
                                            عرض {{ $candidates->firstItem() }} إلى {{ $candidates->lastItem() }} 
                                            من أصل {{ $candidates->total() }} نتيجة
                                        </p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="float-sm-end">
                                        {{ $candidates->appends(request()->query())->links() }}
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
                <!-- end card -->
            </div> <!-- end col -->
        </div> <!-- end row -->
        
    </div> <!-- container-fluid -->
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">تأكيد الحذف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من رغبتك في حذف المرشح <strong id="candidateName"></strong>؟</p>
                <p class="text-danger"><small>تحذير: لا يمكن التراجع عن هذا الإجراء</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">حذف</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Import Excel Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.candidates.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">استيراد المرشحين من ملف Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="excel_file" class="form-label">اختر ملف Excel</label>
                        <input type="file" class="form-control" id="excel_file" name="excel_file" 
                               accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">الصيغ المدعومة: .xlsx, .xls, .csv</div>
                    </div>
                    <div class="alert alert-info">
                        <h6>تعليمات مهمة:</h6>
                        <ul class="mb-0">
                            <li>يجب أن يحتوي الملف على الأعمدة المطلوبة حسب النموذج</li>
                            <li>تأكد من صحة البيانات قبل الاستيراد</li>
                            <li>سيتم تجاهل الصفوف التي تحتوي على أخطاء</li>
                            <li>ستتلقى تقريراً مفصلاً بعد انتهاء الاستيراد</li>
                        </ul>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="update_existing" name="update_existing" value="1">
                        <label class="form-check-label" for="update_existing">
                            تحديث المرشحين الموجودين (بناءً على البريد الإلكتروني)
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary" id="importBtn">
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" style="display: none;"></span>
                        <i class="bx bx-upload me-1"></i>
                        استيراد الملف
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function confirmDelete(candidateId, candidateName) {
    document.getElementById('candidateName').textContent = candidateName;
    document.getElementById('deleteForm').action = `/admin/candidates/${candidateId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Auto-hide alerts after 5 seconds
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

    // Handle import form submission
    const importForm = document.getElementById('importForm');
    const importBtn = document.getElementById('importBtn');
    const spinner = importBtn.querySelector('.spinner-border');

    importForm.addEventListener('submit', function(e) {
        // Show loading state
        importBtn.disabled = true;
        spinner.style.display = 'inline-block';
        importBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>جاري الاستيراد...';
    });

    // Reset modal when closed
    document.getElementById('importModal').addEventListener('hidden.bs.modal', function() {
        importForm.reset();
        importBtn.disabled = false;
        spinner.style.display = 'none';
        importBtn.innerHTML = '<i class="bx bx-upload me-1"></i>استيراد الملف';
    });

    // File input validation
    document.getElementById('excel_file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const allowedTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
                                 'application/vnd.ms-excel', 
                                 'text/csv'];
            
            if (!allowedTypes.includes(file.type)) {
                alert('يرجى اختيار ملف Excel صحيح (.xlsx, .xls, .csv)');
                e.target.value = '';
                return;
            }

        }
    });
});
</script>
@endsection
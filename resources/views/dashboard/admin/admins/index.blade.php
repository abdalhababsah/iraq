@extends('dashboard.layouts.app')

@section('content')
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
    
    .table-responsive {
        border-radius: 0.375rem;
    }
    
    .table th {
        font-weight: 600;
        color: #495057;
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
    
    .btn-group .btn {
        margin-right: 0.25rem;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    
    .avatar-xs {
        height: 2rem;
        width: 2rem;
    }
    
    .avatar-title {
        align-items: center;
        background-color: #556ee6;
        color: #fff;
        display: flex;
        font-weight: 500;
        height: 100%;
        justify-content: center;
        width: 100%;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    @media (max-width: 768px) {
        .card-header .row {
            flex-direction: column;
            gap: 1rem;
        }
        
        .card-header .col-auto {
            text-align: center;
        }
        
        .btn-group {
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .btn-group .btn {
            margin-right: 0;
        }
    }
    </style>
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">إدارة المدراء</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                            <li class="breadcrumb-item active">إدارة المدراء</li>
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

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-1 overflow-hidden">
                                <p class="text-truncate font-size-14 mb-2">إجمالي المدراء</p>
                                <h4 class="mb-0">{{ $admins->count() }}</h4>
                            </div>
                            <div class="text-primary">
                                <i class="mdi mdi-account-multiple-check font-size-24"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-1 overflow-hidden">
                                <p class="text-truncate font-size-14 mb-2">المدراء النشطين</p>
                                <h4 class="mb-0">{{ $admins->where('is_active', true)->count() }}</h4>
                            </div>
                            <div class="text-success">
                                <i class="mdi mdi-account-check font-size-24"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-1 overflow-hidden">
                                <p class="text-truncate font-size-14 mb-2">المدراء المعطلين</p>
                                <h4 class="mb-0">{{ $admins->where('is_active', false)->count() }}</h4>
                            </div>
                            <div class="text-warning">
                                <i class="mdi mdi-account-off font-size-24"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-1 overflow-hidden">
                                <p class="text-truncate font-size-14 mb-2">آخر تسجيل</p>
                                <h4 class="mb-0 font-size-14">{{ $admins->sortByDesc('created_at')->first()?->created_at->format('Y-m-d') ?? 'لا يوجد' }}</h4>
                            </div>
                            <div class="text-info">
                                <i class="mdi mdi-clock-outline font-size-24"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admins Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">قائمة المدراء</h4>
                                <p class="card-title-desc">إدارة وتحكم في المدراء بالنظام</p>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#adminModal" onclick="openCreateModal()">
                                    <i class="mdi mdi-plus me-2"></i>إضافة مدير جديد
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($admins->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 60px;">#</th>
                                        <th>الاسم</th>
                                        <th>البريد الإلكتروني</th>
                                        <th>الحالة</th>
                                        <th>تاريخ التسجيل</th>
                                        <th class="text-center">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($admins as $admin)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs me-3">
                                                    <span class="avatar-title rounded-circle bg-primary text-white font-size-12">
                                                        {{ strtoupper(substr($admin->first_name, 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $admin->full_name }}</h6>
                                                    <p class="text-muted font-size-13 mb-0">مدير نظام</p>
                                                    @if($admin->id === auth()->id())
                                                        <span class="badge bg-info font-size-10">أنت</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $admin->email }}</td>
                                        <td>
                                            @if($admin->is_active)
                                                <span class="badge bg-success">نشط</span>
                                            @else
                                                <span class="badge bg-danger">معطل</span>
                                            @endif
                                        </td>
                                        <td>{{ $admin->created_at->format('Y-m-d') }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-outline-primary btn-sm" 
                                                        onclick="openEditModal({{ json_encode($admin) }})" 
                                                        data-bs-toggle="modal" data-bs-target="#adminModal">
                                                    <i class="mdi mdi-pencil"></i>
                                                </button>
                                                @if($admin->id !== auth()->id())
                                                <button type="button" 
                                                        class="btn btn-outline-{{ $admin->is_active ? 'warning' : 'success' }} btn-sm"
                                                        onclick="toggleStatus({{ $admin->id }}, '{{ $admin->is_active ? 'deactivate' : 'activate' }}')">
                                                    <i class="mdi mdi-{{ $admin->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-outline-danger btn-sm"
                                                        onclick="deleteAdmin({{ $admin->id }})">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                                @else
                                                <span class="text-muted font-size-12">لا يمكن حذف حسابك</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="mdi mdi-account-multiple-outline display-4 text-muted"></i>
                            </div>
                            <h5 class="text-muted">لا يوجد مدراء</h5>
                            <p class="text-muted">لم يتم إضافة أي المدراء بعد</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#adminModal" onclick="openCreateModal()">
                                <i class="mdi mdi-plus me-2"></i>إضافة مدير جديد
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- container-fluid -->
</div>

<!-- Admin Create/Edit Modal -->
<div class="modal fade" id="adminModal" tabindex="-1" aria-labelledby="adminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adminModalLabel">إضافة مدير جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="adminForm" method="POST">
                @csrf
                <input type="hidden" id="adminMethod" name="_method" value="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="first_name" class="form-label">الاسم الأول <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="last_name" class="form-label">الاسم الأخير <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">كلمة المرور <span class="text-danger" id="passwordRequired">*</span></label>
                        <input type="password" class="form-control" id="password" name="password">
                        <div class="invalid-feedback"></div>
                        <small class="text-muted" id="passwordHelp">يجب أن تكون 8 أحرف على الأقل</small>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">تأكيد كلمة المرور <span class="text-danger" id="confirmRequired">*</span></label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                        <label class="form-check-label" for="is_active">
                            نشط
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Status Toggle Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">تغيير حالة المدير</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="statusModalText">هل أنت متأكد من تغيير حالة هذا المدير؟</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" id="confirmStatusChange">تأكيد</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">حذف المدير</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="mdi mdi-alert-circle-outline display-4 text-warning"></i>
                    <h4 class="mt-3">هل أنت متأكد؟</h4>
                    <p class="text-muted">سيتم حذف المدير نهائياً ولن يمكن استرداده</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">حذف</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let adminIdToToggle = null;
let adminIdToDelete = null;
let isEditMode = false;
let editingAdminId = null;

function openCreateModal() {
    isEditMode = false;
    document.getElementById('adminModalLabel').textContent = 'إضافة مدير جديد';
    document.getElementById('saveBtn').textContent = 'حفظ';
    document.getElementById('adminForm').action = '{{ route("admin.admins.store") }}';
    document.getElementById('adminMethod').value = 'POST';
    document.getElementById('passwordHelp').textContent = 'يجب أن تكون 8 أحرف على الأقل';
    document.getElementById('password').required = true;
    document.getElementById('password_confirmation').required = true;
    document.getElementById('passwordRequired').style.display = 'inline';
    document.getElementById('confirmRequired').style.display = 'inline';
    
    // Clear form
    document.getElementById('adminForm').reset();
    document.getElementById('is_active').checked = true;
    clearValidation();
}

function openEditModal(admin) {
    isEditMode = true;
    editingAdminId = admin.id;
    document.getElementById('adminModalLabel').textContent = 'تعديل المدير';
    document.getElementById('saveBtn').textContent = 'تحديث';
    document.getElementById('adminForm').action = `/admin/admins/${admin.id}`;
    document.getElementById('adminMethod').value = 'PUT';
    document.getElementById('passwordHelp').textContent = 'اتركه فارغاً لعدم تغيير كلمة المرور';
    document.getElementById('password').required = false;
    document.getElementById('password_confirmation').required = false;
    document.getElementById('passwordRequired').style.display = 'none';
    document.getElementById('confirmRequired').style.display = 'none';
    
    // Fill form
    document.getElementById('first_name').value = admin.first_name;
    document.getElementById('last_name').value = admin.last_name;
    document.getElementById('email').value = admin.email;
    document.getElementById('password').value = '';
    document.getElementById('password_confirmation').value = '';
    document.getElementById('is_active').checked = admin.is_active;
    
    clearValidation();
}

function toggleStatus(adminId, action) {
    adminIdToToggle = adminId;
    
    const actionText = action === 'activate' ? 'تفعيل' : 'إلغاء تفعيل';
    document.getElementById('statusModalText').textContent = `هل أنت متأكد من ${actionText} هذا المدير؟`;
    
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
}

function deleteAdmin(adminId) {
    adminIdToDelete = adminId;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function clearValidation() {
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
}

// Form submission
document.getElementById('adminForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = document.getElementById('saveBtn');
    const originalText = submitBtn.textContent;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-2"></i>جاري الحفظ...';
    
    // Get CSRF token with fallback
    const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => Promise.reject(data));
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'تم!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        }
    })
    .catch(error => {
        clearValidation();
        if (error.errors) {
            Object.keys(error.errors).forEach(field => {
                const input = document.getElementById(field);
                if (input) {
                    input.classList.add('is-invalid');
                    const feedback = input.nextElementSibling;
                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                        feedback.textContent = error.errors[field][0];
                    }
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'خطأ!',
                text: error.message || 'حدث خطأ أثناء العملية'
            });
        }
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    });
});

// Confirm status change
document.getElementById('confirmStatusChange').addEventListener('click', function() {
    if (adminIdToToggle) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/admins/${adminIdToToggle}/toggle-status`;
        
        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PATCH';
        
        form.appendChild(csrfInput);
        form.appendChild(methodInput);
        document.body.appendChild(form);
        form.submit();
    }
});

// Confirm delete
document.getElementById('confirmDelete').addEventListener('click', function() {
    if (adminIdToDelete) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/admins/${adminIdToDelete}`;
        
        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        form.appendChild(csrfInput);
        form.appendChild(methodInput);
        document.body.appendChild(form);
        form.submit();
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
</script>
@endsection

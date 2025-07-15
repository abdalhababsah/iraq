<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" data-key="t-menu">القائمة الرئيسية</li>

                <li>
                    <a href="{{ route('admin.dashboard') }}">
                        <i data-feather="home"></i>
                        <span data-key="t-dashboard">لوحة التحكم</span>
                    </a>
                </li>

                <li class="menu-title" data-key="t-apps">إدارة النظام</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="users"></i>
                        <span data-key="t-candidates">إدارة المرشحين</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('admin.candidates.index') }}" data-key="t-candidates-list">قائمة المرشحين</a></li>
                        <li><a href="{{ route('admin.candidates.export') }}" data-key="t-export-candidates">تصدير المرشحين</a></li>
                        <li><a href="{{ route('admin.candidates.download-template') }}" data-key="t-download-template">تحميل نموذج الاستيراد</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="map-pin"></i>
                        <span data-key="t-constituencies">الدوائر الانتخابية</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="#" data-key="t-constituencies-list">قائمة الدوائر</a></li>
                        <li><a href="#" data-key="t-add-constituency">إضافة دائرة جديدة</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="bar-chart-2"></i>
                        <span data-key="t-reports">التقارير والإحصائيات</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="#" data-key="t-candidates-report">تقرير المرشحين</a></li>
                        <li><a href="#" data-key="t-constituencies-report">تقرير الدوائر</a></li>
                        <li><a href="#" data-key="t-statistics">الإحصائيات العامة</a></li>
                    </ul>
                </li>

                <li class="menu-title" data-key="t-settings">الإعدادات</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="settings"></i>
                        <span data-key="t-system-settings">إعدادات النظام</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="#" data-key="t-general-settings">الإعدادات العامة</a></li>
                        <li><a href="#" data-key="t-user-management">إدارة المستخدمين</a></li>
                        <li><a href="#" data-key="t-system-logs">سجلات النظام</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="shield"></i>
                        <span data-key="t-security">الأمان والخصوصية</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="#" data-key="t-permissions">الصلاحيات</a></li>
                        <li><a href="#" data-key="t-audit-logs">سجل العمليات</a></li>
                        <li><a href="#" data-key="t-backup">النسخ الاحتياطي</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
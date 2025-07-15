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
                
                <li>
                    <a href="{{ route('admin.admins.index') }}">
                        <i data-feather="shield"></i>
                        <span data-key="t-admins">إدارة المدراء</span>
                    </a>
                </li>

                <li class="menu-title" data-key="t-apps">إدارة المرشحين</li>

                
                <li>
                    <a href="{{ route('admin.candidates.index') }}">
                        <i data-feather="list"></i>
                        <span data-key="t-candidates-list">قائمة المرشحين</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('admin.candidates.export') }}">
                        <i data-feather="download"></i>
                        <span data-key="t-export-candidates">تصدير المرشحين</span>
                    </a>
                </li>


            </ul>
        </div>
        <!-- Sidebar -->    
    </div>
</div>
<aside class="main-sidebar sidebar-light-primary">
    <!-- Brand Logo -->
    <a href="{{url('admiclientn/dashboard')}}" class="brand-link">
        <img src="{{asset('assets/adminlte-boot-4/dist/img/AdminLTELogo.png')}}" alt="Digital CRM" class="brand-image elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Digital CRM</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->

        <!-- Sidebar Menu -->
        <nav class="">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{url('client/dashboard')}}" class="nav-link {{ request()->is('client/dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home icon-size"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
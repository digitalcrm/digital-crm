
<aside class="main-sidebar sidebar-dark-primary">
    <!-- Main Sidebar Container -->
    <!-- Brand Logo -->
    <a href="{{url('dashboard')}}" class="brand-link">
        <img src="{{asset('assets/adminlte-boot-4/dist/img/AdminLTELogo.png')}}" alt="Digital CRM" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">DIgital CRM</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->


        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->

                <li class="nav-item">
                    <a href="{{url('dashboard')}}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{url('webtolead')}}" class="nav-link">
                        <i class="nav-icon fas fa-file"></i>
                        <p>Web to Lead</p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{url('accounts')}}" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Accounts</p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{url('contacts')}}" class="nav-link">
                        <i class="nav-icon fas fa-phone"></i>
                        <p>Contacts</p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{url('leads')}}" class="nav-link">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>Leads</p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{url('deals')}}" class="nav-link">
                        <i class="nav-icon fas fa-suitcase"></i>
                        <p>Deals</p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{url('customers')}}" class="nav-link">
                        <i class="nav-icon fas fa-user-circle"></i>
                        <p>Customers</p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{url('sales')}}" class="nav-link">
                        <i class="nav-icon fas fa-cart-plus "></i>
                        <p>Sales</p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{url('forecast')}}" class="nav-link">
                        <i class="nav-icon fas fa-bullseye"></i>
                        <p>Forecast</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{url('territory')}}" class="nav-link">
                        <i class="nav-icon fas fa-map-marked-alt"></i>
                        <p>Territory</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{url('products')}}" class="nav-link">
                        <i class="nav-icon fab fa-product-hunt"></i>
                        <p>Product</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{url('documents')}}" class="nav-link">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Document</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{url('invoice')}}" class="nav-link">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>Invoice</p>
                    </a>
                </li>


                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-envelope"></i>
                        <p>
                            Mails
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item">
                            <a href="{{url('mails')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Webmail</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{url('campaigns')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Campaign</p>
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="nav-item">
                    <a href="{{url('settings')}}" class="nav-link">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>Settings</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{url('reports/leads')}}" class="nav-link">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>Reports</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{url('trash')}}" class="nav-link">
                        <i class="nav-icon fas fa-trash"></i>
                        <p>Trash</p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
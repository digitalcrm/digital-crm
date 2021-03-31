
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-primary">
    <!-- Brand Logo -->
    <a href="{{url('admin/dashboard')}}" class="brand-link">
        <img src="{{asset('assets/adminlte-boot-4/dist/img/AdminLTELogo.png')}}" alt="Digital CRM" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">DIgital CRM</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->


        <!-- Sidebar Menu -->
        <nav class="">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->

                <li class="nav-item">
                    <a href="{{url('admin/dashboard')}}" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{url('admin/users')}}" class="nav-link">
                        <i class="nav-icon fas fa-user-circle"></i>
                        <p>Users</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{url('admin/webtolead')}}" class="nav-link">
                        <i class="nav-icon fas fa-file"></i>
                        <p>Web to Lead</p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{url('admin/accounts')}}" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Accounts</p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{url('admin/contacts')}}" class="nav-link">
                        <i class="nav-icon fas fa-phone"></i>
                        <p>Contacts</p>
                    </a>
                </li>


                <!--                <li class="nav-item">
                                    <a href="{{url('admin/leads')}}" class="nav-link">
                                        <i class="nav-icon fas fa-file"></i>
                                        <p>Leads</p>
                                    </a>
                                </li>-->

                <li class="nav-item has-treeview menu-open">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>
                            Leads
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: block;">
                        <li class="nav-item">
                            <a href="{{url('admin/leads')}}" class="nav-link">
                                <i class="nav-icon far fa-circle text-danger"></i>
                                <p class="sub-menu">Leads</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{url('admin/zpleads')}}" class="nav-link">
                                <i class="far nav-icon far fa-circle text-warning"></i>
                                <p class="sub-menu">Facebook Leads from Zapier</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{url('admin/fbleads/all')}}" class="nav-link">
                                <i class="nav-icon far fa-circle text-info"></i>
                                <p class="sub-menu">Facebook Leads from Csv</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{url('admin/unassignedleads')}}" class="nav-link">
                                <i class="nav-icon far fa-circle text-primary"></i>
                                <p class="sub-menu">Unassigned Leads</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{url('admin/webtoleadformleads')}}" class="nav-link">
                                <i class="nav-icon far fa-circle text-success"></i>
                                <p>Web to Lead</p>
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="nav-item">
                    <a href="{{url('admin/deals')}}" class="nav-link">
                        <i class="nav-icon fas fa-suitcase"></i>
                        <p>Deals</p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{url('admin/customers')}}" class="nav-link">
                        <i class="nav-icon fas fa-user-circle"></i>
                        <p>Customers</p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{url('admin/sales')}}" class="nav-link">
                        <i class="nav-icon fas fa-cart-plus "></i>
                        <p>Sales</p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{url('admin/forecast')}}" class="nav-link">
                        <i class="nav-icon fas fa-bullseye"></i>
                        <p>Forecast</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{url('admin/territory')}}" class="nav-link">
                        <i class="nav-icon fas fa-map-marked-alt"></i>
                        <p>Territory</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{url('admin/products')}}" class="nav-link">
                        <i class="nav-icon fab fa-product-hunt"></i>
                        <p>Product</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{url('admin/invoices')}}" class="nav-link">
                        <i class="nav-icon fas fa-money-bill"></i>
                        <p>Invoice</p>
                    </a>
                </li>

                <!--                <li class="nav-item">
                                    <a href="https://adminlte.io/docs" class="nav-link">
                                        <i class="nav-icon fas fa-mail-bulk"></i>
                                        <p>Mails</p>
                                    </a>
                                </li>-->

                <li class="nav-item">
                    <a href="{{url('admin/settings')}}" class="nav-link">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>Settings</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{url('admin/reports/users')}}" class="nav-link">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>Reports</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{url('admin/trash')}}" class="nav-link">
                        <i class="nav-icon fas fa-recycle"></i>
                        <p>Trash</p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
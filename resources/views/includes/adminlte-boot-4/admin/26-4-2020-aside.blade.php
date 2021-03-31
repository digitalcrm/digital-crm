<aside class="main-sidebar sidebar-light-primary">
    <!-- Brand Logo -->
    <a href="{{url('admin/dashboard')}}" class="brand-link">
        <img src="{{asset('assets/adminlte-boot-4/dist/img/AdminLTELogo.png')}}" alt="Digital CRM" class="brand-image elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Digital CRM</span>
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
                    <a href="{{url('admin/dashboard')}}" class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home icon-size"></i>
                        <p>Dashboard</p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{url('admin/users')}}" class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }} {{ request()->is('admin/mails/mailsend/users*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-circle icon-size"></i>
                        <p>Users</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{url('admin/webtolead/')}}" class="nav-link {{ request()->is('admin/webtolead*') ? 'active' : 'null' }}">
                        <i class="nav-icon fas fa-copy icon-size"></i>
                        <p>Web to Lead</p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{url('admin/accounts')}}" class="nav-link {{ request()->is('admin/accounts*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user icon-size"></i>
                        <p>Accounts</p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{url('admin/contacts')}}" class="nav-link {{ request()->is('admin/contacts*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-phone icon-size"></i>
                        <p>Contacts</p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{url('admin/leads')}}" class="nav-link {{ request()->is('admin/leads*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file"></i>
                        <p>Leads</p>
                    </a>
                </li>
                <!-- 
                <li class="nav-item has-treeview {{ request()->is('admin/leads*') ? 'menu-open' : '' }} 
                    {{ request()->is('admin/zpleads*') ? 'menu-open' : '' }} 
                    {{ request()->is('admin/fbleads/all*') ? 'menu-open' : '' }} 
                    {{ request()->is('admin/unassignedleads*') ? 'menu-open' : '' }} 
                    {{ request()->is('admin/assignleadstouser*') ? 'menu-open' : '' }} 
                    {{ request()->is('admin/allocateleadsquota*') ? 'menu-open' : '' }} 
                    {{ request()->is('admin/webtoleadformleads') ? 'menu-open' : '' }}">

                    <a href="#" class="nav-link {{ request()->is('admin/leads*') ? 'active' : '' }} 
                        {{ request()->is('admin/zpleads*') ? 'active' : '' }} 
                        {{ request()->is('admin/fbleads/all*') ? 'active' : '' }} 
                        {{ request()->is('admin/unassignedleads*') ? 'active' : '' }} 
                        {{ request()->is('admin/assignleadstouser*') ? 'active' : '' }} 
                        {{ request()->is('admin/allocateleadsquota*') ? 'active' : '' }} 
                        {{ request()->is('admin/webtoleadformleads') ? 'active' : '' }} ">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>
                            Leads
                            <i class="right fas fa-angle-left icon-size"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{url('admin/leads')}}" class="nav-link {{ request()->is('admin/leads*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon icon-size text-danger"></i>
                                <p class="sub-nav">Leads</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{url('admin/zpleads')}}" class="nav-link {{ request()->is('admin/zpleads*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon icon-size text-warning"></i>
                                <p class="sub-nav">FB Leads from Zapier</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{url('admin/fbleads/all')}}" class="nav-link {{ request()->is('admin/fbleads/all*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon icon-size text-info"></i>
                                <p class="sub-nav">FB Leads from CSV</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{url('admin/unassignedleads')}}" class="nav-link {{ request()->is('admin/unassignedleads*') ? 'active' : '' }}  {{ request()->is('admin/assignleadstouser*') ? 'active' : '' }} {{ request()->is('admin/allocateleadsquota*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon icon-size text-success"></i>
                                <p class="sub-nav">Unassigned Leads</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{url('admin/webtoleadformleads')}}" class="nav-link {{ request()->is('admin/webtoleadformleads') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon icon-size text-primary"></i>
                                <p class="sub-nav">Web to Lead</p>
                            </a>
                        </li>
                    </ul>
                </li> -->


                <li class="nav-item">
                    <a href="{{url('admin/deals')}}" class="nav-link {{ request()->is('admin/deals*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-suitcase icon-size"></i>
                        <p>Deals</p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{url('admin/customers')}}" class="nav-link {{ request()->is('admin/customers*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-circle icon-size"></i>
                        <p>Customers</p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{url('admin/sales')}}" class="nav-link {{ request()->is('admin/sales*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cart-plus icon-size"></i>
                        <p>Sales</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{url('admin/orders')}}" class="nav-link {{ request()->is('admin/orders*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cart-plus icon-size"></i>
                        <p>Orders</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{url('admin/forecast')}}" class="nav-link {{ request()->is('admin/forecast*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bullseye icon-size"></i>
                        <p>Forecast</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{url('admin/territory')}}" class="nav-link {{ request()->is('admin/territory*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-map-marked-alt icon-size"></i>
                        <p>Territory</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{url('admin/products')}}" class="nav-link {{ request()->is('admin/products*') ? 'active' : '' }}">
                        <i class="nav-icon fab fa-product-hunt icon-size"></i>
                        <p>Products</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{url('admin/invoices')}}" class="nav-link {{ request()->is('admin/invoices*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-bill icon-size"></i>
                        <p>Invoice</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{url('admin/groups')}}" class="nav-link {{ request()->is('admin/groups*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-bill icon-size"></i>
                        <p>Groups</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{url('admin/projects')}}" class="nav-link {{ request()->is('admin/projects*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-bill icon-size"></i>
                        <p>Projects</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{url('admin/rds')}}" class="nav-link {{ request()->is('admin/rds*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-bill icon-size"></i>
                        <p>RD</p>
                    </a>
                </li>

                <!--                <li class="nav-item">
                                    <a href="https://adminlte.io/docs" class="nav-link">
                                        <i class="nav-icon fas fa-mail-bulk"></i>
                                        <p>Mails</p>
                                    </a>
                                </li>-->

                <li class="nav-item">
                    <a href="{{url('admin/settings')}}" class="nav-link {{ request()->is('admin/settings*') ? 'active' : '' }} 
                        {{ request()->is('admin/accounttypes*') ? 'active' : '' }}
                        {{ request()->is('admin/industrytypes*') ? 'active' : '' }}
                        {{ request()->is('admin/leadsource*') ? 'active' : '' }}
                        {{ request()->is('admin/leadstatus*') ? 'active' : '' }}
                        {{ request()->is('admin/dealstage*') ? 'active' : '' }}
                        {{ request()->is('admin/department*') ? 'active' : '' }}
                        {{ request()->is('admin/country*') ? 'active' : '' }}
                        {{ request()->is('admin/states*') ? 'active' : '' }}
                        {{ request()->is('admin/currency*') ? 'active' : '' }}
                        {{ request()->is('admin/emailcategory*') ? 'active' : '' }}
                        {{ request()->is('admin/emailtemplates*') ? 'active' : '' }}
                        {{ request()->is('admin/emails*') ? 'active' : '' }}
                        {{ request()->is('admin/units*') ? 'active' : '' }}
                        {{ request()->is('admin/filecategory*') ? 'active' : '' }}
                        {{ request()->is('admin/filecategory*') ? 'active' : '' }}                        
                        {{ request()->is('admin/cronjobs*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cog icon-size"></i>
                        <p>Settings</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{url('admin/reports/users')}}" class="nav-link {{ request()->is('admin/reports/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-pie icon-size"></i>
                        <p>Reports</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{url('admin/trash')}}" class="nav-link {{ request()->is('admin/trash*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-trash icon-size"></i>
                        <p>Trash</p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
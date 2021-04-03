<?php
// echo json_encode(Auth::user()->tbl_features);
$features = Auth::user()->tbl_features;
// exit();
?>
<aside class="main-sidebar sidebar-light-primary">
    <!-- Brand Logo -->
    <a href="{{ url('dashboard') }}" class="brand-link">
        {{-- <img src="{{ config('custom_appdetail.logo') }}" }}" alt="{{ config('custom_appdetail.name') }}"
            class="brand-image img-circle"> --}}
        <span class="brand-text font-weight-light">{{ config('custom_appdetail.name') }}</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <!-- Sidebar Menu -->
        <nav>
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->

                <li class="nav-item">
                    <a href="{{ url('dashboard') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
                    <span class="mt-2"><ion-icon name="speedometer-outline"></ion-icon></span>
                        <p>Dashboard</p>
                    </a>
                </li>
                @if ($features != null && $features->productleads == 1)
                                <li class="nav-item">
                                    <a href="{{ url('leads/getproductleads/list') }}" class="nav-link nav-link-custom">
                                    <ion-icon name="document-text-outline"></ion-icon>
                                        <p class="">Product Leads</p>
                                    </a>
                                </li>
                            @endif


            @if ($features != null)
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                        <ion-icon name="business-outline"></ion-icon>
                            <p>
                                Company
                                <i class="right fas fa-angle-left"></i>
                            </p>

                        </a>
                        <ul class="nav nav-treeview">

                        @if ($features != null && $features->companies == 1)
                                <li class="nav-item">
                                    <a href="{{ route('companies.index') }}"
                                        class="nav-link nav-link-custom {{ request()->is('accounts*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p class="sub-nav">All Companies</p>
                                    </a>
                                </li>
                            @endif

                            @if ($features != null && $features->companies == 1)
                                <li class="nav-item">
                                    <a href="{{ route('companies.create') }}"
                                        class="nav-link nav-link-custom {{ request()->is('accounts*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p class="sub-nav">Add New</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

            @if ($features != null)
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                        <ion-icon name="shirt-outline"></ion-icon>
                            <p>
                                Product
                                <i class="right fas fa-angle-left"></i>
                            </p>

                        </a>
                        <ul class="nav nav-treeview">

                        @if ($features != null && $features->products == 1)
                    <li class="nav-item">
                        <a href="{{ url('products') }}"
                            class="nav-link nav-link-custom {{ Request::is('products*') ? 'active' : '' }}">
                            <i class="nav-icon far fa-circle icon-size"></i>
                            <p class="sub-nav">All Products</p>
                        </a>
                    </li>
                     @endif

                     @if ($features != null && $features->products == 1)
                    <li class="nav-item">
                        <a href="{{ url('products/create') }}"
                            class="nav-link nav-link-custom {{ Request::is('products*') ? 'active' : '' }}">
                            <i class="nav-icon far fa-circle icon-size"></i>
                            <p class="sub-nav">New Product</p>
                        </a>
                    </li>
                @endif
                        </ul>
                    </li>
                @endif


                            @if ($features != null && $features->customers == 1)
                                <li class="nav-item">
                                    <a href="{{ url('customers') }}"
                                        class="nav-link nav-link-custom {{ Request::is('customers*') ? 'active' : '' }}">
                                        <ion-icon name="people-outline"></ion-icon>
                                        <p class="">Customers</p>
                                    </a>
                                </li>
                            @endif


                <li class="nav-item">
                                <a href="{{ route('rfq-forms.index') }}"
                                    class="nav-link {{ request()->is('rfq') ? 'active' : '' }}">
                                    <ion-icon name="notifications-outline"></ion-icon>
                                    <p class="">RFQ</p>
                                </a>
                            </li>    

                            @if ($features != null && $features->reports == 1)
                    <li class="nav-item">
                        <a href="{{ url('reports/leads') }}"
                            class="nav-link nav-link-custom {{ Request::is('reports/*') ? 'active' : '' }}">
                            <ion-icon name="bar-chart-outline"></ion-icon>
                            <p>Reports</p>
                        </a>
                    </li>
                @endif

                <li class="nav-header">CRM</li>

                @if ($features != null)
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                        <ion-icon name="call-outline"></ion-icon>
                            <p>
                                Contacts
                                <i class="right fas fa-angle-left"></i>
                            </p>

                        </a>
                        <ul class="nav nav-treeview">

                            @if ($features != null && $features->contacts == 1)
                                <li class="nav-item">
                                    <a href="{{ url('contacts') }}"
                                        class="nav-link nav-link-custom {{ Request::is('contacts*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p class="sub-nav">Contacts</p>
                                    </a>
                                </li>
                            @endif

                            @if ($features != null && $features->accounts == 1)
                                <li class="nav-item">
                                    <a href="{{ url('accounts') }}"
                                        class="nav-link nav-link-custom {{ request()->is('accounts*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p class="sub-nav">Accounts</p>
                                    </a>
                                </li>
                            @endif

                        
                        </ul>
                    </li>

                @endif


                @if ($features != null)
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                        <ion-icon name="cart-outline"></ion-icon>
                            <p>Sales
                                <i class="right fas fa-angle-left"></i>
                            </p>

                        </a>
                        <ul class="nav nav-treeview">

                            @if ($features != null && $features->leads == 1)
                                <li class="nav-item">
                                    <a href="{{ url('leads') }}"
                                        class="nav-link nav-link-custom {{ Request::is('leads*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p class="sub-nav">Leads</p>
                                    </a>
                                </li>
                            @endif

                          

                            @if ($features != null && $features->deals == 1)
                                <li class="nav-item">
                                    <a href="{{ url('deals') }}"
                                        class="nav-link nav-link-custom {{ Request::is('deals*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p class="sub-nav">Deals</p>
                                    </a>
                                </li>
                            @endif



                            @if ($features != null && $features->sales == 1)
                                <li class="nav-item">
                                    <a href="{{ url('sales') }}"
                                        class="nav-link nav-link-custom {{ Request::is('sales*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p class="sub-nav">Sales</p>
                                    </a>
                                </li>
                            @endif

                            @if ($features != null && $features->orders == 1)
                                <li class="nav-item">
                                    <a href="{{ url('orders') }}"
                                        class="nav-link nav-link-custom {{ Request::is('orders*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p class="sub-nav">Orders</p>
                                    </a>
                                </li>
                            @endif

                            @if ($features != null && $features->invoices == 1)
                                <li class="nav-item">
                                    <a href="{{ url('invoice') }}"
                                        class="nav-link nav-link-custom {{ Request::is('invoice*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p class="sub-nav">Invoice</p>
                                    </a>
                                </li>
                            @endif
                            <!--
                        <li class="nav-item">
                            <a href="{{ url('products') }}" class="nav-link nav-link-custom {{ Request::is('products*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p class="sub-nav">Product</p>
                            </a>
                        </li>
      -->
                            @if ($features != null && $features->forecasts == 1)
                                <li class="nav-item">
                                    <a href="{{ url('forecast') }}"
                                        class="nav-link nav-link-custom {{ Request::is('forecast*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p class="sub-nav">Forecast</p>
                                    </a>
                                </li>
                            @endif

                            @if ($features != null && $features->territory == 1)
                                <li class="nav-item">
                                    <a href="{{ url('territory') }}"
                                        class="nav-link nav-link-custom {{ Request::is('territory*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p class="sub-nav">Territory</p>
                                    </a>
                                </li>
                            @endif

                            @if ($features != null && $features->tasks == 1)
                                <li class="nav-item">
                                    <a href="{{ route('taskmanagement.index') }}"
                                        class="nav-link nav-link-custom {{ request()->is('admin/tasks*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p class="sub-nav">Task</p>
                                    </a>
                                </li>
                            @endif

                            @if ($features != null && $features->appointments == 1)
                                <li class="nav-item">
                                    {{-- <a href="{{ route('bookevents.index',['events'=>'upcoming']) }}" --}}
                                    <a href="{{ url('/calendar') }}"
                                        class="nav-link nav-link-custom {{ request()->is('bookevents*') ? 'active' : '' }} {{ request()->is('calendar*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p class="sub-nav">
                                            Appointments
                                        </p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>

                @endif

            

                @if ($features != null)
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                        <ion-icon name="briefcase-outline"></ion-icon>
                            <p>
                                Marketing
                                <i class="right fas fa-angle-left"></i>
                            </p>

                        </a>
                        <ul class="nav nav-treeview">
                            @if ($features != null && $features->webmails == 1)
                                <li class="nav-item">
                                    <a href="{{ url('mails') }}"
                                        class="nav-link nav-link-custom {{ Request::is('mails*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p class="sub-nav">Webmail</p>
                                    </a>
                                </li>
                            @endif

                            @if ($features != null && $features->campaigns == 1)
                                <li class="nav-item">
                                    <a href="{{ url('campaigns') }}"
                                        class="nav-link nav-link-custom {{ Request::is('campaigns*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p class="sub-nav">Campaign</p>
                                    </a>
                                </li>
                            @endif

                            @if ($features != null && $features->webtolead == 1)
                                <li class="nav-item">
                                    <a href="{{ url('webtolead') }}"
                                        class="nav-link nav-link-custom {{ request()->is('webtolead*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p class="sub-nav">Web to Lead</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>

                @endif


                @if ($features != null)
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                        <ion-icon name="easel-outline"></ion-icon>
                            <p>
                                Projects
                                <i class="right fas fa-angle-left"></i>
                            </p>

                        </a>
                        <ul class="nav nav-treeview">
                            @if ($features != null && $features->projects == 1)
                                <li class="nav-item">
                                    <a href="{{ url('projects') }}"
                                        class="nav-link nav-link-custom {{ Request::is('projects*') ? 'active' : '' }}">
                                        <i class="fas fa-circle nav-icon"></i>
                                        <p class="sub-nav">Projects</p>
                                    </a>
                                </li>
                            @endif
                            @if ($features != null && $features->ticketing == 1)
                                <li class="nav-item">
                                    <a href="{{ route('tickets.index', ['all' => 'true']) }}"
                                        class="nav-link nav-link-custom {{ request()->is('tickets*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p class="sub-nav">Ticketing</p>
                                    </a>
                                </li>
                            @endif
                            @if ($features != null && $features->documents == 1)
                                <li class="nav-item">
                                    <a href="{{ url('documents') }}"
                                        class="nav-link nav-link-custom {{ Request::is('documents*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p class="sub-nav">Document</p>
                                    </a>
                                </li>
                            @endif

                            <li class="nav-item">
                                <a href="{{ route('pois.index') }}"
                                    class="nav-link {{ request()->is('pois*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon icon-size text-warning"></i>
                                    <p class="sub-nav">Proudct of Interest</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                @endif


                <!--<li class="nav-item has-treeview">-->
                <!--    <a href="#" class="nav-link">-->
                <!--        <i class="nav-icon fas fa-shopping-cart"></i>-->
                <!--        <p>Ecommerce<i class="fas fa-angle-left right"></i></p>-->
                <!--    </a>-->
                <!--    <ul class="nav nav-treeview" style="display: none;">-->
                <!--        <li class="nav-item">-->
                <!--            <a href="{{ url('ecommerce/orders/list') }}" class="nav-link">-->
                <!--                <i class="far fa-circle nav-icon"></i>-->
                <!--                <p>Orders</p>-->
                <!--            </a>-->
                <!--        </li>-->
                <!--        <li class="nav-item">-->
                <!--            <a href="{{ url('ecommerce/consumers/list') }}" class="nav-link">-->
                <!--                <i class="far fa-circle nav-icon"></i>-->
                <!--                <p>Consumers</p>-->
                <!--            </a>-->
                <!--        </li>-->
                <!--    </ul>-->
                <!--</li>-->
   

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

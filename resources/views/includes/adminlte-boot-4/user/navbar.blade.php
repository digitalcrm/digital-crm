<nav class="main-header navbar navbar-expand navbar-light navbar-light fixed-top">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <!-- SEARCH FORM -->
    <form action="{{ route('search.getsearchresults') }}" method="get" class="form-inline ml-3 pull-right">
        @csrf
        <div class="input-group input-group-sm">
            <input type="text" class="form-control form-control-navbar" placeholder="Search" id="searchTerm"
                name="search">
            <div class="input-group-append">
                <button type="submit" class="btn btn-navbar"><i class="fas fa-search fa-search-navbar"></i></button>
            </div>
        </div>
    </form>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <div class="navbar-search-block">
        </div>
        <!-- Notifications Dropdown Menu -->
        {{-- <li class=""><a href="{{ route('companies.create') }}" class="btn btn-sm btn-primary mr-4 ml-4"><i class="far fa-plus-square mr-1"></i> Add Company</a></li> --}}
        {{-- <li class=""><a href="{{ url('products/create') }}" class="btn btn-sm btn-primary"><i class="far fa-plus-square mr-1"></i> Add Product</a></li> --}}
        {{-- <li class=""><a href="{{ route('services.create') }}" class="btn btn-sm btn-primary mr-4 ml-4"><i class="far fa-plus-square mr-1"></i> Add Services</a></li> --}}
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-pill badge-danger2 navbar-badge" id='not_count'>0</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="not_menu">
            </div>
        </li>

        {{-- @can('isUser')
            <li class="nav-item"><a class="nav-link" href="{{ url('/subusers') }}"><i class="fas fa-user-plus"></i></a></li>
        @endcan --}}
        <li class="nav-item"><a class="nav-link" href="{{ url('/calendar') }}"><i class="far fa-calendar-alt"></i></a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-plus-square mr-1"></i><i class="fas fa-angle-down"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right pb-2">
                <div class="text-bold small px-3 py-1">Add New</div>
                <div class="dropdown-divider"></div>
                <a href="{{ url('webtolead/create') }}" class="list-group-item list-group-item-action">
                    Web to Lead
                </a>
                <a href="{{ url('accounts/create') }}" class="list-group-item list-group-item-action">
                    Account
                </a>
                <a href="{{ url('contacts/create') }}" class="list-group-item list-group-item-action">
                    Contact
                </a>
                <a href="{{ url('leads/create') }}" class="list-group-item list-group-item-action">
                    Lead
                </a>
                <a href="{{ url('deals/create') }}" class="list-group-item list-group-item-action">
                    Deal
                </a>
                <a href="{{ url('territory/create') }}" class="list-group-item list-group-item-action">
                    Territory
                </a>
                <a href="{{ url('forecast/create') }}" class="list-group-item list-group-item-action">
                    Forecast
                </a>

                <a href="{{ url('products/create') }}" class="list-group-item list-group-item-action">
                    Product
                </a>
                <a href="{{ url('invoice/create') }}" class="list-group-item list-group-item-action">
                    Invoice
                </a>
            </div>
        </li>
        <li class="nav-item"><a class="nav-link" href="{{ url('settings') }}"><i class="fas fa-cog"></i></a></li>


        <!-- profile Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <img src="{{ auth()->user()->profile_img }}" class="img-profile rounded-circle"
                    alt="{{ auth()->user()->name }}" width="24" height="24">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown"
                style="width:275px;-webkit-box-shadow: 0 2px 10px rgba(0,0,0,.2)!important;">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <img src="{{ auth()->user()->profile_img }}"
                                class="user-image2 rounded-circle my-2" alt="{{auth()->user()->name}}" width="85" height="85">
                        </div>
                    </div>
                    <div class="col-lg-12 text-center">
                        <h6 class="m-0"><span class="hidden-xs">{{ Auth::user()->name }}</span></h6>
                        <div class="text-muted">
                            {{ Auth::user()->jobtitle }}
                        </div>
                        <hr>
                        <div class="text-muted small">{{ Auth::user()->email }}</div>
                        <div class="text-muted"><i class="fas fa-mobile-alt"></i> {{ Auth::user()->mobile }}</div>
                        <hr>
                        <div class="">
                            <a href="{{ url('user/profile') }}" class="btn btn-link btn-block">Manage Profile</a>
                        </div>
                        <div class="">
                            <div class="row">
                                <div class="col-lg-12">
                                    <a class="btn btn-link btn-block" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                   document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->

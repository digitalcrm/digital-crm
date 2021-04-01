<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-light fixed-top">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Messages Dropdown Menu -->
        <ul class="navbar-nav ml-auto">
            <!-- Notifications Dropdown Menu -->

            <li class="nav-item">
                <button type="button" class="btn nav-link" data-toggle="modal" data-target="#exampleModal">
                    <i class="fas fa-search"></i>
                </button>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-warning navbar-badge" id="spanUnread">0</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id='notUl'>

                </div>
            </li>
            {{-- Calendar --}}
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/calendar') }}"><i
                        class="far fa-calendar-alt"></i></a></li>
            <!-- profile Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                    <img src="{{ auth()->user()->profile_img }}"
                        class="img-profile rounded-circle" alt="{{auth()->user()->name}}" width="24" height="24">
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown"
                    style="width:300px;-webkit-box-shadow: 0 2px 10px rgba(0,0,0,.2)!important;">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="text-center">
                                <img src="<?php echo Auth::user()->picture != null ? url(Auth::user()->picture) : url('uploads/default/admin.png'); ?>"
                                    class="user-image2" alt="User Image" width="72" height="72">
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="text-left"><span class="hidden-xs">{{ Auth::user()->name }}</span></div>
                            <div>
                                {{ Auth::user()->jobtitle }}
                            </div>
                            <div class="text-left small">{{ Auth::user()->email }}</div>
                            <div class="text-left small">{{ Auth::user()->mobile }}</div>
                            <div class="text-left">
                                <a href="{{ url('admin/profile') }}" class="btn btn-outline-secondary my-2">My
                                    Profile</a>

                            </div>
                            <div class="text-left">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <p>
                                            <a class="btn btn-outline-danger" href="{{ route('logout') }}" onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                                {{ __('Logout') }}
                                            </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                        </form>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
        <!-- Modal Serach field-->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Search</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body py-4">
                        <!-- SEARCH FORM -->
                        <form action="{{ route('admin.search.getsearchresults') }}" method="get"
                            class="form-inline ml-3 pull-right">
                            @csrf

                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <select id="searchfield" name="searchfield"
                                        class="selectpicker form-control  form-control-lg">
                                        <option value='All'>All</option>
                                        <option value='Lead'>Lead</option>
                                        <option value='Account'>Account</option>
                                        <option value='Contact'>Contact</option>
                                        <option value='Deal'>Deal</option>
                                        <option value='Webtolead'>Webtolead</option>
                                        <option value='Customer'>Customer</option>
                                        <option value='Sales'>Sales</option>
                                        <option value='Order'>Order</option>
                                        <option value='Ticket'>Ticket</option>
                                    </select>
                                </div>
                                <input type="text" class="form-control form-control-lg" placeholder="Search"
                                    id="searchTerm" name="search">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-lg btn-primary"><i
                                            class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>
        <!-- End Modal Serach field-->
</nav>
<!-- /.navbar -->

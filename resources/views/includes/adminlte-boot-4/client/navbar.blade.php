<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark navbar-light fixed-top">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>

    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <ul class="navbar-nav ml-auto">
            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-warning navbar-badge" id="spanUnread">0</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id='notUl'>

                </div>
            </li>
            {{-- Calendar  --}}
            <li class="nav-item"><a class="nav-link" href="#"><i class="far fa-calendar-alt"></i></a></li>
            <!-- profile Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                    <img src="<?php echo (Auth::user()->picture != NULL) ? url(Auth::user()->picture) : url("uploads/default/admin.png"); ?>" class="img-profile rounded-circle" alt="User Image" width="24" height="24">
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown" style="width:300px;-webkit-box-shadow: 0 2px 10px rgba(0,0,0,.2)!important;">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="text-center">
                                <img src="<?php echo (Auth::user()->picture != NULL) ? url(Auth::user()->picture) : url("uploads/default/admin.png"); ?>" class="user-image2" alt="User Image" width="72" height="72">
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="text-left"><span class="hidden-xs">{{ Auth::user()->name }}</span></div>
                            <div>
                                {{ Auth::user()->jobtitle }}
                                <!--<small>Member since Nov. 2012</small>-->
                            </div>
                            <div class="text-left small">{{ Auth::user()->email }}</div>
                            <div class="text-left small">{{ Auth::user()->mobile }}</div>
                            <div class="text-left">
                                <a href="{{url('clients/show/'.Auth::user()->id)}}" class="btn btn-outline-secondary my-2">My Profile</a>

                            </div>
                            <div class="text-left">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <p>
                                            <a class="btn btn-outline-danger" href="{{ route('client.logout') }}" onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                                {{ __('Logout') }}
                                            </a>
                                            <form id="logout-form" action="{{ route('client.logout') }}" method="POST" style="display: none;">
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
</nav>
<!-- /.navbar -->
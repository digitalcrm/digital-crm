<header class="main-header">
    <!-- Logo -->
    <a href="{{url('admin/dashboard')}}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">DigitalCRM</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Digital</b>CRM</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li>
                    <a href="{{url('admin/calendar')}}" class="">
                        <i class="fa fa-calendar-plus-o" data-toggle="tooltip" title="Calendar" data-placement="bottom" ></i>
                    </a>
                </li>

                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-warning" id="spanUnread">0</span>
                    </a>
                    <ul class="dropdown-menu" id='notUl'></ul>
                </li>



                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?php echo (Auth::user()->picture != NULL) ? url(Auth::user()->picture) : url("uploads/default/admin.png"); ?>" class="user-image" alt="User Image">
                        <strong><span class="hidden-xs">{{ Auth::user()->name }}</span></strong>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <div class="navbar-login">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <p class="text-center">
                                            <img src="<?php echo (Auth::user()->picture != NULL) ? url(Auth::user()->picture) : url("uploads/default/admin.png"); ?>" class="user-image2" alt="User Image">
                                        </p>
                                    </div>
                                    <div class="col-lg-8">
                                        <p class="text-left"><strong><span class="hidden-xs">{{ Auth::user()->name }}</span></strong></p>
                                        <p>
                                            {{ Auth::user()->jobtitle }}
                                            <!--<small>Member since Nov. 2012</small>-->
                                        </p>
                                        <p class="text-left small">{{ Auth::user()->email }}</p>
                                        <p class="text-left">
                                            <a href="{{url('admin/profile')}}" class="">My Profile</a>

                                        </p>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <div class="navbar-login navbar-login-session">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <p>
                                            <a class="btn btn-danger btn-block" href="{{ url('adminlogout') }}"
                                               onclick="event.preventDefault();
                                                       document.getElementById('logout-form').submit();">
                                                {{ __('Logout') }}
                                            </a>
                                        <form id="logout-form" action="{{ url('adminlogout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
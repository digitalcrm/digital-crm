<header class="main-header">
    <a href="{{url('dashboard')}}" class="logo">
        <span class="logo-mini">Digital CRM</span>
        <span class="logo-lg"><b>Digital</b>CRM</span>
    </a>
    <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!--Products--->

                <!--Search --->
                <li>
                    <form action="{{route('search.getsearchresults')}}" method="get" class="form-inline">
                        @csrf
                        <div class="input-group input-group-sm" style="margin: 10px 0 0 0;">
                            <input type="text" class="form-control" placeholder="Search" id="searchTerm" name="search" >
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-default btn-flat"><i class="fas fa-search"></i></button>
                            </span>
                        </div>
                    </form>
                </li>
                <!--Map--->
                <!--
                <li>
                    <a href="#" class="">
                        <i class="fa fa-map-marker" data-toggle="tooltip" title="Map" data-placement="bottom" ></i>
                    </a>
                </li>
                -->
                <!--Calendar--->
                <li>
                    <a href="{{url('/calendar')}}" class="">
                        <i class="fa fa-calendar-plus-o" data-toggle="tooltip" title="Calendar" data-placement="bottom" ></i>
                    </a>
                </li>
                <!--Newsletter--->
                <!--
                <li>
                    <a href="{{url('/newsletter')}}" class="">
                        <i class="fa fa-newspaper-o" data-toggle="tooltip" title="Newsletter" data-placement="bottom" ></i>
                    </a>
                </li>
                -->
                <!-- Messages: style can be found in dropdown.less-->
                <!--
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-envelope"></i>
                        <span class="label label-success">4</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have 4 messages</li>
                        <li>
                            <ul class="menu">
                                <li>
                                    <a href="#">
                                        <div class="pull-left">
                                            <img src="{{asset('assets/dist/img/user2-160x160.jpg')}}" class="img-circle" alt="User Image">
                                        </div>
                                        <h4>
                                            Support Team
                                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                        </h4>
                                        <p>Why not buy a new awesome theme?</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="pull-left">
                                            <img src="{{asset('assets/dist/img/user3-128x128.jpg')}}" class="img-circle" alt="User Image">
                                        </div>
                                        <h4>
                                            AdminLTE Design Team
                                            <small><i class="fa fa-clock-o"></i> 2 hours</small>
                                        </h4>
                                        <p>Why not buy a new awesome theme?</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="pull-left">
                                            <img src="{{asset('assets/dist/img/user4-128x128.jpg')}}" class="img-circle" alt="User Image">
                                        </div>
                                        <h4>
                                            Developers
                                            <small><i class="fa fa-clock-o"></i> Today</small>
                                        </h4>
                                        <p>Why not buy a new awesome theme?</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="pull-left">
                                            <img src="{{asset('assets/dist/img/user3-128x128.jpg')}}" class="img-circle" alt="User Image">
                                        </div>
                                        <h4>
                                            Sales Department
                                            <small><i class="fa fa-clock-o"></i> Yesterday</small>
                                        </h4>
                                        <p>Why not buy a new awesome theme?</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="pull-left">
                                            <img src="{{asset('assets/dist/img/user4-128x128.jpg')}}" class="img-circle" alt="User Image">
                                        </div>
                                        <h4>
                                            Reviewers
                                            <small><i class="fa fa-clock-o"></i> 2 days</small>
                                        </h4>
                                        <p>Why not buy a new awesome theme?</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="footer"><a href="{{url('/mails')}}">See All Messages</a></li>
                    </ul>
                </li>
                -->
                <!-- Notifications: style can be found in dropdown.less -->

                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                        <span class="label label-warning" id='not_count'>0</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header" id='limsg'></li>
                        <li id='not_menu'>
                        </li>
                        <li class="footer">
                            <a href="{{url('notifications')}}">View all</a>
                            <a href="#" onclick="return markAllasread();">Mark all as read</a>
                        </li>
                    </ul>
                </li>

                <!--Sub Users--->
                @can('isUser')
                <li>
                    <a href="{{url('/subusers')}}" class="">
                        <i class="fas fa-user-plus" data-toggle="tooltip" title="Sub Users" data-placement="bottom" ></i>
                    </a>
                </li>
                @endcan
                <!-- Add-->
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-plus"></i>
                        <!--<span class="label label-warning" id='not_count'>0</span>-->
                    </a>
                    <ul class="dropdown-menu">
                        <li id=''>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li><a href="{{url('webtolead/create')}}"><i class="fa fa-file-text"></i> Create Web to lead</a></li>
                                <li><a href="{{url('leads/create')}}"><i class="fa fa-paper-plane"></i> Create Lead</a></li>
                                <li><a href="{{url('accounts/create')}}"><i class="fa fa-user"></i> Create Account</a></li>
                                <li><a href="{{url('contacts/create')}}"><i class="fa fa-phone"></i> Create Contact</a></li>
                                <li><a href="{{url('deals/create')}}"><i class="fa fa-briefcase"></i> Create Deal</a></li>
                                <li><a href="{{url('territory/create')}}"><i class="fa fa-map-marked-alt"></i> Create Territory</a></li>
                                <li><a href="{{url('forecast/create')}}"><i class="fa fa-bullseye"></i> Create Forecast</a></li>
                                <li><a href="{{url('products/create')}}"><i class="fab fa-product-hunt"></i> Create Products</a></li>
                                <li><a href="{{url('documents/create')}}"><i class="fa fa-files-o"></i> Create Documents</a></li>
                                <li><a href="{{url('invoice/create')}}"><i class="fa fa-money-bill"></i> Create Invoice</a></li>
                                <li><a href="{{url('calendar/create')}}"><i class="fa fa-calendar-plus"></i> Create Event</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?php echo (Auth::user()->picture != NULL) ? url(Auth::user()->picture) : url("uploads/default/user.png"); ?>" class="user-image" alt="User Image">
                        <strong><span class="hidden-xs">{{ Auth::user()->name }}</span></strong>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <div class="navbar-login">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <p class="text-center">
                                            <img src="<?php echo (Auth::user()->picture != NULL) ? url(Auth::user()->picture) : url("uploads/default/user.png"); ?>" class="user-image2" alt="User Image">
                                        </p>
                                    </div>
                                    <div class="col-lg-8">
                                        <p class="text-left"><strong><span class="hidden-xs">{{ Auth::user()->name }}</span></strong></p>
                                        <p>
                                            {{ Auth::user()->jobtitle }}
                                            <!--<small>Member since Nov. 2012</small>-->
                                        </p>
                                        <p class="text-left small">{{ Auth::user()->email }}</p>
                                        <p class="text-left small">{{ Auth::user()->mobile }}</p>
                                        <p class="text-left">
                                            <a href="{{url('user/profile')}}" class="">My Profile</a>

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
                                            <a class="btn btn-danger btn-block" href="{{ route('logout') }}"
                                               onclick="event.preventDefault();
                                                       document.getElementById('logout-form').submit();">
                                                {{ __('Logout') }}
                                            </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
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
<?php
// $userDetails  = Auth::User();
// // echo json_encode($userDetails);
// // exit();
$email = '';
$name = '';
$mobile = '';
$jobtitle = '';
$picture = url("uploads/default/user.png");
// if ($userDetails != '') {
//     $email = $userDetails->email;
//     $name = $userDetails->name;
//     $mobile = $userDetails->mobile;
//     $jobtitle = $userDetails->jobtitle;
//     $picture = ($userDetails->picture != '') ? url($userDetails->picture) : url("uploads/default/user.png");
// }
?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-light navbar-light fixed-top">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>

    </ul>
    
    <div class="col-lg-6 col-md-4 mb-3">
        <!-- <form action="{{route('shop.searchproduct')}}" method="get" class="form-inline ml-3 pull-right"> -->
        <!-- @csrf -->
        <!-- <form class="form-inline ml-3"> -->
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" id="keyword" name="keyword" type="search" placeholder="Search" aria-label="Search"/>
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit" id="keywordSearch">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        <!-- </form> -->
    </div>
    
	<!--<ul class="navbar-nav ml-auto">-->
	<!--<li class="nav-itemd">-->
	<!--<a class="nav-link2 px-3" href="">Buyer</a><a class="nav-link2 px-3" href="">Seller</a><a class="nav-link2 px-3" href="">Messages</a>	<a class="nav-link2 px-3" href="">RFQ</a>	<a class="nav-link2 px-3" href="">Sell</a>-->
	<!--</li>-->
	<!--</ul>-->
	
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Shopping Cart -->

        @if(Session::has('user'))
        <!--<li class="nav-item dropdown">-->
        <!--    <a class="nav-link" href="{{url('consumers/cart')}}">-->
        <!--        <i class="fas fa-shopping-cart"></i>-->
        <!--        <span class="badge badge-pill badge-danger2 navbar-badge" id='cart_count'>0</span>-->
        <!--    </a>-->
        <!--</li>-->
        @endif

        <!-- Notifications Dropdown Menu -->
        <!--<li class="nav-item dropdown">-->
        <!--    <a class="nav-link" data-toggle="dropdown" href="#">-->
        <!--        <i class="far fa-bell"></i>-->
        <!--        <span class="badge badge-pill badge-danger2 navbar-badge" id='not_count'>0</span>-->
        <!--    </a>-->
        <!--    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="not_menu">-->

        <!--    </div>-->
        <!--</li>-->

        <!-- profile Dropdown Menu -->
        <!--<li class="nav-item dropdown">-->
        <!--    <a class="nav-link" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
        <!--        <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ $name }}</span>-->
        <!--        <img src="<?php echo $picture; ?>" class="img-profile rounded-circle" alt="User Image" width="24" height="24">-->
        <!--    </a>-->
            <!-- Dropdown - User Information -->
        <!--    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown" style="width:300px;-webkit-box-shadow: 0 2px 10px rgba(0,0,0,.2)!important;">-->
        <!--        <div class="row">-->
        <!--            <div class="col-lg-4">-->
        <!--                <div class="text-center">-->
        <!--                    <img src="<?php echo $picture; ?>" class="user-image2" alt="User Image" width="72" height="72">-->
        <!--                </div>-->
        <!--            </div>-->
        <!--            <div class="col-lg-8">-->
        <!--                @if(Session::has('user'))-->

        <!--                <div class="text-left"><span class="hidden-xs">{{ ($userDetails != NULL)? $userDetails->name:'' }}</span></div>-->
        <!--                <small>-->
        <!--                    {{ ($userDetails != NULL)? $userDetails->jobtitle:''}}-->
        <!--                </small>-->
        <!--                <div class="text-left small">{{ $email}}</div>-->
        <!--                <div class="text-left small">{{ $mobile}}</div>-->
        <!--                <div class="text-left">-->
        <!--                    <a href="{{url('consumers/profile')}}" class="btn btn-outline-secondary my-2">My Profile</a>-->

        <!--                </div>-->
        <!--                @endif-->

        <!--                <div class="text-left">-->
        <!--                    <div class="row">-->
        <!--                        @if(Session::has('user'))-->

        <!--                        <div class="col-lg-12">-->
        <!--                            <p>-->
        <!--                                <a class="btn btn-outline-danger" href="{{ route('consumer.logout') }}" onclick="event.preventDefault();-->
        <!--                                           document.getElementById('logout-form').submit();">-->
        <!--                                    {{ __('Logout') }}-->
        <!--                                </a>-->
        <!--                                <form id="logout-form" action="{{ route('consumer.logout') }}" method="POST" style="display: none;">-->
        <!--                                    @csrf-->
        <!--                                </form>-->
        <!--                            </p>-->
        <!--                        </div>-->
        <!--                        @else-->
        <!--                        <div class="col-lg-12">-->
        <!--                            <p>-->
        <!--                                <a class="btn btn-outline-danger" href="{{ route('consumer.login') }}">-->
        <!--                                    {{ __('Login') }}-->
        <!--                                </a>-->
        <!--                            </p>-->
        <!--                        </div>-->

        <!--                        @endif-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</li>-->
        
        <!--<li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#">
                <i class="fas fa-th-large"></i>
            </a>
        </li>-->
    </ul>
</nav>
<!-- /.navbar -->
<aside class="main-sidebar sidebar-light-primary">
    <!-- Brand Logo -->
    <a href="{{url('shop')}}" class="brand-link">
        <img src="{{asset('assets/adminlte-boot-4/dist/img/AdminLTELogo.png')}}" alt="Digital CRM" class="brand-image">
        <span class="brand-text font-weight-light">Digital CRM</span> <!-- img-circle-->
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <!-- Sidebar Menu -->
        <nav>
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->

                <!-- {{url('dashboard')}} -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <p>Filters</p>
                    </a>
                </li>
                <li class="nav-header pt-2">PRICE</li>

                <div class="col-10 ml-3">
                    <input type="text" id="amount" readonly="" style="border:0; color:#f6931f; font-weight:bold;font-size: 12px;margin: 0 0 6px -8px;padding: 0;">
                    <div id="slider-range" class="ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content">
                        <div class="ui-slider-range ui-corner-all ui-widget-header" style="left: 0%; width: 100%;"></div><span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="left: 0%;"></span><span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="left: 100%;"></span>
                    </div>
                </div>

                <!-- <li class="nav-header">PRODUCT CATEGORY</li>
                <li class="nav-item">
                    <div class="ml-3">
                        <select class="" id="procatId" name="procatId">
                            
                        </select>
                    </div>


                </li>
                <li class="nav-header">PRODUCT SUB CATEGORY</li>
                <li class="nav-item">
                    <div class="ml-3">
                        <select class="" id="prosubcatId" name="prosubcatId">
                            <option>All</option>
                        </select>
                    </div>


                </li> -->
                <br>
                <li class="nav-header">CATEGORY</li>
                <?php echo $details['procatMenu']; ?>

<!-- 
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link nav-dropdown-toggle">
                        <i class="nav-icon far fa-circle icon-size"></i>
                        <p>
                            Web Mails
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon icon-size text-danger"></i>
                                <p class="sub-nav">Webmail</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon icon-size text-warning"></i>
                                <p class="sub-nav">Campaign</p>
                            </a>
                        </li>
                    </ul>
                </li> -->

                <!-- <li class="nav-header">FRESHNESS</li>
                <li class="nav-item">

                    <div class="ml-3">
                        <label class="custom-control">
                            <input type="checkbox" class="custom-control-input">
                            <div class="custom-control-label">Last 1 Day</div>
                        </label>
                    </div>

                    <div class="ml-3">
                        <label class="custom-control">
                            <input type="checkbox" class="custom-control-input">
                            <div class="custom-control-label">Last 3 Day</div>
                        </label>
                    </div>

                    <div class="ml-3">
                        <label class="custom-control">
                            <input type="checkbox" class="custom-control-input">
                            <div class="custom-control-label">Last 7 Day</div>
                        </label>
                    </div>

                    <div class="ml-3">
                        <label class="custom-control">
                            <input type="checkbox" class="custom-control-input">
                            <div class="custom-control-label">Last 30 Day</div>
                        </label>
                    </div>

                </li> -->

                <!-- <li class="nav-header">RATING</li>
                <li class="nav-item">
                    <div class="ml-3">
                        <a href="#"><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="far fa-star"></i> <small>& Up</small></a>
                    </div>

                    <div class="ml-3">
                        <a href="#"><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="far fa-star"></i><i class="far fa-star"></i> <small>& Up</small></a>
                    </div>


                    <div class="ml-3">
                        <a href="#"><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i> <small>& Up</small></a>
                    </div>

                    <div class="ml-3">
                        <a href="#"><i class="fa fa-star" aria-hidden="true"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i> <small>& Up</small></a>
                    </div>
                </li> -->




            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
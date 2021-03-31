<aside class="main-sidebar">
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <ul class="sidebar-menu" data-widget="tree">
            <li id="lidashboard" class="active lisidebar">
                <a href="{{url('dashboard')}}"><i class="fa fa-home"></i> <span>Dashboard</span></a>
            </li>

            <li id="liwebtolead" class="lisidebar">
                <a href="{{url('webtolead')}}"><i class="fa fa-file-text user-left-bar-icon"></i> <span>Web to Lead</span></a>
            </li>

            <!--Leads--->
            <li id="lileads" class="lisidebar">
                <a href="{{url('leads')}}"><i class="fa fa-paper-plane user-left-bar-icon"></i> <span>Leads</span></a>
            </li>

            <!--Accounts--->
            <li id="liaccounts" class="lisidebar">
                <a href="{{url('accounts')}}"><i class="fa fa-user user-left-bar-icon"></i> <span>Accounts</span></a>
            </li>

            <!--Accounts--->
            <li id="licontacts" class="lisidebar">
                <a href="{{url('contacts')}}"><i class="fa fa-phone user-left-bar-icon"></i> <span>Contacts</span></a>
            </li>
            <li id="lideals" class="lisidebar">
                <a href="{{url('deals')}}"><i class="fa fa-briefcase user-left-bar-icon"></i> <span>Deals</span></a>
            </li>


            <!--Customers--->
            <li id="licustomers" class="lisidebar">
                <a href="{{url('customers')}}"><i class="fa fa-users user-left-bar-icon"></i> <span>Customers</span></a>
            </li>
            <!--Sales--->
            <li id="lisales" class="lisidebar">
                <a href="{{url('sales')}}"><i class="fa fa-shopping-cart user-left-bar-icon"></i> <span>Sales</span></a>
            </li>

            <!--Forecast--->
            <li id="liforecast" class="lisidebar">
                <a href="{{url('forecast')}}"><i class="fa fa-bullseye user-left-bar-icon"></i> <span>Forecast</span></a>
            </li>

            <!--Territory--->
            <li id="literritory" class="lisidebar">
                <a href="{{url('territory')}}"><i class="fas fa-map-marked-alt user-left-bar-icon"></i> &nbsp;<span>Territory</span></a>
            </li>

            <!--Products--->
            <!--
            <li id="liproducts" class="lisidebar">
                <a href="{{url('products')}}"><i class="fab fa-product-hunt user-left-bar-icon"></i> &nbsp;<span>Products</span></a>
            </li>
            -->

            <!--Documents--->
            <li id="lidocuments" class="lisidebar">
                <a href="{{url('documents')}}"><i class="fa fa-files-o user-left-bar-icon"></i><span>Documents</span></a>
            </li>

            <!--Invoice--->
            <li id="liinvoice" class="lisidebar">
                <a href="{{url('invoice')}}"><i class="fas fa-money-bill user-left-bar-icon"></i> &nbsp;<span>Invoice</span></a>
            </li>

            <!--File Manager--->
            <li id="lifiles" class="lisidebar">
                <a href="{{url('files')}}"><i class="fa fa-folder-open user-left-bar-icon"></i><span>File Manager</span></a>
            </li>
            <!--Mails-->
            <!--            <li id="limail" class="active lisidebar">
                            <a href="{{url('mails')}}"><i class="fa fa-envelope"></i> <span>Mails</span></a>
                        </li>-->

            <!--Mails-->
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-envelope"></i> <span>Mails</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li id="limail" class="active lisidebar"><a href="{{url('mails')}}"><i class="fa fa-mail-forward"></i> Webmail</a></li>
                    <li id="licampaign" class="active lisidebar"><a href="{{url('campaigns')}}"><i class="fa fa-mail-bulk"></i> Campaign</a></li>
                </ul>
            </li>

            <!--Settings--->
            <li id="lisettings" class="lisidebar">
                <a href="{{url('settings')}}"><i class="fas fa-gears user-left-bar-icon"></i>&nbsp; <span>Settings</span></a>
            </li>

            <!--Reports-->
            <li id="lireports" class="lisidebar">
                <a href="{{url('reports/leads')}}"><i class="fas fa-area-chart user-left-bar-icon"></i> &nbsp;<span>Reports</span></a>
            </li>

            <!--Api-->
            <li id="lireports" class="lisidebar">
                <a href="#"><i class="fa fa-arrow-alt-circle-down user-left-bar-icon"></i> &nbsp;<span>Api</span></a>
            </li>

            <!--Trash--->
            <li id="litrash" class="lisidebar">
                <a href="{{url('trash')}}"><i class="fas fa-trash user-left-bar-icon"></i> &nbsp;<span>Trash</span></a>
            </li>

            <!--Multilevel--->
            <!--
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-share"></i> <span>Multilevel</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
                    <li class="treeview">
                        <a href="#"><i class="fa fa-circle-o"></i> Level One
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
                            <li class="treeview">
                                <a href="#"><i class="fa fa-circle-o"></i> Level Two
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
                </ul>
            </li>
            -->
        </ul>

    </section>
</aside>
<aside class="main-sidebar">
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <ul class="sidebar-menu" data-widget="tree">
            <li id="lidashboard" class="active lisidebar">
                <a href="{{url('dashboard')}}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
            </li>

            <!--Web to Lead-->
            <li class="active treeview" id="ulwebtolead">
                <a href="#">
                    <i class="fa fa-file-text" aria-hidden="true"></i> Web to Lead</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li id="licreateform" class="lisidebar"><a href="{{url('webtolead/create')}}">Create Form</a></li>
                    <li id="liwebtolead" class="lisidebar"><a href="{{url('webtolead')}}">Forms</a></li>
                </ul>
            </li>

            <!--Leads-->
            <li class="active treeview" id="ulleads">
                <a href="#">
                    <i class="fa fa-paper-plane" aria-hidden="true"></i> Leads</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li id="licreatelead" class="lisidebar"><a href="{{url('leads/create')}}">Create Lead</a></li>
                    <li id="lileads" class="lisidebar"><a href="{{url('leads')}}">Leads</a></li>
                </ul>
            </li>

            <!--Accounts-->
            <li class="active treeview" id="ulaccounts">
                <a href="#">
                    <i class="fa fa-user" aria-hidden="true"></i> Accounts</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li id="licreateaccount" class="lisidebar"><a href="{{url('accounts/create')}}">Create Account</a></li>
                    <li id="liaccounts" class="lisidebar"><a href="{{url('accounts')}}">Accounts</a></li>
                </ul>
            </li>

            <!--Deals-->
            <li class="active treeview" id="uldeals">
                <a href="#">
                    <i class="fa fa-briefcase" aria-hidden="true"></i> Deals</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li id="licreatedeal" class="lisidebar"><a href="{{url('deals/create')}}">Create Deal</a></li>
                    <li id="lideals" class="lisidebar"><a href="{{url('deals')}}">Deals</a></li>
                </ul>
            </li>

            <!--Customers-->
            <li id="licustomers" class="active lisidebar">
                <a href="{{url('customers')}}"><i class="fa fa-dashboard"></i> <span>Customers</span></a>
            </li>
        </ul>
    </section>
</aside>
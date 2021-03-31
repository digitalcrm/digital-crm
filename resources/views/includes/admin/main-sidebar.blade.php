<aside class="main-sidebar">
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <ul class="sidebar-menu" data-widget="tree">
            <!--Dashboard-->
            <li id="lidashboard" class="active lisidebar">
                <a href="{{url('admin/dashboard')}}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
            </li>
            <!--Users-->
            <li id="liusers" class="active lisidebar">
                <a href="{{url('admin/users')}}"><i class="fa fa-user-circle"></i> <span>Users</span></a>
            </li>
            <!--Web to Lead-->
            <li id="liwebtolead" class="active lisidebar">
                <a href="{{url('admin/webtolead')}}"><i class="fa fa-list-alt"></i> <span>Web to Lead</span></a>
            </li>
            <!--Accounts-->
            <li id="liaccounts" class="active lisidebar">
                <a href="{{url('admin/accounts')}}"><i class="fa fa-user"></i> <span>Accounts</span></a>
            </li>
            <!--Contacts-->
            <li id="licontacts" class="active lisidebar">
                <a href="{{url('admin/contacts')}}"><i class="fa fa-phone"></i> <span>Contacts</span></a>
            </li>
            <!--Leads-->
            <!--            <li id="lileads" class="active lisidebar">
                            <a href="{{url('admin/leads')}}"><i class="fa fa-paper-plane"></i> <span>Leads</span></a>
                        </li>-->

            <li class="treeview lisidebar">
                <a href="#">
                    <i class="fa fa-paper-plane"></i>
                    <span>Leads</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display: none;">
                    <li id="liunassigned" ><a href="{{url('admin/unassignedleads')}}"><i class="fa fa-circle-o"></i> <span>Unassigned Leads</span></a></a></li>
                    <li id="lileads" ><a href="{{url('admin/leads')}}"><i class="fa fa-circle-o"></i> <span>Assigned Leads</span></a></li>
                    <li id="lizpleads" ><a href="{{url('admin/zpleads')}}"><i class="fa fa-circle-o"></i> <span>Facebook Leads from Zapier</span></a></a></li>
                    <li id="lifbleads" ><a href="{{url('admin/fbleads/all')}}"><i class="fa fa-circle-o"></i> <span>Facebook Leads from Csv</span></a></a></li>
                    <li id="liformleads" ><a href="{{url('admin/webtoleadformleads')}}"><i class="fa fa-circle-o"></i> <span>Web to Lead</span></a></a></li>

                </ul>
            </li>


            <!--Deals-->
            <li id="lideals" class="active lisidebar">
                <a href="{{url('admin/deals')}}"><i class="fa fa-briefcase"></i> <span>Deals</span></a>
            </li>
            <!--Customers-->
            <li id="licustomers" class="active lisidebar">
                <a href="{{url('admin/customers')}}"><i class="fa fa-users"></i> <span>Customers</span></a>
            </li>
            <!--Sales-->
            <li id="lisales" class="active lisidebar">
                <a href="{{url('admin/sales')}}"><i class="fa fa-shopping-cart"></i> <span>Sales</span></a>
            </li>
            <!--Extentions-->
            <li id="liextentions" class="active lisidebar">
                <a href="{{url('admin/extentions')}}"><i class="fa fa-puzzle-piece"></i> <span>Extentions</span></a>
            </li>
            <!--Forecast-->
            <li id="liforecast" class="active lisidebar">
                <a href="{{url('admin/forecast')}}"><i class="fa fa-bullseye"></i> <span>Forecast</span></a>
            </li>
            <!--Territory-->
            <li id="literritory" class="active lisidebar">
                <a href="{{url('admin/territory')}}"><i class="fa fa-map-o"></i> <span>Territory</span></a>
            </li>
            <!--Territory-->
            <li id="ligroups" class="active lisidebar">
                <a href="{{url('admin/groups')}}"><i class="fa fa-users" aria-hidden="true"></i> <span>Groups</span></a>
            </li>

            <!--Email Templates-->
            <!--            <li id="ultemplates" class="active lisidebar">
                            <a href="{{url('admin/emailtemplates')}}"><i class="fa fa-mail-reply-all"></i> <span>Email Templates</span></a>
                        </li>-->
            <!--Documents-->
            <li id="lidocuments" class="active lisidebar">
                <a href="{{url('admin/documents')}}"><i class="fa fa-files-o"></i> <span>Documents</span></a>
            </li>
            <!--Products-->
            <li id="liproducts" class="active lisidebar">
                <a href="{{url('admin/products')}}"><i class="fa fa-product-hunt"></i> <span>Products</span></a>
            </li>
            <!--Invoice-->
            <li id="liinvoice" class="active lisidebar">
                <a href="{{url('admin/invoices')}}"><i class="fa fa-money"></i> <span>Invoice</span></a>
            </li>

            <!--Mails-->
            <li id="limail" class="active lisidebar">
                <a href="{{url('admin/mails')}}"><i class="fa fa-envelope-open-o"></i> <span>Mails</span></a>
            </li>

            <!--File manager-->
            <li id="lifiles" class="active lisidebar">
                <a href="{{url('admin/files')}}"><i class="fa fa-folder-o"></i> <span>File Manager</span></a>
            </li>

            <!--Reports-->
            <li id="lireports" class="active lisidebar">
                <a href="{{url('admin/reports/users')}}"><i class="fa fa-bar-chart"></i> <span>Reports</span></a>
            </li>
            <!--Api-->
            <li id="liapi" class="active lisidebar">
                <a href="#"><i class="fa fa-wpforms" aria-hidden="true"></i> <span>Api</span></a>
            </li>
            <!--Cron Jobs-->
            <li id="licronjobs" class="active lisidebar">
                <a href="{{url('admin/cronjob')}}"><i class="fa fa-clock-o" aria-hidden="true"></i> <span>Cron Jobs</span></a>
            </li>
            <!--Settings-->
            <li id="lisettings" class="active lisidebar">
                <a href="{{url('admin/settings')}}"><i class="fa fa-gears"></i> <span>Settings</span></a>
            </li>
            <!--Trash-->
            <li id="litrash" class="active lisidebar">
                <a href="{{url('admin/trash')}}"><i class="fa fa-trash"></i> <span>Trash</span></a>
            </li>
        </ul>
    </section>
</aside>
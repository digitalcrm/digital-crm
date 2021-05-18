                        <div class="col-md-2 float-left">
                            <div class="card shadow card-primary card-outline card-primary card-outline">
                                <div class="card-body p-0">
                                    <ul class="nav nav-pills flex-column">
                                        <li class="nav-item {{ request()->is('admin/reports/webtolead') ? 'active-bg' : ''}}">
                                            <a class="nav-link" href="{{url('admin/reports/webtolead')}}">Web to Lead</a>
                                        </li>
                                        <li class="nav-item {{ request()->is('admin/reports/users') ? 'active-bg' : ''}}">
                                            <a class="nav-link" href="{{url('admin/reports/users')}}">Users</a>
                                        </li>
                                        <li class="nav-item {{ request()->is('admin/reports/leads') ? 'active-bg' : ''}}">
                                            <a class="nav-link" href="{{url('admin/reports/leads')}}">Leads</a>
                                        </li>
                                        <li class="nav-item {{ request()->is('admin/reports/accounts') ? 'active-bg' : ''}}">
                                            <a class="nav-link" href="{{url('admin/reports/accounts')}}">Accounts</a>
                                        </li>
                                        <li class="nav-item {{ request()->is('admin/reports/contacts') ? 'active-bg' : ''}}">
                                            <a class="nav-link" href="{{url('admin/reports/contacts')}}">Contacts</a>
                                        </li>
                                        <li class="nav-item {{ request()->is('admin/reports/deals') ? 'active-bg' : ''}}">
                                            <a class="nav-link" href="{{url('admin/reports/deals')}}">Deals</a>
                                        </li>
                                        <li class="nav-item {{ request()->is('admin/reports/customers') ? 'active-bg' : ''}}">
                                            <a class="nav-link" href="{{url('admin/reports/customers')}}">Customers</a>
                                        </li>
                                        <li class="nav-item {{ request()->is('admin/reports/sales') ? 'active-bg' : ''}}">
                                            <a class="nav-link" href="{{url('admin/reports/sales')}}">Sales</a>
                                        </li>
                                        <li class="nav-item {{ request()->is('admin/reports/tasks') ? 'active-bg' : ''}}">
                                            <a class="nav-link" href="{{ route('admin.tasks.reports') }}">Tasks</a>
                                        </li>
                                        <li class="nav-item {{ request()->is('admin/reports/tickets') ? 'active-bg' : ''}}">
                                            <a class="nav-link" href="{{ route('admin.ticket.reports') }}">Tickets</a>
                                        </li>
                                        <li class="nav-item {{ request()->is('admin/reports/appointments') ? 'active-bg' : ''}}">
                                            <a class="nav-link" href="{{ route('admin.appointments') }}">Appointments</a>
                                        </li>
                                        <li class="nav-item {{ request()->is('admin/reports/productleads*') ? 'active-bg' : ''}}">
                                            <a href="{{ url('admin/reports/productleads') }}" class="nav-link">
                                                Product Leads
                                            </a>
                                        </li>
                                        <li class="nav-item {{ request()->is('admin/reports/products*') ? 'active-bg' : ''}}">
                                            <a href="{{ url('admin/reports/products') }}" class="nav-link">
                                                Products
                                            </a>
                                        </li>
                                        <li class="nav-item {{ request()->is('admin/reports/companies*') ? 'active-bg' : ''}}">
                                            <a href="{{ url('admin/reports/companies') }}" class="nav-link">
                                                Companies
                                            </a>
                                        </li>
                                        <li class="nav-item {{ request()->is('admin/reports/services') ? 'active-bg' : ''}}">
                                            <a href="{{ url('admin/reports/services') }}" class="nav-link">
                                                Services
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /. card -->
                        </div>

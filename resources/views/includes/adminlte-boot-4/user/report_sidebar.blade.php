<div class="card shadow card-primary card-outline card-primary card-outline">
    <div class="card-body p-0">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item {{ request()->is('reports/webtolead') ? 'active-bg' : ''}}">
                <a href="{{url('reports/webtolead')}}" class="nav-link">
                    Web to Lead
                </a>
            </li>
            <li class="nav-item {{ request()->is('reports/leads') ? 'active-bg' : ''}}">
                <a href="{{url('reports/leads')}}" class="nav-link">
                    Leads
                </a>
            </li>
            <li class="nav-item {{ request()->is('reports/accounts') ? 'active-bg' : ''}}">
                <a href="{{url('reports/accounts')}}" class="nav-link">
                    Accounts
                </a>
            </li>
            <li class="nav-item {{ request()->is('reports/contacts') ? 'active-bg' : ''}}">
                <a href="{{url('reports/contacts')}}" class="nav-link">
                    Contacts
                </a>
            </li>
            <li class="nav-item {{ request()->is('reports/deals') ? 'active-bg' : ''}}">
                <a href="{{url('reports/deals')}}" class="nav-link">
                    Deals
                </a>
            </li>
            <li class="nav-item {{ request()->is('reports/customers') ? 'active-bg' : ''}}">
                <a href="{{url('reports/customers')}}" class="nav-link">
                    Customers
                </a>
            </li>
            <li class="nav-item {{ request()->is('reports/sales') ? 'active-bg' : ''}}">
                <a href="{{url('reports/sales')}}" class="nav-link">
                    Sales
                </a>
            </li>
            <li class="nav-item {{ request()->is('reports/task*') ? 'active-bg' : ''}}">
                <a href="{{ route('reports.tasks') }}" class="nav-link">
                    Tasks
                </a>
            </li>
            <li class="nav-item {{ request()->is('reports/tickets*') ? 'active-bg' : ''}}">
                <a href="{{ route('reports.tickets') }}" class="nav-link">
                    Tickets
                </a>
            </li>
            <li class="nav-item {{ request()->is('reports/appointments*') ? 'active-bg' : ''}}">
                <a href="{{ route('reports.appointments') }}" class="nav-link">
                    Appointment
                </a>
            </li>
            <li class="nav-item {{ request()->is('reports/productleads*') ? 'active-bg' : ''}}">
                <a href="{{ url('reports/products') }}" class="nav-link">
                    Products
                </a>
            </li>
            <li class="nav-item {{ request()->is('reports/productleads*') ? 'active-bg' : ''}}">
                <a href="{{ url('reports/companies') }}" class="nav-link">
                    Companies
                </a>
            </li>
            <li class="nav-item {{ request()->is('reports/productleads*') ? 'active-bg' : ''}}">
                <a href="{{ url('reports/productleads') }}" class="nav-link">
                    Product Leads
                </a>
            </li>
        </ul>
    </div>
    <!-- /.card-body -->
</div>
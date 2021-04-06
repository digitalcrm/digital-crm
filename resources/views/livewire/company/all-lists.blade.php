<div>
    <!--<div class="content-header">

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1 class="m-0 text-dark">Companies <small class="badge badge-secondary"></small></h1>
                </div>
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a class="btn btn-sm btn-primary mr-1 px-3"
                                href="{{ url('/products/create') }}"><i
                                    class="far fa-plus-square mr-1"></i>{{ __('Add Product') }}
                            </a>
                            <a class="btn btn-sm btn-primary mr-1 px-3"
                                href="{{ route('companies.create') }}"><i
                                    class="far fa-plus-square mr-1"></i>{{ __('custom.new') }}
                            </a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{ url('reports/companies') }}"><i
                                    class="fa fa-chart-pie"></i></a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>-->
    @include('taskmanagement.includes.message')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="listCompany" class="table">
                                <thead>
                                    <tr>
                                        <th>Company Name</th>
                                        <th>Product Leads</th>
                                        <th>Views</th>
                                        <th>Products</th>
                                        {{-- <th>isActive</th> --}}
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($companies as $company)
                                        <tr>
                                            <td>
                                                <a href="https://bigindia.vercel.app/company/{{ $company->slug }}" target="_new">
                                                    <img src="{{ $company->companyLogo() }}" alt="{{ $company->c_name }}" width="45px" height="45px"
                                                        style="border-radius: 50%" />
                                                    {{ $company->c_name }}

                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ url('/leads/getproductleads/list') }}">
                                                    {{ $company->productLead() }}
                                                </a>
                                            </td>
                                            <td>{{ $company->views }}</td>
                                            <td>
                                                <a href="{{ url('/products') }}">
                                                    {{ $company->tbl_products->count() }}
                                                </a>
                                            </td>
                                            {{-- <td>
                                                @livewire('toggle-button', [
                                                    'model' => $company,
                                                    'field' => 'isActive',
                                                ])
                                            </td> --}}
                                            <td>{{ $company->created_at->isoFormat('D/MM/Y') }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item"
                                                            href="{{ route('companies.edit', $company->slug) }}">Edit</a>
                                                        <button class="dropdown-item"
                                                            onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
                                                            wire:click.prevent="deleteCompany('{{ $company->id }}')">
                                                            Delete
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="10">
                                                {{ __('custom.empty_table') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

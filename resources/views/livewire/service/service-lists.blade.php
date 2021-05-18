<div>
    <div class="container-fluid">
        @include('includes.partials.validation-message')
        <div class="row">
            <div class="col-lg-12 p-0">
                <div class="card shadow card-primary card-outline">
                    <!--/.card-header-->
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-8"></div>
                                    <div class="col-md-4">
                                        <div class="search-box float-right m-2">
                                            <input type="text" wire:model="search" class="form-control" placeholder="Search…" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="table" id="servicesTable">
                                <thead>
                                    <tr>
                                        <th>
                                            Name
                                        </th>
                                        <th>
                                            Price
                                        </th>
                                        <th>
                                            Category
                                        </th>
                                        <th>
                                            Company
                                        </th>
                                        <th>
                                            Views
                                        </th>
                                        <th>
                                            Leads
                                        </th>
                                        <th>
                                            Status
                                        </th>
                                        <th>
                                            Date
                                        </th>
                                        <th>
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($services as $service)
                                        <tr>
                                            <td>
                                                <a href="{{ route('services.show', $service->id) }}">
                                                    {{ $service->title }}
                                                </a>
                                            </td>
                                            <td>{{ $service->price }}</td>
                                            <td>{{ $service->serviceCategory->name }}</td>
                                            <td>{{ $service->company->c_name }}</td>
                                            <td>{{ $service->views ?? '0' }}</td>
                                            <td>Null</td>
                                            <td>{{ ($service->isActive) ? 'active' : 'inactive' }}
                                            </td>
                                            <td>{{ $service->created_at->isoFormat('D/MM/Y') }}
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item"
                                                            href="{{ route('services.edit', $service->id) }}">Edit</a>
                                                        <button class="dropdown-item"
                                                            onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
                                                            wire:click.prevent="delete('{{ $service->id }}')">
                                                            Delete
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td>
                                                {{ __('custom.row_not_found') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="9">
                                            {{ $services->links() }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

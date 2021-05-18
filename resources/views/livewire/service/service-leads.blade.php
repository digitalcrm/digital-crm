<div>
    <div class="card shadow card-primary card-outline">
        <div class="card-header">
        </div>
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
                <table id="serviceLeads" class="table">
                    <thead>
                        <tr>
                            <th width="230">Lead Name</th>
                            <th>Service Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Company</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($serviceLeads as $leads)
                            <tr>
                                <td>{{ $leads->first_name }}</td>
                                <td>{{ $leads->services->title }}</td>
                                <td>{{ $leads->email }}</td>
                                <td>{{ $leads->mobile }}</td>
                                <td>
                                    {{ $leads->company->c_name ?? 'Not Found' }}
                                </td>
                                <td>{{ $leads->created_at->isoFormat('D/MM/Y') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        </button>
                                        <div class="dropdown-menu">
                                            {{-- <a class="dropdown-item"
                                                href="#serviceEditUrl">Edit</a> --}}
                                            <button class="dropdown-item"
                                                onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
                                                wire:click.prevent="delete({{ $leads->ld_id }})">
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" align="center">{{ __('custom.row_not_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="ml-2">
                    {{ $serviceLeads->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-12">
    <div class="card shadow card-primary card-outline">
        <div class="card-header">
            <div class="row">
                <div class="col-md-8">
                    <select name="users" class="form-control" wire:model="userId" id="users" style="width: 35%">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 text-right">
                    <input type="text" wire:model="search" class="form-control" />
                </div>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
            <table id="exampletable" class="table">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>User</th>
                        <th>Price</th>
                        <th>Created at</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                        <tr>
                            <td>
                                <a href="#">
                                    {{ $service->title ?? '' }}
                                </a>
                            </td>

                            <td>{{ $service->user->name ?? '' }}</td>

                            <td>{{ $service->price ?? '' }}</td>

                            <td>{{ $service->created_at->toDateString() ?? '' }}
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td class="text-center" colspan="10">
                                {{ __('custom.row_not_found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-2">
                {{ $services->links() }}
            </div>
        </div>
        <!-- /.card-body -->
    </div>

</div>

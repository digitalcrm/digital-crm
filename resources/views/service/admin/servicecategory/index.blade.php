@extends('layouts.adminlte-boot-4.admin')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="row">
            <div class="col-md-7 mt-2">
                <h1>
                    Service Category
                    <small id="total" class="badge badge-secondary"></small>
                </h1>
            </div>
            <div class="col-md-5 text-right pull-right">
                <a class="btn btn-primary px-3" href="{{ route('service-categories.create') }}">
                    <i class="far fa-plus-square mr-1"></i> New Service Category
                </a>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content mt-2 mx-0">
        <div class="container-fluid">
            @include('includes.partials.validation-message')
            <div class="row">
                <div class="col-lg-6">
                    <div class="card shadow card-primary card-outline">
                        <div class="card-body p-0" id="table">
                            <table id="example1" class="table">
                                <thead>
                                    <tr>
                                        <th>
                                            Category
                                        </th>
                                        <th>
                                            Subcategory
                                        </th>
                                        <th>
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($categories as $category)
                                        <tr>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->serv_sub_category_count }}</td>
                                            <td>
                                                <a class="btn badge badge-secondary py-1 px-2 mr-2"
                                                    href="{{ route('service-categories.edit', $category->id) }}">Edit</a>
                                                <a class="btn badge badge-secondary py-1 px-2 mr-2" href="" onclick="event.preventDefault();
                                                    if(confirm('Are you sure!')){
                                                        $('#form-delete-{{ $category->id }}').submit();
                                                    }
                                                    ">
                                                    Delete
                                                </a>
                                                <form id="form-delete-{{ $category->id }}"
                                                    action="{{ route('service-categories.destroy', $category->id) }}"
                                                    method="post" style="display: none">
                                                    @csrf
                                                    @method('delete')
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">
                                                {{ __('custom.row_not_found') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="pt-2">
                                {{ $categories->links() }}
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top text-right pull-right">
                            <a href="{{ route('service-categories.index') }}"
                                class="btn btn-outline-secondary">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection

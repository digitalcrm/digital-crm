@extends('layouts.adminlte-boot-4.admin')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="row">
            <div class="col-md-7 mt-2">
                <h1>
                    Service Subcategory
                    <small id="total" class="badge badge-secondary"></small>
                </h1>
            </div>
            <div class="col-md-5 text-right pull-right">
                <a class="btn btn-primary px-3" href="{{ route('service-subcategories.create') }}">
                    <i class="far fa-plus-square mr-1"></i> New Service Subcategory
                </a>
                <a class="btn btn-default px-3" href="{{ route('service-subcategories.create',['import' => 'csv']) }}">
                    <i class="fas fa-upload"></i> &nbsp;Import
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
                                            Subcategory
                                        </th>
                                        <th>
                                            Category
                                        </th>
                                        <th>
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($subcategories as $subcategory)
                                    <tr>
                                        <td>{{ $subcategory->name }}</td>
                                        <td>
                                            {{$subcategory->serviceCategory->name}}
                                        </td>
                                        <td>
                                            <a class="btn badge badge-secondary py-1 px-2 mr-2"
                                            href="{{ route('service-subcategories.edit', $subcategory->id) }}">Edit</a>
                                            <a class="btn badge badge-secondary py-1 px-2 mr-2" href="" onclick="event.preventDefault();
                                            if(confirm('Are you sure!')){
                                                $('#form-delete-{{ $subcategory->id }}').submit();
                                            }
                                            ">
                                            Delete
                                        </a>
                                        <form id="form-delete-{{ $subcategory->id }}"
                                            action="{{ route('service-subcategories.destroy', $subcategory->id) }}"
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
                            {{ $subcategories->links() }}
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top text-right pull-right">
                        <a href="{{ route('service-subcategories.index') }}"
                        class="btn btn-outline-secondary">Back</a>
                    </div>
                </div>
            </div>
            @if(session()->has('failures'))
            <div class="col-lg-6">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            {{ __('All values are saved but these duplicate values are already exists') }}
                        </tr>
                        <tr>
                            <th>Row</th>
                            <th>Attribute</th>
                            <th>Error</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (session()->get('failures') as $validation)
                        <tr>
                            <td>
                                {{ $validation->row() }}
                            </td>
                            <td>
                                {{ $validation->attribute() }}
                            </td>
                            <td>
                                <ul>
                                    @foreach($validation->errors() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                {{ $validation->values()[$validation->attribute()] }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>        
</section>
</div>

@endsection

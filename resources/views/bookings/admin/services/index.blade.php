@extends('layouts.adminlte-boot-4.admin')
@section('content')

<!--Style-->
<style type="text/css">


</style>
<!--/Style-->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <section class="content-header">
        <div class="row">
            <div class="col-md-7 mt-2">
                <h1>
                    Services
                    <small id="total" class="badge badge-secondary">{{ count($services) }}</small>
                </h1>
            </div>
            <div class="col-md-5 text-right pull-right">
                <a class="btn btn-primary px-3" href="{{ route('bookservices.create') }}"><i class="far fa-plus-square mr-1"></i>New</a>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content mt-2 mx-0">
	<div class="container-fluid">
        <!-- Small cardes (Stat card) -->
        <div class="row">
            <div class="col-lg-6">
                @if(session('success'))
                <div class='alert alert-success'>
                    {{session('success')}}
                </div>
                @endif

                @if(session('error'))
                <div class='alert alert-danger'>
                    {{session('error')}}
                </div>
                @endif
                <div class="card shadow card-primary card-outline">
                    <!-- /.card-header -->
                    <div class="card-body p-0" id="table">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>

                            </thead>
                            <tbody>
                                @forelse($services as $status)
                                <tr>
                                    <td>{{ $status->name ?? 'Null'}}</td>
                                    <td>
                                        <span><a href="{{ route('bookservices.edit',$status->id) }}">Edit</a></span>
                                        <span>
                                        <a
                                        class="swalDefaultSuccess"
                                        href=""
                                        onclick="event.preventDefault();
                                        if(confirm('Are you sure!')){
                                            $('#form-delete-{{$status->id}}').submit();
                                        }
                                        ">
                                        Delete
                                        </a>
                                        <form
                                        style="display:none"
                                        method="post"
                                        id="form-delete-{{$status->id}}"
                                        action="{{route('bookservices.destroy',$status->id)}}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('delete')
                                        </form>
                                        </span>
                                    </td>
                                @empty
                                    <td colspan="2">No data available</td>
                                </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top text-right pull-right">
                        <a href="{{ route('bookservices.index') }}" class="btn btn-outline-secondary">Back</a>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
	</div>
    </section>
    <!-- /.content -->
</div>
@endsection

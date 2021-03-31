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
                    Edit Outcomes
                    <small id="total" class="badge badge-secondary"></small>
                </h1>
            </div>
            <div class="col-md-5 text-right pull-right">
                <a class="btn btn-primary px-3" href="{{ route('outcomes.create') }}"><i class="far fa-plus-square mr-1"></i> Create</a>
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
                <form action="{{ route('outcomes.update', $outcome->id) }}" method="post">
                    @csrf
                    @method('put')
                    <div class="card-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="name" class="control-label">Name</label>
                                </div>
                                <div class="col-md-12">
                                    <input
                                    type="text"
                                    class="form-control @error('name') is-invalid @enderror "
                                    name="name"
                                    id="name"
                                    value="{{ $outcome->name }}">
                                    @error('name')
                                    <small>
                                        <small class="text-danger"> {{ $message }} </small>
                                    </small>
                                    @enderror
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top text-right pull-right">
                        <a href="{{ route('outcomes.index') }}" class="btn btn-outline-secondary float-left">Back</a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
	</div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

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
                    Create Booking Activity
                    <small id="total" class="badge badge-secondary"></small>
                </h1>
            </div>
            <div class="col-md-5 text-right pull-right">
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
                <form action="{{ route('activity_type.store') }}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="title" class="control-label">Activity Name</label>
                                </div>
                                <div class="col-md-12">
                                    <input
                                    type="text"
                                    class="form-control @error('title') is-invalid @enderror "
                                    name="title" id="title"
                                    placeholder="types">
                                    @error('title')
                                    <small>
                                        <small class="text-danger"> {{ $message }} </small>
                                    </small>
                                    @enderror
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top text-right pull-right">
                        <a href="{{ route('activity_type.index') }}" class="btn btn-outline-secondary float-left">Back</a>
                        <button type="submit" class="btn btn-primary">Create</button>
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

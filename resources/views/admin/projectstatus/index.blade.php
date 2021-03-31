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
                    Project Status
                    <small id="total" class="badge badge-secondary">{{$data['total']}}</small>
                </h1>
            </div>
            <div class="col-md-5 text-right pull-right">
                <a class="btn btn-primary px-3" href="{{url('admin/projectstatus/create')}}"><i class="far fa-plus-square mr-1"></i> New Project Status</a>
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
                            {!!$data['table']!!}
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white border-top text-right pull-right">
                            <a href="<?php echo url('admin/settings'); ?>" class="btn btn-outline-secondary">Back</a>
                        </div>
                    </div>
                    <!-- /.card -->
                    <div id="resulttt">

                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    $(function() {});
</script>
@endsection
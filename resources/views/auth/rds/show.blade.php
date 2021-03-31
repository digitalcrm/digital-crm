@extends('layouts.adminlte-boot-4.user')
@section('content')

<?php
// echo json_encode($data);
// exit();
?>



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>RD</h1>
                </div>
            </div>
        </div>
    </section>


    <!-- Main content -->
    <section class="content mt-2 mx-0">
        <div class="container-fluid">
            <!-- Small cardes (Stat card) -->
            <div class="row">
                @if(session('success'))
                <div class='alert alert-success'>
                    {{session('success')}}
                </div>
                @endif

                @if(session('error'))
                <div class='alert alert-success'>
                    {{session('error')}}
                </div>
                @endif
                <!-- general form elements -->
                <div class="col-md-3">
                    <!-- Profile Image -->
                    <div class="card shadow card-primary card-outline">
                        <div class="card-header no-border text-center">
                            <!-- <img class="border img-responsive img-circle" src="<?php // echo ($data['accounts'] != "") ? url($data['accounts']['picture']) : url('/uploads/default/accounts.png'); ?>" height="85" width="85" alt="User profile picture" /> -->
                            <h3 class="profile-username text-center"><?php echo $data->title; ?></h3>
                        </div>
                        <div class="card-body card-profile">
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>Title</b> <a class="pull-right">{!!$data['title']!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Product</b> <a class="pull-right"><?php echo ($data['tbl_products'] != null) ? $data['tbl_products']['name'] : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Product Category</b> <a class="pull-right">{!!$data['pro_category']!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Rd Type</b> <a class="pull-right"><?php echo ($data['tbl_rdtypes'] != null) ? $data['tbl_rdtypes']['type'] : ''; ?></a>
                                </li>

                                <li class="list-group-item">
                                    <b>Priority</b> <a class="pull-right"><?php echo ($data['tbl_rd_priority'] != null) ? $data['tbl_rd_priority']['priority'] : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Trend</b> <a class="pull-right"><?php echo ($data['tbl_rd_trends'] != null) ? $data['tbl_rd_trends']['trend'] : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Status</b> <a class="pull-right"><?php echo ($data['status'] == 1) ? 'Yes' : (($data['status'] == 2) ? 'No' : ''); ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Link</b> <a class="pull-right">{!!$data['link']!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Creation Date</b> <a class="pull-right"><?php echo ($data['creation_date'] != null) ? date('d-m-Y', strtotime($data['creation_date'])) : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Submission Date</b> <a class="pull-right"><?php echo ($data['submission_date'] != null) ? date('d-m-Y', strtotime($data['submission_date'])) : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Uploaded Date</b> <a class="pull-right"><?php echo ($data['uploaded_date'] != null) ? date('d-m-Y', strtotime($data['uploaded_date'])) : ''; ?></a>
                                </li>
                            </ul>

                        </div>
                        <div class="card-footer">
                            <div class="btn-group pull-left">
                                <a href="{{URL::previous()}}" class="btn btn-default"><b>Back</b></a>
                                <a href="<?php echo url('rds/' . $data['rd_id'] . '/edit'); ?>" class="btn btn-primary"><b>Edit</b></a>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                    <!-- About Me card -->

                </div>
                <!--                 
                <div class="col-md-9">
                    <div class="card shadow card-primary card-outline">
                        <div class="card-header with-border">
                            <h3 class="card-title">
                                Leads &nbsp;<small class="badge badge-secondary">{!!$data['total']!!}</small>
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            {!!$data['table']!!}
                        </div>
                        <div class="card-footer bg-white pull-right text-right">
                            <a href="{{url('accounts')}}" class="btn btn-sm btn-outline-secondary"><i class="nav-icon fas fa-long-arrow-alt-left"></i></a>
                        </div>
                    </div>
                </div>
 -->
                <!-- ./col -->
            </div>
            <!-- /.row -->

        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    $(function() {
        $(".sidebar-menu li").removeClass("menu-open");
        $(".sidebar-menu li").removeClass("active");
        $("#ulaccounts").addClass('menu-open');
        $("#ulaccounts ul").css('display', 'block');
        // $("#licreatelead").addClass("active");
    });
</script>
@endsection
@extends('layouts.adminlte-boot-4.user')
@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Account Profile</h1>
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
                            <img class="border img-responsive img-circle" src="<?php echo ($data['accounts']['picture'] != "") ? url($data['accounts']['picture']) : url('/uploads/default/accounts.png'); ?>" height="85" width="85" alt="User profile picture" />
                            <h3 class="profile-username text-center"><?php echo $data['accounts']['name']; ?></h3>
                        </div>
                        <div class="card-body card-profile p-0">
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>Account Name</b> <a class="pull-right">{!!$data['accounts']['name']!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Email</b> <a class="pull-right">{!!$data['accounts']['email']!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Mobile</b> <a class="pull-right"><?php echo ($data['accounts']['mobile'] != null) ? $data['accounts']['mobile'] : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Phone</b> <a class="pull-right">{!!$data['accounts']['phone']!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Account Type</b> <a class="pull-right"><?php echo ($data['accounts']['tbl_accounttypes'] != null) ? $data['accounts']['tbl_accounttypes']['account_type'] : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Industry Type</b> <a class="pull-right"><?php echo ($data['accounts']['tbl_industrytypes'] != null) ? $data['accounts']['tbl_industrytypes']['type'] : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Website</b> <a class="pull-right"><?php echo ($data['accounts']['website'] != null) ? $data['accounts']['website'] : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Description</b> <a class="pull-right"><?php echo ($data['accounts']['description'] != null) ? $data['accounts']['description'] : ''; ?></a>
                                </li>
                            </ul>

                            <a href="{!!$data['editLink']!!}" class="btn btn-sm btn-primary btn-block"><b>Edit</b></a>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                    <!-- About Me card -->
                    <div class="card shadow card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Address</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>
                            <p class="text-muted"><?php echo ($data['accounts']['street'] != null) ? $data['accounts']['street'] : ''; ?>
                                <?php echo ($data['accounts']['city'] != null) ? ', ' . $data['accounts']['city'] : ''; ?>
                                <?php echo ($data['accounts']['tbl_states'] != null) ? ', ' . $data['accounts']['tbl_states']['name'] : ''; ?>
                                <?php echo ($data['accounts']['tbl_countries'] != null) ? ', ' . $data['accounts']['tbl_countries']['name'] : ''; ?>
                                <?php echo ($data['accounts']['zip'] != null) ? ', ' . $data['accounts']['zip'] : ''; ?></p>

                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
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
                            <!-- <a href="{{url('accounts')}}" class="btn btn-sm btn-outline-secondary"><i class="nav-icon fas fa-long-arrow-alt-left"></i></a> -->
                        </div>
                    </div>

                    <div class="card shadow card-primary card-outline">
                        <div class="card-header with-border">
                            <h3 class="card-title">
                                Companies &nbsp;<small class="badge badge-secondary">{!!$data['c_total']!!}</small>
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            {!!$data['c_table']!!}
                        </div>
                        <div class="card-footer bg-white pull-right text-right">
                            <a href="{{url('accounts')}}" class="btn btn-sm btn-outline-secondary"><i class="nav-icon fas fa-long-arrow-alt-left"></i></a>
                        </div>
                    </div>

                    <!-- /.card -->

                </div>
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
@extends('layouts.adminlte-boot-4.user')
@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Lead Profile</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
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
                <div class='alert alert-danger'>
                    {{session('error')}}
                </div>
                @endif
                <!-- general form elements -->
                <div class="col-md-3">
                    <!-- Profile Image -->
                    <div class="card shadow card-primary card-outline">
                        <div class="card-header no-border text-center">
                            <img class="border img-responsive img-circle" src="<?php echo ($data['leadarr']['picture'] != "") ? url($data['leadarr']['picture']) : url('/uploads/default/leads.png'); ?>" height="85" width="85" alt="User profile picture" />
                            <h3 class="profile-username text-center">{!!($data['leadarr']['tbl_salutations']!='')?$data['leadarr']['tbl_salutations']['salutation'].' ':''!!}&nbsp;{!!$data['leadarr']['first_name']!!}&nbsp;{!!$data['leadarr']['last_name']!!}</h3>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>First Name</b>
                                    <a class="pull-right">{!!$data['leadarr']['first_name']!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Last Name</b>
                                    <a class="pull-right">{!!$data['leadarr']['last_name']!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Email</b>
                                    <a class="pull-right">{!!$data['leadarr']['email']!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Designation</b>
                                    <a class="pull-right">{!!$data['leadarr']['designation']!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Mobile</b>
                                    <a class="pull-right"><?php echo ($data['leadarr']['mobile'] != null) ? $data['leadarr']['mobile'] : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Phone</b>
                                    <a class="pull-right">{!!$data['leadarr']['phone']!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Lead Source</b>
                                    <a class="pull-right">{!!($data['leadarr']['tbl_leadsource'] != null) ? $data['leadarr']['tbl_leadsource']['leadsource'] : ''!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Lead Status</b>
                                    <a class="pull-right">{!!($data['leadarr']['tbl_leadstatus'] != null) ? $data['leadarr']['tbl_leadstatus']['status'] : ''!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Industry Type</b>
                                    <a class="pull-right">{!!($data['leadarr']['tbl_industrytypes'] != null) ? $data['leadarr']['tbl_industrytypes']['type'] : ''!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Account</b>
                                    <a class="pull-right">{!!($data['leadarr']['tbl__accounts'] != null) ? $data['leadarr']['tbl__accounts']['name'] : ''!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Company</b>
                                    <a class="pull-right">{!!($data['leadarr']['tbl__accounts'] != null) ? $data['leadarr']['tbl__accounts']['company'] : ''!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Product</b>
                                    <a class="pull-right">{!!($data['leadarr']['tbl_products'] != null) ? $data['leadarr']['tbl_products']['name'] : ''!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Website</b>
                                    <a class="pull-right">{!!($data['leadarr']['website'] != null) ? $data['leadarr']['website'] : ''!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Notes</b>
                                    <a class="pull-right">{!!($data['leadarr']['notes'] != null) ? $data['leadarr']['notes'] : ''!!}</a>
                                </li>
                            </ul>

                            <a href="{!!$data['editLink']!!}" class="btn btn-primary btn-block"><b>Edit</b></a>
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
                            <p class="text-muted">
                                <?php echo ($data['leadarr']['street'] != null) ? $data['leadarr']['street'] : ''; ?>
                                <?php echo ($data['leadarr']['city'] != null) ? ', ' . $data['leadarr']['city'] : ''; ?>
                                <?php echo ($data['leadarr']['tbl_states'] != null) ? ', ' . $data['leadarr']['tbl_states']['name'] : ''; ?>
                                <?php echo ($data['leadarr']['tbl_countries'] != null) ? ', ' . $data['leadarr']['tbl_countries']['name'] : ''; ?>
                                <?php echo ($data['leadarr']['zip'] != null) ? ', ' . $data['leadarr']['zip'] : ''; ?>
                            </p>

                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <div class="col-md-9">


                    <div class="card shadow card-primary card-outline">
                        <div class="card-header with-border">
                            <h3 class="card-title">
                                Deals &nbsp;<small class="badge badge-secondary">{!!$data['total']!!}</small>
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            {!!$data['table']!!}
                        </div>
                        <div class="card-footer bg-white pull-right text-right">
                            <a href="{{url('leads')}}" class="btn btn-sm btn-outline-secondary"><i class="nav-icon fas fa-long-arrow-alt-left"></i></a>
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
        $(".sidebar-menu li").removeClass("active");
        $("#lileads").addClass("active");
    });
</script>
@endsection
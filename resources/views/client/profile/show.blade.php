@extends('layouts.adminlte-boot-4.client')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Profile </h1>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content mt-2 mx-0">
        <div class="container-fluid">
            <!-- Main row -->
            <div class="row">
                <!-- Main content -->
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-md-3">
                            <!-- Profile Image -->
                            <div class="card shadow card-primary card-outline">
                                <div class="card-body card-profile">
                                    <img class="profile-user-img img-responsive img-circle" src="<?php echo (($data['userdata'] != null) && ($data['userdata']['picture'] != null)) ? url($data['userdata']['picture']) :  url('/uploads/default/user.png'); ?>" alt="User profile picture">

                                    <h3 class="profile-username text-center">{!!$data['userdata']['name']!!}</h3>


                                    <ul class="list-group list-group-unbordered">
                                        <li class="list-group-item">
                                            <b>Email</b> <a class="pull-right">{!!$data['userdata']['email']!!}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Mobile</b> <a class="pull-right">{!!$data['userdata']['mobile']!!}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Currenccy</b> <a class="pull-right"><span>{!!$data['userdata']['currency']['html_code']!!}</span>&nbsp;{!!$data['userdata']['currency']['name']!!}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Address</b> <a class="pull-right">{!!$data['userdata']['address']!!}</a>
                                        </li>

                                        <li class="list-group-item">
                                            <b>Country</b> <a class="pull-right"><?php echo ($data['userdata']['country'] > 0) ? $data['userdata']['tbl_countries']['name'] : ''  ?></a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>State</b> <a class="pull-right"><?php echo ($data['userdata']['state'] > 0) ? $data['userdata']['tbl_states']['name'] : ''  ?></a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>City</b> <a class="pull-right"><?php echo ($data['userdata']['city'] != '') ? $data['userdata']['city'] : '' ?></a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Zip</b> <a class="pull-right"><?php echo ($data['userdata']['zip'] != '') ? $data['userdata']['zip'] : '' ?></a>
                                        </li>
                                    </ul>
                                    <a href="{{url('/clients/edit/'.$data['userdata']['id'])}}" class="btn btn-primary btn-block"><b>Edit</b></a>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-9">

                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                </div>
                <!-- /.content -->
            </div>
            <!-- /.row (main row) -->
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        // $("#ulwebtolead").addClass('menu-open');
        // $("#ulwebtolead ul").css('display', 'block');
        // $("#licreateform").addClass("active");
    });
</script>
@endsection
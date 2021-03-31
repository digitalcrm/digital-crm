@extends('layouts.adminlte-boot-4.user')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1>Contact Profile</h1>
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
                <div class='alert alert-success'>
                    {{session('error')}}
                </div>
                @endif
                <!-- general form elements -->
                <div class="col-md-3">
                    <!-- Profile Image -->
                    <div class="card shadow card-primary card-outline">
                        <div class="card-header text-center">
                            <img class="profile-user-img img-responsive img-circle" src="<?php echo ($data['contact']['picture'] != "") ? url($data['contact']['picture']) : url('/uploads/default/contacts.png'); ?>" height="50" width="50" alt="User profile picture" />
                            <h3 class="profile-username text-center"><?php echo $data['contact']['first_name'] . ' ' . $data['contact']['last_name']; ?></h3>
                        </div>
                        <div class="card-body card-profile p-0">
                            <!--<p class="text-muted text-center">Software Engineer</p>-->

                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>First Name</b> <a class="pull-right">{!!$data['contact']['first_name']!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Last Name</b> <a class="pull-right">{!!$data['contact']['last_name']!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Email</b> <a class="pull-right">{!!$data['contact']['email']!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Designation</b> <a class="pull-right">{!!$data['contact']['designation']!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Mobile</b> <a class="pull-right"><?php echo ($data['contact']['mobile'] != null) ? $data['contact']['mobile'] : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Phone</b> <a class="pull-right">{!!$data['contact']['phone']!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Account</b> <a class="pull-right"><?php echo ($data['contact']['tbl__accounts'] != null) ? $data['contact']['tbl__accounts']['name'] : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Company</b> <a class="pull-right"><?php echo ($data['contact']['tbl__accounts'] != null) ? $data['contact']['tbl__accounts']['company'] : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Lead Source</b> <a class="pull-right"><?php echo ($data['contact']['tbl_leadsource'] != null) ? $data['contact']['tbl_leadsource']['leadsource'] : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Website</b> <a class="pull-right"><?php echo ($data['contact']['website'] != null) ? $data['contact']['website'] : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Notes</b> <a class="pull-right"><?php echo ($data['contact']['notes'] != null) ? $data['contact']['notes'] : ''; ?></a>
                                </li>

                            </ul>


                        </div>
                        <!-- /.card-body -->
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <div class="btn-group btn-flat pull-right">
                                <a href="{!!$data['editLink']!!}" class="btn btn-sm btn-primary btn-block"><b>Edit</b></a>
                                <a href="{{url('contacts')}}" class="btn btn-sm btn-outline-secondary"><i class="nav-icon fas fa-long-arrow-alt-left"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->

                </div>
                <div class="col-md-3">
                    <!-- About Me card -->
                    <div class="card shadow card-primary card-outline">
                        <div class="card-header with-border">
                            <h3 class="card-title">Social Networks</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>Google</b> <a class="pull-right"><?php echo $data['contact']['google_id']; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Linked In</b> <a class="pull-right"><?php echo $data['contact']['linkedin_id']; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Facebook</b> <a class="pull-right"><?php echo $data['contact']['facebook_id']; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Twitter</b> <a class="pull-right"><?php echo $data['contact']['twitter_id']; ?></a>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
                <div class="col-md-4">
                    <!-- About Me card -->
                    <div class="card shadow card-primary card-outline">
                        <div class="card-header with-border">
                            <h3 class="card-title">Address</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>
                            <p class="text-muted"><?php echo ($data['contact']['street'] != null) ? $data['contact']['street'] : ''; ?>
                                <?php echo ($data['contact']['city'] != null) ? ', ' . $data['contact']['city'] : ''; ?>
                                <?php echo ($data['contact']['tbl_states'] != null) ? ', ' . $data['contact']['tbl_states']['name'] : ''; ?>
                                <?php echo ($data['contact']['tbl_countries'] != null) ? ', ' . $data['contact']['tbl_countries']['name'] : ''; ?>
                                <?php echo ($data['contact']['zip'] != null) ? ', ' . $data['contact']['zip'] : ''; ?></p>

                        </div>
                        <!-- /.card-body -->

                    </div>
                </div>
            </div>


            <!-- /.row -->
            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <section class="col-lg-10">

                </section>
                <!-- right col -->
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
        $("#licontacts").addClass("active");
    });
</script>
@endsection
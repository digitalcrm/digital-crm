@extends('layouts.user')
@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
<!--    <section class="content-header">
        <h1>
    <?php echo $data['accounts']['name']; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Lead</li>
        </ol>
    </section>-->



    <!-- Main content -->
    <section class="content mt-2 mx-0">
	<div class="container-fluid">
        <!-- Small boxes (Stat box) -->
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
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            Account
                        </h3>
                    </div>
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive img-circle" src="<?php echo ($data['accounts']['picture'] != "") ? url($data['accounts']['picture']) : url('/uploads/default/accounts.png'); ?>" height="50" width="50" alt="User profile picture"/>

                        <h3 class="profile-username text-center"><?php echo $data['accounts']['name']; ?></h3>

                                <!--<p class="text-muted text-center">Software Engineer</p>-->

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

                        <a href="{!!$data['editLink']!!}" class="btn btn-primary btn-block"><b>Edit</b></a>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
                <!-- About Me Box -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Address</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>
                        <p class="text-muted"><?php echo ($data['accounts']['street'] != null) ? $data['accounts']['street'] : ''; ?>
                            <?php echo ($data['accounts']['city'] != null) ? ', ' . $data['accounts']['city'] : ''; ?>
                            <?php echo ($data['accounts']['tbl_states'] != null) ? ', ' . $data['accounts']['tbl_states']['name'] : ''; ?>
                            <?php echo ($data['accounts']['tbl_countries'] != null) ? ', ' . $data['accounts']['tbl_countries']['name'] : ''; ?>
                            <?php echo ($data['accounts']['zip'] != null) ? ', ' . $data['accounts']['zip'] : ''; ?></p>
                        <hr>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <div class="col-md-9">


                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            Leads &nbsp;<small class="badge badge-secondary">{!!$data['total']!!}</small>
                        </h3>
                    </div>
                    <div class="box-body">
                        {!!$data['table']!!}
                    </div>
                    <div class="box-footer">
                        <div class="btn-group btn-flat pull-right">
                            <a href="{{url('accounts')}}" class="btn btn-default">Back</a>
                        </div>
                    </div>
                </div>

                <!-- /.box -->

            </div>
            <!-- ./col -->
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
        $(".sidebar-menu li").removeClass("menu-open");
        $(".sidebar-menu li").removeClass("active");
        $("#ulaccounts").addClass('menu-open');
        $("#ulaccounts ul").css('display', 'block');
        // $("#licreatelead").addClass("active");
    });


</script>
@endsection
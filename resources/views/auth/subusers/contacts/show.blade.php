@extends('layouts.adminlte-boot-4.user')
@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
<!--    <section class="content-header">
        <h1>
    <?php echo $data['contact']['first_name'] . ' ' . $data['contact']['last_name']; ?>
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
            <div class="col-lg-12">
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

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <img src="<?php echo ($data['contact']['picture'] != "") ? url($data['contact']['picture']) : ''; ?>" height="30" width="30" />&nbsp;
                            <?php echo $data['contact']['first_name'] . ' ' . $data['contact']['last_name']; ?>
                        </h3>
                        <a href="{!!$data['editLink']!!}" class="btn btn-primary pull-right">Edit</a>
                    </div>
                    <div class="box-body">
                        <section class="col-lg-4">
                            <div class="form-group">
                                <label for="title">First Name</label>
                                <p>{!!$data['contact']['first_name']!!}</p>
                            </div>
                            <div class="form-group">
                                <label for="redirecturl">Mobile</label>
                                <p><?php echo ($data['contact']['mobile'] != null) ? $data['contact']['mobile'] : ''; ?></p>
                            </div>
                            <div class="form-group">
                                <label for="frommail">Lead Source</label>
                                <p><?php echo ($data['contact']['tbl_leadsource'] != null) ? $data['contact']['tbl_leadsource']['leadsource'] : ''; ?></p>
                            </div>

                            <div class="form-group">
                                <label for="message">Notes</label>
                                <p><?php echo ($data['contact']['notes'] != null) ? $data['contact']['notes'] : ''; ?></p>
                            </div>
                        </section>
                        <section class="col-lg-4">
                            <div class="form-group">
                                <label for="title">Last Name</label>
                                <p><?php echo ($data['contact']['last_name'] != null) ? $data['contact']['last_name'] : ''; ?></p>
                            </div>

                            <div class="form-group">
                                <label for="frommail">Phone</label>
                                <p>{!!$data['contact']['phone']!!}</p>
                            </div>
                            <div class="form-group">
                                <label for="frommail">Account</label>
                                <p><?php echo ($data['contact']['tbl__accounts'] != null) ? $data['contact']['tbl__accounts']['name'] : ''; ?></p>
                            </div>

                        </section>

                        <section class="col-lg-4">
                            <div class="form-group">
                                <label for="posturl">Email</label>
                                <p>{!!$data['contact']['email']!!}</p>
                            </div>
                            <div class="form-group">
                                <!-- <label for="title">Profile Picture</label> -->
                                <img src="<?php echo ($data['contact']['picture'] != "") ? url($data['contact']['picture']) : ''; ?>" height="50" width="50" alt=""/>
                            </div>
                            <div class="form-group">
                                <label for="frommail">Website</label>
                                <p><?php echo ($data['contact']['website'] != null) ? $data['contact']['website'] : ''; ?></p>
                            </div>
                        </section>
                        <div class="form-group col-lg-12">
                            <div class="box-header with-border">
                                <h3 class="box-title">Address Information</h3>
                            </div>
                        </div>
                        <section class="col-lg-6">
                            <div class="form-group">
                                <label for="country">Country</label>
                                <p><?php echo ($data['contact']['tbl_countries'] != null) ? $data['contact']['tbl_countries']['name'] : ''; ?></p>
                            </div>
                            <div class="form-group">
                                <label for="city">City</label>
                                <p><?php echo ($data['contact']['city'] != null) ? $data['contact']['city'] : ''; ?></p>
                            </div>
                            <div class="form-group">
                                <label for="zip">Zip</label>
                                <p><?php echo ($data['contact']['zip'] != null) ? $data['contact']['zip'] : ''; ?></p>
                            </div>
                        </section>
                        <!-- Left col -->
                        <section class="col-lg-6">
                            <div class="form-group">
                                <label for="state">State</label>
                                <p><?php echo ($data['contact']['tbl_states'] != null) ? $data['contact']['tbl_states']['name'] : ''; ?></p>
                            </div>
                            <div class="form-group">
                                <label for="street">Street</label>
                                <p><?php echo ($data['contact']['street'] != null) ? $data['contact']['street'] : ''; ?></p>
                            </div>
                        </section>

                    </div>
                    <div class="box-footer">
                        <a href="#" class="btn btn-danger pull-right">Delete</a>
                        <a href="{{url('contacts')}}" class="btn btn-primary">Back</a>
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
    $(function () {
        $(".sidebar-menu li").removeClass("menu-open");
        $(".sidebar-menu li").removeClass("active");
        $("#ulleads").addClass('menu-open');
        $("#ulleads ul").css('display', 'block');
        // $("#licreatelead").addClass("active");
    });


</script>
@endsection

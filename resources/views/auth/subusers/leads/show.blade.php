@extends('layouts.adminlte-boot-4.user')
@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
<!--    <section class="content-header">
        <h1>
            <?php echo $data['leadarr']['first_name'] . ' ' . $data['leadarr']['last_name']; ?>
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
                            <img src="<?php echo ($data['leadarr']['picture'] != "") ? url($data['leadarr']['picture']) : ''; ?>" height="30" width="30" />&nbsp;
                            <?php echo $data['leadarr']['first_name'] . ' ' . $data['leadarr']['last_name']; ?>
                        </h3>
                        <a href="{!!$data['editLink']!!}" class="btn btn-primary pull-right">Edit</a>
                    </div>
                    <div class="box-body">
                        <section class="col-lg-6">
                            <div class="form-group">
                                <label for="title">First Name</label>
                                <p>{!!$data['leadarr']['first_name']!!}</p>
                            </div>

                            <div class="form-group">
                                <label for="posturl">Email</label>
                                <p>{!!$data['leadarr']['email']!!}</p>
                            </div>

                            <div class="form-group">
                                <label for="frommail">Phone</label>
                                <p>{!!$data['leadarr']['phone']!!}</p>
                            </div>

                            <div class="form-group">
                                <label for="frommail">Lead Source</label>
                                <p><?php echo ($data['leadarr']['tbl_leadsource'] != null) ? $data['leadarr']['tbl_leadsource']['leadsource'] : ''; ?></p>
                            </div>

                            <div class="form-group">
                                <label for="frommail">Industry Type</label>
                                <p><?php echo ($data['leadarr']['tbl_industrytypes'] != null) ? $data['leadarr']['tbl_industrytypes']['type'] : ''; ?></p>
                            </div>

                            <div class="form-group">
                                <label for="message">Notes</label>
                                <p><?php echo ($data['leadarr']['notes'] != null) ? $data['leadarr']['notes'] : ''; ?></p>
                            </div>
                        </section>
                        <section class="col-lg-6">

                            <div class="form-group">
                                <label for="title">Last Name</label>
                                <p><?php echo ($data['leadarr']['last_name'] != null) ? $data['leadarr']['last_name'] : ''; ?></p>
                            </div>

                            <div class="form-group">
                                <!-- <label for="title">Profile Picture</label> -->
                                <img src="<?php echo ($data['leadarr']['picture'] != "") ? url($data['leadarr']['picture']) : ''; ?>" height="75" width="75" />
                            </div>

                            <div class="form-group">
                                <label for="redirecturl">Mobile</label>
                                <p><?php echo ($data['leadarr']['mobile'] != null) ? $data['leadarr']['mobile'] : ''; ?></p>
                            </div>

                            <div class="form-group">
                                <label for="frommail">Lead Status</label>
                                <p><?php echo ($data['leadarr']['tbl_leadstatus'] != null) ? $data['leadarr']['tbl_leadstatus']['status'] : ''; ?></p>
                            </div>

                            <div class="form-group">
                                <label for="frommail">Account</label>
                                <p><?php echo ($data['leadarr']['tbl__accounts'] != null) ? $data['leadarr']['tbl__accounts']['name'] : ''; ?></p>
                            </div>

                            <div class="form-group">
                                <label for="frommail">Website</label>
                                <p><?php echo ($data['leadarr']['website'] != null) ? $data['leadarr']['website'] : ''; ?></p>
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
                                <p><?php echo ($data['leadarr']['tbl_countries'] != null) ? $data['leadarr']['tbl_countries']['name'] : ''; ?></p>
                            </div>
                            <div class="form-group">
                                <label for="city">City</label>
                                <p><?php echo ($data['leadarr']['city'] != null) ? $data['leadarr']['city'] : ''; ?></p>
                            </div>
                            <div class="form-group">
                                <label for="zip">Zip</label>
                                <p><?php echo ($data['leadarr']['zip'] != null) ? $data['leadarr']['zip'] : ''; ?></p>
                            </div>
                        </section>
                        <!-- Left col -->
                        <section class="col-lg-6">
                            <div class="form-group">
                                <label for="state">State</label>
                                <p><?php echo ($data['leadarr']['tbl_states'] != null) ? $data['leadarr']['tbl_states']['name'] : ''; ?></p>
                            </div>
                            <div class="form-group">
                                <label for="street">Street</label>
                                <p><?php echo ($data['leadarr']['street'] != null) ? $data['leadarr']['street'] : ''; ?></p>
                            </div>
                        </section>

                    </div>
                    <div class="box-footer">
                        <a href="#" class="btn btn-danger pull-right">Delete</a>
                        <a href="{{url('leads')}}" class="btn btn-primary">Back</a>
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

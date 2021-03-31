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
            <div class="col-lg-10">
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
                            <img src="<?php echo ($data['accounts']['picture'] != "") ? url($data['accounts']['picture']) : ''; ?>" height="50" width="50" />&nbsp;
                            <?php echo $data['accounts']['name']; ?>
                        </h3>
                        <a href="{!!$data['editLink']!!}" class="btn btn-primary pull-right">Edit</a>
                    </div>
                    <div class="box-body">
                        <section class="col-lg-6">
                            <div class="form-group">
                                <label for="title">Name</label>
                                <p>{!!$data['accounts']['name']!!}</p>
                            </div>

                            <div class="form-group">
                                <label for="redirecturl">Mobile</label>
                                <p><?php echo ($data['accounts']['mobile'] != null) ? $data['accounts']['mobile'] : ''; ?></p>
                            </div>


                            <div class="form-group">
                                <label for="frommail">Industry Type</label>
                                <p><?php echo ($data['accounts']['tbl_industrytypes'] != null) ? $data['accounts']['tbl_industrytypes']['type'] : ''; ?></p>
                            </div>

                            <div class="form-group">
                                <label for="message">Description</label>
                                <p><?php echo ($data['accounts']['description'] != null) ? $data['accounts']['description'] : ''; ?></p>
                            </div>
                        </section>
                        <section class="col-lg-6">
                            <div class="form-group">
                                <label for="posturl">Email</label>
                                <p>{!!$data['accounts']['email']!!}</p>
                            </div>

                            <div class="form-group">
                                <!-- <label for="title">Profile Picture</label> -->
                                <img src="<?php echo ($data['accounts']['picture'] != "") ? url($data['accounts']['picture']) : ''; ?>" height="75" width="75" />
                            </div>

                            <div class="form-group">
                                <label for="frommail">Phone</label>
                                <p>{!!$data['accounts']['phone']!!}</p>
                            </div>

                            <div class="form-group">
                                <label for="frommail">Account Type</label>
                                <p><?php echo ($data['accounts']['tbl_accounttypes'] != null) ? $data['accounts']['tbl_accounttypes']['account_type'] : ''; ?></p>
                            </div>

                            <div class="form-group">
                                <label for="frommail">Website</label>
                                <p><?php echo ($data['accounts']['website'] != null) ? $data['accounts']['website'] : ''; ?></p>
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
                                <p><?php echo ($data['accounts']['tbl_countries'] != null) ? $data['accounts']['tbl_countries']['name'] : ''; ?></p>
                            </div>
                            <div class="form-group">
                                <label for="city">City</label>
                                <p><?php echo ($data['accounts']['city'] != null) ? $data['accounts']['city'] : ''; ?></p>
                            </div>
                            <div class="form-group">
                                <label for="zip">Zip</label>
                                <p><?php echo ($data['accounts']['zip'] != null) ? $data['accounts']['zip'] : ''; ?></p>
                            </div>
                        </section>
                        <!-- Left col -->
                        <section class="col-lg-6">
                            <div class="form-group">
                                <label for="state">State</label>
                                <p><?php echo ($data['accounts']['tbl_states'] != null) ? $data['accounts']['tbl_states']['name'] : ''; ?></p>
                            </div>
                            <div class="form-group">
                                <label for="street">Street</label>
                                <p><?php echo ($data['accounts']['street'] != null) ? $data['accounts']['street'] : ''; ?></p>
                            </div>
                        </section>

                    </div>
                    <div class="box-footer">
                        <a href="#" class="btn text-danger pull-right">Delete</a>
                        <a href="{{url('accounts')}}" class="btn text-primary pull-right">Back</a>
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
        $("#ulaccounts").addClass('menu-open');
        $("#ulaccounts ul").css('display', 'block');
        // $("#licreatelead").addClass("active");
    });


</script>
@endsection
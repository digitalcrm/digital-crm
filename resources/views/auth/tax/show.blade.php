@extends('layouts.adminlte-boot-4.user')
@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Tax</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content mt-2 mx-0">
	<div class="container-fluid">
        <!-- Small cardes (Stat card) -->
        <div class="row">
            <div class="col-lg-4">
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

                <div class="card shadow card-primary card-outline">

                    <div class="card-body">
                        <section class="col-lg-12">
                            <div class="form-group">
                                <label for="name">Name</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group">
                                    <p><?php echo $data->name ?></p>                                    
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="tax">Tax</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group">
                                    <p><?php echo $data->tax ?></p>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="card-footer bg-white pull-right text-right">
                        <a href="{{url('/tax')}}" class="btn btn-outline-secondary mr-1"><i class="nav-icon fas fa-long-arrow-alt-left"></i></a>
                        <a href="#" class="btn btn-default"><i class="far text-danger fa-trash-alt"></i></a>
                        <a href="<?php echo url('tax/' . $data->tax_id . '/edit'); ?>" class="btn btn-primary">Edit</a>
                    </div>
                </div>

                <!-- /.card -->

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
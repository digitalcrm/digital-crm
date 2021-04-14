@extends('layouts.adminlte-boot-4.user')
@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

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
                            <?php echo $data->title; ?>
                        </h3>
                        <a href="<?php echo url('newsletter/' . $data->nl_id . '/edit'); ?>" class="btn btn-primary btn-flat pull-right">Edit</a>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="Category">Category</label>
                            <p>
                                <?php
                                if ($data->type == 1) {
                                    echo 'Accounts';
                                }
                                if ($data->type == 2) {
                                    echo 'Contacts';
                                }
                                if ($data->type == 3) {
                                    echo 'Leads';
                                }
                                ?>
                            </p>
                        </div>
                        <div class="form-group">
                            <label for="title">Title</label>
                            <p><?php echo $data->title; ?></p>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <p><?php echo $data->subject; ?></p>
                        </div>
                        <div class="form-group">
                            <label for="frommail">Message</label>
                            <p><?php echo $data->message; ?></p>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="btn-group btn-flat pull-right">
                            <a href="{{url('newsletter')}}" class="btn btn-default">Back</a>
                            <a href="#" class="btn btn-danger">Delete</a>&nbsp;
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

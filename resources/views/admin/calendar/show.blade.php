@extends('layouts.adminlte-boot-4.admin')
@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Calendar
            <small id="total">0</small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content mt-2 mx-0">
	<div class="container-fluid">
        <!-- Small cardes (Stat card) -->
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

                <div class="card card-primary">
                    <div class="card-header with-border">
                        <h3 class="card-title">
                            <?php echo $data['event']->title; ?>
                        </h3>
                        <div class="btn-group btn-flat float-right">
                            <a href="<?php echo url('admin/calendar/' . $data['event']->ev_id . '/edit'); ?>" class="btn btn-flat btn-primary">Edit</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="user">User</label>
                            <p><?php echo $data['user']->name; ?></p>
                        </div>
                        <div class="form-group">
                            <label for="title">Title</label>
                            <p><?php echo $data['event']->title; ?></p>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <p><?php echo $data['event']->description; ?></p>
                        </div>
                        <div class="form-group">
                            <label for="time">Time</label>
                            <p><?php echo $data['event']->start_date . ' ' . $data['event']->start_time . ' - ' . $data['event']->end_date . ' ' . $data['event']->end_time; ?></p>
                        </div>
                        <?php
                        if ($data['member'] != '') {
                            ?>
                            <div class="form-group">
                                <label for="category"><?php echo $data['category']; ?></label>
                                <p><?php echo $data['member']; ?></p>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="card-footer bg-white border-top">
                        <div class="btn-group float-right">
                            <a href="{{url('admin/calendar')}}" class="btn btn-default">Back</a>
                            <a href="{{url('admin/calendar/delete/'.$data['event']->ev_id)}}" class="btn btn-danger">Delete</a>&nbsp;
                        </div>

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
//        $("#ulaccounts").addClass('menu-open');
//        $("#ulaccounts ul").css('display', 'block');
        // $("#licreatelead").addClass("active");
    });


</script>
@endsection
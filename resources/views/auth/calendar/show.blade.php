@extends('layouts.user')
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
                            <?php echo $data['event']->title; ?>
                        </h3>
                        <div class="btn-group btn-flat pull-right">
                            <a href="<?php echo url('calendar/' . $data['event']->ev_id . '/edit'); ?>" class="btn btn-flat btn-primary">Edit</a>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <p><?php echo $data['event']->title; ?></p>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <p><?php echo $data['event']->description; ?></p>
                        </div>
                        <div class="form-group">
                            <label for="time">Date/ Time</label>
                            <p><?php echo $data['event']->start_date . ' ' . $data['event']->start_time . ' - ' . $data['event']->end_date . ' ' . $data['event']->end_time; ?></p>
                        </div>
                        <div class="form-group">
                            <label for="location">Location</label>
                            <p><?php echo $data['event']->location; ?></p>
                        </div>
                        <div class="form-group">
                            <label for="allday">All Day</label>
                            <p><?php echo ($data['event']->allday == 1) ? "Yes" : 'No'; ?></p>
                        </div>
                        <div class="form-group">
                            <label for="allday">Event Type</label>
                            <p><?php echo ($data['event']->tbl_event_types != '') ? $data['event']->tbl_event_types->type : ''; ?></p>
                        </div>
                        <?php
                        if ($data['user'] != '') {
                            ?>
                            <div class="form-group col-lg-12">
                                <h4 class="box-title text-primary">Related To</h4>
                            </div>

                            <div class="form-group">
                                <label for="category"><?php echo $data['category']; ?></label>
                                <p><?php echo $data['user']; ?></p>
                            </div>
                            <?php
                        }
                        ?>


                    </div>
                    <div class="box-footer">
                        <div class="btn-group pull-right">
                            <a href="{{url('calendar')}}" class="btn btn-default">Back</a>
                            <a href="{{url('calendar/delete/'. $data['event']->ev_id)}}" class="btn btn-danger">Delete</a>&nbsp;
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
//        $("#ulaccounts").addClass('menu-open');
//        $("#ulaccounts ul").css('display', 'block');
        // $("#licreatelead").addClass("active");
    });


</script>
@endsection
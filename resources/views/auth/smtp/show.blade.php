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
                            <?php echo $data->name; ?>
                        </h3>
                        <a href="<?php echo url('documents/' . $data->doc_id . '/edit'); ?>" class="btn btn-primary btn-flat pull-right">Edit</a>
                    </div>
                    <div class="box-body">
                        <?php
                        if (($data->content_type == 'image/jpeg') || ($data->content_type == 'image/png') || ($data->content_type == 'image/gif')) {
                            echo "<img src='" . $data->document . "' height='480' width='600' />";
                        }
                        if ($data->content_type == 'application/pdf') {
                            echo "<iframe src='" . $data->document . "' frameborder='0' style='width:100%;min-height:640px;'></iframe>";
                        }
                        if ($data->content_type == 'application/msword') {
                            echo "<iframe src='" . $data->document . "'&embedded=true'></iframe>";
                        }
                        ?>
                    </div>
                    <div class="box-footer">
                        <div class="btn-group btn-flat pull-right">
                            <a href="{{url('documents')}}" class="btn btn-default">Back</a>
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
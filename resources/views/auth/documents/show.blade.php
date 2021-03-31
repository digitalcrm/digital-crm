@extends('layouts.adminlte-boot-4.user')
@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1>{{$data->name}}</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content mt-2 mx-0">
	<div class="container-fluid">
        <!-- Small cardes (Stat card) -->
        <div class="row">
            <div class="col-lg-8">
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

                    <div class="card-body text-center">
                        <?php
                        if (($data->content_type == 'image/jpeg') || ($data->content_type == 'image/png') || ($data->content_type == 'image/gif')) {
                            echo "<img class='img-fluid' src='" . $data->document . "' height='auto' width='auto' />";
                        }
                        if ($data->content_type == 'application/pdf') {
                            echo "<iframe src='" . $data->document . "' frameborder='0' style='width:100%;min-height:640px;'></iframe>";
                        }
                        if ($data->content_type == 'application/msword') {
                            echo "<iframe src='" . $data->document . "'&embedded=true'></iframe>";
                        }
                        ?>
                    </div>
                    <div class="card-footer bg-white border-top text-right pull-right">
                        <a href="{{url('documents')}}" class="btn btn-outline-secondary"><i class="nav-icon fas fa-long-arrow-alt-left"></i></a>
                        <a href="#" class="btn btn-outline-secondary"><i class="far text-danger fa-trash-alt"></i></a>
                        <a href="<?php echo url('documents/' . $data->doc_id . '/edit'); ?>" class="btn btn-primary">Edit</a>
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
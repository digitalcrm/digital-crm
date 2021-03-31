@extends('layouts.adminlte-boot-4.admin')
@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Documents
            <small>Control panel</small> 
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Documents</li>
        </ol>
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
                            <?php echo $data->name; ?>
                        </h3>
                        <a href="<?php echo url('admin/documents/' . $data->doc_id . '/edit'); ?>" class="btn btn-primary btn-flat float-right">Edit</a>
                    </div>
                    <div class="card-body">
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
                    <div class="card-footer bg-white border-top">
                        <div class="btn-group btn-flat float-right">
                            <a href="{{url('admin/documents')}}" class="btn btn-default">Back</a>
                            <a href="#" class="btn btn-danger">Delete</a>&nbsp;
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
        $(".sidebar-menu li").removeClass("active");
        $("#lidocuments").addClass("active");
    });


</script>
@endsection
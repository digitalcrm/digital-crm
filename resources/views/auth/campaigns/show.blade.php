@extends('layouts.adminlte-boot-4.user')
@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Campaign</h1>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content mt-2 mx-0"><div class="container-fluid">
        <!-- Small cardes (Stat card) -->
        <div class="row">
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
            <div class="col-md-3">
                <!-- Profile Image -->
                <div class="card shadow card-primary card-outline">
                    <div class="card-body card-profile p-0">
                        <h3 class="profile-username text-center"><?php echo $data['name']; ?></h3>
                        <ul class="list-group list-group-unbordered">
<!--                            <li class="list-group-item">
                                <b>Campaign Name</b> <a class="pull-right"><?php echo $data['name']; ?></a>
                            </li>-->
                            <li class="list-group-item">
                                <b>Start Date</b> <a class="pull-right"><?php echo date('d-m-Y', strtotime($data['start_date'])); ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>End Date</b> <a class="pull-right"><?php echo date('d-m-Y', strtotime($data['end_date'])); ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Status</b> <a class="pull-right"><?php echo ($data['tbl_camp_status'] != null) ? $data['tbl_camp_status']['status'] : ''; ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Type</b> <a class="pull-right"><?php echo ($data['tbl_camp_type'] != null) ? $data['tbl_camp_type']['type'] : ''; ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Budget</b> <a class="pull-right"><?php echo $data['currency']['html_code'] . ' ' . $data['budget']; ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Actual Cost</b> <a class="pull-right"><?php echo $data['currency']['html_code'] . ' ' . $data['actual_cost']; ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Expected Cost</b> <a class="pull-right"><?php echo $data['currency']['html_code'] . ' ' . $data['expected_cost']; ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Expected Revenue</b> <a class="pull-right"><?php echo $data['currency']['html_code'] . ' ' . $data['expected_revenue']; ?></a>
                            </li>
                        </ul>
                    </div>
                    <!-- /.card-body -->
					<div class="card-footer">
						<a href="{{url('campaigns/'.$data['camp_id'].'/edit')}}" class="btn btn-primary btn-block"><b>Edit</b></a>
					</div>
                </div>
                <!-- /.card -->
            </div>

            <div class="col-md-3">
                <!-- About Me Box -->
                <div class="card shadow card-primary card-outline">
                    <div class="card-header with-border">
                        <h3 class="">Details</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body card-profile">
                        <strong>Objective</strong>
                        <p class="text-muted"><?php echo ($data['objective'] != null) ? $data['objective'] : ''; ?><hr>
                        <strong>Description</strong>
                        <p class="text-muted"><?php echo ($data['description'] != null) ? $data['description'] : ''; ?><hr>
                    </div>
					<div class="card-footer text-right pull-right">
                            <a href="{{url('campaigns/')}}" class="btn btn-outline-secondary">Back</a>
                        </div>
                    <!-- /.card-body -->
                </div>
            </div>

        </div>
        <!-- /.row -->
		</div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- /.content-wrapper -->
<script src="{{asset('assets/bower_components/ckeditor/ckeditor.js')}}"></script>
<script>
$(function() {
    $(".sidebar-menu li").removeClass("menu-open");
    $(".sidebar-menu li").removeClass("active");
    $("#ulaccounts").addClass('menu-open');
    $("#ulaccounts ul").css('display', 'block');
    // $("#licreatelead").addClass("active");


    CKEDITOR.replace('message');
});


</script>
@endsection
@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1>Project</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content mt-2 mx-0">
        <div class="container-fluid">
            <!-- Small cardes (Stat card) -->
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
            <div class="row">
                <div class="col-md-4">
                    <!-- Profile Image -->
                    <div class="card shadow card-primary card-outline">
                        <div class="card-header">
                            <h3 class="profile-username"><?php echo $data->name; ?></h3>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <b>Name</b> <a class="pull-right"><?php echo $data->name; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Code</b> <a class="pull-right"><?php echo $data->code; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Type</b> <a class="pull-right"><?php echo ($data->type == 1) ? 'Syndicate' : 'Custom'; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Status</b> <a class="pull-right"><?php echo ($data->tbl_project_status != '') ? $data->tbl_project_status->status : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Manager</b> <a class="pull-right"><?php echo $data->project_manager; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Forecast</b> <a class="pull-right"><?php echo $data->forecast; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Actual Days</b> <a class="pull-right"><?php echo $data->actual_days; ?></a>
                                </li>

                                <li class="list-group-item">
                                    <b>Members</b> <a class="pull-right"><?php echo $data->total_members; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Total Forecast</b> <a class="pull-right"><?php echo $data->total_forecast; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Creation Date</b> <a class="pull-right"><?php echo (($data->creation_date != NULL)) ? date('d-m-Y', strtotime($data->creation_date)) : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Submission Date</b> <a class="pull-right"><?php echo (($data->submission_date != NULL)) ? date('d-m-Y', strtotime($data->submission_date)) : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Description</b> <a class="pull-right"><?php echo $data->description; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Leaves</b> <a class="pull-right"><?php echo $data->leaves; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Different Project</b> <a class="pull-right"><?php echo $data->different_project; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Company Activity</b> <a class="pull-right"><?php echo $data->company_activity; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Working on Other Activity</b> <a class="pull-right"><?php echo $data->working; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Other</b> <a class="pull-right"><?php echo $data->other; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Total Days</b> <a class="pull-right"><?php echo $data->total_days; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Marketing Collateral</b> <a class="pull-right"><?php echo ($data->marketing_collateral == 1) ? 'Yes' : (($data->marketing_collateral == 2) ? 'No' : ''); ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Sample Pages</b> <a class="pull-right"><?php echo ($data->sample_pages == 1) ? 'Yes' : (($data->sample_pages == 2) ? 'No' : ''); ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Client Date</b> <a class="pull-right"><?php echo (($data->client_submit != NULL)) ? date('d-m-Y', strtotime($data->client_submit)) : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Feedback</b> <a class="pull-right"><?php echo $data->feedback; ?></a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-header"><a href="{{url('projects/'.$data->project_id.'/edit')}}" class="btn btn-sm btn-primary btn-block"><b>Edit</b></a></div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>

                <div class="col-md-8">
                    <div class="card shadow card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                Project Members
                            </h3>
                        </div>
                        <div class="card-body">
                            <?php echo $data->table; ?>
                        </div>
                        <div class="card-footer bg-white border-top pull-right text-right">
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#lideals").addClass("active");
    });
</script>
@endsection
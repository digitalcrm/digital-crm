<?php
// echo json_encode($data['deals']['tbl_deal_types']);
$tbl_deal_type = '';
if ($data['deals']['tbl_deal_types'] != '') {
    $tbl_deal_type = $data['deals']['tbl_deal_types']['type'];
}

$leadsource = '';
if ($data['deals']['tbl_leadsource'] != '') {
    $leadsource = $data['deals']['tbl_leadsource']['leadsource'];
}
// exit();
?>
@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1>Deal Profile</h1>
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
                <div class="col-md-3">
                    <!-- Profile Image -->
                    <div class="card shadow card-primary card-outline">
                        <div class="card-header">
                            <h3 class="profile-username"><?php echo $data['deals']['name']; ?></h3>
                        </div>
                        <div class="card-body p-0">
                            <!--<p class="text-muted text-center">Software Engineer</p>-->

                            <ul class="list-group">
                                <li class="list-group-item">
                                    <b>Deal Name</b> <a class="pull-right"><?php echo $data['deals']['name']; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Lead</b> <a class="pull-right"><?php echo $data['deals']['tbl_leads']['first_name'] . ' ' . $data['deals']['tbl_leads']['last_name']; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Account</b> <a class="pull-right"><?php echo $data['account']; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Company</b> <a class="pull-right"><?php echo $data['company']; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Deal Stage</b> <a class="pull-right"><?php echo $data['deals']['tbl_salesfunnel']['salesfunnel']; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Deal Type</b> <a class="pull-right"><?php echo $tbl_deal_type; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Lead Source</b> <a class="pull-right"><?php echo $leadsource; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Amount</b> <a class="pull-right"><?php echo $data['deals']['value']; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Closing Date</b> <a class="pull-right"><?php echo date('d-m-Y', strtotime($data['deals']['closing_date'])); ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Probability</b> <a class="pull-right"><?php echo ($data['deals']['probability'] > 0) ? $data['deals']['probability'] . ' %' : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Notes</b> <a class="pull-right"><?php echo $data['deals']['notes']; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Loss Reason</b> <a class="pull-right"><?php echo ($data['deals']['tbl_lossreasons'] != '') ? $data['deals']['tbl_lossreasons']['reason'] : ''; ?></a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-header"><a href="<?php echo $data['editlink']; ?>" class="btn btn-sm btn-primary btn-block"><b>Edit</b></a></div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>

                <div class="col-md-9">
                    <div class="card shadow card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                Deal Stages
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <section class="col-lg-6">
                                <?php echo $data['eventsUl']; ?>
                            </section>
                        </div>
                        <div class="card-footer bg-white border-top pull-right text-right">
                            <!--<a href="#" class="btn btn-danger pull-right">Delete</a>-->
                            <a href="{{url('deals')}}" class="btn btn-sm btn-outline-secondary"><i class="nav-icon fas fa-long-arrow-alt-left"></i></a>
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
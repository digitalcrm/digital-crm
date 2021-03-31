@extends('layouts.adminlte-boot-4.admin')
@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-10">
                    <h1 class="m-0 text-dark"><?php echo $data['deals']['name']; ?></h1>
                </div>
                <div class="col-sm-2">

                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
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

                    <div class="card shadow card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title float-left">
                                <?php echo $data['deals']['name']; ?>
                            </h3>
                        </div>
                        <div class="card-body">
                            <section class="col-lg-6">
                                <div class="form-group">
                                    <label for="">Name</label>
                                    <p><?php echo $data['deals']['name']; ?></p>
                                </div>

                                <div class="form-group">
                                    <label for="">Lead</label>
                                    <p><?php echo $data['deals']['tbl_leads']['first_name'] . ' ' . $data['deals']['tbl_leads']['last_name']; ?></p>
                                </div>

                                <div class="form-group">
                                    <label for="">Account</label>
                                    <p><?php echo $data['account']; ?></p>
                                </div>

                                <div class="form-group">
                                    <label for="">Company</label>
                                    <p><?php echo $data['company']; ?></p>
                                </div>

                                <div class="form-group">
                                    <label for="">Product</label>
                                    <p><?php echo $data['product']; ?></p>
                                </div>

                                <div class="form-group">
                                    <label for="">Deal Stage</label>
                                    <p><?php echo ($data['deals']['tbl_salesfunnel'] != '') ? $data['deals']['tbl_salesfunnel']['salesfunnel'] : ''; ?></p>
                                </div>


                                <div class="form-group">
                                    <label for="">Lead Source</label>
                                    <p><?php echo ($data['deals']['tbl_leadsource'] != '') ? $data['deals']['tbl_leadsource']['leadsource'] : ''; ?></p>
                                </div>

                                <div class="form-group">
                                    <label for="">Deal Type</label>
                                    <p><?php echo ($data['deals']['tbl_deal_types'] != '') ? $data['deals']['tbl_deal_types']['type'] : ''; ?></p>
                                </div>

                                <div class="form-group">
                                    <label for="">Amount</label>
                                    <p><?php echo '<span>' . $data['currency']['html_code'] . '</span> ' . $data['deals']['value']; ?></p>
                                </div>

                                <div class="form-group">
                                    <label for="">Closing Date</label>
                                    <p><?php echo date('d-m-Y', strtotime($data['deals']['closing_date'])); ?></p>
                                </div>

                                <div class="form-group">
                                    <label for="">Notes</label>
                                    <p><?php echo $data['deals']['notes']; ?></p>
                                </div>
                            </section>
                        </div>
                        <div class="card-footer bg-white border-top text-right pull-right">
                            <a href="#" class="btn text-danger btn-outline-secondary"><i class="far fa-trash-alt"></i></a>
                            <a href="{{url('admin/deals')}}" class="btn  btn-outline-secondary">Back</a>
                            <a href="<?php echo $data['editlink']; ?>" class="btn btn-primary">Edit</a>
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
        $("#lideals").addClass("active");
    });
</script>
@endsection
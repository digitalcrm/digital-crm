@extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1>Order Profile</h1>
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

                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <h3 class="profile-username text-left">Order</h3>
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Order Number</b> <a class="float-right"><?php echo $data->number; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Order Date</b> <a class="float-right"><?php echo date('d-m-Y', strtotime($data->order_date)); ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Shipping Date</b> <a class="float-right"><?php echo  date('d-m-Y', strtotime($data->shipping_date)); ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Quantity</b> <a class="float-right"><?php echo $data->quantity; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Delivery Charges</b> <a class="float-right"><?php echo $data['currency']['html_code'] . ' ' . $data->delivery_charges; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Total Amount</b> <a class="float-right"><?php echo $data['currency']['html_code'] . ' ' . $data->total_amount; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Post Order Stage</b> <a class="float-right"><?php echo ($data->tbl_post_order_stage != '') ? $data->tbl_post_order_stage->stage : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Delivery By</b> <a class="float-right"><?php echo ($data->tbl_deliveryby != '') ? $data->tbl_deliveryby->delivery_by : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Verify By</b> <a class="float-right"><?php echo $data->verify_by; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Remarks</b> <a class="float-right"><?php echo $data->remarks; ?></a>
                                </li>
                            </ul>

                            <a href="<?php echo url('admin/orders/' . $data->oid . '/edit'); ?>" class="btn btn-primary btn-block"><b>Edit</b></a>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <h3 class="profile-username text-left">Deal</h3>
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Deal Name</b> <a class="float-right"><?php echo $data['tbl_deals']['name']; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Lead</b> <a class="float-right"><?php echo $data['lead']['first_name'] . ' ' . $data['lead']['last_name']; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Product</b> <a class="float-right"><?php echo ($data['product'] != '') ? $data['product']['name'] : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Lead Source</b> <a class="float-right"><?php echo ($data['leadsource'] != '') ?  $data['leadsource']['leadsource'] : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Amount</b> <a class="float-right"><?php echo $data['currency']['html_code'] . ' ' . $data['tbl_deals']['value']; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Closing Date</b> <a class="float-right"><?php echo date('d-m-Y', strtotime($data['tbl_deals']['closing_date'])); ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Probability</b> <a class="float-right"><?php echo ($data['tbl_deals']['probability'] > 0) ? $data['tbl_deals']['probability'] . ' %' : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Notes</b> <a class="float-right"><?php echo $data['tbl_deals']['notes']; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Loss Reason</b> <a class="float-right"><?php echo ($data['lossreason'] != '') ? $data['lossreason']['reason'] : ''; ?></a>
                                </li>
                            </ul>

                            <a href="<?php echo url('admin/deals/' . $data['tbl_deals']['deal_id'] . '/edit'); ?>" class="btn btn-primary btn-block"><b>Edit</b></a>
                        </div>
                        <!-- /.card-body -->
                    </div>

                </div>
<div class="col-md-4">
                    <div class="card card-primary card-outline">
                        <div class="card-header no-border text-center">
                            <h3 class="profile-username text-left">Customer</h3>
                            <img class="border img-responsive img-circle" src="<?php echo ($data['lead']['picture'] != "") ? url($data['lead']['picture']) : url('/uploads/default/leads.png'); ?>" height="85" width="85" alt="User profile picture" />
                            <h3 class="profile-username text-center">{!!($data['lead']['tbl_salutations']!='')?$data['lead']['tbl_salutations']['salutation'].' ':''!!}&nbsp;{!!$data['lead']['first_name']!!}&nbsp;{!!$data['lead']['last_name']!!}</h3>
                        </div>
                        <div class="card-body box-profile">
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>First Name</b>
                                    <a class="pull-right">{!!$data['lead']['first_name']!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Last Name</b>
                                    <a class="pull-right">{!!$data['lead']['last_name']!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Email</b>
                                    <a class="pull-right">{!!$data['lead']['email']!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Designation</b>
                                    <a class="pull-right">{!!$data['lead']['designation']!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Mobile</b>
                                    <a class="pull-right"><?php echo ($data['lead']['mobile'] != null) ? $data['lead']['mobile'] : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Phone</b>
                                    <a class="pull-right">{!!$data['lead']['phone']!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Lead Source</b>
                                    <a class="pull-right">{!!($data['lead']['tbl_leadsource'] != null) ? $data['lead']['tbl_leadsource']['leadsource'] : ''!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Lead Status</b>
                                    <a class="pull-right">{!!($data['lead']['tbl_leadstatus'] != null) ? $data['lead']['tbl_leadstatus']['status'] : ''!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Industry Type</b>
                                    <a class="pull-right">{!!($data['lead']['tbl_industrytypes'] != null) ? $data['lead']['tbl_industrytypes']['type'] : ''!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Account</b>
                                    <a class="pull-right">{!!($data['lead']['tbl__accounts'] != null) ? $data['lead']['tbl__accounts']['name'] : ''!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Company</b>
                                    <a class="pull-right">{!!($data['lead']['tbl__accounts'] != null) ? $data['lead']['tbl__accounts']['company'] : ''!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Product</b>
                                    <a class="pull-right">{!!($data['lead']['tbl_products'] != null) ? $data['lead']['tbl_products']['name'] : ''!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Website</b>
                                    <a class="pull-right">{!!($data['lead']['website'] != null) ? $data['lead']['website'] : ''!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Notes</b>
                                    <a class="pull-right">{!!($data['lead']['notes'] != null) ? $data['lead']['notes'] : ''!!}</a>
                                </li>

                            </ul>

                        </div>
                        <!-- /.card-body -->
                    </div>

                </div>
            </div>
            <!-- /.row -->
            <div class="row">

                <div class="col-md-4">

                    <div class="card shadow card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                Order Stages
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <section class="col-lg-6">
                                <?php echo $data['eventsul']; ?>
                            </section>
                        </div>
                        <div class="card-footer bg-white border-top pull-right text-right">
                            <!--<a href="#" class="btn btn-danger pull-right">Delete</a>-->
                            <!-- <a href="{{url('deals')}}" class="btn btn-sm btn-outline-secondary"><i class="nav-icon fas fa-long-arrow-alt-left"></i></a> -->
                        </div>
                    </div>

                </div>


                <div class="col-md-4">
                    <div class="card shadow card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                Deal Stages
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <section class="col-lg-12">
                                <?php echo $data['dealEvents']; ?>
                            </section>
                        </div>
                        <div class="card-footer bg-white border-top pull-right text-right">
                            <!-- <a href="#" class="btn btn-danger float-right">Delete</a> -->
                            <a href="{{url('admin/orders')}}" class="btn btn-sm btn-outline-secondary"><i class="nav-icon fas fa-long-arrow-alt-left"></i></a>
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
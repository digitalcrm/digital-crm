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
            <div class="col-lg-12">
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
                        <a href="{!!$data['editLink']!!}" class="btn btn-primary pull-right">Edit</a>
                    </div>
                    <div class="box-body">
                        <!-- Main content -->
                        <section class="invoice">
                            <!-- title row -->
                            <!--                            
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <h2 class="page-header">
                                                                    <i class="fa fa-globe"></i> AdminLTE, Inc.
                                                                    <small class="pull-right">Date: 2/10/2014</small>
                                                                </h2>
                                                            </div>
                                                        </div>
                            -->
                            <!-- info row -->
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                    From
                                    <address>
                                        <strong>
                                            <?php echo Auth::user()->name; ?>
                                        </strong>
                                        <br>
                                        Phone: <?php echo Auth::user()->mobile; ?><br>
                                        Email: <?php echo Auth::user()->email; ?>
                                    </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                    To
                                    <address>
                                        <strong><?php echo $data['lead']['first_name'] . ' ' . $data['lead']['last_name']; ?></strong><br>
                                        Mobile: <?php echo $data['lead']['mobile']; ?><br>
                                        Email: <?php echo $data['lead']['email']; ?>
                                        <p><?php echo $data['lead']['city']; ?></p><p><?php echo $data['lead']['zip']; ?></p>
                                    </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                    <b>Invoice #<?php echo json_encode($data->inv_number); ?></b><br>
                                    <b>Order ID:</b> <?php echo $data['inv_number']; ?><br>
                                    <b>Payment Due:</b> <?php echo date('d-m-Y', strtotime($data['inv_date'])); ?><br>
                                    <!--<b>Account:</b> 968-34567-->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->

                            <!-- Table row -->
                            <div class="row">
                                <div class="col-xs-12 table-responsive">
                                    <?php echo $data['products']; ?>
<!--                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Qty</th>
                                                <th>Product</th>
                                                <th>Serial #</th>
                                                <th>Description</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Call of Duty</td>
                                                <td>455-981-221</td>
                                                <td>El snort testosterone trophy driving gloves handsome</td>
                                                <td>$64.50</td>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>Need for Speed IV</td>
                                                <td>247-925-726</td>
                                                <td>Wes Anderson umami biodiesel</td>
                                                <td>$50.00</td>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>Monsters DVD</td>
                                                <td>735-845-642</td>
                                                <td>Terry Richardson helvetica tousled street art master</td>
                                                <td>$10.70</td>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>Grown Ups Blue Ray</td>
                                                <td>422-568-642</td>
                                                <td>Tousled lomo letterpress</td>
                                                <td>$25.99</td>
                                            </tr>
                                        </tbody>
                                    </table>-->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->

                            <div class="row">

                                <div class="col-xs-6 pull-right">

                                    <div class="table-responsive">
                                        <?php echo $data['total_table']; ?>
<!--                                        <table class="table">
                                            <tr>
                                                <th style="width:50%">Subtotal:</th>
                                                <td>$250.30</td>
                                            </tr>
                                            <tr>
                                                <th>Tax (9.3%)</th>
                                                <td>$10.34</td>
                                            </tr>
                                            <tr>
                                                <th>Shipping:</th>
                                                <td>$5.80</td>
                                            </tr>
                                            <tr>
                                                <th>Total:</th>
                                                <td>$265.24</td>
                                            </tr>
                                        </table>-->
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->

                        </section>
                        <!-- /.content -->
                    </div>
                    <div class="box-footer">
                        <!--                        <a href="#" class="btn btn-danger pull-left`">Delete</a>&nbsp;-->
                        <a href="{{url('invoice')}}" class="btn btn-primary pull-right">Back</a>
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
        $(".sidebar-menu li").removeClass("active");
        $("#liinvoice").addClass("active");
    });


</script>
@endsection
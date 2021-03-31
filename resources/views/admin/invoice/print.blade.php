@extends('layouts.adminlte-boot-4.admin')
@section('content')

<div class="content-wrapper">
    <section class="content mt-2 mx-0">
	<div class="container-fluid">
        <!-- title row -->
        <div class="row">
            <div class="col-md-12">
                <h2 class="page-header">
                    <i class="fa fa-globe"></i> <?php echo $data->name; ?>
                    <small class="pull-right">Date: <?php echo date('d/m/Y'); ?></small>
                </h2>
            </div>
            <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-3 invoice-col">
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
            <div class="col-sm-3 invoice-col">
                To
                <address>
                    <strong><?php echo $data['lead']['first_name'] . ' ' . $data['lead']['last_name']; ?></strong><br>
                    Mobile: <?php echo $data['lead']['mobile']; ?><br>
                    Email: <?php echo $data['lead']['email']; ?>
                    <p><?php echo $data['lead']['city']; ?></p><p><?php echo $data['lead']['zip']; ?></p>
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-3 invoice-col">
                <b>Invoice #<?php echo json_encode($data->inv_number); ?></b><br>
                <b>Order ID:</b> <?php echo $data['inv_number']; ?><br>
                <b>Payment Due:</b> <?php echo date('d-m-Y', strtotime($data['inv_date'])); ?><br>
            </div>
            <div class="col-sm-3 invoice-col">
                <img src="<?php echo url($data['inv_image']); ?>" width="60">
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- Table row -->
        <div class="row">
            <div class="col-md-12 table-responsive">
                <?php echo $data['products']; ?>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">

            <!-- /.col -->
            <div class="col-md-6 float-right">
                <p class="lead">Amount Due <?php echo date('d-m-Y', strtotime($data['inv_date'])); ?></p>

                <div class="table-responsive">
                    <?php echo $data['total_table']; ?>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
	</div>
    </section>
</div>
@endsection
@extends('layouts.adminlte-boot-4.admin')
@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
	            <div class="container-fluid">
                <div class="row mb-0">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark"><?php echo $data->name; ?></h1>
                    </div>
					<div class="col-sm-6 text-right pull-right">
                        <a href="{!!$data['editLink']!!}" class="btn btn-sm btn-primary">Edit</a>
                            <a href="<?php echo url('admin/invoices/print/' . $data['inv_id']); ?>" target="_blank" class="btn btn-sm  btn-outline-secondary"><i class="fa fa-print"></i> Print</a>
                    </div>
                </div>
                <!-- /.row -->
            </div>
    </section>
    <!-- Main content -->
    <section class="content mt-2 mx-0">
	<div class="container-fluid">
        <!-- Small cardes (Stat card) -->
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

                <div class="card shadow card-primary card-outline">
                    <div class="card-body p-0">
                        <!-- Main content -->
                        <section class="invoice">
                            <!-- title row -->

                            <!-- info row -->
                            <div class="row pl-4 pt-3 invoice-info">
                                <div class="col-sm-3 invoice-col">
                                    From
                                    <address>
                                        <strong>
                                            <?php echo $data['user']['name']; ?>
                                        </strong>
                                        <br>
                                        Phone: <?php echo $data['user']['mobile']; ?><br>
                                        Email: <?php echo $data['user']['email']; ?>
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
                                    <!--<b>Account:</b> 968-34567-->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-3 invoice-col">
                                    <img class="border p-2" src="<?php echo url($data['inv_image']); ?>" width="60">
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
                                <div class="col-md-6 float-right">

                                    <div class="table-responsive">
                                        <?php echo $data['total_table']; ?>
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </section>
                        <!-- /.content -->
                    </div>
                    <div class="card-footer bg-white border-top">
                        <div class="btn-group btn-flat float-right">
                            <button href="{{url('admin/invoices/delete/'.$data['inv_id'])}}" class="btn btn-sm text-danger btn-outline-secondary"><i class="far fa-trash-alt"></i></button>
							<a href="{{url('admin/invoices')}}" class="btn btn-outline-secondary">Back</a>
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
        $("#liinvoice").addClass("active");
    });


</script>
@endsection
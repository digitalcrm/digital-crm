@extends('layouts.adminlte-boot-4.user')
@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1><?php echo $data->name; ?></h1>
                </div>
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                           <a href="<?php echo url('invoice/email/' . $data['inv_id']); ?>" class="btn btn-primary"><i class="fa fa-mail-forward"></i> Email</a>

                            <a href="<?php echo url('invoice/print/' . $data['inv_id']); ?>" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Print</a> 
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Content Header (Page header) -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800" rel="stylesheet">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            /*margin:50px 0 0 0;*/
            font-size:13px;
        }
        .invoice-box {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05);
            box-shadow: 0 1px 1px rgba(0,0,0,.05);
            display: flow-root;
            width:100%;
            padding:20px;
        }
        .invoice-logo {
            width: 125px;
    height: 75px;
    border: 1px solid #eee;
    object-fit: contain;
        }
        .invoice-title {
            text-transform:uppercase;
            font-size:36px;
            font-weight:800;
            text-align:right;
            color:#e0e0e0;
        }
        .inv-comp-name {
            font-weight:600;
            font-size:16px;
        }
        .bill-to {
            font-weight:700;
            font-size:15px;
        }

        .invoice-table-grid {
            text-align:left;
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
        }

        tr.invoice-details td {
            border:1px solid #ccc;
            padding:5px 10px;
        }

        tr.invoice-details-title th{
            padding:5px 10px;
            border:1px solid #ccc;
            border-width:1px 1px 0 1px;
            background-color:#eee;
        }

        .plan-name {
            text-transform:uppercase;
            font-weight:600;
        }

        .plan-name-ftr {
            text-transform:capitalize;
            font-weight:600;
            color:#808080;
        }

        .inv-ftr {
            color:#808080;
        }

        .totalTd{
            text-align: right;
        }

    </style>
    <!-- Main content -->
    <section class="content mt-2 mx-0">
	<div class="container-fluid">
        <!-- Small boxes (Stat box) -->
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
                    <div class="card-body">
                        <!-- Main content -->

                        <!----------------------------------------------------------------------------------------------->
                        <?php
                        //  Assigning user array to userdetails variable
                        $userdetails = $data['user'];
                        $lead = $data['lead'];
                        ?>
                        <!-- Invoice Body -->
                        <div class="invoice-box">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td>
                                        <div><img src="<?php echo url($data['inv_image']); ?>" class="invoice-logo" width="24" height="72"></div>
                                    </td>
                                    <td><div class="invoice-title">invoice</div></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="inv-comp-name"><?php echo $userdetails['name']; ?></div>
                                        <!--<div>1234 Main Street</div>-->
                                        <div>

                                            <?php
                                            //  Echo User details City and Zip
                                            if (($userdetails['city'] != '') && ($userdetails['zip'] != '')) {
                                                echo $userdetails['city'] . ', ' . $userdetails['zip'];
                                            }
                                            if (($userdetails['city'] != '') && ($userdetails['zip'] == '')) {
                                                echo $userdetails['city'];
                                            }
                                            if (($userdetails['city'] == '') && ($userdetails['zip'] != '')) {
                                                echo $userdetails['zip'];
                                            }
                                            ?>
                                        </div>
                                        <div>
                                            <?php
                                            //  Echo User details Country and State
                                            if (($userdetails['tbl_countries'] != '') && ($userdetails['tbl_states'] != '')) {
                                                echo $userdetails['tbl_countries']['name'] . ', ' . $userdetails['tbl_states']['name'] . '<br>';
                                            }
                                            if (($userdetails['tbl_countries'] != '') && ($userdetails['tbl_states'] == '')) {
                                                echo $userdetails['tbl_countries']['name'] . '<br>';
                                            }
                                            if (($userdetails['tbl_countries'] == '') && ($userdetails['tbl_states'] != '')) {
                                                echo $userdetails['tbl_states']['name'] . '<br>';
                                            }
                                            ?>
                                        </div>
                                        <div><?php echo $userdetails['email']; ?></div>
                                    </td>
                                    <td>
                                        <table width="0" cellpadding="0" cellspacing="7" border="0" align="right">
                                            <tr>
                                                <td>Invoice number</td>
                                                <td>:</td>
                                                <td><?php echo json_encode($data->inv_number); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Invoice date</td>
                                                <td>:</td>
                                                <td><?php echo date('d-m-Y', strtotime($data['created_at'])); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Payment terms</td>
                                                <td>:</td>
                                                <td>Due on receipt</td>
                                            </tr>
                                            <tr>
                                                <td>Due date</td>
                                                <td>:</td>
                                                <td><?php echo date('d-m-Y', strtotime($data['inv_date'])); ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="bill-to">Bill To:</div>
                                        <div><?php echo $lead['email']; ?></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        &nbsp;
                                    </td>
                                </tr>
                            </table>
                            <table width="100%" cellpadding="0" cellspacing="5" border="0"  class="table invoice-table-grid">
                                <tr>
                                    <td>
<!--                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr class="invoice-details-title">
                                                <th>Description</th>
                                                <th width="90">Quantity</th>
                                                <th width="100">Price</th>
                                                <th width="120">Amount</th>
                                            </tr>
                                            <tr class="invoice-details">
                                                <td>
                                                    <div class="plan-name">gold plan</div>
                                                    <div>Resume Search</div>
                                                </td>
                                                <td>1</td>
                                                <td>$499.00</td>
                                                <td>$499.00</td>
                                            </tr>
                                            <tr class="invoice-details">
                                                <td>
                                                    <div class="plan-name">silver</div>
                                                    <div>Job Posting</div>
                                                </td>
                                                <td>1</td>
                                                <td>$499.00</td>
                                                <td>$499.00</td>
                                            </tr>
                                            <tr class="invoice-details">
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>Total</td>
                                                <td><div class="bill-to">$998.00</div></td>
                                            </tr>
                                        </table>-->
                                        <?php echo $data['products']; ?>
                                    </td>
                                </tr>
                            </table>
                            <hr>
                            <table width="100%" cellpadding="0" cellspacing="5" border="0" class="inv-ftr">
                                <tr>
                                    <td>
                                        <div class="plan-name-ftr">notes</div>
                                        <div><?php echo $data->notes; ?></div>
                                    </td>

                                </tr>
                            </table>
                        </div>
                        <!-- End Invoice Body -->
                    </div>
                    <div class="card-footer text-right pull-right">
                        <div class="btn-group btn-flat">
                           <a href="#" class="btn btn-outline-secondary"><i class="far text-danger fa-trash-alt"></i></a>
                           <a href="{{url('invoice')}}" class="btn btn-outline-secondary">Back</a>
                        </div>
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
@extends('layouts.print')
@section('content')
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
        width:650px;
        margin:0 auto;
        padding:20px;
    }
    .invoice-logo {
        width:125px;
        border:1px solid #eee;
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
<?php
//  Assigning user array to userdetails variable
$userdetails = $data['user'];
$lead = $data['lead'];
?>
<!-- title row -->
<div class="row">
    <div class="col-xs-12">
        <h2 class="page-header">
            <?php echo $data->name; ?>
            <!--<small class="pull-right">Date: <?php echo date('d/m/Y'); ?></small>-->
        </h2>
    </div>
    <!-- /.col -->
</div>
<!-- info row -->
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
                        <td>:&nbsp;</td>
                        <td><?php echo json_encode($data->inv_number); ?></td>
                    </tr>
                    <tr>
                        <td>Invoice date</td>
                        <td>:&nbsp;</td>
                        <td><?php echo date('d-m-Y', strtotime($data['created_at'])); ?></td>
                    </tr>
                    <tr>
                        <td>Payment terms</td>
                        <td>:&nbsp;</td>
                        <td>Due on receipt</td>
                    </tr>
                    <tr>
                        <td>Due date</td>
                        <td>:&nbsp;</td>
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

<div class="row">

    <!-- /.col -->
    <div class="col-xs-6 pull-right">
        <p class="lead">Amount Due on<?php echo date('d-m-Y', strtotime($data['inv_date'])); ?></p>

        <div class="table-responsive">
            <?php echo $data['total_table']; ?>
        </div>
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->

@endsection
<html>
    <head>
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
    </head>
    <body>
        <div id="container">
            <!-- Invoice Body -->
            <div class="invoice-box">
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td>
                            <div><img src="{{$inv_image}}" class="invoice-logo" width="24" height="72"></div>
                        </td>
                        <td><div class="invoice-title">invoice</div></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>
                            <div class="inv-comp-name">{{$username}}</div>
                            <div>
                                {{$cityzip}}

                            </div>
                            <div>
                                {{$countrystate}}

                            </div>
                            <div>{{$username}}</div>
                        </td>
                        <td>
                            <table width="0" cellpadding="0" cellspacing="7" border="0" align="right">
                                <tr>
                                    <td>Invoice number</td>
                                    <td>:</td>
                                    <td>{{$inv_number}}</td>
                                </tr>
                                <tr>
                                    <td>Invoice date</td>
                                    <td>:</td>
                                    <td>{{$created_at}}</td>
                                </tr>
                                <tr>
                                    <td>Payment terms</td>
                                    <td>:</td>
                                    <td>Due on receipt</td>
                                </tr>
                                <tr>
                                    <td>Due date</td>
                                    <td>:</td>
                                    <td>{{$inv_date}}</td>
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
                            <div>{{$lead_email}}</div>
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
                            {!!$products_table!!}
                        </td>
                    </tr>
                </table>
                <hr>
                <table width="100%" cellpadding="0" cellspacing="5" border="0" class="inv-ftr">
                    <tr>
                        <td>
                            <div class="plan-name-ftr">notes</div>
                            <div>{{$notes}}</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>
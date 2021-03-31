@extends('layouts.adminlte-boot-4.user')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Sales <small class="badge badge-secondary" id="total">{{$data['total']}}</small></h1>
                </div>
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <!-- <a class="btn btn-sm btn-outline-secondary" href="{{url('sales/import/csv')}}"><i class="fas fa-upload"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('sales/export/csv')}}"><i class="fas fa-download"></i></a> -->
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('reports/sales')}}"><i class="fa fa-chart-pie"></i></a>
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>


    <!-- Main content -->
    <section class="content mx-0">
        <div class="container-fluid">
            <!-- Small cardes (Stat card) -->
            <div class="row">
                <div class="col-lg-12 p-0">
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
                    <div class="card shadow card-primary card-outline">

                        <!-- /.card-header -->

                        <div class="card-header">
                            <div class="row">

                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Payment Status</label>
                                        <select id="paymentStatusFilter" class="form-control" name="paymentStatusFilter">
                                            {!!$data['paymentstatusOptions']!!}
                                        </select>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Closing Date</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="far fa-calendar-alt"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control float-right" id="reservation">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="close_date"><i class="fa fa-window-close"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                            </div>
                        </div>

                        <div class="card-body p-0" id="table">

                            {!!$data['table']!!}

                        </div>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
                <div id="resulttt">

                </div>
            </div>
        </div>
        <!-- /.row -->
</div>
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!--JQuery Datepicker-->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    // var filterUrl = "{{url('sales/filter/{type}/{year}/{month}')}}";

    var filterUrl = "{{url('sales/filter/{start}/{end}/{status}')}}";
    var paystatusChange = "{{url('sales/paystatus/{id}/{status}')}}";

    $(function() {
        $(".sidebar-menu li").removeClass("menu-open");
        $(".sidebar-menu li").removeClass("active");
        $("#lisales").addClass("active");

        $("#datepicker").datepicker({
            "dateFormat": 'dd-mm-yy'
        });

        $('#reservation').daterangepicker({
            locale: {
                format: 'DD-MM-YYYY'
            },
            startDate: moment().subtract(30, 'days'),
            endDate: moment(),
            function(startDate, endDate) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
            }
        });

        $('#reservation').change(function() {
            var startDate = $('#reservation').data('daterangepicker').startDate.format('DD-MM-YYYY');
            var endDate = $('#reservation').data('daterangepicker').endDate.format('DD-MM-YYYY');
            var status = $("#paymentStatusFilter").val();
            // alert(startDate + ' ' + endDate);
            // return false;
            getSalesData(startDate, endDate, status);

        });

        $("#paymentStatusFilter").change(function() {
            var status = $(this).val();
            var startDate = $('#reservation').data('daterangepicker').startDate.format('DD-MM-YYYY');
            var endDate = $('#reservation').data('daterangepicker').endDate.format('DD-MM-YYYY');
            getSalesData(startDate, endDate, status);
        });

        $("#close_date").click(function() {
            var status = $("#paymentStatusFilter").val();
            var startDate = "";
            var endDate = "";
            getSalesData(startDate, endDate, status);
        });

    });


    function getSalesData(startDate, endDate, status) {

        $.get(filterUrl, {
            'start': startDate,
            'end': endDate,
            'status': status,
        }, function(result, status) {

            // alert(result);

            var res = eval("(" + result + ")");
            $("#total").text(res.total);
            $("#table").html(res.table);
            $('#salesTable').DataTable({
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': false,
                'info': true,
                'autoWidth': false,
            });
        });
    }

    function changePayStatus(id, deal) {
        // alert(id + ' ' + deal);

        var status = $("#" + id).val();
        // alert(status);


        $.get(paystatusChange, {
            'id': deal,
            'status': status,
        }, function(result, status) {
            // alert(result);
            if (result) {
                alert('Updated Successfully...!');
            } else {
                alert('Updated Failed. Try again later...!');
            }
        });

    }
</script>
@endsection
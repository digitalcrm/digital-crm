@extends('layouts.adminlte-boot-4.user')
@section('content')
<style>
    table td {
        word-wrap: break-word;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Product Leads <small class="badge badge-secondary" id="totalBadge">{{$data['total']}}</small></h1>
                </div>
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">

                            <a class="btn btn-sm btn-default mr-1" href="{{url('leads')}}"><i class=""></i> Leads</a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('reports/productleads')}}"><i class="fa fa-chart-pie"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('leads/pro/import/csv')}}"><i class="fas fa-upload"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('leads/pro/export/csv')}}"><i class="fas fa-download"></i></a>
                            <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#exampleModal2">
                                <i class="fa fa-filter"></i>
                            </button>

                            <!-- <a class="btn btn-sm btn-primary mr-1" href="{{url('leads/create')}}"><i class="far fa-plus-square mr-1"></i> New Lead</a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('leads/import/csv')}}"><i class="fas fa-upload"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('leads/export/csv')}}"><i class="fas fa-download"></i></a>

                            <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#exampleModal2">
                                <i class="fa fa-filter"></i>
                            </button> -->
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small cardes (Stat card) -->
            <div class="row">
                <div class="col-lg-12 p-0">
                    <x-alert />
                    <div class="card shadow card-primary card-outline">
                        <div class="card-header">
                        </div>

                        <div class="card-body p-0" id="leadsTableDiv">

                            {!!$data['table']!!}

                        </div>
                        <div class="card-footer bg-white border-top pull-left">
                            <div class="btn-group btn-flat pull-left">
                                <button class="btn btn-sm btn-outline-secondary" id="selectAll" onclick="table_row_color_change();"><i class="fas fa-check"></i> Select All</button>
                                <button class="btn btn-sm text-danger btn-outline-secondary" onclick="return deleteAll();"><i class="far fa-trash-alt"></i></button>
                            </div>
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
<!-- Modal -->
<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-slideout" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Search Filter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Launch demo modal sideout normal -->
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label>Products</label>
                            <select name="products" id="productsfilter" class="form-control">
                                {!!$data['productOptions']!!}
                            </select>
                        </div>
                    </div>

                    <div class="col-12">
                        <!-- Date and time range -->
                        <div class="form-group">
                            <label>Creation Date</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-default float-right" id="reportrange">
                                    <i class="far fa-calendar-alt"></i>&nbsp;<span>Select Date</span>
                                    <i class="fas fa-caret-down"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="applyFilter">Apply</button>
            </div>
        </div>
    </div>
</div>

<script>
    var oldDate = '<?php echo $data['oldDate']; ?>';
    var latestDate = '<?php echo $data['latestDate']; ?>';
    var proleadsfilterUrl = "{{url('leads/proleadsfilter/{id}/{start}/{end}')}}"; //day
    var deleteAllUrl = "{{url('leads/product/deleteAll/{id}')}}";

    $(function() {
        //Date range as a button
        $('#reportrange').daterangepicker({
                ranges: {
                    'All': [moment(oldDate), moment(latestDate)],
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment(oldDate), //moment().subtract(29, 'days'),
                endDate: moment(latestDate), // moment()
            },
            function(start, end) {
                // $('#reportrange span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'))
            }
        );

        $("#selectAll").click(function() {
            var checked = ($(".checkAll").prop('checked') == false) ? true : false;
            $(".checkAll").prop('checked', checked);
        });

        $("#applyFilter").click(function() {
            // alert(close_create_date);

            var status = $("#productsfilter").val();
            var rstartDate = $('#reportrange').data('daterangepicker').startDate.format('DD-MM-YYYY');
            var rendDate = $('#reportrange').data('daterangepicker').endDate.format('DD-MM-YYYY');
            // alert('rstartDate ' + rstartDate + ' rendDate ' + rendDate);

            getLeads(status, rstartDate, rendDate);

        });
    });

    function deleteAll() {
        var deleteIdlength = $('.checkAll:checked').length;
        if (deleteIdlength > 0) {
            var checkList = $('.checkAll:checked');
            var itemIds = [];
            $(checkList).each(function(index) {
                itemIds[index] = $(checkList).get(index).id;
            });

            // alert(itemIds);

            $.get(deleteAllUrl, {
                'id': itemIds
            }, function(result, status) {

                // alert(result);

                if (result > 0) {
                    alert('Deleted successfully...');
                } else {
                    alert('Failed. Try again...');
                }
                location.reload();
            });
        } else {
            alert('Please select...');
        }
    }

    function getLeads(status, startDate, endDate) {


        // alert(status + " " + startDate + " " + endDate);

        $.get(proleadsfilterUrl, {
            'id': status,
            'start': startDate,
            'end': endDate,

        }, function(result, status) {

            // alert(result);

            var res = eval('(' + result + ')');

            $("#totalBadge").text(res.total);
            $("#leadStatusVal").text(res.leadStatusVal);
            $("#timer").text(res.timer);
            $("#leadsTableDiv").html(res.table);

            $('#leadsTable').DataTable({
                'paging': false,
                'lengthChange': true,
                'searching': true,
                'ordering': false,
                'info': false,
                'autoWidth': false,
                'responsive': true,
            });
            $("#exampleModal2").modal('hide');
        });

    }
</script>
@endsection

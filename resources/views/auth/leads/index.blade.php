@extends('layouts.adminlte-boot-4.user')
@section('content')

<style type="text/css">
    .clsDatePicker {
        z-index: 100000;
    }

    .modal-dialog-slideout {
        min-height: 100%;
        margin: 0 0 0 auto;
        background: #fff;
    }

    .modal.fade .modal-dialog.modal-dialog-slideout {
        -webkit-transform: translate(100%, 0)scale(1);
        transform: translate(100%, 0)scale(1);
    }

    .modal.fade.show .modal-dialog.modal-dialog-slideout {
        -webkit-transform: translate(0, 0);
        transform: translate(0, 0);
        display: flex;
        align-items: stretch;
        -webkit-box-align: stretch;
        height: 100%;
    }

    .modal.fade.show .modal-dialog.modal-dialog-slideout .modal-body {
        overflow-y: auto;
        overflow-x: hidden;
    }

    .modal-dialog-slideout .modal-content {
        border: 0;
    }

    .modal-dialog-slideout .modal-header,
    .modal-dialog-slideout .modal-footer {
        height: 69px;
        display: block;
    }

    .modal-dialog-slideout .modal-header h5 {
        float: left;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Leads <small class="badge badge-secondary" id="totalBadge">{{$data['total']}}</small></h1>
                </div>
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a class="btn btn-sm btn-primary mr-1" href="{{url('leads/create')}}"><i class="far fa-plus-square mr-1"></i> New Lead</a>
                            <a class="btn btn-sm btn-default mr-1" href="{{url('leads/getproductleads/list')}}"><i class=""></i>Product Leads</a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('leads/import/csv')}}"><i class="fas fa-upload"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('leads/export/csv')}}"><i class="fas fa-download"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('reports/leads')}}"><i class="fa fa-chart-pie"></i></a>
                            <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#exampleModal2">
                                <i class="fa fa-filter"></i>
                            </button>
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
                    @if(session('success'))
                    <div class='alert alert-success'>
                        {{session('success')}}
                    </div>
                    @endif

                    @if(session('error'))
                    <div class='alert alert-danger'>
                        {{session('error')}}
                    </div>
                    @endif

                    @if(session('info'))
                    <div class='alert alert-warning'>
                        {{session('info')}}
                    </div>
                    @endif
                    <div class="card shadow card-primary card-outline">
                        <div class="card-header">
                            <h5>
                                <label class="badge badge-secondary">
                                    Lead Status : <span id="leadStatusVal">{!!$data['leadStatusVal']!!}</span>
                                </label>
                                <label class="badge badge-secondary">
                                    Creation Date : <span id="timer">{!!$data['timer']!!}</span>
                                </label>
                            </h5>
                        </div>
                        <!--/.card-header-->

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
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<div class="modal fade" id="modal-event">
    <div class="modal-dialog" style="width: 55%;">
        {{Form::open(['action'=>'LeadController@storeEvent','method'=>'Post','enctype'=>'multipart/form-data'])}}
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Create Event</h4>
            </div>
            <div class="modal-body">
                @csrf
                <div class="card-body p-0">
                    <input type="hidden" name="leadId" id="leadId">
                    <input type="hidden" name="leadStatus" id="leadStatus">
                    <section class="col-lg-6">
                        <div class="form-group">
                            <label for="name">Title</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                            <input type="text" class="form-control" name="title" id="title" placeholder="" value="{{old('title')}}" required>
                            <span class="text-danger">{{ $errors->first('title') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="name">Start Date/ Time</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                            <div class="input-group my-group">
                                <input type="text" class="form-control" name="startDate" id="startDate" placeholder="" value="{{old('startDate')}}" required>
                                <span class="text-danger">{{ $errors->first('startDate') }}</span>
                                <input type="text" class="form-control timepicker" id="startTime" name="startTime" value="{{old('startTime')}}" required>
                                <span class="text-danger">{{ $errors->first('startTime') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name">End Date</label>
                            <div class="input-group my-group">
                                <input type="text" class="form-control" name="endDate" id="endDate" placeholder="" value="{{old('endDate')}}">
                                <span class="text-danger">{{ $errors->first('endDate') }}</span>
                                <input type="text" class="form-control timepicker" id="endTime" name="endTime" value="{{old('endTime')}}">
                                <span class="text-danger">{{ $errors->first('endTime') }}</span>
                            </div>
                        </div>
                    </section>
                    <section class="col-lg-6">
                        <div class="form-group">
                            <label for="name">Description</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                            <textarea class="form-control" name="description" id="description" required rows="7">{{old('description')}}</textarea>
                            <span class="text-danger">{{ $errors->first('description') }}</span>
                        </div>
                    </section>
                </div>
                <!-- /.card-body -->
            </div>
            <div class="modal-footer">
                <div class="btn btn-group pull-right">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    {{Form::submit('Save',['class'=>"btn btn-primary"])}}
                </div>
            </div>
        </div>
        {{Form::close()}}
        <!-- </form> -->
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
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
                            <label>Lead Status</label>
                            <select name="leadStatusfilter" id="leadStatusfilter" class="form-control">
                                {!!$data['leadstatusSelect']!!}
                            </select>
                        </div>
                    </div>

                    <!-- <div class="col-12">
                        <div class="form-group">
                            <label>Created At</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control float-right" id="reservation" style="z-index: 10510">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="close_create_date"><i class="fa fa-window-close"></i></span>
                                </div>
                            </div>
                        </div>
                    </div> -->

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
    var deleteAllUrl = "{{url('leads/deleteAll/{id}')}}";
    var leadStatusfilterUrl = "{{url('leads/leadStatusfilter/{id}/{start}/{end}')}}"; //day
    var changeleadstatusUrl = "{{url('leads/changeleadstatus/{id}/{status}/{deal}')}}";

    var oldDate = '<?php echo $data['oldDate']; ?>';
    var latestDate = '<?php echo $data['latestDate']; ?>';
    $(function() {

        $("#selectAll").click(function() {
            var checked = ($(".checkAll").prop('checked') == false) ? true : false;
            $(".checkAll").prop('checked', checked);
        });
        // alert(oldDate + ' ' + latestDate);

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

        // var close_create_date = 1;
        // $("#close_create_date").click(function() {
        //     var startDate = '';
        //     var endDate = '';
        //     var status = $("#leadStatusfilter").val();
        //     close_create_date = 1;
        //     // getLeads(status, startDate, endDate);

        // });

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
            close_create_date = 0;
            var startDate = $('#reservation').data('daterangepicker').startDate.format('DD-MM-YYYY');
            var endDate = $('#reservation').data('daterangepicker').endDate.format('DD-MM-YYYY');
            var status = $("#leadStatusfilter").val();

            // alert(startDate + ' ' + endDate+ ' ' + status);
            // getLeads(status, startDate, endDate);
        });

        $("#leadStatusfilter").change(function() {
            var status = $(this).val();
            var startDate = $('#reservation').data('daterangepicker').startDate.format('DD-MM-YYYY');
            var endDate = $('#reservation').data('daterangepicker').endDate.format('DD-MM-YYYY');

            // getLeads(status, startDate, endDate);

        });

        $("#applyFilter").click(function() {
            // alert(close_create_date);

            var status = $("#leadStatusfilter").val();
            var rstartDate = $('#reportrange').data('daterangepicker').startDate.format('DD-MM-YYYY');
            var rendDate = $('#reportrange').data('daterangepicker').endDate.format('DD-MM-YYYY');
            // alert('rstartDate ' + rstartDate + ' rendDate ' + rendDate);


            getLeads(status, rstartDate, rendDate);

            // if (close_create_date == 0) {
            //     var startDate = $('#reservation').data('daterangepicker').startDate.format('DD-MM-YYYY');
            //     var endDate = $('#reservation').data('daterangepicker').endDate.format('DD-MM-YYYY');
            //     var status = $("#leadStatusfilter").val();
            // } else {
            //     var startDate = '';
            //     var endDate = '';
            //     var status = $("#leadStatusfilter").val();
            // }
            // getLeads(status, startDate, endDate);

        });

    });

    function getLeads(status, startDate, endDate) {
        $.get(leadStatusfilterUrl, {
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
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': false,
                'info': true,
                'autoWidth': false,
                'responsive': true,
                //                    'columnDefs': [
                //                        {type: 'date-uk', targets: 4}
                //                    ]
            });
            $("#exampleModal2").modal('hide');
        });

    }

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

    function changeLeadStatus(id, ld) {
        // alert(id + " " + ld);
        //        alert($("#" + id).val() + " " + ld);

        var statusEvent = $("#" + id).val();
        var statusEventSplit = statusEvent.split("|");
        var status = statusEventSplit[0];
        var event = statusEventSplit[1];

        // alert(statusEvent + " " + status + " " + event);


        $.get(changeleadstatusUrl, {
            'id': ld,
            'status': status,
            'deal': event
        }, function(result, status) {
            // alert(result);
            if (result > 0) {
                alert('Updated successfully...');
            } else {
                alert('Failed. Try again...');
            }
        });

    }
</script>
@endsection

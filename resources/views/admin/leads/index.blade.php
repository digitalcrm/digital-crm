@extends('layouts.adminlte-boot-4.admin')
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
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-4">
                    <h1 class="m-0 text-dark">Leads <span class="badge badge-secondary" id="total">{{$data['total']}}</span></h1>
                </div>
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModal2">
                                <i class="fa fa-filter"></i>
                            </button>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('admin/leads/import/csv')}}"><i class="fas fa-upload"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('admin/leads/export/csv')}}"><i class="fas fa-download"></i></a>
                        </li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>

    <!-- Main content -->
    <section class="content mt-2 mx-0">
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
                        <div class="card-header">
                            <h5>
                                <label class="badge badge-secondary">
                                    User : <span id="userVal">{!!$data['userVal']!!}</span>
                                </label>
                                <label class="badge badge-secondary">
                                    Lead Status : <span id="leadStatusVal">{!!$data['leadStatusVal']!!}</span>
                                </label>
                                <label class="badge badge-secondary">
                                    Creation Date : <span id="timer">{!!$data['timer']!!}</span>
                                </label>
                            </h5>
                        </div>
                        <div class="card-body p-0" id="table">
                            {!!$data['table']!!}
                        </div>

                        <div class="card-footer bg-white border-top pull-left">
                            <div class="btn-group btn-flat pull-left">
                                <button class="btn btn-sm btn-outline-secondary" id="selectAll"><i class="fas fa-check"></i> Select All</button>
                                <button class="btn text-danger btn-sm btn-outline-secondary" onclick="return deleteAll();" href="#"><i class="far fa-trash-alt"></i></button>
                            </div>
                        </div>

                        <!-- /.card-body -->
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
                <div class="row ">
                    <div class="col-12">
                        <div class="form-group">
                            <label>Users</label>
                            <select class="form-control" id="selectUser" name="selectUser">
                                {!!$data['useroptions']!!}
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>Lead Status</label>
                            <select name="selectLeadStatus" id="selectLeadStatus" class="form-control">
                                {!!$data['leadstatusSelect']!!}
                            </select>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label>Time</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-default float-right" id="reportrange">
                                    <i class="far fa-calendar-alt"></i>&nbsp;<span>Date range picker</span>
                                    <i class="fas fa-caret-down"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- /.form group -->
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
    var getleadsofuser = "{{url('admin/getleadsofuser/{id}/{status}/{start}/{end}')}}";
    var deleteAllUrl = "{{url('admin/leads/deleteAll/{id}')}}";

    var oldDate = '<?php echo $data['oldDate']; ?>';
    var latestDate = '<?php echo $data['latestDate']; ?>';

    $(function() {

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


        // $("#close_create_date").click(function() {
        //     var startDate = '';
        //     var endDate = '';
        //     var status = $("#selectLeadStatus").val();
        //     var uid = $("#selectUser").val();
        //     // getLeads(uid, status, startDate, endDate);
        // });

        // $('#reservation').daterangepicker({
        //     locale: {
        //         format: 'DD-MM-YYYY'
        //     },
        //     startDate: moment().subtract(30, 'days'),
        //     endDate: moment(),
        //     function(startDate, endDate) {
        //         $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
        //     }
        // });

        // $("#selectUser").change(function() {
        //     var uid = $(this).val();
        //     var status = $("#selectLeadStatus").val();
        //     var startDate = $('#reservation').data('daterangepicker').startDate.format('DD-MM-YYYY');
        //     var endDate = $('#reservation').data('daterangepicker').endDate.format('DD-MM-YYYY');
        //     // getLeads(uid, status, startDate, endDate);
        // });


        // $("#selectLeadStatus").change(function() {
        //     var status = $(this).val();
        //     var uid = $("#selectUser").val();
        //     var startDate = $('#reservation').data('daterangepicker').startDate.format('DD-MM-YYYY');
        //     var endDate = $('#reservation').data('daterangepicker').endDate.format('DD-MM-YYYY');
        //     // getLeads(uid, status, startDate, endDate);
        // });



        // $('#reservation').change(function() {
        //     var startDate = $('#reservation').data('daterangepicker').startDate.format('DD-MM-YYYY');
        //     var endDate = $('#reservation').data('daterangepicker').endDate.format('DD-MM-YYYY');
        //     var status = $("#selectLeadStatus").val();
        //     var uid = $("#selectUser").val();
        //     // getLeads(uid, status, startDate, endDate);
        //     // alert(startDate + ' ' + endDate);
        //     // return false;

        // });

        $("#selectAll").click(function() {
            $(".checkAll").prop('checked', $(this).prop('checked'));
        });

        $("#applyFilter").click(function() {
            var startDate = $('#reportrange').data('daterangepicker').startDate.format('DD-MM-YYYY');
            var endDate = $('#reportrange').data('daterangepicker').endDate.format('DD-MM-YYYY');
            var status = $("#selectLeadStatus").val();
            var uid = $("#selectUser").val();

            // alert(uid + ' ' + status + ' ' + startDate + ' ' + endDate);

            getLeads(uid, status, startDate, endDate);
        });


    });

    function getLeads(uid, status, startDate, endDate) {

        // alert(uid + ' ' + status + ' ' + startDate + ' ' + endDate);

        $.get(getleadsofuser, {
            'id': uid,
            'status': status,
            'start': startDate,
            'end': endDate,
        }, function(result, status) {

            // alert(result);

            var res = eval('(' + result + ')');

            $("#total").text(res.total);
            $("#leadStatusVal").text(res.leadStatusVal);
            $("#timer").text(res.timer);
            $("#userVal").text(res.userVal);
            $("#table").html(res.table);

            $('#leadsTable').DataTable({
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': false,
                'info': true,
                'autoWidth': false,
                'responsive': true
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

            //            alert(itemIds);

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
</script>
@endsection
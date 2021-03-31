@extends('layouts.adminlte-boot-4.admin')
@section('content')

<style type="text/css">
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
                    <h1 class="m-0 text-dark">Deals <small class="badge badge-secondary" id="total">{{$data['total']}}</small></h1>
                </div>
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModal2">
                                <i class="fa fa-filter"></i>
                            </button>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('admin/deals/import/csv')}}"><i class="fas fa-upload"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('admin/deals/export/csv')}}"><i class="fas fa-download"></i></a>
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
                                    Deal Stage : <span id="dealstageVal">{!!$data['dealstageVal']!!}</span>
                                </label>
                                <label class="badge badge-secondary">
                                    Closing Date : <span id="timer">{!!$data['timer']!!}</span>
                                </label>
                            </h5>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0" id="table">
                            {!!$data['table']!!}
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white border-top">
                            <div class="btn-group btn-flat float-right">
                                <button type="button" value="Delete" class="btn text-danger btn-outline-secondary" onclick="return deleteAll();"><i class="far fa-trash-alt"></i></button>
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
                    <div class="col-6">
                        <div class="form-group">
                            <label>User</label>
                            <select class="form-control" id="selectUser" name="selectUser">
                                {!!$data['useroptions']!!}
                            </select>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <label>Deal Stage</label>
                            <select class="form-control" id="dealStageFilter">
                                {!!$data['dealstageoptions']!!}
                            </select>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <label>Closing Date</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-default float-right" id="datepicker">
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
    var getUserDeals = "{{url('admin/ajax/getUserDeals/{uid}/{stage}/{start}/{end}')}}";
    var deleteAllUrl = "{{url('admin/deals/deleteAll/{id}')}}";

    var oDateClose = '<?php echo $data['closeDate']['oDateClose']; ?>';
    var lDateClose = '<?php echo $data['closeDate']['lDateClose']; ?>';

    $(function() {

        $(".sidebar-menu li").removeClass("active");
        $("#lideals").addClass("active");

        $("#selectAll").click(function() {
            $(".checkAll").prop('checked', $(this).prop('checked'));
        });

        $('#datepicker').daterangepicker({
                ranges: {
                    'All': [moment(oDateClose), moment(lDateClose)],
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment(oDateClose), //moment().subtract(29, 'days'),
                endDate: moment(lDateClose), // moment()
            },
            function(start, end) {
                // $('#datepicker span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'))
            }
        );

        // $("#close_reservation").click(function() {
        //     // alert("close_reservation");
        //     // $("#reservation").val("");
        //     var uid = $("#selectUser").val();
        //     var stage = $("#dealStageFilter").val();
        //     // alert(filter);

        //     getDeals(uid, stage, '', '');

        //     $('#reservation').daterangepicker({
        //         locale: {
        //             format: 'DD-MM-YYYY'
        //         },
        //         startDate: moment().subtract(30, 'days'),
        //         endDate: moment(),
        //         function(startDate, endDate) {
        //             $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
        //         }
        //     });

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
        //     var stage = $("#dealStageFilter").val();
        //     var startDate = $('#reservation').data('daterangepicker').startDate.format('DD-MM-YYYY');
        //     var endDate = $('#reservation').data('daterangepicker').endDate.format('DD-MM-YYYY');
        //     getDeals(uid, stage, startDate, endDate);
        //     // $.get(getUserDeals, {
        //     //     'uid': uid,
        //     //     'stage': stage,
        //     //     'start': startDate,
        //     //     'end': endDate,

        //     // }, function(result) {
        //     //     var res = eval("(" + result + ")");
        //     //     $("#total").text(res.total);
        //     //     $("#table").html(res.table);

        //     //     $('#dealsTable').DataTable({
        //     //         'paging': true,
        //     //         'lengthChange': true,
        //     //         'searching': true,
        //     //         'ordering': false,
        //     //         'info': true,
        //     //         'autoWidth': false
        //     //     });

        //     // });
        // });

        // $("#dealStageFilter").change(function() {
        //     var uid = $("#selectUser").val();
        //     var stage = $(this).val();
        //     var startDate = $('#reservation').data('daterangepicker').startDate.format('DD-MM-YYYY');
        //     var endDate = $('#reservation').data('daterangepicker').endDate.format('DD-MM-YYYY');
        //     getDeals(uid, stage, startDate, endDate);
        //     // $.get(getUserDeals, {
        //     //     'uid': uid,
        //     //     'stage': stage,
        //     //     'start': startDate,
        //     //     'end': endDate,

        //     // }, function(result) {
        //     //     var res = eval("(" + result + ")");
        //     //     $("#total").text(res.total);
        //     //     $("#table").html(res.table);

        //     //     $('#dealsTable').DataTable({
        //     //         'paging': true,
        //     //         'lengthChange': true,
        //     //         'searching': true,
        //     //         'ordering': false,
        //     //         'info': true,
        //     //         'autoWidth': false
        //     //     });

        //     // });
        // });


        // $('#reservation').change(function() {
        //     var uid = $("#selectUser").val();
        //     var startDate = $('#reservation').data('daterangepicker').startDate.format('DD-MM-YYYY');
        //     var endDate = $('#reservation').data('daterangepicker').endDate.format('DD-MM-YYYY');
        //     var stage = $("#dealStageFilter").val();
        //     getDeals(uid, stage, startDate, endDate);
        //     // alert(startDate + ' ' + endDate);
        //     // return false;

        // });

        $("#applyFilter").click(function() {
            var uid = $("#selectUser").val();
            var startDate = $('#datepicker').data('daterangepicker').startDate.format('DD-MM-YYYY');
            var endDate = $('#datepicker').data('daterangepicker').endDate.format('DD-MM-YYYY');
            var stage = $("#dealStageFilter").val();
            getDeals(uid, stage, startDate, endDate);
        });

    });

    function getDeals(uid, stage, startDate, endDate) {

        $.get(getUserDeals, {
            'uid': uid,
            'stage': stage,
            'start': startDate,
            'end': endDate,
        }, function(result, status) {

            // alert(result);
            var res = eval("(" + result + ")");
            $("#total").text(res.total);
            $("#userVal").text(res.userVal);
            $("#dealstageVal").text(res.dealstageVal);
            $("#timer").text(res.timer);
            $("#table").html(res.table);

            $('#dealsTable').DataTable({
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': false,
                'info': true,
                'autoWidth': false
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
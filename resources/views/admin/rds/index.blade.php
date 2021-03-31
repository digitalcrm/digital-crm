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
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Rd <small class="badge badge-secondary" id="total">{{$data['total']}}</small></h1>
                </div>
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a class="btn btn-sm btn-primary mr-1" href="{{url('admin/rds/create')}}"><i class="far fa-plus-square mr-1"></i> New RD</a>
                            <!-- <a class="btn btn-sm btn-outline-secondary" href="{{url('accounts/import/csv')}}"><i class="fas fa-upload"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('accounts/export/csv')}}"><i class="fas fa-download"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('reports/accounts')}}"><i class="fa fa-chart-pie"></i></a> -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal2">
                                <i class="fa fa-filter"></i>
                            </button>
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content mt-2 mx-0">
        <div class="container-fluid">
            <!-- Small cardes (Stat card) -->
            <div class="row">
                <div class="col-lg-12 p-0">
                    @if(session('success'))
                    <div id="alertSuccess" class='alert alert-success'>
                        {{session('success')}}
                    </div>
                    @endif

                    @if(session('error'))
                    <div id="alertError" class='alert alert-danger'>
                        {{session('error')}}
                    </div>
                    @endif

                    @if(session('info'))
                    <div id="alertWarning" class='alert alert-warning'>
                        {{session('info')}}
                    </div>
                    @endif

                    <div class="card shadow card-primary card-outline">
                        <div class="card-header">
                            <h5>
                                <label class="badge badge-secondary">
                                    User : <span id="userVal">{!!$data['userVal']!!}</span>
                                </label>
                                <label class="badge badge-secondary">
                                    Creation Date : <span id="CreationDateVal">{!!$data['CreationDateVal']!!}</span>
                                </label>
                                <label class="badge badge-secondary">
                                    Submission Date : <span id="SubmitDateVal">{!!$data['SubmitDateVal']!!}</span>
                                </label>
                                <label class="badge badge-secondary">
                                    Uploaded Date : <span id="UploadDateVal">{!!$data['UploadDateVal']!!}</span>
                                </label>
                                <label class="badge badge-secondary">
                                    Rd Types : <span id="typeRd">{!!$data['typeRd']!!}</span>
                                </label>
                                <label class="badge badge-secondary">
                                    Priority : <span id="priorityVal">{!!$data['priorityVal']!!}</span>
                                </label>
                                <label class="badge badge-secondary">
                                    Product : <span id="productName">{!!$data['productName']!!}</span>
                                </label>
                                <label class="badge badge-secondary">
                                    Product Category: <span id="productCategory">{!!$data['productCategory']!!}</span>
                                </label>
                                <label class="badge badge-secondary">
                                    Status: <span id="statusName">{!!$data['statusName']!!}</span>
                                </label>
                            </h5>
                        </div>
                        <div class="card-body p-0" id="table">
                            {!!$data['table']!!}
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white border-top">
                            <!-- <div class="btn-group btn-flat pull-left">
                                <button class="btn btn-sm btn-outline-secondary" id="selectAll" onclick="table_row_color_change();"><i class="fas fa-check"></i> Select All</button>
                                <button class="btn btn-sm text-danger btn-outline-secondary" onclick="return deleteAll();"><i class="far fa-trash-alt"></i></button>
                            </div> -->
                        </div>
                        <!-- /.card-footer -->
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
                            <label for="createdate">Creation Date</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-default float-right" id="datepicker">
                                    <i class="far fa-calendar-alt"></i>&nbsp;<span>Select Date</span>
                                    <i class="fas fa-caret-down"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="submitdate">Submission Date</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-default float-right" id="datepicker2">
                                    <i class="far fa-calendar-alt"></i>&nbsp;<span>Select Date</span>
                                    <i class="fas fa-caret-down"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="uploadeddate">Uploaded Date</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-default float-right" id="datepicker3">
                                    <i class="far fa-calendar-alt"></i>&nbsp;<span>Select Date</span>
                                    <i class="fas fa-caret-down"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label>Users</label>
                            <!-- onchange="return getRds();" -->
                            <select class="form-control" id="selectUser" name="selectUser">
                                {!!$data['useroptions']!!}
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>Rd Types</label>
                            <!-- onchange="return getRds();" -->
                            <select class="form-control" id="selectRdtype" name="selectRdtype">
                                {!!$data['rdtypeoptions']!!}
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>Priority</label>
                            <!-- onchange="return getRds();" -->
                            <select class="form-control" id="rdpr_id" name="rdpr_id">
                                {!!$data['rdprtypeoptions']!!}
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>Product</label>
                            <!-- onchange="return getRds();" -->
                            <select class="form-control" id="pro_id" name="pro_id">
                                {!!$data['productoptions']!!}
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>Product Category</label>
                            <!-- onchange="return getRds();" -->
                            <select class="form-control" id="procat_id" name="procat_id">
                                {!!$data['categoryoptions']!!}
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>Status</label>
                            <!-- onchange="return getRds();" -->
                            <select class="form-control" id="status" name="status">
                                <option value='All'>All</option>
                                <option value='1'>Yes</option>
                                <option value='2'>No</option>
                            </select>
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

<!--JQuery Datepicker-->
<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
<script>
    var deleteAllUrl = "{{url('accounts/deleteAll/{id}')}}";
    var ajaxchangerdstatus = "{{url('admin/rds/ajaxchangerdstatus/{id}/{status}')}}";
    var ajaxgetrdlist = "{{url('admin/rds/ajaxgetrdlist/{id}/{type}/{user_type}/{intype_id}/{rdpr_id}/{pro_id}/{status}/{create_date_start}/{create_date_end}/{submit_date_start}/{submit_date_end}/{upload_date_start}/{upload_date_end}/{procat_id}')}}";

    var oDateCreate = '<?php echo $data['createDate']['oDateCreate']; ?>';
    var lDateCreate = '<?php echo $data['createDate']['lDateCreate']; ?>';
    var oDateSubmit = '<?php echo $data['submitDate']['oDateSubmit']; ?>';
    var lDateSubmit = '<?php echo $data['submitDate']['lDateSubmit']; ?>';
    var oDateUpload = '<?php echo $data['uploadDate']['oDateUpload']; ?>';
    var lDateUpload = '<?php echo $data['uploadDate']['lDateUpload']; ?>';

    $(function() {
        // $("#datepicker").datepicker({
        //     "dateFormat": 'dd-mm-yy'
        // });

        // $("#datepicker2").datepicker({
        //     "dateFormat": 'dd-mm-yy'
        // });

        // $("#datepicker3").datepicker({
        //     "dateFormat": 'dd-mm-yy'
        // });

        // alert('active');
        // $(".sidebar-menu li").removeClass("active");
        // $("#liaccounts").addClass("active");

        $("#selectAll").click(function() {
            // $(".checkAll").prop('checked', $(this).prop('checked'));
            var checked = ($(".checkAll").prop('checked') == false) ? true : false;
            $(".checkAll").prop('checked', checked);
        });

        // $("#datepicker").change(function() {
        //     getRds();
        // });

        // $("#datepicker2").change(function() {
        //     getRds();
        // });

        // $("#datepicker3").change(function() {
        //     getRds();
        // });

        // $("#close_create_date").click(function() {
        //     // alert("close_create_date");
        //     $("#datepicker").val("");
        //     getRds();
        // });

        // $("#close_submit_date").click(function() {
        //     // alert("close_submit_date");
        //     $("#datepicker2").val("");
        //     getRds();
        // });

        // $("#close_upload_date").click(function() {
        //     // alert("close_upload_date");
        //     $("#datepicker3").val("");
        //     getRds();
        // });

        $('#datepicker').daterangepicker({
                ranges: {
                    'All': [moment(oDateCreate), moment(lDateCreate)],
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment(oDateCreate), //moment().subtract(29, 'days'),
                endDate: moment(lDateCreate), // moment()
            },
            function(start, end) {
                // $('#datepicker span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'))
            }
        );

        $('#datepicker2').daterangepicker({
                ranges: {
                    'All': [moment(oDateSubmit), moment(lDateSubmit)],
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment(oDateSubmit), //moment().subtract(29, 'days'),
                endDate: moment(lDateSubmit), // moment()
            },
            function(start, end) {
                // $('#datepicker span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'))
            }
        );

        $('#datepicker3').daterangepicker({
                ranges: {
                    'All': [moment(oDateUpload), moment(lDateUpload)],
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment(oDateUpload), //moment().subtract(29, 'days'),
                endDate: moment(lDateUpload), // moment()
            },
            function(start, end) {
                // $('#datepicker span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'))
            }
        );


        $("#applyFilter").click(function() {
            getRds();
        });
    });

    function ChangeStatus(id, rd_id) {
        // alert(id +" "+rd_id);

        var statusVal = $("#" + id).val();
        // alert(statusVal);

        $.get(ajaxchangerdstatus, {
            'id': rd_id,
            'status': statusVal,
        }, function(result, status) {
            if (result > 0) {
                alert('Updated successfully...');
            } else {
                alert('Failed. Try again...');
            }
            // location.reload();
        });
    }


    function getRds() {
        var uid = $("#selectUser").val();
        var type = $("#selectRdtype").val();
        var user = uid.split("|");
        //var intype_id = $("#intype_id").val();
        var rdpr_id = $("#rdpr_id").val();
        var pro_id = $("#pro_id").val();
        var status = $("#status").val();
        var procat_id = $("#procat_id").val();
        // var create_date = $("#datepicker").val();
        // var submit_date = $("#datepicker2").val();
        // var upload_date = $("#datepicker3").val();

        var create_date_start = $('#datepicker').data('daterangepicker').startDate.format('DD-MM-YYYY');
        var create_date_end = $('#datepicker').data('daterangepicker').endDate.format('DD-MM-YYYY');

        // alert(create_date_start + ' ' + create_date_end);

        var submit_date_start = $('#datepicker2').data('daterangepicker').startDate.format('DD-MM-YYYY');
        var submit_date_end = $('#datepicker2').data('daterangepicker').endDate.format('DD-MM-YYYY');

        // alert(submit_date_start + ' ' + submit_date_end);

        var upload_date_start = $('#datepicker3').data('daterangepicker').startDate.format('DD-MM-YYYY');
        var upload_date_end = $('#datepicker3').data('daterangepicker').endDate.format('DD-MM-YYYY');

        // alert(upload_date_start + ' ' + upload_date_end);

        // alert(type + ' ' + rdpr_id + ' ' + pro_id + ' ' + status + ' ' + procat_id);
        // return false;
        // alert(ajaxgetrdlist);
        // 'intype_id': intype_id,
        $.get(ajaxgetrdlist, {
            'id': user[0],
            'type': type,
            'user_type': user[1],

            'rdpr_id': rdpr_id,
            'pro_id': pro_id,
            'status': status,
            // 'create_date': create_date,
            // 'submit_date': submit_date,
            // 'upload_date': upload_date,
            'create_date_start': create_date_start,
            'create_date_end': create_date_end,
            'submit_date_start': submit_date_start,
            'submit_date_end': submit_date_end,
            'upload_date_start': upload_date_start,
            'upload_date_end': upload_date_end,
            'procat_id': procat_id,
        }, function(result, status) {
            // alert(result);

            var res = eval("(" + result + ")");
            // alert(res.total);
            // alert(res.table);
            $("#total").text(res.total);
            $("#CreationDateVal").text(res.CreationDateVal);
            $("#totaSubmitDateVall").text(res.SubmitDateVal);
            $("#UploadDateVal").text(res.UploadDateVal);
            $("#typeRd").text(res.typeRd);
            $("#priorityVal").text(res.priorityVal);
            $("#productName").text(res.productName);
            $("#productCategory").text(res.productCategory);
            $("#statusName").text(res.statusName);
            $("#userVal").text(res.userVal);
            $("#table").html(res.table);

            $('#dealsTable').DataTable({
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': false,
                'info': true,
                'autoWidth': false,
                // 'columnDefs': [{
                //     type: 'date-uk',
                //     targets: 4
                // }]
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
                // if (result > 0) {
                //     alert('Deleted successfully...');
                // } else {
                //     alert('Failed. Try again...');
                // }
                // location.reload();
            });
        } else {
            alert('Please select...');
        }
    }
</script>
@endsection
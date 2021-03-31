@extends('layouts.adminlte-boot-4.admin')
@section('content')

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
                <div class="col-lg-12">
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
                            <div class="row ">
                                <div class="col-2">
                                    <label>Users</label>
                                    <select class="form-control" id="selectUser" name="selectUser" onchange="return getRds();">
                                        {!!$data['useroptions']!!}
                                    </select>
                                </div>
                                <div class="col-2">
                                    <label>Rd Types</label>
                                    <select class="form-control" id="selectRdtype" name="selectRdtype" onchange="return getRds();">
                                        {!!$data['rdtypeoptions']!!}
                                    </select>
                                </div>
                                <div class="col-2">
                                    <label>Priority</label>
                                    <select class="form-control" id="rdpr_id" name="rdpr_id" onchange="return getRds();">
                                        {!!$data['rdprtypeoptions']!!}
                                    </select>
                                </div>
                                <div class="col-2">
                                    <label>Product</label>
                                    <select class="form-control" id="pro_id" name="pro_id" onchange="return getRds();">
                                        {!!$data['productoptions']!!}
                                    </select>
                                </div>
                                <div class="col-2">
                                    <label>Product Category</label>
                                    <select class="form-control" id="procat_id" name="procat_id" onchange="return getRds();">
                                        {!!$data['categoryoptions']!!}
                                    </select>
                                </div>

                                <div class="col-2">
                                    <label>Status</label>
                                    <select class="form-control" id="status" name="status" onchange="return getRds();">
                                        <option value='All'>All</option>
                                        <option value='1'>Yes</option>
                                        <option value='2'>No</option>
                                    </select>
                                </div>


                            </div>
                            <div class="row">
                                
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="createdate">Creation Date</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="createdate" id="datepicker" placeholder="" value="">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="close_create_date"><i class="fa fa-window-close"></i></span>
                                            </div>
                                            <span class="text-danger">{{ $errors->first('createdate') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="submitdate">Submission Date</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="submitdate" id="datepicker2" placeholder="" value="">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="close_submit_date"><i class="fa fa-window-close"></i></span>
                                            </div>
                                            <span class="text-danger">{{ $errors->first('submitdate') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="uploadeddate">Uploaded Date</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="uploadeddate" id="datepicker3" placeholder="" value="">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="close_upload_date"><i class="fa fa-window-close"></i></span>
                                            </div>
                                            <span class="text-danger">{{ $errors->first('uploadeddate') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>



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
<!--JQuery Datepicker-->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    var deleteAllUrl = "{{url('accounts/deleteAll/{id}')}}";
    var ajaxchangerdstatus = "{{url('admin/rds/ajaxchangerdstatus/{id}/{status}')}}";
    var ajaxgetrdlist = "{{url('admin/rds/ajaxgetrdlist/{id}/{type}/{user_type}/{intype_id}/{rdpr_id}/{pro_id}/{status}/{create_date}/{submit_date}/{upload_date}/{procat_id}')}}";
    $(function() {
        $("#datepicker").datepicker({
            "dateFormat": 'dd-mm-yy'
        });

        $("#datepicker2").datepicker({
            "dateFormat": 'dd-mm-yy'
        });

        $("#datepicker3").datepicker({
            "dateFormat": 'dd-mm-yy'
        });

        // alert('active');
        $(".sidebar-menu li").removeClass("active");
        $("#liaccounts").addClass("active");

        $("#selectAll").click(function() {
            // $(".checkAll").prop('checked', $(this).prop('checked'));
            var checked = ($(".checkAll").prop('checked') == false) ? true : false;
            $(".checkAll").prop('checked', checked);
        });

        $("#datepicker").change(function() {
            getRds();
        });

        $("#datepicker2").change(function() {
            getRds();
        });

        $("#datepicker3").change(function() {
            getRds();
        });

        $("#close_create_date").click(function() {
            // alert("close_create_date");
            $("#datepicker").val("");
            getRds();
        });

        $("#close_submit_date").click(function() {
            // alert("close_submit_date");
            $("#datepicker2").val("");
            getRds();
        });

        $("#close_upload_date").click(function() {
            // alert("close_upload_date");
            $("#datepicker3").val("");
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
        var create_date = $("#datepicker").val();
        var submit_date = $("#datepicker2").val();
        var upload_date = $("#datepicker3").val();
        // alert(type + ' ' + intype_id + ' ' + rdpr_id + ' ' + pro_id + ' ' + status + ' ' + create_date + ' ' + submit_date + ' ' + upload_date + ' ' + procat_id);
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
            'create_date': create_date,
            'submit_date': submit_date,
            'upload_date': upload_date,
            'procat_id': procat_id,
        }, function(result, status) {
            // alert(result);

            var res = eval("(" + result + ")");
            // alert(res.total);
            // alert(res.table);
            $("#total").text(res.total);
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
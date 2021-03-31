@extends('layouts.adminlte-boot-4.user')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Invoice <small class="badge badge-secondary"><?php echo $data['total']; ?></small></h1>
                </div>
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a class="btn btn-sm btn-primary mr-1" href="{{url('invoice/create')}}"><i class="far fa-plus-square mr-1"></i> New Invoice</a>
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
                    <div class='alert alert-danger'>
                        {{session('error')}}
                    </div>
                    @endif
                    <div class="card shadow card-primary card-outline">
                        <!-- 
                    <div class="card-header">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-md-3" id='timeslotDiv'>
                                    <select class="form-control" id='timeslot' name="timeslot">
                                        <option value='0'>Select ...</option>
                                        <option value='monthly'>Monthly</option>
                                        <option value='quarterly'>Quarterly</option>
                                        <option value='annually'>Annually</option>
                                    </select>
                                </div>
                                <div class="col-md-3" id='datepickerDiv' style="display: none;">
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="datepicker">
                                    </div>

                                </div>
                                <div class="col-md-3" id='quartelyTime' style="display: none;">
                                    <select class="form-control" id='quarterlyslot' name="quarterlyslot">
                                        <option value='1' selected>January, February, March, April</option>
                                        <option value='2'>May, June, July, August</option>
                                        <option value='3'>September, October, November, December</option>
                                    </select>
                                </div>
                                <div class="col-md-3" id='getTimeslotDiv' style="display: none;">
                                    <input type='button' value="Get" name='getTimeslotdata' id='getTimeslotdata' class="btn btn-primary btn-sm">
                                </div>
                            </div>
                        </div>
                    </div>
 -->

                        <!--/.card-header-->
                        <div class="card-body p-0">
                            <?php echo $data['table']; ?>

                        </div>
                        <!-- /.card-body -->
                        <!--<div class="card-footer"></div>-->
                        <!-- /.card-footer -->

                        <!-- /.card -->
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    var deleteAllUrl = "{{url('invoice/deleteAll/{id}')}}";
    var filterUrl = "{{url('invoice/filter/{type}/{year}/{month}')}}";
    var changeinvstageUrl = "{{url('invoice/change/stage')}}";
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#liinvoice").addClass("active");

        //Date picker
        $('#datepicker').datepicker({
            format: 'MM-yyyy',
            autoclose: true,
            viewMode: "months",
            minViewMode: "months"
        });

        $("#timeslot").change(function() {
            var timeslot = $("#timeslot").val();
            $("#quartelyTime").hide();
            $("#getTimeslotDiv").show();
            $("#datepickerDiv").show();
            $("#datepicker").datepicker("destroy");

            if (timeslot == "monthly") {
                $('#datepicker').datepicker({
                    format: 'mm-yyyy',
                    autoclose: true,
                    viewMode: "months",
                    minViewMode: "months"
                });
                $('#datepicker').val('<?php echo date('m-Y'); ?>');
            }
            if (timeslot == "quarterly") {
                $("#quartelyTime").show();
                $('#datepicker').datepicker({
                    format: 'yyyy',
                    autoclose: true,
                    viewMode: "years",
                    minViewMode: "years"
                });
                $('#datepicker').val('<?php echo date('Y'); ?>');
            }
            if (timeslot == "annually") {
                $('#datepicker').datepicker({
                    format: 'yyyy',
                    autoclose: true,
                    viewMode: "years",
                    minViewMode: "years"
                });
                $('#datepicker').val('<?php echo date('Y'); ?>');
            }
        });

        $("#selectAll").click(function() {
            $(".checkAll").prop('checked', $(this).prop('checked'));
        });

        $("#getTimeslotdata").click(function() {
            var timeslot = $("#timeslot").val();
            var dateslot = $('#datepicker').val();

            var year = '';
            var month = '';

            if (timeslot == "none") {
                alert('Please');
            } else {
                if (timeslot == "monthly") {
                    //                    alert(dateslot);

                    var dates = dateslot.split("-");

                    month = dates[0];
                    year = dates[1];

                } else if (timeslot == "quarterly") {
                    var quarterlyslot = $('#quarterlyslot').val();
                    //                    alert(dateslot);
                    //                    alert(quarterlyslot);

                    month = quarterlyslot;
                    year = dateslot;

                } else if (timeslot == "annually") {
                    //                    alert(dateslot);

                    month = 0;
                    year = dateslot;
                } else {
                    alert('Please select ...!');
                    return false;
                }

                $.get(filterUrl, {
                    'type': timeslot,
                    'year': year,
                    'month': month
                }, function(result, status) {
                    //                    alert(result);

                    var res = eval("(" + result + ")");
                    $("#total").text(res.total);
                    $("#table").html(res.table);
                    $('#example1').DataTable({
                        'paging': true,
                        'lengthChange': true,
                        'searching': true,
                        'ordering': false,
                        'info': true,
                        'autoWidth': false,
                    });
                });
            }
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


    function changeLeadStatus(id, ld) {
        // alert(id + " " + ld);
        // alert($("#" + id).val() + " " + ld);

        var posId = $("#" + id).val();
        var invId = ld;
        // alert(changeinvstageUrl);

        $.get(changeinvstageUrl, {
            'posId': posId,
            'invId': invId,
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
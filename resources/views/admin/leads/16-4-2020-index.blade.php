@extends('layouts.adminlte-boot-4.admin')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Leads <span class="badge badge-secondary" id="total">{{$data['total']}}</span></h1>
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
                <div class="col-lg-12">
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
                            <div class="row ">
                                <div class="col-3">
                                    <label>Users</label>
                                    <select class="form-control" id="selectUser" name="selectUser">
                                        {!!$data['useroptions']!!}
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label>Lead Status</label>
                                    <select name="selectLeadStatus" id="selectLeadStatus" class="form-control">
                                        {!!$data['leadstatusSelect']!!}
                                    </select>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Created At</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="far fa-calendar-alt"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control float-right" id="reservation">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="close_create_date"><i class="fa fa-window-close"></i></span>
                                            </div>
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                    <!-- /.form group -->
                                </div>
                                <!--
                                <div class="col-lg-2">
                                    <label>Groups</label>
                                    <select class="form-control" id="selectGroups" name="selectGroups">
                                        {!!$data['groupoptions']!!}
                                    </select>
                                </div>
                                -->
                            </div>
                        </div>
                        <!--                            <div class="btn-group float-right">
                                                            <a href="{{url("admin/allocateleadsquota")}}" class="btn btn-primary">Allocate</a>
                                                            <a href="{{url("admin/assignleadstouser")}}" class="btn btn-primary">Assign Leads</a>
                                                        </div>-->
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


<script>
    var getleadsofuser = "{{url('admin/getleadsofuser/{id}/{status}/{start}/{end}')}}";
    var deleteAllUrl = "{{url('admin/leads/deleteAll/{id}')}}";
    $(function() {

        // $(".sidebar-menu li").removeClass("active");
        // $("#lileads").parent('ul').css('display', 'block');
        // $("#lileads").addClass("active");


        $("#close_create_date").click(function() {
            var startDate = '';
            var endDate = '';
            var status = $("#selectLeadStatus").val();
            var uid = $("#selectUser").val();
            getLeads(uid, status, startDate, endDate);
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

        $("#selectUser").change(function() {
            var uid = $(this).val();
            var status = $("#selectLeadStatus").val();
            var startDate = $('#reservation').data('daterangepicker').startDate.format('DD-MM-YYYY');
            var endDate = $('#reservation').data('daterangepicker').endDate.format('DD-MM-YYYY');
            getLeads(uid, status, startDate, endDate);
            // $.get(getleadsofuser, {
            //     'id': uid,
            //     'status': status,
            //     'start': startDate,
            //     'end': endDate,
            // }, function(result) {
            //     //                alert(result);
            //     var res = eval("(" + result + ")");
            //     $("#total").text(res.total);
            //     $("#table").html(res.table);
            //     $('#leadsTable').DataTable({
            //         'paging': true,
            //         'lengthChange': true,
            //         'searching': true,
            //         'ordering': false,
            //         'info': true,
            //         'autoWidth': false,
            //         'responsive': true
            //     });
            // });
        });


        $("#selectLeadStatus").change(function() {
            var status = $(this).val();
            var uid = $("#selectUser").val();
            var startDate = $('#reservation').data('daterangepicker').startDate.format('DD-MM-YYYY');
            var endDate = $('#reservation').data('daterangepicker').endDate.format('DD-MM-YYYY');
            getLeads(uid, status, startDate, endDate);
            // $.get(getleadsofuser, {
            //     'id': uid,
            //     'status': status,
            //     'start': startDate,
            //     'end': endDate,
            // }, function(result) {
            //     //                alert(result);
            //     var res = eval("(" + result + ")");
            //     $("#total").text(res.total);
            //     $("#table").html(res.table);
            //     $('#leadsTable').DataTable({
            //         'paging': true,
            //         'lengthChange': true,
            //         'searching': true,
            //         'ordering': false,
            //         'info': true,
            //         'autoWidth': false,
            //         'responsive': true
            //     });
            // });
        });



        $('#reservation').change(function() {
            var startDate = $('#reservation').data('daterangepicker').startDate.format('DD-MM-YYYY');
            var endDate = $('#reservation').data('daterangepicker').endDate.format('DD-MM-YYYY');
            var status = $("#selectLeadStatus").val();
            var uid = $("#selectUser").val();
            getLeads(uid, status, startDate, endDate);
            // alert(startDate + ' ' + endDate);
            // return false;

        });

        $("#selectAll").click(function() {
            $(".checkAll").prop('checked', $(this).prop('checked'));
        });





    });

    function getLeads(uid, status, startDate, endDate) {
        $.get(getleadsofuser, {
            'id': uid,
            'status': status,
            'start': startDate,
            'end': endDate,
        }, function(result, status) {

            // alert(result);

            var res = eval('(' + result + ')');

            $("#total").text(res.total);
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
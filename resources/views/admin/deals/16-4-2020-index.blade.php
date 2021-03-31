@extends('layouts.adminlte-boot-4.admin')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-10">
                    <h1 class="m-0 text-dark">Deals <small class="badge badge-secondary" id="total">{{$data['total']}}</small></h1>
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
                        <div class="card-header">
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>User</label>
                                        <select class="form-control" id="selectUser" name="selectUser">
                                            {!!$data['useroptions']!!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Deal Stage</label>
                                        <select class="form-control" id="dealStageFilter">
                                            {!!$data['dealstageoptions']!!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
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
                                                <span class="input-group-text" id="close_reservation"><i class="fa fa-window-close"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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


<script>
    var getUserDeals = "{{url('admin/ajax/getUserDeals/{uid}/{stage}/{start}/{end}')}}";
    var deleteAllUrl = "{{url('admin/deals/deleteAll/{id}')}}";
    $(function() {

        $(".sidebar-menu li").removeClass("active");
        $("#lideals").addClass("active");

        $("#selectAll").click(function() {
            $(".checkAll").prop('checked', $(this).prop('checked'));
        });


        $("#close_reservation").click(function() {
            // alert("close_reservation");
            // $("#reservation").val("");
            var uid = $("#selectUser").val();
            var stage = $("#dealStageFilter").val();
            // alert(filter);

            getDeals(uid, stage, '', '');

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
            var stage = $("#dealStageFilter").val();
            var startDate = $('#reservation').data('daterangepicker').startDate.format('DD-MM-YYYY');
            var endDate = $('#reservation').data('daterangepicker').endDate.format('DD-MM-YYYY');
            getDeals(uid, stage, startDate, endDate);
            // $.get(getUserDeals, {
            //     'uid': uid,
            //     'stage': stage,
            //     'start': startDate,
            //     'end': endDate,

            // }, function(result) {
            //     var res = eval("(" + result + ")");
            //     $("#total").text(res.total);
            //     $("#table").html(res.table);

            //     $('#dealsTable').DataTable({
            //         'paging': true,
            //         'lengthChange': true,
            //         'searching': true,
            //         'ordering': false,
            //         'info': true,
            //         'autoWidth': false
            //     });

            // });
        });

        $("#dealStageFilter").change(function() {
            var uid = $("#selectUser").val();
            var stage = $(this).val();
            var startDate = $('#reservation').data('daterangepicker').startDate.format('DD-MM-YYYY');
            var endDate = $('#reservation').data('daterangepicker').endDate.format('DD-MM-YYYY');
            getDeals(uid, stage, startDate, endDate);
            // $.get(getUserDeals, {
            //     'uid': uid,
            //     'stage': stage,
            //     'start': startDate,
            //     'end': endDate,

            // }, function(result) {
            //     var res = eval("(" + result + ")");
            //     $("#total").text(res.total);
            //     $("#table").html(res.table);

            //     $('#dealsTable').DataTable({
            //         'paging': true,
            //         'lengthChange': true,
            //         'searching': true,
            //         'ordering': false,
            //         'info': true,
            //         'autoWidth': false
            //     });

            // });
        });


        $('#reservation').change(function() {
            var uid = $("#selectUser").val();
            var startDate = $('#reservation').data('daterangepicker').startDate.format('DD-MM-YYYY');
            var endDate = $('#reservation').data('daterangepicker').endDate.format('DD-MM-YYYY');
            var stage = $("#dealStageFilter").val();
            getDeals(uid, stage, startDate, endDate);
            // alert(startDate + ' ' + endDate);
            // return false;

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
            $("#table").html(res.table);

            $('#dealsTable').DataTable({
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': false,
                'info': true,
                'autoWidth': false
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
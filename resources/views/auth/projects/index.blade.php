@extends('layouts.adminlte-boot-4.user')
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
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Projects <small class="badge badge-secondary" id="total">{{$data['total']}}</small></h1>
                </div>
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a class="btn btn-sm  btn-primary mr-1 px-3" href="{{url('projects/create')}}"><i class="far fa-plus-square mr-1"></i>New Project</a>
                            <!-- <a class="btn btn-sm btn-outline-secondary" href="{{url('deals/import/csv')}}"><i class="fas fa-upload"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('deals/export/csv')}}"><i class="fas fa-download"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('reports/deals')}}"><i class="fa fa-chart-pie"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('deals/status/won')}}"><i class="fa fa-thumbs-up"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('deals/status/lost')}}"><i class="fa fa-thumbs-down"></i></a> -->
                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModal2">
                                <i class="fa fa-filter"></i>
                            </button>
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

                    @if(session('info'))
                    <div class='alert alert-warning'>
                        {{session('info')}}
                    </div>
                    @endif
                    <div class="card shadow card-primary card-outline">
                        <div class="card-header">
                            <h5>
                                <label class="badge badge-secondary">
                                    Manager : <span id="managerName">{!!$data['managerName']!!}</span>
                                </label>
                                <label class="badge badge-secondary">
                                    Analyst / Sub Analyst : <span id="auserName">{!!$data['auserName']!!}</span>
                                </label>
                            </h5>
                        </div>
                        <!--/.card-header-->
                        <div class="card-body p-0" id="table">
                            {!!$data['table']!!}
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white border-top">
                            <div class="btn-group btn-flat pull-left">
                                <button class="btn btn-sm btn-outline-secondary" id="selectAll" onclick="table_row_color_change();"><i class="fas fa-check"></i> Select All</button>
                                <button class="btn btn-sm text-danger btn-outline-secondary" onclick="return deleteAll();" href="#"><i class="far fa-trash-alt"></i></button>
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
                    <div class="form-group col-6">
                        <label for="pro_manager">Manager</label>
                        <select class="form-control" id="manager" name="manager">
                            {!!$data['museroptions']!!}
                        </select>
                    </div>
                    <div class="form-group col-6">
                        <label for="analysts">Analyts/Sub Analysts</label>
                        <select class="form-control" id="auser" name="auser">
                            {!!$data['auseroptions']!!}
                        </select>
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
    var projectsFilterUrl = "{{url('projects/filter/{manager}/{auser}')}}";
    var deleteAllUrl = "{{url('projects/deleteAll/{id}')}}";
    $(function() {

        $("#selectAll").click(function() {
            // $(".checkAll").prop('checked', $(this).prop('checked'));
            var checked = ($(".checkAll").prop('checked') == false) ? true : false;
            $(".checkAll").prop('checked', checked);
        });

        // $("#manager").change(function() {
        //     //            alert($(this).val());
        //     var man = $(this).val();
        //     var auser = $("#auser").val();
        //     $.get(projectsFilterUrl, {
        //         'manager': man,
        //         'auser': auser
        //     }, function(result, status) {

        //         // alert(result);
        //         var res = eval("(" + result + ")");
        //         $("#total").text(res.total);
        //         $("#table").html(res.table);
        //         $('#dealsTable').DataTable({
        //             'paging': true,
        //             'lengthChange': true,
        //             'searching': true,
        //             'ordering': false,
        //             'info': true,
        //             'autoWidth': false,
        //             'responsive': true,
        //             'columnDefs': [{
        //                 type: 'date-uk',
        //                 targets: 4
        //             }]
        //         });
        //         $("#exampleModal2").modal('hide');
        //     });
        // });

        // $("#auser").change(function() {
        //     //            alert($(this).val());
        //     var man = $('#manager').val();
        //     var auser = $(this).val();
        //     // $.get(projectsFilterUrl, {
        //     //     'manager': man,
        //     //     'auser': auser
        //     // }, function(result, status) {

        //     //     // alert(result);
        //     //     var res = eval("(" + result + ")");
        //     //     $("#total").text(res.total);
        //     //     $("#table").html(res.table);
        //     //     $('#dealsTable').DataTable({
        //     //         'paging': true,
        //     //         'lengthChange': true,
        //     //         'searching': true,
        //     //         'ordering': false,
        //     //         'info': true,
        //     //         'autoWidth': false,
        //     //         'responsive': true,
        //     //         'columnDefs': [{
        //     //             type: 'date-uk',
        //     //             targets: 4
        //     //         }]
        //     //     });
        //     //     $("#exampleModal2").modal('hide');

        //     // });
        // });

        $("#applyFilter").click(function() {

            var man = $('#manager').val();
            var auser = $("#auser").val();

            $.get(projectsFilterUrl, {
                'manager': man,
                'auser': auser
            }, function(result, status) {

                // alert(result);
                var res = eval("(" + result + ")");
                $("#total").text(res.total);
                $("#managerName").text(res.managerName);
                $("#auserName").text(res.auserName);

                $("#table").html(res.table);
                $('#dealsTable').DataTable({
                    'paging': true,
                    'lengthChange': true,
                    'searching': true,
                    'ordering': false,
                    'info': true,
                    'autoWidth': false,
                    'responsive': true,
                    'columnDefs': [{
                        type: 'date-uk',
                        targets: 4
                    }]
                });
                $("#exampleModal2").modal('hide');

            });
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
</script>
@endsection
@extends('layouts.adminlte-boot-4.admin')
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
                    <h1>Product Leads <small class="badge badge-secondary" id="totalBadge">{{$data['orders']['total']}}</small></h1>
                </div>
                <div class="col-sm-8">
                    <!-- <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a class="btn btn-sm btn-primary mr-1" href="{{url('leads/create')}}"><i class="far fa-plus-square mr-1"></i> New Lead</a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('leads/import/csv')}}"><i class="fas fa-upload"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('leads/export/csv')}}"><i class="fas fa-download"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('reports/leads')}}"><i class="fa fa-chart-pie"></i></a>
                            <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#exampleModal2">
                                <i class="fa fa-filter"></i>
                            </button>
                        </li>
                    </ol> -->
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
                            <div class="col-3">
                                <label>Users</label>
                                <select class="form-control" id="selectUser" name="selectUser">
                                    {!!$data['useroptions']!!}
                                </select>
                            </div>
                        </div>
                        <!--/.card-header-->

                        <div class="card-body p-0" id="table">
                            {!!$data['orders']['table']!!}
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


<script>
    var deleteAllUrl = "{{url('admin/leads/product/deleteAll/{id}')}}";
    // var leadStatusfilterUrl = "{{url('leads/leadStatusfilter/{id}/{start}/{end}')}}"; //day
    // var changeleadstatusUrl = "{{url('leads/changeleadstatus/{id}/{status}/{deal}')}}";
    var getproductleadsUrl = "{{url('admin/ajax/getproductleads/{uid}')}}";
    $(function() {

        // alert(oldDate + ' ' + latestDate);


        $("#selectAll").click(function() {
            var checked = ($(".checkAll").prop('checked') == false) ? true : false;
            $(".checkAll").prop('checked', checked);
        });

        $("#selectUser").change(function() {
            var uid = $(this).val();
            // alert(uid);
            // alert(prourl);
            $.get(getproductleadsUrl, {
                'uid': uid
            }, function(result) {
                // alert(result);
                var res = eval("(" + result + ")");
                $("#totalBadge").text(res.total);
                $("#table").html(res.table);
                $('#leadsTable').DataTable({
                    'paging': true,
                    'lengthChange': true,
                    'searching': true,
                    'ordering': true,
                    'info': true,
                    'autoWidth': true,
                    'responsive': true
                    // 'columnDefs': [{
                    //     type: 'date-uk',
                    //     targets: 4
                    // }]
                });
                $('#example2').DataTable({
                    'paging': true,
                    'lengthChange': true,
                    'searching': true,
                    'ordering': false,
                    'info': true,
                    'autoWidth': false
                });
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
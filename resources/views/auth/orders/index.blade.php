@extends('layouts.adminlte-boot-4.user')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Orders <small class="badge badge-secondary">{{$data['total']}}</small></h1>
                </div>
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a class="btn btn-sm  btn-primary mr-1 px-3" href="{{url('orders/create')}}"><i class="far fa-plus-square mr-1"></i>New Order</a>
                            <!-- <a class="btn btn-sm btn-outline-secondary" href="{{url('orders/import/csv')}}"><i class="fas fa-upload"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('orders/export/csv')}}"><i class="fas fa-download"></i></a> -->
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('reports/orders')}}"><i class="fa fa-chart-pie"></i></a>
                            <!--<a class="btn btn-sm btn-outline-secondary" href="{{url('orders/status/won')}}"><i class="fa fa-thumbs-up"></i></a>-->
                            <!--<a class="btn btn-sm btn-outline-secondary" href="{{url('orders/status/lost')}}"><i class="fa fa-thumbs-down"></i></a>-->
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
                            <!-- <div class="col-lg-12">
                                <div class="form-group form-inline pull-left">
                                    <label>Select Stage</label>
                                    <select class="ml-2 form-control" id="dealFilter">
                                        <option value="all">All</option>
                                        <option value="won">Won</option>
                                        <option value="lost">Lost</option>
                                        <option value="closedthisMonth">Closes this month </option>
                                        <option value="closednextMonth">Closes next month </option>
                                    </select>
                                </div>
                            </div> -->
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
<script>
    var deleteAllUrl = "{{url('deals/deleteAll/{id}')}}";
    var dealFilter = "{{url('deals/filter/{type}')}}";
    $(function() {

        $(".sidebar-menu li").removeClass("active");
        $("#lideals").addClass("active");

        $("#selectAll").click(function() {
            var checked = ($(".checkAll").prop('checked') == false) ? true : false;
            $(".checkAll").prop('checked', checked);
        });

        $("#dealFilter").change(function() {
            //            alert($(this).val());
            var filter = $(this).val();
            $.get(dealFilter, {
                'type': filter
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
                    'autoWidth': false,
                    'columnDefs': [{
                        type: 'date-uk',
                        targets: 4
                    }]
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
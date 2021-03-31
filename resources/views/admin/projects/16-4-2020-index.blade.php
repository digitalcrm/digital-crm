@extends('layouts.adminlte-boot-4.admin')
@section('content')


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
                            <!--<a class="btn btn-sm  btn-primary mr-1 px-3" href="{{url('projects/create')}}"><i class="far fa-plus-square mr-1"></i>New Project</a>
                             <a class="btn btn-sm btn-outline-secondary" href="{{url('deals/import/csv')}}"><i class="fas fa-upload"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('deals/export/csv')}}"><i class="fas fa-download"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('reports/deals')}}"><i class="fa fa-chart-pie"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('deals/status/won')}}"><i class="fa fa-thumbs-up"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('deals/status/lost')}}"><i class="fa fa-thumbs-down"></i></a> -->
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

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
                            <div class="row">
                                <div class="col-2">
                                    <label>Users</label>
                                    <select class="form-control" id="selectUser" name="selectUser" onchange="return getProjects();">
                                        {!!$data['useroptions']!!}
                                    </select>
                                </div>

                                <div class="col-2">
                                    <label for="pro_manager">Manager</label>
                                    <select class="form-control" id="manager" name="manager" onchange="return getProjects();">
                                        {!!$data['museroptions']!!}
                                    </select>
                                </div>
                                <div class="col-2">
                                    <label for="analysts">Analyts/Sub Analysts</label>
                                    <select class="form-control" id="auser" name="auser" onchange="return getProjects();">
                                        {!!$data['auseroptions']!!}
                                    </select>
                                </div>
                            </div>
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
    var projectsFilterUrl = "{{url('admin/projects/filter/{user}/{manager}/{auser}')}}";
    var deleteAllUrl = "{{url('admin/projects/deleteAll/{id}')}}";
    $(function() {

        $("#selectAll").click(function() {
            // $(".checkAll").prop('checked', $(this).prop('checked'));
            var checked = ($(".checkAll").prop('checked') == false) ? true : false;
            $(".checkAll").prop('checked', checked);
        });

    });

    function getProjects() {
        var user = $('#selectUser').val();
        var man = $('#manager').val();
        var auser = $('#auser').val();
        $.get(projectsFilterUrl, {
            'user': user,
            'manager': man,
            'auser': auser
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
                'responsive': true,
                'columnDefs': [{
                    type: 'date-uk',
                    targets: 4
                }]
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
@extends('layouts.adminlte-boot-4.admin')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-4">
                    <h1 class="m-0 text-dark">Products <span id="total" class="badge badge-secondary">{{$data['total']}}</span></h1>
                </div>
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a class="btn btn-sm btn-default mr-1" href="{{url('admin/products/search/analytics')}}"><i class="fas mr-1"></i>Analytics</a>
                            <a class="btn btn-sm btn-default mr-1" href="{{url('admin/products/inventory/list')}}"><i class="fas mr-1"></i>Inventory</a>
                            <a class="btn btn-sm btn-outline-secondary float-right mr-1" href="{{url('admin/products/export/csv')}}"><i class="fas fa-download"></i></a>
                            <a class="btn btn-sm btn-outline-secondary float-right mr-1" href="{{url('admin/products/import/csv')}}"><i class="fas fa-upload"></i></a>
                            <a class="btn btn-primary px-3 float-right mr-1" href="{{url('admin/products/create')}}"><i class="far fa-plus-square"></i> New Product</a>
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
            <!-- Content Header (Page header) -->
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
                        <div class="card-header">
                            <div class="col-3">
                                <label>Users</label>
                                <select class="form-control" id="selectUser" name="selectUser">
                                    {!!$data['useroptions']!!}
                                </select>
                            </div>
                        </div>
                        <!--/.card-header-->
                        <div class="card-body p-0">
                            <div id="table">
                                {!!$data['table']!!}
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
    var prourl = "{{url('admin/ajax/getUserProducts/{uid}')}}"; ///{uid}
    var ajaxupdatefeaturestatus = "{{url('admin/products/ajaxupdatefeaturestatus/{id}/{status}')}}";
    $(function() {

        // $("input[data-bootstrap-switch]").each(function() {
        //     $(this).bootstrapSwitch('state', $(this).prop('checked'));
        // });

        $(".sidebar-menu li").removeClass("active");
        $("#liproducts").addClass("active");

        $("#selectUser").change(function() {
            var uid = $(this).val();
            // alert(uid);
            // alert(prourl);
            $.get(prourl, {
                'uid': uid
            }, function(result) {
                // alert(result);
                var res = eval("(" + result + ")");
                $("#total").text(res.total);
                $("#table").html(res.table);
                $('#example1').DataTable({
                    'paging': false,
                    'lengthChange': true,
                    'searching': true,
                    'ordering': false,
                    'info': false,
                    'autoWidth': false,
                    'columnDefs': [{
                        type: 'date-uk',
                        targets: 4
                    }]
                });
                $('#example2').DataTable({
                    'paging': false,
                    'lengthChange': true,
                    'searching': true,
                    'ordering': false,
                    'info': false,
                    'autoWidth': false
                });
            });
        });
    });

    function changeFeaturedStatus(proId, featStatus) {
        // alert(proId + " " + featStatus);

        $.get(ajaxupdatefeaturestatus, {
            'id': proId,
            'status': featStatus
        }, function(result) {
            // alert(result);
            if (result == 'success') {
                alert("Updated Successfully");
            } else {
                alert("Error occurred. Please try again later.");
            }
        });
    }
</script>
@endsection

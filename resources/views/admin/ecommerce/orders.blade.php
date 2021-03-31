@extends('layouts.adminlte-boot-4.admin')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Orders <span id="total" class="badge badge-secondary">{{$data['total']}}</span></h1>
                </div>
                <!-- <div class="col-sm-6">
                    <a class="btn btn-primary px-3 float-right" href="{{url('admin/products/create')}}"><i class="far fa-plus-square"></i> New Product</a>
                </div> -->

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
    var consurl = "{{url('admin/ecommerce/ajax/orders/{cid}')}}"; ///{uid}
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#liproducts").addClass("active");

        $("#selectUser").change(function() {
            var uid = $(this).val();
            // alert(uid);
            // alert(prourl);
            $.get(consurl, {
                'uid': uid
            }, function(result) {
                // alert(result);
                var res = eval("(" + result + ")");
                $("#total").text(res.total);
                $("#table").html(res.table);
                $('#example1').DataTable({
                    'paging': true,
                    'lengthChange': true,
                    'searching': true,
                    'ordering': true,
                    'info': true,
                    'autoWidth': false,
                });
            });
        });
    });
</script>
@endsection
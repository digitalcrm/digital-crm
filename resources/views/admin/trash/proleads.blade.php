@extends('layouts.adminlte-boot-4.admin')
@section('content')

<!--Style-->
<style type="text/css">


</style>
<!--/Style-->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-10 mt-1">
                    <h1 class="m-0 text-dark">Product Leads <small class="badge badge-secondary" id="total">{{$data['total']}}</small></h1>
                </div>
                <div class="col-sm-2">
                    <select class="form-control" id="selectUser" name="selectUser">
                        {!!$data['useroptions']!!}
                    </select>
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
                        <!--/.card-header-->
                        <div class="card-body p-0" id="table">
                            {!!$data['table']!!}
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white border-top">
                            <a href="<?php echo url('admin/trash'); ?>" class="btn btn-outline-secondary float-right">Back</a>
                        </div>
                        <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->
                    <div id="resulttt">

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
    var dayurl = "{{url('admin/trash/getuserproleads/{uid}')}}";
    $(function() {

        // alert('active');
        $(".sidebar-menu li").removeClass("active");
        $("#litrash").addClass("active");


        $("#selectUser").change(function() {
            var user = $(this).val();
            // alert(user);
            $.get(dayurl, {
                'uid': user
            }, function(result) {
                // alert(result);
                var res = eval("(" + result + ")");
                $("#table").html(res.table);
                $("#total").text(res.total);

                $('#example1').DataTable({
                    'paging': true,
                    'lengthChange': true,
                    'searching': true,
                    'ordering': true,
                    'info': true,
                    'autoWidth': false,
                })
                $('#example2').DataTable({
                    'paging': true,
                    'lengthChange': true,
                    'searching': true,
                    'ordering': true,
                    'info': true,
                    'autoWidth': false,
                })
            });
        });
    });
</script>
@endsection
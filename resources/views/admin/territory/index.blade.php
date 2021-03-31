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
            <div class="row mb-0">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Territory
                    <small class="badge badge-secondary" id="total">{{$data['total']}}</small></h1>
                </div>
				<!-- <div class="col-sm-6">
                    <a class="btn btn-primary px-3 float-right" href="{{url('admin/territory/create')}}"><i class="far fa-plus-square mr-1"></i>New Territory</a>
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

        <!-- Small cardes (Stat card) -->
        <div class="row">
            <div class="col-lg-12 p-0">
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
                    <div class="card-header with-border">
                        <h3 class="card-title float-left">
                            <select class="form-control" id="selectUser" name="selectUser">{!!$data['useroptions']!!}</select>
                        </h3>
                        <div class="btn-group btn-default float-right">
                            
                        </div>
                    </div> 
                    <!--/.card-header--> 
                    <div class="card-body p-0" id="table">
                        {!!$data['table']!!}
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
    var url = "{{url('admin/ajax/getUserTerritory')}}";
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#literritory").addClass("active");


        $("#selectUser").change(function() {
            var uid = $(this).val();
//            alert(uid);
            var ajaxUrl = url;
//            alert(ajaxUrl);
            $.get(ajaxUrl, {'uid': uid}, function(result) {
//                alert(result);
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
                    'columnDefs': [
                        {type: 'date-uk', targets: 4}
                    ]
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
</script>
@endsection
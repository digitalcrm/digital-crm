@extends('layouts.adminlte-boot-4.admin')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-0">
                    <div class="col-sm-10">
                        <h1 class="m-0 text-dark">Groups</h1>
                    </div>                
                    <div class="col-sm-2">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                            <li class="breadcrumb-item active">Groups</li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
    <!-- Main content -->
    <section class="content mt-2 mx-0">
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
                <div class="card shadow card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Groups
                            <small id="total" class="badge">{{$data['total']}}</small>
                        </h3>
                        <div class="btn-group btn-flat float-right">
                            <a class="btn bg-blue" href="{{url('admin/groups/create')}}"><i class="fa fa-plus"></i>&nbsp; Add New</a>
                        </div>
                    </div>
                    <!--/.card-header-->
                    <div class="card-body p-0"  id="table">
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
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#ligroups").addClass("active");

        $("#selectUser").change(function() {
            var uid = $(this).val();
            var ajaxUrl = "{{url('admin/ajax/getUserDocuments')}}";
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

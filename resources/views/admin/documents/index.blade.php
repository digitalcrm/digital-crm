@extends('layouts.adminlte-boot-4.admin')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-4 mt-0">
                    <h1 class="m-0 text-dark">Documents <small class="badge badge-secondary" id="total">{{$data['total']}}</small></h1>
                </div>
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <select class="form-control" id="selectUser" name="selectUser">
                                {!!$data['useroptions']!!}
                            </select>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Content Header (Page header) -->
    <!--<section class="content-header">-->
    <!--    <h1>-->
    <!--        Documents-->
    <!--        <small id="total">{{$data['total']}}</small>-->
    <!--    </h1>-->
    <!--</section>-->
    <!-- Content Header (Page header) -->
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
                        <!--<h3 class="card-title">-->
                        <!--    <select class="form-control" id="selectUser" name="selectUser">-->
                        <!--        {!!$data['useroptions']!!}-->
                        <!--    </select>-->
                        <!--</h3>-->
                        <div class="btn-group btn-flat float-right">
                            <a class="btn bg-blue" href="{{url('admin/documents/create')}}"><i class="fa fa-plus"></i>&nbsp; Add New</a>
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
        $("#lidocuments").addClass("active");


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

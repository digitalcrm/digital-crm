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
                <div class="col-sm-6">
                    <h1>States
                        <small id="total" class="badge badge-secondary">{{$data['total']}}</small>
                    </h1>
                </div>
				<div class="col-md-6 text-right pull-right">
					<a class="btn btn-primary px-3" href="{{url('admin/states/create')}}"><i class="far fa-plus-square mr-1"></i> New State</a>
				</div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!--    
        <section class="content-header">
            <h1>
                Account Types
                <small id="total">{{$data['total']}}</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Web to lead</li>
            </ol>
        </section>
    -->
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
                <div class="card shadow card-primary card-outline">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-2">
                                <select name="country" id="country" class="form-control">
                                    <?php echo $data['countryOptions']; ?>
                                </select>
                            </div>
                            <div class="col">
                               
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0" id="table">
                        {!!$data['table']!!}
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top text-right pull-right">
                        <a href="<?php echo url('admin/settings'); ?>" class="btn btn-outline-secondary">Back</a>
                    </div>
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
    var url = "{{url('admin/states/getCountryStates/{country}')}}";
    $(function() {

        $(".sidebar-menu li").removeClass("active");

        $("#country").change(function() {
            var country = $(this).val();
//            alert(country);
            var ajaxUrl = url;
//            alert(ajaxUrl);
            $.get(ajaxUrl, {'country': country}, function(result) {
//                alert(result);

                var res = eval("(" + result + ")");
//                alert(res.total);
//                alert(res.table);

                $("#total").text(res.total);
                $("#table").html(res.table);
                $('#example1').DataTable()
                $('#example2').DataTable({
                    'paging': true,
                    'lengthChange': false,
                    'searching': false,
                    'ordering': false,
                    'info': true,
                    'autoWidth': false
                });
            });
        });


    });

</script>
@endsection
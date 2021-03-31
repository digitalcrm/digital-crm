@extends('layouts.adminlte-boot-4.admin')
@section('content')

<!--Style-->
<style type="text/css">


</style>
<!--/Style-->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Main content -->
    <section class="content mt-2 mx-0">
	<div class="container-fluid">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-0">
                    <div class="col-sm-10">
                        <h1 class="m-0 text-dark">Extension</h1>
                    </div>                
                    <div class="col-sm-2">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                            <li class="breadcrumb-item active">Extension</li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
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
                    <div class="card-header with-border">
                        <div class="col-lg-10">
                            <h3 class="card-title">
                                Extentions
                            </h3>
                        </div>

                    </div> 
                    <!--/.card-header--> 
                    <div class="card-body" id="table">
                        <div class="card card-primary">

                            <!-- /.card-header -->
                            <div class="card-body">
                                <ul class="products-list product-list-in-card">
                                    <li class="item">
                                        <div class="product-img">
                                            <i class="fa fa-file-excel-o fa-2x"></i>
                                        </div>
                                        <div class="product-info">
                                            <a href="{{url('admin/leadcsv')}}" class="product-title">Import Leads From Facebook CSV Files</a>
                                        </div>
                                    </li>
                                    <!-- /.item -->

                                    <li class="item">
                                        <div class="product-img">
                                            <i class="fa fa-facebook fa-2x"></i>
                                        </div>
                                        <div class="product-info">
                                            <a href="{{url('admin/fbadmanager')}}" class="product-title">Import Leads From Facebook Using Zapier</a>
                                        </div>
                                    </li>
                                    <!-- /.item -->

                                    <li class="item">
                                        <div class="product-img">
                                            <i class="fa fa-code fa-2x"></i>
                                        </div>
                                        <div class="product-info">
                                            <a href="{{url('admin/unassignedleads')}}" class="product-title">Round Robin</a>
                                        </div>
                                    </li>
                                    <!-- /.item -->

                                </ul>
                            </div>

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
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#liextentions").addClass("active");


    });


</script>
@endsection
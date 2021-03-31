@extends('layouts.adminlte-boot-4.consumers.product_details')
<?php
$url = url()->current();
?>
@section('title', 'companyslist')
@section('description', 'list of Companys available')
@section('url',$url)
@section('site_name', 'supportcrm')
@section('keywords', 'Brands, Products')
@section('content')
<style>
    ul {
        list-style: none;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1></h1>
                </div>
                <!-- <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Blank Page</li>
                    </ol>
                </div> -->
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-sm-6">
                <h1></h1>
                @if(session('success'))
                <div id="alertSuccess" class='alert alert-success'>
                    {{session('success')}}
                </div>
                @endif

                @if(session('error'))
                <div id="alertError" class='alert alert-danger'>
                    {{session('error')}}
                </div>
                @endif
            </div>
        </div>
        <!-- Default box -->

        <div class="row">
            <div class="col-lg-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <B>Products by Company</B>
                        </h3>
                        <!-- <div class="card-tools">
                            <a href="{{url('shop/sitemap/productcategories/feed')}}"><span class="badge badge-primary">Rss</span></a>
                        </div> -->
                    </div>
                    <div class="card-body">
                        <div class="container">
                            <div class="row float:center">
                                <div class="col-lg-4">
                                    <ul>
                                        <?php echo $data['oneli']; ?>
                                    </ul>
                                </div>
                                <div class="col-lg-4">
                                    <ul>
                                        <?php echo $data['twoli']; ?>
                                    </ul>
                                </div>
                                <div class="col-lg-4">
                                    <ul>
                                        <?php echo $data['threeli']; ?>
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <!-- <div class="card-footer"></div> -->
                    <!-- /.card-footer-->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
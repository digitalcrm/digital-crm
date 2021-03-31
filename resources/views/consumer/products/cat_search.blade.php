@extends('layouts.adminlte-boot-4.consumers.product_details')
<?php
// echo json_encode($data);
// exit();
$procats = $data['procats'];
// $categoryName = $procats->category;
// $categorySlug = $procats->slug;

// $metadata = $data['metadata'];
?>
@section('title', '')
@section('description', '')
@section('url', '')
@section('site_name', '')
@section('keywords', '')
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
                    <h1>Search by Product Category</h1>
                </div>
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
        <?php

        // $procats = $data['procats'];

        // use App\Tbl_productcategory;
        // use App\Tbl_product_subcategory;
        // // echo json_encode($data);
        // // exit();

        // $categoryName = $procats->category;
        // $procat_id = $procats->procat_id;
        // $products = $data['products'];

        // // echo json_encode($products);
        // // exit();

        // $oneli = '';
        // $twoli = '';
        // $kli = 1;
        // $lielement = '';
        // foreach ($products as $product) {
        //     $prosubcat = '';
        //     if ($product->prosubcat_id > 0) {
        //         $prosubcatdet = Tbl_product_subcategory::find($product->prosubcat_id);
        //         $prosubcat = '<a href="' . url('shop/sitemap/products/subcategory/' . $prosubcatdet->slug) . '"><small>(' . ucfirst($prosubcatdet->category) . ')</small></a>';
        //     }

        //     $lielement = '<li><a href="' . url('shop/product/' . $product->slug) . '">' . ucfirst($product->name) . ' ' . $prosubcat . '</a></li>';
        //     if (($kli % 2) == 0) {
        //         $twoli .= $lielement;
        //     } else {
        //         $oneli .= $lielement;
        //     }
        //     $kli++;
        // }


        ?>
        <div class="row">
            <div class="col-lg-8 offset-md-2">
                <div class="card">
                   
                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-6">
                                    <!-- <ul></ul> -->
                                    <?php echo $data['oneli']; ?>

                                </div>
                                <div class="col-lg-6">
                                    <!-- <ul></ul> -->
                                    <?php echo $data['twoli']; ?>

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
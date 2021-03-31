@extends('layouts.adminlte-boot-4.consumers.product_details')
<?php
$metadata = $data['metadata'];
?>
@section('title', $metadata['og_title'])
@section('description', $metadata['og_description'])
@section('url', $metadata['og_url'])
@section('site_name', $metadata['og_site_name'])
@section('keywords', $data['prosubcat']->category)
@section('content')
<style>
    ul {
        list-style: none;
    }

    /* .attachment-block .attachment-img {
        max-width: 50px;
        max-height: 50px;
        height: auto;
        float: left;
    } */
</style>
<style>

</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Sitemap / Product Category / Product Sub Category</h1>
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
        <?php

        $prosubcat = $data['prosubcat'];
        $procats = $data['procats'];
        $products = $data['products'];

        use App\Tbl_productcategory;
        use App\Tbl_product_subcategory;
        use App\User;
        use App\Admin;

        // echo json_encode($data);
        // exit();

        $categoryName = ucfirst($procats->category) . ' / ' . ucfirst($prosubcat->category);
        $prosubcat_id = $prosubcat->prosubcat_id;
        $procat_id = $procats->procat_id;



        // echo json_encode($products);
        // exit();

        $oneli = '';
        $twoli = '';
        $kli = 1;
        $lielement = '';
        foreach ($products as $product) {

            $img = ($product->picture != '') ? $product->picture : '/uploads/default/products.jpg';
            if ($product->user_type == 1) {
                $userId = $product->uid;
                $userdt = Admin::with('currency')->find($userId);
            }
            if ($product->user_type == 2) {
                $userId = $product->uid;
                $userdt = User::with('currency')->find($userId);
            }
            // // $lielement = '<li><a href="' . url('shop/product/' . $product->slug) . '">' . ucfirst($product->name) . '</a></li>';
            // $lielement = '<div class="card-comment">';
            // $lielement .= '<img class="img-circle img-sm" src="' . url($img) . '" alt="Product Image">';
            // $lielement .= '<div class="comment-text">';
            // $lielement .= '<span class="username"><a href="' . url('shop/product/' . $product->slug) . '">' . ucfirst($product->name) . '</a></span>';
            // $lielement .= '</div>';
            // $lielement .= '</div>';

            $lielement = '<div class="col-lg-8 col-md-4 mb-3">';
            $lielement .= '<div class="cardd border h-100">';
            $lielement .= '<a href="' . url('shop/product/' . $product->slug) . '" target="_blank"><img class="card-img-top card-img-top-custom" src="' . url($img) . '" alt="" width="301" height="172"></a>';
            $lielement .= '<div class="card-body">';
            $lielement .= '<div class="small card-text">' . substr($product->description, 0, 10) . '</div>';
            $lielement .= '<h6 class="">';
            $lielement .= '<a href="' . url('shop/product/' . $product->slug) . '" target="_blank">' . $product->name . '</a>';
            $lielement .= '</h6>';
            $lielement .= '<p class="font-weight-bold"><span>' . $userdt->currency->html_code . '</span>&nbsp;' . $product->price . '</p>';
            $lielement .= '</div>';
            $lielement .= '</div>';
            $lielement .= '</div>';



            if (($kli % 2) == 0) {
                $twoli .= $lielement;
            } else {
                $oneli .= $lielement;
            }
            $kli++;
        }



        ?>
        <div class="row">
            <div class="col-lg-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <?php echo $categoryName; ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-6">
                                    <!-- <ul></ul> -->

                                    <?php echo $oneli; ?>
                                </div>
                                <div class="col-lg-6">
                                    <!-- <ul></ul> -->
                                    <?php echo $twoli; ?>

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
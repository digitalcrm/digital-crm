@extends('layouts.adminlte-boot-4.user')
@section('content')
<?php
$picUrl = ($data['product']->picture != '') ? url($data['product']->picture) : url('/uploads/default/products.jpg');

// 0-Out of Stock,1-Low Stock,2-In Stock
$current_stock = '';
if ($data['product']->current_stock == 0) {
    $current_stock = 'Out of Stock';
}
if ($data['product']->current_stock == 1) {
    $current_stock = 'Low Stock';
}
if ($data['product']->current_stock == 2) {
    $current_stock = 'In Stock';
}

?>

<style id="compiled-css" type="text/css">
    #carousel-custom {
        /* margin: 20px auto; */
        width: 400px;
    }

    #carousel-custom .carousel-indicators {
        margin: 10px 0 0;
        overflow: auto;
        position: static;
        text-align: left;
        white-space: nowrap;
        width: 100%;
    }

    #carousel-custom .carousel-indicators li {
        background-color: transparent;
        -webkit-border-radius: 0;
        border-radius: 0;
        display: inline-block;
        height: auto;
        margin: 0 !important;
        width: auto;
    }

    #carousel-custom .carousel-indicators li img {
        display: block;
        opacity: 0.5;
    }

    #carousel-custom .carousel-indicators li.active img {
        opacity: 1;
    }

    #carousel-custom .carousel-indicators li:hover img {
        opacity: 0.75;
    }

    #carousel-custom .carousel-outer {
        position: relative;
    }

    /* EOS */
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1><?php echo $data['product']->name; ?></h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.container-fluid -->

    <!-- Main content -->
    <section class="content mt-2 mx-0">
        <div class="container-fluid">

            <div class="row">
                <section class="col-lg-6">
                    <div class="card card-default">
                        <div class="card-header">
                            <div class="card-title">
                                <ol class="breadcrumb float-sm-left">
                                    <?php
                                    if ($data['product']->tbl_productcategory != '') {
                                        echo '<li class="breadcrumb-item"><a href=' . url('/shop/sitemap/products/category/' . $data['product']->tbl_productcategory->slug) . '>' . (($data['product']->tbl_productcategory != '') ? $data['product']->tbl_productcategory->category : '') . '</a></li>';
                                    }
                                    if ($data['product']->tbl_product_subcategory != '') {
                                        //  /sitemap/products/subcategory/
                                        echo '<li class="breadcrumb-item"><a href=' . url('/shop/sitemap/products/subcategory/' . $data['product']->tbl_product_subcategory->slug) . '>' . (($data['product']->tbl_product_subcategory != '') ? $data['product']->tbl_product_subcategory->category : '') . '</a></li>';
                                    }

                                    ?>

                                </ol>
                            </div>
                            <!-- <div class="card-tools">
                                <ul class="nav nav-pills ml-auto">
                                    <li class="nav-item">
                                        <a class="nav-link" target="_blank" href="{{url('shop/product/'.$data['product']->slug)}}"><small>Go to Store</small></a>
                                    </li>
                                </ul>
                            </div> -->
                        </div>
                        <div class="card-body">
                            <img src="<?php echo $picUrl; ?>" width="400" height="auto" />
                        </div>

                    </div>
                </section>

                <section class="col-lg-6">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">Inventory Details</h3>
                        </div>
                        
                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Product ID</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" value="{{$data['product']->productid}}">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Product SKU</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" value="{{$data['product']->productsku}}">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">QR Code</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" value="{{$data['product']->qrcode}}">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Supplier Price</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" value="{{$data['product']->supply_price}}">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Sale Price</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" value="{{$data['product']->price}}">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Location</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" value="{{$data['product']->location}}">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Current Stock</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" value="{{$current_stock}}">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Company</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" value="{{$data['company_name']}}">
                            </div>
                        </div>
                    </div>

                </section>

            </div>


        </div>
        <!-- /.row (main row) -->
</div>
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
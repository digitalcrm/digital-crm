@extends('layouts.adminlte-boot-4.user')
@section('content')
<?php
$picUrl = ($data['product']->picture != '') ? url($data['product']->picture) : url('/uploads/default/products.jpg');

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
                            <div class="card-tools">
                                <ul class="nav nav-pills ml-auto">
                                    <li class="nav-item">
                                        <!-- <a class="nav-link" target="_blank" href="{{url('shop/product/'.$data['product']->slug)}}"><small>Go to Store</small></a> -->
                                        <a class="nav-link" target="_blank" href="{{ config('custom_appdetail.product') .$data['product']->slug  }}"><small>Go to Store</small></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php echo $data['slide']; ?>
                        </div>

                    </div>
                </section>

                <section class="col-lg-6">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">Product Details</h3>
                        </div>
                        <div class="card-body">
                            <span class="text-uppercase">
                                {{$data['product']->vendor}}
                            </span>

                            <h6>{{$data['product']->name}}</h6>
                            <h2><span>{!!$data['user']['currency']['html_code']!!}</span>{{$data['product']->price}}</h2>
                            <!-- <div class="badge badge-success mr-4">3.8 <i class="fa fa-star" aria-hidden="true"></i></div> -->

                            <span class="text-muted">Size</span>
                            <span class="font-weight-bold mr-4">
                                {{$data['product']->size.' '.(($data['product']['tbl_units']!='')?$data['product']['tbl_units']->sortname:'')}}
                            </span>

                            <span class="text-muted">Units</span>
                            <span class="font-weight-bold">
                                {{(($data['product']['tbl_units']!='')?$data['product']['tbl_units']->name:'')}}
                            </span>
                            <h5 class="mt-4">Product Details</h5>
                            <p>{{strip_tags($data['product']->description)}}</p>
                            <?php
                            if ($data['product']->tags != '') {
                                $tagsArr = explode(",", $data['product']->tags);
                                foreach ($tagsArr as $tg) {
                                    echo '<div class="badge badge-success"><a href="' . url('/shop/search/product/' . $tg) . '">' . $tg . '</a></div>&nbsp;';
                                }
                            }
                            ?>


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
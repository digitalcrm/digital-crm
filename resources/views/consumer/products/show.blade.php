@extends('layouts.adminlte-boot-4.consumers.product_details')
<?php
$metadata = $data['metadata'];
//  $metadata['og_title']
$picUrl = ($data['product']->picture != '') ? url($data['product']->picture) : url('/uploads/default/products.jpg');
$url = url()->current();
?>
@section('title',$data['product']->name)
@section('url', $url)
@section('site_name', $metadata['og_site_name'])
@section('description', strip_tags($data['product']->description))
@section('keywords', $data['product']->tags)
@section('image', $picUrl)



@section('ogtitle', $data['product']->name)
@section('ogurl', $url)
@section('ogdescription', strip_tags($data['product']->description))
@section('ogtype', 'website')
@section('ogimage', $picUrl)

@section('content')
<?php


//  consumers
// $buyNowUrl = url('shop/ajax/ajaxbuynow/' . $data['product']->pro_id);   

$buyNowUrl = url('shop/product/buynow/' . $data['product']->slug);

// if (Auth::user() != '') {
// $buyNowUrl = url('consumers/ajax/buynow/' . $data['product']->pro_id);
// }

?>

<style id="compiled-css" type="text/css">
    #carousel-custom {
        margin: 0px auto;
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
		object-fit: contain;
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
	.btn-warning {background-color:#ff6a00;border-color:#ff6a00;color:#fff;}
	.btn-warning:hover {color:#fff;}
	.text-warning {color:#ff6a00!important;}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mt-4">
				<div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left small">
                                    <?php
                                    if ($data['product']->tbl_productcategory != '') {
                                        echo '<li class="breadcrumb-item"><a class="text-muted" href=' . url('/shop/sitemap/products/category/' . $data['product']->tbl_productcategory->slug) . '>' . (($data['product']->tbl_productcategory != '') ? $data['product']->tbl_productcategory->category : '') . '</a></li>';
                                    }
                                    if ($data['product']->tbl_product_subcategory != '') {
                                        //  /sitemap/products/subcategory/
                                        echo '<li class="breadcrumb-item"><a class="text-muted" href=' . url('/shop/sitemap/products/subcategory/' . $data['product']->tbl_product_subcategory->slug) . '>' . (($data['product']->tbl_product_subcategory != '') ? $data['product']->tbl_product_subcategory->category : '') . '</a></li>';
                                    }

                                    ?>

                                </ol>
			  </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- /.container-fluid -->
    <!-- Main content -->
    <section class="content mt-5 mx-0">
        <div class="container-fluid">
            <div class="row">
                <section class="col-lg-5 text-center">
                    <div class="carddd card-default">
                        <div class="card-body pt-0">
                            <?php echo $data['slide']; ?>
                        </div>

                    </div>
     
                </section>

                <section class="col-lg-4">
                    <div class="carddd card-default">
                        <div class="card-body pt-0">
                            <h4 class="text-dark">{{$data['product']->name}}</h4>
							<span class="">
                                <a href="{{url('shop/search/product/'.$data['product']->vendor)}}">{{$data['product']->vendor}}</a>
                            </span>
                            <h4 class="mt-3 text-warning"><span>{!!$data['user']['currency']['html_code']!!}</span>{{$data['product']->price}}</h4>
                            <!-- <div class="badge badge-success mr-4">3.8 <i class="fa fa-star" aria-hidden="true"></i></div> -->

                            <span class="text-muted">Size</span>
                            <span class="mr-4">
                                {{$data['product']->size.' '.(($data['product']['tbl_units']!='')?$data['product']['tbl_units']->sortname:'')}}
                            </span>

                            <span class="text-muted">Units</span>
                            <span class="">
                                {{(($data['product']['tbl_units']!='')?$data['product']['tbl_units']->name:'')}}
                            </span>
                            <h5 class="mt-4">Product Details</h5>
                            <p>{{strip_tags($data['product']->description)}}</p>
                            <?php
                            if ($data['product']->tags != '') {
                                $tagsArr = explode(",", $data['product']->tags);
                                foreach ($tagsArr as $tg) {
                                    echo '<div class="badge badge-secondary mt-3"><a href="' . url('/shop/search/product/' . $tg) . '">' . $tg . '</a></div>&nbsp;';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </section>
			<section class="col-lg-3">
			<a href="{{ url($buyNowUrl) }}" class="btn btn-lg btn-warning" style="border-radius:0px!important;">Contact Supplier</a>
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
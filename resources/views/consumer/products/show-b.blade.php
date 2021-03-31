@extends('layouts.adminlte-boot-4.consumers.product_details')
@section('content')

<?php
$picUrl = ($data['product']->picture != '') ? url($data['product']->picture) : url('/uploads/default/products.jpg');

$buyNowUrl = url('consumers/ajax/ajaxbuynow/' . $data['product']->pro_id);
// if (Auth::user() != '') {
// $buyNowUrl = url('consumers/ajax/buynow/' . $data['product']->pro_id);
// }

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- /.container-fluid -->
    <!-- Main content -->
    <section class="content mt-5 mx-0">
        <div class="container-fluid">
            <!-- Small cardes (Stat card) -->
            <div class="row">
                <section class="row">
                    <div class="col-lg-5">
                        <div class="cardd card-widgetd">
                            <div class="card-header">
                                <ol class="breadcrumb float-sm-left">
                                    <?php
                                    if ($data['product']->tbl_productcategory != '') {
                                        echo '<li class="breadcrumb-item">' . (($data['product']->tbl_productcategory != '') ? $data['product']->tbl_productcategory->category : '') . '</li>';
                                    }
                                    if ($data['product']->tbl_productcategory != '') {
                                        echo '<li class="breadcrumb-item">' . (($data['product']->tbl_product_subcategory != '') ? $data['product']->tbl_product_subcategory->category : '') . '</li>';
                                    }

                                    ?>

                                </ol>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <?php echo $data['slide']; ?>
                                <!-- <img class="img-fluid pad" src="{{url($picUrl)}}" alt="Photo" width="700" height="100"> -->
                                <!-- <br>
                                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                    <ol class="carousel-indicators">
                                        <li data-target="#carouselExampleIndicators" data-slide-to="0" class=""></li>
                                        <li data-target="#carouselExampleIndicators" data-slide-to="1" class="active"></li>
                                        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                                    </ol>
                                    <div class="carousel-inner">
                                        <div class="carousel-item">
                                            <img class="d-block w-100" src="https://placehold.it/900x500/39CCCC/ffffff&amp;text=I+Love+Bootstrap" alt="First slide">
                                        </div>
                                        <div class="carousel-item active">
                                            <img class="d-block w-100" src="https://placehold.it/900x500/3c8dbc/ffffff&amp;text=I+Love+Bootstrap" alt="Second slide">
                                        </div>
                                        <div class="carousel-item">
                                            <img class="d-block w-100" src="https://placehold.it/900x500/f39c12/ffffff&amp;text=I+Love+Bootstrap" alt="Third slide">
                                        </div>
                                    </div>
                                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div> -->
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer bg-white">

                                <a href="{{ url($buyNowUrl) }}" class="btn btn-lg btn-warning"><i class="fas fa-phone"></i> Contact Supplierdddd</a>

                            </div>
                        </div>

                    </div>

                    <div class="col-lg-7">
                        <div class="cardd">
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
                                <p>{{$data['product']->description}}</span>
                                    <?php
                                    if ($data['product']->tags != '') {
                                        $tagsArr = explode(",", $data['product']->tags);
                                        foreach ($tagsArr as $tg) {
                                            echo '<div class="badge badge-success">' . $tg . '</div>&nbsp;';
                                        }
                                    }
                                    ?>



                            </div>

                        </div>
                    </div>


                </section>
            </div>
            <!-- <div class="card-footer bg-white border-top text-right pull-right">
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Back</a>
                        </div> -->

        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <section class="col-lg-10">

            </section>
            <!-- right col -->
        </div>
        <!-- /.row (main row) -->
</div>
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
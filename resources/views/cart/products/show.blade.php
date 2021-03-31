@extends('layouts.adminlte-boot-4.consumer')

@section('content')
<?php
$picUrl = ($data['product']->picture != '') ? url($data['product']->picture) : url('/uploads/default/products.jpg');

$buyNowUrl = url('consumers/ajax/ajaxbuynow/' . $data['product']->pro_id);
// if (Auth::user() != '') {
// $buyNowUrl = url('consumers/ajax/buynow/' . $data['product']->pro_id);
// }

$buyNowPro = url('consumers/ajax/buynow/product/' . $data['product']->pro_id);

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1></h1>
                </div>
            </div>
        </div>
    </section>
    <!-- /.container-fluid -->
    <!-- Main content -->
    <section class="content mt-2 mx-0">
        <div class="container-fluid">
            <!-- Small cardes (Stat card) -->
            <div class="row">
                <section class="row">
                    <div class="col-lg-6">
                        <div class="card card-widget">
                            <div class="card-header">
                                <h3 class="card-title float-left">
                                    <?php echo $data['product']->name; ?>
                                </h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <img class="img-fluid pad" src="{{url($picUrl)}}" alt="Photo" width="700" height="100">
                                <hr>
                                <!-- <div class="row">
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <?php if (Auth::user() != '') {
                                        ?>
                                            <button onclick="return addtoCartProduct(' . $formdetails->pro_id . '   );" class="btn btn-primary btn-block btn-lg"><i class="fas fa-shopping-cart"></i>&nbsp;Add to Cart</button>&nbsp;
                                        <?php
                                        } else {
                                        ?>
                                            <a href="{{url('consumers/login')}}" class="btn btn-primary btn-block btn-lg"><i class="fas fa-shopping-cart"></i>&nbsp;Add to Cart</a>
                                        <?php
                                        } ?>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <a href="{{url('consumers/login')}}" class="btn btn-primary btn-block btn-lg"><i class="fas fa-bolt"></i>&nbsp;Buy Now</a>
                                    </div>
                                </div> -->
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">

                            </div>
                        </div>

                    </div>

                    <div class="col-lg-6">
                        <div class="card">
                            <!-- <div class="card-header">
                                <h3 class="card-title float-left">
                                    Product Details
                                </h3>
                            </div> -->
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                                    <p class="d-flex flex-column">
                                        <span class="text-muted">Name</span><span class="font-weight-bold">
                                            {{$data['product']->name}}
                                        </span>
                                    </p>
                                    <p class="d-flex flex-column">
                                        <span class="text-muted">Price</span><span class="font-weight-bold">
                                            <span>{!!$data['user']['currency']['html_code']!!}</span>&nbsp;{{$data['product']->price}}
                                        </span>
                                    </p>
                                    <p class="d-flex flex-column">
                                        <span class="text-muted">Size</span><span class="font-weight-bold">
                                            {{$data['product']->size.' '.(($data['product']['tbl_units']!='')?$data['product']['tbl_units']->sortname:'')}}
                                        </span>
                                    </p>
                                </div>
                                <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                                    <p class="d-flex flex-column">
                                        <span class="text-muted">Units</span><span class="font-weight-bold">
                                            {{(($data['product']['tbl_units']!='')?$data['product']['tbl_units']->name:'')}}
                                        </span>
                                    </p>
                                    <p class="d-flex flex-column">
                                        <span class="text-muted">Category</span><span class="font-weight-bold">
                                            {{(($data['product']['tbl_units']!='')?$data['product']['tbl_units']->name:'')}}
                                        </span>
                                    </p>
                                </div>
                                <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                                    <p class="d-flex flex-column">
                                        <span class="text-muted">Description</span><span class="font-weight-bold">
                                            {{$data['product']->description}}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ $buyNowPro }}" class="btn btn-warning" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    <i class="fas fa-bolt"></i>&nbsp;Buy Now
                                </a>

                                <!-- <a href="{{ url($buyNowUrl) }}" class="btn btn-warning"><i class="fas fa-bolt"></i>&nbsp;Buy Now</a> -->
                                <form id="logout-form" action="{{ $buyNowPro }}" method="POST" style="display: none;">
                                    @csrf
                                    <input type="hidden" value="{{$data['product']->pro_id}}" id="pro_cart" name="pro_cart">
                                </form>
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
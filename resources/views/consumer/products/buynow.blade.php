@extends('layouts.adminlte-boot-4.consumers.product_details')
<?php
$metadata = $data['metadata'];
//  $metadata['og_title']
$picUrl = ($data['product']->picture != '') ? url($data['product']->picture) : url('/uploads/default/products.jpg');
?>
@section('title',$data['product']->name )
@section('url', $metadata['og_url'])
@section('site_name', $metadata['og_site_name'])
@section('description', strip_tags($data['product']->description))
@section('keywords', $data['product']->tags)
@section('image', $picUrl)
@section('content')
<style>
    .btn-warning {
        background-color: #ff6a00;
        border-color: #ff6a00;
        color: #fff;
    }

    .text-warning {
        color: #ff6a00 !important;
    }

    img.shop-pic-end {
        height: 450px;
        width: 400px;
        object-fit: contain;
        object-position: top;
    }

    .border {
        background-color: #eee !important;
        border-width: 0px !important;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
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

                    @if ($errors->has('g-recaptcha-response'))
                    <div id="alertError" class='alert alert-danger'>
                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                    </div>
                    @endif
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content mx-0">
        <div class="container-fluid">
            <!-- Small cardes (Stat card) -->
            <div class="row">
                <div class="col-lg-5">

                    <!-- <section class="col-lg-12"> -->

                    <div class="carde card-widgete">
                        <!-- /.card-header -->

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <img class="img-fluid pad shop-pic-end" src="{{url($picUrl)}}" alt="Photo">
                                </div>
                                <div class="col-md-7">
                                    <h4 class="text-dark"><a href="{{url('shop/product/'.$data['product']->slug)}}">{{$data['product']->name}}</a></h4>

                                    <span><a href="{{url('shop/search/product/'.$data['product']->vendor)}}">{{$data['product']->vendor}}</a></span>
                                    <h4 class="mt-3 text-warning">{!!$data['user']['currency']['html_code']!!} {{$data['product']->price}}</h4>

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
                                    <p>{{substr(strip_tags($data['product']->description),0,100).'...'}}</p>


                                </div>
                            </div>

                        </div>
                        <!-- /.card-body -->
                        <!-- <div class="card-footer"></div> -->
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="card mt-3 border card-widgetd">
                        <div class="card-header border-bottom">
                            <h5 class="float-left">
                                Contact Supplier
                            </h5>
                            <!--consumers-->
                            <a href="{{url('shop/ajax/getproductdetails/'.$data['product']->pro_id)}}" class="float-right text-right" id=""><i class="fas fa-long-arrow-alt-left"></i></a>
                        </div>
                        {{Form::open(['action'=>['Consumer\AjaxController@buyNowAction',$data['product']->pro_id],'method'=>'Post','enctype'=>'multipart/form-data'])}}
                        @csrf

                        <!-- /.card-header -->
                        <div class="card-body">

                            <!-- form start -->
                            <!-- <form class="form-horizontal"> -->
                            <div class="form-group row mb-2">
                                <div class="col-sm-4">
                                    <label for="quantity" class="control-label text-right">Quantity</label>
                                    <input type="text" class="form-control" name="quantity" id="quantity" placeholder="1" value="{{old('quantity')}}" required>
                                    @if ($errors->has('quantity'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('quantity') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="col-sm-4">
                                    <label for="price" class="control-label text-right">Price</label>
                                    <input type="text" class="form-control" name="price" id="price" placeholder="Price" value="<?php echo $data['product']->price; ?>" readonly required>
                                    @if ($errors->has('price'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('price') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-sm-4">
                                    <label for="total_amount" class="control-label text-right">Total Amount</label>
                                    <input type="text" class="form-control" name="total_amount" id="total_amount" placeholder="Price" value="<?php echo $data['product']->price; ?>" readonly required>
                                    @if ($errors->has('total_amount'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('total_amount') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row mb-2">
                                <div class="col-sm-4">
                                    <label for="name" class="control-label text-right">Contact Name</label>
                                    <input type="text" class="form-control required" name="name" id="name" placeholder="" required value="{{old('name')}}">
                                    @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-sm-4">
                                    <label for="email" class="control-label text-right">Email Id</label>
                                    <input type="email" class="form-control required" name="email" id="email" placeholder="" required value="{{old('email')}}">
                                    @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="col-sm-4">
                                    <label for="mobile" class="control-label text-right">Mobile</label>
                                    <input type="text" class="form-control required" name="mobile" id="mobile" placeholder="" required value="{{old('mobile')}}">
                                    @if ($errors->has('mobile'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('mobile') }}</strong>
                                    </span>
                                    @endif
                                </div>


                            </div>

                            <div class="form-group row mb-2">
                                <div class="col-sm-4">
                                    <label for="address" class="control-label text-right">Delivery Address</label>
                                    <input type="text" class="form-control" name="address" id="address" placeholder="" value="{{old('address')}}">
                                    @if ($errors->has('address'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="col-sm-4">
                                    <label class="text-right" for="country">Country</label>
                                    <select class="form-control" name="country" id="country">
                                        {!!$data['countryoptions']!!}
                                    </select>
                                </div>

                                <div class="col-sm-4">
                                    <label class="text-right" for="city">City</label>
                                    <input type="text" class="form-control" name="city" id="city" placeholder="" value="{{old('city')}}">
                                    @if ($errors->has('city'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                                    @endif
                                </div>



                            </div>

                            <!--<div class="form-group row mb-2">
                                

                                <div class="col-sm-6">
									<label class="text-right" for="state">State</label>
                                    <select class="form-control" name="state" id="state" required>
                                        <option value="0">Select State</option>
                                    </select>
                                </div>							
								
                            </div>

                            <div class="form-group row mb-2">
                                

                                <div class="col-sm-6">
									<label class="text-right" for="zip">Zip</label>
                                    <input type="text" class="form-control" name="zip" id="zip" placeholder="" value="" required>
                                    @if ($errors->has('zip'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('zip') }}</strong>
                                    </span>
                                    @endif
                                </div>								
								
                            </div>-->

                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <label for="message">Message</label>
                                    <textarea class="form-control" id="message" name="message" rows="3">{{old('message')}}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.sitekey') }}" data-error-callback="Fill the recaptcha" data-expired-callback="Your Recaptcha has expired, please verify it again !">
                                </div>
                            </div>


                            <div class="form-group row">
                                <div class="col-sm-12">
                                    {{Form::submit('Send',['class'=>"btn btn-lg btn-block btn-warning"])}}
                                    {{Form::close()}}
                                </div>
                            </div>


                        </div>
                        <!-- /.card-body -->


                    </div>
                </div>
                <!-- </section> -->
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <!-- <section class="col-lg-12"> -->

                <div class="col-lg-6">
                    <!-- <div class="card">
                        <div class="card-header">
                            <h3 class="card-title float-left">
                                Product Details
                            </h3>
                        </div>
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
                    </div> -->

                </div>
                <!-- </section> -->
                <!-- right col -->
            </div>
            <!-- /.row (main row) -->
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
    var stateurl = "{{url('shop/ajax/getstateoptions')}}";
    //  consumers
    $(function() {

        // $("#country").val(country);
        // $("#state").val(state);

        $("#quantity").keyup(function() {
            var q = $(this).val();
            if (q > 0) {
                // alert(q);
                var p = $("#price").val();
                p = p * q;
                $("#total_amount").val(p);
            } else {
                alert('Quantity must be atleast 1');
                return false;
            }

        });

        $("#country").change(function() {
            var country = $(this).val();
            // alert(country);
            if (country > 0) {
                $.get(stateurl, {
                    'country': country
                }, function(result, status) {
                    // alert(result);
                    $("#state").html(result);
                });
            }
        });

    });
</script>
@endsection
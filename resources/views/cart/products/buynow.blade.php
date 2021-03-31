@extends('layouts.adminlte-boot-4.consumer')

@section('content')
<?php $picUrl = ($data['product']->picture != '') ? url($data['product']->picture) : url('/uploads/default/products.jpg'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
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
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content mt-2 mx-0">
        <div class="container-fluid">
            <!-- Small cardes (Stat card) -->
            <div class="row">
                <div class="col-lg-6">

                    <!-- <section class="col-lg-12"> -->

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
                        </div>
                        <!-- /.card-body -->
                        <!-- <div class="card-footer"></div> -->
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card card-widget">
                        <div class="card-header">
                            <h3 class="card-title float-left">
                                Contact Supplier
                            </h3>
                        </div>
                        {{Form::open(['action'=>['Consumer\AjaxController@buyNowAction',$data['product']->pro_id],'method'=>'Post','enctype'=>'multipart/form-data'])}}
                        @csrf

                        <!-- /.card-header -->
                        <div class="card-body">

                            <!-- form start -->
                            <!-- <form class="form-horizontal"> -->
                            <div class="form-group">
                                <label for="quantity" class="col-sm-4 control-label">Quantity</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="quantity" id="quantity" placeholder="Quantity" value="1" required>
                                    @if ($errors->has('quantity'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('quantity') }}</strong>
                                    </span>
                                    @endif
                                </div>

                            </div>

                            <div class="form-group">
                                <label for="price" class="col-sm-4 control-label">Price</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="price" id="price" placeholder="Price" value="<?php echo $data['product']->price; ?>" readonly required>
                                    @if ($errors->has('price'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('price') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="total_amount" class="col-sm-4 control-label">Total Amount</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="total_amount" id="total_amount" placeholder="Price" value="<?php echo $data['product']->price; ?>" readonly required>
                                    @if ($errors->has('total_amount'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('total_amount') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="col-sm-4 control-label">Contact Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Contact Name" required value="">
                                    @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-sm-4 control-label">Email Id</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" name="email" id="email" placeholder="Email" required value="">
                                    @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="mobile" class="col-sm-4 control-label">Mobile</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile" required value="">
                                    @if ($errors->has('mobile'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('mobile') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address" class="col-sm-4 control-label">Delivery Address</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="address" id="address" placeholder="Address" required value="">
                                    @if ($errors->has('address'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                    @endif

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="country">Country</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="country" id="country" required>
                                        {!!$data['countryoptions']!!}
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="state">State</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="state" id="state" required>
                                        <option value="0">Select State</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="city">City</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="city" id="city" placeholder="" value="" required>
                                    @if ($errors->has('city'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                                    @endif

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="zip">Zip</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="zip" id="zip" placeholder="" value="" required>
                                    @if ($errors->has('zip'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('zip') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            {{Form::submit('Submit',['class'=>"btn btn-primary"])}}
                            <a href="{{url('consumers/ajax/getproductdetails/'.$data['product']->pro_id)}}" class="btn btn-primary" id="">Back</a>
                        </div>
                        {{Form::close()}}
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
<script>
    var stateurl = "{{url('consumers/ajax/getstateoptions')}}";
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
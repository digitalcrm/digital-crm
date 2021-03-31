@extends('layouts.adminlte-boot-4.admin')
@section('content')

<?php $picUrl = ($data['product']['picture'] != '') ? url($data['product']['picture']) : url('/uploads/default/products.jpg'); ?>
<?php
// $country =  $data['cart_order']->tbl_countries->name;
// $state =  $data['cart_order']->tbl_states->name;
// exit();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Product</h1>


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

                <div class="col-lg-5">

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
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <!-- <div class="card"> -->
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
                            <!-- </div> -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="card card-widget">
                        <div class="card-header">
                            <h3 class="card-title float-left">
                                Contact Supplier
                            </h3>
                        </div>

                        <!-- /.card-header -->
                        {{Form::open(['action'=>['Admin\EcommerceController@update',$data['cart_order']->coid],'method'=>'Post','enctype'=>'multipart/form-data'])}}
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="quantity" class="col-sm-4 control-label">Order Number</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="number" id="number" placeholder="Order Number" value="<?php echo $data['cart_order']->number; ?>">

                                    <span class="text-danger">
                                        <strong>{{ $errors->first('number') }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="pos_id" class="col-sm-4 control-label">Post Order Stage</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="pos_id" id="pos_id">
                                        {!!$data['orderoptions']!!}
                                    </select>
                                    <span class="text-danger">
                                        <strong>{{ $errors->first('pos_id') }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="pos_id" class="col-sm-4 control-label">Shipping Date</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input class="form-control" type="text" value="{!!date('d-m-Y', strtotime($data['cart_order']->shipping_date))!!}" id="shipping_date" name="shipping_date">
                                        <span class="text-danger">{{ $errors->first('shipping_date') }}</span>
                                    </div>
                                    <span class="text-danger">
                                        <strong>{{ $errors->first('shipping_date') }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="quantity" class="col-sm-4 control-label">Quantity</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="quantity" id="quantity" placeholder="Quantity" value="<?php echo $data['cart_order']->quantity; ?>" required>
                                    <span class="text-danger">
                                        <strong>{{ $errors->first('quantity') }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="price" class="col-sm-4 control-label">Price</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="price" id="price" placeholder="Price" value="<?php echo $data['cart_order']->price; ?>" readonly required>
                                    @if ($errors->has('price'))
                                    <span class="text-danger">
                                        <strong>{{ $errors->first('price') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="total_amount" class="col-sm-4 control-label">Total Amount</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="total_amount" id="total_amount" placeholder="Price" value="<?php echo $data['cart_order']->total_amount; ?>" readonly required>
                                    @if ($errors->has('total_amount'))
                                    <span class="text-danger">
                                        <strong>{{ $errors->first('total_amount') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="col-sm-4 control-label">Contact Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Contact Name" required value="<?php echo $data['cart_order']['name']; ?>">
                                    @if ($errors->has('name'))
                                    <span class="text-danger">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-sm-4 control-label">Email Id</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" name="email" id="email" placeholder="Email" required value="<?php echo $data['cart_order']['email']; ?>">
                                    @if ($errors->has('email'))
                                    <span class="text-danger">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="mobile" class="col-sm-4 control-label">Mobile</label>
                                <div class="col-sm-10">
                                    <input type="text" pattern="" class="form-control" name="mobile" id="mobile" placeholder="Mobile" value="<?php $data['cart_order']['mobile']; ?>">
                                    @if ($errors->has('mobile'))
                                    <span class="text-danger">
                                        <strong>{{ $errors->first('mobile') }}</strong>
                                    </span>
                                    @endif

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address" class="col-sm-4 control-label">Delivery Address</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="address" id="address" placeholder="Address" value="<?php echo $data['cart_order']['address']; ?>">
                                    @if ($errors->has('address'))
                                    <span class="text-danger">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                    @endif

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="country" class="col-sm-4 control-label">Country</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="country" id="country">
                                        {!!$data['countryoptions']!!}
                                    </select>
                                    @if ($errors->has('country'))
                                    <span class="text-danger">
                                        <strong>{{ $errors->first('country') }}</strong>
                                    </span>
                                    @endif

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="state" class="col-sm-4 control-label">State</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="state" id="state">
                                        {!!$data['stateoptions']!!}
                                    </select>
                                    @if ($errors->has('state'))
                                    <span class="text-danger">
                                        <strong>{{ $errors->first('state') }}</strong>
                                    </span>
                                    @endif

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="city" class="col-sm-4 control-label">City</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="city" id="city" placeholder="" value="<?php echo $data['cart_order']->city; ?>" required>
                                    @if ($errors->has('city'))
                                    <span class="text-danger">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                                    @endif

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="zip" class="col-sm-4 control-label">Zip</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="zip" id="zip" placeholder="" value="<?php echo $data['cart_order']->zip; ?>" required>
                                    @if ($errors->has('zip'))
                                    <span class="text-danger">
                                        <strong>{{ $errors->first('zip') }}</strong>
                                    </span>
                                    @endif

                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            {{Form::hidden('_method','PUT')}}
                            {{Form::submit('Submit',['class'=>"btn btn-primary"])}}
                            <a href="{{url('admin/ecommerce/orders/all')}}" class="btn btn-primary" id="">Back</a>
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
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    var stateurl = "{{url('consumers/ajax/getstateoptions')}}";
    $(function() {

        // $("#country").val(country);
        // $("#state").val(state);

        $("#shipping_date").datepicker({
            "dateFormat": 'dd-mm-yy'
        });

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
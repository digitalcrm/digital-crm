@extends('layouts.adminlte-boot-4.user')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-10">
                <h1>
                    Edit Inventory
                </h1>

            </div>
            <div class="col-lg-2">

            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content mt-2 mx-0">
        <div class="container-fluid">
            <!-- Small cardes (Stat card) -->
            <div class="row">
                <div class="col-lg-12">
                    @if(session('success'))
                    <div class='alert alert-success'>
                        {{session('success')}}
                    </div>
                    @endif

                    @if(session('error'))
                    <div class='alert alert-danger'>
                        {{session('error')}}
                    </div>
                    @endif
                    <!-- general form elements -->
                    <div class="card shadow card-primary card-outline">
                        <!-- /.card-header -->
                        <!-- form start -->
                        <!-- <form role="form" > -->
                        <div class="card-header with-border">
                            <h3 class="card-title">
                                <?php echo $data['product']->name; ?>
                            </h3>
                        </div>
                        {{Form::open(['action'=>['ProductController@updateInventory',$data['product']->pro_id],'method'=>'Post','enctype'=>'multipart/form-data'])}}
                        @csrf
                        <div class="card-body">
                            <!-- Left col -->
                            <div class="row">
                                <div class="col-lg-6">

                                    <div class="form-group">
                                        <label for="name">Product</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <select class="form-control" id='product' name="product" required>
                                            {!!$data['productoption']!!}
                                        </select>
                                        <span class="text-danger"><strong>{{ $errors->first('product') }}</strong></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="name">Product ID</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <input type="text" class="form-control" name="productid" id="productid" placeholder="" value="{{$data['product']->productid}}" required>
                                        <span class="text-danger"><strong>{{ $errors->first('productid') }}</strong></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">QR Code</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <input type="text" class="form-control" name="qrcode" id="qrcode" placeholder="" value="{{$data['product']->qrcode}}" required>
                                        <span class="text-danger"><strong>{{ $errors->first('qrcode') }}</strong></span>
                                    </div>

                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Product SKU</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <input type="text" class="form-control" name="productsku" id="productsku" placeholder="" value="{{$data['product']->productsku}}" required>
                                        <span class="text-danger"><strong>{{ $errors->first('productsku') }}</strong></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Supplier Price</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <input type="text" class="form-control" name="supply_price" id="supply_price" placeholder="" value="{{$data['product']->supply_price}}" required>
                                        <span class="text-danger"><strong>{{ $errors->first('supply_price') }}</strong></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Location</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <input type="text" class="form-control" name="location" id="location" placeholder="" value="{{$data['product']->location}}" required>
                                        <span class="text-danger"><strong>{{ $errors->first('location') }}</strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white border-top text-right pull-right">
                            <a href="{{url('products/inventory/list')}}" class="btn btn-outline-secondary">Cancel</a>
                            {{Form::hidden('_method','PUT')}}
                            {{Form::submit('Save',['class'=>"btn btn-primary"])}}
                        </div>
                        <!-- </form> -->
                        {{Form::close()}}
                    </div>
                    <!-- /.card -->
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <section class="col-lg-7 connectedSortable">


                </section>
                <!-- /.Left col -->
                <!-- right col (We are only adding the ID to make the widgets sortable)-->
                <section class="col-lg-5 connectedSortable">


                </section>
                <!-- right col -->
            </div>
            <!-- /.row (main row) -->
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script src="{{asset('assets/bower_components/ckeditor/ckeditor.js')}}"></script>

<script>
    var url = "{{url('admin/ajax/getStateoptions')}}";
    var prosubcaturl = "{{url('products/ajaxgetproductsubcategory/{id}')}}";
    $(function() {
        CKEDITOR.replace('description');

        $(".sidebar-menu li").removeClass("active");
        $("#liproducts").addClass("active");

        $("#account").change(function() {
            var acc = $(this).val();
            if (acc == "NewAccount") {
                $("#addAccount").show();
            } else {
                $("#addAccount").hide();
            }
        });

        $("#country").change(function() {
            var country = $(this).val();
            // alert(country);
            if (country > 0) {
                $.get(url, {
                    'country': country
                }, function(result, status) {
                    // alert(result);
                    $("#state").html(result);
                });
            }
        });


        $("#procat_id").change(function() {
            var catId = $(this).val();
            if (catId > 0) {
                $.get(prosubcaturl, {
                    'procat_id': catId
                }, function(result) {
                    // alert(result);
                    $("#prosubcat_id").html(result);
                });
            }
        });

    });
</script>
@endsection
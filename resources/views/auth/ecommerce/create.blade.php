@extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-10 pb-2">
                <h1>
                    New Product
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
                        {{Form::open(['action'=>'Admin\ProductController@store','method'=>'Post','enctype'=>'multipart/form-data'])}}
                        @csrf
                        <div class="card-body">
                            <section class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Name</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <input type="text" class="form-control" name="name" id="name" placeholder="" value="{{old('name')}}" required tabindex="1">
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="size">Size</label>
                                        <input type="number" class="form-control" name="size" id="size" placeholder="" value="{{old('size')}}" tabindex="3">
                                        <span class="text-danger">{{ $errors->first('size') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="procat_id">Product Category</label>
                                        <select class="form-control" id='procat_id' name="procat_id" tabindex="5">
                                            {!!$data['categoryoption']!!}
                                        </select>
                                        <span class="text-danger">{{ $errors->first('procat_id') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>&nbsp;
                                        <textarea class="form-control" name="description" id="description" rows="5" tabindex="7">{{old('description')}}</textarea>
                                        <span class="text-danger">{{ $errors->first('description') }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="price">Price</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <div class="input-group">
                                            <span class="input-group-text"><?php echo $data['currency']->html_code; ?></span>
                                            <input type="number" name="price" id="price" class="form-control" placeholder="" value="{{old('price')}}" required="" tabindex="2">
                                            <span class="text-danger">{{ $errors->first('price') }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="units">Units</label>&nbsp;
                                        <select class="form-control" id="units" name="units" tabindex="4">
                                            {!!$data['unitOptions']!!}
                                        </select>
                                        <span class="text-danger">{{ $errors->first('units') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="picture">Picture</label><br>
                                        <input type="file" class="btn btn-default" name="picture" id="picture" tabindex="6" />
                                        <span class="text-danger">{{ $errors->first('picture') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="shownstore">Store</label><br>
                                        <input type="checkbox" class="btn btn-default" name="shownstore" id="shownstore" tabindex="7" />
                                        <span class="text-danger">{{ $errors->first('shownstore') }}</span>
                                    </div>
                                </div>
                            </section>


                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white border-top text-right pull-right">
                            <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
                            <a href="{{url('admin/products')}}" class="btn btn-outline-secondary">Cancel</a>
                            {{Form::submit('Create',['class'=>"btn btn-primary"])}}
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
<script>
    var url = "{{url('admin/ajax/getUserCurrency')}}";
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#liproducts").addClass("active");


        $("#selectUser").change(function() {
            var uid = $(this).val();
            var ajaxUrl = url;
            //            alert(ajaxUrl);
            $.get(ajaxUrl, {
                'uid': uid
            }, function(result) {
                $("#currencySpan").html(result);
                //                alert(result);
            });
        });


    });
</script>
@endsection
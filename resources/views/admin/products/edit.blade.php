@extends('layouts.adminlte-boot-4.admin')
@section('content')
<?php
// $tags = ($data['product']->tags != '') ? $data['product']->tags : '';
?>
<!-- <script type="text/javascript" src="https://rawgit.com/TimSchlechter/bootstrap-tagsinput/master/src/bootstrap-tagsinput.js"></script>
<script type="text/javascript" src="https://rawgit.com/bassjobsen/Bootstrap-3-Typeahead/master/bootstrap3-typeahead.js"></script>
<link rel="stylesheet" type="text/css" href="https://rawgit.com/timschlechter/bootstrap-tagsinput/master/src/bootstrap-tagsinput.css"> -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-10">
                <h1>
                    Edit product
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
                        {{Form::open(['action'=>['Admin\ProductController@update',$data['product']->pro_id],'method'=>'Post','enctype'=>'multipart/form-data'])}}
                        @csrf
                        <div class="card-body">
                            <!-- Left col -->
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Name</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <input type="text" class="form-control" name="name" id="name" placeholder="" value="{{$data['product']->name}}" required tabindex="1">
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    </div>

                                    <div class="form-group"><br>
                                        <label for="description">Description</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <textarea class="form-control" name="description" id="description" tabindex="3" rows="5">{{$data['product']->description}}</textarea>
                                        <span class="text-danger">{{ $errors->first('description') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="size">Size</label>
                                        <input type="number" class="form-control" name="size" id="size" placeholder="" value="{{$data['product']->size}}" tabindex="">
                                        <span class="text-danger">{{ $errors->first('size') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="units">Units</label>&nbsp;
                                        <select class="form-control" id="units" name="units" tabindex="">
                                            {!!$data['unitOptions']!!}
                                        </select>
                                        <span class="text-danger">{{ $errors->first('units') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="vendor">Brand</label>
                                        <input type="text" class="form-control" name="vendor" id="vendor" placeholder="" value="<?php echo $data['product']->vendor; ?>">
                                        <span class="text-danger">{{ $errors->first('vendor') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="store">Store</label><br>
                                        <input type="checkbox" class="btn btn-default" name="store" id="store" tabindex="" <?php echo ($data['product']->store == 1) ? 'checked' : ''; ?> />
                                        <span class="text-danger">{{ $errors->first('store') }}</span>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="price">Price</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <div class="input-group">
                                            <span class="input-group-text">{!!$data['currency']['html_code']!!}</span>
                                            <input type="number" name="price" id="price" class="form-control" placeholder="" value="{{$data['product']->price}}" required="" tabindex="2">
                                            <span class="text-danger">{{ $errors->first('price') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="procat_id">Product Category</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <select class="form-control" id='procat_id' name="procat_id" tabindex="4">
                                            {!!$data['categoryoption']!!}
                                        </select>
                                        <span class="text-danger">{{ $errors->first('procat_id') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="prosubcat_id">Product Sub Category</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <select class="form-control" id='prosubcat_id' name="prosubcat_id" tabindex="5">
                                            {!!$data['prosubcatOptions']!!}
                                        </select>
                                        <span class="text-danger">{{ $errors->first('prosubcat_id') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="name">Company</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <select class="form-control" id='company' name="company" tabindex="6">
                                            {!!$data['companyoption']!!}
                                        </select>
                                        <span class="text-danger">{{ $errors->first('company') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="min_quantity">Minimum Order of Quantity (MOQ)</label>&nbsp;<i class="fa fa-asterisk text-danger"></i><br>
                                        <input type="text" class="form-control" name="min_quantity" id="min_quantity" placeholder="" value="{{$data['product']->min_quantity}}">
                                        @error('min_quantity')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="picture">Picture</label>&nbsp;<i class="fa fa-asterisk text-danger"></i><br>
                                        <input type="file" class="btn btn-default" name="picture" id="picture" tabindex="7" />
                                        <span class="text-danger">{{ $errors->first('picture') }}</span>
                                    </div>

                                    <!-- <div class="form-group">
                                        <label for="picture">Product SlideShow Pictures</label><br>
                                        <input type="file" class="btn btn-default" name="slideshowpics[]" id="pic_2" />
                                        <span class="text-danger">{{ $errors->first('slideshowpics') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <input type="file" class="btn btn-default" name="slideshowpics[]" id="pic_3" tabindex="" />
                                        <span class="text-danger">{{ $errors->first('slideshowpics') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <input type="file" class="btn btn-default" name="slideshowpics[]" id="pic_4" tabindex="" />
                                        <span class="text-danger">{{ $errors->first('slideshowpics') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <input type="file" class="btn btn-default" name="slideshowpics[]" id="pic_5" tabindex="" />
                                        <span class="text-danger">{{ $errors->first('slideshowpics') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="tags">Tags</label>
                                        <input type="text" class="form-control" name="tags" id="tags" placeholder="" value="<?php echo $data['product']->tags; ?>">
                                        <span class="text-danger">{{ $errors->first('tags') }}</span>
                                    </div> -->

                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white border-top text-right pull-right">
                            <a href="{{url('admin/products')}}" class="btn btn-outline-secondary">Cancel</a>
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
    var prosubcaturl = "{{url('admin/products/ajaxgetproductsubcategory/{id}')}}";
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
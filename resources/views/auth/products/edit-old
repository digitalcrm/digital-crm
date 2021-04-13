@extends('layouts.adminlte-boot-4.user')
@section('content')

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
                        {{Form::open(['action'=>['ProductController@update',$data['product']->pro_id],'method'=>'Post','enctype'=>'multipart/form-data'])}}
                        @csrf
                        <div class="card-body">
                            <!-- Left col -->
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Name</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <input type="text" class="form-control" name="name" id="name" placeholder="" value="{{$data['product']->name}}" required tabindex="1">
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                        @error('name')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="form-group"><br>
                                        <label for="description">Description</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <textarea class="form-control" name="description" id="description" tabindex="3" rows="5" required>{{$data['product']->description}}</textarea>
                                        <span class="text-danger">{{ $errors->first('description') }}</span>
                                        @error('description')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="size">Size</label>
                                        <input type="number" class="form-control" name="size" id="size" placeholder="" value="{{$data['product']->size}}" tabindex="">
                                        <span class="text-danger">{{ $errors->first('size') }}</span>
                                        @error('size')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="units">Units</label>&nbsp;
                                        <select class="form-control" id="units" name="units" tabindex="">
                                            {!!$data['unitOptions']!!}
                                        </select>
                                        <span class="text-danger">{{ $errors->first('units') }}</span>
                                        @error('units')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="vendor">Brand</label>
                                        <input type="text" class="form-control" name="vendor" id="vendor" placeholder="" value="<?php echo $data['product']->vendor; ?>">
                                        <span class="text-danger">{{ $errors->first('vendor') }}</span>
                                        @error('vendor')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="store">Store</label><br>
                                        <input type="checkbox" class="btn btn-default" name="store" id="store" tabindex="" <?php echo ($data['product']->store == 1) ? 'checked' : ''; ?> />
                                        <span class="text-danger">{{ $errors->first('store') }}</span>
                                        @error('store')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="" for="price">Price</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <div class="">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">{!!$data['user']['currency']['html_code']!!}</span>
                                                </div>
                                                <input type="number" name="price" id="price" class="form-control required" placeholder="" value="{{$data['product']->price}}" required tabindex="2">
                                                <span class="text-danger">{{ $errors->first('price') }}</span>
                                                @error('price')
                                                <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="procat_id">Product Category</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <select class="form-control" id='procat_id' name="procat_id" tabindex="4" required>
                                            {!!$data['categoryoption']!!}
                                        </select>
                                        <span class="text-danger">{{ $errors->first('procat_id') }}</span>
                                        @error('procat_id')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="form-group">
                                        <label for="prosubcat_id">Product Sub Category</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <input type="text" class="form-control required" name="prosubcatId" id="prosubcatId" placeholder="Sub Category" value="{{$data['billtolabel']}}" required>
                                        <span class="text-danger">{{ $errors->first('prosubcatId') }}</span>
                                        <input type="hidden" name="billtoId" id="billtoId" value="" />
                                        <input type="hidden" name="billtolabel" id="billtolabel" value="" />
                                        <!-- <input type="hidden" name="billtovalue" id="billtovalue" {{$data['billtoId']}} {{$data['billtolabel']}}/> -->
                                        <div id="projectDiv"></div>

                                        @error('prosubcat_id')
                                        <div class="error">{{ $message }}</div>
                                        @enderror

                                        @error('billtoId')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="name">Company</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <select class="form-control" id='company' name="company" tabindex="6" required>
                                            {!!$data['companyoption']!!}
                                        </select>
                                        <span class="invalid-feedback" role="alert"><strong>{{ $errors->first('company') }}</strong></span>
                                        @error('company')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
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
                                        <!-- <span class="text-danger">{{ $errors->first('picture') }}</span> -->
                                        @error('picture')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- <div class="form-group">
                                        <label for="picture">Product SlideShow Pictures</label><br>
                                        <input type="file" class="btn btn-default" name="slideshowpics[]" id="pic_2" />
                                        <span class="text-danger">{{ $errors->first('slideshowpics') }}</span>
                                        @error('slideshowpics')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <input type="file" class="btn btn-default" name="slideshowpics[]" id="pic_3" tabindex="" />
                                        <span class="text-danger">{{ $errors->first('slideshowpics') }}</span>
                                        @error('slideshowpics')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <input type="file" class="btn btn-default" name="slideshowpics[]" id="pic_4" tabindex="" />
                                        <span class="text-danger">{{ $errors->first('slideshowpics') }}</span>
                                        @error('slideshowpics')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <input type="file" class="btn btn-default" name="slideshowpics[]" id="pic_5" tabindex="" />
                                        <span class="text-danger">{{ $errors->first('slideshowpics') }}</span>
                                        @error('slideshowpics')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div> -->

                                    <div class="form-group">
                                        <label for="tags">Tags</label>
                                        <input type="text" class="form-control" name="tags" id="tags" placeholder="" value="<?php echo $data['product']->tags; ?>">
                                        <span class="text-danger">{{ $errors->first('tags') }}</span>
                                        @error('tags')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>


                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white border-top text-right pull-right">
                            <a href="{{url('products')}}" class="btn btn-outline-secondary">Cancel</a>
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
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css"> -->
<style>
    .ui-autocomplete {
        max-height: 200px;
        overflow-y: auto;
        /* prevent horizontal scrollbar */
        overflow-x: hidden;
    }

    /* IE 6 doesn't support max-height
     * we use height instead, but this forces the menu to always be this tall
     */
    * html .ui-autocomplete {
        height: 200px;
    }
</style>

<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/base/jquery-ui.css">
<script src="{{asset('assets/bower_components/ckeditor/ckeditor.js')}}"></script>

<script>
    var leadArray = '<?php echo json_encode($data['leadArray']); ?>';
    var billtoId = '<?php echo $data['billtoId']; ?>';
    var billtolabel = '<?php echo $data['billtolabel']; ?>';
    var url = "{{url('admin/ajax/getStateoptions')}}";
    var prosubcaturl = "{{url('products/ajaxgetproductsubcategory/{id}')}}";
    $(function() {
        // alert(billtoId + ' ' + billtolabel);
        // alert(leadArray);
        autoCompleteProSubCategory(leadArray);

        AutoCompleteDefaultVal('', billtolabel)

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
                    // $("#prosubcat_id").html(result);
                    $("#prosubcatId").val('');
                    autoCompleteProSubCategory(result);
                });
            }
        });

    });

    function autoCompleteProSubCategory(result) {
        $("#prosubcatId").autocomplete({
            appendTo: $("#projectDiv"),
            source: eval("(" + result + ")"), //countries_starting_with_A
            minLength: 1,
            select: function(event, ui) {
                // alert("Id : " + ui.item.id + " Label : " + ui.item.label); //+ " Value : " + ui.item.value
                $("#billtoId").val(ui.item.id);
                $("#billtolabel").val(ui.item.label);
                // $("#billtovalue").val(ui.item.value);
            },
            open: function(event, ui) {
                var len = $('.ui-autocomplete > li').length;
            },
            close: function(event, ui) {},
            change: function(event, ui) {
                if (ui.item === null) {
                    $(this).val('');
                }
            }
        }).autocomplete("instance")._renderItem = function(ul, item) {
            return $("<li>")
                .append("<div>" + item.label + "</div>") //   "<br>" + item.value +
                .appendTo(ul);
        };
        //        });

        // mustMatch (no value) implementation
        $("#prosubcatId").blur();
    }


    function AutoCompleteDefaultVal(labelTextBox, Label) {
        // alert(labelTextBox + ' ' + Label);
        $("#prosubcatId").autocomplete("search", Label);
        var menu = $("#prosubcatId").autocomplete("widget");
        $(menu[0].children[0]).click();
        $("#prosubcatId").blur();
    }
</script>
@endsection
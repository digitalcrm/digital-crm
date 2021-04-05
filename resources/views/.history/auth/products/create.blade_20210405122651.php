@extends('layouts.adminlte-boot-4.user')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-10 pb-2">
                <h1>
                <ion-icon name="shirt-outline"></ion-icon> New Product
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
                        {{Form::open(['action'=>'ProductController@store','method'=>'Post','enctype'=>'multipart/form-data','id'=>'proCreate'])}}
                        @csrf
                        <div class="card-body">
                            <section class="row">
                                
                                    <div class="form-group row">
                                        <label for="name" class="col-md-3 col-form-label text-right">Name</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <div class="col-md-9">
                                        <input type="text" class="form-control" name="name" id="name" placeholder="" value="{{old('name')}}" required tabindex="1">
                                        @error('name')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Description</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <textarea class="form-control" name="description" id="description" rows="5" tabindex="3" required>{{old('description')}}</textarea>
                                        @error('description')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>



                                    <div class="form-group">
                                        <label for="size">Size</label>
                                        <input type="number" class="form-control" name="size" id="size" placeholder="" value="{{old('size')}}" tabindex="">
                                        @error('size')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="units">Units</label>&nbsp;
                                        <select class="form-control" id="units" name="units" tabindex="">
                                            {!!$data['unitOptions']!!}
                                        </select>
                                        @error('units')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="vendor">Brand</label>
                                        <input type="text" class="form-control" name="vendor" id="vendor" placeholder="" value="{{old('vendor')}}">
                                        @error('vendor')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="store">Store</label><br>
                                        <input type="checkbox" class="btn btn-default" name="store" id="store" tabindex="7" checked />
                                        @error('store')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                
                                <div class="col-lg-6">

                                    <div class="form-group">
                                        <label class="" for="price">Price</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <div class="">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">{!!$data['user']['currency']['html_code']!!}</strong></span>
                                                </div>
                                                <input type="number" name="price" id="price" class="form-control required" placeholder="" value="{{old('price')}}" tabindex="2" required="">
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
                                        @error('procat_id')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <!-- 
                                    <div class="form-group">
                                        <label for="prosubcat_id">Product Sub Category</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <select class="form-control" id='prosubcat_id' name="prosubcat_id" tabindex="5" required>
                                        </select>
                                        @error('prosubcat_id')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div> -->

                                    <div class="form-group">
                                        <label for="prosubcat_id">Product Sub Category</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <input type="text" class="form-control required" name="prosubcatId" id="prosubcatId" placeholder="Sub Category" value="{{old('prosubcatId')}}" required>
                                        <span class="text-danger">{{ $errors->first('prosubcatId') }}</span>
                                        <input type="hidden" name="billtoId" id="billtoId" />
                                        <input type="hidden" name="billtolabel" id="billtolabel" />
                                        <!-- <input type="hidden" name="billtovalue" id="billtovalue" /> -->
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
                                        @error('company')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="min_quantity">Minimum Order of Quantity (MOQ)</label>&nbsp;<i class="fa fa-asterisk text-danger"></i><br>
                                        <input type="text" class="form-control" name="min_quantity" id="min_quantity" placeholder="" value="{{old('min_quantity')}}">
                                        @error('min_quantity')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="picture">Product Picture</label>&nbsp;<i class="fa fa-asterisk text-danger"></i><br>
                                        <input type="file" class="btn btn-default" name="picture" id="picture" tabindex="7" required />
                                        @error('picture')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- <div class="form-group">
                                        <label for="picture">Product SlideShow Pictures</label><br>
                                        <input type="file" class="btn btn-default" name="slideshowpics[]" id="pic_2" />
                                        <span class="invalid-feedback" role="alert"><strong>{{ $errors->first('slideshowpics') }}</strong></span>
                                    </div>

                                    <div class="form-group">
                                        <input type="file" class="btn btn-default" name="slideshowpics[]" id="pic_3" tabindex="" />
                                        <span class="invalid-feedback" role="alert"><strong>{{ $errors->first('slideshowpics') }}</strong></span>
                                    </div>

                                    <div class="form-group">
                                        <input type="file" class="btn btn-default" name="slideshowpics[]" id="pic_4" tabindex="" />
                                        <span class="invalid-feedback" role="alert"><strong>{{ $errors->first('slideshowpics') }}</strong></span>
                                    </div>

                                    <div class="form-group">
                                        <input type="file" class="btn btn-default" name="slideshowpics[]" id="pic_5" tabindex="" />
                                        <span class="invalid-feedback" role="alert"><strong>{{ $errors->first('slideshowpics') }}</strong></span>
                                    </div> -->
                                    @error('slideshowpics')
                                    <!-- <div class="error">{{ $message }}</div> -->
                                    @enderror


                                    <div class="form-group">
                                        <label for="tags">Tags</label>
                                        <input type="text" class="form-control" name="tags" id="tags" placeholder="" value="{{old('tags')}}">
                                        @error('tags')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>



                                </div>
                            </section>

                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white border-top text-right pull-right">
                            <a href="{{url('/products')}}" class="btn btn-outline-secondary">Cancel</a>
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
<script>
    // var url = "{{url('admin/ajax/getUserCurrency')}}";
    var prosubcaturl = "{{url('products/ajaxgetproductsubcategory/{id}')}}";

    $(function() {

        // $("#proCreate").submit(function() {
        //     alert('form Submit');
        //     // var editor1 = document.getElementById('description').value;
        //     var desc = CKEDITOR.instances.description.getData();
        //     // alert(desc);
        //     if (desc == '') {
        //         alert('Enter product description');
        //     }
        //     return false;
        // });

        // alert(prosubcaturl);
        CKEDITOR.replace('description');

        $(".sidebar-menu li").removeClass("active");
        $("#liproducts").addClass("active");

        // $("#tags").val();


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

        $("#procat_id").change(function() {
            var catId = $(this).val();
            if (catId > 0) {
                $.get(prosubcaturl, {
                    'procat_id': catId
                }, function(result) {
                    // alert(result);
                    // $("#prosubcat_id").html(result);
                    $("#prosubcatId").val('');
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
                    $("#prosubcatId").focusout(function() {});
                });
            }
        });


    });
</script>

<script type="text/javascript">
    //<![CDATA[


    $('#tags').tagsinput({
        freeInput: true
    });

    $('#tags').on('beforeItemAdd', function(event) {
        // event.cancel: set to true to prevent the item getting added
        event.cancel = !(/^[0-9A-Za-z\.,\n]+$/.test(event.item));
    });


    //]]>
</script>

@endsection
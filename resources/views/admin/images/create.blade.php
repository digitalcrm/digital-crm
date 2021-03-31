@extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-10">
                    <h1 class="m-0 text-dark">Images</h1>
                </div>                
                <div class="col-sm-2">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="breadcrumb-item active">Images Profile</li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
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
                <div class="card card-primary">
                    <div class="card-header with-border">
                        <h3 class="card-title">
                            Create Account
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <!-- <form role="form" > -->
                    {{Form::open(['action'=>'Admin\ImagesController@store','method'=>'Post','enctype'=>'multipart/form-data'])}} 
                    @csrf
                    <div class="card-body">
                        <section class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Type</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <select id="type" name="type" class="form-control">
                                    <option value="0">Select...</option>
                                    <option value="1">Accounts</option>
                                    <option value="2">Leads</option>
                                    <option value="3">Contacts</option>
                                    <option value="4">Deals</option>
                                    <option value="5">Products</option>
                                    <option value="6">Invoice</option>
                                </select>
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                        </section>
                        <section class="col-lg-6">
                            <div class="form-group">
                                <label for="picture">Logo</label>
                                <input type="file" class="btn btn-default" name="logo" id="logo" />
                                <small>upload only PNG, Gif, Jpeg </small>
                                <span class="text-danger">{{ $errors->first('logo') }}</span>
                            </div>
                        </section>
                        <!-- Left col -->


                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top">
                        <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
                        {{Form::submit('Save',['class'=>"btn btn-primary float-right"])}}
                        <a href="{{url('admin/images')}}" class="btn btn-primary">Back</a>
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
    var url = "{{url('ajaxwebtolead/getStateoptions')}}";
    $(function() {
        $(".sidebar-menu li").removeClass("menu-open");
        $(".sidebar-menu li").removeClass("active");
        $("#ulaccounts").addClass('menu-open');
        $("#ulaccounts ul").css('display', 'block');
        $("#licreateaccount").addClass("active");

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
                $.get(url, {'country': country}, function(result, status) {
                    // alert(result);
                    $("#state").html(result);
                });
            }
        });

    });
</script>
@endsection
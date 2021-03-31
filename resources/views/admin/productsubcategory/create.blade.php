@extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-md-10 float-left">
                <h1 class="pb-2">
                    New Product Sub Category
                    <!-- <small>Control panel</small> -->
                </h1>
            </div>
            <div class="col-md-2 float-right">

            </div>
        </div>
    </section>




    <!-- Main content -->
    <section class="content mt-2 mx-0">
        <div class="container-fluid">
            <!-- Small cardes (Stat card) -->
            <div class="row">


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
                <div class="col-lg-6">

                    <!-- general form elements -->
                    <!-- Horizontal Form -->
                    <div class="card shadow card-primary card-outline">

                        <!-- /.card-header -->
                        <!-- form start -->
                        {{Form::open(['action'=>'Admin\ProductSubCategoryController@store','method'=>'Post','class' => 'form-horizontal'])}}
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="category" class="control-label">Product Sub Category</label>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="category" id="category" placeholder="Product Sub Category" value="{{old('category')}}" required>
                                        <span class="text-danger">{{ $errors->first('category') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="procat_id" class="control-label">Product Category</label>
                                    </div>
                                    <div class="col-md-12">
                                        <select class="form-control" name="procat_id" id="procat_id" required>
                                            {!!$data!!}
                                        </select>
                                        <span class="text-danger">{{ $errors->first('procat_id') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white border-top">
                            {{Form::submit('Create',['class'=>"btn btn-primary float-right"])}}
                            <a href="<?php echo url('admin/productsubcategorys'); ?>" class="btn btn-outline-secondary">Back</a>

                        </div>
                        <!-- /.card-footer -->
                        <!--</form>-->
                        {{Form::close()}}
                    </div>
                    <!-- /.card -->

                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->

        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    $(function() {

        $(".sidebar-menu li").removeClass("active");
        $("#lisettings").addClass("active");
    });
</script>
@endsection
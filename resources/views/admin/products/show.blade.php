@extends('layouts.adminlte-boot-4.admin')
@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Products Show</h1>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    @if(session('success'))
    <div class='alert alert-success'>
        {{session('success')}}
    </div>
    @endif

    @if(session('error'))
    <div class='alert alert-success'>
        {{session('error')}}
    </div>
    @endif

    <!-- Main content -->
    <section class="content mt-2 mx-0">
        <div class="container-fluid">
            <!-- Small cardes (Stat card) -->
            <div class="row">
                <div class="col-lg-6">
                    <!-- general form elements -->

                    <div class="card shadow card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title float-left">
                                <?php echo $data['product']->name; ?>
                            </h3>

                        </div>
                        <div class="card-body">
                            <section class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <p>{{$data['product']->name}}</p>
                                    </div>

                                    <div class="form-group">
                                        <label for="size">Picture</label>
                                        <p><img src="<?php echo ($data['product']->picture != '') ? url($data['product']->picture) : url('/uploads/default/products.jpg'); ?>" height="50" width="50" />&nbsp;</p>
                                    </div>

                                    <div class="form-group">
                                        <label for="size">Size</label>
                                        <p>{{$data['product']->size.' '.(($data['unit']!='')?$data['unit']->sortname:'')}}</p>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>&nbsp;
                                        <p>{{$data['product']->description}}</p>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Vendor</label>
                                        <p>{{$data['product']->vendor}}</p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="price">Price</label>&nbsp;
                                        <div class="input-group">
                                            <p><span>{!!$data['user']['currency']['html_code']!!}</span>&nbsp;{{$data['product']->price}}</p>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="units">Units</label>&nbsp;
                                        <p>{{(($data['unit']!='')?$data['unit']->name:'')}}</p>
                                    </div>
                                    <div class="form-group">
                                        <label for="size">Product Category</label>
                                        <p>{{(($data['product']['tbl_productcategory'] != '')?$data['product']['tbl_productcategory']->category:'')}}</p>
                                    </div>
                                    <div class="form-group">
                                        <label for="size">Product Sub Category</label>
                                        <p>{{(($data['product']['tbl_product_subcategory'] != '')?$data['product']['tbl_product_subcategory']->category:'')}}</p>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Tags</label>
                                        <p>{{$data['product']->tags}}</p>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <div class="card-footer bg-white border-top text-right pull-right">
                            <a href="{{url('admin/products')}}" class="btn btn-outline-secondary">Back</a>
                            <button href="#" class="btn text-danger btn-outline-secondary"><i class="far fa-trash-alt"></i></button>
                            <a href="<?php echo url('admin/products/' . $data['product']->pro_id . '/edit'); ?>" class="btn btn-primary">Edit</a>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- ./col -->
                <div class="col-lg-6">
                    <div class="card shadow card-primary card-outline">
                        <div class="card-header with-border">
                            <h3 class="card-title">
                                Product Leads &nbsp;<small class="badge badge-secondary">{!!$data['total']!!}</small>
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            {!!$data['table']!!}
                        </div>
                        <div class="card-footer bg-white pull-right text-right">
                        <a href="{{url('admin/products')}}" class="btn btn-outline-secondary">Back</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <section class="col-lg-10">

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
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#liproducts").addClass("active");
    });
</script>
@endsection
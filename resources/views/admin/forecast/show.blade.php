@extends('layouts.adminlte-boot-4.admin')
@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Show Forecast</h1>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- Main content -->
    <section class="content mt-2 mx-0">
	<div class="container-fluid">
        <!-- Small cardes (Stat card) -->
        <div class="row">
            <div class="col-lg-10">
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
                <!-- general form elements -->

                <div class="card card-primary">
                    <div class="card-header with-border">
                        <h3 class="card-title">
                            <?php echo $data['product']->name; ?>
                        </h3>
                        <a href="<?php echo url('products/' . $data['product']->pro_id . '/edit'); ?>" class="btn btn-primary float-right">Edit</a>
                    </div>
                    <div class="card-body">
                        <section class="row">
                            <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Name</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <p>{{$data['product']->name}}</p>
                            </div>
                            <div class="form-group">
                                <label for="price">Price</label>&nbsp;<span>({!!$data['user']['currency']['html_code']!!})</span>
                                <div class="input-group">
                                    <p>{{$data['product']->price}}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="units">Units</label>&nbsp;
                                <p>{{$data['unit']}}</p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="description">Description</label>&nbsp;
                                <p>{{$data['product']->description}}</p>
                            </div>
                            <div class="form-group">
                                <label for="size">Size</label>
                                <p>{{$data['product']->size}}</p>
                            </div>
                            <div class="form-group">
                                <label for="size">Picture</label>
                                <p><img src="<?php echo ($data['product']->picture != "") ? url($data['product']->picture) : ''; ?>" height="50" width="50" />&nbsp;</p>
                            </div>
                        </div>
                        </section>
                    </div>
                    <div class="card-footer bg-white border-top">
                        <!--                        <a href="#" class="btn btn-danger float-left`">Delete</a>&nbsp;-->
                        <a href="{{url('products')}}" class="btn btn-primary float-right">Back</a>
                    </div>
                </div>

                <!-- /.card -->

            </div>
            <!-- ./col -->
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
        $(".sidebar-menu li").removeClass("menu-open");
        $(".sidebar-menu li").removeClass("active");
        $("#ulaccounts").addClass('menu-open');
        $("#ulaccounts ul").css('display', 'block');
        // $("#licreatelead").addClass("active");
    });


</script>
@endsection
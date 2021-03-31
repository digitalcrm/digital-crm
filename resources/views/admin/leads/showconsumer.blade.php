@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-10">
                    <h1 class="m-0 text-dark">Consumer Profile</h1>
                </div>
                <div class="col-sm-2">

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
                <div class="col-lg-6">
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
                    <div class="card shadow card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <!-- <img src="<?php echo ($data['userdata']['picture'] != null) ? url($data['userdata']['picture']) : url('uploads/default/user.png'); ?>" height="25" width="25" />
                                {{$data['userdata']['name']}} -->
                            </h3>
                        </div>
                        <div class="card-body">

                            <div class="form-group">
                                <label for="title">Name</label>
                                <p>{!!$data['userdata']['name']!!}</p>
                            </div>

                            <div class="form-group">
                                <label for="posturl">Email</label>
                                <p>{!!$data['userdata']['email']!!}</p>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white border-top text-right pull-right">
                            <a href="{{url('admin/ecommerce/consumers/list')}}" class="btn btn btn-outline-secondary">Back</a>
                            <!-- <a href="<?php //echo url('admin/admins/edit/' . $data['userdata']['id']) 
                                            ?>" class="btn btn-primary">Edit</a> -->
                        </div>
                        <!-- /.card-footer -->
                        </form>
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
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#ulusers").addClass('menu-open');
        $("#ulusers ul").css('display', 'block');
        $("#liusers").addClass("active");
    });
</script>
@endsection
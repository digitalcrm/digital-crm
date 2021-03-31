@extends('layouts.adminlte-boot-4.user')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 mt-1">
                    <h1>Events <small class="badge badge-secondary">{{$data['total']}}</small></h1>
                </div>
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

                @if(session('info'))
                <div class='alert alert-warning'>
                    {{session('info')}}
                </div>
                @endif
                <div class="card shadow">

                    <div class="card-body p-0">
                        {!!$data['table']!!}
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top pull-left">
                        <a href="<?php echo url('trash'); ?>" class="btn btn-sm btn-outline-secondary"><i class="nav-icon fas fa-long-arrow-alt-left"></i></a>
                    </div>
                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->
                <div id="resulttt">

                </div>
            </div>
        </div>
        <!-- /.row -->
	</div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>

    $(function() {

        // alert('active');
        $(".sidebar-menu li").removeClass("active");
        $("#litrash").addClass("active");
    });



</script>
@endsection
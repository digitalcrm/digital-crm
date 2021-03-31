@extends('layouts.adminlte-boot-4.consumer')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Cart</h1>
                </div>
                <!-- <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Blank Page</li>
                    </ol>
                </div> -->
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Products</h3>
            </div>
            <div class="card-body">
                {!!$data['cartDiv']!!}
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <div class="btn-group">
                    <a href="{{url('consumers/dashboard')}}" class="btn btn-default">Back</a>
                    <!-- <button type="button" class="btn btn-default">Middle</button>
                    <button type="button" class="btn btn-default">Right</button> -->
                </div>
            </div>
            <!-- /.card-footer-->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
@extends('layouts.adminlte-boot-4.user')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Customers <small class="badge badge-secondary">{{$data['total']}}</small></h1>
                </div>
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('customers/import/csv')}}"><i class="fas fa-upload"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('customers/export/csv')}}"><i class="fas fa-download"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('reports/customers')}}"><i class="fa fa-chart-pie"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" target="_blank" href="{{url('customers/print/'.Auth::user()->id)}}"><i class="fa fa-print"></i></a>
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content mx-0">
        <div class="container-fluid">
            <!-- Small cardes (Stat card) -->
            <div class="row">
                <div class="col-lg-12 p-0">
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

                    @if(session('info'))
                    <div class='alert alert-warning'>
                        {{session('info')}}
                    </div>
                    @endif
                    <div class="card shadow card-primary card-outline">

                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            {!!$data['table']!!}
                        </div>
                        <!-- /.card-body -->
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

        $(".sidebar-menu li").removeClass("active");
        $("#licustomers").addClass("active");

    });

</script>
@endsection
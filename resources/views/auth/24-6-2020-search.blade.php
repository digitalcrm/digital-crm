@extends('layouts.adminlte-boot-4.user')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Search Results<small class="badge badge-secondary">{{$data['total']}}</small></h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content mt-2 mx-0">
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
                <div class="card">

                    <div class="card-body p-0">
                        {!!$data['table']!!}
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    var searchTerm = '{{$data["search"]}}';
    $(function() {
//        alert(searchTerm);
        $("#searchTerm").val(searchTerm);
        // alert('active');
        $(".sidebar-menu li").removeClass("active");
//        $("#liaccounts").addClass("active");
    });



</script>
@endsection
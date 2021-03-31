@extends('layouts.adminlte-boot-4.user')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Leads <small class="badge badge-secondary">{{$data['total']}}</small></h1>
                </div>

            </div>
        </div><!-- /.container-fluid -->
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
                <div class="card shadow card-primary card-outline">
                    <div class="card-body p-0">
                        {!!$data['table']!!}
                    </div>
                    <div class="card-footer bg-white border-top pull-right text-right">
                        <a href="<?php echo url('subusers/view/' . $data['user']); ?>" class="btn btn-outline-secondary">Back</a>
                    </div>
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
        $("#lileads").addClass("active");
    });

</script>
@endsection
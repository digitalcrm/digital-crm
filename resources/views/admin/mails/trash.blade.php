@extends('layouts.adminlte-boot-4.admin')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Mails</h1>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- Main content -->
    <section class="content mt-2 mx-0">
	<div class="container-fluid">
        <div class="row">
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
            <div class="col-md-2">
                <!--<a href="{{url('mails/create')}}" class="btn btn-primary btn-block margin-bottom">Compose</a>-->

                <div class="card card-solid">
                    <div class="card-body no-padding">
                        <ul class="nav nav-pills nav-stacked">
<!--                            <li><a href="{{url('admin/mails')}}"><i class="fa fa-inbox"></i> Inbox
                                    <span class="label label-primary float-right">12</span></a></li>-->
                            <li><a href="{{url('admin/mails')}}"><i class="fa fa-envelope"></i> Sent</a></li>
                            <li class="active"><a href="{{url('admin/mails/trash/deletedmails')}}"><i class="fa fa-trash"></i> Trash</a></li>
                        </ul>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /. card -->

            </div>
            <!-- /.col -->
            <div class="col-md-10">
                <div class="card card-primary">
                    <div class="card-header with-border">
                        <h3 class="card-title">Trash</h3>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        {!!$data['table']!!}
                        <!-- /.table -->
                    </div>
                    <!-- /.card-body -->

                </div>
                <!-- /. card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
	</div>
    </section>
    <!-- /.content -->

</div>
<!-- /.content-wrapper -->

<script>

    $(function() {
//        alert('hi');
        $(".sidebar-menu li").removeClass("active");
        $("#limail").addClass("active");


    });



</script>
@endsection
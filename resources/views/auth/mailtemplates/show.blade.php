@extends('layouts.adminlte-boot-4.user')
@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1><?php echo $data['name']; ?> Template</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
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
            <div class='alert alert-success'>
                {{session('error')}}
            </div>
            @endif
            <!-- general form elements -->
            <div class="col-md-3">
                <!-- Profile Image -->
                <div class="card shadow card-primary card-outline">

                    <div class="card-body card-profile">

                        <h3 class="profile-username text-center"><?php echo $data['name']; ?></h3>
                        <strong>Subject</strong>
                        <p class="text-muted"><?php echo $data['subject']; ?></p>
                        <hr>

                        <strong>Message</strong>
                        <p class="text-muted"><?php echo $data['message']; ?></p>
                        <hr>

                        <a href="{{$data['temp_id'].'/edit'}}" class="btn btn-primary btn-block"><b>Edit</b></a>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
                <!-- About Me Box -->

            </div>
            <div class="col-md-3">


                <div class="card shadow card-primary card-outline">
                    <div class="card-header with-border">
                        <h3 class="card-title">
                            Template Involved &nbsp;<small class="badge badge-secondary">
                                <!--{!!$data['total']!!}-->
                            </small>
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <!--                        {!!$data['table']!!}-->
                    </div>
                    <div class="card-footer bg-white pull-right text-right">
                        <a href="{{url('/mailtemplates')}}" class="btn btn-outline-secondary"><i class="nav-icon fas fa-long-arrow-alt-left"></i></a>
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
//        $(".sidebar-menu li").removeClass("active");
//        $("#ulaccounts").addClass('menu-open');
//        $("#ulaccounts ul").css('display', 'block');
        // $("#licreatelead").addClass("active");
    });


</script>
@endsection
@extends('layouts.adminlte-boot-4.user')
@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Form Lead</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
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
                    <div class="card-header btn-flat with-border">
                        <h3 class="card-title">{!!$data['formleads']->first_name!!}</h3>

                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="title">Name</label>
                            <p>{!!$data['formleads']->first_name!!}</p>
                        </div>
                        <div class="form-group">
                            <label for="posturl">Email</label>
                            <p>{!!$data['formleads']->email!!}</p>
                        </div>
                        <div class="form-group">
                            <label for="redirecturl">Mobile</label>
                            <p>{!!$data['formleads']->mobile!!}</p>
                        </div>
                        <div class="form-group">
                            <label for="frommail">Website</label>
                            <p>{!!$data['formleads']->website!!}</p>
                        </div>
                        <div class="form-group">
                            <label for="message">Notes</label>
                            <p>{!!$data['formleads']->notes!!}</p>
                        </div>
                    </div>
                    <div class="card-footer bg-white pull-right text-right">
                    <div class="btn-group btn-flat pull-left">
                        <a href="{{url('webtolead/formleads/'.$data['form']->form_id)}}" class="btn btn-sm btn-outline-secondary"><i class="nav-icon fas fa-long-arrow-alt-left"></i></a>
                        <a href="{{$data['addtoleadLink']}}" class="btn btn-sm btn-outline-secondary">{!!$data['addtoleadbutton']!!}</a>
                        <button onclick="#" class="btn btn-sm btn-outline-secondary"><i class="far fa-trash-alt text-danger"></i></button>
                    </div>
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
        $("#ulwebtolead").addClass('menu-open');
        $("#ulwebtolead ul").css('display', 'block');
        $("#liwebtolead").addClass("active");
    });


</script>
@endsection
@extends('layouts.adminlte-boot-4.user')
@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {!!$data['form']->title!!}
            <!-- <small>Control panel</small> -->
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Create Web to Lead</li>
        </ol>
    </section>



    <!-- Main content -->
    <section class="content mt-2 mx-0">
	<div class="container-fluid">
        <!-- Small boxes (Stat box) -->
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
                    <div class="card-header with-border">
                        <h3 class="card-title">{!!$data['formleads']->first_name!!}</h3>
                        <a href="{{$data['addtoleadLink']}}" class="btn btn-primary pull-right">{!!$data['addtoleadbutton']!!}</a>
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
                    <div class="card-footer bg-white border-top pull-right text-right">
                        <a href="#" class="btn text-danger pull-right">Delete</a>
                        <a href="{{url('webtolead/formleads/'.$data['form']->form_id)}}" class="btn text-outline-secondary pull-right">Back</a>
                    </div>
                </div>

                <!-- /.box -->

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
    $(function () {
        $(".sidebar-menu li").removeClass("menu-open");
        $(".sidebar-menu li").removeClass("active");
        $("#ulwebtolead").addClass('menu-open');
        $("#ulwebtolead ul").css('display', 'block');
        $("#liwebtolead").addClass("active");
    });


</script>
@endsection

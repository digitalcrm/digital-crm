@extends('layouts.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
<!--    <section class="content-header">
        <h1>
            Create Web to Lead Form
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Create Web to Lead</li>
        </ol>
    </section>-->

    <!-- Main content -->
    <section class="content mt-2 mx-0">
	<div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-12">
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
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            Create Form
                        </h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <!-- <form role="form" > -->
                    {{Form::open(['action'=>'WebtoleadController@store','method'=>'Post'])}}
                    <!-- {{csrf_field()}} -->
                    <div class="box-body">
                        <div class="form-group">
                            <label for="title">Title</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                            <input type="text" class="form-control" name="title" id="title" placeholder="" value="{{old('title')}}">
                            <span class="text-danger">{{ $errors->first('title') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="from_mail">From Mail</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                            <input type="text" class="form-control" name="from_mail" id="from_mail" placeholder="" value="{{old('from_mail')}}">
                            <span class="text-danger">{{ $errors->first('from_mail') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="post_url">Post Url</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                            <input type="text" class="form-control" name="post_url" id="post_url" placeholder="" value="{{old('post_url')}}">
                            <span class="text-danger">{{ $errors->first('post_url') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="redirect_url">Redirect Url</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                            <input type="text" class="form-control" name="redirect_url" id="redirect_url" placeholder="" value="{{old('redirect_url')}}">
                            <span class="text-danger">{{ $errors->first('redirect_url') }}</span>
                        </div>
                        <div class="form-group">
                            <div class="box-header with-border">
                                <h3 class="box-title">Google Catcha</h3>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="post_url">Site Key</label>&nbsp;
                            <!--<i class="fa fa-asterisk text-danger"></i>-->
                            <input type="text" class="form-control" name="site_key" id="site_key" placeholder="" value="{{old('site_key')}}">
                            <span class="text-danger">{{ $errors->first('site_key') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="redirect_url">Secret Key</label>&nbsp;
                            <!--<i class="fa fa-asterisk text-danger"></i>-->
                            <input type="text" class="form-control" name="secret_key" id="secret_key" placeholder="" value="{{old('secret_key')}}">
                            <span class="text-danger">{{ $errors->first('secret_key') }}</span>
                        </div>
                        <div class="form-group">
                            <div class="box-header with-border">
                                <h3 class="box-title">Autoresponder</h3>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                            <input type="text" class="form-control" name="subject" id="subject" placeholder="" value="{{old('subject')}}"/>
                            <span class="text-danger">{{ $errors->first('subject') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                            <textarea name="message" id="message" class="form-control" placeholder="" rows="5"></textarea>
                            <span class="text-danger">{{ $errors->first('message') }}</span>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{{url('/webtolead')}}" class="btn btn-primary pull-right">Back</a> 
                        {{Form::submit('Save',['class'=>"btn btn-primary"])}}
                    </div>
                    <!-- </form> -->
                    {{Form::close()}}
                </div>
                <!-- /.box -->
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
        $(".sidebar-menu li").removeClass("menu-open");
        $(".sidebar-menu li").removeClass("active");
        $("#ulwebtolead").addClass('menu-open');
        $("#ulwebtolead ul").css('display', 'block');
        $("#licreateform").addClass("active");
    });
</script>
@endsection
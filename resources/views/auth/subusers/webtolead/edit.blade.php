@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

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
                            {{$form->title}}
                        </h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <!-- <form role="form" > -->
                    {{Form::open(['action'=>['WebtoleadController@update',$form->form_id],'method'=>'Post'])}}
                    <!-- {{csrf_field()}} -->
                    <div class="box-body">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" id="title" placeholder="Enter Form Title" value="{{$form->title}}">
                            <span class="text-danger">{{ $errors->first('title') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="posturl">Post Url</label>
                            <input type="text" class="form-control" name="post_url" id="post_url" placeholder="Post Url" value="{{$form->post_url}}">
                            <span class="text-danger">{{ $errors->first('post_url') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="redirecturl">Redirect Url</label>
                            <input type="text" class="form-control" name="redirect_url" id="redirect_url" placeholder="Redirect Url" value="{{$form->redirect_url}}">
                            <span class="text-danger">{{ $errors->first('redirect_url') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="frommail">From Mail</label>
                            <input type="text" class="form-control" name="from_mail" id="from_mail" placeholder="From Mail Id" value="{{$form->from_mail}}">
                            <span class="text-danger">{{ $errors->first('from_mail') }}</span>
                        </div>
                        <div class="form-group">
                            <div class="box-header with-border">
                                <h3 class="box-title">Google Catcha</h3>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="post_url">Site Key</label>&nbsp;
                            <!--<i class="fa fa-asterisk text-danger"></i>-->
                            <input type="text" class="form-control" name="site_key" id="site_key" placeholder="" value="{{$form->site_key}}">
                            <span class="text-danger">{{ $errors->first('site_key') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="redirect_url">Secret Key</label>&nbsp;
                            <!--<i class="fa fa-asterisk text-danger"></i>-->
                            <input type="text" class="form-control" name="secret_key" id="secret_key" placeholder="" value="{{$form->secret_key}}">
                            <span class="text-danger">{{ $errors->first('secret_key') }}</span>
                        </div>
                        <div class="form-group">
                            <div class="box-header with-border">
                                <h3 class="box-title">Autoresponder</h3>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" value="{{$form->subject}}">
                            <span class="text-danger">{{ $errors->first('subject') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea name="message" id="message" rows="7" class="form-control" >{{$form->message}}</textarea>
                            <span class="text-danger">{{ $errors->first('message') }}</span>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{{url('webtolead')}}" class="btn btn-default pull-right">Back</a>
                        {{Form::hidden('_method','PUT')}}
                        {{Form::submit('Save',['class'=>"btn btn-primary pull-left"])}}

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
        $("#liwebtolead").addClass("active");
    });
</script>
@endsection

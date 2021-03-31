@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
	<section class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12">
            <h1><i class="far fa-edit"></i> Edit - {{$form->title}}</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content mx-0">
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
                    <!-- /.card-header -->
                    <!-- form start -->
                    <!-- <form role="form" > -->
                    {{Form::open(['action'=>['WebtoleadController@update',$form->form_id],'method'=>'Post'])}}
                    <!-- {{csrf_field()}} -->
                    <div class="card-body">


                            <div class="form-group row row">
                                <label class="col-md-3 col-form-label text-right" for="title">Title</label>
                                <div class="col-md-9">
								<input type="text" class="form-control" name="title" id="title" placeholder="Enter Form Title" value="{{$form->title}}">
                                <span class="text-danger">{{ $errors->first('title') }}</span>
								</div>
                            </div>
							
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="posturl">Post Url</label>
                                <div class="col-md-9">
								<input type="text" class="form-control" name="post_url" id="post_url" placeholder="Post Url" value="{{$form->post_url}}">
                                <span class="text-danger">{{ $errors->first('post_url') }}</span>
								</div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="frommail">From Mail</label>
                                <div class="col-md-9">
								<input type="text" class="form-control" name="from_mail" id="from_mail" placeholder="From Mail Id" value="{{$form->from_mail}}">
                                <span class="text-danger">{{ $errors->first('from_mail') }}</span>
								</div>
                            </div> 
							
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="redirecturl">Redirect Url</label>
                                <div class="col-md-9">
								<input type="text" class="form-control" name="redirect_url" id="redirect_url" placeholder="Redirect Url" value="{{$form->redirect_url}}">
                                <span class="text-danger">{{ $errors->first('redirect_url') }}</span>
								</div>
                            </div>

									<div class="row">
									<div class="col-md-3"></div>
									<div class="col-md-9">
                                    <h3 class="badge badge-info">Auto Responder</h3>
									</div>
									</div>
									
									
                        <section class="col-lg-12">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="subject">Subject</label>
                                <div class="col-md-9">
								<input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" value="{{$form->subject}}">
                                <span class="text-danger">{{ $errors->first('subject') }}</span>
								</div>
                            </div>
                        </section>
                        <section class="col-lg-12">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="message">Message</label>
                                <div class="col-md-9">
								<textarea name="message" id="message" rows="7" class="form-control" >{{$form->message}}</textarea>
                                <span class="text-danger">{{ $errors->first('message') }}</span>
								</div>
                            </div>
                        </section>


									<div class="row">
									<div class="col-md-3"></div>
									<div class="col-md-9">
                                    <h3 class="badge badge-info">Google Captcha</h3> <small style="font-size: 12px;"><a href="https://www.google.com/recaptcha/intro/v3.html">Click here for tutorial</a></small>
									</div>
									</div>
									
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="post_url">Site Key</label>                               <!--<i class="fa fa-asterisk text-danger"></i>-->
                                <div class="col-md-9">
								<input type="text" class="form-control" name="site_key" id="site_key" placeholder="" value="{{$form->site_key}}">
                                <span class="text-danger">{{ $errors->first('site_key') }}</span>
								</div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="redirect_url">Secret Key</label>
                                <!--<i class="fa fa-asterisk text-danger"></i>-->
                                <div class="col-md-9">
								<input type="text" class="form-control" name="secret_key" id="secret_key" placeholder="" value="{{$form->secret_key}}">
                                <span class="text-danger">{{ $errors->first('secret_key') }}</span>
								</div>
                            </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white pull-right text-right">
                            <button href="{{url('webtolead')}}" class="btn btn-outline-secondary">Cancel</button>
                            {{Form::hidden('_method','PUT')}}
                            {{Form::submit('Save',['class'=>"btn btn-primary"])}}
                    </div>
                    <!-- </form> -->
                    {{Form::close()}}
                </div>
                <!-- /.card -->
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

<script src="{{asset('assets/bower_components/ckeditor/ckeditor.js')}}"></script>
<script>
$(function() {

    CKEDITOR.replace('message');

    $(".sidebar-menu li").removeClass("active");
    $("#liwebtolead").addClass("active");
});
</script>
@endsection
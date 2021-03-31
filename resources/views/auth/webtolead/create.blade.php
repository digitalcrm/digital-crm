@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1><i class="far fa-edit"></i> New Web to Lead Form</h1>
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
                    <div class='alert alert-danger'>
                        {{session('error')}}
                    </div>
                    @endif
                    <!-- general form elements -->
                    <div class="card shadow card-primary card-outline">
                        {{Form::open(['action'=>'WebtoleadController@store','method'=>'Post'])}}
                        <!-- {{csrf_field()}} -->
                        <div class="card-body">

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="title">Title</i></label>
                                        <div class="col-md-9">
										<input type="text" class="form-control required" name="title" id="title" placeholder="" value="{{old('title')}}">
                                        <span class="text-danger">{{ $errors->first('title') }}</span>
										</div>
                                    </div>
									
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="post_url">Post URL</label>
                                        <div class="col-md-9">
										<input type="text" class="form-control required" name="post_url" id="post_url" placeholder="" value="{{old('post_url')}}">
                                        <span class="text-danger">{{ $errors->first('post_url') }}</span>
										</div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="from_mail">From Mail</label>
                                        <div class="col-md-9">
										<input type="text" class="form-control required" name="from_mail" id="from_mail" placeholder="" value="{{old('from_mail')}}">
                                        <span class="text-danger">{{ $errors->first('from_mail') }}</span>
										</div>
                                    </div>
									
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="redirect_url">Redirect URL</label>
                                        <div class="col-md-9">
										<input type="text" class="form-control required" name="redirect_url" id="redirect_url" placeholder="" value="{{old('redirect_url')}}">
                                        <span class="text-danger">{{ $errors->first('redirect_url') }}</span>
										</div>
                                    </div>
									
									<div class="row">
									<div class="col-md-3"></div>
									<div class="col-md-9">
                                    <h3 class="badge badge-info">Auto Responder</h3>
									</div>
									</div>
									
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="subject">Subject</label>
                                        <div class="col-md-9">
										<input type="text" class="form-control required" name="subject" id="subject" placeholder="" value="{{old('subject')}}"/>
                                        <span class="text-danger">{{ $errors->first('subject') }}</span>
										</div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="message">Message <i class="fa fa-asterisk text-danger"></i></label>
                                        <div class="col-md-9">
										<textarea name="message" id="message" class="form-control" placeholder="" rows="5"></textarea>
                                        <span class="text-danger">{{ $errors->first('message') }}</span>
										</div>
                                    </div>
                                
									<div class="row">
									<div class="col-md-3"></div>
									<div class="col-md-9">
                                    <h3 class="badge badge-info">Google Captcha</h3> <small style="font-size: 12px;"><a href="https://www.google.com/recaptcha/intro/v3.html">Click here for tutorial</a></small>
									</div>
									</div>                            
							
							
								
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="site_key">Site Key</label>
                                        <div class="col-md-9">
										<input type="text" class="form-control" name="site_key" id="site_key" placeholder="" value="{{old('site_key')}}">
                                        <span class="text-danger">{{ $errors->first('site_key') }}</span>
										</div>
                                    </div>								
                                    
									<div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="secret_key">Secret Key</label>
                                        <div class="col-md-9">
										<input type="text" class="form-control" name="secret_key" id="secret_key" placeholder="" value="{{old('secret_key')}}">
                                        <span class="text-danger">{{ $errors->first('secret_key') }}</span>
										</div>
                                    </div>								
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white text-right pull-right">
                            <a href="{{url('/webtolead')}}" class="btn btn-outline-secondary mr-2">Cancel</a> 
                            {{Form::submit('Create',['class'=>"btn btn-primary"])}}
                        </div>
                        <!-- </form> -->
                        {{Form::close()}}
                    </div>
                    <!-- /.card -->
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script src="{{asset('assets/bower_components/ckeditor/ckeditor.js')}}"></script>
<script>
$(function() {
    $(".sidebar-menu li").removeClass("active");
    $("#liwebtolead").addClass("active");

    CKEDITOR.replace('message');
});
</script>
@endsection
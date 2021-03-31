@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1><i class="far fa-edit"></i> Edit Template</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content mt-2 mx-0"><div class="container-fluid">
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
                    <!-- /.card-header -->

                    {{Form::open(['action'=>['MailTemplateController@update',$data['temp_id']],'method'=>'Post','enctype'=>'multipart/form-data'])}}
                    @csrf
                    <div class="card-body">
                        <!-- Left col -->
                        <section class="col-lg-12">
                            <div class="form-group">
                                <label for="name">Template Name</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <input type="text" class="form-control" name="name" id="templatename" placeholder="" value="{{$data['name']}}" required>
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="subject">Subject</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <input type="text" class="form-control" name="subject" id="subject" placeholder="" value="{{$data['subject']}}" required>
                                <span class="text-danger">{{ $errors->first('subject') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="message">Message</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <textarea name="message" id="message" class="form-control" rows="5" required>{{strip_tags($data['message'])}}</textarea>
                                <span class="text-danger">{{ $errors->first('message') }}</span>
                            </div>
                        </section>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white pull-right text-right">
                        <a href="{{url('/mailtemplates')}}" class="btn btn-outline-secondary mr-1">Cancel</a>
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
	</div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script src="{{asset('assets/bower_components/ckeditor/ckeditor.js')}}"></script>
<script>
//    var url = "{{url('ajaxwebtolead/getStateoptions')}}";
$(function() {
    $(".sidebar-menu li").removeClass("active");
//        $("#licreateaccount").addClass("active");

    CKEDITOR.replace('message');

});
</script>
@endsection
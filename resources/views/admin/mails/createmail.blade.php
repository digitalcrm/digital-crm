@extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">New Mail</h1>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- Main content -->
    <section class="content mt-2 mx-0">
        <div class="container-fluid">
            <!-- Small cardes (Stat card) -->
            <div class="row">
                <div class="col-lg-8">
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
                        <!-- form start -->
                        <!-- <form role="form" > -->
                        {{Form::open(['action'=>'Admin\MailController@mailSendAction','method'=>'Post','enctype'=>'multipart/form-data'])}}
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="user"><?php echo $data['typehead']; ?></label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <input type="text" class="form-control" name="user" id="user" placeholder="" value="<?php echo $data['name'] . ' (' . $data['email'] . ')'; ?>" readonly="">
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                            <!--
                        <div class="form-group">
                            <label for="leads">Leads</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                            <input type="text" class="form-control" name="leads" id="leads" placeholder="" value="{{old('leads')}}" required>
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        </div>
                        -->
                            <div class="form-group">
                                <label for="subject">Subject</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <input type="text" class="form-control" name="subject" id="subject" placeholder="" value="{{old('subject')}}" required>
                                <span class="text-danger">{{ $errors->first('subject') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="message">Message</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <textarea class="form-control" name="message" id="message">{{old('Message')}}</textarea>
                                <span class="text-danger">{{ $errors->first('message') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="attachment">Attachment</label>
                                <input type="file" class="btn btn-default" name="attachment" id="attachment" />
                                <span class="text-danger">{{ $errors->first('attachment') }}</span>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white border-top text-right pull-right">
                            <input type="hidden" value="<?php echo $data['type']; ?>" name="userType" id="userType">
                            <input type="hidden" value="<?php echo $data['id']; ?>" name="userId" id="userId">
                            <input type="hidden" value="<?php echo $data['email']; ?>" name="userEmail" id="userEmail">
                            <input type="hidden" value="<?php echo $data['name']; ?>" name="userName" id="userName">
                            <input type="hidden" value="<?php echo $data['uid']; ?>" name="uid" id="uid">
                            <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
                            <a href="{{ url('admin/dashboard') }}" class="btn btn-outline-secondary">Back</a>
                            {{Form::submit('Send',['class'=>"btn btn-primary"])}}
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
    var url = "{{url('ajaxwebtolead/getStateoptions')}}";
    $(function() {
        CKEDITOR.replace('message');

        $(".sidebar-menu li").removeClass("active");



    });
</script>
@endsection
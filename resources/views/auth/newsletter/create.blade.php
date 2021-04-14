@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
<!--    <section class="content-header">
        <h1>
            Create Account
             <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Create Account</li>
        </ol>
    </section>-->

    <!-- Main content -->
    <section class="content mt-2 mx-0"><div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-12">
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
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="far fa-edit"></i> New Newsletter
                        </h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <!-- <form role="form" > -->
                    {{Form::open(['action'=>'NewsletterController@store','method'=>'Post','enctype'=>'multipart/form-data'])}}
                    @csrf
                    <div class="box-body">
                        <!-- Left col -->
                        <div class="form-group">
                            <label for="category">Category</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                            <select class="form-control" name="category" id="category">
                                <option value="">Select Category</option>
                                <option value="1">Accounts</option>
                                <option value="2">Contacts</option>
                                <option value="3">Leads</option>
                            </select>
                            <span class="text-danger">{{ $errors->first('category') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="title">Title</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                            <input type="text" class="form-control" name="title" id="title" placeholder="" value="{{old('title')}}" >
                            <span class="text-danger">{{ $errors->first('title') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                            <input type="text" class="form-control" name="subject" id="subject" placeholder="" value="{{old('subject')}}" >
                            <span class="text-danger">{{ $errors->first('subject') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                            <textarea class="form-control" name="message" id="message">{{old('Message')}}</textarea>
                            <span class="text-danger">{{ $errors->first('message') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="document">Document</label>
                            <input type="file" class="btn btn-default" name="document" id="document" />
                            <span class="text-danger">{{ $errors->first('document') }}</span>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <div class="btn-group btn-flat pull-right">
                            <a href="{{url('/newsletter')}}" class="btn btn-default">Back</a>
                            {{Form::submit('Create',['class'=>"btn btn-primary"])}}
                        </div>


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
<script src="{{asset('assets/bower_components/ckeditor/ckeditor.js')}}"></script>
<script>
var url = "{{url('ajaxwebtolead/getStateoptions')}}";
$(function() {
    CKEDITOR.replace('message');

    $(".sidebar-menu li").removeClass("menu-open");
    $(".sidebar-menu li").removeClass("active");
    $("#ulaccounts").addClass('menu-open');
    $("#ulaccounts ul").css('display', 'block');
    $("#licreateaccount").addClass("active");

    $("#account").change(function() {
        var acc = $(this).val();
        if (acc == "NewAccount") {
            $("#addAccount").show();
        } else {
            $("#addAccount").hide();
        }
    });

    $("#country").change(function() {
        var country = $(this).val();
        // alert(country);
        if (country > 0) {
            $.get(url, {'country': country}, function(result, status) {
                // alert(result);
                $("#state").html(result);
            });
        }
    });

});
</script>
@endsection

@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1><i class="far fa-edit"></i> New Mail</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

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

                    {{Form::open(['action'=>'MailController@mailSendAction','method'=>'Post','enctype'=>'multipart/form-data'])}} 
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="user"><?php echo $data['typehead']; ?></label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                            <input type="text" class="form-control" name="user" id="user" placeholder="" value="<?php echo $data['name'] . ' (' . $data['email'] . ')'; ?>" readonly="">
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        </div>

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
                    <div class="card-footer bg-white pull-right text-right">
                    <a href="{{url('/mails')}}" class="btn btn-outline-secondary mr-1"><i class="nav-icon fas fa-long-arrow-alt-left"></i></a>
                        <input type="hidden" value="<?php echo $data['type']; ?>" name="userType" id="userType">
                        <input type="hidden" value="<?php echo $data['id']; ?>" name="userId" id="userId">
                        <input type="hidden" value="<?php echo $data['email']; ?>" name="userEmail" id="userEmail">
                        <input type="hidden" value="<?php echo $data['name']; ?>" name="userName" id="userName">
                        <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
                        {{Form::submit('Send',['class'=>"btn btn-primary pull-right"])}}
                        
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

    $("#select2").on("select2:unselect", function(e) {
        var data = e.params.data.id;
        alert(data);

        var y = [1, 2, 3, 2, 2, 4, 7, 8]
        var removeItem = 2;

        alert('Array before removing the element = ' + y);
        y = jQuery.grep(y, function(value) {
            return value != removeItem;
        });
        alert('Array after removing the element = ' + y);
    });

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
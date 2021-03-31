@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1><i class="far fa-edit"></i> New Sub User</h1>
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
                    {{Form::open(['action'=>'SubuserController@store','method'=>'Post','enctype'=>'multipart/form-data'])}}
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="name">Name</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                    <input id="password" type="password" class="form-control" name="password" required>
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="email">E-Mail</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="password-confirm">Confirm Password</label>
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                    <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white pull-right text-right">
                        <a href="{{url('/subusers')}}" class="btn btn-outline-secondary">Back</a>
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

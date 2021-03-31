@extends('layouts.user')
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
                <div class='alert alert-danger'>
                    {{session('error')}}
                </div>
                @endif

                @if(session('warning'))
                <div class='alert alert-warning'>
                    {{session('warning')}}
                </div>
                @endif
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="far fa-edit"></i> Create SMTP Settings
                        </h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <!-- <form role="form" > -->
                    {{Form::open(['action'=>'SmtpController@store','method'=>'Post','enctype'=>'multipart/form-data'])}} 
                    @csrf
                    <div class="box-body">
                        <div class="form-group">
                            <label for="username" class="control-label">Username</label>
                            <input type="email" class="form-control" name="username" id="username" placeholder="Username" required="">
                            <span class="text-danger">{{ $errors->first('username') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="password" class="control-label">Password</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Password" required="">
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="incomingserver" class="control-label">Incoming Server</label>
                            <input type="text" class="form-control" name="incomingserver" id="incomingserver" placeholder="Incoming Server" required="">
                            <span class="text-danger">{{ $errors->first('incomingserver') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="incomingport" class="control-label">Incoming Server Imap Port</label>
                            <input type="text" class="form-control" name="incomingport" id="incomingport" placeholder="Incoming Server Imap Port" required="">
                            <span class="text-danger">{{ $errors->first('incomingport') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="outgoingserver" class="control-label">Outgoing Server</label>
                            <input type="text" class="form-control" name="outgoingserver" id="outgoingserver" placeholder="Outgoing Server" required="">
                            <span class="text-danger">{{ $errors->first('outgoingserver') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="outgoingport" class="control-label">Outgoing Server Smtp Port</label>
                            <input type="text" class="form-control" name="outgoingport" id="outgoingport" placeholder="Outgoing Server Smtp Port" required="">
                            <span class="text-danger">{{ $errors->first('outgoingport') }}</span>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <div class="btn-group btn-flat pull-right">
                            <a href="{{url('/home')}}" class="btn btn-default">Back</a>
                            {{Form::submit('Save',['class'=>"btn btn-primary"])}}
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
	</div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    $(function() {
        $(".sidebar-menu li").removeClass("active");
    });
</script>
@endsection
@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
<!--    <section class="content-header">
        <h1>
            Edit Lead
             <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Edit Lead</li>
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
                <div class='alert alert-danger'>
                    {{session('error')}}
                </div>
                @endif
                <!-- general form elements -->
                <div class="box box-primary">
                    <!-- /.box-header -->
                    <!-- form start -->
                    <!-- <form role="form" > -->
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="far fa-edit"></i> Edit SMTP Settings
                        </h3>
                    </div>
                    {{Form::open(['action'=>['SmtpController@update',$data['ss_id']],'method'=>'Post','enctype'=>'multipart/form-data'])}}
                    @csrf
                    <div class="box-body">
                        <div class="form-group">
                            <label for="username" class="control-label">Username</label>
                            <input type="email" class="form-control" name="username" id="username" placeholder="Username" required="" value="{{$data['username']}}">
                            <span class="text-danger">{{ $errors->first('username') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="password" class="control-label">Password</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Password" required="" value="{{$data['password']}}">
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="incomingserver" class="control-label">Incoming Server</label>
                            <input type="text" class="form-control" name="incomingserver" id="incomingserver" placeholder="Incoming Server" required="" value="{{$data['incomingserver']}}">
                            <span class="text-danger">{{ $errors->first('incomingserver') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="incomingport" class="control-label">Incoming Server Imap Port</label>
                            <input type="text" class="form-control" name="incomingport" id="incomingport" placeholder="Incoming Server Imap Port" required="" value="{{$data['incomingport']}}">
                            <span class="text-danger">{{ $errors->first('incomingport') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="outgoingserver" class="control-label">Outgoing Server</label>
                            <input type="text" class="form-control" name="outgoingserver" id="outgoingserver" placeholder="Outgoing Server" required="" value="{{$data['outgoingserver']}}">
                            <span class="text-danger">{{ $errors->first('outgoingserver') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="outgoingport" class="control-label">Outgoing Server Smtp Port</label>
                            <input type="text" class="form-control" name="outgoingport" id="outgoingport" placeholder="Outgoing Server Smtp Port" required="" value="{{$data['outgoingport']}}">
                            <span class="text-danger">{{ $errors->first('outgoingport') }}</span>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <div class="btn-group btn-flat pull-right">
                            <a href="{{url('/home')}}" class="btn btn-default">Back</a>
                            {{Form::hidden('_method','PUT')}}
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
    var url = "{{url('ajaxwebtolead/getStateoptions')}}";
    $(function() {
        $(".sidebar-menu li").removeClass("menu-open");
        $(".sidebar-menu li").removeClass("active");
        $("#ulleads").addClass('menu-open');
        $("#ulleads ul").css('display', 'block');
        $("#licreatelead").addClass("active");

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

@extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-10">
                    <h1 class="m-0 text-dark">Notification</h1>
                </div>                
                <div class="col-sm-2">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="breadcrumb-item active">Notification</li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section> 
    <!-- Main content -->
    <section class="content mt-2 mx-0">
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
                <!-- Horizontal Form -->
                <div class="card card-info">
                    <div class="card-header with-border">
                        <h3 class="card-title">Edit Post Order Stage</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    {{Form::open(['action'=>['Admin\PostOrderStageController@update',$data->pos_id],'method'=>'Post','class' => 'form-horizontal'])}} 
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="stage" class="col-sm-3 control-label">Post Order Stage</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="stage" id="stage" placeholder="Post Order Stage" value="{{$data->stage}}" required>
                                <span class="text-danger">{{ $errors->first('stage') }}</span>
                            </div>
                        </div>

                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top">
                        <a href="{{url('admin/postorderstage')}}" class="btn btn-default">Back</a>
                        {{Form::submit('Save',['class'=>"btn btn-primary float-right"])}}
                    </div>
                    <!-- /.card-footer -->
                    <!--</form>-->
                    {{Form::hidden('_method','PUT')}}
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
<script>
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#lileadstatus").addClass("active");
        $("#lileadstatus").parent('#ulleadstatus ul').css('display', 'block').addClass("menu-open");
        $("#ulleadstatus").parent('#ulsettings ul').css('display', 'block').addClass("menu-open");
    });
</script>
@endsection
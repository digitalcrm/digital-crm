@extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="row">
            <div class="col-md-10">                
                <h1 class="pb-2">
                    New Email
                </h1>
            </div>
            <div class="col-md-2">
                
            </div>
        </div>
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
                <div class="card shadow card-primary card-outline">
                    <!-- /.card-header -->
                    <!-- form start -->
                    {{Form::open(['action'=>'Admin\EmailController@store','method'=>'Post','class' => 'form-horizontal'])}} 
                    @csrf
                    <div class="card-body">
                        <!--                        <div class="form-group">
                                                    <label for="name" class="col-sm-2 control-label">Department</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control" id="ecat_id" name="ecat_id" required="">
                        <?php // echo $data['department_options']; ?>
                                                        </select>
                                                        <span class="text-danger">{{ $errors->first('ecat_id') }}</span>
                                                    </div>
                                                </div>-->
                        <div class="form-group">
                            <label for="mail" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="mail" id="mail" placeholder="Email" value="{{old('mail')}}" required>
                                <span class="text-danger">{{ $errors->first('mail') }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top">
                        {{Form::submit('Create',['class'=>"btn btn-primary float-right"])}}
                    </div>
                    <!-- /.card-footer -->
                    <!--</form>-->
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
        $("#lisettings").addClass("active");
    });
</script>
@endsection
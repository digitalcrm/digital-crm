@extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="row">
            <div class="col-md-10">                
                <h1 class="pb-2">
                    New Deal Stage
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
                    {{Form::open(['action'=>'Admin\DealStageController@store','method'=>'Post','class' => 'form-horizontal'])}} 
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="salesfunnel" class="col-sm-2 control-label">Deal Stage</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="salesfunnel" id="salesfunnel" placeholder="Deal Stage" value="{{old('salesfunnel')}}" required>
                                <span class="text-danger">{{ $errors->first('salesfunnel') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="color" class="col-sm-2 control-label">Color</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <span class="input-group-text">#</span>
                                    <input type="text" class="form-control" name="color" id="color" placeholder="Color" value="{{old('color')}}" required>
                                    <span class="text-danger">{{ $errors->first('color') }}</span>
                                </div>
                                <small>example #ff0000</small>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top text-right pull-right">
                        <a href="{{url('admin/dealstage')}}" class="btn btn-outline-secondary">Back</a>
                        {{Form::submit('Create',['class'=>"btn btn-primary"])}}
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
        $("#lileadstatuscreate").addClass("active");
        $("#lileadstatuscreate").parent('#ulleadstatus ul').css('display', 'block').addClass("menu-open");
        $("#ulleadstatus").parent('#ulsettings ul').css('display', 'block').addClass("menu-open");
    });
</script>
@endsection
 @extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Edit Currency
                    </h1>
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
                    {{Form::open(['action'=>['Admin\CurrencyController@update',$data->cr_id],'method'=>'Post','class' => 'form-horizontal'])}} 
                    @csrf
                    <div class="card-body">
                        <section class="row">
                            <div class="col-lg-12">
                            <div class="form-group">
                                <label for="name" class="col-sm-12 control-label">Currency</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Currency" value="{{$data->name}}" required>
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="html_code" class="col-sm-12 control-label">Html Code</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="html_code" id="html_code" placeholder="Html Code" value="{{$data->html_code}}" required>
                                    <span class="text-danger">{{ $errors->first('html_code') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="code" class="col-sm-12 control-label">Code</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="code" id="sortname" placeholder="Code" value="{{$data->code}}" required>
                                    <span class="text-danger">{{ $errors->first('code') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <div class="checkbox">
                                        <label class="col-sm-4 control-label">
                                            <input type="checkbox" name="default" id="default" <?php echo ($data->status == 1) ? 'checked' : ''; ?>> Set as default
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </section>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top text-right pull-right">
                        <a href="{{url('admin/currency')}}" class="btn btn-outline-secondary">Back</a>
						{{Form::submit('Save',['class'=>"btn btn-primary"])}}
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
        $("#liaccounttypes").addClass("active");
        $("#liaccounttypes").parent('#ulaccounttypes ul').css('display', 'block').addClass("menu-open");
        $("#ulaccounttypes").parent('#ulsettings ul').css('display', 'block').addClass("menu-open");
    });
</script>
@endsection
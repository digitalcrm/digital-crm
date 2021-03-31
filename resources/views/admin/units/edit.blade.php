@extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="row">
            <div class="col-md-10">                
                <h1 class="pb-2">
                    Edit Unit
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
                    {{Form::open(['action'=>['Admin\UnitController@update',$data->unit_id],'method'=>'Post','class' => 'form-horizontal'])}} 
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Unit</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="name" id="name" placeholder="Unit" value="{{$data->name}}" required>
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sortname" class="col-sm-2 control-label">Sort Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="sortname" id="sortname" placeholder="Sort Name" value="{{$data->sortname}}" required>
                                <span class="text-danger">{{ $errors->first('sortname') }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top text-right pull-right">
                            <a href="{{url('admin/units')}}" class="btn btn-outline-secondary">Back</a> 
                            {{Form::hidden('_method','PUT')}}
                            {{Form::submit('Save',['class'=>"btn btn-primary"])}}
                    </div>
                    <!-- /.card-footer -->
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
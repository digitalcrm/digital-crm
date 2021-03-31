@extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-md-10 float-left">
                <h1 class="pb-2">
                    Edit Deal Type
                    <!-- <small>Control panel</small> -->
                </h1>
            </div>
            <div class="col-md-2 float-right">

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
                        {{Form::open(['action'=>['Admin\DealTypeController@update',$data->dl_id],'method'=>'Post','class' => 'form-horizontal'])}}
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="type" class="col-sm-12 control-label">Deal Type</label>
                                    </div>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" name="type" id="type" placeholder="Deal Type" value="{{$data->type}}" required>
                                        <span class="text-danger">{{ $errors->first('type') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white border-top">
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
        $("#liaccounttypes").addClass("active");
        $("#liaccounttypes").parent('#ulaccounttypes ul').css('display', 'block').addClass("menu-open");
        $("#ulaccounttypes").parent('#ulsettings ul').css('display', 'block').addClass("menu-open");
    });
</script>
@endsection
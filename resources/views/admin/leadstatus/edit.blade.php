@extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-md-10">
                <h1 class="pb-2">
                    Edit Lead Status
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
                        {{Form::open(['action'=>['Admin\LeadStatusController@update',$data->ldstatus_id],'method'=>'Post','class' => 'form-horizontal'])}}
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="status" class="col-sm-12 control-label">Lead Status</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="status" id="status" placeholder="Lead Status" value="{{$data->status}}" required>
                                    <span class="text-danger">{{ $errors->first('status') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" class="" name="deal" id="deal" <?php echo ($data->deal == 1) ? "checked" : ""; ?>>
                                <label for="deal" class="col-sm-2 control-label">Deal</label>
                                <span class="text-danger">{{ $errors->first('deal') }}</span>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white border-top text-right pull-right">
                            {{Form::submit('Save',['class'=>"btn btn-primary pull-right"])}}
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
@extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-md-10">
                <h1 class="pb-2">
                    New Lead Status
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
                        {{Form::open(['action'=>'Admin\LeadStatusController@store','method'=>'Post','class' => 'form-horizontal'])}}
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="status" class="col-sm-12 control-label">Lead Status</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="status" id="status" placeholder="Lead Status" value="{{old('status')}}" required>
                                    <span class="text-danger">{{ $errors->first('status') }}</span>
                                </div>
                            </div>
                            <div class="">
                                <input type="checkbox" class="" name="event" id="event">
                                <label for="event" class="control-label">Deal</label>
                                <span class="text-danger">{{ $errors->first('status') }}</span>

                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white border-top text-right pull-right">
                            {{Form::submit('Create',['class'=>"btn btn-primary pull-right"])}}
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
@extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-md-10 float-left">
                <h1 class="pb-2">
                    New RD Priority
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
                        {{Form::open(['action'=>'Admin\RdtrendController@store','method'=>'Post','class' => 'form-horizontal'])}}
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="trend" class="control-label">Rd Trend</label>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="trend" id="trend" placeholder="RD Trend" value="{{old('trend')}}" required>
                                        <span class="text-danger">{{ $errors->first('trend') }}</span>
                                    </div>
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
        $("#liaccounttypescreate").addClass("active");
        $("#liaccounttypescreate").parent('#ulaccounttypes ul').css('display', 'block').addClass("menu-open");
        $("#ulaccounttypes").parent('#ulsettings ul').css('display', 'block').addClass("menu-open");
    });
</script>
@endsection
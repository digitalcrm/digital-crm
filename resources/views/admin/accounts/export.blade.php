@extends('layouts.adminlte-boot-4.admin')
@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Export Accounts</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content mt-2 mx-0">
        <!-- Small cardes (Stat card) -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8">
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

                    @if(session('info'))
                    <div class='alert alert-warning'>
                        {{session('info')}}
                    </div>
                    @endif
                    <!-- general form elements -->

                    <div class="card shadow card-primary card-outline">
                        <!--                        <div class="card-header with-border">
                                                    <h3 class="card-title">
                                                        Import Accounts
                                                    </h3>
                                                </div>-->
                        <!-- /.card-header -->
                        <!-- form start -->
                        <!-- <form role="form" > -->
                        {{Form::open(['action'=>'Admin\AccountController@exportData','method'=>'Post','enctype'=>'multipart/form-data'])}}
                        @csrf
                        <div class="card-body p-0">
                            <!-- Left col -->
                            <section class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">User</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                    <select class="form-control" id="selectUser" name="selectUser" required>
                                        {!!$data['useroptions']!!}
                                    </select>
                                    <span class="text-danger">{{ $errors->first('selectUser') }}</span>
                                </div>
                                
                            </section>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white text-right pull-right">
                            <a href="{{url('admin/accounts')}}" class="btn btn-default">Back</a>
                            {{Form::submit('Save',['class'=>"btn btn-primary"])}}
                        </div>
                        <!-- </form> -->
                        {{Form::close()}}
                    </div>

                </div>
                <!-- ./col -->
            </div>
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <section class="col-lg-10">

            </section>
            <!-- right col -->
        </div>
        <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#liaccounts").addClass("active");
    });
</script>
@endsection
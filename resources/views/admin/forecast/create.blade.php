@extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">New Forecast</h1>
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
            <div class="col-lg-12">
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
                <!-- general form elements -->
                <div class="card shadow card-primary card-outline">
                    <!-- /.card-header -->
                    <!-- form start -->
                    <!-- <form role="form" > -->
                    {{Form::open(['action'=>'Admin\ForecastController@store','method'=>'Post','enctype'=>'multipart/form-data'])}} 
                    @csrf
                    <div class="card-body">

                        <section class="row">
                            <div class="col-lg-6">
                            <div class="form-group">
                                <label for="units">Select Users</label>&nbsp;
                                <select class="form-control selectpicker" id="users" name="users" >
                                    {!!$data['useroptions']!!}
                                </select>
                                <span class="text-danger">{{ $errors->first('users') }}</span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="year">Year</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group date">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control float-right" name="year" id="datepicker" required value="<?php echo date('Y'); ?>">
                                    <span class="text-danger">{{ $errors->first('year') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="january">January</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group">
                                    <span class="input-group-text currency"></span>
                                    <input type="text" name="january" id="january" class="form-control" placeholder="" value="{{old('january')}}" required >
                                </div>
                                <span class="text-danger">{{ $errors->first('january') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="may">May</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group">
                                    <span class="input-group-text currency"></span>
                                    <input type="text" name="may" id="may" class="form-control" placeholder="" value="{{old('may')}}" required  >
                                </div>
                                <span class="text-danger">{{ $errors->first('may') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="september">September</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group">
                                    <span class="input-group-text currency"></span>
                                    <input type="text" name="september" id="september" class="form-control" placeholder="" value="{{old('september')}}" required  >
                                </div>
                                <span class="text-danger">{{ $errors->first('september') }}</span>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="february">February</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group">
                                    <span class="input-group-text currency"></span>
                                    <input type="text" name="february" id="february" class="form-control" placeholder="" value="{{old('february')}}" required  >
                                </div>
                                <span class="text-danger">{{ $errors->first('february') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="june">June</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group">
                                    <span class="input-group-text currency"></span>
                                    <input type="text" name="june" id="june" class="form-control" placeholder="" value="{{old('june')}}" required  >
                                </div>
                                <span class="text-danger">{{ $errors->first('june') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="october">October</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group">
                                    <span class="input-group-text currency"></span>
                                    <input type="text" name="october" id="october" class="form-control" placeholder="" value="{{old('october')}}" required  >
                                </div>
                                <span class="text-danger">{{ $errors->first('october') }}</span>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="march">March</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group">
                                    <span class="input-group-text currency"></span>
                                    <input type="text" name="march" id="march" class="form-control" placeholder="" value="{{old('march')}}" required  >
                                </div>
                                <span class="text-danger">{{ $errors->first('march') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="july">July</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group">
                                    <span class="input-group-text currency"></span>
                                    <input type="text" name="july" id="july" class="form-control" placeholder="" value="{{old('july')}}" required  >
                                </div>
                                <span class="text-danger">{{ $errors->first('march') }}</span>
                            </div>

                            <div class="form-group">
                                <label for="november">November</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group">
                                    <span class="input-group-text currency"></span>
                                    <input type="text" name="november" id="november" class="form-control" placeholder="" value="{{old('november')}}" required  >
                                </div>
                                <span class="text-danger">{{ $errors->first('november') }}</span>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="april">April</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group">
                                    <span class="input-group-text currency"></span>
                                    <input type="text" name="april" id="april" class="form-control" placeholder="" value="{{old('april')}}" required  >
                                </div>
                                <span class="text-danger">{{ $errors->first('january') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="august">August</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group">
                                    <span class="input-group-text currency"></span>
                                    <input type="text" name="august" id="august" class="form-control" placeholder="" value="{{old('august')}}" required  >
                                </div>
                                <span class="text-danger">{{ $errors->first('august') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="december">December</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group">
                                    <span class="input-group-text currency"></span>
                                    <input type="text" name="december" id="december" class="form-control" placeholder="" value="{{old('december')}}" required  >
                                </div>
                                <span class="text-danger">{{ $errors->first('december') }}</span>
                            </div>
                        </div>
                        </section>


                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top text-right pull-right">
                            <a href="{{url('admin/forecast')}}" class="btn btn-outline-secondary">Cancel</a>
                            {{Form::submit('Create',['class'=>"btn btn-primary"])}}
                    </div>
                    <!-- </form> -->
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
    var forecasturl = "{{url('admin/ajax/getUserCurrency')}}";
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#liforecast").addClass("active");

        //Date picker
        $('#datepicker').datepicker({
            format: 'yyyy',
            autoclose: true,
            viewMode: "years",
            minViewMode: "years"
        });

        $("#users").change(function() {
            var uid = $(this).val();
            $.get(forecasturl, {'uid': uid}, function(result) {
                $(".currency").html(result);
//                alert(result);
            });
        });

    });
</script>
@endsection
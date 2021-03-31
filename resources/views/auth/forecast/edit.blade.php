@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1><i class="far fa-edit"></i> Edit Forecast</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>


    <!-- Main content -->
    <section class="content mt-2 mx-0">
        <div class="container-fluid">
            <!-- Small cardes (Stat card) -->
            <div class="row">
                <div class="col-lg-7">
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
                        {{Form::open(['action'=>['ForecastController@forecastUpdate',$data['user']->id],'method'=>'Post','enctype'=>'multipart/form-data'])}}
                        @csrf
                        <div class="card-body">
                            <input type="hidden" value="<?php echo $data['user']->id; ?>" name="user" id="user" >
                            <section class="col-lg-12">
                                <div class="form-group">
                                    <label for="units">Select Users</label>&nbsp;
                                    <select class="form-control selectpicker" id="users" name="users" disabled>
                                        {!!$data['selectUsers']!!}
                                    </select>
                                    <span class="text-danger">{{ $errors->first('users') }}</span>
                                </div>
                            </section>
                            <section class="col-lg-12">
                                <div class="form-group">
                                    <label for="year">Year</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                    <div class="input-group date">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input type="text" class="form-control pull-right" name="year" id="datepicker" required value="<?php echo $data['year']; ?>" readonly="readonly">
                                        <span class="text-danger">{{ $errors->first('year') }}</span>
                                    </div>
                                </div>
                            </section>
                            <div class="row">
                                <section class="col-lg-3">
                                    <div class="form-group">
                                        <label for="january">January</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{!!$data['user']['currency']['html_code']!!}</span>
                                            </div>
                                            <input type="text" name="january" id="january" class="form-control" placeholder="" value="<?php echo $data['forecast'][0]->forecast; ?>" required >
                                            <span class="text-danger">{{ $errors->first('january') }}</span>
                                        </div>
                                        <input type="hidden" value="<?php echo $data['forecast'][0]->fcid; ?>" name="janfcid" id="janfcid" >
                                    </div>
                                    <div class="form-group">
                                        <label for="february">February</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{!!$data['user']['currency']['html_code']!!}</span>
                                            </div>
                                            <input type="text" name="february" id="february" class="form-control" placeholder="" value="<?php echo $data['forecast'][1]->forecast; ?>" required  >
                                            <span class="text-danger">{{ $errors->first('february') }}</span>
                                        </div>
                                        <input type="hidden" value="<?php echo $data['forecast'][1]->fcid; ?>" name="febfcid" id="febfcid" >
                                    </div>
                                    <div class="form-group">
                                        <label for="march">March</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{!!$data['user']['currency']['html_code']!!}</span>
                                            </div>
                                            <input type="text" name="march" id="march" class="form-control" placeholder="" value="<?php echo $data['forecast'][2]->forecast; ?>" required  >
                                            <span class="text-danger">{{ $errors->first('march') }}</span>
                                        </div>
                                        <input type="hidden" value="<?php echo $data['forecast'][2]->fcid; ?>" name="marfcid" id="marfcid" >
                                    </div>
                                </section>
                                <section class="col-lg-3">
                                    <div class="form-group">
                                        <label for="april">April</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{!!$data['user']['currency']['html_code']!!}</span>
                                            </div>
                                            <input type="text" name="april" id="april" class="form-control" placeholder="" value="<?php echo $data['forecast'][3]->forecast; ?>" required  >
                                            <span class="text-danger">{{ $errors->first('april') }}</span>
                                        </div>
                                        <input type="hidden" value="<?php echo $data['forecast'][3]->fcid; ?>" name="aprfcid" id="aprfcid" >
                                    </div>
                                    <div class="form-group">
                                        <label for="may">May</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{!!$data['user']['currency']['html_code']!!}</span>
                                            </div>
                                            <input type="text" name="may" id="may" class="form-control" placeholder="" value="<?php echo $data['forecast'][4]->forecast; ?>" required  >
                                            <span class="text-danger">{{ $errors->first('may') }}</span>
                                        </div>
                                        <input type="hidden" value="<?php echo $data['forecast'][4]->fcid; ?>" name="mayfcid" id="mayfcid" >
                                    </div>
                                    <div class="form-group">
                                        <label for="june">June</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{!!$data['user']['currency']['html_code']!!}</span>
                                            </div>
                                            <input type="text" name="june" id="june" class="form-control" placeholder="" value="<?php echo $data['forecast'][5]->forecast; ?>" required  >
                                            <span class="text-danger">{{ $errors->first('june') }}</span>
                                        </div>
                                        <input type="hidden" value="<?php echo $data['forecast'][5]->fcid; ?>" name="junfcid" id="junfcid" >
                                    </div>

                                </section>
                                <section class="col-lg-3">

                                    <div class="form-group">
                                        <label for="july">July</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{!!$data['user']['currency']['html_code']!!}</span>
                                            </div>
                                            <input type="text" name="july" id="july" class="form-control" placeholder="" value="<?php echo $data['forecast'][6]->forecast; ?>" required  >
                                            <span class="text-danger">{{ $errors->first('july') }}</span>
                                        </div>
                                        <input type="hidden" value="<?php echo $data['forecast'][6]->fcid; ?>" name="julfcid" id="julfcid" >
                                    </div>
                                    <div class="form-group">
                                        <label for="august">August</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{!!$data['user']['currency']['html_code']!!}</span>
                                            </div>
                                            <input type="text" name="august" id="august" class="form-control" placeholder="" value="<?php echo $data['forecast'][7]->forecast; ?>" required  >
                                            <span class="text-danger">{{ $errors->first('august') }}</span>
                                        </div>
                                        <input type="hidden" value="<?php echo $data['forecast'][7]->fcid; ?>" name="augfcid" id="augfcid" >
                                    </div>
                                    <div class="form-group">
                                        <label for="september">September</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{!!$data['user']['currency']['html_code']!!}</span>
                                            </div>
                                            <input type="text" name="september" id="september" class="form-control" placeholder="" value="<?php echo $data['forecast'][8]->forecast; ?>" required  >
                                            <span class="text-danger">{{ $errors->first('september') }}</span>
                                        </div>
                                        <input type="hidden" value="<?php echo $data['forecast'][8]->fcid; ?>" name="sepfcid" id="sepfcid" >
                                    </div>
                                </section>
                                <section class="col-lg-3">
                                    <div class="form-group">
                                        <label for="october">October</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{!!$data['user']['currency']['html_code']!!}</span>
                                            </div>
                                            <input type="text" name="october" id="october" class="form-control" placeholder="" value="<?php echo $data['forecast'][9]->forecast; ?>" required  >
                                            <span class="text-danger">{{ $errors->first('october') }}</span>
                                        </div>
                                        <input type="hidden" value="<?php echo $data['forecast'][9]->fcid; ?>" name="octfcid" id="octfcid" >
                                    </div>
                                    <div class="form-group">
                                        <label for="november">November</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{!!$data['user']['currency']['html_code']!!}</span>
                                            </div>
                                            <input type="text" name="november" id="november" class="form-control" placeholder="" value="<?php echo $data['forecast'][10]->forecast; ?>" required  >
                                            <span class="text-danger">{{ $errors->first('november') }}</span>
                                        </div>
                                        <input type="hidden" value="<?php echo $data['forecast'][10]->fcid; ?>" name="novfcid" id="novfcid" >
                                    </div>
                                    <div class="form-group">
                                        <label for="december">December</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{!!$data['user']['currency']['html_code']!!}</span>
                                            </div>
                                            <input type="text" name="december" id="december" class="form-control" placeholder="" value="<?php echo $data['forecast'][11]->forecast; ?>" required  >
                                            <span class="text-danger">{{ $errors->first('december') }}</span>
                                        </div>
                                        <input type="hidden" value="<?php echo $data['forecast'][11]->fcid; ?>" name="decfcid" id="decfcid" >
                                    </div>
                                </section>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white border-top text-right pull-right">
                            {{Form::hidden('_method','PUT')}}
                            <a href="{{url('/forecast')}}" class="btn btn-outline-secondary mr-1">Cancel</a>
                            {{Form::submit('Save',['class'=>"btn btn-primary"])}}
                        </div>
                        <!-- </form> -->
                        {{Form::close()}}
                    </div>
                    <!-- /.card -->
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <section class="col-lg-7 connectedSortable">


                </section>
                <!-- /.Left col -->
                <!-- right col (We are only adding the ID to make the widgets sortable)-->
                <section class="col-lg-5 connectedSortable">


                </section>
                <!-- right col -->
            </div>
            <!-- /.row (main row) -->
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    var url = "{{url('ajaxwebtolead/getStateoptions')}}";
    $(function() {
        $(".sidebar-menu li").removeClass("menu-open");
        $(".sidebar-menu li").removeClass("active");
        $("#ulleads").addClass('menu-open');
        $("#ulleads ul").css('display', 'block');
        $("#licreatelead").addClass("active");

        $("#account").change(function() {
            var acc = $(this).val();
            if (acc == "NewAccount") {
                $("#addAccount").show();
            } else {
                $("#addAccount").hide();
            }
        });

        $("#country").change(function() {
            var country = $(this).val();
            // alert(country);
            if (country > 0) {
                $.get(url, {'country': country}, function(result, status) {
                    // alert(result);
                    $("#state").html(result);
                });
            }
        });

    });
</script>
@endsection
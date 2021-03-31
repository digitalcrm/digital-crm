 @extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Edit Forecast</h1>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
<!--    <section class="content-header">
        <h1>
            Edit Lead
             <small>Control panel</small> 
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Edit Lead</li>
        </ol>
    </section>-->

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
                    {{Form::open(['action'=>['Admin\ForecastController@forecastUpdate',$data['user']->id],'method'=>'Post','enctype'=>'multipart/form-data'])}}
                    @csrf
                    <div class="card-body">
                        <input type="hidden" value="<?php echo $data['user']->id; ?>" name="user" id="user" >
                        <section class="row">
                            <div class="col-lg-6">
                            <div class="form-group">
                                <label for="units">Select Users</label>&nbsp;
                                <p><?php echo $data['user']->name; ?></p>
                            </div>
                        </div>
                        {{-- </section> --}}
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="year">Year</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group date">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control float-right" name="year" id="datepicker" required value="<?php echo $data['year']; ?>" readonly="readonly">
                                    <span class="text-danger">{{ $errors->first('year') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="january">January</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group">
                                    <span class="input-group-text"><?php echo $data['currency']->html_code; ?></span>
                                    <input type="text" name="january" id="january" class="form-control" placeholder="" value="<?php echo $data['forecast'][0]->forecast; ?>" required >
                                </div>
                                <span class="text-danger">{{ $errors->first('january') }}</span>
                                <input type="hidden" value="<?php echo $data['forecast'][0]->fcid; ?>" name="janfcid" id="janfcid" >
                            </div>
                            <div class="form-group">
                                <label for="february">February</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group">
                                    <span class="input-group-text"><?php echo $data['currency']->html_code; ?></span>
                                    <input type="text" name="february" id="february" class="form-control" placeholder="" value="<?php echo $data['forecast'][1]->forecast; ?>" required  >
                                </div>
                                <span class="text-danger">{{ $errors->first('february') }}</span>
                                <input type="hidden" value="<?php echo $data['forecast'][1]->fcid; ?>" name="febfcid" id="febfcid" >
                            </div>
                            <div class="form-group">
                                <label for="march">March</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group">
                                    <span class="input-group-text"><?php echo $data['currency']->html_code; ?></span>
                                    <input type="text" name="march" id="march" class="form-control" placeholder="" value="<?php echo $data['forecast'][2]->forecast; ?>" required  >
                                </div>
                                <span class="text-danger">{{ $errors->first('march') }}</span>
                                <input type="hidden" value="<?php echo $data['forecast'][2]->fcid; ?>" name="marfcid" id="marfcid" >
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="april">April</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group">
                                    <span class="input-group-text"><?php echo $data['currency']->html_code; ?></span>
                                    <input type="text" name="april" id="april" class="form-control" placeholder="" value="<?php echo $data['forecast'][3]->forecast; ?>" required  >
                                </div>
                                <span class="text-danger">{{ $errors->first('april') }}</span>
                                <input type="hidden" value="<?php echo $data['forecast'][3]->fcid; ?>" name="aprfcid" id="aprfcid" >
                            </div>
                            <div class="form-group">
                                <label for="may">May</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group">
                                    <span class="input-group-text"><?php echo $data['currency']->html_code; ?></span>
                                    <input type="text" name="may" id="may" class="form-control" placeholder="" value="<?php echo $data['forecast'][4]->forecast; ?>" required  >
                                </div>
                                <span class="text-danger">{{ $errors->first('may') }}</span>
                                <input type="hidden" value="<?php echo $data['forecast'][4]->fcid; ?>" name="mayfcid" id="mayfcid" >
                            </div>
                            <div class="form-group">
                                <label for="june">June</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group">
                                    <span class="input-group-text"><?php echo $data['currency']->html_code; ?></span>
                                    <input type="text" name="june" id="june" class="form-control" placeholder="" value="<?php echo $data['forecast'][5]->forecast; ?>" required  >
                                </div>
                                <span class="text-danger">{{ $errors->first('june') }}</span>
                                <input type="hidden" value="<?php echo $data['forecast'][5]->fcid; ?>" name="junfcid" id="junfcid" >
                            </div>

                        </div>
                        <div class="col-lg-3">

                            <div class="form-group">
                                <label for="july">July</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group">
                                    <span class="input-group-text"><?php echo $data['currency']->html_code; ?></span>
                                    <input type="text" name="july" id="july" class="form-control" placeholder="" value="<?php echo $data['forecast'][6]->forecast; ?>" required  >
                                </div>
                                <span class="text-danger">{{ $errors->first('july') }}</span>
                                <input type="hidden" value="<?php echo $data['forecast'][6]->fcid; ?>" name="julfcid" id="julfcid" >
                            </div>
                            <div class="form-group">
                                <label for="august">August</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group">
                                    <span class="input-group-text"><?php echo $data['currency']->html_code; ?></span>
                                    <input type="text" name="august" id="august" class="form-control" placeholder="" value="<?php echo $data['forecast'][7]->forecast; ?>" required  >
                                </div>
                                <span class="text-danger">{{ $errors->first('august') }}</span>
                                <input type="hidden" value="<?php echo $data['forecast'][7]->fcid; ?>" name="augfcid" id="augfcid" >
                            </div>
                            <div class="form-group">
                                <label for="september">September</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group">
                                    <span class="input-group-text"><?php echo $data['currency']->html_code; ?></span>
                                    <input type="text" name="september" id="september" class="form-control" placeholder="" value="<?php echo $data['forecast'][8]->forecast; ?>" required  >
                                </div>
                                <span class="text-danger">{{ $errors->first('september') }}</span>
                                <input type="hidden" value="<?php echo $data['forecast'][8]->fcid; ?>" name="sepfcid" id="sepfcid" >
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="october">October</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group">
                                    <span class="input-group-text"><?php echo $data['currency']->html_code; ?></span>
                                    <input type="text" name="october" id="october" class="form-control" placeholder="" value="<?php echo $data['forecast'][9]->forecast; ?>" required  >
                                </div>
                                <span class="text-danger">{{ $errors->first('october') }}</span>
                                <input type="hidden" value="<?php echo $data['forecast'][9]->fcid; ?>" name="octfcid" id="octfcid" >
                            </div>
                            <div class="form-group">
                                <label for="november">November</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group">
                                    <span class="input-group-text"><?php echo $data['currency']->html_code; ?></span>
                                    <input type="text" name="november" id="november" class="form-control" placeholder="" value="<?php echo $data['forecast'][10]->forecast; ?>" required  >
                                </div>
                                <span class="text-danger">{{ $errors->first('november') }}</span>
                                <input type="hidden" value="<?php echo $data['forecast'][10]->fcid; ?>" name="novfcid" id="novfcid" >
                            </div>
                            <div class="form-group">
                                <label for="december">December</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group">
                                    <span class="input-group-text"><?php echo $data['currency']->html_code; ?></span>
                                    <input type="text" name="december" id="december" class="form-control" placeholder="" value="<?php echo $data['forecast'][11]->forecast; ?>" required  >
                                </div>
                                <span class="text-danger">{{ $errors->first('december') }}</span>
                                <input type="hidden" value="<?php echo $data['forecast'][11]->fcid; ?>" name="decfcid" id="decfcid" >
                            </div>
                        </div>
						</div>
                        </section>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top text-right pull-right">
                        <a href="{{url('admin/forecast')}}" class="btn btn-outline-secondary">Back</a>
						{{Form::hidden('_method','PUT')}}
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
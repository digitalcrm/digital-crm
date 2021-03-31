@extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1><i class="far fa-edit"></i> New Project</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

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
                    <div class='alert alert-success'>
                        {{session('error')}}
                    </div>
                    @endif
                    <!-- general form elements -->
                    <div class="card shadow card-primary card-outline">
                        <!-- /.card-header -->
                        <!-- form start -->
                        <!-- <form role="form" > -->
                        {{Form::open(['action'=>'ProjectController@store','method'=>'Post'])}}
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="pro_name">Project Name</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <input type="text" class="form-control" name="name" id="name" placeholder="" value="{{old('name')}}" required tabindex="1">
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="pro_code">Project Code</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <input type="text" class="form-control" name="code" id="code" placeholder="" value="{{old('code')}}" required tabindex="2">
                                        <span class="text-danger">{{ $errors->first('code') }}</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="pro_type">Project Type</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <select class="form-control" name="type" id="type" required tabindex="3">
                                            <option value="0">Select Project Type</option>
                                            <option value="1">Syndicate</option>
                                            <option value="2">Custom</option>
                                        </select>
                                        <span class="text-danger">{{ $errors->first('type') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="ps_id">Project Status</label>
                                        <select class="form-control" id="ps_id" name="ps_id" tabindex="4">
                                            {!!$data['prostatusoptions']!!}
                                        </select>
                                        <span class="text-danger">{{ $errors->first('ps_id') }}</span>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" name="description" id="description" placeholder="Description" tabindex="5"></textarea>
                                        <span class="text-danger">{{ $errors->first('description') }}</span>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="pro_code">Standard/ Forecast</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <input type="text" class="form-control" name="forecast" id="forecast" placeholder="" value="{{old('forecast')}}" required tabindex="6" onkeyup="return calculateForecast();">
                                        <span class="text-danger">{{ $errors->first('forecast') }}</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="pro_manager">Manager</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <select class="form-control" id="manager" name="manager" tabindex="7">
                                            {!!$data['museroptions']!!}
                                        </select>
                                        <span class="text-danger">{{ $errors->first('manager') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row calRows col-12" id="calRow">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="pro_code">Analysts</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <select class='form-control ausers' id='auser' name='ausers[]' onchange='return analystUser("auser","weight","userId");'>
                                            {!!$data['auseroptions']!!}
                                        </select>
                                        <input type="hidden" value="" id="userId" class="userIds" name="userIds[]" />
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="pro_code">Weight</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <input type="text" class="form-control weights" name="weight[]" id="weight" placeholder="" value="{{old('weight')}}" required>
                                        <span class="text-danger">{{ $errors->first('weight') }}</span>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <input type="button" value="Add Member" class="btn btn-default" id="basic-addon2" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="pro_code">Total Forecast</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <input type="text" class="form-control" name="total_forecast" id="total_forecast" placeholder="" value="{{old('totalForecast')}}" required tabindex="">
                                        <span class="text-danger">{{ $errors->first('total_forecast') }}</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="pro_code">Creation Date</label>
                                        <input type="text" class="form-control" name="creation_date" id="creation_date" placeholder="" value="{{old('creation_date')}}" required tabindex="">
                                        <span class="text-danger">{{ $errors->first('creation_date') }}</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="pro_code">Submission Date</label>
                                        <input type="text" class="form-control" name="submission_date" id="submission_date" placeholder="" value="{{old('submission_date')}}" required tabindex="">
                                        <span class="text-danger">{{ $errors->first('submission_date') }}</span>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top pull-right text-right">
                        <a href="{{url('projects')}}" class="btn btn-outline-secondary mr-1">Cancel</a>
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
<!--JQuery Datepicker-->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    var auseroptions = "<?php echo $data['auseroptions']; ?>";
    var submissiondateurl = "{{url('projects/getSubmissionDate/{startDate}/{days}')}}";
    $(function() {
        // $(".sidebar-menu li").removeClass("active");
        // $("#lideals").addClass("active");

        $("#creation_date").datepicker({
            "dateFormat": 'dd-mm-yy'
        });

        $("#submission_date").datepicker({
            "dateFormat": 'dd-mm-yy'
        });

        $("#forecast").keyup(function() {
            var days = $(this).val();
            if (days > 0) {
                calculateForecast();
            }
        });

        $("#creation_date").change(function() {
            var startdate = $(this).val();
            var days = $("#forecast").val();

            // alert(startdate + ' ' + days);

            if ((startdate != '') && (Number(days) > 0)) {
                $.get(submissiondateurl, {
                    'startDate': startdate,
                    'days': days
                }, function(result, status) {

                    // alert(result);
                    $("#submission_date").val(result);
                });
            }
        });

        $("#basic-addon2").click(function() {
            // alert("Add");


            var rowsCount = $(".calRows").length;
            // alert(rowsCount);

            var calRowid = "calRow" + rowsCount;
            var removeId = "remove" + rowsCount;
            var auserId = "auser" + rowsCount;
            var userId = "user" + rowsCount;
            var pro_codeId = "weight" + rowsCount;

            var pro_codeIdArg = '"' + pro_codeId + '"';
            var userIdArg = '"' + userId + '"';
            var removeIdArg = "'" + calRowid + "'";
            var auserIdArg = '"' + auserId + '"';

            var memberHtml = '<div class="col-12"><div class="row calRows" id="' + calRowid + '">';
            memberHtml += '<div class="col-4">';
            // memberHtml += '<input type="text" class="form-control" name="pro_code" id="pro_code" placeholder="" value="" required>';
            memberHtml += "<select class='form-control ausers' id='" + auserId + "' name='ausers[]' onchange='return analystUser(" + auserIdArg + "," + pro_codeIdArg + "," + userIdArg + ");'>";
            memberHtml += auseroptions;
            memberHtml += '</select>';
            memberHtml += '<input type="hidden" value="" class="userIds" name="userIds[]"  id="' + userId + '"/>';
            memberHtml += '</div>';
            memberHtml += '<div class="col-2">';
            memberHtml += '<div class="form-group">';
            memberHtml += '<div class="input-group mb-3">';
            memberHtml += '<input type="text" class="form-control weights" name="weight[]" id="' + pro_codeId + '" placeholder="" value="" required>';
            memberHtml += '<div class="input-group-append">';
            memberHtml += '<span class="input-group-text" id="' + removeId + '" onclick="return removeRow(' + removeIdArg + ')"><i class="fa fa-window-close" aria-hidden="true"></i></span>';
            memberHtml += '</div>';
            memberHtml += '</div>';
            memberHtml += '</div>';
            memberHtml += '</div>';
            memberHtml += '</div>';
            memberHtml += '</div>';
            memberHtml += '</div>';
            $("#calRow").append(memberHtml);

        });

    });

    function removeRow(id) {
        // alert(id);
        $("#" + id).remove();
    }

    function analystUser(id, weightId, userId) {
        var auserVal = $("#" + id).val();
        // alert(auserVal + ' ' + weightId);

        var auserArr = auserVal.split("|");

        var auserId = auserArr[0];
        var auserWeight = auserArr[1];
        // alert(auserId + ' ' + auserWeight);
        var repeat = false;
        $(".userIds").each(function() {
            if ($(this).val() == auserId) {
                alert("Please select different analyst");
                $("#" + id).val('');
                repeat = true;
                return false;
            }
        });

        if (repeat == false) {
            $("#" + weightId).val(auserWeight);
            $("#" + userId).val(auserId);
            calculateForecast();

        }


    }

    function calculateForecast() {

        var forecast = $("#forecast").val();

        if (forecast >= 0) {

            var totalForecast = 0;
            $(".weights").each(function(e) {
                // alert($(this).val());
                var weight = $(this).val();
                totalForecast += (Number(forecast) * Number(weight));
            });

            $("#total_forecast").val(totalForecast);
        } else {
            alert('Please enter Forcast');
            return false;
        }
    }
</script>
@endsection
@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1><i class="far fa-edit"></i> Edit Project</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content mx-0">
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
                    <div class="card shadow card-primary card-outline">
                        <div class="card-header with-border">
                            <h3 class="card-title">
                                <?php echo $data->name; ?>
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <!-- <form role="form" > -->
                        {{Form::open(['action'=>['ProjectController@update',$data->project_id],'method'=>'Post'])}}
                        @csrf
                        <div class="card-body">
						
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="pro_name">Project Name</label>
                                        <div class="col-md-9">
										<input type="text" class="form-control required" name="name" id="name" placeholder="" value="{{$data->name}}" required tabindex="1">
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
										</div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="pro_code">Project Code</label>
                                        <div class="col-md-9">
										<input type="text" class="form-control required" name="code" id="code" placeholder="" value="{{$data->code}}" required tabindex="2">
                                        <span class="text-danger">{{ $errors->first('code') }}</span>
										</div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="pro_type">Project Type</label>
                                        <div class="col-md-9">
										<select class="form-control required" name="type" id="type" required tabindex="3">
                                            <option value="0">Select Project Type</option>
                                            <option value="1" <?php echo ($data->type == 1) ? 'selected' : ''; ?>>Syndicate</option>
                                            <option value="2" <?php echo ($data->type == 2) ? 'selected' : ''; ?>>Custom</option>
                                        </select>
                                        <span class="text-danger">{{ $errors->first('type') }}</span>
										</div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="ps_id">Project Status</label>
                                        <div class="col-md-9">
										<select class="form-control" id="ps_id" name="ps_id" tabindex="4">
                                            {!!$data['prostatusoptions']!!}
                                        </select>
                                        <span class="text-danger">{{ $errors->first('ps_id') }}</span>
										</div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="description">Description</label>
                                        <div class="col-md-9">
										<textarea class="form-control" name="description" id="description" placeholder="Description" tabindex="5">{{$data->description}}</textarea>
                                        <span class="text-danger">{{ $errors->first('description') }}</span>
										</div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="pro_code">Standard/ Forecast</label>
                                        <div class="col-md-9">
										<input type="text" class="form-control required" name="forecast" id="forecast" placeholder="" value="{{$data->forecast}}" required tabindex="6" onkeyup="return calculateForecast();">
                                        <span class="text-danger">{{ $errors->first('forecast') }}</span>
										</div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="actual_days">Actual Man Days</label>
                                        <div class="col-md-9">
										<input type="text" class="form-control required" name="actual_days" id="actual_days" placeholder="" value="{{$data->actual_days}}" required tabindex="">
                                        <span class="text-danger">{{ $errors->first('actual_days') }}</span>
										</div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="pro_manager">Manager</label>
                                        <div class="col-md-9">
										<select class="form-control required" id="manager" name="manager" tabindex="7">
                                            {!!$data['museroptions']!!}
                                        </select>
                                        <span class="text-danger">{{ $errors->first('manager') }}</span>
										</div>
                                    </div>
							
							
							<div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="pro_manager"></label>
                                        <div class="col-md-9">
                            <div class="row calRows">
                                <?php echo $data['memberHtml']; ?>
                            </div>
							</div>
							</div>
							
                            <div id="calRow">
                            </div>
							
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="pro_code">Total Forecast</label>
                                        <div class="col-md-9">
										<input type="text" class="form-control required" name="total_forecast" id="total_forecast" placeholder="" value="{{$data->total_forecast}}" required tabindex="">
                                        <span class="text-danger">{{ $errors->first('total_forecast') }}</span>
										</div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="pro_code">Creation Date</label>
                                        <div class="col-md-9">
										<input type="text" class="form-control" name="creation_date" id="creation_date" placeholder="" value="{{($data->creation_date != NULL)?date('d-m-Y',strtotime($data->creation_date)):''}}" required tabindex="">
                                        <span class="text-danger">{{ $errors->first('creation_date') }}</span>
										</div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="pro_code">Submission Date</label>
                                        <div class="col-md-9">
										<input type="text" class="form-control" name="submission_date" id="submission_date" placeholder="" value="{{($data->submission_date != NULL)?date('d-m-Y',strtotime($data->submission_date)):''}}" required tabindex="">
                                        <span class="text-danger">{{ $errors->first('submission_date') }}</span>
										</div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="pro_code">Leaves</label>
                                        <div class="col-md-9">
										<input type="number" class="form-control" name="leaves" id="leaves" placeholder="" value="{{$data->leaves}}" onkeyup="return CalcuateTotalDays();" />
                                        <span class="text-danger">{{ $errors->first('leaves') }}</span>
										</div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="pro_manager">Working on different project</label>
                                        <div class="col-md-9">
										<input type="number" class="form-control" name="different_project" id="different_project" placeholder="" value="{{$data->different_project}}" tabindex="" onkeyup="return CalcuateTotalDays();" />
                                        <span class="text-danger">{{ $errors->first('different_project') }}</span>
										</div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="pro_code">Company Activity</label>
                                        <div class="col-md-9">
										<input type="number" class="form-control" name="company_activity" id="company_activity" placeholder="" value="{{$data->company_activity}}" tabindex="" onkeyup="return CalcuateTotalDays();" />
                                        <span class="text-danger">{{ $errors->first('company_activity') }}</span>
										</div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="pro_manager">Working</label>
                                        <div class="col-md-9">
										<input type="number" class="form-control" name="working" id="working" placeholder="" value="{{$data->working}}" tabindex="" onkeyup="return CalcuateTotalDays();" />
                                        <span class="text-danger">{{ $errors->first('working') }}</span>
										</div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="pro_manager">Others</label>
                                        <div class="col-md-9">
										<input type="number" class="form-control" name="other" id="other" placeholder="" value="{{$data->other}}" tabindex="" onkeyup="return CalcuateTotalDays();" />
                                        <span class="text-danger">{{ $errors->first('other') }}</span>
										</div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="total_days">Total Days</label>
                                        <div class="col-md-9">
										<input type="number" class="form-control" name="total_days" id="total_days" placeholder="" value="{{$data->total_days}}" tabindex="" />
                                        <span class="text-danger">{{ $errors->first('total_days') }}</span>
										</div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="pro_code">Marketing Collaterals</label>
                                        <div class="col-md-9">
										<select class="form-control" name="marketing_collateral" id="marketing_collateral">
                                            <option value="0">Select Marketing Collateral</option>
                                            <option value="1" <?php echo ($data->marketing_collateral == 1) ? 'selected' : ''; ?>>Yes</option>
                                            <option value="2" <?php echo ($data->marketing_collateral == 2) ? 'selected' : ''; ?>>No</option>
                                        </select>
                                        <span class="text-danger">{{ $errors->first('marketing_collateral') }}</span>
										</div>
									</div>
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="pro_code">Sample Pages</label>
                                        <div class="col-md-9">
										<select class="form-control" name="sample_pages" id="sample_pages">
                                            <option value="0">Select ...</option>
                                            <option value="1" <?php echo ($data->sample_pages == 1) ? 'selected' : ''; ?>>Yes</option>
                                            <option value="2" <?php echo ($data->sample_pages == 2) ? 'selected' : ''; ?>>No</option>
                                        </select>
                                        <span class="text-danger">{{ $errors->first('sample_pages') }}</span>
										</div>
									</div>
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="pro_code">Client Date</label>
                                        <div class="col-md-9">
										<input type="text" class="form-control" name="client_submit" id="client_submit" placeholder="" value="{{($data->client_submit != NULL)?date('d-m-Y',strtotime($data->client_submit)):''}}" tabindex="">
                                        <span class="text-danger">{{ $errors->first('client_submit') }}</span>
										</div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="pro_code">Feedback</label>
                                        <div class="col-md-9">
										<textarea class="form-control" name="feedback" id="feedback" placeholder="">{!!$data->feedback!!}</textarea>
                                        <span class="text-danger">{{ $errors->first('feedback') }}</span>
										</div>
                                    </div>
									
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top pull-right text-right">
                        <a href="{{url('projects')}}" class="btn btn-outline-secondary mr-1">Cancel</a>
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

        $("#client_submit").datepicker({
            "dateFormat": 'dd-mm-yy'
        });

        $("#creation_date").datepicker({
            "dateFormat": 'dd-mm-yy'
        });

        $("#submission_date").datepicker({
            "dateFormat": 'dd-mm-yy'
        });

        $("#creation_date").change(function() {
            calculateSubbmissionDate();
        });


        $("#forecast").keyup(function() {
            var days = $(this).val();
            if (days > 0) {
                calculateForecast();
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
            memberHtml += '<div class="form-group row">';
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

            // alert(memberHtml);

            $("#calRow").append(memberHtml);

        });

    });


    function removeRow(id) {
        // alert(id);
        $("#" + id).remove();
        calculateForecast();
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
            calculateSubbmissionDate();

        } else {
            alert('Please enter Forcast');
            return false;
        }
    }

    function calculateSubbmissionDate() {
        var startdate = $("#creation_date").val();
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
    }

    function CalcuateTotalDays() {
        var leaves = $("#leaves").val();
        var different_project = $("#different_project").val();
        var company_activity = $("#company_activity").val();
        var working = $("#working").val();
        var other = $("#other").val();

        if ((Number(leaves) >= 0) && (Number(different_project) >= 0) && (Number(company_activity) >= 0) && (Number(working) >= 0) && (Number(other) >= 0)) {
            var total_days = Number(leaves) + Number(different_project) + Number(company_activity) + Number(working) + Number(other);
            $("#total_days").val(total_days);
        } else {
            alert("Please enter number only");
            return false;
        }
    }
</script>
@endsection
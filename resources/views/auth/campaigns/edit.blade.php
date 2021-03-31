@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1><i class="far fa-edit"></i> Edit Campaign</h1>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content mx-0"><div class="container-fluid">
        <!-- Small cardes (Stat card) -->
        <div class="row">
            <div class="col-lg-6">
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

                    {{Form::open(['action'=>['CampaignController@update',$data['campaign']['camp_id']],'method'=>'Post','enctype'=>'multipart/form-data'])}}
                    @csrf
                    <div class="card-body">

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right" for="name">Campaign Name</label>
                                    <div class="col-md-9">
									<input type="text" class="form-control required" name="name" id="accountname" placeholder="" value="{{$data['campaign']['name']}}" required>
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
									</div>
                                </div>
								
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right" for="mobile">Status</label>
                                    <div class="col-md-9">
									<select class="form-control required" name="status" id="status" required="">
                                        {!!$data['statusoptions']!!}
                                    </select>
                                    <span class="text-danger">{{ $errors->first('status') }}</span>
									</div>
                                </div>
								
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right" for="actual_cost">Actual Cost</label>
                                    <div class="col-md-9">
									<input type="number" class="form-control" name="actual_cost" id="actual_cost" placeholder="Actual Cost" value="{{$data['campaign']['actual_cost']}}" >
                                    <span class="text-danger">{{ $errors->first('actual_cost') }}</span>
									</div>
                                </div>
								
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right" for="objective">Objective</label>
                                    <div class="col-md-9">
									<textarea name="objective" id="objective" class="form-control" rows="5">{{$data['campaign']['objective']}}</textarea>
									</div>
                                </div>
								
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right" for="start_date">Start Date</label>
                                    <div class="col-md-9">
									<input type="text" class="form-control pull-right required" name="start_date" id="start_date" required value="{{date('d-m-Y',strtotime($data['campaign']['start_date']))}}">
                                    <span class="text-danger">{{ $errors->first('start_date') }}</span>
									</div>
                                </div>
								
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right" for="phone">Type</label>
                                    <div class="col-md-9">
									<select class="form-control required" name="type" id="type" required="">
                                        {!!$data['typeoptions']!!}
                                    </select>
                                    <span class="text-danger">{{ $errors->first('type') }}</span>
									</div>
                                </div>
								
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right" for="expected_cost">Expected Cost</label>
                                    <div class="col-md-9">
									<input type="number" class="form-control" name="expected_cost" id="expected_cost" placeholder="Expected Cost" value="{{$data['campaign']['expected_cost']}}">
                                    <span class="text-danger">{{ $errors->first('expected_cost') }}</span>
									</div>
                                </div>
								
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right" for="description">Description</label>
                                    <div class="col-md-9">
									<textarea name="description" id="description" class="form-control" rows="5">{{$data['campaign']['description']}}</textarea>
									</div>
                                </div>
								
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right" for="end_date">End Date</label>
                                    <div class="col-md-9">
									<input type="text" class="form-control pull-right required" name="end_date" id="end_date" required value="{{date('d-m-Y',strtotime($data['campaign']['end_date']))}}">
                                    <span class="text-danger">{{ $errors->first('end_date') }}</span>
									</div>
                                </div>
								
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right" for="budget">Budget</label>
                                    <div class="col-md-9">
									<input type="number" class="form-control" name="budget" id="budget" placeholder="Budget" value="{{$data['campaign']['budget']}}" >
                                    <span class="text-danger">{{ $errors->first('budget') }}</span>
									</div>
                                </div>
								
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right" for="expected_revenue">Expected Revenue</label>
                                    <div class="col-md-9">
									<input type="number" class="form-control" name="expected_revenue" id="expected_revenue" placeholder="Expected Revenue" value="{{$data['campaign']['expected_revenue']}}">
                                    <span class="text-danger">{{ $errors->first('expected_revenue') }}</span>
									</div>
                                </div>
								
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer pull-right text-right">
                        <a href="{{url('/campaigns')}}" class="btn btn-outline-secondary mr-1">Cancel</a>
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
var url = "{{url('ajaxwebtolead/getStateoptions')}}";
$(function() {
    $(".sidebar-menu li").removeClass("active");
    $("#licampaigns").addClass("active");

    $("#start_date").datepicker({"dateFormat": 'dd-mm-yy'});
    $("#end_date").datepicker({"dateFormat": 'dd-mm-yy'});

});
</script>
@endsection
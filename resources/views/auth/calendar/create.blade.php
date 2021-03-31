@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1><i class="far fa-edit"></i> New Event</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <style id="compiled-css" type="text/css">
        .my-group{
            width: 100%;
        }
        .my-group #startDate{
            width:50%;
        }
        .my-group #startTime{
            width:50%;
        }
        .my-group #endDate{
            width:50%;
        }
        .my-group #endTime{
            width:50%;
        }
    </style>
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
                <div class="card card-primary">

                    {{Form::open(['action'=>'CalendarController@store','method'=>'Post','enctype'=>'multipart/form-data'])}} 
                    @csrf
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="name">Title</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                    <input type="text" class="form-control" name="title" id="title" placeholder="" value="{{old('title')}}" required>
                                    <span class="text-danger">{{ $errors->first('title') }}</span>
                                </div>

                                <div class="form-group">
                                    <label for="name">Start Date</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                    <div class="input-group my-group"> 
                                        <input type="text" class="form-control" name="startDate" id="startDate" placeholder="" value="{{old('startDate')}}" required>
                                        <span class="text-danger">{{ $errors->first('startDate') }}</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name">Start Time</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                    <div class="input-group date" id="timepicker1" data-target-input="nearest">
                                        <div class="input-group-append" data-target="#timepicker1" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                                        </div>
                                        <input type="text" class="form-control datetimepicker-input" name="startTime" name="startTime" data-target="#timepicker1"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name">End Date</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                    <div class="input-group my-group"> 
                                        <input type="text" class="form-control" name="endDate" id="endDate" placeholder="" value="{{old('endDate')}}" required>
                                        <span class="text-danger">{{ $errors->first('endDate') }}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name">End Time</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                    <div class="input-group date" id="timepicker2" data-target-input="nearest">
                                        <div class="input-group-append" data-target="#timepicker2" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                                        </div>
                                        <input type="text" class="form-control datetimepicker-input" name="endTime" name="endTime" data-target="#timepicker2"/>
                                    </div>
                                    <span class="text-danger">{{ $errors->first('endTime') }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="name">Description</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                    <textarea class="form-control" name="description" id="description" required rows="5">{{old('description')}}</textarea>
                                    <span class="text-danger">{{ $errors->first('description') }}</span>
                                </div>

                                <div class="form-group">
                                    <label for="location">Location</label>
                                    <input type="text" class="form-control" name="location" id="location" placeholder="" value="{{old('location')}}">
                                    <span class="text-danger">{{ $errors->first('location') }}</span>
                                </div>

                                <div class="form-group">
                                    <label for="type">Type</label>&nbsp;
                                    <select class="form-control" name="type" id="type"> 
                                        <?php echo $data['typeoptions']; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" name="allday" id="allday" >
                                    &nbsp;
                                    <label for="allday">All Day Event</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-lg-12">
                            <h4 class="card-title text-primary">Related To</h4>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="name">Related to</label>
                                    <select class="form-control" name="category" id="category"> 
                                        <option value="0">Select </option>
                                        <option value="1">Accounts</option>
                                        <option value="2">Contacts</option>
                                        <option value="3">Leads</option>
                                    </select>
                                    <span class="text-danger">{{ $errors->first('category') }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="user">Name</label>&nbsp;
                                    <select class="form-control" name="user" id="user"> 
                                        <option value="0">Select</option>
                                    </select>
                                    <span class="text-danger">{{ $errors->first('user') }}</span>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white pull-right text-right">
                        <a href="{{url('/calendar')}}" class="btn btn-default">Back</a>
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
var url = "{{url('ajaxwebtolead/getStateoptions')}}";
$(function() {
    $(".sidebar-menu li").removeClass("active");

    $("#startDate").datepicker({"dateFormat": 'dd-mm-yy'});
    $("#endDate").datepicker({"dateFormat": 'dd-mm-yy'});

    //Timepicker
    $('#timepicker1').datetimepicker({
        format: 'LT'
    })

    //Timepicker
    $('#timepicker2').datetimepicker({
        format: 'LT'
    })

    //Date picker
    $('#startDate').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
    }).datepicker('setDate', 'today');

    //Date picker
    $('#endDate').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
    }).datepicker('setDate', 'today');


    $("#category").change(function() {
        var category = $(this).val();
        var url = '';
        if (Number(category) > 0) {
            if (Number(category) == 1) {
                url = "{{url('ajax/getAccountselect')}}";
            }
            if (Number(category) == 3) {
                url = "{{url('ajax/getLeadselect')}}";
            }
            if (Number(category) == 2) {
                url = "{{url('ajax/getContactselect')}}";
            }
            $.ajax({
                type: 'GET',
                url: url,
                data: {uid: 0},
                success: function(data) {
                    //                        alert(data);
                    $("#user").html(data);
                }
            });

        } else {
            return false;
        }
    });

});
</script>
@endsection
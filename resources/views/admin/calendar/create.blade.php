@extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Calendar
            <small id="total">0</small>
        </h1>
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
                    <div class='alert alert-danger'>
                        {{session('error')}}
                    </div>
                    @endif
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header with-border">
                            <h3 class="card-title">
                                Create Event
                            </h3>
                        </div>
                        {{Form::open(['action'=>'Admin\CalendarController@store','method'=>'Post','enctype'=>'multipart/form-data'])}}
                        @csrf
                        <div class="card-body">
                            <section class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="user">User</label>&nbsp;
                                        <select class="form-control" name="user" id="user">
                                            {!!$data['useroptions']!!}
                                        </select>
                                        <span class="text-danger">{{ $errors->first('user') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="name">Start Date</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <input type="text" class="form-control" name="startDate" id="startDate" placeholder="" value="{{old('startDate')}}" required>
                                        <span class="text-danger">{{ $errors->first('startDate') }}</span>
                                    </div>

                                    <!-- /.form group -->
                                    <div class="form-group">
                                        <label>Start Time</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <div class="input-group">
                                            <input type="text" class="form-control timepicker" id="startTime" name="startTime" value="{{old('startTime')}}" required>
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                        </div>
                                        <span class="text-danger">{{ $errors->first('startTime') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Category</label>&nbsp;
                                        <select class="form-control" name="category" id="category">
                                            <option value="0">Select Category</option>
                                            <option value="1">Accounts</option>
                                            <option value="2">Contacts</option>
                                            <option value="3">Leads</option>
                                        </select>
                                        <span class="text-danger">{{ $errors->first('category') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Description</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <textarea class="form-control" name="description" id="description">
                                        {{old('description')}}
                                        </textarea>
                                        <span class="text-danger">{{ $errors->first('description') }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Title</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <input type="text" class="form-control" name="title" id="title" placeholder="" value="{{old('title')}}" required>
                                        <span class="text-danger">{{ $errors->first('title') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">End Date</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <input type="text" class="form-control" name="endDate" id="endDate" placeholder="" value="{{old('endDate')}}" required>
                                        <span class="text-danger">{{ $errors->first('endDate') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label>End Time</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <div class="input-group">
                                            <input type="text" class="form-control timepicker" id="endTime" name="endTime" value="{{old('endTime')}}" required>
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                        </div>
                                        <span class="text-danger">{{ $errors->first('endTime') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="member">Select Member</label>&nbsp;
                                        <select class="form-control" name="member" id="member">
                                            <option value="0">Select</option>
                                        </select>
                                        <span class="text-danger">{{ $errors->first('member') }}</span>
                                    </div>
                                </div>
                            </section>
                            <!-- Left col -->
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white border-top">
                            <div class="btn-group float-right">
                                <a href="{{url('/calendar')}}" class="btn btn-default">Back</a>
                                {{Form::submit('Save',['class'=>"btn btn-primary"])}}
                            </div>
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

        $("#startDate").datepicker({
            "dateFormat": 'dd-mm-yy'
        });

        $("#endDate").datepicker({
            "dateFormat": 'dd-mm-yy'
        });

        //Date picker
        // $('#startDate').datepicker({
        //     format: 'dd-mm-yyyy',
        //     autoclose: true,
        // }).datepicker('setDate', 'today');

        // //Date picker
        // $('#endDate').datepicker({
        //     format: 'dd-mm-yyyy',
        //     autoclose: true,
        // }).datepicker('setDate', 'today');


        $("#category").change(function() {
            var user = $("#user").val();

            if (user > 0) {
                var category = $(this).val();
                var url = '';
                // alert('category ' + category);
                if (Number(category) > 0) {
                    if (Number(category) == 1) {
                        url = "{{url('admin/ajax/getAccountselect')}}";
                    }
                    if (Number(category) == 3) {
                        url = "{{url('admin/ajax/getLeadselect')}}";
                    }
                    if (Number(category) == 2) {
                        url = "{{url('admin/ajax/getContactselect')}}";
                    }
                    // alert('url ' + url);
                    $.ajax({
                        type: 'GET',
                        url: url,
                        data: {
                            uid: user
                        },
                        success: function(data) {
                            //                            alert(data);
                            $("#member").html(data);
                        }
                    });

                } else {
                    return false;
                }
            } else {
                $("#category").val(0);
                alert("Please select user...!");
                return false;
            }
        });

    });
</script>
@endsection
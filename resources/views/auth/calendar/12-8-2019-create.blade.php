@extends('layouts.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
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
        <!-- Small boxes (Stat box) -->
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
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            Create Event
                        </h3>
                    </div>
                    {{Form::open(['action'=>'CalendarController@store','method'=>'Post','enctype'=>'multipart/form-data'])}} 
                    @csrf
                    <div class="box-body">
                        <section class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Title</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <input type="text" class="form-control" name="title" id="title" placeholder="" value="{{old('title')}}" required>
                                <span class="text-danger">{{ $errors->first('title') }}</span>
                            </div>

                            <div class="form-group">
                                <label for="name">Start Date/ Time</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group my-group"> 
                                    <input type="text" class="form-control" name="startDate" id="startDate" placeholder="" value="{{old('startDate')}}" required>
                                    <span class="text-danger">{{ $errors->first('startDate') }}</span>
                                    <input type="text" class="form-control timepicker" id="startTime" name="startTime" value="{{old('startTime')}}" required>
                                    <span class="text-danger">{{ $errors->first('startTime') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name">End Date</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group my-group"> 
                                    <input type="text" class="form-control" name="endDate" id="endDate" placeholder="" value="{{old('endDate')}}" required>
                                    <span class="text-danger">{{ $errors->first('endDate') }}</span>
                                    <input type="text" class="form-control timepicker" id="endTime" name="endTime" value="{{old('endTime')}}" required>
                                    <span class="text-danger">{{ $errors->first('endTime') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="allday" id="allday" >
                                &nbsp;
                                <label for="allday">All Day Event</label>
                            </div>


                        </section>
                        <section class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Description</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <textarea class="form-control" name="description" id="description" required>{{old('description')}}</textarea>
                                <span class="text-danger">{{ $errors->first('description') }}</span>
                            </div>

                            <div class="form-group">
                                <label for="location">Location</label>
                                <input type="text" class="form-control" name="location" id="location" placeholder="" value="{{old('location')}}">
                                <span class="text-danger">{{ $errors->first('location') }}</span>
                            </div>
                            <!--                            <div class="form-group">
                                                            <label>Start Time</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control timepicker" id="startTime" name="startTime" value="{{old('startTime')}}" required>
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-clock-o"></i>
                                                                </div>
                                                            </div>
                                                            <span class="text-danger">{{ $errors->first('startTime') }}</span>
                                                        </div>-->
                            <!--                            
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
                            -->


                            <div class="form-group">
                                <label for="type">Type</label>&nbsp;
                                <select class="form-control" name="type" id="type"> 
                                    <?php echo $data['typeoptions']; ?>
                                </select>
                            </div>
                        </section>
                        <div class="form-group col-lg-12">
                            <h4 class="box-title text-primary">Related To</h4>
                        </div>
                        <section class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Related to</label>&nbsp;
                                <select class="form-control" name="category" id="category"> 
                                    <option value="0">Select </option>
                                    <option value="1">Accounts</option>
                                    <option value="2">Contacts</option>
                                    <option value="3">Leads</option>
                                </select>
                                <span class="text-danger">{{ $errors->first('category') }}</span>
                            </div>
                        </section>
                        <section class="col-lg-6">
                            <div class="form-group">
                                <label for="user">Name</label>&nbsp;
                                <select class="form-control" name="user" id="user"> 
                                    <option value="0">Select</option>
                                </select>
                                <span class="text-danger">{{ $errors->first('user') }}</span>
                            </div>
                        </section>
                        <!-- Left col -->
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <div class="btn-group pull-right">
                            <a href="{{url('/calendar')}}" class="btn btn-default">Back</a>
                            {{Form::submit('Save',['class'=>"btn btn-primary"])}}
                        </div>
                    </div>
                    <!-- </form> -->
                    {{Form::close()}}
                </div>
                <!-- /.box -->
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
    var url = "{{url('ajaxwebtolead/getStateoptions')}}";
    $(function() {
        $(".sidebar-menu li").removeClass("active");


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
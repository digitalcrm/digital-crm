@extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
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
                            <?php echo $data['event']->title; ?>
                        </h3>
                    </div>
                    {{Form::open(['action'=>['Admin\CalendarController@update',$data['event']->ev_id],'method'=>'Post','enctype'=>'multipart/form-data'])}}
                    @csrf
                    <div class="card-body">
                        <section class="row">
                            <div class="col-lg-6">
                            <input type="hidden" value="<?php echo $data['uid']; ?>" name="uid" id="uid">
                            <div class="form-group">
                                <label for="name">Title</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <input type="text" class="form-control" name="title" id="title" placeholder="" value="<?php echo $data['event']->title; ?>" required>
                                <span class="text-danger">{{ $errors->first('title') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="name">Start Date</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <input type="text" class="form-control" name="startDate" id="startDate" placeholder="" value="<?php echo date('d-m-Y', strtotime($data['event']->start_date)); ?>" required>
                                <span class="text-danger">{{ $errors->first('startDate') }}</span>
                            </div>

                            <!-- /.form group -->
                            <div class="form-group">
                                <label for="name">End Date</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <input type="text" class="form-control" name="endDate" id="endDate" placeholder="" value="<?php echo date('d-m-Y', strtotime($data['event']->end_date)); ?>" required>
                                <span class="text-danger">{{ $errors->first('endDate') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="name">Category</label>&nbsp;
                                <select class="form-control" name="category" id="category"> 
                                    <option value="0">Select Category</option>
                                    <option value="1" <?php echo $data['Accountcategory']; ?>>Accounts</option>
                                    <option value="2" <?php echo $data['Contactscategory']; ?>>Contacts</option>
                                    <option value="3" <?php echo $data['Leadscategory']; ?>>Leads</option>
                                </select>
                                <span class="text-danger">{{ $errors->first('category') }}</span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Description</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <input type="text" class="form-control" name="description" id="description" placeholder="" value="<?php echo $data['event']->description; ?>" required>
                                <span class="text-danger">{{ $errors->first('description') }}</span>
                            </div>
                            <div class="form-group">
                                <label>Start Time</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group">
                                    <input type="text" class="form-control timepicker" id="startTime" name="startTime" value="<?php echo $data['event']->start_time; ?>" required>
                                    <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                                <span class="text-danger">{{ $errors->first('startTime') }}</span>
                            </div>
                            <div class="form-group">
                                <label>End Time</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group">
                                    <input type="text" class="form-control timepicker" id="endTime" name="endTime" value="<?php echo $data['event']->end_time; ?>" required>
                                    <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                                <span class="text-danger">{{ $errors->first('endTime') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="name">User</label>&nbsp;
                                <select class="form-control" name="user" id="user"> 
                                    <?php echo $data['option']; ?>
                                </select>
                                <span class="text-danger">{{ $errors->first('user') }}</span>
                            </div>
                        </div>
                        </section>
                        <!-- Left col -->
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top">
                        <div class="btn-group pull-right">
                            <a href="{{url('admin/calendar')}}" class="btn btn-default">Back</a>
                            {{Form::hidden('_method','PUT')}}
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
    $(function() {
        $(".sidebar-menu li").removeClass("active");

        //Date picker
        $('#startDate').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
        });

        //Date picker
        $('#endDate').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
        });

        $("#category").change(function() {
            var user = $("#uid").val();

//            if (user > 0) {
            var category = $(this).val();
            var url = '';
//                alert('category ' + category);
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
//                alert('url ' + url);
                $.ajax({
                    type: 'GET',
                    url: url,
                    data: {uid: user},
                    success: function(data) {
//                            alert(data);
                        $("#user").html(data);
                    }
                });

            } else {
                return false;
            }
//            } else {
//                $("#category").val(0);
//                alert("Please select user...!");
//                return false;
//            }
        });
    });
</script>
@endsection
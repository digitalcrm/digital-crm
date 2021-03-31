@extends('layouts.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
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
                            <?php echo $data['event']->title; ?>
                        </h3>
                    </div>
                    {{Form::open(['action'=>['CalendarController@update',$data['event']->ev_id],'method'=>'Post','enctype'=>'multipart/form-data'])}}
                    @csrf
                    <div class="box-body">
                        <section class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Title</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <input type="text" class="form-control" name="title" id="title" placeholder="" value="<?php echo $data['event']->title; ?>" required>
                                <span class="text-danger">{{ $errors->first('title') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="name">Start Date/ Time</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group my-group">
                                    <input type="text" class="form-control" name="startDate" id="startDate" placeholder="" value="<?php echo date('d-m-Y', strtotime($data['event']->start_date)); ?>" required>
                                    <span class="text-danger">{{ $errors->first('startDate') }}</span>
                                    <input type="text" class="form-control timepicker" id="startTime" name="startTime" value="<?php echo $data['event']->start_time; ?>" required>
                                    <span class="text-danger">{{ $errors->first('startTime') }}</span>
                                </div>
                            </div>

                            <!-- /.form group -->
                            <div class="form-group">
                                <label for="name">End Date/ Time</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group my-group">
                                    <input type="text" class="form-control" name="endDate" id="endDate" placeholder="" value="<?php echo date('d-m-Y', strtotime($data['event']->end_date)); ?>" required>
                                    <span class="text-danger">{{ $errors->first('endDate') }}</span>
                                    <input type="text" class="form-control timepicker" id="endTime" name="endTime" value="<?php echo $data['event']->end_time; ?>" required>
                                    <span class="text-danger">{{ $errors->first('endTime') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="allday" id="allday" <?php echo ($data['event']->allday == 1) ? 'checked' : ''; ?>>
                                &nbsp;
                                <label for="allday">All Day Event</label>
                            </div>

                        </section>
                        <section class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Description</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <textarea class="form-control" name="description" id="description" placeholder="" required><?php echo $data['event']->description; ?></textarea>
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
                            <!--                            
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
                            -->

                        </section>


                        <div class="form-group col-lg-12">
                            <h4 class="box-title text-primary">Related To</h4>
                        </div>
                        <section class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Related to</label>&nbsp;
                                <select class="form-control" name="category" id="category"> 
                                    <option value="0">Select ...</option>
                                    <option value="1" <?php echo $data['Accountcategory']; ?>>Accounts</option>
                                    <option value="2" <?php echo $data['Contactscategory']; ?>>Contacts</option>
                                    <option value="3" <?php echo $data['Leadscategory']; ?>>Leads</option>
                                </select>
                                <span class="text-danger">{{ $errors->first('category') }}</span>
                            </div>
                        </section>
                        <section class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Name</label>&nbsp;
                                <select class="form-control" name="user" id="user"> 
                                    <?php echo $data['option']; ?>
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
                            {{Form::hidden('_method','PUT')}}
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
@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
       <!-- Content Header (Page header) -->
	<section class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12">
            <h1><i class="far fa-edit"></i> Edit Territory</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content mt-2 mx-0">
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
                <div class='alert alert-danger'>
                    {{session('error')}}
                </div>
                @endif
                <!-- general form elements -->
                <div class="card shadow card-primary card-outline">
                    <!-- /.card-header -->
                    <!-- form start -->
                    <!-- <form role="form" > -->
                    {{Form::open(['action'=>['TerritoryController@update',$data['territory']['tid']],'method'=>'Post'])}}
                    @csrf
                    <div class="card-body">
                        <!-- Left col -->
                        <section class="col-lg-12">
                            <div class="form-group">
                                <label for="name">Territory Name</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Territory Name" value="<?php echo $data['territory']['name']; ?>" required>
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                        </section>
                        <section class="col-lg-12">
                            <div class="form-group">
                                <label for="subusers">Sub Users</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <select multiple class="form-control" name="subusers[]" required>
                                    <?php echo $data['useroptions']; ?>
                                </select>
                                <span class="text-danger">{{ $errors->first('subuserssubusers') }}</span>
                            </div>
                        </section>
                        <section class="col-lg-12">
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" name="description" id="description"><?php echo $data['territory']['description']; ?></textarea>
                                <span class="text-danger">{{ $errors->first('description') }}</span>
                            </div>
                        </section>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top text-right pull-right">
                            <a href="{{url('/territory')}}" class="btn btn-outline-secondary mr-1">Cancel</a>
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
<script>
    var url = "{{url('ajaxwebtolead/getStateoptions')}}";
    $(function() {
        $(".sidebar-menu li").removeClass("menu-open");
        $(".sidebar-menu li").removeClass("active");
        $("#uldeals").addClass('menu-open');
        $("#uldeals ul").css('display', 'block');
        $("#licreatedeal").addClass("active");


        //Date picker
        $('#datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
        });


        $("#lead").change(function() {
            var acc = $(this).val();
            if (acc == "NewLead") {
                $("#addLead").show();
            } else {
                $("#addLead").hide();
            }
        });


    });
</script>
@endsection
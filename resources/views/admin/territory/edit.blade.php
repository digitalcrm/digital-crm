@extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
{{--     <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Territory
                        <small id="total">{{$data['total']}}</small>
                    </h1>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
    </div> --}}
    <section class="content-header">
        <div class="row">
            <div class="col-lg-10">
                <h1>
                    Edit Territory
                </h1>
            </div>
            <div class="col">
                
            </div>
        </div>
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
                    <div class="card-header with-border">
                        <h3 class="card-title">
                            <?php echo $data['territory']['name']; ?>
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <!-- <form role="form" > -->
                    {{Form::open(['action'=>['Admin\TerritoryController@update',$data['territory']['tid']],'method'=>'Post'])}}
                    @csrf
                    <div class="card-body">
                        <!-- Left col -->
                        <section class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="name">Territory Name</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Territory Name" value="<?php echo $data['territory']['name']; ?>" required>
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="subusers">Sub Users</label>
                                    <select multiple class="form-control" name="subusers[]" required>
                                        <?php echo $data['useroptions']; ?>
                                    </select>
                                </div>
                                <span class="text-danger">{{ $errors->first('subuserssubusers') }}</span>
                            </div>
                        </section>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top text-right pull-right">
                            <a href="{{url('/admin/territory')}}" class="btn btn-outline-secondary">Cancel</a>
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
    var url = "{{url('admin/ajax/getStateoptions')}}";
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#literritory").addClass("active");
        
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
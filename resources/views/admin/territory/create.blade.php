@extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">New Territory
                        {{-- <small id="total">{{$data['total']}}</small> --}}
                    </h1>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
{{--     <section class="content-header">
        <h1>
            Create Account
             <small>Control panel</small> 
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Create Account</li>
        </ol>
    </section> --}}

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
                    {{Form::open(['action'=>'Admin\TerritoryController@store','method'=>'Post'])}}
                    @csrf
                    <div class="card-body">
                        <!-- Left col -->
                        <section class="col-lg-12">
                            <div class="form-group">
                                <label for="user">User</label>
                                <select class="form-control" name="user" id="selectUser" required>
                                    <?php echo $data['useroptions']; ?>
                                </select>
                            </div>
                            <span class="text-danger">{{ $errors->first('user') }}</span>
                            <div class="form-group">
                                <label for="name">Territory Name</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Territory Name" value="{{old('name')}}" required>
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="subusers">Sub Users</label>
                                <select multiple class="form-control" id="subusers"  name="subusers[]" required>
                                    <?php echo $data['subuseroptions']; ?>
                                </select>
                            </div>
                            <span class="text-danger">{{ $errors->first('subusers') }}</span>
                        </section>
                        <!-- /.Left col -->

                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top text-right pull-right">
                            <a href="{{url('admin/territory')}}" class="btn btn-outline-secondary">Cancel</a>
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
    var url = "{{url('admin/ajax/getSubuseroptions')}}";
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#literritory").addClass("active");

        $("#selectUser").change(function() {
            var uid = $(this).val();
            var ajaxUrl = url;
            alert(ajaxUrl);
            $.get(ajaxUrl, {'uid': uid}, function(result) {
                $("#subusers").html(result);
//                alert(result);
            });
        });
    });
</script>
@endsection
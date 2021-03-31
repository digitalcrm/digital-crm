@extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-10">
                    <h1 class="m-0 text-dark">Groups</h1>
                </div>                
                <div class="col-sm-2">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="breadcrumb-item active">Groups</li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>    
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
                <div class="col-lg-8">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                Create Group
                            </h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <!-- <form role="form" > -->
                        {{Form::open(['action'=>'Admin\GroupController@store','method'=>'Post','enctype'=>'multipart/form-data'])}}
                        @csrf
                        <div class="box-body">
                            <section class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">Name</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                    <input type="text" class="form-control" name="name" id="groupname" placeholder="" value="{{old('name')}}" required>
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                </div>
                                <div class="form-group">
                                    <label>Select Users</label>
                                    <select class="form-control" multiple="multiple" name="selectUsers[]" id="selectUser">
                                        <?php echo $data['useroptions']; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Select Products</label>
                                    <select class="form-control" name="selectProducts[]" id="selectProduct">
                                        <?php echo $data['productoptions']; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="description">Description</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                    <textarea class="form-control" name="description" id="description" placeholder="Description" value="{{old('description')}}" rows="7"></textarea>
                                    <span class="text-danger">{{ $errors->first('description') }}</span>
                                </div>
                            </div>
                            </section>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <div class="btn-group btn-flat pull-right">
                                <a href="{{url('admin/groups')}}" class="btn btn-default">Back</a>
                                {{Form::submit('Save',['class'=>"btn btn-primary"])}}
                            </div>
                        </div>
                        <!-- </form> -->
                        {{Form::close()}}
                    </div>
                    <!-- /.box -->
                </div>
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->

        <!-- /.row (main row) -->
	</div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#ligroups").addClass("active");

        $('.select2').select2();
    });
</script>
@endsection

@extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
<!--    <section class="content-header">
        <h1>
            Edit Lead
             <small>Control panel</small> 
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Edit Lead</li>
        </ol>
    </section>-->

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
                <div class="col-lg-6">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <!-- /.card-header -->
                        <!-- form start -->
                        <!-- <form role="form" > -->
                        <div class="card-header with-border">
                            <h3 class="card-title">
                                <?php echo $data['files']->name; ?>
                            </h3>
                        </div>
                        {{Form::open(['action'=>['Admin\FileManagerController@update',$data['files']['file_id']],'method'=>'Post','enctype'=>'multipart/form-data'])}}
                        @csrf
                        <div class="card-body">
                            <section class="col-lg-12">
                                <div class="form-group">
                                    <label for="name">Name</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="" value="{{$data['files']->name}}" required>
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="category">Select Category</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                    <select class="form-control" id="category" name="category" required>
                                        {!!$data['categoryoptions']!!}
                                    </select>
                                    <span class="text-danger">{{ $errors->first('category') }}</span>
                                </div>
                                <div class="form-group">&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                    <label for="file">File</label>
                                    <input type="file" class="btn btn-default" name="file" id="file" required/>
                                    <small>upload only CSV, Word, Excel, PDF, PNG, Gif, Jpeg </small>
                                    <span class="text-danger">{{ $errors->first('file') }}</span>
                                </div>
                            </section>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white border-top">
                            <div class="btn-group btn-flat float-right">
                                <a href="{{url('admin/files')}}" class="btn btn-default">Back</a>
                                {{Form::hidden('_method','PUT')}}
                                {{Form::submit('Save',['class'=>"btn btn-primary"])}}
                            </div>
                        </div>
                        <!-- </form> -->
                        {{Form::close()}}
                    </div>
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
        $(".sidebar-menu li").removeClass("active");
        $("#lifiles").addClass("active");


    });
</script>
@endsection
@extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Documents
             <small>Control panel</small> 
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Documents</li>
        </ol>
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
                    <!-- /.card-header -->
                    <!-- form start -->
                    <!-- <form role="form" > -->
                    <div class="card-header with-border">
                        <h3 class="card-title">
                            <?php echo $data->name; ?>
                        </h3>
                    </div>
                    {{Form::open(['action'=>['Admin\DocumentController@update',$data['doc_id']],'method'=>'Post','enctype'=>'multipart/form-data'])}}
                    @csrf
                    <div class="card-body">
                        <section class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Name</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <input type="text" class="form-control" name="name" id="documentname" placeholder="" value="{{$data->name}}" required>
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                        </section>
                        <section class="col-lg-6">
                            <div class="form-group">
                                <label for="document">Document</label>
                                <input type="file" class="btn btn-default" name="document" id="document" />
                                <small>upload only CSV, Word, Excel, PDF, PNG, Gif, Jpeg </small>
                                <span class="text-danger">{{ $errors->first('document') }}</span>
                            </div>
                        </section>
                        <!-- Left col -->



                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top">
                        <div class="btn-group btn-flat float-right">
                            <a href="{{url('admin/documents')}}" class="btn btn-default">Back</a>
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
    var url = "{{url('ajaxwebtolead/getStateoptions')}}";
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#lidocuments").addClass("active");


    });
</script>
@endsection
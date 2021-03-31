@extends('layouts.adminlte-boot-4.user')
@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->   
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Import Deals</h1>
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

                @if(session('info'))
                <div class='alert alert-warning'>
                    {{session('info')}}
                </div>
                @endif
                <!-- general form elements -->

                <div class="card shadow card-primary card-outline">

                    <!-- /.card-header -->
                    <!-- form start -->
                    <!-- <form role="form" > -->
                    {{Form::open(['action'=>'DealController@importData','method'=>'Post','enctype'=>'multipart/form-data'])}} 
                    @csrf
                    <div class="card-body p-0">
                        <!-- Left col -->
                        <section class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Import</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <input type="file" class="form-control" name="importFile" id="importFile" placeholder="" required>
                                <span class="text-danger">{{ $errors->first('importFile') }}</span>
                                <small>upload <b>.csv</b> files only</small>
                            </div>
                            <div class="form-group">
                                <p><a href="<?php echo url('uploads/samples/tbl_deals_upload.csv'); ?>">click here to download sample csv file</a></p>
                            </div>
                        </section>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top pull-right text-right">
                        <div class="btn-group btn-flat pull-right">
                            <a href="{{url('/deals')}}" class="btn btn-default">Back</a>
                            {{Form::submit('Save',['class'=>"btn btn-primary"])}}
                        </div>

                    </div>
                    <!-- </form> -->
                    {{Form::close()}}
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
        $("#lideals").addClass("active");
    });
</script>
@endsection
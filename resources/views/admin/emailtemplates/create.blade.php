@extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="row">
            <div class="col-md-10">                
                <h1 class="pb-2">
                    New Email Template
             </h1>
         </div>
         <div class="col-md-2">
            
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
                <div class='alert alert-success'>
                    {{session('error')}}
                </div>
                @endif
                <!-- general form elements -->
                <!-- Horizontal Form -->
                <div class="card shadow card-primary card-outline">
                    
                    <!-- /.card-header -->
                    <!-- form start -->
                    {{Form::open(['action'=>'Admin\EmailTemplateController@store','method'=>'Post','class' => 'form-horizontal'])}} 
                    @csrf
                    <div class="card-body">
                        <!--                        <div class="form-group">
                                                    <label for="name" class="col-sm-2 control-label">Name</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control" id="ecat_id" name="ecat_id">
                        <?php // echo $data['department_options']; ?>
                                                        </select>
                                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                                    </div>
                                                </div>-->
                        <div class="form-group">
                            <label for="subject" class="col-sm-12 control-label">Subject</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" value="{{old('subject')}}" required>
                                <span class="text-danger">{{ $errors->first('subject') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message" class="col-sm-12 control-label">Message</label>
                            <div class="col-sm-12">
                                <!--<input type="text" class="form-control" name="message" id="message" placeholder="Message" value="{{old('message')}}" required>-->
                                <textarea id="message" name="message" rows="10" cols="80" required></textarea>
                                <span class="text-danger">{{ $errors->first('message') }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top text-right pull-right">
                            <a class="btn btn-outline-secondary" href="{{url('admin/emailtemplates')}}">Back</a>
                            {{Form::submit('Create',['class'=>"btn btn-primary"])}}
                    </div>
                    <!-- /.card-footer -->
                    <!--</form>-->
                    {{Form::close()}}
                </div>
                <!-- /.card -->

            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->

	</div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- CK Editor -->
<script src="{{asset('assets/bower_components/ckeditor/ckeditor.js')}}"></script>
<script>
$(function() {
    CKEDITOR.replace('message');

    $(".sidebar-menu li").removeClass("active");
    $("#lisettings").addClass("active");
});
</script>
@endsection
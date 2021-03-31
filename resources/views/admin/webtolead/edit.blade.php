 @extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-10">
                    <h1 class="m-0 text-dark">Edit Web to Lead Form</h1>
                </div>                
                <div class="col-sm-2">
                    
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>


    <!-- Main content -->
    <section class="content mt-2 mx-0">
	<div class="container-fluid">
        <!-- Small cardes (Stat card) -->
        <div class="row">
            <div class="col-lg-8">
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
                <div class="card shadow card-primary card-outline">
                    <!-- /.card-header -->
                    <!-- form start -->
                    <!-- <form role="form" > -->
                    {{Form::open(['action'=>['Admin\WebtoleadController@update',$form->form_id],'method'=>'Post'])}}
                    <!-- {{csrf_field()}} -->
                    <div class="card-body">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" id="title" placeholder="Enter Form Title" value="{{$form->title}}">
                            <span class="text-danger">{{ $errors->first('title') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="posturl">Post Url</label>
                            <input type="text" class="form-control" name="post_url" id="post_url" placeholder="Post Url" value="{{$form->post_url}}">
                            <span class="text-danger">{{ $errors->first('post_url') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="redirecturl">Redirect Url</label>
                            <input type="text" class="form-control" name="redirect_url" id="redirect_url" placeholder="Redirect Url" value="{{$form->redirect_url}}">
                            <span class="text-danger">{{ $errors->first('redirect_url') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="frommail">From Mail</label>
                            <input type="text" class="form-control" name="from_mail" id="from_mail" placeholder="From Mail Id" value="{{$form->from_mail}}">
                            <span class="text-danger">{{ $errors->first('from_mail') }}</span>
                        </div> 
                        <div class="form-group">
                            <div class="card-header with-border">
                                <h3 class="card-title">Autoresponder</h3>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" value="{{$form->subject}}">
                            <span class="text-danger">{{ $errors->first('subject') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea name="message" id="message" rows="7" class="form-control" >{{$form->message}}</textarea>
                            <span class="text-danger">{{ $errors->first('message') }}</span>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top text-right pull-right">
                        <a href="{{url('admin/webtolead')}}" class="btn btn-outline-secondary">Back</a>
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
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#liwebtolead").addClass("active");
    });
</script>
@endsection
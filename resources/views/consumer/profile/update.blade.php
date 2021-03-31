  @extends('layouts.adminlte-boot-4.admin')
  @section('content')


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Admin Profile</h1>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>

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
          <div class="card card-primary">
            <div class="card-header with-border">
              <h3 class="card-title">
                <img src="<?php echo ($data['userdata']['picture']!=null)?url($data['userdata']['picture']):'#';?>" height="25" width="25"/>
                {{$data['userdata']['name']}}
              </h3>
            </div>
            {{Form::open(['action'=>['Admin\AdminController@adminUpdate',$data['userdata']['id']],'method'=>'Post','enctype'=>'multipart/form-data','class'=>'form-horizontal'])}}
              @csrf
              <div class="card-body">
                <div class="form-group">
                  <label for="username" class="col-sm-2 control-label">Name</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="username" id="username" placeholder="Enter Name" value="{!!$data['userdata']['name']!!}">
                    <span class="text-danger">{{ $errors->first('username') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="email" class="col-sm-2 control-label">Email</label>
                  <div class="col-sm-10">
                    <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="{!!$data['userdata']['email']!!}" readonly>
                  </div>
                </div>                
                
                <div class="form-group">
                  <label for="profilepicture" class="col-sm-2 control-label">Profile Picture</label>
                  <div class="col-sm-10">
                    <input type="file" class="btn btn-default" id="profilepicture" name="profilepicture"  accept="image/*" >
                  </div>
                </div>
                
                
              </div>
              <!-- /.card-body -->
              <div class="card-footer bg-white border-top">
                <!-- <button type="submit" class="btn btn-default">Cancel</button> -->
                <!-- <button type="submit" class="btn btn-info pull-right">Sign in</button> -->
                {{Form::hidden('_method','PUT')}}
                {{Form::submit('Save',['class'=>"btn btn-primary float-right"])}}
              </div>
              <!-- /.card-footer -->
            </form>
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
  $(function () {
    $(".active").removeClass("active");
    // $("#ulwebtolead").addClass('menu-open');
    // $("#ulwebtolead ul").css('display', 'block');
    // $("#licreateform").addClass("active");
  });
</script>
  @endsection
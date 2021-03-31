 @extends('layouts.adminlte-boot-4.admin')
  @section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Create User
        <!-- <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Create Lead</li>
      </ol>
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
          <div class="card card-primary">
            <!-- <form role="form" > -->
              {{Form::open(['action'=>'Admin\UserController@store','method'=>'Post'])}}
              @csrf
              <div class="card-body">
                <!-- Left col col-lg-6-->
                <section class="">
                  <div class="form-group">
                    <label for="first_name">Name</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                    <input type="text" class="form-control" name="username" id="username" placeholder="Enter Name" value="{{old('username')}}" required>
                    <span class="text-danger">{{ $errors->first('username') }}</span>
                  </div>
                  <div class="form-group">
                    <label for="usermail">Email</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Enter Email Id" value="{{old('email')}}" required>
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                  </div>
                  <div class="form-group">
                    <label for="mobile">Password</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                    <input type="password" class="form-control" name="userpass" id="userpass" placeholder="Enter Password" value="" required>
                    <span class="text-danger">{{ $errors->first('userpass') }}</span>
                  </div>
                 </section>
              </div>
              <!-- /.card-body -->
              <div class="card-footer bg-white border-top">
                {{Form::submit('Save',['class'=>"btn btn-primary pull-right"])}}
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
  $(function () {
    $(".sidebar-menu li").removeClass("active");
    $("#ulusers").addClass('menu-open');
    $("#ulusers ul").css('display', 'block');
    $("#licreateuser").addClass("active");
  });
</script>
  @endsection
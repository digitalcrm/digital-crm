 @extends('layouts.adminlte-boot-4.admin')
  @section('content')



  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
        <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-0">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">{!!$data['form']->title!!}</h1>
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

            <div class="card shadow card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">{!!$data['formleads']->first_name!!}</h3>
                <!--<a href="{{$data['addtoleadLink']}}" class="btn btn-success pull-right">{!!$data['addtoleadbutton']!!}</a>-->
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label for="title">Name</label>
                  <p>{!!$data['formleads']->first_name!!}</p>
                </div>
                <div class="form-group">
                  <label for="posturl">Email</label>
                  <p>{!!$data['formleads']->email!!}</p>
                </div>
                <div class="form-group">
                  <label for="redirecturl">Mobile</label>
                  <p>{!!$data['formleads']->mobile!!}</p>
                </div>
                <div class="form-group">
                  <label for="frommail">Website</label>
                  <p>{!!$data['formleads']->website!!}</p>
                </div>
                <div class="form-group">
                  <label for="message">Notes</label>
                  <p>{!!$data['formleads']->notes!!}</p>
                </div>
              </div>
              <div class="card-footer bg-white border-top text-right pull-right">
                <!--<a href="#" class="btn btn-danger pull-left">Delete</a>-->
                <a href="{{url('admin/webtolead/formleads/'.$data['form']->form_id)}}" class="btn btn-outline-secondary">Back</a>
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
        <section class="col-lg-10">

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
    $("#ulwebtolead").removeClass("active");
    $("#ulwebtolead").addClass('menu-open');
    $("#ulwebtolead ul").css('display', 'block');
    $("#liwebtolead").addClass("active");
  });


</script>
  @endsection
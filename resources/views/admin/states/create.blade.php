 @extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">New State
                    </h1>
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
                <!-- Horizontal Form -->
                <div class="card shadow card-primary card-outline">
                    <!-- /.card-header -->
                    <!-- form start -->
                    {{Form::open(['action'=>'Admin\StateController@store','method'=>'Post','class' => 'form-horizontal'])}} 
                    @csrf
                    <div class="card-body">
                        <section class="row">
                            <div class="col-lg-12">
                            <div class="form-group">
                                <label for="country" class="col-sm-12 control-label">Country</label>
                                <div class="col-sm-12">
                                    <select name="country" id="country" class="form-control">
                                        <?php echo $data['countryOptions']; ?>
                                    </select>
                                    <span class="text-danger">{{ $errors->first('country') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="name" class="col-sm-12 control-label">State</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="name" id="name" placeholder="State" value="{{old('name')}}" required>
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                </div>
                            </div>
                        </div>
                        </section>


                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top text-right pull-right">
                        <a href="{{url('admin/country')}}" class="btn btn-outline-secondary">Back</a>
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
<script>
    $(function() {
        $(".sidebar-menu li").removeClass("active");
    });
</script>
@endsection
 @extends('layouts.adminlte-boot-4.admin')
@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Currency
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
            <div class="col-lg-12">
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
                            Country
                        </h3>

                    </div>
                    <div class="card-body">
                        <section class="row">
                            <div>
                                <div class="form-group">
                                    <label for="title">Country</label>
                                    <p><?php echo $data->name; ?></p>
                                </div>

                            </div>
                            <div class="col-lg-6">

                                <div class="form-group">
                                    <label for="title">Sort Name</label>
                                    <p><?php echo $data->sortname; ?></p>
                                </div>
                            </div>        
                        </section>
                    </div>
                    <div class="card-footer bg-white border-top">
                        <a href="#" class="btn btn-danger float-left">Delete</a>
                        <a href="{{url('admin/country')}}" class="btn btn-primary float-right">Back</a>
                    </div>
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
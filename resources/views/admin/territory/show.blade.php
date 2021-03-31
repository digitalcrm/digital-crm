@extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
        <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Territory User Show
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
            <div class="col-lg-10">
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
                        <h3 class="card-title">
                            <?php echo $data['territory']['name']; ?>
                        </h3>
                    </div>
					<div class="card-header">
                        <div class="form-group">
                                <label for="name">User</label>
                                <p><?php echo $data['user']->name; ?></p>
                            </div>
                    </div>
				   <div class="card-body p-0">
                        <section class="">
                            <?php echo $data['subusertable']; ?>
                        </section>
                    </div>
                    <div class="card-footer bg-white border-top">
                        <a href="{{url('admin/territory')}}" class="btn btn-outline-secondary float-right">Back</a>
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
    $(function() {
        $(".sidebar-menu li").removeClass("menu-open");
        $(".sidebar-menu li").removeClass("active");
        $("#literritory").addClass("active");
    });


</script>
@endsection
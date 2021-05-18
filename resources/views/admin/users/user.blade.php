@extends('layouts.adminlte-boot-4.admin')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Users <span class="badge badge-secondary">{!!$data['total']!!}</span></h1>
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
                <div class="col-lg-12 p-0">
                    <x-alert />
                    <div class="card shadow card-primary card-outline">
                        <!-- /.card-header -->
                        <div class="card-body p-0 table-responsive">
                            {!!$data['table']!!}
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                    <div id="resulttt">

                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    $(function () {
        // $(".sidebar-menu li").removeClass("active");
        //    $("#ulusers").addClass('menu-open');
        //    $("#ulusers ul").css('display', 'block');
        // $("#liusers").addClass("active");
    });

</script>
@endsection

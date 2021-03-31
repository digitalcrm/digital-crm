@extends('layouts.user')
@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $data['deals']['name']; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Deal</li>
        </ol>
    </section>



    <!-- Main content -->
    <section class="content mt-2 mx-0">
	<div class="container-fluid">
        <!-- Small boxes (Stat box) -->
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
                <div class="col-lg-6">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <?php echo $data['deals']['name']; ?>
                            </h3>
                        </div>
                        <div class="box-body">
                            <section class="col-lg-6">
                                <div class="form-group">
                                    <label for="">Name</label>
                                    <p><?php echo $data['deals']['name']; ?></p>
                                </div>

                                <div class="form-group">
                                    <label for="">Lead</label>
                                    <p><?php echo $data['deals']['tbl_leads']['first_name'] . ' ' . $data['deals']['tbl_leads']['last_name']; ?></p>
                                </div>

                                <div class="form-group">
                                    <label for="">Deal Stage</label>
                                    <p><?php echo $data['deals']['tbl_salesfunnel']['salesfunnel']; ?></p>
                                </div>


                                <div class="form-group">
                                    <label for="">Lead Source</label>
                                    <p><?php echo $data['deals']['tbl_leadsource']['leadsource']; ?></p>
                                </div>

                                <div class="form-group">
                                    <label for="">Amount</label>
                                    <p><?php echo $data['deals']['value']; ?></p>
                                </div>

                                <div class="form-group">
                                    <label for="">Closing Date</label>
                                    <p><?php echo date('d-m-Y', strtotime($data['deals']['closing_date'])); ?></p>
                                </div>

                                <div class="form-group">
                                    <label for="">Notes</label>
                                    <p><?php echo $data['deals']['notes']; ?></p>
                                </div>
                            </section>
                        </div>
                        <div class="box-footer">
                            <!--<a href="#" class="btn btn-danger pull-left">Delete</a>-->
                            <a href="{{url('sales')}}" class="btn btn-primary pull-right">Back</a>
                        </div>
                    </div>
                </div>

                <!-- /.box -->

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
        $(".sidebar-menu li").removeClass("active");
        $("#licustomers").addClass("active");
    });


</script>
@endsection
@extends('layouts.adminlte-boot-4.admin')
@section('content')

<!--Style-->
<style type="text/css">


</style>
<!--/Style-->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="row">
            <div class="col-md-7">                
                <h1>
                    Emails <small id="total" class="badge badge-secondary">{{$data['total']}}</small>
             </h1>
         </div>
         <div class="col-md-5 text-right pull-right">
            <a class="btn btn-primary px-3" href="{{url('admin/emails/create')}}"><i class="far fa-plus-square mr-1"></i> New Email</a>
        </div>
    </div>
</section>
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
                <div class="card shadow card-primary card-outline">
				<div class="card-header">
					                        <div class="row">
                            <div class="col-md-12">
                                <p>
                                    <b>thankyou@digitalcrm.com</b> is configured to send mail. If you want to change please follow below instructions</p>
                                    
                                    <p>1. Go to project folder <b>project folder/.env</b> and open <b>.env</b> file</p>
                                    <p>2. Find the following

                                <ul>
                                    <li>MAIL_DRIVER</li>
                                    <li>MAIL_HOST</li>
                                    <li>MAIL_PORT</li>
                                    <li>MAIL_USERNAME</li>
                                    <li>MAIL_PASSWORD</li>
                                    <li>MAIL_ENCRYPTION</li>
                                </ul></p>
                                <p>3. Please given valid details.</p>
                                
                            </div>
                        
                    </div>
</div>
					
                    <!-- /.card-header -->
                    <div class="card-body p-0" id="table">
                    {!!$data['table']!!}
					</div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top">
                        <a href="<?php echo url('admin/settings'); ?>" class="btn btn-outline-secondary float-right">Back</a>
                    </div>
                </div>
                <!-- /.card -->

            </div>
        </div>
        <!-- /.row -->
	</div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>

    $(function() {
//        alert('hi');
        $(".sidebar-menu li").removeClass("active");
        $("#lisettings").addClass("active");


    });



</script>
@endsection
@extends('layouts.adminlte-boot-4.user')
@section('content')

<!--Style-->
<style type="text/css">


</style>
<!--/Style-->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Latest Form Leads
            <small>{{$data['total']}}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{url('auth/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class=""><a href="{{url('webtolead')}}">Web to lead</a></li>
            <li class="active">Form leads</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content mt-2 mx-0">
	<div class="container-fluid">
        <!-- Small boxes (Stat box) -->
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
                    <!--             <div class="box-header">
                                  <h3 class="box-title">Data Table With Full Features</h3>
                                </div> -->
                    <!-- /.box-header -->
                    <div class="card-body p-0">
                        {!!$data['table']!!}
                    </div>
                    <!-- /.box-body -->
                    <div class="card-footer bg-white border-top pull-right text-right">
                        <a href="{{url('webtolead')}}" class="btn btn-outline-secondary">Back</a>
                    </div>
                </div>
                <!-- /.box -->

            </div>
        </div>
        <!-- /.row -->
	</div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
var url = "{{url('ajaxwebtolead/previewForm')}}";
$(function() {

  // alert('active');

  $(".active").removeClass("active");
//  $("#ulwebtolead").addClass('menu-open');
//  $("#ulwebtolead ul").css('display', 'block');
  $("#liwebtolead").addClass("active");
});


</script>
@endsection

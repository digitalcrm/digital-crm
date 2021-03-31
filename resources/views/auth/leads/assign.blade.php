@extends('layouts.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

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
                <div class='alert alert-danger'>
                    {{session('error')}}
                </div>
                @endif
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            Assign Lead
                        </h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <!-- <form role="form" > -->
                    <!-- {{Form::open(['action'=>'LeadController@store','method'=>'Post'])}} -->
                    <form action="{{url('leads/assign/assigntoUser')}}" method="post">
                        @csrf
                        <div class="box-body">
                            <!-- Left col -->
                            <section class="col-lg-6">
                                <div class="form-group">
                                    <label for="lead">Lead</label>
                                    <input type="text" class="form-control" name="lead" id="lead" placeholder="Lead" value="{{$data['lead']->first_name.' '.$data['lead']->last_name}}" readonly>
                                    <input type="hidden" name="ld_id" id="ld_id" value="{{$data['lead']->ld_id}}" >
                                    <span class="text-danger">{{ $errors->first('company') }}</span>
                                </div>
                            </section>
                            <!-- /.Left col -->
                            <section class="col-lg-6">

                                <div class="form-group">
                                    <label for="subusers">Sub Users</label>
                                    <select class="form-control" name="subusers" id="subusers" required="">
                                        {!!$data['useroptions']!!}
                                    </select>
                                </div>
                            </section>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <div class="btn-group btn-flat pull-right">
                                <a href="{{url('/leads')}}" class="btn btn-default">Back</a>
                                {{Form::submit('Save',['class'=>"btn btn-primary"])}}
                            </div>

                        </div>
                        <!-- </form> -->
                        {{Form::close()}}
                </div>
                <!-- /.box -->
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
    var url = "{{url('ajaxwebtolead/getStateoptions')}}";
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#lileads").addClass("active");


    });
</script>
@endsection
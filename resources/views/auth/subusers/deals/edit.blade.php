@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
<!--    <section class="content-header">
        <h1>
            Edit Deal
             <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Edit Deal</li>
        </ol>
    </section>-->

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
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <?php echo $data['deal']['name']; ?>
                        </h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <!-- <form role="form" > -->
                    {{Form::open(['action'=>['DealController@update',$data['deal']['deal_id']],'method'=>'Post'])}}
                    @csrf
                    <div class="box-body">
                        <!-- Left col -->
                        <section class="col-lg-6">
                            <div class="form-group">
                                <label for="first_name">Deal Name</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <input type="text" class="form-control" name="dealname" id="dealname" placeholder="" value="{{$data['deal']['name']}}" required>
                                <span class="text-danger">{{ $errors->first('dealname') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="lead">Lead</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <select class="form-control" name="lead" id="lead" required>
                                    {!!$data['leadoptions']!!}
                                </select>
                                <br>
                                <input class="form-control" type="text" name="addLead" id="addLead" style="display: none;" />
                                <span class="text-danger">{{ $errors->first('lead') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="leadsource">Lead Source</label>
                                <select class="form-control" name="leadsource" id="leadsource">
                                    {!!$data['leadsourceoptions']!!}
                                </select>
                                <span class="text-danger">{{ $errors->first('leadsource') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea name="notes" id="notes" class="form-control" rows="5">{{$data['deal']['notes']}}</textarea>
                            </div>
                            <span class="text-danger">{{ $errors->first('notes') }}</span>
                        </section>
                        <!-- /.Left col -->
                        <section class="col-lg-6">
                            <div class="form-group">
                                <label for="last_name">Deal Stage</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <select class="form-control" name="dealstage" id="dealstage" required>
                                    {!!$data['dealstageoptions']!!}
                                </select>

                                <span class="text-danger">{{ $errors->first('dealstage') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="userpicture">Amount</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group">
                                    <span class="input-group-addon">{!!$data['user']['currency']['html_code']!!}</span>
                                    <input type="text" name="amount" id="amount" class="form-control" placeholder="" value="{{$data['deal']['value']}}" required>
                                </div>
                                <span class="text-danger">{{ $errors->first('amount') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="phone">Closing Date</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" name="closingdate" id="datepicker" value="{{date('d-m-Y',strtotime($data['deal']['closing_date']))}}" required>
                                    <span class="text-danger">{{ $errors->first('closingdate') }}</span>
                                </div>
                            </div>
                        </section>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        {{Form::hidden('_method','PUT')}}
                        {{Form::submit('Save',['class'=>"btn btn-primary pull-right"])}}
                        <a href="{{url('deals')}}" class="btn btn-primary">Back</a>
                    </div>
                    <!-- </form> -->
                    {{Form::close()}}
                </div>
                <!-- /.box -->
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
    var url = "{{url('ajaxwebtolead/getStateoptions')}}";
    $(function () {
        $(".sidebar-menu li").removeClass("menu-open");
        $(".sidebar-menu li").removeClass("active");
        $("#uldeals").addClass('menu-open');
        $("#uldeals ul").css('display', 'block');
        $("#licreatedeal").addClass("active");


        //Date picker
        $('#datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
        });


        $("#lead").change(function () {
            var acc = $(this).val();
            if (acc == "NewLead") {
                $("#addLead").show();
            } else {
                $("#addLead").hide();
            }
        });


    });
</script>
@endsection

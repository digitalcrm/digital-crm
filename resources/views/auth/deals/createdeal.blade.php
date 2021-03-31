@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1><i class="far fa-edit"></i> New Deal</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
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
                    <!-- general form elements -->
                    <div class="card shadow card-primary card-outline">

                        <!-- /.card-header -->
                        <!-- form start -->
                        <!-- <form role="form" > -->
                        {{Form::open(['action'=>'DealController@store','method'=>'Post'])}}
                        @csrf
                        <div class="card-body">
                            <!-- Left col -->
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="first_name">Deal Name</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <input type="text" class="form-control" name="dealname" id="dealname" placeholder="" value="{{old('dealname')}}" required>
                                        <span class="text-danger">{{ $errors->first('dealname') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="lead">Lead</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <input type="text" class="form-control" name="leadname" id="leadname" placeholder="" value="{{$data['leaddetails']->first_name.' '.$data['leaddetails']->last_name}}" readonly>
                                        <input class="form-control" type="text" name="lead" id="lead" style="display: none;" value="{{$data['leaddetails']->ld_id}}" />
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name">Deal Stage</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <select class="form-control" name="dealstage" id="dealstage" required>
                                            {!!$data['dealstageoptions']!!}
                                        </select>
                                        <span class="text-danger">{{ $errors->first('dealstage') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="userpicture">Amount</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <!--                                <div class="input-group">
                                                                    <span class="input-group-addon">{!!$data['user']['currency']['html_code']!!}</span>
                                                                    <input type="text" name="amount" id="amount" class="form-control" placeholder="" value="{{old('amount')}}" required>
                                                                </div>
                                                                <span class="text-danger">{{ $errors->first('amount') }}</span>-->

                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{!!$data['user']['currency']['html_code']!!}</span>
                                            </div>
                                            <input type="text" name="amount" id="amount" class="form-control" placeholder="" value="{{old('amount')}}" required>
                                            <span class="text-danger">{{ $errors->first('amount') }}</span>
                                        </div>
                                    </div>
                                    <!--                            <div class="form-group">
                                                            <label for="phone">Closing Date</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                                            <div class="input-group date">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </div>
                                                                <input type="text" class="form-control pull-right" name="closingdate" id="datepicker" required value="<?php echo date('d-m-Y'); ?>">
                                                                <span class="text-danger">{{ $errors->first('closingdate') }}</span>
                                                            </div>
                                                        </div>-->
                                    <div class="form-group">
                                        <label for="closingdate">Closing Date</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="closingdate" id="datepicker" placeholder="" value="<?php echo date('d-m-Y'); ?>" required>
                                            <span class="text-danger">{{ $errors->first('closingdate') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.Left col -->
                                <div class="col-lg-6">

                                    <div class="form-group">
                                        <label for="product">Product</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <select class="form-control" name="pro_id" id="pro_id">
                                            {!!$data['productoptions']!!}
                                        </select>
                                        <span class="text-danger">{{ $errors->first('product') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="product">Deal Type</label>&nbsp;
                                        <!-- <i class="fa fa-asterisk text-danger"></i> -->
                                        <select class="form-control" name="dl_id" id="dl_id">
                                            {!!$data['dealtype_options']!!}
                                        </select>
                                        <span class="text-danger">{{ $errors->first('dl_id') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="userpicture">Probability</label>
                                        <!--                                <div class="input-group">
                                                                    <span class="input-group-addon">%</span>
                                                                    <input type="number" name="probability" id="probability" class="form-control" placeholder="" value="{{old('probability')}}" max="100">
                                                                </div>
                                                                <span class="text-danger">{{ $errors->first('probability') }}</span>-->

                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">%</span>
                                            </div>
                                            <input type="number" name="probability" id="probability" class="form-control" placeholder="" value="{{old('probability')}}" max="100">
                                            <span class="text-danger">{{ $errors->first('probability') }}</span>
                                        </div>
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
                                        <textarea name="notes" id="notes" class="form-control" rows="5"></textarea>
                                        <span class="text-danger">{{ $errors->first('leadsournotesce') }}</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white border-top pull-right text-right">
                            <a href="{{url('deals')}}" class="btn btn-outline-secondary mr-1">Cancel</a>
                            {{Form::submit('Save',['class'=>"btn btn-primary"])}}
                        </div>
                        <!-- </form> -->
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
<!--JQuery Datepicker-->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    var url = "{{url('ajaxwebtolead/getStateoptions')}}";
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#lideals").addClass("active");

        $("#datepicker").datepicker({
            "dateFormat": 'dd-mm-yy'
        });

        //Date picker
        // $('#datepicker').datepicker({
        //     format: 'dd-mm-yyyy',
        //     autoclose: true,
        // });


        $("#lead").change(function() {
            var acc = $(this).val();
            if (acc == "NewLead") {
                $("#addLead").show();
            } else {
                $("#addLead").hide();
            }
        });

        $("#probability").keyup(function() {
            var pro = $(this).val();
            if (pro == '') {
                $(this).val(0);
            }
        });

    });
</script>
@endsection
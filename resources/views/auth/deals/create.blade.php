@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1><i class="far fa-edit"></i> New Deal</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content mx-0">
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
                    <div class="card shadow card-primary card-outline">
                        <!-- /.card-header -->
                        <!-- form start -->
                        <!-- <form role="form" > -->
                        {{Form::open(['action'=>'DealController@store','method'=>'Post'])}}
                        @csrf
                        <div class="card-body">
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="first_name">Deal Name</label>
                                        <div class="col-md-9">
										<input type="text" class="form-control required" name="dealname" id="dealname" placeholder="" value="{{old('dealname')}}" required>
                                        <span class="text-danger">{{ $errors->first('dealname') }}</span>
										</div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="lead">Lead</label>
                                        <div class="col-md-9">
										<select class="form-control required" name="lead" id="lead" required>
                                            {!!$data['leadoptions']!!}
                                        </select>
                                        
                                        <input class="form-control mt-3" type="text" name="addLead" id="addLead" style="display: none;" />
                                        <span class="text-danger">{{ $errors->first('lead') }}</span>
										</div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="last_name">Deal Stage</label>
                                        <div class="col-md-9">
										<select class="form-control required" name="dealstage" id="dealstage" required>
                                            {!!$data['dealstageoptions']!!}
                                        </select>
                                        <span class="text-danger">{{ $errors->first('dealstage') }}</span>
										</div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="userpicture">Amount</label>
                                        <div class="col-md-9">
										<div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{!!$data['user']['currency']['html_code']!!}</span>
                                            </div>
                                            <input type="text" name="amount" id="amount" class="form-control required" placeholder="" value="{{old('amount')}}" required>
                                            <span class="text-danger">{{ $errors->first('amount') }}</span>
                                        </div>
										</div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="closingdate">Closing Date</label>
                                        <div class="col-md-9">
										<div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                            </div>
                                            <input type="text" class="form-control required" name="closingdate" id="datepicker" placeholder="" value="<?php echo date('d-m-Y'); ?>" required>
                                            <span class="text-danger">{{ $errors->first('closingdate') }}</span>
                                        </div>
										</div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="product">Product</label>
                                        <div class="col-md-9">
										<select class="form-control required" name="product" id="product" required>
                                            {!!$data['productoptions']!!}
                                        </select>
                                        <input type="hidden" id="pro_id" name="pro_id">
                                        <span class="text-danger">{{ $errors->first('product') }}</span>
										</div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="dl_id">Deal Type</label>
                                        <!-- <i class="fa fa-asterisk text-danger"></i> -->
                                        <div class="col-md-9">
										<select class="form-control" name="dl_id" id="dl_id">
                                            {!!$data['dealtype_options']!!}
                                        </select>
                                        <span class="text-danger">{{ $errors->first('dl_id') }}</span>
										</div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="leadsource">Lead Source</label>
                                        <div class="col-md-9">
										<select class="form-control" name="leadsource" id="leadsource">
                                            {!!$data['leadsourceoptions']!!}
                                        </select>
                                        <span class="text-danger">{{ $errors->first('leadsource') }}</span>
										</div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="userpicture">Probability</label>
                                        <div class="col-md-9">
										<div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">%</span>
                                            </div>
                                            <input type="number" name="probability" id="probability" class="form-control" placeholder="" value="{{old('probability')}}" max="100">
                                            <span class="text-danger">{{ $errors->first('probability') }}</span>
                                        </div>
										</div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="notes">Notes</label>
                                        <div class="col-md-9">
										<textarea name="notes" id="notes" class="form-control" rows="5"></textarea>
                                        <span class="text-danger">{{ $errors->first('notes') }}</span>
										</div>
                                    </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white border-top pull-right text-right">
                            <a href="{{url('deals')}}" class="btn btn-outline-secondary mr-1">Cancel</a>
                            {{Form::submit('Create',['class'=>"btn btn-primary"])}}
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
    var getleadData = "{{url('leads/get/product/{id}')}}";

    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#lideals").addClass("active");

        $("#datepicker").datepicker({
            "dateFormat": 'dd-mm-yy'
        });

        //Date picker
        //        $('#datepicker').datepicker({
        //            format: 'dd-mm-yyyy',
        //            autoclose: true,
        //        });

        $("#lead").change(function() {
            var acc = $(this).val();
            if (acc == "NewLead") {
                $("#addLead").show();
            } else {
                $("#addLead").hide();

                $.get(getleadData, {
                    'lead': acc
                }, function(result) {
                    // alert(result);
                    if (Number(result) > 0) {
                        $("#product").val(result).attr('disabled', true);
                        $("#pro_id").val(result);
                    } else {
                        $("#product").val('').attr('disabled', false);
                        $("#pro_id").val('');
                        alert("Lead didn't have product");
                    }
                });

            }
        });

        $("#probability").keyup(function() {
            var pro = $(this).val();
            if (pro == '') {
                $(this).val(0);
            }
        });

        $("#product").change(function() {
            var p = $(this).val();
            $("#pro_id").val(p);
        });

    });
</script>
@endsection
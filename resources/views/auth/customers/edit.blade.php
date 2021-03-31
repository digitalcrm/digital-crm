@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1><i class="far fa-edit"></i> Edit Customer</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content mt-2 mx-0">
        <div class="container-fluid">
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
            <div class="container-fluid">

                <div class="card shadow card-primary card-outline">
                    {{Form::open(['action'=>['CustomersController@updateCustomer',$data['deal']['deal_id']],'method'=>'Post'])}}
                    @csrf
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="first_name">Deal Name</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                    <input type="text" class="form-control" name="dealname" id="dealname" placeholder="" value="{{$data['deal']['name']}}" required>
                                    <span class="text-danger">{{ $errors->first('dealname') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="lead">Lead</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                    <input type="hidden" class="form-control" name="lead" id="lead" value="{{$data['lead']['ld_id']}}" readonly>
                                    <input type="text" class="form-control" name="leadname" id="leadname" value="{{$data['lead']['first_name'].' '.$data['lead']['last_name']}}" readonly>
                                    <span class="text-danger">{{ $errors->first('lead') }}</span>
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
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">{!!$data['user']['currency']['html_code']!!}</span>
                                        </div>
                                        <input type="text" name="amount" id="amount" class="form-control" placeholder="" value="{{$data['deal']['value']}}" required>
                                        <span class="text-danger">{{ $errors->first('amount') }}</span>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label for="phone">Closing Date</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                    <input type="text" class="form-control" name="closingdate" id="datepicker" placeholder="" value="{{date('d-m-Y',strtotime($data['deal']['closing_date']))}}" required>
                                    <span class="text-danger">{{ $errors->first('closingdate') }}</span>
                                    <!--                                <div class="input-group date">
                                                                    <div class="input-group-addon">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </div>
                                                                    <input type="text" class="form-control pull-right" name="closingdate" id="datepicker" value="{{date('d-m-Y',strtotime($data['deal']['closing_date']))}}" required>
                                                                    <span class="text-danger">{{ $errors->first('closingdate') }}</span>
                                                                </div>-->
                                </div>
                                <div class="form-group">
                                    <label for="product">Product</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                    <select required class="form-control" name="product" id="product">
                                        {!!$data['productoptions']!!}
                                    </select>
                                    <span class="text-danger">{{ $errors->first('product') }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="pos_id">Post Order Stage</label>
                                    <select class="form-control" name="pos_id" id="pos_id">
                                        {!!$data['orderoptions']!!}
                                    </select>
                                    <span class="text-danger">{{ $errors->first('pos_id') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="leadsource">Lead Source</label>
                                    <select class="form-control" name="leadsource" id="leadsource">
                                        {!!$data['leadsourceoptions']!!}
                                    </select>
                                    <span class="text-danger">{{ $errors->first('leadsource') }}</span>
                                </div>


                                <div class="form-group">
                                    <label for="userpicture">Probability</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">%</span>
                                        </div>
                                        <input type="number" name="probability" id="probability" class="form-control" placeholder="" value="{{$data['deal']['probability']}}" max="100">
                                        <span class="text-danger">{{ $errors->first('probability') }}</span>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label for="last_name">Loss Reason</label>
                                    <select class="form-control" name="lossreason" id="lossreason">
                                        {!!$data['lossreasonoptions']!!}
                                    </select>
                                    <span class="text-danger">{{ $errors->first('lossreason') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <textarea name="notes" id="notes" class="form-control" rows="5">{{$data['deal']['notes']}}</textarea>
                                    <span class="text-danger">{{ $errors->first('notes') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top pull-right text-right">
                        <a href="{{url('customers')}}" class="btn btn-outline-secondary pull-right mr-1">Cancel</a>
                        {{Form::hidden('_method','PUT')}}
                        {{Form::submit('Save',['class'=>"btn btn-primary pull-right"])}}&nbsp;

                    </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </section>
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
        $("#lideals").addClass('active');


        //Date picker
        $("#datepicker").datepicker({
            "dateFormat": 'dd-mm-yy'
        });
        //    $('#datepicker').datepicker({
        //        format: 'dd-mm-yyyy',
        //        autoclose: true,
        //    });


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
@extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1><i class="far fa-edit"></i> Edit Deal</h1>
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
                        {{Form::open(['action'=>['Admin\OrderController@update',$data->oid],'method'=>'Post'])}}
                        @csrf
                        <input type="hidden" id="user" name="user" value="{!!$data['tbl_deals']['uid']!!}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="deal_id">Deal</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <select class="form-control" name="deal_id" id="deal_id" required>
                                            {!!$data['dealoptions']!!}
                                        </select>
                                        <span class="text-danger">{{ $errors->first('deal_id') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="lead">Lead</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <select class="form-control" name="ld_id" id="ld_id" required readonly>
                                            {!!$data['leadoptions']!!}
                                        </select>
                                        <span class="text-danger">{{ $errors->first('ld_id') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="product">Product</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <select class="form-control" name="pro_id" id="pro_id" required readonly>
                                            {!!$data['productoptions']!!}
                                        </select>
                                        <span class="text-danger">{{ $errors->first('pro_id') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="amount">Amount</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{!!$data['currency']['html_code']!!}</span>
                                            </div>
                                            <input class="form-control" type="text" value="{!!$data['tbl_deals']['value']!!}" id="amount" name="amount" readonly>
                                            <span class="text-danger">{{ $errors->first('amount') }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="remarks">Remarks</label>
                                        <textarea class="form-control" name="remarks" id="remarks" rows="3">{!!$data['remarks']!!}</textarea>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="number">Order Number</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <input class="form-control" type="number" value="{!!$data['number']!!}" id="number" name="number" required>
                                        <span class="text-danger">{{ $errors->first('number') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="order_date">Order Date</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                            </div>
                                            <input class="form-control" type="text" value="{!!date('d-m-Y', strtotime($data['order_date']))!!}" id="order_date" name="order_date" required>
                                            <span class="text-danger">{{ $errors->first('order_date') }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="shipping_date">Shipping Date</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                            </div>
                                            <input class="form-control" type="text" value="{!!date('d-m-Y', strtotime($data['shipping_date']))!!}" id="shipping_date" name="shipping_date" required>
                                            <span class="text-danger">{{ $errors->first('shipping_date') }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="delivery_charges">Delivery Charges</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{!!$data['currency']['html_code']!!}</span>
                                            </div>
                                            <input class="form-control" type="number" value="{!!$data['delivery_charges']!!}" id="delivery_charges" name="delivery_charges">
                                            <span class="text-danger">{{ $errors->first('delivery_charges') }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="verify_by">Verify By</label>
                                        <input class="form-control" type="text" value="{!!$data['verify_by']!!}" id="verify_by" name="verify_by">
                                        <span class="text-danger">{{ $errors->first('verify_by') }}</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="pos_id">Post Order Stage</label>
                                        <select class="form-control" name="pos_id" id="pos_id" required>
                                            {!!$data['orderoptions']!!}
                                        </select>
                                        <span class="text-danger">{{ $errors->first('pos_id') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="dlb_id">Delivery By</label>
                                        <select class="form-control" name="dlb_id" id="dlb_id" required>
                                            {!!$data['deliveryoptions']!!}
                                        </select>
                                        <span class="text-danger">{{ $errors->first('dlb_id') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="quantity">Quantity</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <input class="form-control" type="number" value="{!!$data['quantity']!!}" id="quantity" name="quantity" min=1 required>
                                        <span class="text-danger">{{ $errors->first('quantity') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="total_amount">Total Amount</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{!!$data['currency']['html_code']!!}</span>
                                            </div>
                                            <input class="form-control" type="text" value="{!!$data['total_amount']!!}" id="total_amount" name="total_amount" readonly>
                                            <span class="text-danger">{{ $errors->first('total_amount') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 pl-0">
                                <h3 class="badge badge-info">Address Information</h3>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="street">Address</label>
                                        <textarea class="form-control" name="address" id="address" rows="5">{!!$data['lead']['street']!!}</textarea>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="country">Country</label>
                                        <select class="form-control" name="country" id="country">
                                            {!!$data['countryoptions']!!}
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <input type="text" class="form-control" name="city" id="city" placeholder="" value="{!!$data['lead']['city']!!}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="state">State</label>
                                        <select class="form-control" name="state" id="state">
                                            {!!$data['stateoptions']!!}
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="zip">Zip</label>
                                        <input type="text" class="form-control" name="zip" id="zip" placeholder="" value="{!!$data['lead']['zip']!!}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white border-top pull-right text-right">
                            <a href="{{url('admin/orders')}}" class="btn btn-outline-secondary mr-1">Cancel</a>
                            {{Form::hidden('_method','PUT')}}
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
<!--JQuery Datepicker-->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    var url = "{{url('ajaxwebtolead/getStateoptions')}}";
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#lideals").addClass("active");

        $("#order_date").datepicker({
            "dateFormat": 'dd-mm-yy'
        });

        $("#shipping_date").datepicker({
            "dateFormat": 'dd-mm-yy'
        });


        $("#lead").change(function() {
            var acc = $(this).val();
            if (acc == "NewLead") {
                $("#addLead").show();
            } else {
                $("#addLead").hide();
            }
        });

        $("#deal_id").change(function() {
            var id = $(this).val();
            $.get(getorderdealdetails, {
                'id': id
            }, function(result, status) {

                var res = eval("(" + result + ")");
                $("#country").html(res.countryoptions);
                $("#state").html(res.stateoptions);

                var ld_id = (res.deal.ld_id > 0) ? res.deal.ld_id : '';
                var pro_id = (res.deal.pro_id > 0) ? res.deal.pro_id : '';
                var amount = (Number(res.deal.value) > 0) ? Number(res.deal.value) : 0;
                var quantity = (Number($("#quantity").val()) > 0) ? Number($("#quantity").val()) : 1;
                var delivery_charges = (Number($("#delivery_charges").val()) > 0) ? Number($("#delivery_charges").val()) : 0;

                $("#ld_id").val(ld_id);
                $("#pro_id").val(pro_id);
                $("#amount").val(amount);


                var total_amount = (Number(amount) * Number(quantity)) + Number(delivery_charges)

                $("#total_amount").val(total_amount);

                $("#address").val(res.lead.street);
                $("#city").val(res.lead.city);
                $("#zip").val(res.lead.zip);

                $("#country").val(res.lead.country);
                $("#state").val(res.lead.state);
            });
        });


        $("#country").change(function() {
            var country = $(this).val();
            // alert(country);
            if (country > 0) {
                $.get(url, {
                    'country': country
                }, function(result, status) {
                    // alert(result);
                    $("#state").html(result);
                });
            }
        });

        $("#delivery_charges").keyup(function() {
            var amount = (Number($("#amount").val()) > 0) ? Number($("#amount").val()) : 0;
            var quantity = (Number($("#quantity").val()) > 0) ? Number($("#quantity").val()) : 1;
            var delivery_charges = (Number($("#delivery_charges").val()) > 0) ? Number($("#delivery_charges").val()) : 0;
            var total_amount = (Number(amount) * Number(quantity)) + Number(delivery_charges)
            $("#total_amount").val(total_amount);
        });

        $("#quantity").keyup(function() {

            var quantity = Number($("#quantity").val());

            if (quantity > 0) {
                var amount = (Number($("#amount").val()) > 0) ? Number($("#amount").val()) : 0;
                var delivery_charges = (Number($("#delivery_charges").val()) > 0) ? Number($("#delivery_charges").val()) : 0;
                var total_amount = (Number(amount) * Number(quantity)) + Number(delivery_charges)
                $("#total_amount").val(total_amount);
            } else {
                alert('Quantity should be atleast 1');
                $("#quantity").val(1);
                return false;
            }

        });

    });
</script>
@endsection
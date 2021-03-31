@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1><i class="far fa-edit"></i> New Invoice </h1>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content mx-0">
        <div class="container-fluid">
            <!-- Small cardes (Stat card) -->
            <div class="row">
                <div class="col-12">
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
                    <div class="card shadow card-primary card-outline">

                        {{Form::open(['action'=>'InvoiceController@store','method'=>'Post','enctype'=>'multipart/form-data'])}}
                        @csrf
                        <div class="card-body">

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="name">Invoice Name</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control required" name="name" id="name" placeholder="" value="{{old('name')}}" required>
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="inv_number">Invoice Number</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control required" name="inv_number" id="inv_number" placeholder="" value="{{old('inv_number')}}" required>
                                    <span class="text-danger">{{ $errors->first('inv_number') }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="billto">Bill To</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control required" name="billto" id="billto" placeholder="" value="{{old('billto')}}" required>
                                    <span class="text-danger">{{ $errors->first('billto') }}</span>
                                    <input type="hidden" name="billtoId" id="billtoId" />
                                    <input type="hidden" name="billtolabel" id="billtolabel" />
                                    <input type="hidden" name="billtovalue" id="billtovalue" />
                                    <div id="projectDiv"></div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="billto_address">Bill to Address</label>
                                <div class="col-md-4">
                                    <textarea class="form-control" name="billto_address" id="billto_address">{{old('billto_address')}}</textarea>
                                    <span class="text-danger">{{ $errors->first('billto_address') }}</span>
                                </div>
                            </div>

                            <!-- <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="date">Date</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control pull-right" id="datepicker" name="date" value="<?php echo date('d-m-Y'); ?>">
                                        </div>
                                    </div> -->
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="date">Date</label>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="date" id="datepicker" placeholder="" value="<?php echo date('d-m-Y'); ?>" required>
                                        <span class="text-danger">{{ $errors->first('date') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="cmp_logo">Invoice Order Stage</label>
                                <div class="col-md-4">
                                    <select class="form-control" name="pos_id" id="pos_id">
                                        {{!!$data['posOptions']!!}}
                                    </select>
                                    <span class="text-danger">{{ $errors->first('pos_id') }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="cmp_logo">Company Logo</label>
                                <div class="col-md-4">
                                    <input type="file" class="btn btn-default" name="cmp_logo" id="cmp_logo" />
                                    <span class="text-danger">{{ $errors->first('cmp_logo') }}</span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3"></div>
                                <div class="col-md-9">
                                    <h3 class="badge badge-info">Add Product</h3>
                                </div>
                            </div>

                            <table class="table table-striped table-bordered" id="invProducts">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Tax</th>
                                        <th>Amount
                                            <span><a href="#" class="btn btn-default btn-sm float-right" name="addRow" id="addRow"><i class="fa fa-plus-circle"></i></a></span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="tr1">
                                        <td>
                                            <select id="products1" name="products[]" class="form-control products" onchange="return productChange('products1');">
                                                <?php echo $data['product_options']; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input type="number" value="0" name="quantity[]" id="quantity1" class="form-control quantity" onkeyup="return calculateTotal();">
                                                <div class="input-group-prepend"><span class="input-group-text   units" id="unit1"></span></div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><?php echo $data['user']->currency->html_code; ?></span>
                                                </div>
                                                <input type="number" value="0" name="price[]" id="price1" class="form-control price" onkeyup="return calculateTotal();">
                                            </div>
                                        </td>
                                        <td>
                                            <select id="tax1" name="tax[]" class="form-control tax" onchange="return taxChange('tax1');">
                                                <?php echo $data['tax_options']; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><?php echo $data['user']->currency->html_code; ?></span>
                                                </div>
                                                <input type="number" value="" name="amount[]" id="amount1" class="form-control amounts" readonly>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <span>
                                <small></small>
                            </span>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="notes">Notes</label>
                                <div class="col-md-9">
                                    <textarea class="form-control" id='notes' name="notes" rows='8'></textarea>
                                </div>
                            </div>

                            <table class="table table-striped table-bordered" id="">
                                <tbody>
                                    <tr>
                                        <td>Sub Total</td>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><?php echo $data['user']->currency->html_code; ?></span>
                                                </div>
                                                <input type="number" name="subTotal" id="subTotal" class="form-control">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Discount</td>
                                        <td>
                                            <div class="input-group">
                                                <input type="number" name="discount" id="discount" class="form-control" onkeyup="return discountTotal();">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Shipping Charges</td>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><?php echo $data['user']->currency->html_code; ?></span>
                                                </div>
                                                <input type="number" name="shipping" id="shipping" class="form-control" onkeyup="return calculateTotal();">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Grand Total</td>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><?php echo $data['user']->currency->html_code; ?></span>
                                                </div>
                                                <input type="number" name="totalAmount" id="totalAmount" class="form-control" step="any">
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer text-right pull-right">
                            <a href="{{url('/invoice')}}" class="btn btn-outline-secondary mr-1">Cancel</a>
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
    //    var url = "{{url('ajaxwebtolead/getStateoptions')}}";
    var taxOptions = '<?php echo $data['tax_options']; ?>';
    var productOptions = '<?php echo $data['product_options']; ?>';
    var leadArray = '<?php echo json_encode($data['leads']); ?>';
    var currency_code = '<?php echo $data['user']->currency->html_code; ?>';
    //    alert(leadArray);
    var taxChangeid = 0;
    var productChangeid = 0;
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#liinvoice").addClass("active");

        $("#datepicker").datepicker({
            "dateFormat": 'dd-mm-yy'
        });

        //Date picker
        // $('#datepicker').datepicker({
        //     format: 'dd-mm-yy',
        //     autoclose: true,
        // });


        $("#addRow").click(function() {
            var trlength = $("#invProducts tbody tr").length;
            trlength += 1;
            var trid = 'tr' + trlength;
            var quantity = "quantity" + trlength;
            var price = "price" + trlength;
            var products = "products" + trlength;
            var tax = "tax" + trlength;
            var amount = "amount" + trlength;
            var units = "unit" + trlength;
            var returnProduct = "'" + products + "'"
            var returnTax = "'" + tax + "'"

            // <div class="input-group">
            //                                             <div class="input-group-prepend">
            //                                                 <span class="input-group-text"><?php echo $data['user']->currency->html_code; ?></span>
            //                                             </div>
            //                                             <input type="number" name="subTotal" id="subTotal" class="form-control">
            //                                         </div>

            var row = '<tr id="' + trid + '">';
            row += '<td>';
            row += '<select id="' + products + '" name="products[]" class="form-control products" onchange="return productChange(' + returnProduct + ');">';
            row += productOptions;
            row += '</select>';
            row += '</td>';
            row += '<td>';
            row += '<div class="input-group">';
            row += '<input type="number" value="0" name="quantity[]" id="' + quantity + '" class="form-control quantity" onkeyup="return calculateTotal();">';
            row += '<div class="input-group-prepend"><span class="input-group-text units" id="' + units + '"></span></div>';
            row += '</div>';
            row += '</td>';
            row += '<td>';
            row += '<div class="input-group"><div class="input-group-prepend">';
            row += '<span class="input-group-text">' + currency_code + '</span></div>';
            row += '<input type="number" value="0" name="price[]" id="' + price + '" class="form-control price" onkeyup="return calculateTotal();">';
            row += '</div>';
            row += '</td>';
            row += '<td>';
            row += '<select id="' + tax + '" name="tax[]" class="form-control tax" onchange="return taxChange(' + returnTax + ');">';
            row += taxOptions;
            row += '</select>';
            row += '</td>';
            row += '<td>';
            row += '<div class="input-group"><div class="input-group-prepend">';
            row += '<span class="input-group-text">' + currency_code + '</span></div>';
            row += '<input type="number" value="0" name="amount[]" id="' + amount + '" class="form-control" readonly>';
            row += '<span class="input-group-text" onclick="removeRow(' + trlength + ');"><i class="fa fa-minus-circle"></i></span>';
            row += '</div>';
            row += '</td>';
            row += '</tr>';

            $("#invProducts").append(row);

            //            <div class="input-group">
            //                <input type="text" class="form-control">
            //                <span class="input-group-addon"><i class="fa fa-check"></i></span>
            //              </div>

        });
    });

    function removeRow(Id) {
        //        alert(Id);
        var trId = 'tr' + Id;
        $("#" + trId).remove();
    }

    function taxChange(id) {
        //        alert('tax change : ' + id);
        //        if ($("#" + id).val() == 'Add New') {
        //            taxChangeid = id;
        //            $("#addNewtax").modal('show');
        //        } else {
        //            if (($("#" + id).val() != 'Add New')) {
        calculateTotal();
        //            }
        //        }
    }


    function productChange(id) {
        var products = [];
        $("#" + id).parent('td').siblings('td').find(':input.price').attr('readonly', false);
        //        if ($("#" + id).val() == 'Add New') {
        //            productChangeid = id;
        //            $("#addNewProduct").modal('show'); //        } else {
        //            if (($("#" + id).val() != 'Add New')) {

        var proVal = $("#" + id).val();
        var valid = true;
        $('.products').each(function(i) {
            //            alert(i);
            var idind = i + 1;
            var productName = 'products' + idind;
            var productData = $("#" + productName).val();
            var price = 'price' + idind;
            var quantity = 'quantity' + idind;

            var a = products.indexOf(productData);
            if (a < 0) {
                products[i] = productData;
            } else {
                alert('Please select different product');
                $("#" + price).val('');
                $("#" + quantity).val('');
                $("#" + productName).val(0);
                $("#" + productName).focus();
                valid = false;
            }
        });

        if (valid == false) {
            return false;
        } else {
            var proData = proVal.split('-');
            var proDatalength = proData.length;
            $("#" + id).parent('td').siblings('td').find(':input.price').val(Number(proData[proDatalength - 2])).attr('readonly', true); //            $("#" + id).parent('td').siblings('td').find(':input.price').attr('readonly', true);
            $("#" + id).parent('td').siblings('td').find(':input.quantity').val(1);
            $("#" + id).parent('td').siblings('td').find('span.units').text(proData[proDatalength - 1]);

            //-----------------------------------------------------------


            calculateTotal();
        }
        //            }
        //        } 
    }

    function discountTotal() {
        calculateTotal();
    }

    function shippingTotal() {
        $("#subTotalshipping").val('');
        calculateTotal();
    }

    function calculateTotal() {
        //                                alert('calculateTotal');
        var subTotal = 0;
        var discountAmount = 0;
        var shippingAmount = 0;
        var totalAmount = 0;
        var discount = $("#discount").val();
        var shipping = $("#shipping").val();
        $('.products').each(function(i) {
            //            alert(i);
            var idind = i + 1;
            var productName = 'products' + idind;
            var quantity = 'quantity' + idind;
            var price = 'price' + idind;
            var amount = 'amount' + idind;

            //            alert('productName: ' + productName);
            //            alert($("#" + productName).val());
            var productData = $("#" + productName).val();
            var quantityVal = $("#" + quantity).val();
            var priceVal = $("#" + price).val();

            //            alert('productData ' + productData);
            //            alert('quantityVal ' + quantityVal);
            //            alert('priceVal ' + priceVal); 
            var proData = productData.split('-');
            var proId = Number(proData[0]);
            var productNameVal = proData[1];
            var proPrice = Number(proData[2]);
            //
            //
            //            alert('productNameVal: ' + productNameVal);
            //            alert('quantityVal: ' + quantityVal);
            if ((productNameVal != '') && (quantityVal > 0)) {
                var amtVal = 0;
                //                                        alert(amtVal);
                if ((Number(quantityVal) > 0) && (Number(priceVal) > 0)) {
                    //                                            alert(amtVal);
                    amtVal = (Number(quantityVal) * (Number(priceVal)));
                    $("#" + amount).val(amtVal);
                    subTotal += Number(amtVal);
                    $("#subTotal").val(subTotal);
                }
            }
            if (productNameVal == '') {
                $("#" + productName).focus();
                $("#" + quantity).val('');
                //                                        $("#" + price).val('');
            }
            if ((quantityVal == '') || (quantityVal <= 0)) {
                //                                        $("#" + quantity).focus();
                //                                        $("#" + price).val('');
            }
        });

        if ((Number(subTotal) > 0) && (Number(discount) > 0)) {
            discountAmount = (Number(subTotal) * (Number(discount) / 100));
            //                                    alert(discountAmount);
            //                                    $("#subTotaldiscount").val(discountAmount);
            subTotal = subTotal - discountAmount;
            //                                    alert(subTotal);
        }

        var taxidArr = [];
        var taxCal = 0;
        $('.tax').each(function(i) {
            var taxind = i + 1;
            var tax = 'tax' + taxind;
            var taxVal = $("#" + tax).val();
            //            alert('tax val ' + taxVal);

            //            alert('exist ' + exist);

            if (taxVal != 0) {
                var exist = jQuery.inArray(taxVal, taxidArr);
                if (Number(exist) == -1) {
                    taxidArr.push(taxVal);
                    var taxRes = '';
                    var taxId = 0;
                    var taxAmt = 0;

                    if (taxVal != 0) {
                        taxRes = taxVal.split("-");
                        taxId = taxRes[0];
                        taxAmt = taxRes[1];

                        if (Number(taxAmt) > 0) {
                            taxCal += (Number(subTotal) * (Number(taxAmt) / 100));
                        }
                    }
                } else {
                    alert("Tax is already applied..!");
                    $("#" + tax).val(0);
                    return false;
                }
            }
        });

        if (Number(subTotal) >= 0) {
            totalAmount = Number(subTotal) + taxCal; //+ Number(shippingAmount)
            if ((Number(totalAmount) >= 0) && (Number(shipping) >= 0)) {
                totalAmount = (Number(totalAmount) + Number(shipping));
            }
            $("#totalAmount").val(totalAmount.toFixed(2));
        }
    }
</script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<!--
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script type="text/javascript" src="https://cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/a549aa8780dbda16f6cff545aeabc3d71073911e/src/js/bootstrap-datetimepicker.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/a549aa8780dbda16f6cff545aeabc3d71073911e/build/css/bootstrap-datetimepicker.css">
-->
<style>
    .ui-autocomplete {
        max-height: 200px;
        overflow-y: auto;
        /* prevent horizontal scrollbar */
        overflow-x: hidden;
    }

    /* IE 6 doesn't support max-height
     * we use height instead, but this forces the menu to always be this tall
     */
    * html .ui-autocomplete {
        height: 200px;
    }
</style>

<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/base/jquery-ui.css">
<script type='text/javascript'>
    //<![CDATA[
    $(function() {
        //        alert(leadArray);
        $(function() {
            $("#billto").autocomplete({
                appendTo: $("#projectDiv"),
                source: eval("(" + leadArray + ")"), //countries_starting_with_A
                minLength: 1,
                select: function(event, ui) {
                    //                    alert("Id : " + ui.item.id + " Value : " + ui.item.value + " Label : " + ui.item.label);
                    $("#billtoId").val(ui.item.id);
                    $("#billtolabel").val(ui.item.label);
                    $("#billtovalue").val(ui.item.value);
                },
                open: function(event, ui) {
                    var len = $('.ui-autocomplete > li').length;
                },
                close: function(event, ui) {},
                change: function(event, ui) {
                    if (ui.item === null) {
                        $(this).val('');
                    }
                }
            }).autocomplete("instance")._renderItem = function(ul, item) {
                return $("<li>")
                    .append("<div>" + item.label + "<br>" + item.value + "</div>")
                    .appendTo(ul);
            };
            //        });

            // mustMatch (no value) implementation
            $("#billTo").focusout(function() {});
        });
    }); //]]> 
</script>

@endsection
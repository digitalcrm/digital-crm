@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1><i class="far fa-edit"></i> Edit - <?php echo $data['invoice']->name; ?></h1>
                </div>
                <div class="col-sm-8">

                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>



    <!-- Main content -->
    <section class="content mt-2 mx-1">
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
                    <div class="card shadow card-primary card-outline">
                        {{Form::open(['action'=>['InvoiceController@update',$data['invoice']->inv_id],'method'=>'Post','enctype'=>'multipart/form-data'])}}
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <section class="col-lg-4">
                                    <div class="form-group">
                                        <label for="name">Invoice Name</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <input type="text" class="form-control" name="name" id="name" placeholder="" value="<?php echo $data['invoice']->name; ?>" required>
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="inv_number">Invoice Number</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <input type="text" class="form-control" name="inv_number" id="inv_number" placeholder="" value="<?php echo $data['invoice']->inv_number; ?>" required>
                                        <span class="text-danger">{{ $errors->first('inv_number') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="cmp_logo">Company Logo</label>
                                        <input type="file" class="btn btn-default" name="cmp_logo" id="cmp_logo" />
                                        <span class="text-danger">{{ $errors->first('cmp_logo') }}</span>
                                    </div>
                                </section>
                                <section class="col-lg-4">
                                    <div class="form-group">
                                        <label for="billto">Bill To</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <input type="text" class="form-control" name="billto" id="billto" placeholder="" value="<?php echo $data['invoice']->billto; ?>" required>
                                        <span class="text-danger">{{ $errors->first('billto') }}</span>
                                        <input type="hidden" name="billtoId" id="billtoId" value="<?php echo $data['lead']->ld_id; ?>" />
                                        <input type="hidden" name="billtolabel" id="billtolabel" value="<?php echo $data['lead']->first_name; ?>" />
                                        <input type="hidden" name="billtovalue" id="billtovalue" value="<?php echo $data['lead']->email; ?>" />
                                        <div id="projectDiv"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="billto_address">Bill to Address</label>
                                        <textarea class="form-control" name="billto_address" id="billto_address"><?php echo $data['invoice']->billto_address; ?></textarea>
                                        <span class="text-danger">{{ $errors->first('billto_address') }}</span>
                                    </div>
                                </section>
                                <section class="col-lg-4">
                                    <div class="form-group">
                                        <label for="date">Date</label>
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control pull-right" id="datepicker" name="date" value="<?php echo date('d-m-Y', strtotime($data['invoice']->inv_date)); ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="" for="pos_id">Invoice Order Stage</label>
                                        <select class="form-control" name="pos_id" id="pos_id">
                                            {{!!$data['posOptions']!!}}
                                        </select>
                                        <span class="text-danger">{{ $errors->first('pos_id') }}</span>
                                    </div>

                                </section>

                                <section class="">
                                    <div class="col-lg-12 pl-0">
                                        <h3 class="badge badge-info">Edit Products</h3>
                                    </div>
                                    <?php echo $data['invoice']['products']; ?>
                                </section>
                                <!---->

                                <section class="col-lg-5 pull-right">
                                    <table class="table table-striped table-bordered" id="">
                                        <tbody>
                                            <tr>
                                                <td>Sub Total</td>
                                                <td>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><?php echo $data['user']->currency->html_code; ?></span>
                                                        </div>
                                                        <input type="number" name="subTotal" id="subTotal" class="form-control" value="<?php echo $data['invoice']->subtotal; ?>">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Discount</td>
                                                <td>
                                                    <div class="input-group">
                                                        <input type="number" name="discount" id="discount" class="form-control" onkeyup="return discountTotal();" value="<?php echo $data['invoice']->discount; ?>">
                                                        <div class="input-group-prepend"><span class="input-group-text">%</span></div>
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
                                                        <input type="number" name="shipping" id="shipping" class="form-control" onkeyup="return calculateTotal();" value="<?php echo $data['invoice']->shipping; ?>">
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
                                                        <input type="number" name="totalAmount" id="totalAmount" class="form-control" value="<?php echo $data['invoice']->total_amount; ?>" step="any">
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </section>
                                <section class="col-lg-6">
                                    <div class="form-group">
                                        <label for="notes">Notes</label>
                                        <textarea class="form-control" id='notes' name="notes" rows='8'><?php echo $data['invoice']->notes; ?></textarea>
                                    </div>
                                </section>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="card-footer text-right pull-right">
                            <a href="{{url('/invoice')}}" class="btn btn-outline-secondary">Cancel</a>
                            {{Form::hidden('_method','PUT')}}
                            {{Form::submit('Save',['class'=>"btn btn-primary"])}}
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
<!--JQuery Datepicker-->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
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
        //     format: 'dd-mm-yyyy',
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

            var row = '<tr id="' + trid + '">';
            row += '<td>';
            row += '<select id="' + products + '" name="products[]" class="form-control products" onchange="return productChange(' + trlength + ');">'; //returnProduct
            row += productOptions;
            row += '</select>';
            row += '</td>';
            row += '<td>';
            row += '<div class="input-group">';
            row += '<input type="text" value="0" name="quantity[]" id="' + quantity + '" class="form-control quantity" onkeyup="return calculateTotal();">';
            row += '<span class="input-group-addon units" id="' + units + '"></span>';
            row += '</div>';
            row += '</td>';
            row += '<td>';
            row += '<div class="input-group">';
            row += '<span class="input-group-addon">' + currency_code + '</span>';
            row += '<input type="text" value="0" name="price[]" id="' + price + '" class="form-control price" onkeyup="return calculateTotal();">';
            row += '</div>';
            row += '</td>';
            row += '<td>';
            row += '<select id="' + tax + '" name="tax[]" class="form-control tax" onchange="return taxChange(' + trlength + ');">'; //returnTax
            row += taxOptions;
            row += '</select>';
            row += '</td>';
            row += '<td>';
            row += '<div class="input-group">';
            row += '<span class="input-group-addon">' + currency_code + '</span>';
            row += '<input type="text" value="0" name="amount[]" id="' + amount + '" class="form-control" readonly>';
            row += '<span class="input-group-addon" onclick="removeRow(' + trlength + ');"><i class="fa fa-minus-circle"></i></span>';
            row += '</div>';
            row += '</td>';
            row += '</tr>';

            $("#invProducts").append(row);
        });

    });

    function removeRow(Id) {
        //        alert(Id);
        var trId = 'tr' + Id;
        $("#" + trId).remove();
        calculateTotal();
    }

    function taxChange(id) {
        //        alert('tax change : ' + id);
        calculateTotal();
    }


    function productChange(id) {
        //        alert('productChange ' + id);
        var products = [];
        $("#price" + id).attr('readonly', false);
        //        $("#products" + id).parent('td').siblings('td').find(':input.price').attr('readonly', false);

        var proVal = $("#products" + id).val();
        //        alert(proVal);
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

        //        alert(valid);

        if (valid == false) {
            return false;
        } else {
            var proData = proVal.split('-');
            var proDatalength = proData.length;
            //            $("#" + id).parent('td').siblings('td').find(':input.price').val(Number(proData[proDatalength - 1]));
            //            $("#" + id).parent('td').siblings('td').find(':input.price').attr('readonly', true);
            //            $("#" + id).parent('td').siblings('td').find(':input.quantity').val(1);

            $("#price" + id).val(Number(proData[proDatalength - 2])).attr('readonly', true);
            $("#quantity" + id).val(1);
            $("#unit" + id).text(proData[proDatalength - 1]);
            calculateTotal();
        }

    }


    function discountTotal() {
        calculateTotal();
    }

    function shippingTotal() {
        $("#subTotalshipping").val('');
        calculateTotal();
    }

    function calculateTotal() {
        //        alert('calculateTotal');
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
            if (taxVal != 0) {
                var exist = jQuery.inArray(taxVal, taxidArr);
                //            alert('exist ' + exist);
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
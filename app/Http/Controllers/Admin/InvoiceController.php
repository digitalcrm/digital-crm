<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Tbl_tax;
use App\Tbl_invoice;
use App\Tbl_invoice_products;
use App\Tbl_leads;
use App\Tbl_products;
use App\User;
use App\currency;

class InvoiceController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->getUseroptions();
        $invoices = $this->getInvoices($data['user']);
        $data['total'] = $invoices['total'];
        $data['table'] = $invoices['table'];

        return view('admin.invoice.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        $useroptions = "<option value='0'>Select User</option>";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";
        }
        $data['useroptions'] = $useroptions;

        return view('admin.invoice.create')->with('data', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = Tbl_invoice::with('tbl_invoice_products')->find($id);
        $invoice['inv_image'] = ($invoice->cmp_logo != '') ? $invoice->cmp_logo : 'uploads/default/invoice.png';
        $invoice['editLink'] = url('admin/invoices/' . $id . '/edit');
        $invproducts = $invoice->tbl_invoice_products;
        $invoice['lead'] = Tbl_leads::find($invoice->ld_id);
        $uid = $invoice->uid;
        $user = User::find($uid);
        $invoice['user'] = $user;
        $currency = currency::find($user->cr_id);
        $invoice['currency'] = $currency;

        $table = '<table class = "table table-striped">';
        $table .= '<thead>';
        $table .= '<tr>';
        $table .= '<th>Product</th>';
        $table .= '<th>Qty</th>';
        $table .= '<th>Price (' . $currency->html_code . ')</th>';
        $table .= '<th colspan="2">Tax</th>';
        $table .= '<th>Amount (' . $currency->html_code . ')</th>';
        $table .= '</tr>';
        $table .= '</thead>';
        $table .= '<tbody>';

        foreach ($invproducts as $invpro) {

            $product = Tbl_products::find($invpro->pro_id);
            $taxName = '';
            $taxVal = '';
            if ($invpro->tax_id > 0) {
                $tax = Tbl_tax::find($invpro->tax_id);
                $taxName = $tax->name;
                $taxVal = $tax->tax . ' %';
            }

            $table .= '<tr>';
            $table .= '<td>' . $product->name . '</td>';
            $table .= '<td>' . $invpro->quantity . '</td>';
            $table .= '<td>' . $product->price . '</td>';
            $table .= '<td>' . $taxName . '</td>';
            $table .= '<td>' . $taxVal . '</td>';
            $table .= '<td>' . (int) $invpro->quantity * (int) $product->price . '</td>';
            $table .= '</tr>';
        }
        $table .= '</tbody>';
        $table .= '</table>';
        $invoice['products'] = $table;

        $total_table = '<div class="col-sm-10 col-lg-12 float-right">';
        $total_table .= '<table class="table">';
        $total_table .= '<tr>';
        $total_table .= '<th style="width:50%">Subtotal (' . $currency->html_code . '):</th>';
        $total_table .= '<td>' . $invoice->subtotal . '</td>';
        $total_table .= '</tr>';
        $total_table .= '<tr>';
        $total_table .= '<th>Discount (%)</th>';
        $total_table .= '<td>' . $invoice->discount . '</td>';
        $total_table .= '</tr>';
        $total_table .= '<tr>';
        $total_table .= '<th>Shipping (' . $currency->html_code . '):</th>';
        $total_table .= '<td>' . $invoice->shipping . '</td>';
        $total_table .= '</tr>';
        $total_table .= '<tr>';
        $total_table .= '<th>Total (' . $currency->html_code . '):</th>';
        $total_table .= '<td>' . $invoice->total_amount . '</td>';
        $total_table .= '</tr>';
        $total_table .= '</table>';
        $total_table .= '</div>';
        $invoice['total_table'] = $total_table;

        return view('admin.invoice.show')->with('data', $invoice);
    }

    public function printInvoice($id)
    {
        $invoice = Tbl_invoice::with('tbl_invoice_products')->find($id);
        $invoice['inv_image'] = ($invoice->cmp_logo != '') ? $invoice->cmp_logo : 'uploads/default/invoice.png';
        $invoice['editLink'] = url('admin/invoices/' . $id . '/edit');
        $invproducts = $invoice->tbl_invoice_products;
        $invoice['lead'] = Tbl_leads::find($invoice->ld_id);
        $uid = $invoice->uid;
        $user = User::find($uid);
        $currency = currency::find($user->cr_id);

        $table = '<table class = "table table-striped">';
        $table .= '<thead>';
        $table .= '<tr>';
        $table .= '<th>Product</th>';
        $table .= '<th>Qty</th>';
        $table .= '<th>Price (' . $currency->html_code . ')</th>';
        $table .= '<th colspan="2">Tax</th>';
        $table .= '<th>Amount (' . $currency->html_code . ')</th>';
        $table .= '</tr>';
        $table .= '</thead>';
        $table .= '<tbody>';

        foreach ($invproducts as $invpro) {

            $product = Tbl_products::find($invpro->pro_id);
            $taxName = '';
            $taxVal = '';
            if ($invpro->tax_id > 0) {
                $tax = Tbl_tax::find($invpro->tax_id);
                $taxName = $tax->name;
                $taxVal = $tax->tax . ' %';
            }

            $table .= '<tr>';
            $table .= '<td>' . $product->name . '</td>';
            $table .= '<td>' . $invpro->quantity . '</td>';
            $table .= '<td>' . $product->price . '</td>';
            $table .= '<td>' . $taxName . '</td>';
            $table .= '<td>' . $taxVal . '</td>';
            $table .= '<td>' . (int) $invpro->quantity * (int) $product->price . '</td>';
            $table .= '</tr>';
        }
        $table .= '</tbody>';
        $table .= '</table>';
        $invoice['products'] = $table;

        $total_table = '<div class="col-sm-10 col-lg-12 float-right">';
        $total_table .= '<table class="table">';
        $total_table .= '<tr>';
        $total_table .= '<th style="width:50%">Subtotal (' . $currency->html_code . '):</th>';
        $total_table .= '<td>' . $invoice->subtotal . '</td>';
        $total_table .= '</tr>';
        $total_table .= '<tr>';
        $total_table .= '<th>Discount (%)</th>';
        $total_table .= '<td>' . $invoice->discount . '</td>';
        $total_table .= '</tr>';
        $total_table .= '<tr>';
        $total_table .= '<th>Shipping (' . $currency->html_code . '):</th>';
        $total_table .= '<td>' . $invoice->shipping . '</td>';
        $total_table .= '</tr>';
        $total_table .= '<tr>';
        $total_table .= '<th>Total (' . $currency->html_code . '):</th>';
        $total_table .= '<td>' . $invoice->total_amount . '</td>';
        $total_table .= '</tr>';
        $total_table .= '</table>';
        $total_table .= '</div>';
        $invoice['total_table'] = $total_table;

        return view('admin.invoice.print')->with('data', $invoice);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $invoice = Tbl_invoice::with('tbl_invoice_products')->find($id);

        $uid = $invoice->uid;
        $user = User::find($uid);

        $product_options = '';
        $tax_options = '';

        $leads = Tbl_leads::where('uid', $uid)->get();
        $leadArray = array();
        foreach ($leads as $lead) {
            $leadArr['id'] = $lead->ld_id;
            $leadArr['label'] = $lead->first_name . ' ' . $lead->last_name;
            $leadArr['value'] = $lead->email;
            $leadArray[] = $leadArr;
        }
        $data['leads'] = $leadArray;

        $invproducts = $invoice->tbl_invoice_products;
        $data['lead'] = Tbl_leads::find($invoice->ld_id);

        $currency = currency::find($user->cr_id);
        $table = '<table class="table table-striped" id="invProducts">';
        $table .= '<thead>';
        $table .= '<tr>';
        $table .= '<th>Product</th>';
        $table .= '<th>Quantity</th>';
        $table .= '<th>Price (' . $currency->html_code . ')</th>';
        $table .= '<th>Tax</th>';
        $table .= '<th width="250">Amount (' . $currency->html_code . ')<span><a href="#" class="btn btn-default btn-sm float-right" name="addRow" id="addRow"><i class="fa fa-plus-circle"></i></a></span></th>';
        $table .= '</tr>';
        $table .= '</thead>';
        $table .= '<tbody>';
        $k = 1;
        foreach ($invproducts as $invpro) {

            //---------------------Products-------------------------------
            $productCost = Tbl_products::find($invpro->pro_id);
            //            $tax = Tbl_tax::find($invpro->tax_id);

            $productsId = 'products' . $k;
            $products = Tbl_products::where('uid', $uid)->where('active', 1)->get();

            $productselect = '<select id="' . $productsId . '" name="products[]" class="form-control products" onchange="return productChange(' . $k . ');">';
            $productselect .= '<option value="0">Select Product</option>';
            $product_options .= '<option value="0">Select Product</option>';
            if (count($products) > 0) {
                foreach ($products as $product) {
                    $proSelected = ($invpro->pro_id == $product->pro_id) ? 'selected' : '';
                    $productselect .= '<option value="' . $product->pro_id . '-' . $product->price . '" ' . $proSelected . '>' . $product->name . '</option>';
                    $product_options .= '<option value="' . $product->pro_id . '-' . $product->price . '">' . $product->name . '</option>';
                }
            }
            $productselect .= '</select>';

            //---------------------Tax-------------------------------
            $taxesId = 'tax' . $k;
            $taxs = Tbl_tax::where('uid', $uid)->get();
            $taxselect = '<select id="' . $taxesId . '" name="tax[]" class="form-control tax" onchange="return taxChange(' . $k . ');">';
            $taxselect .= '<option value="0">Select Tax</option>';
            $tax_options .= '<option value="0">Select Tax</option>';
            if (count($taxs) > 0) {
                foreach ($taxs as $tax) {
                    $taxSelected = ($invpro->tax_id == $tax->tax_id) ? 'selected' : '';
                    $taxselect .= '<option value="' . $tax->tax_id . '-' . $tax->tax . '-' . $tax->name . '" ' . $taxSelected . '>' . $tax->name . ' - ' . $tax->tax . '%</option>';
                    $tax_options .= '<option value="' . $tax->tax_id . '-' . $tax->tax . '-' . $tax->name . '">' . $tax->name . ' - ' . $tax->tax . '%</option>';
                }
            }
            $taxselect .= '</select>';

            //---------------------Quantity-------------------------------
            $quantityId = 'quantity' . $k;
            $quantityInput = '<input type="text" value="' . $invpro->quantity . '" name="quantity[]" id="' . $quantityId . '" class="form-control quantity" onkeyup="return calculateTotal();">';

            //---------------------Price-------------------------------
            $priceId = 'price' . $k;
            $priceInput = '<input type="text" value="' . $productCost->price . '" name="price[]" id="' . $priceId . '" class="form-control price" onkeyup="return calculateTotal();" readonly>';

            //---------------------Amount-------------------------------
            $amountId = 'amount' . $k;
            $amountInput = ' <input type="text" value="' . (int) $invpro->quantity * (int) $productCost->price . '" name="amount[]" id="' . $amountId . '" class="form-control amounts" readonly>';

            $trId = 'tr' . $k;
            $table .= '<tr id="' . $trId . '">';
            $table .= '<td>' . $productselect . '</td>';
            $table .= '<td>' . $quantityInput . '</td>';
            $table .= '<td>' . $priceInput . '</td>';
            $table .= '<td>' . $taxselect . '</td>';
            $table .= '<td>';
            if ($k > 1) {
                $table .= '<div class="input-group">' . $amountInput . '<span class="input-group-text" onclick="removeRow(' . $k . ');"><i class="fa fa-minus-circle"></i></span></div>';
            } else {
                $table .= $amountInput;
            }
            $table .= '</td>';
            $table .= '</tr>';

            $k++;
        }
        $table .= '</tbody>';
        $table .= '</table>';
        $invoice['products'] = $table;

        $data['product_options'] = $product_options;
        $data['tax_options'] = $tax_options;
        $data['invoice'] = $invoice;
        return view('admin.invoice.edit')->with('data', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //        echo json_encode($request->input());
        //        exit();

        $this->validate($request, [
            'name' => 'required|max:255',
            'inv_number' => 'required|max:255',
            'billto' => 'required|max:255',
        ], ['inv_number.unique' => 'The invoice number is already taken']);

        $products = $request->input('products');
        $quantity = $request->input('quantity');
        $price = $request->input('price');
        $tax = $request->input('tax');
        $amount = $request->input('amount');
        $inv_number = $request->input('inv_number');

        if ($inv_number > 0) {
            $invIds = Tbl_invoice::where('inv_number', $inv_number)->where('inv_id', '!=', $id)->get(['inv_id']);
            foreach ($invIds as $invId) {
                if ($id == $invId) {
                    return redirect('admin/invoices/' . $id . '/edit')->with('error', 'Please inv number already taken');
                }
            }
        } else {
            return redirect('admin/invoices/' . $id . '/edit')->with('error', 'Please enter inv number');
        }


        $filename = '';
        if ($request->hasfile('cmp_logo')) {

            //-------------Image Validation----------------------------------
            $file = $request->file('cmp_logo');
            // Build the input for validation
            $fileArray = array('cmp_logo' => $file);

            // Tell the validator that this file should be an image
            $rules = array(
                'cmp_logo' => 'mimes:jpeg,jpg,png,gif|max:10000' // max 10000kb
            );

            // Now pass the input and rules into the validator
            $validator = Validator::make($fileArray, $rules);

            // Check to see if validation fails or passes
            if ($validator->fails()) {
                return redirect('admin/invoice/' . $id . '/edit')->with('error', 'Please upload jpg, png and giff images only.');
            }
            //-------------Image Validation----------------------------------
            //            $file = $request->file('userpicture');
            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $file->move('uploads/invoice/', $name);  //public_path().
            $filename = '/uploads/invoice/' . $name;
        }

        $invoice = Tbl_invoice::find($id);
        $invoice->name = $request->input('name');
        $invoice->inv_number = $request->input('inv_number');
        if ($filename != '') {
            $invoice->cmp_logo = $filename;
        }
        $invoice->billto = $request->input('billtovalue');
        $invoice->ld_id = $request->input('billtoId');
        $invoice->billto_address = $request->input('billto_address');
        $invoice->inv_date = date('Y-m-d', strtotime($request->input('date')));
        $invoice->discount = $request->input('discount');
        $invoice->shipping = $request->input('shipping');
        $invoice->subtotal = $request->input('subTotal');
        $invoice->total_amount = $request->input('totalAmount');
        $invoice->save();


        Tbl_invoice_products::where('inv_id', $id)->delete();

        for ($i = 0; $i < count($products); $i++) {
            $product = explode('-', $products[$i]);
            $taxes = explode('-', $tax[$i]);
            $prodata = array(
                'inv_id' => $id,
                'pro_id' => $product[0],
                'quantity' => $quantity[$i],
                'tax_id' => $taxes[0],
            );

            Tbl_invoice_products::create($prodata);
        }

        return redirect('admin/invoices/' . $id)->with('success', 'Updated Successfully...');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getInvoices($uid)
    {
        $currency = "";

        if ($uid == 'All') {
            // $currency = currency::where('status', 1)->first();
            // $invoices = Tbl_invoice::where('active', 1)->orderBy('inv_id', 'desc')->get();
            $query = DB::table('tbl_invoice')->where('tbl_invoice.active', 1);
            $query->join('users', 'users.id', '=', 'tbl_invoice.uid');
            $query->orderBy('tbl_invoice.uid', 'desc');
            $query->select(
                'tbl_invoice.*',
                'users.cr_id as cr_id'
            );
            $invoices = $query->get();
        } else {
            $user = User::find($uid);
            $currency = currency::find($user->cr_id);
            $invoices = Tbl_invoice::where('uid', $uid)->where('active', 1)->orderBy('inv_id', 'desc')->get();
        }

        $total = count($invoices);
        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th width="2"><input type="checkbox" id="selectAll"></th>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Bill To</th>';
            $formstable .= '<th>Amount</th>';
            $formstable .= '<th>Status</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($invoices as $formdetails) {
                if ($uid == 'All') {
                    $user = User::find($formdetails->uid);

                    //     // print_r($user);

                    $currency = currency::find($user->cr_id);
                }

                // exit();

                $inv_image = ($formdetails->cmp_logo != '') ? $formdetails->cmp_logo : 'uploads/default/invoice.png';

                $formstable .= '<tr>';
                $formstable .= '<td>
				<div class="custom-control custom-checkbox">
				<input type="checkbox" class="custom-control-input checkAll" id="' . $formdetails->inv_id . '">
				<label class="custom-control-label" for="' . $formdetails->inv_id . '"></label>
				</div>
				</td>';
                $formstable .= '<td class="table-title">';
                $formstable .= '<img src="' . url($inv_image) . '" class="avatar">';
                $formstable .= '<a href="' . url('admin/invoices/' . $formdetails->inv_id) . '">' . $formdetails->name . '</a>&nbsp;';
                $formstable .= '</td>';
                $formstable .= '<td>' . $formdetails->billto . '</td>';
                $formstable .= '<td>' . $currency->html_code . ' ' . $formdetails->total_amount . '</td>';
                $status = ($formdetails->status == 1) ? 'Send' : 'Draft';
                $formstable .= '<td>' . $status . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item text-default text-btn-space" href="' . url('admin/invoices/' . $formdetails->inv_id . '/edit') . '">Edit</a>
                    <a class="dropdown-item text-default text-btn-space" href="' . url('admin/invoices/delete/' . $formdetails->inv_id) . '">Delete</a>
                  </div>
                </div>';

                $formstable .= '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;
        return $data;
    }

    public function delete($id)
    {
        $inv = Tbl_invoice::find($id);
        $inv->active = 0;
        $inv->save();
        return redirect('admin/invoices')->with('success', 'Deleted Successfully...!');
    }

    public function getUseroptions()
    {
        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        $uid = "All";
        $useroptions = "<option value='All'>All</option>";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";
        }
        $data['useroptions'] = $useroptions;
        $data['user'] = $uid;
        return $data;
    }

    public function getProductoptions($uid)
    {
        $products = Tbl_products::where('uid', $uid)->where('active', 1)->get();
        $product_options = '<option value="0">Select Product</option>';
        if (count($products) > 0) {
            foreach ($products as $product) {
                $product_options .= '<option value="' . $product->pro_id . '-' . $product->price . '">' . $product->name . '</option>';
            }
        }
        return $product_options;
    }

    public function getTaxoptions($uid)
    {
        $taxs = Tbl_tax::where('uid', $uid)->get();
        $tax_options = '<option value="0">Select Tax</option>';
        if (count($taxs) > 0) {
            foreach ($taxs as $tax) {
                $tax_options .= '<option value="' . $tax->tax_id . '-' . $tax->tax . '-' . $tax->name . '">' . $tax->name . ' - ' . $tax->tax . '%</option>';
            }
        }
        return $tax_options;
    }

    public function getLeadoptions($uid)
    {
        $leads = Tbl_leads::where('uid', $uid)->get();
        $leadArray = array();
        foreach ($leads as $lead) {
            $leadArr['id'] = $lead->ld_id;
            $leadArr['label'] = $lead->first_name . ' ' . $lead->last_name;
            $leadArr['value'] = $lead->email;
            $leadArray[] = $leadArr;
        }
        return $leadArray;
    }
}

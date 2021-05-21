<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Mail;
use App\Tbl_tax;
use App\Tbl_invoice;
use App\Tbl_invoice_products;
use App\Tbl_leads;
use App\Tbl_products;
use App\User;
use App\currency;
use App\Tbl_post_order_stage;

class InvoiceController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('test:invoices', ['only' => ['index', 'show', 'create', 'store', 'edit', 'update', 'delete', 'deleteAll', 'printInvoice', 'getInvoicesFilter']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $uid = Auth::user()->id;

        $user = User::find($uid);
        $currency = currency::find($user->cr_id);

        $postages = Tbl_post_order_stage::all();

        $invoices = Tbl_invoice::where('uid', $uid)->where('active', 1)->with('Tbl_post_order_stage')->orderBy('inv_id', 'desc')->get();
        // echo json_encode($invoices);
        // exit();

        $total = count($invoices);
        if ($total > 0) {
            $formstable = '<div class="table-responsive"><table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            // width="2" <input type="checkbox" id="selectAll">
            $formstable .= '<th>Invoice Number</th>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Bill To</th>';
            $formstable .= '<th>Amount</th>';
            $formstable .= '<th>Status</th>';
            $formstable .= '<th>Stage</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($invoices as $formdetails) {

                $inv_image = ($formdetails->cmp_logo != '') ? $formdetails->cmp_logo : 'uploads/default/invoice.png';

                $leadstatusSelecttagidentifier = "'" . "postage" . $formdetails->inv_id . "'";
                $leadstatusSelecttag = '<select class="btn btn-sm btn-default dropdown-toggle" id="postage' . $formdetails->inv_id . '" name="postage' . $formdetails->inv_id . '" onchange="return changeLeadStatus(' . $leadstatusSelecttagidentifier . ',' . $formdetails->inv_id . ');">';
                $leadstatusSelecttag .= '<option value="0">Select</option>';
                foreach ($postages as $postage) {
                    $ldslected = ((int) $postage->pos_id === (int) $formdetails->pos_id) ? 'selected' : '';
                    $leadstatusSelecttag .= '<option value="' . $postage->pos_id . '" ' . $ldslected . '>' . $postage->stage . '</option>';
                }

                $formstable .= '<tr>';
                //<input type="checkbox" class="checkAll" id="' . $formdetails->inv_id . '">
                $formstable .= '<td>' . $formdetails->inv_number . '</td>';
                $formstable .= '<td class="table-title">';
                $formstable .= '<img src="' . url($inv_image) . '" class="avatar">';
                $formstable .= '<a href="' . url('invoice/' . $formdetails->inv_id) . '">' . $formdetails->name . '</a>&nbsp;';
                $formstable .= '</td>';
                $formstable .= '<td>' . $formdetails->billto . '</td>';
                $formstable .= '<td>' . $currency->html_code . ' ' . $formdetails->total_amount . '</td>';
                $status = ($formdetails->status == 1) ? 'Send' : 'Draft';
                $formstable .= '<td>' . $status . '</td>';
                $formstable .= '<td>' . $leadstatusSelecttag . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item text-default text-btn-space" href="' . url('invoice/' . $formdetails->inv_id . '/edit') . '">Edit</a>
                    <a class="dropdown-item text-default text-btn-space" href="' . url('invoice/delete/' . $formdetails->inv_id) . '">Delete</a>
                  </div>
                </div>';

                $formstable .= '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table></div>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;

        return view('auth.invoice.index')->with("data", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $uid = Auth::user()->id;

        $user = Auth::user();
        if ($user->can('create', Tbl_invoice::class)) {

            $data['product_options'] = $this->getProductoptions($uid);

            $data['tax_options'] = $this->getTaxoptions($uid);

            $leads = Tbl_leads::where('uid', $uid)->get();
            $leadArray = array();
            foreach ($leads as $lead) {
                $leadArr['id'] = $lead->ld_id;
                $leadArr['label'] = $lead->first_name . ' ' . $lead->last_name;
                $leadArr['value'] = $lead->email;
                $leadArray[] = $leadArr;
            }
            $data['leads'] = $leadArray;

            $user = User::with('currency')->find($uid);
            $data['user'] = $user;

            $posId = 0;
            $posOptions = $this->getPostOrderStageOptions($posId);
            $data['posOptions'] = $posOptions;

            return view('auth.invoice.create')->with('data', $data);
        } else {
            return redirect('/invoice');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|max:255',
            'inv_number' => 'required|max:255|unique:tbl_invoice',
            'billto' => 'required|max:255',
        ], ['inv_number.unique' => 'The invoice number is already taken']);

        $products = $request->input('products');
        $quantity = $request->input('quantity');
        $price = $request->input('price');
        $tax = $request->input('tax');
        $amount = $request->input('amount');
        $billtoId = $request->input('billtoId');
        $discount = ($request->input('discount') > 0) ? $request->input('discount') : 0;
        $shipping = ($request->input('shipping') > 0) ? $request->input('shipping') : 0;
        $notes = ($request->input('notes') == null) ? "" : $request->input('notes');
        $pos_id = $request->input('pos_id');
        //        echo $notes;
        //        echo json_encode($request->input());
        //        exit();

        if (count($products) > 0) {

            if (($billtoId == '') || ($billtoId == 0)) {
                return redirect('/invoice/create')->with('error', 'Please select bill to');
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
                    return redirect('/invoice/create')->with('error', 'Please upload jpg, png and giff images only.');
                }
                //-------------Image Validation----------------------------------
                //            $file = $request->file('userpicture');
                $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
                $file->move('uploads/invoice/', $name);  //public_path().
                $filename = '/uploads/invoice/' . $name;
            }

            $formdata = array(
                'uid' => Auth::user()->id,
                'name' => $request->input('name'),
                'inv_number' => $request->input('inv_number'),
                'cmp_logo' => $filename,
                'billto' => $request->input('billtovalue'),
                'ld_id' => $request->input('billtoId'),
                'billto_address' => $request->input('billto_address'),
                'inv_date' => date('Y-m-d', strtotime($request->input('date'))),
                'discount' => $discount,
                'shipping' => $shipping,
                'subtotal' => $request->input('subTotal'),
                'total_amount' => $request->input('totalAmount'),
                'notes' => $notes,
                'pos_id' => $pos_id,
            );

            //        echo json_encode($formdata);

            $invoice = Tbl_invoice::create($formdata);
            if ($invoice->inv_id > 0) {


                for ($i = 0; $i < count($products); $i++) {
                    //                echo $products[$i] . ' ' . $quantity[$i] . ' ' . $price[$i] . ' ' . $tax[$i] . ' ' . $amount[$i] . '<br/>';
                    $product = explode('-', $products[$i]);
                    $taxes = explode('-', $tax[$i]);
                    $prodata = array(
                        'inv_id' => $invoice->inv_id,
                        'pro_id' => $product[0],
                        'quantity' => $quantity[$i],
                        'tax_id' => $taxes[0],
                    );

                    $invoice_product = Tbl_invoice_products::create($prodata);
                }

                return redirect('invoice/' . $invoice->inv_id)->with('success', 'Created Successfully...!');
            } else {
                return redirect('/invoice')->with('error', 'Error occurred. Please try again...!');
            }
        } else {
            return redirect('/invoice')->with('error', 'Please select products...!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //  Invoice Details
        $invoice = Tbl_invoice::with('tbl_invoice_products')->find($id);

        $user = Auth::user();
        if ($user->can('view', $invoice)) {

            $invoice['inv_image'] = ($invoice->cmp_logo != '') ? $invoice->cmp_logo : 'uploads/default/invoice.png';
            $invoice['editLink'] = url('invoice/' . $id . '/edit');
            $invproducts = $invoice->tbl_invoice_products;

            //  Lead Details
            $invoice['lead'] = Tbl_leads::with('tbl_countries')->with('tbl_states')->find($invoice->ld_id);

            //  User Details
            $uid = Auth::user()->id;
            $user = User::with('tbl_countries')->with('tbl_states')->find($uid);
            $invoice['user'] = $user;

            //  Currency Details
            $currency = currency::find($user->cr_id);

            $table = '<table width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr class="invoice-details-title">
                                                <th>Description</th>
                                                <th width="90">Quantity</th>
                                                <th width="100">Price</th>
                                                <th width="100">Tax</th>
                                                <th width="120">Amount</th>
                                            </tr>';

            foreach ($invproducts as $invpro) {
                $product = Tbl_products::with('tbl_units')->find($invpro->pro_id);
                if ($product != '') {
                    $taxName = '';
                    $taxVal = '';
                    if ($invpro->tax_id > 0) {
                        $tax = Tbl_tax::find($invpro->tax_id);
                        $taxName = $tax->name;
                        $taxVal = $tax->tax . ' %';
                    }

                    $proImage = ($product->picture != '') ? '<img src="' . url($product->picture) . '" width="25" height="25">&nbsp;' : '';

                    $table .= '<tr class = "invoice-details">
                        <td>
                        <div class = "plan-name">' . $proImage . $product->name . '</div>
                        <div></div>
                        </td>
                        <td>' . $invpro->quantity . ' ' . (($product->tbl_units != '') ? $product->tbl_units->sortname : '') . '</td>
                        <td>' . $currency->html_code . ' ' . $product->price . '</td>
                        <td>' . $taxName . ' - ' . $taxVal . '</td>
                        <td>' . $currency->html_code . ' ' . (int) $invpro->quantity * (int) $product->price . '</td>
                        </tr>';
                }
            }
            $table .= '<tr class="invoice-details">
        <td colspan="4" class="totalTd">Sub Total</td><td><div class="">' . $currency->html_code . ' ' . $invoice->subtotal . '</div></td>
        </tr>';
            $table .= '<tr class="invoice-details">
        <td colspan="4" class="totalTd">Discount</td><td><div class="">' . $invoice->discount . ' %</div></td>
        </tr>';
            $table .= '<tr class="invoice-details">
        <td colspan="4" class="totalTd">Shipping Charges</td><td><div class="">' . $currency->html_code . ' ' . $invoice->shipping . '</div></td>
        </tr>';
            $table .= '<tr class="invoice-details">
        <td colspan="4" class="totalTd">Grand Total</td><td><div class="">' . $currency->html_code . ' ' . $invoice->total_amount . '</div></td>
        </tr>';
            $table .= '</table>';
            $invoice['products'] = $table;

            return view('auth.invoice.show')->with('data', $invoice);
        } else {
            return redirect('/invoice');
        }
    }

    public function printInvoice($id)
    {
        $invoice = Tbl_invoice::with('tbl_invoice_products')->find($id);
        $invoice['inv_image'] = ($invoice->cmp_logo != '') ? $invoice->cmp_logo : 'uploads/default/invoice.png';
        $invoice['editLink'] = url('invoice/' . $id . '/edit');
        $invproducts = $invoice->tbl_invoice_products;

        //  Lead Details
        $invoice['lead'] = Tbl_leads::with('tbl_countries')->with('tbl_states')->find($invoice->ld_id);
        $uid = Auth::user()->id;

        //  User Details
        $user = User::with('tbl_countries')->with('tbl_states')->find($uid);
        $invoice['user'] = $user;

        //  Currency Details
        $currency = currency::find($user->cr_id);
        $invoice['currency'] = $currency;

        $table = '<table width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr class="invoice-details-title">
                                                <th>Description</th>
                                                <th width="90">Quantity</th>
                                                <th width="100">Price</th>
                                                <th width="100">Tax</th>
                                                <th width="120">Amount</th>
                                            </tr>';

        foreach ($invproducts as $invpro) {
            $product = Tbl_products::with('tbl_units')->find($invpro->pro_id);
            $taxName = '';
            $taxVal = '';
            if ($invpro->tax_id > 0) {
                $tax = Tbl_tax::find($invpro->tax_id);
                $taxName = $tax->name;
                $taxVal = $tax->tax . ' %';
            }
            // $proImage = ($product->picture != '') ? '<img src="' . url($product->picture) . '" width="25" height="25">&nbsp;' : '';
            $proImage = '<img src="' . url($product->product_img) . '" width="25" height="25">&nbsp;';


            $table .= '<tr class = "invoice-details">
            <td>
            <div class = "plan-name">' . $proImage . $product->name . '</div>
            <div></div>
            </td>
            <td>' . $invpro->quantity . ' ' . $product->tbl_units->sortname . '</td>
            <td>' . $currency->html_code . ' ' . $product->price . '</td>
            <td>' . $taxName . ' - ' . $taxVal . '</td>
            <td>' . $currency->html_code . ' ' . (int) $invpro->quantity * (int) $product->price . '</td>
            </tr>';
        }
        $table .= '<tr class="invoice-details">
        <td colspan="4" class="totalTd">Sub Total</td><td><div class="">' . $currency->html_code . ' ' . $invoice->subtotal . '</div></td>
        </tr>';
        $table .= '<tr class="invoice-details">
        <td colspan="4" class="totalTd">Discount</td><td><div class="">' . $invoice->discount . ' %</div></td>
        </tr>';
        $table .= '<tr class="invoice-details">
        <td colspan="4" class="totalTd">Shipping Charges</td><td><div class="">' . $currency->html_code . ' ' . $invoice->shipping . '</div></td>
        </tr>';
        $table .= '<tr class="invoice-details">
        <td colspan="4" class="totalTd">Grand Total</td><td><div class="">' . $currency->html_code . ' ' . $invoice->total_amount . '</div></td>
        </tr>';
        $table .= '</table>';
        $invoice['products'] = $table;

        return view('auth.invoice.print')->with('data', $invoice);
    }

    public function emailInvoice($id)
    {

        //  Invoice Details
        $invoice = Tbl_invoice::with('tbl_invoice_products')->find($id);
        $invoice['inv_image'] = ($invoice->cmp_logo != '') ? $invoice->cmp_logo : 'uploads/default/invoice.png';
        $invoice['editLink'] = url('/invoice/' . $id . '/edit');
        $invproducts = $invoice->tbl_invoice_products;

        $inv_image = url(($invoice->cmp_logo != '') ? $invoice->cmp_logo : 'uploads/default/invoice.png');
        $inv_number = $invoice->inv_number;
        $created_at = $invoice->created_at;
        $inv_date = $invoice->inv_date;
        $notes = $invoice->notes;

        //  Lead Details
        $invoice['lead'] = Tbl_leads::with('tbl_countries')->with('tbl_states')->find($invoice->ld_id);
        $lead_email = $invoice['lead']->email;

        //  User Details
        $uid = Auth::user()->id;
        $user = User::with('tbl_countries')->with('tbl_states')->find($uid);
        $invoice['user'] = $user;
        $username = $user->name;

        //  Currency Details
        $currency = currency::find($user->cr_id);

        $cityzip = '';
        if (($user->city != '') && ($user->zip != '')) {
            $cityzip = $user->city . ', ' . $user->zip;
        }
        if (($user->city != '') && ($user->zip == '')) {
            $cityzip = $user->city;
        }
        if (($user->city == '') && ($user->zip != '')) {
            $cityzip = $user->zip;
        }

        $countrystate = '';
        if (($user->tbl_countries != '') && ($user->tbl_states != '')) {
            $countrystate = $user->tbl_countries->name . ', ' . $user->tbl_states->name . '<br>';
        }
        if (($user->tbl_countries != '') && ($user->tbl_states == '')) {
            $countrystate = $user->tbl_countries->name . '<br>';
        }
        if (($user->tbl_countries == '') && ($user->tbl_states != '')) {
            $countrystate = $user->tbl_states->name . '<br>';
        }

        $table = '<table width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr class="invoice-details-title">
                                                <th>Description</th>
                                                <th width="90">Quantity</th>
                                                <th width="100">Price</th>
                                                <th width="100">Tax</th>
                                                <th width="120">Amount</th>
                                            </tr>';

        foreach ($invproducts as $invpro) {
            $product = Tbl_products::with('tbl_units')->find($invpro->pro_id);
            $taxName = '';
            $taxVal = '';
            if ($invpro->tax_id > 0) {
                $tax = Tbl_tax::find($invpro->tax_id);
                $taxName = $tax->name;
                $taxVal = $tax->tax . ' %';
            }
            // $proImage = ($product->picture != '') ? '<img src="' . url($product->picture) . '" width="25" height="25">&nbsp;' : '';
            $proImage = '<img src="' . url($product->product_img) . '" width="25" height="25">&nbsp;';


            $table .= '<tr class = "invoice-details">
            <td>
            <div class = "plan-name">' . $proImage . $product->name . '</div>
            <div></div>
            </td>
            <td>' . $invpro->quantity . ' ' . $product->tbl_units->sortname . '</td>
            <td>' . $currency->html_code . ' ' . $product->price . '</td>
            <td>' . $taxName . ' - ' . $taxVal . '</td>
            <td>' . $currency->html_code . ' ' . (int) $invpro->quantity * (int) $product->price . '</td>
            </tr>';
        }
        $table .= '<tr class="invoice-details">
        <td colspan="4" class="totalTd">Sub Total</td><td><div class="">' . $currency->html_code . ' ' . $invoice->subtotal . '</div></td>
        </tr>';
        $table .= '<tr class="invoice-details">
        <td colspan="4" class="totalTd">Discount</td><td><div class="">' . $invoice->discount . ' %</div></td>
        </tr>';
        $table .= '<tr class="invoice-details">
        <td colspan="4" class="totalTd">Shipping Charges</td><td><div class="">' . $currency->html_code . ' ' . $invoice->shipping . '</div></td>
        </tr>';
        $table .= '<tr class="invoice-details">
        <td colspan="4" class="totalTd">Grand Total</td><td><div class="">' . $currency->html_code . ' ' . $invoice->total_amount . '</div></td>
        </tr>';
        $table .= '</table>';
        $invoice['products'] = $table;
        $products_table = $table;

        $mailItems = array(
            'inv_image' => $inv_image,
            'inv_number' => $inv_number,
            'created_at' => $created_at,
            'inv_date' => $inv_date,
            'notes' => $notes,
            'lead_email' => $lead_email,
            'username' => $username,
            'cityzip' => $cityzip,
            'countrystate' => $countrystate,
            'products_table' => $products_table,
        );

        $from = Auth::user()->email;
        $to = $lead_email;
        $subject = 'Invoice from ' . Auth::user()->name;

        Mail::send(['html' => 'emails.invoice'], $mailItems, function ($message) use ($from, $to, $subject) {
            $message->subject($subject);
            $message->from($from, config('app.name'));   //'sandeepindana@yahoo.com'
            $message->to($to);   //'isandeep.1609@gmail.com'
        });

        if (count(Mail::failures()) > 0) {
            return redirect('/invoice/' . $invoice->inv_id)->with('error', 'Failed. Try again later...!');
        } else {
            return redirect('/invoice/' . $invoice->inv_id)->with('success', 'Mail Sent Successfully...!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $user = Auth::user();
        $invoice = Tbl_invoice::with('tbl_invoice_products')->find($id);

        if ($user->can('view', $invoice)) {

            $uid = Auth::user()->id;
            $user = User::with('currency')->find($uid);
            $data['user'] = $user;

            $currency = currency::find($user->cr_id);

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

            $table = '<table class="table table-striped" id="invProducts">';
            $table .= '<thead>';
            $table .= '<tr>';
            $table .= '<th>Product</th>';
            $table .= '<th>Quantity</th>';
            $table .= '<th>Price</th>';
            $table .= '<th>Tax</th>';
            $table .= '<th width="250">Amount (' . $currency->html_code . ')<span><a href="#" class="btn btn-default btn-sm pull-right" name="addRow" id="addRow"><i class="fa fa-plus-circle"></i></a></span></th>';
            $table .= '</tr>';
            $table .= '</thead>';
            $table .= '<tbody>';
            $k = 1;
            foreach ($invproducts as $invpro) {

                //---------------------Products-------------------------------
                $productCost = Tbl_products::find($invpro->pro_id);
                //            $tax = Tbl_tax::find($invpro->tax_id);

                $productsId = 'products' . $k;
                $products = Tbl_products::with('tbl_units')->where('active', 1)->get();     //where('uid', $uid)->

                $productselect = '<select id="' . $productsId . '" name="products[]" class="form-control products" onchange="return productChange(' . $k . ');">';
                $productselect .= '<option value="0">Select Product</option>';
                $product_options .= '<option value="0">Select Product</option>';
                if (count($products) > 0) {
                    foreach ($products as $product) {
                        $proSelected = ($invpro->pro_id == $product->pro_id) ? 'selected' : '';

                        $sortname = ($product->tbl_units != '') ? $product->tbl_units->sortname : '';

                        $productselect .= '<option value="' . $product->pro_id . '-' . $product->price . '-' . $sortname . '" ' . $proSelected . '>' . $product->name . '</option>';
                        $product_options .= '<option value="' . $product->pro_id . '-' . $product->price . '-' . $sortname . '">' . $product->name . '</option>';
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
                $unitId = 'unit' . $k;
                $quantityInput = '<div class="input-group">';
                $quantityInput .= '<input type="number" value="' . $invpro->quantity . '" name="quantity[]" id="' . $quantityId . '" class="form-control quantity" onkeyup="return calculateTotal();">';
                $quantityInput .= '<div class="input-group-prepend"><span class="input-group-text units" id="' . $unitId . '">' . $sortname . '</span></div>';
                $quantityInput .= '<div>';

                //---------------------Price-------------------------------
                $priceId = 'price' . $k;
                $priceInput = '<div class="input-group">';
                $priceInput .= '<div class="input-group-prepend"><span class="input-group-text">' . $currency->html_code . '</span></div>';
                $priceInput .= '<input type="number" value="' . $productCost->price . '" name="price[]" id="' . $priceId . '" class="form-control price" onkeyup="return calculateTotal();" readonly>';
                $priceInput .= '</div>';

                //---------------------Amount-------------------------------
                $amountId = 'amount' . $k;
                $amountInput = '<div class="input-group">';
                $amountInput .= '<div class="input-group-prepend"><span class="input-group-text">' . $currency->html_code . '</span></div>';
                $amountInput .= '<input type="number" value="' . (int) $invpro->quantity * (int) $productCost->price . '" name="amount[]" id="' . $amountId . '" class="form-control amounts" readonly>';
                $amountInput .= '</div>';

                $trId = 'tr' . $k;
                $table .= '<tr id="' . $trId . '">';
                $table .= '<td>' . $productselect . '</td>';
                $table .= '<td>' . $quantityInput . '</td>';
                $table .= '<td>' . $priceInput . '</td>';
                $table .= '<td>' . $taxselect . '</td>';
                $table .= '<td>';
                if ($k > 1) {
                    $table .= '<div class="input-group">' . $amountInput . '<div class="input-group-append"><span class="input-group-text" onclick="removeRow(' . $k . ');"><i class="fa fa-minus-circle"></i></span></div></div>';
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

            $posId = $invoice->pos_id;
            $posOptions = $this->getPostOrderStageOptions($posId);
            $data['posOptions'] = $posOptions;

            return view('auth.invoice.edit')->with('data', $data);
        } else {
            return redirect('/invoice');
        }
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

        $user = Auth::user();
        $invoice = Tbl_invoice::find($id);

        if ($user->can('update', $invoice)) {

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
            $pos_id = $request->input('pos_id');

            if ($inv_number > 0) {
                $invIds = Tbl_invoice::where('inv_number', $inv_number)->where('inv_id', '!=', $id)->get(['inv_id']);
                foreach ($invIds as $invId) {
                    if ($id == $invId) {
                        return redirect('invoice/' . $id . '/edit')->with('error', 'Please inv number already taken');
                    }
                }
            } else {
                return redirect('invoice/' . $id . '/edit')->with('error', 'Please enter inv number');
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
                    return redirect('invoice/' . $id . '/edit')->with('error', 'Please upload jpg, png and giff images only.');
                }
                //-------------Image Validation----------------------------------
                //            $file = $request->file('userpicture');
                $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
                $file->move('uploads/invoice/', $name);  //public_path().
                $filename = '/uploads/invoice/' . $name;
            }


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
            $invoice->notes = $request->input('notes');
            $invoice->pos_id = $request->input('pos_id');
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

                //            $invoice_product = Tbl_invoice_products::create($prodata);

                Tbl_invoice_products::create($prodata);
            }

            return redirect('invoice/' . $id)->with('success', 'Updated Successfully...');
        } else {
            return redirect('/invoice');
        }
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

    public function delete($id)
    {
        $user = Auth::user();
        $inv = Tbl_invoice::find($id);

        if ($user->can('delete', $inv)) {

            // $inv = Tbl_invoice::find($id);
            $inv->active = 0;
            $inv->save();
            return redirect('/invoice')->with('success', 'Deleted Successfully...!');
        } else {
            return redirect('/invoice');
        }
    }

    public function getProductoptions($uid)
    {
        $products = Tbl_products::with('tbl_units')->where('active', 1)->get();     //where('uid', $uid)->
        //        echo json_encode($products);
        //        exit();
        $product_options = '<option value="0">Select Product</option>';
        if (count($products) > 0) {
            foreach ($products as $product) {

                $units = ($product->tbl_units != '') ? $product->tbl_units->sortname : '';

                $product_options .= '<option value="' . $product->pro_id . '-' . $product->price . '-' . $units . '">' . $product->name . '</option>';
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

    public function getPostOrderStageOptions($posId)
    {
        $taxs = Tbl_post_order_stage::all();
        $tax_options = '<option value="0">Select Invoice Stage</option>';
        // if (count($taxs) > 0) {
        foreach ($taxs as $tax) {
            $selected = ($tax->pos_id == $posId) ? 'selected' : '';
            $tax_options .= '<option value="' . $tax->pos_id . '" ' . $selected . '>' . $tax->stage . '</option>';
        }
        // }
        return $tax_options;
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->input('id');
        return Tbl_invoice::whereIn('inv_id', $ids)->update(array('active' => 0));
    }

    public function getInvoicesFilter(Request $request)
    {
        $uid = Auth::user()->id;

        $type = $request->input('type');
        $year = $request->input('year');
        $month = $request->input('month');

        $invoices = '';

        if ($type == "monthly") {
            $invoices = Tbl_invoice::where('uid', $uid)
                ->where('active', 1)
                ->where(DB::raw('MONTH(created_at)'), $month)
                ->where(DB::raw('YEAR(created_at)'), $year)
                ->orderBy('inv_id', 'desc')
                ->get();
        }

        if ($type == "quarterly") {

            if ($month == 1) {
                $monthArr = array(1, 2, 3, 4);
            }

            if ($month == 2) {
                $monthArr = array(5, 6, 7, 8);
            }

            if ($month == 3) {
                $monthArr = array(9, 10, 11, 12);
            }

            $invoices = Tbl_invoice::where('uid', $uid)
                ->where('active', 1)
                ->whereIn(DB::raw('MONTH(created_at)'), $monthArr)
                ->where(DB::raw('YEAR(created_at)'), $year)
                ->orderBy('inv_id', 'desc')
                ->get();
        }

        if ($type == "annually") {
            $invoices = Tbl_invoice::where('uid', $uid)
                ->where('active', 1)
                ->where(DB::raw('YEAR(created_at)'), $year)
                ->orderBy('inv_id', 'desc')
                ->get();
        }

        $total = count($invoices);

        $user = User::find($uid);
        $currency = currency::find($user->cr_id);

        if ($total > 0) {
            $formstable = '<div class="table-responsive"><table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Invoice Number</th>';
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

                $inv_image = ($formdetails->cmp_logo != '') ? $formdetails->cmp_logo : 'uploads/default/invoice.png';

                $formstable .= '<tr>';
                $formstable .= '<td>' . $formdetails->inv_number . '</td>';
                $formstable .= '<td class="table-title">';
                $formstable .= '<img src="' . url($inv_image) . '" width="30" height="30">&nbsp;';
                $formstable .= '<a href="' . url('invoice/' . $formdetails->inv_id) . '">' . $formdetails->name . '</a>&nbsp;';
                $formstable .= '</td>';
                $formstable .= '<td>' . $formdetails->billto . '</td>';
                $formstable .= '<td>' . $currency->html_code . ' ' . $formdetails->total_amount . '</td>';
                $status = ($formdetails->status == 1) ? 'Send' : 'Draft';
                $formstable .= '<td>' . $status . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a class="text-default text-btn-space" href="' . url('invoice/' . $formdetails->inv_id . '/edit') . '">Edit</a></li>
                    <li><a class="text-default text-btn-space" href="' . url('invoice/delete/' . $formdetails->inv_id) . '">Delete</a></li>
                  </ul>
                </div>';

                $formstable .= '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table></div>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;

        return json_encode($data);
    }

    public function changeInvoiceStage(Request $request)
    {
        // echo json_encode($request->input());
        // exit();

        $posId = $request->input('posId');
        $invId = $request->input('invId');

        $res = Tbl_invoice::where('inv_id', $invId)->update([
            'pos_id' => $posId
        ]);

        return $res;
    }
}

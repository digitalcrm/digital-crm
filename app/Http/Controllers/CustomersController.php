<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Tbl_deals;
use App\Tbl_salesfunnel;
use App\Tbl_leads;
use App\currency;
use App\Tbl_products;
use App\User;
use App\Tbl_units;
use App\Tbl_leadsource;
use App\Tbl_leadstatus;
use App\Tbl_lossreasons;
use App\Tbl_productcategory;
use App\Tbl_post_order_stage;
use App\Company;
// use Excel;

use App\Imports\CustomersImport;
use App\Exports\CustomersExport;
use Maatwebsite\Excel\Facades\Excel;

class CustomersController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('test:customers', ['only' => ['index', 'show', 'create', 'store', 'edit', 'update', 'delete', 'editCustomer', 'printCustomers', 'updateCustomer', 'import', 'importData', 'export', 'exportData']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $uid = Auth::user()->id;
        $cr_id = Auth::user()->cr_id;
        $currency = currency::find($cr_id);

        $deals = Tbl_deals::where('active', 1)
            ->where('uid', $uid)
            ->where('deal_status', 1)
            ->with('tbl_salesfunnel')
            ->with('tbl_leadsource')
            ->with('Tbl_products')
            //                ->with('Tbl_leads')
            ->with(array('tbl_leads' => function ($query) {
                $query->where('tbl_leads.active', 1);
            }))
            ->orderBy('closing_date', 'desc')
            ->get();
        // echo json_encode($deals);
        // exit();

        if (count($deals) > 0) {
            $formstable = '<div class="table-responsive"><table id="customersTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Lead</th>';
            $formstable .= '<th>Deal Name</th>';
            $formstable .= '<th>Amount</th>';
            $formstable .= '<th>Closing Date</th>';
            $formstable .= '<th>Product</th>';
            $formstable .= '<th>Company</th>';
            $formstable .= '<th>Lead Source</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($deals as $formdetails) {

                // echo $formdetails['tbl_products']['company'].'<br>';

                $leadsource = ($formdetails['tbl_leadsource'] != '') ? $formdetails['tbl_leadsource']['leadsource'] : '';

                $leadimage = ($formdetails['tbl_leads']['picture'] != '') ? $formdetails['tbl_leads']['picture'] : '/uploads/default/leads.png';

                $product = ($formdetails['pro_id'] > 0) ? $formdetails['tbl_products']['name'] : '';

                $c_name = '';
                if ((!is_null($formdetails['tbl_products'])) && ($formdetails['tbl_products']['company'] > 0)) {
                    $company = Company::find($formdetails['tbl_products']['company']);
                    $c_name = $company->c_name;
                }

                $formstable .= '<tr>';
                $formstable .= '<td><img src="' . url($leadimage) . '" class="avatar"><a href="' . url('customers/profile/' . $formdetails['deal_id']) . '">' . $formdetails['tbl_leads']['first_name'] . ' ' . $formdetails['tbl_leads']['last_name'] . '</a></td>';
                $formstable .= '<td class="table-title"><a href="' . url('customers/' . $formdetails['deal_id']) . '">' . $formdetails['name'] . '</a></td>';
                $formstable .= '<td>' . $currency->html_code . ' ' . $formdetails['value'] . '</td>';
                $formstable .= '<td>' . date('m-d-Y', strtotime($formdetails['closing_date'])) . '</td>';
                $formstable .= '<td>' . $product . '</td>';
                $formstable .= '<td>' . $c_name . '</td>';
                $formstable .= '<td>' . $leadsource . '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table></div>';
        } else {
            $formstable = 'No records available';
        }
        $data['total'] = count($deals);
        $data['table'] = $formstable;

        // exit();

        return view('auth.customers.index')->with("data", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $deals = Tbl_deals::with('Tbl_leads')
            ->with('Tbl_leadsource')
            ->with('Tbl_salesfunnel')
            ->find($id);

        $user = Auth::user();
        if ($user->can('view', $deals)) {


            $data['deals'] = $deals->toArray();
            return view('auth.customers.show')->with("data", $data);
        } else {
            return redirect('/customers');
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
        //
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
        //
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

    public function exportData($type)
    {

        $uid = Auth::user()->id;
        return Excel::download(new CustomersExport($uid), 'customers.xlsx');
    }

    public function import($type)
    {
        return view('auth.customers.import');
    }

    public function importData(Request $request)
    {
        $uid = Auth::user()->id;
        //        echo json_encode($request->input());

        $filename = '';
        if ($request->hasfile('importFile')) {

            //-------------Image Validation----------------------------------
            $file = $request->file('importFile');
            // Build the input for validation
            $fileArray = array('importFile' => $file, 'extension' => strtolower($file->getClientOriginalExtension()));

            // Tell the validator that this file should be an image
            $rules = array(
                'importFile' => 'required', // max 10000kb
                'extension' => 'required|in:csv'
            );

            // Now pass the input and rules into the validator
            $validator = Validator::make($fileArray, $rules);

            // Check to see if validation fails or passes
            if ($validator->fails()) {
                return redirect('/customers/import/csv')->with('error', 'Please upload .csv only.');
            }
            //-------------Image Validation----------------------------------

            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $path = $request->file('importFile')->getRealPath();

            $res = Excel::import(new CustomersImport($uid), request()->file('importFile'));


            if ($res) {
                echo 'Success';
                return redirect('/customers')->with('success', 'Uploaded successfully');
            } else {
                // echo 'Failed';
                return redirect('/customers/import/csv')->with('error', "Error ocurred. Try again later..!");
            }
        } else {
            return redirect('/customers/import/csv')->with('error', 'Please upload file.');
        }
    }

    public function customerProfile($id)
    {
        $user = Auth::user();
        $deals = Tbl_deals::with('Tbl_leads')
            ->with('Tbl_leadsource')
            ->with('Tbl_salesfunnel')
            ->with('Tbl_lossreasons')
            ->with('Tbl_products')
            ->with('Tbl_deal_events')
            ->find($id);

        if ($user->can('view', $deals)) {

            //  Deal Details
            $deal = $deals->toArray();

            // echo json_encode($deal);
            // exit();

            $data['deals'] = $deal;

            //  Lead Details
            $lead = Tbl_leads::with('Tbl_leadsource')
                ->with('Tbl_industrytypes')
                ->with('Tbl_leadstatus')
                ->with('Tbl_Accounts')
                ->with('Tbl_countries')
                ->with('Tbl_states')
                ->with('Tbl_salutations')
                ->find($deal['ld_id'])->toArray();

            $data['leadarr'] = $lead;

            $editLink = url('leads') . '/' . $id . '/edit';
            $data['editLink'] = $editLink;
            //  Product Details
            $products = '';
            if ($deal['tbl_products'] != '') {
                $products = Tbl_products::with('Tbl_units')->find($deal['tbl_products']['pro_id']);
                //        echo json_encode($products);
                //        exit();

            }

            $data['product'] = $products;

            $uid = Auth::user()->id;
            $user = User::find($uid);
            $units = Tbl_units::all();
            $option = '<option value="">Select Unit</option>';
            $unitt = '';
            if ($products != null) {
                foreach ($units as $unit) {
                    $selected = ((($products != '') && ($products['tbl_units'] != '')) && ($unit->unit_id == $products['tbl_units']->unit_id)) ? 'selected' : '';
                    $option .= '<option value="' . $unit->unit_id . '" ' . $selected . '>' . $unit->name . '</option>';
                    if ($unit != '') {
                        if ($selected != '') {
                            $unitt = $unit;
                        }
                    }
                }
            }
            $data['unitOptions'] = $option;


            $data['user'] = $user;
            $data['unit'] = $unitt;

            return view('auth.customers.profile')->with("data", $data);
        } else {
            return redirect('/customers');
        }
    }

    public function editCustomer($id)
    {
        //  Deal Details
        $uid = Auth::user()->id;
        
        $user = Auth::user();
        $deals = Tbl_deals::with('Tbl_leads')
            ->with('Tbl_leadsource')
            ->with('Tbl_salesfunnel')
            ->with('Tbl_products')
            ->find($id);

        if ($user->can('view', $deals)) {

            //        echo json_encode($deals);
            //        exit();

            $data['deal'] = $deals;

            $data['lead'] = Tbl_leads::find($deals->tbl_leads->ld_id);


            $leadsource = Tbl_leadsource::all();
            //        echo json_encode($leadsource);
            //        exit();
            $leadsourceoptions = "<option value='0'>Select Lead Source</option>";
            if (count($leadsource) > 0) {
                foreach ($leadsource as $source) {
                    $leadsourceselected = (($deals->tbl_leadsource != '') && ($deals->tbl_leadsource->ldsrc_id == $source->ldsrc_id)) ? 'selected' : '';
                    $leadsourceoptions .= "<option value='" . $source->ldsrc_id . "' " . $leadsourceselected . ">" . $source->leadsource . "</option>";
                }
            }
            $data['leadsourceoptions'] = $leadsourceoptions;

            $dealstage = Tbl_salesfunnel::all();
            $dealstageoptions = "<option value=''>Select Deal Stage</option>";
            if (count($dealstage) > 0) {
                foreach ($dealstage as $stage) {
                    $dealstageselected = ($deals->tbl_salesfunnel->sfun_id == $stage->sfun_id) ? 'selected' : '';
                    $dealstageoptions .= "<option value='" . $stage->sfun_id . "' " . $dealstageselected . ">" . $stage->salesfunnel . "</option>";
                }
            }
            $data['dealstageoptions'] = $dealstageoptions;

            $lossreasons = Tbl_lossreasons::all();
            $lossreasonoptions = "<option value=''>Select Loss Reason</option>";
            if (count($lossreasons) > 0) {
                foreach ($lossreasons as $lossreason) {
                    $lossreasonselected = (($deals->tbl_lossreasons != '') && ($deals->tbl_lossreasons->lr_id == $lossreason->lr_id)) ? 'selected' : '';
                    $lossreasonoptions .= "<option value='" . $lossreason->lr_id . "' " . $lossreasonselected . ">" . $lossreason->reason . "</option>";
                }
            }
            $data['lossreasonoptions'] = $lossreasonoptions;

            //        $uid = Auth::user()->id;
            $user = User::with('currency')->find($uid)->toArray();
            $data['user'] = $user;

            //  Products
            $products = Tbl_products::where('active', 1)->get();    //where('uid', $uid)->
            $productoptions = '<option value="">Select ...</option>';
            foreach ($products as $product) {
                $productselected = (($deals->tbl_products != '') && ($deals->tbl_products->pro_id == $product->pro_id)) ? 'selected' : '';
                $productoptions .= '<option value="' . $product->pro_id . '" ' . $productselected . '>' . $product->name . '</option>';
            }
            $data['productoptions'] = $productoptions;


            //  Post order stage
            $orders = Tbl_post_order_stage::all();
            $orderoptions = "<option value=''>Select Post Order Stage</option>";
            if (count($orders) > 0) {
                foreach ($orders as $order) {
                    $orderselected = (($deals->tbl_post_order_stage != '') && ($deals->tbl_post_order_stage->pos_id == $order->pos_id)) ? 'selected' : '';
                    $orderoptions .= "<option value='" . $order->pos_id . "' " . $orderselected . ">" . $order->stage . "</option>";
                }
            }
            $data['orderoptions'] = $orderoptions;

            return view('auth.customers.edit')->with('data', $data);
        } else {
            return redirect('/customers');
        }
    }

    public function updateCustomer(Request $request, $id)
    {
        //        echo json_encode($request->input());

        $user = Auth::user();
        $deals = Tbl_deals::find($id);

        if ($user->can('view', $deals)) {

            $this->validate($request, [
                'dealname' => 'required|max:255',
                'amount' => 'required|numeric',
                'closingdate' => 'required|date_format:d-m-Y',
                'lead' => 'required',
                'product' => 'required',
            ]);

            $formdata = $request->input();
            $sfun_id = $request->input('dealstage');

            $deal_status = 0;
            if ($sfun_id == 5) {
                $deal_status = 1;
            }
            if ($sfun_id == 6) {
                $deal_status = 2;
            }

            $formdata['deal_status'] = $deal_status;

            $res = $this->updateDeal($formdata, $id);

            if ($res) {
                return redirect('/customers')->with('success', 'Customer Updated Successfully...!');
            } else {
                return redirect('/customers')->with('error', 'Failed. Please try again...');
            }
        } else {
            return redirect('/customers');
        }
    }

    public function updateDeal($formdata, $id)
    {
        $deals = Tbl_deals::find($id);
        //        echo json_encode($deals);
        //        exit();
        //  Tracking Deal Stages

        $from = $deals->sfun_id;
        $to = (isset($formdata['dealstage'])) ? $formdata['dealstage'] : $deals->sfun_id;
        $deal_status = (isset($formdata['deal_status'])) ? $formdata['deal_status'] : $deals->deal_status;

        if ($from != $to) {
            $this->dealStageEvents($id, $from, $to, $deal_status);
        }

        $deals->ld_id = (isset($formdata['lead'])) ? $formdata['lead'] : $deals->ld_id;
        $deals->sfun_id = (isset($formdata['dealstage'])) ? $formdata['dealstage'] : $deals->sfun_id;
        $deals->ldsrc_id = (isset($formdata['leadsource'])) ? $formdata['leadsource'] : $deals->ldsrc_id;
        $deals->name = (isset($formdata['dealname'])) ? $formdata['dealname'] : $deals->name;
        $deals->value = (isset($formdata['amount'])) ? $formdata['amount'] : $deals->value;
        $deals->closing_date = (isset($formdata['closingdate'])) ? date('Y-m-d', strtotime(str_replace('/', '-', $formdata['closingdate']))) : $deals->closing_date;
        $deals->notes = (isset($formdata['notes'])) ? $formdata['notes'] : $deals->notes;
        $deals->deal_status = (isset($formdata['deal_status'])) ? $formdata['deal_status'] : $deals->deal_status;
        $deals->probability = (isset($formdata['probability'])) ? $formdata['probability'] : $deals->probability;
        $deals->lr_id = (isset($formdata['lossreason'])) ? $formdata['lossreason'] : $deals->lr_id;
        $deals->pro_id = (isset($formdata['product'])) ? $formdata['product'] : $deals->pro_id;
        $deals->pos_id = (isset($formdata['pos_id'])) ? $formdata['pos_id'] : $deals->pos_id;
        return $deals->save();
    }

    public function getFilterResults(Request $request)
    {
        //        return json_encode($request->input());

        $pro_id = $request->input('pro_id');
        $pos_id = $request->input('pos_id');

        $uid = Auth::user()->id;
        $cr_id = Auth::user()->cr_id;
        $currency = currency::find($cr_id);
        $deals = array();

        if (($pro_id == 0) && ($pos_id == 0)) {

            $deals = Tbl_deals::where('active', 1)
                ->with('tbl_salesfunnel')
                ->with('tbl_leadsource')
                ->with('Tbl_products')
                //                ->with('Tbl_leads')
                ->with(array('tbl_leads' => function ($query) {
                    $query->where('tbl_leads.active', 1);
                }))
                ->where('uid', $uid)
                ->where('deal_status', 1)
                ->orderBy('closing_date', 'desc')
                ->get();
        }

        if (($pro_id > 0) && ($pos_id > 0)) {

            $deals = Tbl_deals::where('active', 1)->where('pro_id', $pro_id)->where('pos_id', $pos_id)
                ->with('tbl_salesfunnel')
                ->with('tbl_leadsource')
                ->with('Tbl_products')
                //                ->with('Tbl_leads')
                ->with(array('tbl_leads' => function ($query) {
                    $query->where('tbl_leads.active', 1);
                }))
                ->where('uid', $uid)
                ->where('deal_status', 1)
                ->orderBy('closing_date', 'desc')
                ->get();
        }
        if ($pro_id > 0) {

            $deals = Tbl_deals::where('active', 1)->where('pro_id', $pro_id)
                ->with('tbl_salesfunnel')
                ->with('tbl_leadsource')
                ->with('Tbl_products')
                //                ->with('Tbl_leads')
                ->with(array('tbl_leads' => function ($query) {
                    $query->where('tbl_leads.active', 1);
                }))
                ->where('uid', $uid)
                ->where('deal_status', 1)
                ->orderBy('closing_date', 'desc')
                ->get();
        }
        if ($pos_id > 0) {

            $deals = Tbl_deals::where('active', 1)->where('pos_id', $pos_id)
                ->with('tbl_salesfunnel')
                ->with('tbl_leadsource')
                ->with('Tbl_products')
                //                ->with('Tbl_leads')
                ->with(array('tbl_leads' => function ($query) {
                    $query->where('tbl_leads.active', 1);
                }))
                ->where('uid', $uid)
                ->where('deal_status', 1)
                ->orderBy('closing_date', 'desc')
                ->get();
        }



        //        echo json_encode($deals);
        $total = count($deals);
        $formstable = "";
        if ($total > 0) {
            $formstable = '<div class="table-responsive"><table id="customersTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Lead</th>';
            $formstable .= '<th>Deal Name</th>';
            $formstable .= '<th>Amount</th>';
            $formstable .= '<th>Closing Date</th>';
            $formstable .= '<th>Product</th>';
            $formstable .= '<th>Lead Source</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($deals as $formdetails) {

                $leadsource = ($formdetails['tbl_leadsource'] != '') ? $formdetails['tbl_leadsource']['leadsource'] : '';

                $leadimage = ($formdetails['tbl_leads']['picture'] != '') ? $formdetails['tbl_leads']['picture'] : '/uploads/default/leads.png';

                $product = ($formdetails['pro_id'] > 0) ? $formdetails['tbl_products']['name'] : '';

                $formstable .= '<tr>';
                $formstable .= '<td>';
                $formstable .= '<img src="' . url($leadimage) . '" width="30" height="24">&nbsp;';
                $formstable .= '<a href="' . url('customers/profile/' . $formdetails['deal_id']) . '">' . $formdetails['tbl_leads']['first_name'] . ' ' . $formdetails['tbl_leads']['last_name'] . '</a>';
                $formstable .= '</td>';
                $formstable .= '<td class="table-title"><a href="' . url('customers/' . $formdetails['deal_id']) . '">' . $formdetails['name'] . '</a></td>';
                $formstable .= '<td><span>' . $currency->html_code . '</span>&nbsp;' . $formdetails['value'] . '</td>';
                $formstable .= '<td>' . date('m-d-Y', strtotime($formdetails['closing_date'])) . '</td>';
                $formstable .= '<td>' . $product . '</td>';
                $formstable .= '<td>' . $leadsource . '</td>';
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

    public function printCustomers($id)
    {
        $uid = Auth::user()->id;
        $cr_id = Auth::user()->cr_id;
        $currency = currency::find($cr_id);

        $deals = Tbl_deals::where('active', 1)
            ->where('uid', $uid)
            ->where('deal_status', 1)
            //                ->with('tbl_salesfunnel')
            ->with('tbl_leadsource')
            ->with('Tbl_products')
            //                ->with('Tbl_leads')
            ->with(array('tbl_leads' => function ($query) {
                $query->where('tbl_leads.active', 1);
            }))
            ->orderBy('closing_date', 'desc')
            ->get();
        //        echo json_encode($deals);

        if (count($deals) > 0) {
            $formstable = '<div class="table-responsive"><table id="" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Lead</th>';
            $formstable .= '<th>Deal Name</th>';
            $formstable .= '<th>Amount</th>';
            $formstable .= '<th>Closing Date</th>';
            $formstable .= '<th>Product</th>';
            $formstable .= '<th>Lead Source</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($deals as $formdetails) {

                $leadsource = ($formdetails['tbl_leadsource'] != '') ? $formdetails['tbl_leadsource']['leadsource'] : '';

                $leadimage = ($formdetails['tbl_leads']['picture'] != '') ? $formdetails['tbl_leads']['picture'] : '/uploads/default/leads.png';

                $product = ($formdetails['pro_id'] > 0) ? $formdetails['tbl_products']['name'] : '';

                $formstable .= '<tr>';
                $formstable .= '<td>';
                $formstable .= '<img src="' . url($leadimage) . '" width="30" height="24">&nbsp;';
                $formstable .= $formdetails['tbl_leads']['first_name'] . ' ' . $formdetails['tbl_leads']['last_name'];
                $formstable .= '</td>';
                $formstable .= '<td class="table-title">' . $formdetails['name'] . '</td>';
                $formstable .= '<td><span>' . $currency->html_code . '&nbsp;</span>' . $formdetails['value'] . '</td>';
                $formstable .= '<td>' . date('m-d-Y', strtotime($formdetails['closing_date'])) . '</td>';
                $formstable .= '<td>' . $product . '</td>';
                $formstable .= '<td>' . $leadsource . '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table></div>';
        } else {
            $formstable = 'No records available';
        }
        $data['total'] = count($deals);
        $data['table'] = $formstable;


        return view('auth.customers.print')->with("data", $data);
    }
}

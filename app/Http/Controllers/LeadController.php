<?php

namespace App\Http\Controllers;

use App\User;
use App\Admin;
use App\Company;
use App\currency;
use App\Tbl_deals;

use App\Tbl_leads;
use App\Tbl_events;
use App\Tbl_states;
use App\Tbl_invoice;
use App\Tbl_Accounts;
use App\Tbl_contacts;
use App\Tbl_products;
use App\Tbl_countries;
use App\Tbl_leadsource;
use App\Tbl_leadstatus;
use App\Tbl_cart_orders;
use App\Tbl_salutations;
use App\Tbl_industrytypes;
use App\Exports\LeadsExport;
use App\Imports\LeadsImport;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
// use Excel;

use App\Exports\ProductLeadsExport;
use App\Imports\ProductLeadsImport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\DealController;
// Controllers
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('test:leads', ['only' => ['index', 'show', 'create', 'store', 'edit', 'update', 'delete', 'deleteAll', 'getLeads', 'leadStatusfilter', 'deleteLead', 'import', 'importData', 'export', 'exportData']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $uid = Auth::user()->id;


        //  Lead Status



        // $day = date('Y-m-d');
        // $weekDay = date('Y-m-d', strtotime($day . ' - 1 week'));

        // echo $day . ' ' . $weekDay;
        // exit();
        $status = 'All';
        $day = '';
        $weekDay = '';

        $leads = $this->getLeads($uid, $weekDay, $day, $status);
        // echo json_encode($leads);
        // exit();

        // $leads = $this->getLeads($uid, $day);
        $leadstatuslist = Tbl_leadstatus::all();

        $leadstatusSelect = '<option value="All">All</option>';
        foreach ($leadstatuslist as $statuslist) {
            $leadstatusSelect .= '<option value="' . $statuslist->ldstatus_id . '">' . $statuslist->status . '</option>';
        }

        $leads['leadstatusSelect'] = $leadstatusSelect;

        $leads['oldDate'] = $this->getOldDate();
        $leads['latestDate'] = $this->getLatestDate();
        $leads['timer'] = 'All';
        $leads['leadStatusVal'] = 'All';

        return view('auth.leads.index')->with("data", $leads);
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
        if ($user->can('create', Tbl_leads::class)) {

            $country = Tbl_countries::all();
            $countryoptions = "<option value='0'>Select Country</option>";
            if (count($country) > 0) {
                foreach ($country as $cnt) {
                    $countryoptions .= "<option value='" . $cnt->id . "'>" . $cnt->name . ' - ' . $cnt->sortname . "</option>";
                }
            }
            $data['countryoptions'] = $countryoptions;

            $industry = Tbl_industrytypes::all();
            $industryoptions = "<option value='0'>Select Industry Type</option>";
            if (count($industry) > 0) {
                foreach ($industry as $int) {
                    $industryoptions .= "<option value='" . $int->intype_id . "'>" . $int->type . "</option>";
                }
            }
            $data['industryoptions'] = $industryoptions;

            $leadsource = Tbl_leadsource::all();
            $leadsourceoptions = "<option value='0'>Select Lead Source</option>";
            if (count($leadsource) > 0) {
                foreach ($leadsource as $source) {
                    $leadsourceoptions .= "<option value='" . $source->ldsrc_id . "'>" . $source->leadsource . "</option>";
                }
            }
            $data['leadsourceoptions'] = $leadsourceoptions;

            $leadstatus = Tbl_leadstatus::all();
            $leadstatusoptions = "<option value='0'>Select Lead Status</option>";
            if (count($leadstatus) > 0) {
                foreach ($leadstatus as $ldstatus) {
                    $leadstatusoptions .= "<option value='" . $ldstatus->ldstatus_id . "'>" . $ldstatus->status . "</option>";
                }
            }
            $data['leadstatusoptions'] = $leadstatusoptions;

            $accounts = Tbl_Accounts::where('active', 1)->where('uid', $uid)->get();
            $accountoptions = "<option value='0'>Select Account</option>";
            if (count($accounts) > 0) {
                foreach ($accounts as $account) {
                    $accountoptions .= "<option value='" . $account->acc_id . "'>" . $account->name . "</option>";
                }
            }
            $accountoptions .= "<option disabled>---</option>";
            $accountoptions .= "<option value='NewAccount'>Add Account</option>";
            $data['accountoptions'] = $accountoptions;


            $salutations = Tbl_salutations::all();
            $salutationoptions = "<option value='0'>None</option>";
            if (count($salutations) > 0) {
                foreach ($salutations as $salutation) {
                    $salutationoptions .= "<option value='" . $salutation->sal_id . "'>" . $salutation->salutation . "</option>";
                }
            }
            $data['salutationoptions'] = $salutationoptions;

            //  Products
            $products = Tbl_products::where('uid', $uid)->where('active', 1)->get();    //where('uid', $uid)->
            $productoptions = '<option value="">Select Product...</option>';
            foreach ($products as $product) {
                $productoptions .= '<option value="' . $product->pro_id . '">' . $product->name . '</option>';
            }
            $data['productoptions'] = $productoptions;

            return view('auth.leads.create')->with('data', $data);
        } else {
            return redirect('/leads');
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
        // echo json_encode($request->input());
        // exit();

        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255:tbl_leads',
        ]);

        $filename = '';
        if ($request->hasfile('userpicture')) {


            //-------------Image Validation----------------------------------
            $file = $request->file('userpicture');
            // Build the input for validation
            $fileArray = array('userpicture' => $file);

            // Tell the validator that this file should be an image
            $rules = array(
                'userpicture' => 'mimes:jpeg,jpg,png,gif|max:10000' // max 10000kb
            );

            // Now pass the input and rules into the validator
            $validator = Validator::make($fileArray, $rules);

            // Check to see if validation fails or passes
            if ($validator->fails()) {
                return redirect('/leads/create')->with('error', 'Please upload jpg, png and giff images only.');
            }
            //-------------Image Validation----------------------------------
            //            $file = $request->file('userpicture');
            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $file->move('uploads/leads/', $name);  //public_path().
            $filename = '/uploads/leads/' . $name;
        }

        $acc_id = 0;
        if ($request->input('account') == 'NewAccount') {
            $arr_account = array(
                'uid' => Auth::user()->id,
                'name' => $request->input('addAccount'),
            );
            $accounts = Tbl_Accounts::create($arr_account);
            $acc_id = $accounts->acc_id;
        } else {
            $acc_id = $request->input('account');
        }

        $ld_status = $request->input('leadstatus');

        $uid = Auth::user()->id;
        $leadname = $request->input('first_name') . ' ' . $request->input('last_name') . ' ' . date('m-d-Y-h-i-s');
        $product = ($request->input('product') != '') ? $request->input('product') : 0;

        $formdata = array(
            'uid' => $uid,
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'picture' => $filename,
            'mobile' => $request->input('mobile'),
            'phone' => $request->input('phone'),
            'ldsrc_id' => $request->input('leadsource'),
            'ldstatus_id' => (int) $ld_status,
            'intype_id' => $request->input('industrytype'),
            'acc_id' => $acc_id,
            'notes' => $request->input('notes'),
            'website' => $request->input('website'),
            'country' => $request->input('country'),
            'state' => $request->input('state'),
            'city' => $request->input('city'),
            'street' => $request->input('street'),
            'zip' => $request->input('zip'),
            'company' => $request->input('company'),
            'sal_id' => $request->input('salutation'),
            'designation' => $request->input('designation'),
            'pro_id' => $product,
        );

        // echo json_encode($formdata);
        // exit();

        //        $leads = Tbl_leads::create($formdata);
        $leads = $this->addLead($formdata);
        $ld_id = $leads->ld_id;

        if ($ld_id > 0) {

            if ($ld_status > 0) {
                $leadStatus = Tbl_leadstatus::find($ld_status);
                if ($leadStatus->deal == 1) {


                    $closingDate = date('Y-m-d', strtotime(' + 30 days'));
                    $notes = 'deal created on ' . date('m-d-Y-h-i-s');
                    $deal_status = 0;
                    $probability = 0;


                    $dealObj = new DealController();

                    $dealdata = array(
                        'uid' => $uid,
                        'ld_id' => $ld_id,
                        'sfun_id' => 0,
                        'ldsrc_id' => 0,
                        'name' => $leadname,
                        'value' => 10,
                        'closing_date' => $closingDate,
                        'notes' => $notes,
                        'deal_status' => $deal_status,
                        'probability' => $probability,
                        'pro_id' => $product,
                        'dl_id' => 0,
                    );

                    $dealRes = $dealObj->addDeal($dealdata);
                }
            }
            return redirect('/leads')->with('success', 'Lead Created Successfully...!');
        } else {
            return redirect('/leads')->with('error', 'Error occurred. Please try again...!');
        }
    }

    public function addLead($formdata)
    {
        return Tbl_leads::create($formdata);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $user = Auth::user();
        $lead  = Tbl_leads::find($id);

        if ($user->can('view', $lead)) {

            // $leads = Tbl_leads::find($id);
            $uid = Auth::user()->id;

            $leads = $this->getLeadDetails($id);

            $leadarr = $leads->toArray();

            $data['leadarr'] = $leadarr;

            $editLink = url('leads') . '/' . $id . '/edit';
            $data['editLink'] = $editLink;

            $cr_id = Auth::user()->cr_id;
            $currency = currency::find($cr_id);

            $deals = Tbl_deals::where('ld_id', $id)
                ->with('Tbl_leads')
                ->with('Tbl_leadsource')
                ->with('Tbl_salesfunnel')
                ->get()->toArray();


            $total = count($deals);
            if ($total > 0) {
                $formstable = '<div class="table-responsive"><table id="dealsTable" class="table">';
                $formstable .= '<thead>';
                $formstable .= '<tr>';
                $formstable .= '<th>Deal Name</th>';
                $formstable .= '<th>Deal Stage</th>';
                $formstable .= '<th>Lead</th>';
                $formstable .= '<th>Amount<span>&nbsp;(' . $currency->html_code . ')</span></th>';
                $formstable .= '<th>Closing Date</th>';
                $formstable .= '</tr>';
                $formstable .= '</thead>';
                $formstable .= '<tbody>';
                foreach ($deals as $formdetails) {
                    $formstable .= '<tr>';

                    $leadimage = ($formdetails['tbl_leads']['picture'] != '') ? $formdetails['tbl_leads']['picture'] : '/uploads/default/leads.png';

                    $formstable .= '<td class="table-title"><a href="' . url('deals/' . $formdetails['deal_id']) . '">' . $formdetails['name'] . '</a></td>';
                    $formstable .= '<td>' . $formdetails['tbl_salesfunnel']['salesfunnel'] . '</td>';
                    $formstable .= '<td><img src="' . url($leadimage) . '" width="30" height="24">&nbsp;' . $formdetails['tbl_leads']['first_name'] . ' ' . $formdetails['tbl_leads']['last_name'] . '</td>';
                    $formstable .= '<td>' . $formdetails['value'] . '</td>';
                    $formstable .= '<td>' . date('m-d-Y', strtotime($formdetails['closing_date'])) . '</td>';
                    $formstable .= '</tr>';
                }
                $formstable .= '</tbody>';
                $formstable .= '</table></div>';
            } else {
                $formstable = 'No records available';
            }

            $data['total'] = $total;
            $data['table'] = $formstable;

            return view('auth.leads.show')->with("data", $data);
        } else {
            return redirect('/leads');
        }
    }

    public function getLeadDetails($id)
    {

        $leads = Tbl_leads::with('Tbl_leadsource')
            ->with('Tbl_industrytypes')
            ->with('Tbl_leadstatus')
            ->with('Tbl_Accounts')
            ->with('Tbl_countries')
            ->with('Tbl_states')
            ->with('Tbl_salutations')
            ->with('Tbl_products')
            ->find($id);

        // echo json_encode($leads);
        // exit();

        return $leads;
    }

    public function getLeadProduct(Request $request)
    {
        $lead = $request->input('lead');
        $data = $this->getLeadDetails($lead);
        $product = ($data->tbl_products != '') ? $data->tbl_products->pro_id : 0;
        return $product;
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
        $lead  = Tbl_leads::find($id);

        if ($user->can('view', $lead)) {
            $uid = Auth::user()->id;
            $leads = Tbl_leads::with('Tbl_leadsource')
                ->with('Tbl_industrytypes')
                ->with('Tbl_leadstatus')
                ->with('Tbl_Accounts')
                ->with('Tbl_countries')
                ->with('Tbl_states')
                ->with('Tbl_salutations')
                ->with('Tbl_products')
                ->find($id);
            $leadarr = $leads->toArray();

            // echo json_encode($leads);
            // exit();

            $data['leadarr'] = $leadarr;

            //-----------------------------------------------------------------

            $country = Tbl_countries::all();
            $countryoptions = "<option value='0'>Select Country</option>";
            if (count($country) > 0) {
                foreach ($country as $cnt) {
                    $selected = ($cnt->id == $leadarr['country']) ? 'selected' : '';
                    $countryoptions .= "<option value='" . $cnt->id . "' " . $selected . ">" . $cnt->name . ' - ' . $cnt->sortname . "</option>";
                }
            }
            $data['countryoptions'] = $countryoptions;

            $stateoptions = "<option value='0'>Select State</option>";
            if ($leadarr['country'] > 0) {
                $states = DB::select('SELECT * FROM `tbl_states` WHERE `country_id`=' . $leadarr['country']);
                if (count($states) > 0) {
                    foreach ($states as $state) {
                        $stateselected = ($state->id == $leadarr['state']) ? 'selected' : '';
                        $stateoptions .= "<option value='" . $state->id . "' " . $stateselected . ">" . $state->name . "</option>";
                    }
                }
            }
            $data['stateoptions'] = $stateoptions;

            $industry = Tbl_industrytypes::all();
            $industryoptions = "<option value='0'>Select Industry Type</option>";
            if (count($industry) > 0) {
                foreach ($industry as $int) {
                    $industryselected = ($int->intype_id == $leadarr['intype_id']) ? 'selected' : '';
                    $industryoptions .= "<option value='" . $int->intype_id . "' " . $industryselected . ">" . $int->type . "</option>";
                }
            }
            $data['industryoptions'] = $industryoptions;

            $leadsource = Tbl_leadsource::all();
            $leadsourceoptions = "<option value='0'>Select Lead Source</option>";
            if (count($leadsource) > 0) {
                foreach ($leadsource as $source) {
                    $leadsourceselected = ($source->ldsrc_id == $leadarr['ldsrc_id']) ? 'selected' : '';
                    $leadsourceoptions .= "<option value='" . $source->ldsrc_id . "' " . $leadsourceselected . ">" . $source->leadsource . "</option>";
                }
            }
            $data['leadsourceoptions'] = $leadsourceoptions;

            $leadstatus = Tbl_leadstatus::all();
            $leadstatusoptions = "<option value='0'>Select Lead Status</option>";
            if (count($leadstatus) > 0) {
                foreach ($leadstatus as $ldstatus) {
                    $leadstatusselected = ($ldstatus->ldstatus_id == $leadarr['ldstatus_id']) ? 'selected' : '';
                    $leadstatusoptions .= "<option value='" . $ldstatus->ldstatus_id . "' " . $leadstatusselected . ">" . $ldstatus->status . "</option>";
                }
            }
            $data['leadstatusoptions'] = $leadstatusoptions;

            $accounts = Tbl_Accounts::where('active', 1)->where('uid', $uid)->get();
            $accountoptions = "<option value='0'>Select Account</option>";
            if (count($accounts) > 0) {
                foreach ($accounts as $account) {
                    $accountselected = ($account->acc_id == $leadarr['acc_id']) ? 'selected' : '';
                    $accountoptions .= "<option value='" . $account->acc_id . "' " . $accountselected . ">" . $account->name . "</option>";
                }
            }
            $accountoptions .= "<option disabled>---</option>";
            $accountoptions .= "<option value='NewAccount'>Add Account</option>";
            $data['accountoptions'] = $accountoptions;
            //-----------------------------------------------------------------

            $salutations = Tbl_salutations::all();
            $salutationoptions = "<option value='0'>None</option>";
            if (count($salutations) > 0) {
                foreach ($salutations as $salutation) {
                    $salutationselected = ($salutation->sal_id == $leadarr['sal_id']) ? 'selected' : '';
                    $salutationoptions .= "<option value='" . $salutation->sal_id . "' " . $salutationselected . ">" . $salutation->salutation . "</option>";
                }
            }
            $data['salutationoptions'] = $salutationoptions;


            // echo json_encode($leadarr);
            // exit();
            //  Products
            $products = Tbl_products::where('uid', $uid)->where('active', 1)->get();
            $productoptions = '<option value="">Select Product...</option>';
            foreach ($products as $product) {
                $productselected = (($leadarr['pro_id'] > 0) && ($product->pro_id == $leadarr['pro_id'])) ? 'selected' : '';
                $productoptions .= '<option value="' . $product->pro_id . '" ' . $productselected . '>' . $product->name . '</option>';
            }
            $data['productoptions'] = $productoptions;

            // echo json_encode($leadarr);
            // exit();

            return view('auth.leads.edit')->with("data", $data);
        } else {
            return redirect('/leads');
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
        // echo json_encode($request->input());
        // exit();
        //        $formdata = array();

        $user = Auth::user();
        $lead  = Tbl_leads::find($id);

        if ($user->can('update', $lead)) {

            $this->validate($request, [
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'email' => 'required|email|max:255',    //,email,' . $id . ',ld_id |unique:tbl_leads
            ]);

            $formdata = $request->input();

            $acc_id = 0;
            if ($request->input('account') == 'NewAccount') {
                $arr_account = array(
                    'uid' => Auth::user()->id,
                    'name' => $request->input('addAccount'),
                );
                $accounts = Tbl_Accounts::create($arr_account);
                $acc_id = $accounts->acc_id;
            } else {
                $acc_id = $request->input('account');
            }
            $formdata['account'] = $acc_id;


            $filename = '';
            if ($request->hasfile('userpicture')) {


                //-------------Image Validation----------------------------------
                $file = $request->file('userpicture');
                // Build the input for validation
                $fileArray = array('userpicture' => $file);

                // Tell the validator that this file should be an image
                $rules = array(
                    'userpicture' => 'mimes:jpeg,jpg,png,gif|max:10000' // max 10000kb
                );

                // Now pass the input and rules into the validator
                $validator = Validator::make($fileArray, $rules);

                // Check to see if validation fails or passes
                if ($validator->fails()) {
                    return redirect('/leads/' . $id . '/edit')->with('error', 'Please upload jpg, png and giff images only.');
                }
                //-------------Image Validation----------------------------------
                //            $file = $request->file('userpicture');
                $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
                $file->move('uploads/leads/', $name);   //public_path()
                $filename = '/uploads/leads/' . $name;
                //            $leads->picture = $filename;
            }
            $formdata['picture'] = $filename;

            $uid = Auth::user()->id;
            $leadname = $request->input('first_name') . ' ' . $request->input('last_name') . ' ' . date('m-d-Y-h-i-s');
            $ld_status = $request->input('leadstatus');

            // Get Lead Details
            $leads = $this->getLeadDetails($id);
            $createDeal = 0;

            // echo json_encode($leads);
            // exit();

            $leadStatus = Tbl_leadstatus::find($ld_status);

            if (($leads->tbl_leadstatus != '') && ((int) $ld_status != (int) $leads->tbl_leadstatus->ldstatus_id)) {
                $createDeal = $leadStatus->deal;
            }
            if (($leads->tbl_leadstatus == '') && ((int) $ld_status > 0)) {
                $createDeal = $leadStatus->deal;
            }

            // echo $createDeal;
            // exit();

            $res = $this->updateLead($formdata, $id);

            if ($res) {

                if ($createDeal == 1) {

                    $closingDate = date('Y-m-d', strtotime(' + 30 days'));
                    $notes = 'deal created on ' . date('m-d-Y-h-i-s');
                    $deal_status = 0;
                    $probability = 0;
                    $product = 0;

                    $dealObj = new DealController();

                    $dealdata = array(
                        'uid' => $uid,
                        'ld_id' => $id,
                        'sfun_id' => 0,
                        'ldsrc_id' => 0,
                        'name' => $leadname,
                        'value' => 10,
                        'closing_date' => $closingDate,
                        'notes' => $notes,
                        'deal_status' => $deal_status,
                        'probability' => $probability,
                        'pro_id' => $product,
                        'dl_id' => 0,
                    );

                    $dealRes = $dealObj->addDeal($dealdata);
                }
                return redirect('/leads')->with('success', 'Lead Updated Successfully...!');
            } else {
                return redirect('/leads')->with('error', 'Error occurred. Please try again later...! ');
            }
        } else {
            return redirect('/leads');
        }
    }

    public function updateLead($formdata, $id)
    {
        // echo $formdata['product'];
        // exit();

        $leads = Tbl_leads::find($id);
        $leads->first_name = (isset($formdata['first_name'])) ? $formdata['first_name'] : $leads->mobile;
        $leads->last_name = (isset($formdata['last_name'])) ? $formdata['last_name'] : $leads->last_name;
        $leads->email = ($formdata['email'] != '') ? $formdata['email'] : $leads->email;
        $leads->picture = ($formdata['picture'] != '') ? $formdata['picture'] : $leads->picture;
        $leads->mobile = (isset($formdata['mobile'])) ? $formdata['mobile'] : $leads->mobile;
        $leads->phone = (isset($formdata['phone'])) ? $formdata['phone'] : $leads->phone;
        $leads->ldsrc_id = (isset($formdata['leadsource'])) ? $formdata['leadsource'] : $leads->ldsrc_id;
        $leads->ldstatus_id = (isset($formdata['leadstatus'])) ? $formdata['leadstatus'] : $leads->ldstatus_id;
        $leads->intype_id = (isset($formdata['industrytype'])) ? $formdata['industrytype'] : $leads->intype_id;
        $leads->acc_id = (isset($formdata['account'])) ? $formdata['account'] : $leads->acc_id;
        $leads->notes = (isset($formdata['notes'])) ? $formdata['notes'] : $leads->notes;
        $leads->website = (isset($formdata['website'])) ? $formdata['website'] : $leads->website;
        $leads->country = (isset($formdata['country'])) ? $formdata['country'] : $leads->country;
        $leads->state = (isset($formdata['state'])) ? $formdata['state'] : $leads->state;
        $leads->city = (isset($formdata['city'])) ? $formdata['city'] : $leads->city;
        $leads->street = (isset($formdata['street'])) ? $formdata['street'] : $leads->street;
        $leads->zip = (isset($formdata['zip'])) ? $formdata['zip'] : $leads->zip;
        $leads->company = (isset($formdata['company'])) ? $formdata['company'] : $leads->company;
        $leads->sal_id = (isset($formdata['salutation'])) ? $formdata['salutation'] : $leads->sal_id;
        $leads->designation = (isset($formdata['designation'])) ? $formdata['designation'] : $leads->designation;
        $leads->pro_id = ($formdata['product'] > 0) ? $formdata['product'] : $leads->pro_id;
        return $leads->save();
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

    public function import($type)
    {
        return view('auth.leads.import');
    }

    public function getLeads($uid, $start, $end, $status)    //$day
    {
        $user = User::find($uid);

        $cr_id = $user->cr_id;

        $currency = currency::find($cr_id);

        $leadstatuslist = Tbl_leadstatus::all();

        //-----------------------------------------
        $query = DB::table('tbl_leads')->where('tbl_leads.uid', $uid)->where('tbl_leads.active', 1)->where('tbl_leads.leadtype', 1);
        if ($status > 0) {
            $query->where('tbl_leads.ldstatus_id', $status);
        }
        if (($start != '') && ($end != '')) {
            $query->whereBetween(DB::raw('DATE(tbl_leads.created_at)'), [$start, $end]);
        }
        $query->leftJoin('tbl_leadstatus', 'tbl_leads.ldstatus_id', '=', 'tbl_leadstatus.ldstatus_id');
        $query->leftJoin('tbl_leadsource', 'tbl_leads.ldsrc_id', '=', 'tbl_leadsource.ldsrc_id');
        $query->leftJoin('tbl_accounts', 'tbl_leads.acc_id', '=', 'tbl_accounts.acc_id');
        $query->leftJoin('tbl_products', 'tbl_leads.pro_id', '=', 'tbl_products.pro_id');
        $query->orderBy('tbl_leads.ld_id', 'desc');
        $query->select(
            'tbl_leads.*',
            'tbl_leadstatus.status as leadstatus',
            'tbl_leadsource.leadsource as leadsource',
            'tbl_accounts.name as account',
            'tbl_products.name as product'
        );
        $leads = $query->get();

        // echo json_encode($leads);
        // exit();

        $total = count($leads);
        if ($total > 0) {
            $formstable = '<div class="table-responsive"><table id="leadsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th></th>';
            $formstable .= '<th width="230">Lead Name</th>';
            $formstable .= '<th>Deals</th>';
            // $formstable .= '<th>Email</th>';
            // $formstable .= '<th>Mobile</th>';
            $formstable .= '<th>Designation</th>';
            $formstable .= '<th>Lead Status</th>';
            $formstable .= '<th>Lead Source</th>';
            $formstable .= '<th>Account</th>';
            $formstable .= '<th>Company</th>';
            $formstable .= '<th>Product</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Add Deal</th>';
            $formstable .= '<th>Add as Customer</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '<th class="none">Notes</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($leads as $formdetails) {
                $customer_status = '';
                $customer = Tbl_deals::where('ld_id', $formdetails->ld_id)->where('deal_status', 1)->first();
                if ($customer != null) {
                    $customer_status = '<span class="badge badge-success"><i class="fa fa-check" aria-hidden="true"></i> Customer</span>';
                }

                $dealstages = Tbl_deals::where('ld_id', $formdetails->ld_id)->where('deal_status', 0)->where('active', 1)->sum('value'); //->whereIn('sfun_id', [1, 2, 3, 4])
                //                $leadstatus = ($formdetails->tbl_leadstatus != null) ? $formdetails->tbl_leadstatus->status : '';
                // $leadstatus = 'Add Lead Status';
                // $leadstatus_id = 0;

                // if ($formdetails->tbl_leadstatus != null) {
                //     $leadstatus_id = $formdetails->tbl_leadstatus->ldstatus_id;
                //     $leadstatus = $formdetails->tbl_leadstatus->status;
                // }

                // $leadsource = ($formdetails->tbl_leadsource != null) ? $formdetails->tbl_leadsource->leadsource : '';

                $leadimage = ($formdetails->picture != '') ? $formdetails->picture : '/uploads/default/leads.png';

                $formstable .= '<tr>';
                $formstable .= '<td><div class="custom-control custom-checkbox"><input type="checkbox" class="checkAll custom-control-input" id="' . $formdetails->ld_id . '"><label class="custom-control-label" for="' . $formdetails->ld_id . '"></label></div></td>';
                $formstable .= '<td class="table-title">';
                $formstable .= '<img src="' . url($leadimage) . '" class="avatar"> ';
                $formstable .= '<h6><a href="' . url('leads/' . $formdetails->ld_id) . '">' . substr($formdetails->first_name . ' ' . $formdetails->last_name, 0, 25) . '</a><h6>';
                $formstable .= '<h6><div class="t-email"><a class="text-muted" href="' . url('mails/mailsend/leads/' . $formdetails->ld_id) . '">' . $formdetails->email . '</a></h6></div><div class="t-mob text-muted">' . $formdetails->mobile . '</div>';
                $formstable .= $customer_status;
                $formstable .= '</td>';
                $formstable .= '<td>' . $currency->html_code . ' ' . $dealstages . '</td>';
                $formstable .= '<td>' . $formdetails->designation  . '</td>';
                // $formstable .= '<td>' . $formdetails->mobile . '</td>';
                // $formstable .= '<td>' . $formdetails->designation . '</td>';
                $formstable .= '<td>';

                //  ----------------------Select Tag--------------------------------
                $leadstatusSelecttagidentifier = "'" . "leadStatus" . $formdetails->ld_id . "'";
                $leadstatusSelecttag = '<select class="btn btn-sm btn-default dropdown-toggle" id="leadStatus' . $formdetails->ld_id . '" name="leadStatus' . $formdetails->ld_id . '" onchange="return changeLeadStatus(' . $leadstatusSelecttagidentifier . ',' . $formdetails->ld_id . '    );">';
                $leadstatusSelecttag .= '<option value="0">Select</option>';
                foreach ($leadstatuslist as $statuslist) {
                    $ldslected = ((int) $statuslist->ldstatus_id === (int) $formdetails->ldstatus_id) ? 'selected' : '';
                    $leadstatusSelecttag .= '<option value="' . $statuslist->ldstatus_id . '|' . $statuslist->deal . '" ' . $ldslected . '>' . $statuslist->status . '</option>';
                }


                $formstable .= $leadstatusSelecttag;
                $formstable .= '</td>';
                $formstable .= '<td>' . $formdetails->leadsource  . '</td>'; //$leadsource
                $formstable .= '<td>' . $formdetails->account  . '</td>';    //(($formdetails->tbl_accounts != '') ? $formdetails->tbl_accounts->name : '')
                $formstable .= '<td>' . $formdetails->company . '</td>';
                $formstable .= '<td>' . $formdetails->product . '</td>';    //(($formdetails->tbl_products != '') ? $formdetails->tbl_products->name : '')
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '<td><a class="badge badge-success" href="' . url('leads/adddeal/' . $formdetails->ld_id) . '">Add Deal</a></td>';
                $formstable .= '<td><a class="badge badge-success" href="' . url('leads/addcustomer/' . $formdetails->ld_id) . '">Add as Customer</a></td>';
                $formstable .= '<td>';
                $formstable .= '
                    <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <a class="dropdown-item text-default text-btn-space" href="' . url('leads/' . $formdetails->ld_id . '/edit') . '">Edit</a>
                    <a class="dropdown-item text-default text-btn-space" href="' . url('leads/delete/' . $formdetails->ld_id) . '">Delete</a>
                    <a class="dropdown-item text-default text-btn-space" href="' . url('leads/assign/' . $formdetails->ld_id) . '">Assign to Subuser</a>
                  </ul>
                </div>';
                $formstable .= '</td>';
                $formstable .= '<td>' . $formdetails->notes . '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table></div>';
        } else {
            $formstable = 'No records available';
        }


        $data['total'] = $total;
        $data['table'] = $formstable;

        return $data;

        // if ($status == 'All') {

        //     $leads = Tbl_leads::where('uid', $uid)
        //         ->where('active', 1)
        //         ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
        //         ->with('tbl_leadsource')
        //         ->with('tbl_leadstatus')
        //         ->with('tbl_products')
        //         ->with('tbl_accounts')
        //         ->orderBy('ld_id', 'desc')
        //         ->get();
        // }
        // if (($status > 0) && ($status != 'All')) {
        //     $leads = Tbl_leads::where('uid', $uid)
        //         ->where('ldstatus_id', $status)
        //         ->where('active', 1)
        //         ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
        //         ->with('tbl_leadsource')
        //         ->with('tbl_leadstatus')
        //         ->with('tbl_products')
        //         ->with('tbl_accounts')
        //         ->orderBy('ld_id', 'desc')
        //         ->get();
        // }


        // $leads = Tbl_leads::where('uid', $uid)
        //     ->where('active', 1)
        //     ->where(DB::raw('DATE(created_at)'), $day)
        //     ->with('tbl_leadsource')
        //     ->with('tbl_leadstatus')
        //     ->with('tbl_products')
        //     ->orderBy('ld_id', 'desc')
        //     ->get();

        // return $leads;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

            //            echo $file->getClientOriginalExtension();
            //            exit(0);
            // Tell the validator that this file should be an image
            $rules = array(
                'importFile' => 'required', // max 10000kb
                'extension' => 'required|in:csv'
            );

            // Now pass the input and rules into the validator
            $validator = Validator::make($fileArray, $rules);

            // Check to see if validation fails or passes
            if ($validator->fails()) {
                return redirect('/leads/import/csv')->with('error', 'Please upload .csv only.');
            }
            //-------------Image Validation----------------------------------

            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $path = $request->file('importFile')->getRealPath();
            // $data = Excel::load($path)->get();

            $res = Excel::import(new LeadsImport($uid), request()->file('importFile'));

            if ($res) {
                // echo 'Success';
                return redirect('/leads')->with('success', 'Uploaded successfully');
            } else {
                // echo 'Failed';
                return redirect('/leads/import/csv')->with('error', "Error ocurred. Try again later..!");
            }
        } else {
            return redirect('/leads/import/csv')->with('error', 'Please upload file.');
        }
    }

    public function exportData($type)
    {
        $uid = Auth::user()->id;
        return Excel::download(new LeadsExport($uid), 'leads.xlsx');
    }

    public function deleteLead($id)
    {
        $user = Auth::user();
        $lead  = Tbl_leads::find($id);

        if ($user->can('update', $lead)) {
            $this->delete($id);

            //----------Deals-------------------
            Tbl_deals::where('ld_id', '=', $id)->update(['active' => 0]);

            //----------Invoice-------------------
            Tbl_invoice::where('ld_id', '=', $id)->update(['active' => 0]);

            //----------Contacts-------------------
            //        Tbl_contacts::where('ld_id', '=', $id)->update(['ld_id' => 0]);

            return redirect('/leads')->with('success', 'Deleted Successfully...');
        } else {
            return redirect('/leads');
        }
    }

    public function delete($id)
    {
        $account = Tbl_leads::find($id);
        $account->active = 0;
        return $account->save();
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->input('id');
        return Tbl_leads::whereIn('ld_id', $ids)->update(array('active' => 0));
    }

    public function restoreAll(Request $request)
    {
        $ids = $request->input('id');
        return Tbl_leads::whereIn('ld_id', $ids)->update(array('active' => 1));
    }

    public function changeLeadStatus(Request $request)  //$id, $status, $deal
    {
        $uid = Auth::user()->id;
        // echo json_encode($request->input());
        $id = $request->input('id');
        $status = $request->input('status');
        $deal = $request->input('deal');


        $leads = Tbl_leads::find($id);
        $leads->ldstatus_id = $status;
        $res = $leads->save();

        if ($res) {

            $leadname = $leads->first_name . ' ' . $leads->last_name . ' ' . date('m-d-Y-h-i-s');
            $closingDate = date('Y-m-d', strtotime(' + 30 days'));
            $notes = 'deal created on ' . date('m-d-Y-h-i-s');
            $deal_status = 0;
            $probability = 0;
            $product = 0;

            if ((int) $deal == 1) {

                $dealObj = new DealController();

                $formdata = array(
                    'uid' => $uid,
                    'ld_id' => $id,
                    'sfun_id' => 0,
                    'ldsrc_id' => 0,
                    'name' => $leadname,
                    'value' => 10,
                    'closing_date' => $closingDate,
                    'notes' => $notes,
                    'deal_status' => $deal_status,
                    'probability' => $probability,
                    'pro_id' => $product,
                    'dl_id' => 0,
                );

                $dealRes = $dealObj->addDeal($formdata);
            }

            return 1;
        } else {
            return 0;
        }
    }

    public function assignUser($id)
    {

        $lead = Tbl_leads::find($id);
        $data['lead'] = $lead;

        $uid = Auth::user()->id;

        $users = User::where('user', $uid)->orderBy('id', 'desc')->get();
        $useroptions = "<option value=''>Select User</option>";
        if (count($users) > 0) {
            foreach ($users as $user) {
                $useroptions .= "<option value='" . $user->id . "'>" . $user->name . "</option>";
            }
        }
        $data['useroptions'] = $useroptions;
        return view('auth.leads.assign')->with("data", $data);
    }

    public function assigntoUser(Request $request)
    {
        //        echo json_encode($request->input());

        $uid = Auth::user()->id;

        $ld_id = $request->input('ld_id');
        $user = $request->input('subusers');

        $lead = Tbl_leads::find($ld_id);
        $lead->uid = $user;
        $lead->assigned_by = $uid;
        $lead->save();

        return redirect('/leads')->with('success', 'Lead Assigned Successfully...!');
    }

    public function leadStatusfilter(Request $request)
    {

        // echo json_encode($request->input());
        // exit();

        $status = $request->input('id');
        $start = ($request->input('start') != '') ? date('Y-m-d', strtotime($request->input('start'))) : '';
        $end = ($request->input('end') != '') ? date('Y-m-d', strtotime($request->input('end'))) : '';

        $oldDate = $this->getOldDate();
        $latestDate = $this->getLatestDate();

        $timer = $this->getFilterTime($start, $end);

        if (($start ==  $oldDate) && ($end == $latestDate)) {
            $start = '';
            $end = '';
        }

        //  User details
        $uid = Auth::user()->id;
        $user = User::find($uid);

        //  Currency
        $cr_id = $user->cr_id;
        $currency = currency::find($cr_id);

        //  Lead Status
        $leadstatuslist = Tbl_leadstatus::all();

        $leads = $this->getLeads($uid, $start, $end, $status);
        $leads['timer'] = $timer;

        $leadStatusVal = 'All';
        if ($status > 0) {
            $lstatus = Tbl_leadstatus::find($status);
            $leadStatusVal = $lstatus->status;
        }
        $leads['leadStatusVal'] = $leadStatusVal;
        return json_encode($leads);
    }

    public function proleadStatusfilter(Request $request)
    {

        // echo json_encode($request->input());
        // exit();

        $status = $request->input('id');
        $start = ($request->input('start') != '') ? date('Y-m-d', strtotime($request->input('start'))) : '';
        $end = ($request->input('end') != '') ? date('Y-m-d', strtotime($request->input('end'))) : '';

        $oldDate = $this->getOldDate();
        $latestDate = $this->getLatestDate();

        $timer = $this->getFilterTime($start, $end);

        if (($start ==  $oldDate) && ($end == $latestDate)) {
            $start = '';
            $end = '';
        }

        //  User details
        $uid = Auth::user()->id;
        $user = User::find($uid);

        //  Currency
        $cr_id = $user->cr_id;
        $currency = currency::find($cr_id);

        //  Lead Status
        $leadstatuslist = Tbl_products::where('uid', $uid)->where('active', 1)->get();

        $leads = $this->getProductLeadsList($uid, $start, $end, $status);
        $leads['timer'] = $timer;

        $leadStatusVal = 'All';
        if ($status > 0) {
            $lstatus = Tbl_products::find($status);
            $leadStatusVal = $lstatus->name;
        }
        $leads['leadStatusVal'] = $leadStatusVal;
        return json_encode($leads);
    }

    public function storeEvent(Request $request)
    {
        //        echo json_encode($request->input());
        //        exit();

        $uid = Auth::user()->id;

        $this->validate($request, [
            'title' => 'required|max:255',
            'startDate' => 'required',
            'endDate' => 'required',
            'startTime' => 'required',
            'endTime' => 'required',
        ]);

        $ldId = $request->input('leadId');
        $ldStatus = $request->input('leadStatus');

        $dateStart = $request->input('startDate');
        $dateEnd = $request->input('endDate');

        $timeStart = $request->input('startTime');
        $timeEnd = $request->input('endTime');

        $startDatetime = date('Y-m-d H:i:s', strtotime($dateStart . ' ' . $timeStart));
        $endDatetime = date('Y-m-d H:i:s', strtotime($dateEnd . ' ' . $timeEnd));
        //        echo $startDatetime . "<br>";
        //        echo $endDatetime . "<br>";
        //        echo "<br>";
        //        if (strtotime($startDatetime) <a strtotime($endDatetime)) {
        $formData = array(
            'uid' => $uid,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'start_date' => $request->input('startDate'),
            'end_date' => $request->input('endDate'),
            'start_time' => $request->input('startTime'),
            'end_time' => $request->input('endTime'),
            'startDatetime' => $startDatetime,
            'endDatetime' => $endDatetime,
            'type' => 3,
            'id' => $ldId,
        );

        $event = Tbl_events::create($formData);
        $ev_id = $event->ev_id;
        if ($ev_id > 0) {

            //  Changing Lead Status of Lead
            $leads = Tbl_leads::find($ldId);
            $leads->ldstatus_id = $ldStatus;
            $leads->save();
            //End of Changing Lead Status of Lead

            return redirect('/leads')->with('success', 'Event created successfully');
        } else {
            return redirect('/leads')->with('error', 'Error occurred. Try again later.');
        }
        //        } else {
        //            return redirect('/leads')->with('error', 'Error occurred. Try again later');
        //        }
    }

    public function getOldDate()
    {
        $uid = Auth::user()->id;
        $oldDate = Tbl_leads::where('uid', $uid)->orderBy('created_at', 'asc')->first();
        // echo json_encode($oldDate);
        $upDate = ($oldDate != '') ? date('Y-m-d', strtotime($oldDate->created_at)) : date('Y-m-d');
        return $upDate;
    }

    public function getLatestDate()
    {
        $uid = Auth::user()->id;
        $oldDate = Tbl_leads::where('uid', $uid)->orderBy('created_at', 'desc')->first();
        // echo json_encode($oldDate);
        $upDate = ($oldDate != '') ? date('Y-m-d', strtotime($oldDate->created_at)) : date('Y-m-d');
        return $upDate;
    }

    public function getFilterTime($start, $end)
    {
        $oldDate = $this->getOldDate();
        $latestDate = $this->getLatestDate();

        $today = date('Y-m-d');

        $yesterday = date('Y-m-d', strtotime("-1 days"));

        $sevendays = date('Y-m-d', strtotime("-6 days"));

        $thirtydays = date('Y-m-d', strtotime("-29 days"));

        $current_month_form = date('Y-m-01', strtotime(date('Y-m-d')));

        $current_month_to =  date('Y-m-t', strtotime(date('Y-m-d')));

        $last_month_form = date('Y-m-01', strtotime('-1 MONTH'));

        $last_month_to =  date('Y-m-t', strtotime(date('Y-m-01') . ' -1 MONTH'));

        $timer = "";

        if (($start ==  $oldDate) && ($end == $latestDate)) {
            $timer = "All";
        } else if (($start ==  $yesterday) && ($end == $yesterday)) {
            $timer = "Yesterday";
        } else if ($start ==  $end) {
            $timer = "Today";
        } else if (($start ==  $sevendays) && ($end == $today)) {
            $timer = "Last 7 Days";
        } else if (($start ==  $thirtydays) && ($end == $today)) {
            $timer = "Last 30 Days";
        } else if (($start ==  $current_month_form) && ($end == $current_month_to)) {
            $timer = "This Month";
        } else if (($start ==  $last_month_form) && ($end == $last_month_to)) {
            $timer = "Last Month";
        } else {
            $start = date('m-d-Y', strtotime($start));
            $end = date('m-d-Y', strtotime($end));

            $timer = "From:" . $start . ' To:' . $end;
        }

        return $timer;
    }


    public function getProductLeads()
    {
        $user = Auth::user()->id;
        $proId = 'All';
        $day = '';
        $weekDay = '';

        // echo $user;
        // exit();
        $orders = $this->getProductLeadsList($user, $weekDay, $day, $proId);

        $orders['oldDate'] = $this->getProLeadsOldDate();
        $orders['latestDate'] = $this->getProLeadsLatestDate();
        $orders['timer'] = 'All';
        $orders['productOptions'] = $this->productOptions();
        $orders['leadStatusVal'] = 'All';


        return view('auth.leads.proleads')->with('data', $orders);
    }

    public function getProductLeadsList($uid, $start, $end, $proId)
    {

        $user = User::find($uid);

        $cr_id = $user->cr_id;

        $currency = currency::find($cr_id);

        $leadstatuslist = Tbl_leadstatus::all();

        //-----------------------------------------
        // $query = DB::table('tbl_leads')->where('tbl_leads.uid', $uid)->where('tbl_leads.active', 1)->where('tbl_leads.leadtype', 2);
        // $query->leftJoin('tbl_leadstatus', 'tbl_leads.ldstatus_id', '=', 'tbl_leadstatus.ldstatus_id');
        // $query->leftJoin('tbl_leadsource', 'tbl_leads.ldsrc_id', '=', 'tbl_leadsource.ldsrc_id');
        // // $query->leftJoin('tbl_accounts', 'tbl_leads.acc_id', '=', 'tbl_accounts.acc_id');
        // $query->leftJoin('tbl_products', 'tbl_leads.pro_id', '=', 'tbl_products.pro_id');
        // $query->orderBy('tbl_leads.ld_id', 'desc');
        // $query->select(
        //     'tbl_leads.*',
        //     'tbl_leadstatus.status as leadstatus',
        //     'tbl_leadsource.leadsource as leadsource',
        //     'tbl_products.name as product'
        // );

        // 'tbl_leadstatus.status as leadstatus',
        // 'tbl_leadsource.leadsource as leadsource',
        // 'tbl_accounts.name as account',
        // $leads = $query->get();

        //-----------------------------------------
        $query = Tbl_leads::where('tbl_leads.uid', $uid)->where('tbl_leads.active', 1)->where('tbl_leads.leadtype', 2);
        if (($proId > 0) && ($proId != 'All')) {
            $query->where('tbl_leads.pro_id', $proId);
        }
        if (($start != '') && ($end != '')) {
            $query->whereBetween(DB::raw('DATE(tbl_leads.created_at)'), [$start, $end]);
        }
        $query->with('Tbl_leadstatus');
        $query->with('Tbl_leadsource');
        $query->with('Tbl_products');
        // $query->with('Tbl_products.companies');
        $query->orderByDesc('ld_id');
        $leads = $query->get();

        // echo json_encode($leads);
        // exit();

        $total = count($leads);
        if ($total > 0) {
            $formstable = '<div class="table-responsive"><table id="leadsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th></th>';
            $formstable .= '<th width="230">Lead Name</th>';
            // $formstable .= '<th>Deals</th>';
            $formstable .= '<th>Email</th>';
            $formstable .= '<th>Mobile</th>';
            // $formstable .= '<th>Designation</th>';
            $formstable .= '<th>Lead Status</th>';
            $formstable .= '<th>Lead Source</th>';
            // $formstable .= '<th>Account</th>';
            $formstable .= '<th>Company</th>';
            $formstable .= '<th width="230">Product</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Add Deal</th>';
            $formstable .= '<th>Add as Customer</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '<th class="none">Notes</th>';    //
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($leads as $formdetails) {
                $customer_status = '';
                $customer = Tbl_deals::where('ld_id', $formdetails->ld_id)->where('deal_status', 1)->first();
                if ($customer != null) {
                    $customer_status = '<span class="badge badge-success"><i class="fa fa-check" aria-hidden="true"></i> Customer</span>';
                }

                $dealstages = Tbl_deals::where('ld_id', $formdetails->ld_id)->where('deal_status', 0)->where('active', 1)->sum('value'); //->whereIn('sfun_id', [1, 2, 3, 4])

                $leadimage = $formdetails->profile_img;

                $product = ($formdetails->tbl_products != '') ? $formdetails->tbl_products->name : '';
                // $company = ($formdetails->tbl_products->companies != '') ? $formdetails->tbl_products->companies->c_name : '';
                $leadsource = ($formdetails->tbl_leadsource != '') ? $formdetails->tbl_leadsource->leadsource : '';

                $c_name = '';
                if ($formdetails->tbl_products->company != '') {
                    $company = Company::find($formdetails->tbl_products->company);
                    $c_name = $company->c_name;
                }

                $formstable .= '<tr>';
                $formstable .= '<td><div class="custom-control custom-checkbox"><input type="checkbox" class="checkAll custom-control-input" id="' . $formdetails->ld_id . '"><label class="custom-control-label" for="' . $formdetails->ld_id . '"></label></div></td>';
                $formstable .= '<td class="table-title">';
                $formstable .= '<img src="' . $leadimage . '" class="avatar"> ';
                $formstable .= '<h6><a href="' . url('leads/product/' . $formdetails->ld_id) . '">' . substr($formdetails->first_name . ' ' . $formdetails->last_name, 0, 25) . '</a><h6>';
                // $formstable .= '<h6><div class="t-email"><a class="text-muted" href="' . url('mails/mailsend/leads/' . $formdetails->ld_id) . '">' . $formdetails->email . '</a></h6></div><div class="t-mob text-muted">' . $formdetails->mobile . '</div>';
                $formstable .= $customer_status;
                $formstable .= '</td>';
                // $formstable .= '<td>' . $currency->html_code . ' ' . $dealstages . '</td>';
                // $formstable .= '<td>' . $formdetails->designation  . '</td>';
                $formstable .= '<td><h6><div class="t-email"><a class="text-muted" target="_blank" href="' . url('mails/mailsend/leads/' . $formdetails->ld_id) . '">' . $formdetails->email . '</a></div></h6></td>';
                $formstable .= '<td>' . $formdetails->mobile . '</td>';
                // $formstable .= '<td>' . $formdetails->designation . '</td>';
                $formstable .= '<td>';

                //  ----------------------Select Tag--------------------------------
                $leadstatusSelecttagidentifier = "'" . "leadStatus" . $formdetails->ld_id . "'";
                $leadstatusSelecttag = '<select class="btn btn-sm btn-default dropdown-toggle" id="leadStatus' . $formdetails->ld_id . '" name="leadStatus' . $formdetails->ld_id . '" onchange="return changeLeadStatus(' . $leadstatusSelecttagidentifier . ',' . $formdetails->ld_id . '    );">';
                $leadstatusSelecttag .= '<option value="0">Select</option>';
                foreach ($leadstatuslist as $statuslist) {
                    $ldslected = ((int) $statuslist->ldstatus_id === (int) $formdetails->ldstatus_id) ? 'selected' : '';
                    $leadstatusSelecttag .= '<option value="' . $statuslist->ldstatus_id . '|' . $statuslist->deal . '" ' . $ldslected . '>' . $statuslist->status . '</option>';
                }

                $formstable .= $leadstatusSelecttag;
                $formstable .= '</td>';
                $formstable .= '<td>' . $leadsource  . '</td>'; //$leadsource
                // $formstable .= '<td>' . $formdetails->account  . '</td>';    //(($formdetails->tbl_accounts != '') ? $formdetails->tbl_accounts->name : '')
                $formstable .= '<td>' . $c_name . '</td>';
                $formstable .= '<td>' . $product . '</td>';    //(($formdetails->tbl_products != '') ? $formdetails->tbl_products->name : '')
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '<td><a class="badge badge-success" href="' . url('leads/adddeal/' . $formdetails->ld_id) . '">Add Deal</a></td>';
                $formstable .= '<td><a class="badge badge-success" href="' . url('leads/addcustomer/' . $formdetails->ld_id) . '">Add as Customer</a></td>';
                $formstable .= '<td>';
                $formstable .= '
                    <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <a class="dropdown-item text-default text-btn-space" href="' . url('leads/product/edit/' . $formdetails->ld_id) . '">Edit</a>
                    <a class="dropdown-item text-default text-btn-space" href="' . url('leads/product/delete/' . $formdetails->ld_id) . '">Delete</a>
                  </ul>
                </div>';
                $formstable .= '</td>';
                $formstable .= '<td>' . $formdetails->notes . '</td>';
                $formstable .= '</tr>';
                // <a class="dropdown-item text-default text-btn-space" href="' . url('leads/assign/' . $formdetails->ld_id) . '">Assign to Subuser</a>
            }
            $formstable .= '</tbody>';
            $formstable .= '</table></div>';
        } else {
            $formstable = 'No records available';
        }


        $data['total'] = $total;
        $data['table'] = $formstable;

        return $data;
    }


    public function getCartOrders($user)
    {
        $query = DB::table('tbl_cart_orders');
        $query->where('tbl_cart_orders.active', 1);
        $query->leftJoin('tbl_products', 'tbl_cart_orders.pro_id', '=', 'tbl_products.pro_id');
        $query->leftJoin('tbl_post_order_stage', 'tbl_cart_orders.pos_id', '=', 'tbl_post_order_stage.pos_id');
        $query->leftJoin('tbl_countries', 'tbl_cart_orders.country', '=', 'tbl_countries.id');
        $query->leftJoin('tbl_states', 'tbl_cart_orders.state', '=', 'tbl_states.id');
        $query->where('tbl_products.uid', $user);
        $query->where('tbl_products.user_type', 2);
        $query->orderByDesc('tbl_cart_orders.coid');
        $query->select(
            'tbl_cart_orders.*',
            'tbl_products.procat_id',
            'tbl_products.pro_id',
            'tbl_products.uid as userid',
            'tbl_products.user_type',
            'tbl_products.name as pname',
            'tbl_products.vendor',
            'tbl_products.size',
            'tbl_products.price',
            'tbl_post_order_stage.stage as stage',
            'tbl_countries.name as countryname',
            'tbl_states.name as statename',
        );
        $orders = $query->get();

        // echo json_encode($orders);
        // exit();

        $total = count($orders);

        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th width="2"></th>';
            $formstable .= '<th>Lead</th>';
            $formstable .= '<th>Order Number</th>';
            $formstable .= '<th>Product</th>';
            $formstable .= '<th>Brand</th>';
            $formstable .= '<th>Quantity</th>';
            $formstable .= '<th>Total Amount</th>';
            $formstable .= '<th>Post Order Stage</th>';
            $formstable .= '<th>Shipping Date</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($orders as $formdetails) {


                $userid = $formdetails->userid;
                $user = User::with('currency')->find($userid);

                // echo json_encode($user);
                // exit();


                $formstable .= '<tr>';
                $formstable .= '<td><input type="checkbox" class="checkAll" id="' . $formdetails->coid . '"></td>';
                //  ' . url('leads/order/' . $formdetails->coid) . '
                $formstable .= '<td><a href="' . url('leads/product/' . $formdetails->coid) . '">' . $formdetails->name . '</a></td>';
                $formstable .= '<td>' . $formdetails->number . '</td>';
                $formstable .= '<td>' . $formdetails->pname . '</td>';
                $formstable .= '<td>' . $formdetails->vendor . '</td>';
                $formstable .= '<td>' . $formdetails->quantity . '</td>';
                $formstable .= '<td><span>' . $user->currency->html_code . '</span>&nbsp;' . $formdetails->total_amount . '</td>';
                $formstable .= '<td>' . $formdetails->stage . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->shipping_date)) . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item text-default text-btn-space" href="' . url('ecommerce/' . $formdetails->coid) . '/edit">Edit</a>
                    <a class="dropdown-item text-default text-btn-space" href="#">Delete</a>
                  </div>
                </div>';
                //  ' . url('admin/accounts/' . $formdetails->coid . '/edit') . '
                // ' . url('admin/accounts/delete/' . $formdetails->coid) . '
                $formstable .= '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }
        $data['table'] = $formstable;
        $data['total'] = $total;

        return $data;
    }


    public function showProductLead($id)
    {
        $user = Auth::user();
        $lead  = Tbl_leads::find($id);

        if ($user->can('view', $lead)) {

            $leads = $this->getLeadDetails($id);

            $leadarr = $leads->toArray();

            $data['leadarr'] = $leadarr;

            $pro_id = $leads->pro_id;
            $product = $this->getProductDetails($pro_id);
            $data['product'] = $product['product'];
            $data['user'] = $product['user'];

            return view('auth.leads.showprolead')->with('data', $data);
        } else {
            return redirect('leads/getproductleads/list');
        }
    }

    public function editProductLead($id)
    {
        $user = Auth::user();
        $lead  = Tbl_leads::find($id);

        if ($user->can('view', $lead)) {

            $uid = Auth::user()->id;
            $leads = Tbl_leads::with('Tbl_leadsource')
                ->with('Tbl_industrytypes')
                ->with('Tbl_leadstatus')
                ->with('Tbl_Accounts')
                ->with('Tbl_countries')
                ->with('Tbl_states')
                ->with('Tbl_salutations')
                ->with('Tbl_products')
                ->find($id);
            $leadarr = $leads->toArray();

            // echo json_encode($leads);
            // exit();

            $data['leadarr'] = $leadarr;

            //-----------------------------------------------------------------

            $country = Tbl_countries::all();
            $countryoptions = "<option value='0'>Select Country</option>";
            if (count($country) > 0) {
                foreach ($country as $cnt) {
                    $selected = ($cnt->id == $leadarr['country']) ? 'selected' : '';
                    $countryoptions .= "<option value='" . $cnt->id . "' " . $selected . ">" . $cnt->name . ' - ' . $cnt->sortname . "</option>";
                }
            }
            $data['countryoptions'] = $countryoptions;

            $stateoptions = "<option value='0'>Select State</option>";
            if ($leadarr['country'] > 0) {
                $states = DB::select('SELECT * FROM `tbl_states` WHERE `country_id`=' . $leadarr['country']);
                if (count($states) > 0) {
                    foreach ($states as $state) {
                        $stateselected = ($state->id == $leadarr['state']) ? 'selected' : '';
                        $stateoptions .= "<option value='" . $state->id . "' " . $stateselected . ">" . $state->name . "</option>";
                    }
                }
            }
            $data['stateoptions'] = $stateoptions;

            $industry = Tbl_industrytypes::all();
            $industryoptions = "<option value='0'>Select Industry Type</option>";
            if (count($industry) > 0) {
                foreach ($industry as $int) {
                    $industryselected = ($int->intype_id == $leadarr['intype_id']) ? 'selected' : '';
                    $industryoptions .= "<option value='" . $int->intype_id . "' " . $industryselected . ">" . $int->type . "</option>";
                }
            }
            $data['industryoptions'] = $industryoptions;

            $leadsource = Tbl_leadsource::all();
            $leadsourceoptions = "<option value='0'>Select Lead Source</option>";
            if (count($leadsource) > 0) {
                foreach ($leadsource as $source) {
                    $leadsourceselected = ($source->ldsrc_id == $leadarr['ldsrc_id']) ? 'selected' : '';
                    $leadsourceoptions .= "<option value='" . $source->ldsrc_id . "' " . $leadsourceselected . ">" . $source->leadsource . "</option>";
                }
            }
            $data['leadsourceoptions'] = $leadsourceoptions;

            $leadstatus = Tbl_leadstatus::all();
            $leadstatusoptions = "<option value='0'>Select Lead Status</option>";
            if (count($leadstatus) > 0) {
                foreach ($leadstatus as $ldstatus) {
                    $leadstatusselected = ($ldstatus->ldstatus_id == $leadarr['ldstatus_id']) ? 'selected' : '';
                    $leadstatusoptions .= "<option value='" . $ldstatus->ldstatus_id . "' " . $leadstatusselected . ">" . $ldstatus->status . "</option>";
                }
            }
            $data['leadstatusoptions'] = $leadstatusoptions;

            $accounts = Tbl_Accounts::where('active', 1)->where('uid', $uid)->get();
            $accountoptions = "<option value='0'>Select Account</option>";
            if (count($accounts) > 0) {
                foreach ($accounts as $account) {
                    $accountselected = ($account->acc_id == $leadarr['acc_id']) ? 'selected' : '';
                    $accountoptions .= "<option value='" . $account->acc_id . "' " . $accountselected . ">" . $account->name . "</option>";
                }
            }
            $accountoptions .= "<option disabled>---</option>";
            $accountoptions .= "<option value='NewAccount'>Add Account</option>";
            $data['accountoptions'] = $accountoptions;
            //-----------------------------------------------------------------

            $salutations = Tbl_salutations::all();
            $salutationoptions = "<option value='0'>None</option>";
            if (count($salutations) > 0) {
                foreach ($salutations as $salutation) {
                    $salutationselected = ($salutation->sal_id == $leadarr['sal_id']) ? 'selected' : '';
                    $salutationoptions .= "<option value='" . $salutation->sal_id . "' " . $salutationselected . ">" . $salutation->salutation . "</option>";
                }
            }
            $data['salutationoptions'] = $salutationoptions;


            // echo json_encode($leadarr);
            // exit();
            //  Products
            $products = Tbl_products::where('active', 1)->where('uid', $uid)->get();
            $productoptions = '<option value="">Select Product...</option>';
            foreach ($products as $product) {
                $productselected = (($leadarr['pro_id'] > 0) && ($product->pro_id == $leadarr['pro_id'])) ? 'selected' : '';
                $productoptions .= '<option value="' . $product->pro_id . '" ' . $productselected . '>' . $product->name . '</option>';
            }
            $data['productoptions'] = $productoptions;

            // echo json_encode($data);


            return view('auth.leads.editprolead')->with('data', $data);
        } else {
            return redirect('leads/getproductleads/list');
        }
    }

    public function updateProductLead(Request $request, $id)
    {
        // echo $id;
        // echo json_encode($request->input());
        // exit();
        //        $formdata = array();
        $user = Auth::user();
        $lead  = Tbl_leads::find($id);

        if ($user->can('update', $lead)) {

            $this->validate($request, [
                'first_name' => 'required|max:255',
                'mobile' => 'required|numeric',
                // 'last_name' => 'required|max:255',
                // 'email' => 'required|email|max:255',    //,email,' . $id . ',ld_id |unique:tbl_leads
            ]);

            $formdata = $request->input();

            $acc_id = 0;
            if ($request->input('account') == 'NewAccount') {
                $arr_account = array(
                    'uid' => Auth::user()->id,
                    'name' => $request->input('addAccount'),
                );
                $accounts = Tbl_Accounts::create($arr_account);
                $acc_id = $accounts->acc_id;
            } else {
                $acc_id = $request->input('account');
            }
            $formdata['account'] = $acc_id;


            $filename = '';
            if ($request->hasfile('userpicture')) {
                //-------------Image Validation----------------------------------
                $file = $request->file('userpicture');
                // Build the input for validation
                $fileArray = array('userpicture' => $file);

                // Tell the validator that this file should be an image
                $rules = array(
                    'userpicture' => 'mimes:jpeg,jpg,png,gif|max:10000' // max 10000kb
                );

                // Now pass the input and rules into the validator
                $validator = Validator::make($fileArray, $rules);

                // Check to see if validation fails or passes
                if ($validator->fails()) {
                    return redirect('leads/product/edit/' . $id)->with('error', 'Please upload jpg, png and giff images only.');
                }
                //-------------Image Validation----------------------------------
                //            $file = $request->file('userpicture');
                $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
                $file->move('uploads/leads/', $name);   //public_path()
                $filename = '/uploads/leads/' . $name;
                //            $leads->picture = $filename;
            }
            $formdata['picture'] = $filename;

            $uid = Auth::user()->id;
            $leadname = $request->input('first_name') . ' ' . $request->input('last_name') . ' ' . date('m-d-Y-h-i-s');
            $ld_status = $request->input('leadstatus');

            // Get Lead Details
            $leads = $this->getLeadDetails($id);
            $createDeal = 0;

            // echo json_encode($leads);
            // exit();

            $leadStatus = Tbl_leadstatus::find($ld_status);

            if (($leads->tbl_leadstatus != '') && ((int) $ld_status != (int) $leads->tbl_leadstatus->ldstatus_id)) {
                $createDeal = $leadStatus->deal;
            }
            if (($leads->tbl_leadstatus == '') && ((int) $ld_status > 0)) {
                $createDeal = $leadStatus->deal;
            }

            // echo $createDeal;
            // exit();

            $res = $this->updateLead($formdata, $id);

            if ($res) {

                if ($createDeal == 1) {

                    $closingDate = date('Y-m-d', strtotime(' + 30 days'));
                    $notes = 'deal created on ' . date('m-d-Y-h-i-s');
                    $deal_status = 0;
                    $probability = 0;
                    $product = $formdata['product'];

                    $dealObj = new DealController();

                    $dealdata = array(
                        'uid' => $uid,
                        'ld_id' => $id,
                        'sfun_id' => 0,
                        'ldsrc_id' => 0,
                        'name' => $leadname,
                        'value' => 10,
                        'closing_date' => $closingDate,
                        'notes' => $notes,
                        'deal_status' => $deal_status,
                        'probability' => $probability,
                        'pro_id' => $product,
                        'dl_id' => 0,
                    );

                    $dealRes = $dealObj->addDeal($dealdata);
                }
                return redirect('/leads/product/' . $id)->with('success', 'Lead Updated Successfully...!');
            } else {
                return redirect('/leads/product/' . $id)->with('error', 'Error occurred. Please try again later...! ');
            }
        } else {
            return redirect('leads/getproductleads/list');
        }
    }

    public function showConsumer($id)
    {
        $cart_order = Tbl_cart_orders::with('Tbl_countries')->with('Tbl_states')->find($id);
        $pro_id = $cart_order->pro_id;
        $cid = $cart_order->uid;

        $product = $this->getProductDetails($pro_id);
        $data['product'] = $product['product'];
        $data['user'] = $product['user'];

        // $consumer = Consumer::find($cid);
        // $data['consumer'] = $consumer;
        $data['cart_order'] = $cart_order;
        // echo json_encode($data);
        // exit();

        return view('auth.leads.showconsumer')->with('data', $data);
    }


    public function getProductDetails($id)
    {
        $products = Tbl_products::with('Tbl_units')->with('Tbl_productcategory')->find($id);
        //    echo json_encode($products);
        //    exit();

        $data['product'] = $products;
        $uid = '';
        $user = '';
        if ($products->user_type == 1) {
            $uid = $products->uid;
            $user = Admin::with('currency')->find($uid);
        } else {
            $uid = $products->uid;
            $user = User::with('currency')->find($uid);
        }

        $data['user'] = $user;

        return $data;
    }


    public function productLeadsDeleteAll(Request $request)
    {
        $ids = $request->input('id');
        // return json_encode($ids);
        // return Tbl_cart_orders::whereIn('coid', $ids)->update(array('active' => 0));

        return Tbl_leads::whereIn('ld_id', $ids)->update(array('active' => 0));
    }

    public function deleteProductLead($id)
    {
        // $ids = $request->input('id');
        // return json_encode($ids);
        // return Tbl_cart_orders::whereIn('coid', $ids)->update(array('active' => 0));

        // echo $id;
        // exit();
        $user = Auth::user();
        $lead  = Tbl_leads::find($id);

        if ($user->can('delete', $lead)) {

            $account = Tbl_leads::where('ld_id', $id)->update(array('active' => 0));
            if ($account) {
                return redirect('leads/getproductleads/list')->with('success', 'Deleted Successfully...!');
            } else {
                return redirect('leads/getproductleads/list')->with('error', 'Error occurred. Please try again later...!');
            }
        } else {
            return redirect('leads/getproductleads/list');
        }
    }

    public function productLeadsRestoreAll(Request $request)
    {
        $ids = $request->input('id');
        return Tbl_leads::whereIn('ld_id', $ids)->update(array('active' => 1));
    }

    public function importProductLeads($type)
    {
        $uid = Auth::user()->id;
        $products = Tbl_products::where('active', 1)->where('uid', $uid)->get();

        $productoptions = '<option value="">Select Product...</option>';
        foreach ($products as $product) {
            $productoptions .= '<option value="' . $product->pro_id . '">' . $product->name . '</option>';
        }
        $data['productoptions'] = $productoptions;

        return view('auth.leads.proleadsimport')->with('data', $data);
    }

    public function importProductLeadsData(Request $request)
    {
        $uid = Auth::user()->id;
        // echo json_encode($request->input());
        // exit();

        $proId = $request->input('product');

        $filename = '';
        if ($request->hasfile('importFile')) {

            //-------------Image Validation----------------------------------
            $file = $request->file('importFile');
            // Build the input for validation
            $fileArray = array('importFile' => $file, 'extension' => strtolower($file->getClientOriginalExtension()));

            //            echo $file->getClientOriginalExtension();
            //            exit(0);
            // Tell the validator that this file should be an image
            $rules = array(
                'importFile' => 'required', // max 10000kb
                'extension' => 'required|in:csv'
            );

            // Now pass the input and rules into the validator
            $validator = Validator::make($fileArray, $rules);

            // Check to see if validation fails or passes
            if ($validator->fails()) {
                return redirect('/leads/pro/import/csv')->with('error', 'Please upload .csv only.');
            }
            //-------------Image Validation----------------------------------

            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $path = $request->file('importFile')->getRealPath();
            // $data = Excel::load($path)->get();

            $res = Excel::import(new ProductLeadsImport($uid, $proId), request()->file('importFile'));

            if ($res) {
                // echo 'Success';
                return redirect('/leads/getproductleads/list')->with('success', 'Uploaded successfully');
            } else {
                // echo 'Failed';
                return redirect('/leads/pro/import/csv')->with('error', "Error ocurred. Try again later..!");
            }
        } else {
            return redirect('/leads/pro/import/csv')->with('error', 'Please upload file.');
        }
    }

    public function exportProductLeads($type)
    {
        $uid = Auth::user()->id;
        $products = Tbl_products::where('active', 1)->where('uid', $uid)->get();

        $productoptions = '<option value="">Select Product...</option>';
        foreach ($products as $product) {
            $productoptions .= '<option value="' . $product->pro_id . '">' . $product->name . '</option>';
        }
        $data['productoptions'] = $productoptions;

        return view('auth.leads.proleadsexport')->with('data', $data);
    }

    public function exportProductLeadsData(Request $request)
    {
        $uid = Auth::user()->id;
        // echo json_encode($request->input());
        // exit();

        $proId = $request->input('product');
        return Excel::download(new ProductLeadsExport($uid, $proId), 'productleads.xlsx');
    }


    public function getProLeadsOldDate()
    {
        $uid = Auth::user()->id;
        $oldDate = Tbl_leads::where('uid', $uid)->where('leadtype', 2)->orderBy('created_at', 'asc')->first();
        // echo json_encode($oldDate);
        $upDate = ($oldDate != '') ? date('Y-m-d', strtotime($oldDate->created_at)) : date('Y-m-d');
        return $upDate;
    }

    public function getProLeadsLatestDate()
    {
        $uid = Auth::user()->id;
        $oldDate = Tbl_leads::where('uid', $uid)->where('leadtype', 2)->orderBy('created_at', 'desc')->first();
        // echo json_encode($oldDate);
        $upDate = ($oldDate != '') ? date('Y-m-d', strtotime($oldDate->created_at)) : date('Y-m-d');
        return $upDate;
    }

    public function productOptions()
    {
        $uid = Auth::user()->id;
        //  Products
        $products = Tbl_products::where('uid', $uid)->where('active', 1)->get();    //where('uid', $uid)->
        $productoptions = '<option value="0">All</option>';
        foreach ($products as $product) {
            $productoptions .= '<option value="' . $product->pro_id . '">' . $product->name . '</option>';
        }
        return $productoptions;
    }

    public function ProductLeadsfilter(Request $request)
    {

        // echo json_encode($request->input());
        // exit();

        $status = $request->input('id');
        $start = ($request->input('start') != '') ? date('Y-m-d', strtotime($request->input('start'))) : '';
        $end = ($request->input('end') != '') ? date('Y-m-d', strtotime($request->input('end'))) : '';

        $oldDate = $this->getOldDate();
        $latestDate = $this->getLatestDate();

        $timer = $this->getFilterTime($start, $end);

        if (($start ==  $oldDate) && ($end == $latestDate)) {
            $start = '';
            $end = '';
        }

        //  User details
        $uid = Auth::user()->id;
        $user = User::find($uid);

        //  Currency
        $cr_id = $user->cr_id;
        $currency = currency::find($cr_id);

        //  Lead Status
        $leadstatuslist = Tbl_leadstatus::all();

        $leads = $this->getLeads($uid, $start, $end, $status);
        $leads['timer'] = $timer;

        $leadStatusVal = 'All';
        if ($status > 0) {
            $lstatus = Tbl_leadstatus::find($status);
            $leadStatusVal = $lstatus->status;
        }
        $leads['leadStatusVal'] = $leadStatusVal;
        return json_encode($leads);
    }
}

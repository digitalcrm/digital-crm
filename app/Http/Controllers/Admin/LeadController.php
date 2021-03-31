<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
//  Models
use App\Tbl_leads;
use App\Tbl_countries;
use App\Tbl_states;
use App\Tbl_industrytypes;
use App\Tbl_leadsource;
use App\Tbl_leadstatus;
use App\Tbl_Accounts;
use App\User;
use App\Admin;
use App\Tbl_deals;
use App\Tbl_invoice;
use App\Tbl_fb_leads;
use App\Tbl_leads_csv_files;
use App\Tbl_forms;
use App\Tbl_formleads;
use App\Tbl_groups;
use App\Tbl_group_users;
use App\Tbl_product_forms;
use App\Tbl_products;
use App\Tbl_productcategory;
use App\Tbl_cart_orders;
use App\Tbl_salutations;
// use Excel;

use App\Imports\LeadsImport;
use App\Exports\LeadsExport;
use Maatwebsite\Excel\Facades\Excel;

//  Controllers
//use App\Http\Controllers\WebtoleadController;

class LeadController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('test:lead-list', ['only' => ['index', 'show']]);
        $this->middleware('test:lead-create', ['only' => ['create', 'store']]);
        $this->middleware('test:lead-edit', ['only' => ['edit', 'update']]);
        $this->middleware('test:lead-delete', ['only' => ['destroy', 'deleteLead', 'deleteAll']]);
        $this->middleware('test:lead-import', ['only' => ['import', 'importData']]);
        $this->middleware('test:lead-export', ['only' => ['export', 'exportData']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //  Lead Status

        $leadstatuslist = Tbl_leadstatus::all();
        $status = 'All';
        $leadstatusSelect = '<option value="All">All</option>';
        foreach ($leadstatuslist as $statuslist) {
            $leadstatusSelect .= '<option value="' . $statuslist->ldstatus_id . '">' . $statuslist->status . '</option>';
        }
        $data['leadstatusSelect'] = $leadstatusSelect;

        //  User Options

        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        $users_arr = $users->toArray();
        // $random_users = $users_arr[0];
        //        echo $random_users['id'];
        //        echo json_encode($users_arr[0]);
        //        exit();

        // $useroptions = "<option value='0'>Select User...</option>";
        $useroptions = "<option value='All'>All</option>";
        $uid = 'All';
        // $uid = $random_users['id'];
        foreach ($users as $userdetails) {
            $selected = ($uid == $userdetails->id) ? 'selected' : '';   //$random_users['id']
            $useroptions .= "<option value=" . $userdetails->id . " " . $selected . ">" . $userdetails->name . "</option>";    // . "  " . $selected
        }

        $data['useroptions'] = $useroptions;


        //  Forms Option Tag
        // $forms = Tbl_fb_leads::groupBy('form_id')->get(['form_id']);   //Tbl_leads
        // $forms_arr = $forms->toArray();
        // $random_forms = $forms_arr[0];
        // $formid = $random_forms['form_id'];

        // $formoptions = "<option value='0'>Select...</option>";
        // foreach ($forms as $form) {
        //     $fselected = ($random_forms['form_id'] == $form->form_id) ? 'selected' : '';
        //     $formoptions .= "<option value=" . $form->form_id . " " . $fselected . ">" . $form->form_id . "</option>";    // . "  " . $selected
        // }

        // $data['formoptions'] = $formoptions;

        //  Groups Option Tag
        $groups = Tbl_groups::where('active', 1)->get();
        $groupoptions = "<option value='0'>Select Group...</option>";
        foreach ($groups as $group) {
            $groupoptions .= "<option value=" . $group->gid . ">" . $group->name . "</option>";    // . "  " . $selected
        }
        $data['groupoptions'] = $groupoptions;

        // $day = date('Y-m-d');
        // $weekDay = date('Y-m-d', strtotime($day . ' - 1 week'));

        //  Leads
        $formid = 0;
        $day = '';
        $weekDay = '';
        $leads = $this->getLeads($uid, $status, $weekDay, $day);

        $data['total'] = $leads['total'];
        $data['table'] = $leads['table'];


        $data['oldDate'] = $this->getOldDate();
        $data['latestDate'] = $this->getLatestDate();

        $data['timer'] = 'All';
        $data['leadStatusVal'] = 'All';
        $data['userVal'] = 'All';

        return view('admin.leads.index')->with("data", $data);
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
        $leads = Tbl_leads::with('Tbl_leadsource')
            ->with('Tbl_industrytypes')
            ->with('Tbl_leadstatus')
            ->with('Tbl_Accounts')
            ->with('Tbl_countries')
            ->with('Tbl_states')
            ->with('users')
            ->find($id);
        $leadarr = $leads->toArray();
        // echo json_encode($leadarr);
        $data['leadarr'] = $leadarr;

        // $data['editLink'] = url('leads').'/'.$id.'/edit';

        $data['deleteLink'] = url('admin/leads/delete') . '/' . $id;

        return view('admin.leads.show')->with("data", $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $leads = Tbl_leads::with('Tbl_leadsource')
            ->with('Tbl_industrytypes')
            ->with('Tbl_leadstatus')
            ->with('Tbl_Accounts')
            ->with('Tbl_countries')
            ->with('Tbl_states')
            ->find($id);
        $leadarr = $leads->toArray();

        $uid =  $leads->uid;

        // echo json_encode($leadarr);

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

        $accounts = Tbl_Accounts::where('uid', $uid)->where('active', 1)->get();;
        // $accounts = Tbl_Accounts::all();
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

        $products = Tbl_products::where('uid', $uid)->where('active', 1)->get();
        $productoptions = '<option value="">Select Product...</option>';
        foreach ($products as $product) {
            $productselected = (($leadarr['pro_id'] > 0) && ($product->pro_id == $leadarr['pro_id'])) ? 'selected' : '';
            $productoptions .= '<option value="' . $product->pro_id . '" ' . $productselected . '>' . $product->name . '</option>';
        }
        $data['productoptions'] = $productoptions;

        return view('admin.leads.edit')->with("data", $data);
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


        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'usermail' => 'required|email|max:255',
        ]);


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


        $leads = Tbl_leads::find($id);
        $leads->first_name = $request->input('first_name');
        $leads->last_name = $request->input('last_name');
        $leads->email = $request->input('usermail');

        $leads->mobile = $request->input('mobile');
        $leads->phone = $request->input('phone');
        $leads->ldsrc_id = $request->input('leadsource');
        $leads->ldstatus_id = $request->input('leadstatus');
        $leads->intype_id = $request->input('industrytype');
        $leads->acc_id = $acc_id;
        $leads->notes = $request->input('notes');
        $leads->website = $request->input('website');
        $leads->country = $request->input('country');
        $leads->state = $request->input('state');
        $leads->city = $request->input('city');
        $leads->street = $request->input('street');
        $leads->zip = $request->input('zip');
        $leads->designation = $request->input('designation');

        //        $filename = '';
        //        if ($request->hasfile('userpicture')) {
        //            $file = $request->file('userpicture');
        //            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
        //            $file->move('uploads/leads/', $name);   //public_path()
        //            $filename = '/uploads/leads/' . $name;
        //            $leads->picture = $filename;
        //        }

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
                return redirect('admin/leads/' . $id . '/edit')->with('error', 'Please upload jpg, png and giff images only.');
            }
            //-------------Image Validation----------------------------------

            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $file->move('uploads/leads/', $name);   //public_path()
            $filename = '/uploads/leads/' . $name;
            $leads->picture = $filename;
        }


        $leads->save();
        return redirect('admin/leads')->with('success', 'Lead Updated Successfully...!');
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

    public function deleteLead($id)
    {
        $leads = Tbl_leads::find($id);
        $leads->active = 0;
        $leads->save();

        //----------Deals-------------------
        Tbl_deals::where('ld_id', '=', $id)->update(['active' => 0]);

        //----------Invoice-------------------
        Tbl_invoice::where('ld_id', '=', $id)->update(['active' => 0]);

        return redirect('admin/leads')->with('success', 'Lead Deleted Successfully...!');
    }

    public function getLeadsofUser(Request $request)
    {

        // echo json_encode($request->input());
        // exit();

        $id = $request->input('id');
        $status = $request->input('status');
        $start = ($request->input('start') != '') ? date('Y-m-d', strtotime($request->input('start'))) : '';
        $end = ($request->input('end') != '') ? date('Y-m-d', strtotime($request->input('end'))) : '';

        $oldDate = $this->getOldDate();
        $latestDate = $this->getLatestDate();

        $timer = $this->getFilterTime($start, $end);

        if (($start ==  $oldDate) && ($end == $latestDate)) {
            $start = '';
            $end = '';
        }

        // $formid = $request->input('formid');
        $formid = 0;

        // echo "id " . $id . " status " . $status . " start " . $start . " end " . $end;
        // exit();

        $leads = $this->getLeads($id, $status, $start, $end);

        // echo json_encode($leads);


        $leads['timer'] = $timer;

        $leadStatusVal = 'All';
        if (($status > 0) && ($status != 'All')) {
            $lstatus = Tbl_leadstatus::find($status);
            $leadStatusVal = $lstatus->status;
        }
        $leads['leadStatusVal'] = $leadStatusVal;

        $userValue = User::find($id);
        $leads['userVal'] = $userValue->name;

        echo json_encode($leads);
    }

    public function getLeads($uid, $status, $start, $end)
    {
        // echo " uid " . $uid . " status " . $status . " start " . $start . " end " . $end . "<br>";
        // exit();

        $leads = '';
        //-----------------------------------------
        $query = DB::table('tbl_leads')->where('tbl_leads.active', 1);
        if (($uid > 0) && ($uid != "All")) {

            // echo 'uid';
            $query->where('tbl_leads.uid', $uid);
        }
        if (($status > 0) && ($uid != "All")) {
            // echo 'status';
            $query->where('tbl_leads.ldstatus_id', $status);
        }
        if (($start != '') && ($end != '')) {
            // echo 'start end';
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
            'tbl_products.name as product',
            'tbl_accounts.company as company'
        );
        $leads = $query->get();

        // echo json_encode($leads);
        // exit();

        // if (($uid == 'All') && ($status == "All")) {
        //     $leads = Tbl_leads::where('active', 1)
        //         ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
        //         ->with('Tbl_leadstatus')
        //         ->with('tbl_leadsource')
        //         ->with('Tbl_accounts')
        //         ->orderBy('ld_id', 'desc')
        //         ->get();
        // }

        // if (($uid > 0) && ($status == "All")) {
        //     $leads = Tbl_leads::where('active', 1)->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])->where('uid', $uid)->with('Tbl_leadstatus')->with('tbl_leadsource')->with('Tbl_accounts')->orderBy('ld_id', 'desc')->get();
        // }

        // if (($uid == "All") && ($status > 0)) {
        //     $leads = Tbl_leads::where('active', 1)->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])->where('ldstatus_id', $status)->with('Tbl_leadstatus')->with('tbl_leadsource')->with('Tbl_accounts')->orderBy('ld_id', 'desc')->get();
        // }

        // if (($uid > 0) && ($status > 0)) {
        //     $leads = Tbl_leads::where('active', 1)->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])->where('uid', $uid)->where('ldstatus_id', $status)->with('Tbl_leadstatus')->with('tbl_leadsource')->with('Tbl_accounts')->orderBy('ld_id', 'desc')->get();
        // }
        //  New Queries


        //        if ($uid == 'All') {
        //            $leads = Tbl_leads::where('active', 1)->orderBy('ld_id', 'desc')->get();
        //        } else {
        //            $leads = Tbl_leads::where('active', 1)->where('uid', $uid)->orderBy('ld_id', 'desc')->get();
        //        }
        //------------------------------  Old Queries------------------------------



        // if (($uid > 0) && ($status == 'All') && ($formid != "All")) {
        //     $leads = Tbl_leads::select("*")
        //             ->join("tbl_fb_leads", "tbl_fb_leads.fblead_id", "=", "tbl_leads.fblead_id")
        //             ->where('tbl_leads.uid', $uid)
        //             ->where('tbl_leads.active', 1)
        //             ->where('tbl_fb_leads.form_id', '=', $formid)
        //             ->with('Tbl_leadstatus')
        //             ->orderBy('tbl_leads.ld_id', 'desc')
        //             ->get();
        // }



        // if (($uid > 0) && ($status > 0) && ($formid != "All")) {
        //     $leads = Tbl_leads::select("*")
        //             ->join("tbl_fb_leads", "tbl_fb_leads.fblead_id", "=", "tbl_leads.fblead_id")
        //             ->where('tbl_leads.uid', $uid)
        //             ->where('tbl_leads.ldstatus_id', $status)
        //             ->where('tbl_leads.active', 1)
        //             ->where('tbl_fb_leads.form_id', '=', $formid)
        //             ->with('Tbl_leadstatus')
        //             ->orderBy('tbl_leads.ld_id', 'desc')
        //             ->get();
        // }

        // echo json_encode($leads);
        // exit();

        $total = count($leads);

        if ($total > 0) {
            $formstable = '<table id="leadsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            //            $formstable .= '<th width="2"></th>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Email</th>';
            $formstable .= '<th>Mobile</th>';
            $formstable .= '<th>City</th>';
            $formstable .= '<th>Designation</th>';
            $formstable .= '<th>Lead Status</th>';
            $formstable .= '<th>Lead Source</th>';
            $formstable .= '<th>Account</th>';
            $formstable .= '<th>Company</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            // $formstable .= '<th class="none">Notes</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($leads as $formdetails) {

                $leadsource = $formdetails->leadsource; //($formdetails->tbl_leadsource != null) ? $formdetails->tbl_leadsource->leadsource : '';

                $leadStatus = $formdetails->leadstatus; //($formdetails->tbl_leadstatus != null) ? $formdetails->tbl_leadstatus->status : '';

                $account = $formdetails->account; //($formdetails->tbl_accounts != null) ? $formdetails->tbl_accounts->name : '';

                $company = $formdetails->company; //($formdetails->tbl_accounts != null) ? $formdetails->tbl_accounts->company : '';

                $formstable .= '<tr>';
                //                $formstable .= '<td><input type="checkbox" class="checkAll" id="' . $formdetails->ld_id . '"></td>';
                $formstable .= '<td><a href="' . url('admin/leads/' . $formdetails->ld_id) . '">' . substr($formdetails->first_name . ' ' . $formdetails->last_name, 0, 25) . '</a>&nbsp;</td>';
                $formstable .= '<td>' . $formdetails->email . '</td>';
                $formstable .= '<td>' . $formdetails->mobile . '</td>';
                $formstable .= '<td>' . $formdetails->city . '</td>';
                $formstable .= '<td>' . $formdetails->designation . '</td>';
                $formstable .= '<td>' . $leadStatus . '</td>';
                $formstable .= '<td>' . $leadsource . '</td>';
                $formstable .= '<td>' . $account . '</td>';
                $formstable .= '<td>' . $company . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                      <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                  </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item text-default text-btn-space" href="' . url('admin/leads/' . $formdetails->ld_id) . '">View</a>
                        <a class="dropdown-item text-default text-btn-space" href="' . url('admin/leads/' . $formdetails->ld_id . '/edit') . '">Edit</a>
                        <a class="dropdown-item text-default text-btn-space" href="' . url('admin/leads/delete/' . $formdetails->ld_id) . '">Delete</a>
                      </div>
                    </div>';
                $formstable .= '</td>';
                // $formstable .= '<td>' . $formdetails->notes . '</td>';
                $formstable .= '</tr>';
                //
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

    public function deleteAll(Request $request)
    {
        $ids = $request->input('id');
        return Tbl_leads::whereIn('ld_id', $ids)->update(array('active' => 0));
    }

    public function importLeadFromCSV($id)
    {
        return view('admin.leads.import');
    }

    public function importLeadData(Request $request)
    {
        //        echo json_encode($request->input());
        $fbleads = array();

        $uid = Auth::user()->id;

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
            $data = Excel::load($path)->get();
            $exist_rec = '';

            if ($data->count()) {
                //                echo json_encode($data);
                foreach ($data as $key => $value) {
                    //
                    $exist = Tbl_leads::where('email', $value->email)->get();
                    //
                    if (count($exist) > 0) {
                        $exist_rec .= $value->email . ', ';
                        //                        echo $value->email . ' exists <br>';
                    } else {
                        //                        echo $value->email . ' not exists <br>';


                        $acc_id = 0;
                        $intype_id = 0;
                        $country = 0;
                        $state = 0;
                        $ldsrc_id = 0;
                        $ldstatus_id = 0;

                        if ($value->leadsource != '') {
                            $ldsrc = Tbl_leadsource::where(strtolower('leadsource'), strtolower($value->leadsource))->first();
                            if ($ldsrc != '') {
                                $ldsrc_id = $ldsrc->ldsrc_id;
                            }
                        }

                        if ($value->leadstatus != '') {
                            $ldstatus = Tbl_leadstatus::where(strtolower('status'), strtolower($value->leadstatus))->first();
                            if ($ldstatus != '') {
                                $ldstatus_id = $ldstatus->ldstatus_id;
                            }
                        }

                        if ($value->account != '') {
                            $accounts = Tbl_Accounts::where(strtolower('name'), strtolower($value->account))->first();
                            if ($accounts != '') {
                                $acc_id = $accounts->acc_id;
                            }
                        }

                        if ($value->industry_type != '') {
                            $intypes = Tbl_industrytypes::where(strtolower('type'), strtolower($value->industry_type))->first();
                            if ($intypes != '') {
                                $intype_id = $intypes->intype_id;
                            }
                        }

                        if ($value->country != '') {
                            $countrys = Tbl_countries::where(strtolower('name'), strtolower($value->country))->first();
                            if ($countrys != '') {
                                $country = $countrys->id;
                            }
                        }

                        if ($value->state != '') {
                            $states = Tbl_states::where(strtolower('name'), strtolower($value->state))->first();
                            if ($states != '') {
                                $state = $states->id;
                            }
                        }

                        $formdata = array(
                            'id' => $value->id,
                            'created_time' => $value->created_time,
                            'ad_id' => $value->ad_id,
                            'ad_name' => $value->ad_name,
                            'adset_id' => $value->adset_id,
                            'adset_name' => $value->adset_id,
                            'campaign_id' => $value->campaign_id,
                            'campaign_name' => $value->campaign_name,
                            'form_id' => $value->form_id,
                            'form_name' => $value->form_name,
                            'is_organic' => $value->is_organic,
                            'platform' => $value->platform,
                            'full_name' => $value->full_name,
                            'city' => $value->city,
                            'phone_number' => $value->phone_number,
                        );
                        $fbleads[] = $formdata;
                    }
                }

                //                echo json_encode($fbleads);
                //                exit();

                if (!empty($fbleads)) {     //$arr
                    Tbl_leads::insert($fbleads);    //$arr
                }

                if ($exist_rec != '') {
                    $with_status = 'info';
                    $with_message = trim($exist_rec, ",") . ' already exists. Remaing Uploaded successfully...!';
                }

                if ($exist_rec == '') {
                    $with_status = 'success';
                    $with_message = 'Uploaded successfully...!';
                }
                return redirect('admin/leads')->with($with_status, $with_message);
            } else {
                return redirect('admin/leads/import/csv')->with('error', "Please check uploaded file. Data don't exist.");
            }
        } else {
            return redirect('admin/leads/import/csv')->with('error', 'Please upload file.');
        }
    }

    public function facebookLeadscsv($id)
    {
        $data = $this->getFacebookleads($id);

        //  Files Option tag
        $files = Tbl_leads_csv_files::where('active', 1)->orderBy('file_id', 'desc')->get();
        $fileOption = "<option value='all'>All...</option>";
        if (count($files) > 0) {
            foreach ($files as $file) {
                $fileOption .= "<option value='" . $file->file_id . "' " . (($id == $file->file_id) ? 'selected' : '') . ">" . $file->original_name . "</option>";
            }
        }
        $data['fileOption'] = $fileOption;

        return view('admin.leads.fbleads')->with('data', $data);
    }

    public function getFacebookLeadscsv(Request $request)
    {
        $id = $request->input('id');
        $data = $this->getFacebookleads($id);
        echo json_encode($data);
    }

    public function facebookLeads($id, $type)
    {

        $latest = '';
        if ($id == 'all') {
            $latest = Tbl_leads_csv_files::where('active', 1)->orderBy('file_id', 'desc')->first();

            if ($latest != null) {
                $id = $latest->file_id;
            } else {
                $id = 0;
            }
        }

        //  Get facebook leads
        if ($id > 0) {
            $data = $this->getFacebookleads($id, $type);
        } else {
            $data['total'] = 0;
            $data['table'] = 'No records available';
        }

        //  Files Option tag
        $files = Tbl_leads_csv_files::where('active', 1)->orderBy('file_id', 'desc')->get();
        $fileOption = "<option value='0'>Select...</option>";
        if (count($files) > 0) {
            foreach ($files as $file) {
                $fileOption .= "<option value='" . $file->file_id . "' " . (($id == $file->file_id) ? 'selected' : '') . ">" . $file->original_name . "</option>";
            }
        }
        $data['fileOption'] = $fileOption;



        return view('admin.leads.fbleads')->with('data', $data);
    }

    public function getfbleads(Request $request)
    {
        $id = $request->input('id');
        $type = $request->input('type');
        $data = $this->getFacebookleads($id, $type);
        return json_encode($data);
    }

    public function getFacebookleads($id)
    {

        $leads = '';
        if ($id == 'all') {
            $leads = Tbl_fb_leads::where('uploaded_by', 1)->with('tbl_leads_csv_files')->orderBy('fblead_id', 'desc')->get();
        } else {
            $leads = Tbl_fb_leads::where('file_id', $id)->with('tbl_leads_csv_files')->orderBy('fblead_id', 'desc')->get();
        }

        $total = count($leads);

        if ($total > 0) {
            $formstable = '<table id="leadsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            //            $formstable .= '<th width="2"><input type="checkbox" id="selectAll"></th>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>City</th>';
            $formstable .= '<th>Mobile</th>';
            $formstable .= '<th>Status</th>';
            $formstable .= '<th>Date</th>';
            //            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($leads as $formdetails) {
                $formstable .= '<tr>';
                //                $formstable .= '<td><input type="checkbox" class="checkAll" id="' . $formdetails->ld_id . '"></td>';
                $formstable .= '<td><a href="#">' . substr($formdetails->full_name, 0, 30) . '</a>&nbsp;</td>';
                $formstable .= '<td>' . $formdetails->city . '</td>';
                $formstable .= '<td>' . $formdetails->phone_number . '</td>';
                $formstable .= '<td>' . (($formdetails->assigned == 1) ? 'Assigned' : 'Not Assigned') . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                //                $formstable .= '<td>';
                //                $formstable .= '<select id="user' . $formdetails->fblead_id . '" name="user' . $formdetails->fblead_id . '" onchange="return assignUser(\'user' . $formdetails->fblead_id . '\',' . $formdetails->fblead_id . ');">';
                //                $formstable .= $useroptions;
                //                $formstable .= '</select>';
                //                $formstable .= '</td>';
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

    public function assignLeadtoUser(Request $request)
    {
        //        echo json_encode($request->input());

        $userid = $request->input('userid');
        $lead = $request->input('lead');

        $fblead = Tbl_fb_leads::find($lead);

        //        echo json_encode($fblead);
        $mobile = '';
        if (strpos($fblead->phone_number, 'p:+') !== false) {
            $mobile = str_replace('p:+', '', $fblead->phone_number);
        } else {
            $mobile = $fblead->phone_number;
        }


        $formdata = array(
            'uid' => $userid,
            'first_name' => $fblead->full_name,
            'city' => $fblead->city,
            'mobile' => $mobile,
            'fblead_id' => $fblead->fblead_id,
        );


        //        echo json_encode($formdata);

        $leads = Tbl_leads::create($formdata);
        $ld_id = $leads->ld_id;

        if ($ld_id > 0) {
            $fblead->assigned = 1;
            $fblead->save();
            return 'success';
        } else {
            return 'error';
        }
    }

    public function getLeadsbyAssignment(Request $request)
    {
        $type = $request->input('type');
        $data = '';
        if ($type == 0) {
            $data = $this->getUnassignedLeadsList();
        }
        if ($type == 1) {
            $data = $this->getLeads('All');
        }

        return json_encode($data);
    }

    public function getUnassignedLeadsList()
    {

        //        //  Forms Option Tag
        //        $forms = Tbl_fb_leads::groupBy('form_id')->get(['form_id']);   //Tbl_leads
        //        $formoptions = "<option value='0'>Select...</option>";
        //        foreach ($forms as $form) {
        //            $formoptions .= "<option value=" . $form->form_id . ">" . $form->form_id . "</option>";    // . "  " . $selected
        //        }
        //
        //        $data['formoptions'] = $formoptions;
        //        ----------------------------------------------------------------
        //  Forms Option Tag
        $forms = Tbl_fb_leads::groupBy('form_id')->get(['form_id']);   //Tbl_leads
        $forms_arr = $forms->toArray();
        $random_forms = $forms_arr[0];
        $formid = $random_forms['form_id'];

        $formoptions = "<option value='0'>Select...</option>";
        foreach ($forms as $form) {
            $fselected = ($random_forms['form_id'] == $form->form_id) ? 'selected' : '';
            $formoptions .= "<option value=" . $form->form_id . " " . $fselected . ">" . $form->form_id . "</option>";    // . "  " . $selected
        }
        $data['formoptions'] = $formoptions;

        //  Users Option tag
        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        $useroptions = "<option value='0'>Select...</option>";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";    // . "  " . $selected
        }

        //  Get Unassigned leads
        $leads = Tbl_fb_leads::where('assigned', 0)->where('form_id', $formid)->orderBy('fblead_id', 'desc')->get();   //Tbl_leads
        $total = count($leads);
        if ($total > 0) {
            $formstable = '<table id="leadsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th width="2">&nbsp;</th>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>City</th>';
            $formstable .= '<th>Mobile</th>';
            //            $formstable .= '<th>Status</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($leads as $formdetails) {
                $formstable .= '<tr>';
                $formstable .= '<td>
				<div class="custom-control custom-checkbox">
			  <input type="checkbox" class="checkAll custom-control-input" id="' . $formdetails->fblead_id . '">
			  <label class="custom-control-label" for="' . $formdetails->fblead_id . '"></label>
				</div>
				</td>';
                $formstable .= '<td><a href="#">' . substr($formdetails->full_name, 0, 30) . '</a>&nbsp;</td>';
                $formstable .= '<td>' . $formdetails->city . '</td>';
                $formstable .= '<td>' . $formdetails->phone_number . '</td>';
                //                $formstable .= '<td>' . (($formdetails->assigned == 1) ? 'Assigned' : 'Not Assigned') . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<select id="user' . $formdetails->fblead_id . '" name="user' . $formdetails->fblead_id . '" onchange="return assignUser(\'user' . $formdetails->fblead_id . '\',' . $formdetails->fblead_id . ');">';
                $formstable .= $useroptions;
                $formstable .= '</select>';
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

    public function getUnassignedLeads()
    {


        $data = $this->getUnassignedLeadsList();

        return view('admin.leads.unassigned')->with('data', $data);
    }

    public function allocateLeadsQuota()
    {
        //  Users
        $users = User::orderby('id', 'asc')->get(['id', 'name', 'quota']);

        $total = count($users);
        $total_quota = 0;
        if ($total > 0) {
            $formstable = '<table id="" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Percent %</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($users as $formdetails) {
                $formstable .= '<tr>';
                $formstable .= '<td>';
                $formstable .= '<input type="hidden" value="' . $formdetails->id . '" id="' . $formdetails->id . '" name="users[]"/>';
                $formstable .= '<label>' . $formdetails->name . '</label>';
                $formstable .= '</td>';
                $formstable .= '<td>';
                $formstable .= '<div class="input-group">';
                $formstable .= '<input type="number" value="' . $formdetails->quota . '" name="quota[]" id="quota" class="form-control leadQuota" min="0" onKeyup="return calculateTotal();"/>';
                $formstable .= '<div class="input-group-append">
    <span class="input-group-text">%</span>
  </div>';
                $formstable .= '</div>';
                $formstable .= '</td>';
                $formstable .= '</tr>';

                $total_quota += $formdetails->quota;
            }
            $formstable .= '<tr>';
            $formstable .= '<td>';
            $formstable .= '<label>Total</label>';
            $formstable .= '</td>';
            $formstable .= '<td>';
            $formstable .= '<div class="input-group">';
            $formstable .= '<input type="number" name="total_quota" id="total_quota" class="form-control" value="' . $total_quota . '"/>';
            $formstable .= '<div class="input-group-append">
    <span class="input-group-text">%</span>
  </div>';
            $formstable .= '</div>';
            $formstable .= '</td>';
            $formstable .= '</tr>';
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;

        return view('admin.leads.allocate')->with('data', $data);
    }

    public function allocateLeadsQuotatoUser(Request $request, $id)
    {
        //        echo json_encode($request->input());

        $users = $request->input('users');
        $quotas = $request->input('quota');

        if (array_sum($quotas) == 100) {

            foreach ($users as $key => $user) {
                //            echo $user . ' ' . $quotas[$key] . '<br>';

                $userdata = User::find($user);
                $userdata->quota = $quotas[$key];
                $userdata->save();
            }
            return redirect('admin/allocateleadsquota')->with('success', 'Allocated Successfully');
        } else {
            return redirect('admin/allocateleadsquota')->with('error', 'Total should be 100%');
        }
    }

    public function assignLeadstoUser()
    {

        //  Get Unassigned leads
        $leads = Tbl_fb_leads::where('assigned', 0)->orderBy('fblead_id', 'desc')->get();   //Tbl_leads
        $total_leads = count($leads);
        $remaining_leads = $total_leads;
        // Get Users
        $users = User::orderby('id', 'asc')->get(['id', 'name', 'quota']);
        $total_users = count($users);
        if ($total_users > 0) {
            $formstable = '<table id="leadsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Percent %</th>';
            $formstable .= '<th>Assigned Leads</th>';
            //            $formstable .= '<th>Remaining Leads</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($users as $formdetails) {
                $formstable .= '<tr>';
                $formstable .= '<td><label>' . $formdetails->name . '</label><input type="hidden" value="' . $formdetails->id . '" name="users[]"></td>';
                $formstable .= '<td><label>' . $formdetails->quota . ' %</label><input type="hidden" value="' . $formdetails->quota . '" name="quotas[]"></td>';
                $assigned = 0;
                if ((int) $formdetails->quota > 0) {
                    $assigned = round(($formdetails->quota / 100) * $total_leads);
                    $remaining_leads = (int) $remaining_leads - (int) $assigned;
                }
                $formstable .= '<td><label>' . $assigned . '</label><input type="hidden" value="' . $assigned . '" name="assignedQuotas[]"></td>';
                //                $formstable .= '<td><label>' . $remaining_leads . '</label></td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        $data['total_users'] = $total_users;
        $data['total_leads'] = $total_leads;
        $data['allocated_leads'] = $remaining_leads;
        $data['remaining_leads'] = $remaining_leads;
        $data['table'] = $formstable;

        return view('admin.leads.assign')->with('data', $data);
    }

    //-----------------------Old----------------------------------------
    public function assignLeadstoUserbyQuota(Request $request)
    {
        //        echo json_encode($request->input());


        $users = $request->input('users');
        $quotas = $request->input('quotas');
        $assignedQuotas = $request->input('assignedQuotas');
        $total_leads = $request->input('total_leads');

        $total_quota = array_sum($quotas);
        if ($total_quota == 100) {

            if ($total_leads > 0) {

                $k = 0;
                for ($i = 0; $i < count($users); $i++) {

                    $data = array();

                    $user = $users[$i];
                    $quota = $assignedQuotas[$i];
                    //                    $userdata = User::find($user);

                    $qleads = Tbl_fb_leads::where('assigned', 0)
                        ->orderBy('fblead_id', 'desc')
                        ->limit($quota)
                        ->get(['fblead_id', 'full_name', 'city', 'phone_number'])
                        ->map(function ($article) {
                            $article->phone_number = str_replace('p:+', '', $article->phone_number);
                            return $article;
                        });

                    //                $data[]['user'] = $user;
                    //                $data[]['quota'] = $quota;
                    //                $data[]['qleads'] = $qleads;
                    $leadId = array();
                    foreach ($qleads as $qlead) {
                        $leadId[] = $qlead->fblead_id;
                        $formdata = array(
                            'uid' => $user,
                            'first_name' => $qlead->full_name,
                            'city' => $qlead->city,
                            'mobile' => $qlead->phone_number,
                            'fblead_id' => $qlead->fblead_id,
                        );
                        $data[] = $formdata;
                    }
                    //                $data[] = $leadId;
                    //                break;

                    $qleadRes = Tbl_leads::insert($data);
                    if ($qleadRes) {
                        Tbl_fb_leads::whereIn('fblead_id', $leadId)->update(array('assigned' => 1));
                    }
                    //                ----------------------------------------------------------
                    //
                    //                for ($j = 0; $j < $quota; $j++) {
                    //
                    //                    if (isset($leads[$k])) {
                    //                        echo $k . ' ' . $user . ' ' . $userdata->name . ' ' . $leads[$k]->fblead_id . ' ' . $leads[$k]->full_name . '<br>';
                    //                        // . ' ' . $leads[$i]->city . ' ' . $leads[$i]->mobile
                    //                        $k++;
                    //                    }
                    //                }
                    ////                echo $k. '<br>';
                    //                echo "-----------------------------------------------------------------------<br>";
                }

                //            echo json_encode($data);
                return redirect('admin/leads')->with('success', 'Leads Assigned Successfully');
            } else {
                return redirect()->back()->with('error', "You don't have leads...");
            }
        } else {
            return redirect()->back()->with('error', 'Please assign quota');
        }
    }

    public function getLeadsZapier()
    {
        $date = date('Y-m-d');
        //        echo date('d-m-Y');
        //        exit();

        $data = $this->getZapierLeads($date);

        return view('admin.leads.zpleads')->with('data', $data);
    }

    public function getLeadsZapierAjax(Request $request)
    {
        $time = $request->input('date');
        $date = date('Y-m-d', strtotime($time));
        $data = $this->getZapierLeads($date);
        echo json_encode($data);
    }

    public function getZapierLeads($date)
    {
        $leads = Tbl_fb_leads::where('uploaded_by', 3)->where(DB::raw('DATE(tbl_fb_leads.created_at)'), $date)  //'2019-07-29'
            ->leftJoin('tbl_leads', 'tbl_leads.fblead_id', '=', 'tbl_fb_leads.fblead_id')
            ->leftJoin('users', 'tbl_leads.uid', '=', 'users.id')
            ->orderBy('tbl_fb_leads.fblead_id', 'desc')
            ->get(['tbl_fb_leads.fblead_id', 'tbl_fb_leads.full_name', 'tbl_fb_leads.phone_number', 'tbl_fb_leads.assigned', 'tbl_fb_leads.created_at', 'tbl_leads.ld_id', 'tbl_leads.uid', 'users.name']);  //where('active', 1)->
        //        return $leads;
        //        echo json_encode($leads);
        //        exit();
        $total = count($leads);

        if ($total > 0) {
            $formstable = '<table id="leadsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            //            $formstable .= '<th width="2"><input type="checkbox" id="selectAll"></th>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Email</th>';
            $formstable .= '<th>Mobile</th>';
            //            $formstable .= '<th>Website</th>';
            $formstable .= '<th>Status</th>';
            $formstable .= '<th>User</th>';
            $formstable .= '<th>Date</th>';
            //            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($leads as $formdetails) {

                $assignedStatus = ($formdetails->assigned == 1) ? 'Assigned' : 'Not Assigned';

                $formstable .= '<tr>';
                $formstable .= '<td>' . substr($formdetails->full_name, 0, 25) . '</a>&nbsp;</td>';
                $formstable .= '<td>' . $formdetails->email . '</td>';
                $formstable .= '<td>' . $formdetails->phone_number . '</td>';
                //                $formstable .= '<td>' . $formdetails->website . '</td>';
                $formstable .= '<td>' . $assignedStatus . '</td>';
                $formstable .= '<td>' . $formdetails->name . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
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

    public function getLeadsFbCsv()
    {


        //  Files Option tag
        $files = Tbl_leads_csv_files::where('active', 1)->orderBy('file_id', 'desc')->get();
        $fileOption = "<option value='0'>All</option>";
        if (count($files) > 0) {
            foreach ($files as $file) {
                $fileOption .= "<option value='" . $file->file_id . "'>" . $file->original_name . "</option>";
            }
        }
        $data['fileOption'] = $fileOption;



        $leads = Tbl_leads::where('active', 1)->where('uploaded_from', 5)->orderBy('ld_id', 'desc')->get();

        $total = count($leads);

        if ($total > 0) {
            $formstable = '<table id="leadsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            //            $formstable .= '<th width="2"><input type="checkbox" id="selectAll"></th>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Email</th>';
            $formstable .= '<th>Mobile</th>';
            $formstable .= '<th>Website</th>';
            $formstable .= '<th>Status</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($leads as $formdetails) {

                $assignedStatus = ($formdetails->assigned == 1) ? 'Assigned' : 'Unassigned';

                $formstable .= '<tr>';
                //                $formstable .= '<td><input type="checkbox" class="checkAll" id="' . $formdetails->ld_id . '"></td>';
                $formstable .= '<td><a href="' . url('admin/leads/' . $formdetails->ld_id) . '">' . substr($formdetails->first_name . ' ' . $formdetails->last_name, 0, 25) . '</a>&nbsp;</td>';
                $formstable .= '<td>' . $formdetails->email . '</td>';
                $formstable .= '<td>' . $formdetails->mobile . '</td>';
                $formstable .= '<td>' . $formdetails->website . '</td>';
                $formstable .= '<td>' . $assignedStatus . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                      <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                      </button>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="' . url('admin/leads/' . $formdetails->ld_id) . '">View</a></li>
                        <li><a href="' . url('admin/leads/' . $formdetails->ld_id . '/edit') . '">Edit</a></li>
                        <li><a href="' . url('admin/leads/delete/' . $formdetails->ld_id) . '">Delete</a></li>
                      </ul>
                    </div>';
                $formstable .= '</td>';
                $formstable .= '</tr>';
                //
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;

        return view('admin.leads.fbleads')->with('data', $data);
    }

    public function webFormleads()
    {

        $data = $this->formleads('all');

        //  User Options
        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        $useroptions = "<option value='All' selected>All</option>";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";    // . "  " . $selected
        }

        $data['useroptions'] = $useroptions;

        return view('admin.leads.formleads')->with("data", $data);
    }

    public function getFormleads(Request $request)
    {
        $id = $request->input('uid');
        $data = $this->formleads($id);
        return json_encode($data);
    }

    public function formleads($id)
    {

        if ($id == 'all') {
            $formleads = Tbl_leads::where('fl_id', '>', 0)->where('active', 1)->get();
        } else {
            $formleads = Tbl_leads::where('fl_id', '>', 0)->where('uid', $id)->where('active', 1)->get();
        }

        $total = 0;
        if (count($formleads) > 0) {
            $formstable = '<table id="leadsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Email</th>';
            $formstable .= '<th>Mobile</th>';
            $formstable .= '<th>Website</th>';
            $formstable .= '<th>Created</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($formleads as $formdetails) {
                $formstable .= '<tr>';
                $formstable .= '<td>' . $formdetails->first_name . ' ' . $formdetails->last_name . '</td>';
                $formstable .= '<td>' . $formdetails->email . '</td>';
                $formstable .= '<td>' . $formdetails->mobile . '</td>';
                $formstable .= '<td>' . $formdetails->website . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';

            $total = count($formleads);
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;
        return $data;
    }

    public function setRoundRobin()
    {
        //  Get Unassigned leads
        $leads = Tbl_fb_leads::where('assigned', 0)->orderBy('fblead_id', 'desc')->get();
        $total_leads = count($leads);
        //        echo $total_leads . '<br>';
        //        exit();

        $remaining_leads = $total_leads;

        $users = User::where('quota', '>', 0)->orderby('id', 'asc')->get(['id', 'name', 'quota']);
        $total_users = count($users);

        if ($total_leads > 0) {
            if ($total_users > 0) {

                //                $data = array();

                if ($total_leads < $total_users) {
                    $users_arr = $users->toArray();
                    $random_users = array_rand($users_arr, $total_leads);
                    //                    echo json_encode($random_keys);


                    $qleads = Tbl_fb_leads::where('assigned', 0)
                        ->orderBy('fblead_id', 'desc')
                        ->limit($total_leads)
                        ->get(['fblead_id', 'full_name', 'city', 'phone_number'])
                        ->map(function ($article) {
                            $article->phone_number = str_replace('p:+', '', $article->phone_number);
                            return $article;
                        });

                    //                    echo json_encode($qleads);
                    //                    exit();

                    $leadId = array();
                    $data = array();
                    foreach ($qleads as $ql => $qlead) {   //$codes as $index => $code
                        $userDetails = $users_arr[$random_users[$ql]];

                        $leadId[] = $qlead->fblead_id;
                        $formdata = array(
                            'uid' => $userDetails['id'],
                            'first_name' => $qlead->full_name,
                            'city' => $qlead->city,
                            'mobile' => $qlead->phone_number,
                            'fblead_id' => $qlead->fblead_id,
                        );
                        $data[] = $formdata;

                        //                        echo $userDetails['name'] . ' ' . 1 . ' ' . 'Leads assigned successfully' . '<br>';
                    }

                    //                    echo json_encode($data);
                    //                    exit();
                    $qleadRes = Tbl_leads::insert($data);
                    if ($qleadRes) {
                        Tbl_fb_leads::whereIn('fblead_id', $leadId)->update(array('assigned' => 1));
                    }

                    return 'Leads assigned successfully. Leads : ' . $total_leads . ' Users : ' . $total_users;
                } else {


                    foreach ($users as $formdetails) {
                        $assigned = 0;
                        if ((int) $formdetails->quota > 0) {
                            $assigned = round(($formdetails->quota / 100) * $total_leads);
                            $remaining_leads = (int) $remaining_leads - (int) $assigned;

                            $qleads = Tbl_fb_leads::where('assigned', 0)
                                ->orderBy('fblead_id', 'desc')
                                ->limit($assigned)
                                ->get(['fblead_id', 'full_name', 'city', 'phone_number'])
                                ->map(function ($article) {
                                    $article->phone_number = str_replace('p:+', '', $article->phone_number);
                                    return $article;
                                });

                            $leadId = array();
                            $data = array();
                            foreach ($qleads as $qlead) {
                                $leadId[] = $qlead->fblead_id;
                                $formdata = array(
                                    'uid' => $formdetails->id,
                                    'first_name' => $qlead->full_name,
                                    'city' => $qlead->city,
                                    'mobile' => $qlead->phone_number,
                                    'fblead_id' => $qlead->fblead_id,
                                );
                                $data[] = $formdata;
                            }

                            $qleadRes = Tbl_leads::insert($data);
                            if ($qleadRes) {
                                Tbl_fb_leads::whereIn('fblead_id', $leadId)->update(array('assigned' => 1));
                            }
                            //                            return $formdetails->name . ' ' . $assigned . ' ' . 'Leads assigned successfully' . '<br>';
                        }
                    }

                    return 'Leads assigned successfully. Leads : ' . $total_leads . ' Users : ' . $total_users;
                }
            } else {
                return 'No Users available';
            }
        } else {
            return 'No leads available';
        }
    }

    public function getFbleadsbyForm(Request $request)
    {
        //        echo json_encode($request->input());
        //        exit();
        $formId = $request->input('formId');


        //  Users Option tag
        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        $useroptions = "<option value='0'>Select...</option>";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";    // . "  " . $selected
        }



        //  Get Unassigned leads
        $leads = Tbl_fb_leads::where('form_id', $formId)->where('assigned', 0)->orderBy('fblead_id', 'desc')->get();   //Tbl_leads
        $total = count($leads);
        if ($total > 0) {
            $formstable = '<table id="leadsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th width="2"><input type="checkbox" id="selectAll"></th>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>City</th>';
            $formstable .= '<th>Mobile</th>';
            //            $formstable .= '<th>Status</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($leads as $formdetails) {
                $formstable .= '<tr>';
                $formstable .= '<td>
				<div class="custom-control custom-checkbox">
			  <input type="checkbox" class="checkAll custom-control-input" id="' . $formdetails->fblead_id . '">
			  <label class="custom-control-label" for="' . $formdetails->fblead_id . '"></label>
				</div>
				</td>';
                $formstable .= '<td><a href="#">' . substr($formdetails->full_name, 0, 30) . '</a>&nbsp;</td>';
                $formstable .= '<td>' . $formdetails->city . '</td>';
                $formstable .= '<td>' . $formdetails->phone_number . '</td>';
                //                $formstable .= '<td>' . (($formdetails->assigned == 1) ? 'Assigned' : 'Not Assigned') . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<select id="user' . $formdetails->fblead_id . '" name="user' . $formdetails->fblead_id . '" onchange="return assignUser(\'user' . $formdetails->fblead_id . '\',' . $formdetails->fblead_id . ');">';
                $formstable .= $useroptions;
                $formstable .= '</select>';
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

        return json_encode($data);
    }


    public function getOldDate()
    {
        // $uid = Auth::user()->id;
        $oldDate = Tbl_leads::orderBy('created_at', 'asc')->first();
        // echo json_encode($oldDate);
        $upDate = '';
        if ($oldDate != '') {
            $upDate = date('Y-m-d', strtotime($oldDate->created_at));
        }
        return $upDate;
    }

    public function getLatestDate()
    {
        // $uid = Auth::user()->id;
        $oldDate = Tbl_leads::orderBy('created_at', 'desc')->first();
        // echo json_encode($oldDate);
        $upDate = '';
        if ($oldDate != '') {
            $upDate = date('Y-m-d', strtotime($oldDate->created_at));
        }
        return $upDate;
    }


    public function getleadsdummy($uid, $status, $formid)
    {



        //        SELECT ld.`ld_id`,ld.`first_name`,ld.`last_name`,ld.`fblead_id` FROM `tbl_leads` as ld INNER JOIN tbl_fb_leads as fbld ON ld.`fblead_id`=fbld.`fblead_id` WHERE fbld.`form_id` != ''

        $leads = '';
        if (($uid > 0) && ($status == 'All') && ($formid == "All")) {
            //            echo $uid . ' ' . $status . ' ' . $formid;
            //            echo '<br>';
            $leads = Tbl_leads::select("tbl_leads.ld_id", "tbl_leads.first_name", "tbl_leads.last_name", "tbl_leads.fblead_id")
                ->join("tbl_fb_leads", "tbl_fb_leads.fblead_id", "=", "tbl_leads.fblead_id")
                ->where('tbl_leads.uid', $uid)
                ->where('tbl_leads.active', 1)
                ->where('tbl_fb_leads.form_id', '!=', '')
                ->with('Tbl_leadstatus')
                ->orderBy('tbl_leads.ld_id', 'desc')
                ->get();
        }

        if (($uid > 0) && ($status == 'All') && ($formid != "All")) {
            //            echo $uid . ' ' . $status . ' ' . $formid;
            //            echo '<br>';
            $leads = Tbl_leads::select("tbl_leads.ld_id", "tbl_leads.first_name", "tbl_leads.last_name", "tbl_leads.fblead_id")
                ->join("tbl_fb_leads", "tbl_fb_leads.fblead_id", "=", "tbl_leads.fblead_id")
                ->where('tbl_leads.uid', $uid)
                ->where('tbl_leads.active', 1)
                ->where('tbl_fb_leads.form_id', '=', $formid)
                ->with('Tbl_leadstatus')
                ->orderBy('tbl_leads.ld_id', 'desc')
                ->get();
        }

        if (($uid > 0) && ($status > 0) && ($formid == "All")) {
            $leads = Tbl_leads::select("tbl_leads.ld_id", "tbl_leads.first_name", "tbl_leads.last_name", "tbl_leads.fblead_id")
                ->join("tbl_fb_leads", "tbl_fb_leads.fblead_id", "=", "tbl_leads.fblead_id")
                ->where('tbl_leads.uid', $uid)
                ->where('tbl_leads.ldstatus_id', $status)
                ->where('tbl_leads.active', 1)
                ->where('tbl_fb_leads.form_id', '!=', '')
                ->with('Tbl_leadstatus')
                ->orderBy('tbl_leads.ld_id', 'desc')
                ->get();
        }

        if (($uid > 0) && ($status > 0) && ($formid != "All")) {
            $leads = Tbl_leads::select("tbl_leads.ld_id", "tbl_leads.first_name", "tbl_leads.last_name", "tbl_leads.fblead_id")
                ->join("tbl_fb_leads", "tbl_fb_leads.fblead_id", "=", "tbl_leads.fblead_id")
                ->where('tbl_leads.uid', $uid)
                ->where('tbl_leads.ldstatus_id', $status)
                ->where('tbl_leads.active', 1)
                ->where('tbl_fb_leads.form_id', '=', $formid)
                ->with('Tbl_leadstatus')
                ->orderBy('tbl_leads.ld_id', 'desc')
                ->get();
        }

        echo json_encode($leads);
    }

    public function setRoundRobintoGroupUsers()
    {
        $prforms = Tbl_products::join('tbl_products_forms', 'tbl_products_forms.pro_id', '=', 'tbl_products.pro_id')
            ->join('tbl_group_products', 'tbl_group_products.pro_id', '=', 'tbl_products.pro_id')
            ->get(['tbl_products.pro_id', 'tbl_products.name', 'tbl_products_forms.form_id', 'tbl_group_products.gid']);
        //        echo json_encode($prforms);
        //        exit();

        if (count($prforms) > 0) {
            foreach ($prforms as $prform) {

                $gusers = Tbl_group_users::where('gid', $prform->gid)->where('quota', '>', 0)->get(['uid', 'quota']);
                $leads = Tbl_fb_leads::where('assigned', 0)->where('form_id', $prform->form_id)->get();

                $total_users = count($gusers);
                $total_leads = count($leads);
                $remaining_leads = $total_leads;
                //                echo json_encode($gusers);
                //                echo " FormId : " . $prform->form_id . " Users : " . $total_users . " Leads : " . $total_leads . "<br>";
                //                exit();
                if (($total_leads > 0) && ($total_users > 0)) {
                    if ($total_users > $total_leads) {
                        $users_arr = $gusers->toArray();
                        $random_users = array();
                        if ($total_leads > 1) {
                            $random_users = array_rand($users_arr, $total_leads);
                        } else {
                            $rusers = array_rand($users_arr, $total_leads);
                            $random_users[] = $rusers;
                        }


                        //                        echo $random_users;
                        //                        echo json_encode($random_users);
                        //                        exit();

                        $qleads = Tbl_fb_leads::where('assigned', 0)->where('form_id', $prform->form_id)
                            ->orderBy('fblead_id', 'desc')
                            ->limit($total_leads)
                            ->get(['fblead_id', 'full_name', 'city', 'phone_number'])
                            ->map(function ($article) {
                                $article->phone_number = str_replace('p:+', '', $article->phone_number);
                                return $article;
                            });

                        //                        echo json_encode($qleads);
                        //                        exit();

                        $leadId = array();
                        $data = array();
                        foreach ($qleads as $ql => $qlead) {
                            $userDetails = $users_arr[$random_users[$ql]];

                            $leadId[] = $qlead->fblead_id;
                            $formdata = array(
                                'uid' => $userDetails['uid'],
                                'first_name' => $qlead->full_name,
                                'city' => $qlead->city,
                                'mobile' => $qlead->phone_number,
                                'fblead_id' => $qlead->fblead_id,
                            );
                            $data[] = $formdata;
                        }
                        //                        echo 'If';
                        //                        echo json_encode($data);
                        //                        exit();
                        $qleadRes = Tbl_leads::insert($data);
                        if ($qleadRes) {
                            Tbl_fb_leads::whereIn('fblead_id', $leadId)->update(array('assigned' => 1));
                        }
                        return 'Leads assigned successfully' . '<br>';
                    } else {
                        //                        echo "Under Development";

                        $total_data = array();
                        foreach ($gusers as $formdetails) {
                            $assigned = 0;
                            if ((int) $formdetails->quota > 0) {
                                $assigned = round(($formdetails->quota / 100) * $total_leads);
                                $remaining_leads = (int) $remaining_leads - (int) $assigned;

                                $qleads = Tbl_fb_leads::where('assigned', 0)->where('form_id', $prform->form_id)
                                    ->orderBy('fblead_id', 'desc')
                                    ->limit($assigned)
                                    ->get(['fblead_id', 'full_name', 'city', 'phone_number'])
                                    ->map(function ($article) {
                                        $article->phone_number = str_replace('p:+', '', $article->phone_number);
                                        return $article;
                                    });

                                $leadId = array();
                                $data = array();
                                foreach ($qleads as $qlead) {
                                    $leadId[] = $qlead->fblead_id;
                                    $formdata = array(
                                        'uid' => $formdetails->uid,
                                        'first_name' => $qlead->full_name,
                                        'city' => $qlead->city,
                                        'mobile' => $qlead->phone_number,
                                        'fblead_id' => $qlead->fblead_id,
                                    );
                                    $data[] = $formdata;
                                    $total_data[] = $formdata;
                                }

                                //                                echo json_encode($data);
                                //                                exit();
                                $qleadRes = Tbl_leads::insert($data);
                                if ($qleadRes) {
                                    Tbl_fb_leads::whereIn('fblead_id', $leadId)->update(array('assigned' => 1));
                                }
                                //                                return 'Leads assigned successfully' . '<br>';
                            }
                        }
                        return 'Leads assigned successfully' . '<br>';
                        //                        echo json_encode($total_data);
                    }
                }
            }
        }
    }


    public function import($type)
    {
        $users = User::orderby('id', 'asc')->get(['id', 'name']);

        $useroptions = "<option value=''>Select User...</option>";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";
        }
        $data['useroptions'] = $useroptions;

        return view('admin.leads.import')->with('data', $data);
    }

    public function importData(Request $request)
    {

        // echo json_encode($request->input());
        // exit();

        // $uid = Auth::user()->id;
        $uid = $request->input('selectUser');

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
                return redirect('admin/leads/import/csv')->with('error', 'Please upload .csv only.');
            }
            //-------------Image Validation----------------------------------

            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $path = $request->file('importFile')->getRealPath();

            $res = Excel::import(new LeadsImport($uid), request()->file('importFile'));

            if ($res) {
                echo 'Success';
                return redirect('admin/leads')->with('success', 'Uploaded successfully');
            } else {
                // echo 'Failed';
                return redirect('admin/leads/import/csv')->with('error', "Error ocurred. Try again later..!");
            }
        } else {
            return redirect('admin/leads/import/csv')->with('error', 'Please upload file.');
        }
    }

    public function export($type)
    {
        $users = User::orderby('id', 'asc')->get(['id', 'name']);

        $useroptions = "<option value=''>Select User...</option>";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";
        }
        $data['useroptions'] = $useroptions;

        return view('admin.leads.export')->with('data', $data);
    }

    public function exportData(Request $request)
    {
        // echo json_encode($request->input());
        // exit();

        // $uid = Auth::user()->id;
        $uid = $request->input('selectUser');
        return Excel::download(new LeadsExport($uid), 'accounts.xlsx');

        // if ($res) {
        //     // echo 'Success';
        //     return redirect('admin/accounts/export/csv')->with('success', 'Downloaded successfully');
        // } else {
        //     // echo 'Failed';
        //     return redirect('admin/accounts/export/csv')->with('error', "Error ocurred. Try again later..!");
        // }
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
            $start = date('d-m-Y', strtotime($start));
            $end = date('d-m-Y', strtotime($end));

            $timer = "From:" . $start . ' To:' . $end;
        }

        return $timer;
    }

    public function getProductLeads()
    {
        // $user = Auth::user()->id;
        // echo $user;
        // exit();
        // $user_type = 'All';

        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        $uid = 'All';
        $useroptions = "<option value='All' selected>All</option>";

        $admins = Admin::orderby('id', 'asc')->get(['id', 'name']);
        foreach ($admins as $admin) {
            $useroptions .= "<option value=" . $admin->id . '-1'  . ">" . $admin->name . "</option>";   // " . $selected . "
        }

        // $useroptions .= "<option value='" . Auth::user()->id . '-1' . "' >" . Auth::user()->name . "</option>";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . '-2'  . ">" . $userdetails->name . "</option>";   // " . $selected . "
        }
        $data['useroptions'] = $useroptions;

        $uid = 'All';
        $user_type = 0;

        // $orders = $this->getCartOrders($uid, $user_type);
        $orders = $this->getProductLeadsList($uid);
        $data['orders'] = $orders;

        // echo

        return view('admin.leads.proleads')->with('data', $data);
    }


    public function getProductLeadsList($uid)
    {

        $user = User::find($uid);

        // $cr_id = $user->cr_id;

        // $currency = currency::find($cr_id);

        $leadstatuslist = Tbl_leadstatus::all();

        //-----------------------------------------
        $query = DB::table('tbl_leads')->where('tbl_leads.active', 1)->where('tbl_leads.leadtype', 2);
        if (($uid > 0) && ($uid != "All")) {
            $query->where('tbl_leads.uid', $uid);
        }
        $query->leftJoin('tbl_leadstatus', 'tbl_leads.ldstatus_id', '=', 'tbl_leadstatus.ldstatus_id');
        $query->leftJoin('tbl_leadsource', 'tbl_leads.ldsrc_id', '=', 'tbl_leadsource.ldsrc_id');
        // $query->leftJoin('tbl_accounts', 'tbl_leads.acc_id', '=', 'tbl_accounts.acc_id');
        $query->leftJoin('tbl_products', 'tbl_leads.pro_id', '=', 'tbl_products.pro_id');
        $query->orderBy('tbl_leads.ld_id', 'desc');
        $query->select(
            'tbl_leads.*',
            'tbl_leadstatus.status as leadstatus',
            'tbl_leadsource.leadsource as leadsource',
            'tbl_products.name as product'
        );

        // 'tbl_leadstatus.status as leadstatus',
        // 'tbl_leadsource.leadsource as leadsource',
        // 'tbl_accounts.name as account',

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
            // $formstable .= '<th>Company</th>';
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


                $leadimage = ($formdetails->picture != '') ? $formdetails->picture : '/uploads/default/leads.png';

                $formstable .= '<tr>';
                $formstable .= '<td><div class="custom-control custom-checkbox"><input type="checkbox" class="checkAll custom-control-input" id="' . $formdetails->ld_id . '"><label class="custom-control-label" for="' . $formdetails->ld_id . '"></label></div></td>';
                $formstable .= '<td class="table-title">';
                $formstable .= '<img src="' . url($leadimage) . '" class="avatar"> ';
                $formstable .= '<h6><a href="' . url('admin/leads/product/' . $formdetails->ld_id) . '">' . substr($formdetails->first_name . ' ' . $formdetails->last_name, 0, 25) . '</a><h6>';
                // $formstable .= '<h6><div class="t-email"><a class="text-muted" href="' . url('mails/mailsend/leads/' . $formdetails->ld_id) . '">' . $formdetails->email . '</a></h6></div><div class="t-mob text-muted">' . $formdetails->mobile . '</div>';
                $formstable .= $customer_status;
                $formstable .= '</td>';
                // $formstable .= '<td>' . $currency->html_code . ' ' . $dealstages . '</td>';
                // $formstable .= '<td>' . $formdetails->designation  . '</td>';
                $formstable .= '<td>' . $formdetails->email . '</td>';
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
                $formstable .= '<td>' . $formdetails->leadsource  . '</td>'; //$leadsource
                // $formstable .= '<td>' . $formdetails->account  . '</td>';    //(($formdetails->tbl_accounts != '') ? $formdetails->tbl_accounts->name : '')
                // $formstable .= '<td>' . $formdetails->company . '</td>';
                $formstable .= '<td>' . $formdetails->product . '</td>';    //(($formdetails->tbl_products != '') ? $formdetails->tbl_products->name : '')
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '<td><a class="badge badge-success" href="' . url('admin/leads/adddeal/' . $formdetails->ld_id) . '">Add Deal</a></td>';
                $formstable .= '<td><a class="badge badge-success" href="' . url('admin/leads/addcustomer/' . $formdetails->ld_id) . '">Add as Customer</a></td>';
                $formstable .= '<td>';
                $formstable .= '
                    <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <a class="dropdown-item text-default text-btn-space" href="' . url('admin/leads/product/edit/' . $formdetails->ld_id) . '">Edit</a>
                    <a class="dropdown-item text-default text-btn-space" href="' . url('admin/leads/product/delete/' . $formdetails->ld_id) . '">Delete</a>
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

    public function getCartOrders($uid, $user_type)
    {
        $query = DB::table('tbl_cart_orders');
        $query->where('tbl_cart_orders.active', 1);
        $query->leftJoin('tbl_products', 'tbl_cart_orders.pro_id', '=', 'tbl_products.pro_id');
        $query->leftJoin('tbl_post_order_stage', 'tbl_cart_orders.pos_id', '=', 'tbl_post_order_stage.pos_id');
        $query->leftJoin('tbl_countries', 'tbl_cart_orders.country', '=', 'tbl_countries.id');
        $query->leftJoin('tbl_states', 'tbl_cart_orders.state', '=', 'tbl_states.id');
        if ($uid != 'All') {
            $query->where('tbl_products.uid', $uid);
        }

        if ($user_type > 0) {
            $query->where('tbl_products.user_type', $user_type);
        }
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


                if ($formdetails->user_type == 1) {
                    $userid = $formdetails->userid;
                    $user = Admin::with('currency')->find($userid);
                } else {
                    $userid = $formdetails->userid;
                    $user = User::with('currency')->find($userid);
                }

                // echo json_encode($user);
                // exit();


                $formstable .= '<tr>';
                $formstable .= '<td><input type="checkbox" class="checkAll" id="' . $formdetails->coid . '"></td>';
                //  ' . url('leads/order/' . $formdetails->coid) . '
                $formstable .= '<td><a href="' . url('admin/leads/product/' . $formdetails->coid) . '">' . $formdetails->name . '</a></td>';
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
                    <a class="dropdown-item text-default text-btn-space" href="' . url('admin/ecommerce/' . $formdetails->coid) . '/edit">Edit</a>
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
        // $cart_order = Tbl_cart_orders::with('Tbl_countries')->with('Tbl_states')->find($id);
        // $data['order'] = $cart_order;
        // // echo json_encode($cart_order);

        // $pro_id = $cart_order->pro_id;

        // $product = $this->getProductDetails($pro_id);
        // $data['product'] = $product['product'];
        // $data['user'] = $product['user'];

        // echo json_encode($data);
        // exit();

        $leads = $this->getLeadDetails($id);

        $leadarr = $leads->toArray();

        $data['leadarr'] = $leadarr;

        $pro_id = $leads->pro_id;
        $product = $this->getProductDetails($pro_id);
        $data['product'] = $product['product'];
        $data['user'] = $product['user'];

        // return view('auth.leads.showprolead')->with('data', $data);

        return view('admin.leads.showprolead')->with('data', $data);
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

        return view('admin.leads.showconsumer')->with('data', $data);
    }

    public function getProductLeadsAjax(Request $request)
    {
        $uid = $request->input('uid');
        if ($uid == 'All') {
            $uid = 'All';
            $user_type = 0;
        } else {
            $user = explode('-', $uid);
            $uid = $user[0];
            $user_type = $user[1];
            // echo json_encode($request->input());
            // exit();
        }

        // $orders = $this->getCartOrders($uid, $user_type);
        $orders = $this->getProductLeadsList($uid);

        echo json_encode($orders);
    }


    // public function productLeadsDeleteAll(Request $request)
    // {
    //     $ids = $request->input('id');
    //     // return json_encode($ids);
    //     return Tbl_cart_orders::whereIn('coid', $ids)->update(array('active' => 0));
    // }

    // public function productLeadsRestoreAll(Request $request)
    // {
    //     $ids = $request->input('id');
    //     return Tbl_cart_orders::whereIn('coid', $ids)->update(array('active' => 1));
    // }


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

    public function editProductLead($id)
    {
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
        $products = Tbl_products::where('active', 1)->get();
        $productoptions = '<option value="">Select Product...</option>';
        foreach ($products as $product) {
            $productselected = (($leadarr['pro_id'] > 0) && ($product->pro_id == $leadarr['pro_id'])) ? 'selected' : '';
            $productoptions .= '<option value="' . $product->pro_id . '" ' . $productselected . '>' . $product->name . '</option>';
        }
        $data['productoptions'] = $productoptions;

        // echo json_encode($data);
        return view('admin.leads.editprolead')->with('data', $data);
    }

    public function updateProductLead(Request $request, $id)
    {
        // echo $id;
        // echo json_encode($request->input());
        // exit();
        //        $formdata = array();

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
                return redirect('admin/leads/product/edit/' . $id)->with('error', 'Please upload jpg, png and giff images only.');
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
            return redirect('admin/leads/product/' . $id)->with('success', 'Lead Updated Successfully...!');
        } else {
            return redirect('admin/leads/product/' . $id)->with('error', 'Error occurred. Please try again later...! ');
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

        $account = Tbl_leads::where('ld_id', $id)->update(array('active' => 0));
        if ($account) {
            return redirect('admin/leads/getproductleads/list')->with('success', 'Deleted Successfully...!');
        } else {
            return redirect('admin/leads/getproductleads/list')->with('error', 'Error occurred. Please try again later...!');
        }
    }

    public function productLeadsRestoreAll(Request $request)
    {
        $ids = $request->input('id');
        return Tbl_leads::whereIn('ld_id', $ids)->update(array('active' => 1));
    }
}

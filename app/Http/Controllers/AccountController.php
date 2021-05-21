<?php

namespace App\Http\Controllers;

use Response;
use App\Company;
use App\Tbl_deals;
use App\Tbl_leads;
use App\Tbl_states;
use App\Tbl_Accounts;

use App\Tbl_contacts;
use App\Tbl_countries;
use App\Tbl_leadsource;
use App\Tbl_leadstatus;
use App\Tbl_accounttypes;
use App\Tbl_industrytypes;
use Illuminate\Http\Request;
use App\Exports\AccountsExport;
use App\Imports\AccountsImport;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
// use Excel;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('test:accounts', ['only' => ['index', 'show', 'create', 'store', 'edit', 'update', 'delete', 'deleteAll', 'deleteAccount', 'import', 'importData', 'export', 'exportData']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getAccountslistAjax(Request $request)
    {
        $uid = Auth::user()->id;
        $data = $this->getAccounts($uid);
        return json_encode($data);
    }

    public function getAccounts($uid)
    {
        $accounts = Tbl_Accounts::with(['Tbl_industrytypes','haveCompany','Tbl_accounttypes'])
            ->where('uid', $uid)->where('active', 1)->orderBy('acc_id', 'desc')->paginate(10);

        $total = count($accounts);
        if ($total > 0) {
            $formstable = '<div class="table-responsive"><table id="accountsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            //            $formstable .= '<th width="2"></th>';   
            $formstable .= '<th>&nbsp;</th>';
            $formstable .= '<th width="230">Account Name</th>';
            $formstable .= '<th>Company</th>';
            $formstable .= '<th>Industry</th>';
            $formstable .= '<th>Website</th>';
            $formstable .= '<th>Account Type</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($accounts as $formdetails) {

                $formstable .= '<tr>';
                $formstable .= '<td width="10"><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input checkAll" id="' . $formdetails->acc_id . '"><label class="custom-control-label" for="' . $formdetails->acc_id . '"></label></div></td>';
                $formstable .= '<td class="table-title">';
                $formstable .= '<img src="' . $formdetails->profileLogo() . '" class="avatar"> ';
                $formstable .= '<h6><a href="' . url('accounts/' . $formdetails->acc_id) . '">' . $formdetails->name . '</a>';
                $formstable .= '</h6><div class="t-email"><a class="text-muted" href="' . url('mails/mailsend/accounts/' . $formdetails->acc_id) . '">' . $formdetails->email . '</a></div><div class="t-mob text-muted">' . $formdetails->mobile . '</div></td>';
                $formstable .= '<td>' . $formdetails->haveCompany->c_name . '</td>';
                $industrytype = ($formdetails->tbl_industrytypes != null) ? $formdetails->tbl_industrytypes->type : '';
                $formstable .= '<td>' . $industrytype . '</td>';
                $accounttype = ($formdetails->tbl_accounttypes != null) ? $formdetails->tbl_accounttypes->account_type : '';
                $formstable .= '<td><a class="text-muted" href="' . $formdetails->website . '" target="_blank">' . $formdetails->website . '</a></td>';
                $formstable .= '<td>' . $accounttype . '</td>';
                $formstable .= '<td>' . date('m-d-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                      <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                      </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item text-default text-btn-space" href="' . url('accounts/' . $formdetails->acc_id . '/edit') . '">Edit</a>
                        <a class="dropdown-item text-default text-btn-space" href="' . url('accounts/delete/' . $formdetails->acc_id) . '">Delete</a>
                      </div>
                    </div>';

                //                <div class="btn-group">
                //                          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                //                            <span class="caret"></span>
                //                          </button>
                //                          <div class="dropdown-menu">
                //                            <a class="dropdown-item" href="#">Dropdown link</a>
                //                            <a class="dropdown-item" href="#">Dropdown link</a>
                //                          </div>
                //                        </div>


                $formstable .= '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>
            <div class="m-2">'.$accounts->links().'</div>
            </div>';

            $total = count($accounts);
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;


        return $data;
    }

    public function index()
    {
        $uid = Auth::user()->id;
        $data = $this->getAccounts($uid);
        // echo json_encode($data);
        // exit();

        // Add Account drop downs

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

        $accounttype = Tbl_accounttypes::all();
        //        echo json_encode($accounttype);
        $accounttypeoptions = "<option value='0'>Select Account Type</option>";
        if (count($accounttype) > 0) {
            foreach ($accounttype as $type) {
                $accounttypeoptions .= "<option value='" . $type->actype_id . "'>" . $type->account_type . "</option>";
            }
        }
        $data['accounttypeoptions'] = $accounttypeoptions;

        return view('auth.accounts.index')->with("data", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        if ($user->can('create', Tbl_Accounts::class)) {

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

            $accounttype = Tbl_accounttypes::all();
            //        echo json_encode($accounttype);
            $accounttypeoptions = "<option value='0'>Select Account Type</option>";
            if (count($accounttype) > 0) {
                foreach ($accounttype as $type) {
                    $accounttypeoptions .= "<option value='" . $type->actype_id . "'>" . $type->account_type . "</option>";
                }
            }
            $data['accounttypeoptions'] = $accounttypeoptions;

            $companys = $this->getCompanyList();
            $companyoption = '<option value="">Select Company...</option>';
            foreach ($companys as $company) {
                $companyoption .= '<option value="' . $company->id . '">' . $company->c_name . '</option>';
            }
            // $companyoption .= '<option value="11">Dummy 11</option>';
            // $companyoption .= '<option value="12">Dummy 12</option>';
            $data['companyoption'] = $companyoption;

            return view('auth.accounts.create')->with('data', $data);
        } else {
            return redirect('/accounts');
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

        // echo ($request->input('company') != '') ? 'success' : 'error';
        // return json_encode($request->input());
        // exit();

        // Form Submission

        $this->validate($request, [
            'name' => 'required|max:255',
            // 'company' => 'required',
            // 'email' => 'email|max:255|unique:tbl_accounts',
        ]);

        // exit();

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
                return redirect('/accounts/create')->with('error', 'Please upload jpg, png and giff images only.');
            }
            //-------------Image Validation----------------------------------
            //            $file = $request->file('userpicture');
            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $file->move('uploads/accounts/', $name);  //public_path().
            $filename = '/uploads/accounts/' . $name;
        }

        $email = $request->input('email');
        if (($email != '') && (!$this->valid_email($email))) {
            return redirect('/accounts/create')->with('error', 'Please check given email address');
        }

        $website = $request->input('website');

        if (strpos($website, 'http://') === false) {
            $website = 'http://' . $website;
        }

        $companies = ($request->input('company') != '') ? implode(",", $request->input('company')) : '';

        $formdata = array(
            'uid' => Auth::user()->id,
            'name' => $request->input('name'),
            'email' => $email,
            'picture' => $filename,
            'mobile' => $request->input('mobile'),
            'phone' => $request->input('phone'),
            'actype_id' => $request->input('accounttype'),
            'intype_id' => $request->input('industrytype'),
            'description' => $request->input('notes'),
            'website' => $website,
            'country' => $request->input('country'),
            'state' => $request->input('state'),
            'city' => $request->input('city'),
            'street' => $request->input('street'),
            'zip' => $request->input('zip'),
            'company' => $companies,  //  $request->input('company')
            'employees' => ($request->input('employees') > 0) ? $request->input('employees') : 1,
        );

        //        echo json_encode($formdata);
        //        $accounts = Tbl_Accounts::create($formdata);

        $accounts = $this->addAccount($formdata);
        if ($accounts->acc_id > 0) {
            return redirect('/accounts')->with('success', 'Account Created Successfully');
        } else {
            return redirect('/accounts')->with('error', 'Error occurred. Please try again...!');
        }
    }

    public function addAccount($formdata)
    {
        return Tbl_Accounts::create($formdata);
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
        $account  = Tbl_Accounts::find($id);

        if ($user->can('view', $account)) {

            $accounts = $this->getAccountDetails($id);
            $data['accounts'] = $accounts;
            $data['editLink'] = url('accounts') . '/' . $id . '/edit';

            $c_total = 0;
            $c_table = 'No records available';
            if ($accounts['company'] != '') {
                $acompany  = explode(",", $accounts['company']);
                $companies =  Company::isActive()->whereIn('id', $acompany)->get();
                $c_total = count($companies);
                if ($c_total > 0) {
                    $c_table = '<table id="example1" class="table">';
                    $c_table .= '<thead>';
                    $c_table .= '<tr>';
                    $c_table .= '<th>Company</th>';
                    $c_table .= '<th>Email</th>';
                    $c_table .= '<th>Mobile</th>';
                    $c_table .= '<th>Date</th>';
                    $c_table .= '</tr>';
                    $c_table .= '</thead>';
                    $c_table .= '<tbody>';
                    foreach ($companies as $company) {

                        $c_logo = ($company->c_logo != '') ? $company->c_logo : '/uploads/default/leads.png';

                        $c_table .= '<tr>';
                        $c_table .= '<td class="table-title"><img src="' . url($c_logo) . '" width="30" height="24">&nbsp;<a href="#">' . $company->c_name  . '</a></td>';
                        $c_table .= '<td><a href="#">' . $company->c_email . '</a></td>';
                        $c_table .= '<td>' . $company->c_mobileNum . '</td>';
                        $c_table .= '<td>' . date('m-d-Y', strtotime($company->created_at)) . '</td>';
                        $c_table .= '</tr>';
                    }
                    $c_table .= '</tbody>';
                    $c_table .= '</table>';
                }
            }
            $data['c_total'] = $c_total;
            $data['c_table'] = $c_table;

            $leads = Tbl_leads::where('acc_id', $id)
                ->where('active', 1)
                ->orderBy('ld_id', 'desc')
                ->get();

            $total = count($leads);
            if ($total > 0) {
                $formstable = '<table id="leadsTable" class="table">';
                $formstable .= '<thead>';
                $formstable .= '<tr>';
                $formstable .= '<th>Name</th>';
                $formstable .= '<th>Email</th>';
                $formstable .= '<th>Mobile</th>';
                $formstable .= '<th>Lead Status</th>';
                $formstable .= '<th>Lead Source</th>';
                $formstable .= '<th>Company</th>';
                $formstable .= '<th>Date</th>';
                $formstable .= '</tr>';
                $formstable .= '</thead>';
                $formstable .= '<tbody>';
                foreach ($leads as $formdetails) {
                    $customer_status = '';
                    $customer = Tbl_deals::where('ld_id', $formdetails->ld_id)->where('deal_status', 1)->first();
                    if ($customer != null) {
                        $customer_status = '<span class="label label-success">Customer</span>';
                    }

                    $leadstatus = 'Add Lead Status';
                    $leadstatus_id = 0;

                    if ($formdetails->tbl_leadstatus != null) {
                        $leadstatus_id = $formdetails->tbl_leadstatus->ldstatus_id;
                        $leadstatus = $formdetails->tbl_leadstatus->status;
                    }

                    $leadsource = ($formdetails->tbl_leadsource != null) ? $formdetails->tbl_leadsource->leadsource : '';


                    $leadimage = ($formdetails->picture != '') ? $formdetails->picture : '/uploads/default/leads.png';

                    $formstable .= '<tr>';
                    $formstable .= '<td class="table-title"><img src="' . url($leadimage) . '" width="30" height="24">&nbsp;<a href="' . url('leads/' . $formdetails->ld_id) . '">' . $formdetails->first_name . ' ' . $formdetails->last_name . '</a>&nbsp; ' . $customer_status . '</td>';
                    $formstable .= '<td><a href="' . url('mails/mailsend/leads/' . $formdetails->ld_id) . '">' . $formdetails->email . '</a></td>';
                    $formstable .= '<td>' . $formdetails->mobile . '</td>';
                    $formstable .= '<td>' . $leadstatus . '</td>';
                    $formstable .= '<td>' . $leadsource . '</td>';
                    $formstable .= '<td>' . $formdetails->company . '</td>';
                    $formstable .= '<td>' . date('m-d-Y', strtotime($formdetails->created_at)) . '</td>';
                    $formstable .= '</tr>';
                }
                $formstable .= '</tbody>';
                $formstable .= '</table>';
            } else {
                $formstable = 'No records available';
            }

            $data['total'] = $total;
            $data['table'] = $formstable;



            //        echo json_encode($data);
            return view('auth.accounts.show')->with("data", $data);
        } else {
            return redirect('/accounts');
        }
    }

    public function getAccountDetails($id)
    {
        return Tbl_Accounts::with('Tbl_industrytypes')
            ->with('Tbl_accounttypes')
            ->find($id);
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
        $account  = Tbl_Accounts::find($id);

        if (($user->can('view', $account)) && ($account != '')) {
            //        echo "<h1>Page is under development</h1>";
            $accounts = Tbl_Accounts::with('Tbl_accounttypes')
                ->with('Tbl_industrytypes')
                ->with('Tbl_countries')
                ->with('Tbl_states')
                ->find($id);

            $accountarr = $accounts->toArray();

            //        echo json_encode($accountarr);

            $data['accountarr'] = $accountarr;

            $country = Tbl_countries::all();
            $countryoptions = "<option value='0'>Select Country</option>";
            if (count($country) > 0) {
                foreach ($country as $cnt) {
                    $selected = ($cnt->id == $accountarr['country']) ? 'selected' : '';
                    $countryoptions .= "<option value='" . $cnt->id . "' " . $selected . ">" . $cnt->name . ' - ' . $cnt->sortname . "</option>";
                }
            }
            $data['countryoptions'] = $countryoptions;

            $stateoptions = "<option value='0'>Select State</option>";
            if ($accountarr['country'] > 0) {
                $states = DB::select('SELECT * FROM `tbl_states` WHERE `country_id`=' . $accountarr['country']);
                if (count($states) > 0) {
                    foreach ($states as $state) {
                        $stateselected = ($state->id == $accountarr['state']) ? 'selected' : '';
                        $stateoptions .= "<option value='" . $state->id . "' " . $stateselected . ">" . $state->name . "</option>";
                    }
                }
            }
            $data['stateoptions'] = $stateoptions;

            $industry = Tbl_industrytypes::all();
            $industryoptions = "<option value='0'>Select Industry Type</option>";
            if (count($industry) > 0) {
                foreach ($industry as $int) {
                    $industryselected = ($int->intype_id == $accountarr['intype_id']) ? 'selected' : '';
                    $industryoptions .= "<option value='" . $int->intype_id . "' " . $industryselected . ">" . $int->type . "</option>";
                }
            }
            $data['industryoptions'] = $industryoptions;

            $accounttype = Tbl_accounttypes::all();
            $accounttypeoptions = "<option value='0'>Select Account Type</option>";
            if (count($accounttype) > 0) {
                foreach ($accounttype as $type) {
                    $accounttypeselected = ($type->actype_id == $accountarr['actype_id']) ? 'selected' : '';
                    $accounttypeoptions .= "<option value='" . $type->actype_id . "' " . $accounttypeselected . ">" . $type->account_type . "</option>";
                }
            }
            $data['accounttypeoptions'] = $accounttypeoptions;

            $companies = [];
            if ($accountarr['company'] != '') {
                $companies = explode(",", $accountarr['company']);
            }

            $companys = $this->getCompanyList();
            $companyoption = '<option value="">Select Company...</option>';
            foreach ($companys as $company) {
                $selected = '';
                if ((count($companies) > 0) && in_array($company->id, $companies)) {
                    $selected = 'selected';
                }
                $companyoption .= '<option value="' . $company->id . '" ' . $selected . '>' . $company->c_name . '</option>';
            }
            // $companyoption .= '<option value="11">Dummy 11</option>';
            // $companyoption .= '<option value="12">Dummy 12</option>';
            $data['companyoption'] = $companyoption;


            //        echo json_encode($data);
            return view('auth.accounts.edit')->with("data", $data);
        } else {
            return redirect('/accounts');
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
        $user = Auth::user();
        $account  = Tbl_Accounts::find($id);
        if ($user->can('update', $account)) {

            $this->validate($request, [
                'name' => 'required|max:255',
                // 'email' => 'email|required|max:255|unique:tbl_accounts,email,' . $id . ',acc_id',
            ]);


            $email = $request->input('email');
            if (($email != '') && (!$this->valid_email($email))) {
                return redirect('accounts/' . $id . '/edit')->with('error', 'Please check given email address');
            }


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
                    return redirect('/accounts/' . $id . '/edit')->with('error', 'Please upload jpg, png and giff images only.');
                }
                //-------------Image Validation----------------------------------
                //            $file = $request->file('userpicture');
                $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
                $file->move('uploads/accounts/', $name);  //public_path().
                $filename = '/uploads/accounts/' . $name;
                //            $acccount->picture = $filename;
            }

            $formdata = $request->input();
            $formdata['picture'] = $filename;
            $companies = ($request->input('company') != '') ? implode(",", $request->input('company')) : '';
            $formdata['companies'] = $companies;

            $this->updateAccount($formdata, $id);

            return redirect('/accounts')->with('success', 'Account Updated Successfully...');
        } else {
            return redirect('/accounts');
        }
    }

    public function updateAccount($formdata, $id)
    {

        $website = $formdata['website'];

        if (strpos($website, 'http://') === false) {
            $website = 'http://' . $website;
        }

        $acccount = Tbl_Accounts::find($id);
        $acccount->name = $formdata['name'];
        $acccount->email = $formdata['email'];
        $acccount->mobile = (isset($formdata['mobile'])) ? $formdata['mobile'] : $acccount->mobile;
        $acccount->phone = (isset($formdata['phone'])) ? $formdata['phone'] : $acccount->phone;
        $acccount->actype_id = (isset($formdata['accounttype'])) ? $formdata['accounttype'] : $acccount->actype_id;
        $acccount->intype_id = (isset($formdata['industrytype'])) ? $formdata['industrytype'] : $acccount->intype_id;
        $acccount->description = (isset($formdata['notes'])) ? $formdata['notes'] : $acccount->description;
        $acccount->website = (isset($formdata['website'])) ? $website : $acccount->website;
        $acccount->country = (isset($formdata['country'])) ? $formdata['country'] : $acccount->country;
        $acccount->state = (isset($formdata['state'])) ? $formdata['state'] : $acccount->state;
        $acccount->city = (isset($formdata['city'])) ? $formdata['city'] : $acccount->city;
        $acccount->street = (isset($formdata['street'])) ? $formdata['street'] : $acccount->street;
        $acccount->zip = (isset($formdata['zip'])) ? $formdata['zip'] : $acccount->zip;
        $acccount->company = (isset($formdata['company'])) ? $formdata['company'] : $acccount->company;
        $acccount->picture = ($formdata['picture'] != '') ? $formdata['picture'] : $acccount->picture;
        $acccount->employees = ($formdata['employees'] > 0) ? $formdata['employees'] : $acccount->employees;
        $acccount->company = $formdata['companies'];
        return $acccount->save();
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

    public function deleteAccount($id)
    {
        $user = Auth::user();
        $account  = Tbl_Accounts::find($id);
        if ($user->can('view', $account)) {
            $this->delete($id);
            return redirect('/accounts')->with('success', 'Deleted Successfully...');
        } else {
            return redirect('/accounts');
        }
    }

    public function delete($id)
    {
        $account = Tbl_Accounts::find($id);
        $account->active = 0;
        return $account->save();
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->input('id');
        return Tbl_Accounts::whereIn('acc_id', $ids)->update(array('active' => 0));
    }

    public function restoreAll(Request $request)
    {
        $ids = $request->input('id');
        return Tbl_Accounts::whereIn('acc_id', $ids)->update(array('active' => 1));
    }

    public function import($type)
    {
        return view('auth.accounts.import');
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
                return redirect('/accounts/import/csv')->with('error', 'Please upload .csv only.');
            }
            //-------------Image Validation----------------------------------

            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $path = $request->file('importFile')->getRealPath();

            $res = Excel::import(new AccountsImport($uid), request()->file('importFile'));

            if ($res) {
                // echo 'Success';
                return redirect('/accounts')->with('success', 'Uploaded successfully');
            } else {
                // echo 'Failed';
                return redirect('/accounts/import/csv')->with('error', "Error ocurred. Try again later..!");
            }
        } else {
            return redirect('/accounts/import/csv')->with('error', 'Please upload file.');
        }
    }

    public function exportData($type)
    {
        $uid = Auth::user()->id;
        return Excel::download(new AccountsExport($uid), 'accounts.xlsx');
    }

    public function valid_email($str)
    {
        return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
    }

    public function getCompanyList()
    {

        $uid = Auth::user()->id;

        $accounts = Company::isActive()->where('user_id', $uid)->get();

        return $accounts;
    }
}

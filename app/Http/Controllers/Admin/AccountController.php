<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
//---------Models---------------
use App\Tbl_Accounts;
use App\Tbl_countries;
use App\Tbl_states;
use App\Tbl_accounttypes;
use App\Tbl_industrytypes;
use App\User;
use App\Tbl_leads;
use App\Tbl_contacts;
use App\Company;

use App\Imports\AccountsImport;
use App\Exports\AccountsExport;
use Maatwebsite\Excel\Facades\Excel;

class AccountController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        // |account-create|account-edit|account-delete
        $this->middleware('test:account-list', ['only' => ['index', 'show']]);
        $this->middleware('test:account-create', ['only' => ['create', 'store']]);
        $this->middleware('test:account-edit', ['only' => ['edit', 'update']]);
        $this->middleware('test:account-delete', ['only' => ['destroy', 'deleteAccount', 'deleteAll']]);
        $this->middleware('test:account-import', ['only' => ['import', 'importData']]);
        $this->middleware('test:account-export', ['only' => ['export', 'exportData']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        $uid = 'All';
        $useroptions = "<option value='All' selected>All</option>";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";   // " . $selected . "
        }
        $data['useroptions'] = $useroptions;
        $accounts = $this->getAccounts($uid);

        $data['table'] = $accounts['table'];
        $data['total'] = $accounts['total'];

        return view('admin.accounts.index')->with('data', $data);
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
        $accounts = Tbl_Accounts::with('Tbl_industrytypes')
            ->with('Tbl_accounttypes')
            ->find($id);
        //        echo json_encode($accounts);
        $data['accounts'] = $accounts;
        $data['editLink'] = url('admin/accounts') . '/' . $id . '/edit';
        //        echo json_encode($data);
        return view('admin.accounts.show')->with("data", $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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

        $user = $accounts->uid;
        $companys = $this->getCompanyList($user);
        // echo json_encode($companys);
        // exit();
        $companies = [];
        if ($accountarr['company'] != '') {
            $companies = explode(",", $accountarr['company']);
        }

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
        return view('admin.accounts.edit')->with("data", $data);
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

        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'email|required|max:255',
        ]);


        $email = $request->input('email');
        if (($email != '') && (!$this->valid_email($email))) {
            return redirect('admin/accounts/' . $id . '/edit')->with('error', 'Please check given email address');
        }

        $companies = ($request->input('company') != '') ? implode(",", $request->input('company')) : '';

        $acccount = Tbl_Accounts::find($id);
        $acccount->name = $request->input('name');
        $acccount->email = $request->input('email');
        $acccount->mobile = $request->input('mobile');
        $acccount->phone = $request->input('phone');
        $acccount->actype_id = $request->input('accounttype');
        $acccount->intype_id = $request->input('industrytype');
        $acccount->description = $request->input('notes');
        $acccount->website = $request->input('website');
        $acccount->country = $request->input('country');
        $acccount->state = $request->input('state');
        $acccount->city = $request->input('city');
        $acccount->street = $request->input('street');
        $acccount->zip = $request->input('zip');
        $acccount->company = $companies;

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
                return redirect('admin/accounts/' . $id . '/edit')->with('error', 'Please upload jpg, png and giff images only.');
            }
            //-------------Image Validation----------------------------------

            $file = $request->file('userpicture');
            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $file->move('uploads/accounts/', $name);  //public_path().
            $filename = '/uploads/accounts/' . $name;
            $acccount->picture = $filename;
        }

        $acccount->save();
        return redirect('admin/accounts')->with('success', 'Updated Successfully...');
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

    public function getAccounts($uid)
    {

        if ($uid == 'All') {
            $accounts = Tbl_Accounts::with('Tbl_industrytypes')
                ->where('active', 1)
                ->with('Tbl_accounttypes')
                ->orderBy('acc_id', 'desc')
                ->get();
        } else {
            $accounts = Tbl_Accounts::with('Tbl_industrytypes')
                ->where('active', 1)
                ->with('Tbl_accounttypes')
                ->orderBy('acc_id', 'desc')
                ->where('uid', $uid)
                ->get();
        }

        $total = count($accounts);
        if ($total > 0) {
            $formstable = '<table id="accountsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th width="2"></th>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Email</th>';
            $formstable .= '<th>Mobile</th>';
            $formstable .= '<th>Industry</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($accounts as $formdetails) {
                $formstable .= '<tr>';
                $formstable .= '<td><input type="checkbox" class="checkAll" id="' . $formdetails->acc_id . '"></td>';
                $formstable .= '<td class="table-title"><a href="' . url('admin/accounts/' . $formdetails->acc_id) . '">' . $formdetails->name . '</a>&nbsp;</td>';
                $formstable .= '<td>' . $formdetails->email . '</td>';
                $formstable .= '<td>' . $formdetails->mobile . '</td>';
                $industrytype = ($formdetails->tbl_industrytypes != null) ? $formdetails->tbl_industrytypes->type : '';
                $formstable .= '<td>' . $industrytype . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item text-default text-btn-space" href="' . url('admin/accounts/' . $formdetails->acc_id . '/edit') . '">Edit</a>
                    <a class="dropdown-item text-default text-btn-space" href="' . url('admin/accounts/delete/' . $formdetails->acc_id) . '">Delete</a>
                  </div>
                </div>';
                //<li><a class="text-default text-btn-space" href="' . url('admin/accounts/' . $formdetails->acc_id . '/edit') . '">Edit</a></li>
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

    public function deleteAccount($id)
    {
        $account = Tbl_Accounts::find($id);
        $account->active = 0;
        $account->save();

        //        //---------Leads------------------
        //        Tbl_leads::where('acc_id', $id)->update(['acc_id' => 0]);
        //
        //        //---------Contacts---------------
        //        Tbl_contacts::where('acc_id', $id)->update(['acc_id' => 0]);


        return redirect('admin/accounts')->with('success', 'Deleted Successfully...');
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->input('id');
        // return json_encode($ids);
        return Tbl_Accounts::whereIn('acc_id', $ids)->update(array('active' => 0));
    }

    public function valid_email($str)
    {
        return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
    }

    public function import($type)
    {
        $users = User::orderby('id', 'asc')->get(['id', 'name']);

        $useroptions = "<option value=''>Select User...</option>";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";
        }
        $data['useroptions'] = $useroptions;

        return view('admin.accounts.import')->with('data', $data);
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
                return redirect('admin/accounts/import/csv')->with('error', 'Please upload .csv only.');
            }
            //-------------Image Validation----------------------------------

            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $path = $request->file('importFile')->getRealPath();

            $res = Excel::import(new AccountsImport($uid), request()->file('importFile'));

            if ($res) {
                echo 'Success';
                return redirect('admin/accounts')->with('success', 'Uploaded successfully');
            } else {
                // echo 'Failed';
                return redirect('admin/accounts/import/csv')->with('error', "Error ocurred. Try again later..!");
            }
        } else {
            return redirect('admin/accounts/import/csv')->with('error', 'Please upload file.');
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

        return view('admin.accounts.export')->with('data', $data);
    }

    public function exportData(Request $request)
    {
        // echo json_encode($request->input());
        // exit();

        // $uid = Auth::user()->id;
        $uid = $request->input('selectUser');
        return Excel::download(new AccountsExport($uid), 'accounts.xlsx');

        // if ($res) {
        //     // echo 'Success';
        //     return redirect('admin/accounts/export/csv')->with('success', 'Downloaded successfully');
        // } else {
        //     // echo 'Failed';
        //     return redirect('admin/accounts/export/csv')->with('error', "Error ocurred. Try again later..!");
        // }
    }

    public function getCompanyList($uid)
    {

        $accounts = Company::isActive()->where('user_id', $uid)->get();

        return $accounts;
    }
}

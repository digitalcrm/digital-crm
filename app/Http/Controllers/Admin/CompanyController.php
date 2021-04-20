<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

//  Models
use App\Company;
use App\Tbl_Accounts;
use App\User;
use App\Admin;
use App\Tbl_accounttypes;
use App\Tbl_productcategory;
use App\Tbl_countries;
use App\Tbl_states;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        // $this->middleware('test:account-list', ['only' => ['index', 'show']]);
        // $this->middleware('test:account-create', ['only' => ['create', 'store']]);
        // $this->middleware('test:account-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('test:account-delete', ['only' => ['destroy', 'deleteAccount', 'deleteAll']]);
        // $this->middleware('test:account-import', ['only' => ['import', 'importData']]);
        // $this->middleware('test:account-export', ['only' => ['export', 'exportData']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        $uid = 'All';
        $useroptions = "<option value='All' selected>All</option>";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";   // " . $selected . "
        }
        $data['useroptions'] = $useroptions;
        $accounts = $this->getCompanies($uid);

        $data['table'] = $accounts['table'];
        $data['total'] = $accounts['total'];

        return view('admin.company.index')->with('data', $data);
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        //
        // echo $id;
        // $accounts = Company::with('Tbl_accounttypes')
        //     ->with('tbl_product_category')
        //     ->orderBy('id', 'desc')
        //     ->where('id', $id)
        //     ->first();

        // // echo json_encode($accounts);
        // // exit();

        // $data['accounts'] = $accounts;

        // $accounttype = Tbl_accounttypes::all();
        // $accounttypeoptions = "<option value='0'>Select Account Type</option>";
        // if (count($accounttype) > 0) {
        //     foreach ($accounttype as $type) {
        //         $accounttypeselected = ($type->actype_id == $accounts->actype_id) ? 'selected' : '';
        //         $accounttypeoptions .= "<option value='" . $type->actype_id . "' " . $accounttypeselected . ">" . $type->account_type . "</option>";
        //     }
        // }
        // $data['accounttypeoptions'] = $accounttypeoptions;

        // //  Product Category
        // $procategory = Tbl_productcategory::all();
        // $categoryoption = '<option value="">Select ...</option>';
        // foreach ($procategory as $category) {
        //     $cat_selected = ($category->procat_id == $accounts->category_id) ? 'selected' : '';
        //     $categoryoption .= '<option value="' . $category->procat_id . '" ' . $cat_selected . '>' . $category->category . '</option>';
        // }
        // $data['categoryoption'] = $categoryoption;

        // $country = Tbl_countries::all();
        // $countryoptions = "<option value='0'>Select Country</option>";
        // if (count($country) > 0) {
        //     foreach ($country as $cnt) {
        //         $selected = ($cnt->id == $accounts->country_id) ? 'selected' : '';
        //         $countryoptions .= "<option value='" . $cnt->id . "' " . $selected . ">" . $cnt->name . ' - ' . $cnt->sortname . "</option>";
        //     }
        // }
        // $data['countryoptions'] = $countryoptions;

        // $stateoptions = "<option value='0'>Select State</option>";
        // if ($accounts->country_id > 0) {
        //     $states = DB::select('SELECT * FROM `tbl_states` WHERE `country_id`=' . $accounts->country_id);
        //     if (count($states) > 0) {
        //         foreach ($states as $state) {
        //             $stateselected = ($state->id == $accounts->state_id) ? 'selected' : '';
        //             $stateoptions .= "<option value='" . $state->id . "' " . $stateselected . ">" . $state->name . "</option>";
        //         }
        //     }
        // }
        // $data['stateoptions'] = $stateoptions;

        // return view('admin.company.edit')->with("data", $data);

        return view('admin.company.edit', compact('company'));
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
        // $this->validate($request, [
        //     'personal_name' => 'required|max:25',
        //     'c_email' => 'email',
        //     'position' => 'nullable|max:15',
        //     'c_mobileNum' => 'required|digits:10',
        //     'c_whatsappNum' => 'required|digits:10',
        //     'c_name' => 'required|max:55|unique:companies,c_name,' . $id,
        //     'category_id' => 'required|not_in:0', //business type
        //     'actype_id' => 'required|not_in:0', // company type
        //     'c_bio' => 'required|min:15',
        //     'c_cover' => 'nullable|image|max:1024',
        //     'c_webUrl' => 'nullable|',
        //     'c_gstNumber' => 'required|digits:15',
        //     'employees' => 'required|min:1',
        //     'google_map_url' => 'nullable|max:500',
        //     'yt_video_link' => 'nullable',
        //     'fb_link' => 'nullable',
        //     'tw_link' => 'nullable',
        //     'yt_link' => 'nullable',
        //     'linkedin_link' => '',
        //     'country_id' => 'required|not_in:0',
        //     'state_id' => 'required|not_in:0',
        //     'address' => 'required|max:255',
        //     'city' => 'required|max:45',
        //     'zipcode' => 'required|digits:6',
        //     'showLive' => 'accepted',
        //     'termsAccept' => 'accepted',
        // ]);

        // if ($request->hasfile('picture')) {

        //     //-------------Image Validation----------------------------------
        //     $file = $request->file('picture');
        //     // Build the input for validation
        //     $fileArray = array('picture' => $file);

        //     // Tell the validator that this file should be an image
        //     $rules = array(
        //         'picture' => 'mimes:jpeg,jpg,png,gif|max:10000' // max 10000kb
        //     );

        //     // Now pass the input and rules into the validator
        //     $validator = Validator::make($fileArray, $rules);

        //     // Check to see if validation fails or passes
        //     if ($validator->fails()) {
        //         return redirect('admin/products/' . $id . '/edit')->with('error', 'Please upload jpg, png and giff images only.');
        //     }
        //     //-------------Image Validation----------------------------------
        //     //            $file = $request->file('picture');
        //     $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
        //     $file->move('uploads/products/', $name);  //public_path().
        //     $filename = '/uploads/products/' . $name;
        // }
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

    public function getCompanies($uid)
    {

        if ($uid == 'All') {
            $accounts = Company::where('isActive', 1)
                ->with('Tbl_accounttypes')
                ->with('tbl_product_category')
                ->orderBy('id', 'desc')
                ->latest()
                ->get();
        } else {
            $accounts = Company::where('isActive', 1)
                ->with('Tbl_accounttypes')
                ->with('tbl_product_category')
                ->orderBy('id', 'desc')
                ->where('user_id', $uid)
                ->latest()
                ->get();
        }

        $total = count($accounts);
        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th width="2"></th>';
            $formstable .= '<th>Company Name</th>';
            $formstable .= '<th>Company Email</th>';
            $formstable .= '<th>Company Mobile</th>';
            $formstable .= '<th>Whatsapp Number</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($accounts as $formdetails) {
                $formstable .= '<tr>';
                $formstable .= '<td><input type="checkbox" class="checkAll" id="' . $formdetails->id . '"></td>';
                $formstable .= '<td class="table-title"><a href="' . url('admin/companies/' . $formdetails->id) . '">' . $formdetails->c_name . '</a>&nbsp;</td>';
                $formstable .= '<td>' . $formdetails->c_email . '</td>';
                $formstable .= '<td>' . $formdetails->c_mobileNum . '</td>';
                $formstable .= '<td>' . $formdetails->c_whatsappNum . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item text-default text-btn-space" href="' . url('admin/companies/' . $formdetails->id . '/edit') . '">Edit</a>
                    <a class="dropdown-item text-default text-btn-space" href="' . url('admin/companies/delete/' . $formdetails->id) . '">Delete</a>
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
        $data['table'] = $formstable;
        $data['total'] = $total;

        return $data;
    }


    public function getUserCompanies(Request $request)
    {
        $uid = $request->input('uid');
        $accounts = $this->getCompanies($uid);
        return json_encode($accounts);
    }

    public function deleteCompany($id)
    {
        $account = Company::find($id);
        $account->isActive = 0;
        $account->save();

        $res = Company::where('id', $id)->update(['isActive' => 0]);

        if ($res) {
            return redirect('admin/companies')->with('success', 'Deleted Successfully...');
        } else {
            return redirect('admin/companies')->with('error', 'Failed. Try again later...');
        }
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->input('id');
        // return json_encode($ids);
        return Company::whereIn('id', $ids)->update(array('isActive' => 0));
    }
}

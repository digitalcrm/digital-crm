<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

//---------Models---------------
use App\Tbl_contacts;
use App\Tbl_countries;
use App\Tbl_states;
use App\Tbl_leadsource;
use App\Tbl_Accounts;
use App\Tbl_leads;
use App\User;

use App\Imports\ContactsImport;
use App\Exports\ContactsExport;
use Maatwebsite\Excel\Facades\Excel;

class ContactController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('test:contact-list', ['only' => ['index', 'show']]);
        $this->middleware('test:contact-create', ['only' => ['create', 'store']]);
        $this->middleware('test:contact-edit', ['only' => ['edit', 'update']]);
        $this->middleware('test:contact-delete', ['only' => ['destroy', 'deleteContact', 'deleteAll']]);
        $this->middleware('test:contact-import', ['only' => ['import', 'importData']]);
        $this->middleware('test:contact-export', ['only' => ['export', 'exportData']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderby('id', 'asc')->get(['id', 'name']);

        //        $i = 0;
        $uid = 'All';

        //        $useroptions = "<option value=''>Select User</option>";
        $useroptions = "<option value='All' selected>All</option>";
        foreach ($users as $userdetails) {
            //            if ($i == 0) {
            //                $selected = 'selected';
            //                $uid = $userdetails->id;
            //            } else {
            //                $selected = '';
            //            }
            //            $i++;
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";   //. "  " . $selected 
        }
        $data['useroptions'] = $useroptions;

        $contacts = $this->getContacts($uid);

        $data['table'] = $contacts['table'];
        $data['total'] = $contacts['total'];

        return view('admin.contacts.index')->with('data', $data);
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
        $contact = Tbl_contacts::with('Tbl_leadsource')
            ->with('Tbl_Accounts')
            ->with('Tbl_countries')
            ->with('Tbl_states')
            ->find($id)->toArray();

        //        echo json_encode($contact);

        $data['contact'] = $contact;

        $editLink = url('admin/contacts') . '/' . $id . '/edit';
        $data['editLink'] = $editLink;

        return view('admin.contacts.show')->with("data", $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contact = Tbl_contacts::with('Tbl_leadsource')
            ->with('Tbl_Accounts')
            ->with('Tbl_countries')
            ->with('Tbl_states')
            ->find($id)->toArray();
        $data['contact'] = $contact;

        $country = Tbl_countries::all();
        $countryoptions = "<option value='0'>Select Country</option>";
        if (count($country) > 0) {
            foreach ($country as $cnt) {
                $selected = ($cnt->id == $contact['country']) ? 'selected' : '';
                $countryoptions .= "<option value='" . $cnt->id . "' " . $selected . ">" . $cnt->name . ' - ' . $cnt->sortname . "</option>";
            }
        }
        $data['countryoptions'] = $countryoptions;

        $stateoptions = "<option value='0'>Select State</option>";
        if ($contact['country'] > 0) {
            $states = Tbl_states::where('country_id', $contact['country'])->get();
            //DB::select('SELECT * FROM `tbl_states` WHERE `country_id`=' . $contact['country']);
            if (count($states) > 0) {
                foreach ($states as $state) {
                    $stateselected = ($state->id == $contact['state']) ? 'selected' : '';
                    $stateoptions .= "<option value='" . $state->id . "' " . $stateselected . ">" . $state->name . "</option>";
                }
            }
        }
        $data['stateoptions'] = $stateoptions;

        $leadsource = Tbl_leadsource::all();
        $leadsourceoptions = "<option value='0'>Select Lead Source</option>";
        if (count($leadsource) > 0) {
            foreach ($leadsource as $source) {
                $leadsourceselected = ($source->ldsrc_id == $contact['ldsrc_id']) ? 'selected' : '';
                $leadsourceoptions .= "<option value='" . $source->ldsrc_id . "' " . $leadsourceselected . ">" . $source->leadsource . "</option>";
            }
        }
        $data['leadsourceoptions'] = $leadsourceoptions;

        $accounts = Tbl_Accounts::all();
        $accountoptions = "<option value='0'>Select Account</option>";
        if (count($accounts) > 0) {
            foreach ($accounts as $account) {
                $accountselected = ($account->acc_id == $contact['acc_id']) ? 'selected' : '';
                $accountoptions .= "<option value='" . $account->acc_id . "' " . $accountselected . ">" . $account->name . "</option>";
            }
        }
        $accountoptions .= "<option disabled>---</option>";
        $accountoptions .= "<option value='NewAccount'>Add Account</option>";
        $data['accountoptions'] = $accountoptions;

        return view('admin.contacts.edit')->with("data", $data);
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

            'usermail' => 'required|email|max:255',
        ]);

        // 'last_name' => 'required|max:255',
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

        $leads = Tbl_contacts::find($id);
        $leads->first_name = $request->input('first_name');
        $leads->last_name = $request->input('last_name');
        $leads->email = $request->input('usermail');
        $leads->mobile = $request->input('mobile');
        $leads->phone = $request->input('phone');
        $leads->ldsrc_id = $request->input('leadsource');
        $leads->acc_id = $acc_id;
        $leads->notes = $request->input('notes');
        $leads->website = $request->input('website');
        $leads->country = $request->input('country');
        $leads->state = $request->input('state');
        $leads->city = $request->input('city');
        $leads->street = $request->input('street');
        $leads->zip = $request->input('zip');
        $leads->designation = (($request->input('designation')) != '') ? $request->input('designation') : $leads->designation;

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
                return redirect('admin/contacts/' . $id . '/edit')->with('error', 'Please upload jpg, png and giff images only.');
            }
            //-------------Image Validation----------------------------------


            $file = $request->file('userpicture');
            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $file->move('uploads/leads/', $name);   //public_path() 
            $filename = '/uploads/leads/' . $name;
            $leads->picture = $filename;
        }

        $leads->save();
        return redirect('admin/contacts')->with('success', 'Updated Successfully...!');
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

    public function deleteContact($id)
    {
        $leads = Tbl_contacts::find($id);
        $leads->active = 0;
        $leads->save();
        return redirect('admin/contacts')->with('success', 'Deleted Successfully...!');
    }

    public function getContacts($uid)
    {

        if ($uid == 'All') {
            $contacts = Tbl_contacts::where('active', 1)->with('Tbl_Accounts')
                ->with('Tbl_leadsource')
                ->with('Tbl_countries')
                ->with('Tbl_states')
                ->orderBy('cnt_id', 'desc')
                ->get();
        } else {
            $contacts = Tbl_contacts::where('active', 1)->where('uid', $uid)
                ->with('Tbl_Accounts')
                ->with('Tbl_leadsource')
                ->with('Tbl_countries')
                ->with('Tbl_states')
                ->orderBy('cnt_id', 'desc')
                ->get();
        }

        //        echo json_encode($contacts);$total = 0;

        $total = count($contacts);
        if ($total > 0) {
            $formstable = '<table id="contactsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            //            $formstable .= '<th width="2"><input type="checkbox" id="selectAll"></th>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Email</th>';
            $formstable .= '<th>Mobile</th>';
            $formstable .= '<th>Designation</th>';
            $formstable .= '<th>Website</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($contacts as $formdetails) {
                $formstable .= '<tr>';
                //                $formstable .= '<td><input type="checkbox" class="checkAll" id="' . $formdetails->cnt_id . '"></td>';
                $formstable .= '<td class="table-title"><a href="' . url('admin/contacts/' . $formdetails->cnt_id) . '">' . $formdetails->first_name . ' ' . $formdetails->last_name . '</a>&nbsp;</td>';
                $formstable .= '<td>' . $formdetails->email . '</td>';
                //' . url('admin/mails/mailsend/contacts/' . $formdetails->cnt_id) . '
                $formstable .= '<td>' . $formdetails->mobile . '</td>';
                $formstable .= '<td>' . $formdetails->designation . '</td>';
                $formstable .= '<td>' . $formdetails->website . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                  </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item text-default text-btn-space" href="' . url('admin/contacts/' . $formdetails->cnt_id . '/edit') . '">Edit</a>
                                        <a class="dropdown-item text-default text-btn-space" href="' . url('admin/contacts/delete/' . $formdetails->cnt_id) . '">Delete</a>
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

        //                                                
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->input('id');
        return Tbl_contacts::whereIn('cnt_id', $ids)->update(array('active' => 0));
    }

    public function export($type)
    {
        $users = User::orderby('id', 'asc')->get(['id', 'name']);

        $useroptions = "<option value=''>Select User...</option>";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";
        }
        $data['useroptions'] = $useroptions;

        return view('admin.contacts.export')->with('data', $data);
    }

    public function exportData(Request $request)
    {
        // echo json_encode($request->input());
        // exit();

        // $uid = Auth::user()->id;
        $uid = $request->input('selectUser');
        return Excel::download(new ContactsExport($uid), 'contacts.xlsx');

        // if ($res) {
        //     // echo 'Success';
        //     return redirect('admin/accounts/export/csv')->with('success', 'Downloaded successfully');
        // } else {
        //     // echo 'Failed';
        //     return redirect('admin/accounts/export/csv')->with('error', "Error ocurred. Try again later..!");
        // }
    }

    public function import($type)
    {
        $users = User::orderby('id', 'asc')->get(['id', 'name']);

        $useroptions = "<option value=''>Select User...</option>";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";
        }
        $data['useroptions'] = $useroptions;

        return view('admin.contacts.import')->with('data', $data);
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
                return redirect('admin/contacts/import/csv')->with('error', 'Please upload .csv only.');
            }
            //-------------Image Validation----------------------------------

            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $path = $request->file('importFile')->getRealPath();

            $res = Excel::import(new ContactsImport($uid), request()->file('importFile'));

            if ($res) {
                echo 'Success';
                return redirect('admin/contacts')->with('success', 'Uploaded successfully');
            } else {
                // echo 'Failed';
                return redirect('admin/contacts/import/csv')->with('error', "Error ocurred. Try again later..!");
            }
        } else {
            return redirect('admin/contacts/import/csv')->with('error', 'Please upload file.');
        }
    }
}

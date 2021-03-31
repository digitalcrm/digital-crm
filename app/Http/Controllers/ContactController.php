<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Tbl_leads;
use App\Tbl_deals;
use App\Tbl_countries;
use App\Tbl_states;
use App\Tbl_contacts;
use App\Tbl_leadsource;
use App\Tbl_Accounts;
use App\Tbl_salutations;
// use Excel;
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
        $this->middleware('auth');
        $this->middleware('test:contacts', ['only' => ['index', 'show', 'create', 'store', 'edit', 'update', 'delete', 'deleteAll', 'deleteContact', 'import', 'importData', 'export', 'exportData']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $uid = Auth::user()->id;
        $salutations = Tbl_salutations::all();
        $accounts = Tbl_Accounts::where('uid', $uid)->where('active', 1)->get();
        $leaddetails = Tbl_leadsource::all();
        $country = Tbl_countries::all();

        $contacts = $this->getContacts($uid);
        $total = count($contacts);
        if ($total > 0) {
            $formstable = '<div class="table-responsive"><table id="contactsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            //            $formstable .= '<th width="2"></th>';   //<input type="checkbox" id="selectAll">
            $formstable .= '<th width="10">&nbsp;</th>';
            $formstable .= '<th width="230">Name</th>';
            $formstable .= '<th>Designation</th>';
            $formstable .= '<th>Account</th>';
            $formstable .= '<th>Company</th>';
            $formstable .= '<th>Lead Source</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Add to Lead</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($contacts as $formdetails) {
                $customer_status = '';
                $ld_id = 0;
                if ($formdetails->ld_id > 0) {
                    $ld_id = $formdetails->ld_id;
                    $customer = Tbl_deals::where('ld_id', $ld_id)->where('deal_status', 1)->first();
                    if ($customer != null) {
                        $customer_status = '<span class="badge badge-success">Customer</span>';
                    }
                }

                $contactimage = ($formdetails->picture != '') ? $formdetails->picture : '/uploads/default/contacts.png';
                $leadsource = ($formdetails->tbl_leadsource != '') ? $formdetails->tbl_leadsource->leadsource : '';
                $account = ($formdetails->tbl_accounts != '') ? $formdetails->tbl_accounts->name : '';
                $company = ($formdetails->tbl_accounts != '') ? $formdetails->tbl_accounts->company : '';

                $formstable .= '<tr>';
                $formstable .= '<td width="10"><div class="custom-control custom-checkbox"><input type="checkbox" class="checkAll custom-control-input" id="' . $formdetails->cnt_id . '"><label class="custom-control-label" for="' . $formdetails->cnt_id . '"></label></div></td>';
                $formstable .= '<td class="table-title"><img src="' . url($contactimage) . '" class="avatar"><h6><a href="' . url('contacts/' . $formdetails->cnt_id) . '">' . $formdetails->first_name . ' ' . $formdetails->last_name . '</a>&nbsp; ' . $customer_status . '</h6><div class="t-email"><a class="text-muted" href="' . url('mails/mailsend/contacts/' . $formdetails->cnt_id) . '">' . $formdetails->email . '</a></div><div class="t-mob text-muted">' . $formdetails->mobile . '</div></td>';
                //$formstable .= '<td><a class="text-muted" href="' . $formdetails->website . '">' . $formdetails->website . '</a></td>';
                $formstable .= '<td>' . $formdetails->designation . '</td>';
                $formstable .= '<td>' . $account . '</td>';
                $formstable .= '<td>' . $company . '</td>';
                $formstable .= '<td>' . $leadsource . '</td>';
                $formstable .= '<td>' . date('m-d-Y', strtotime($formdetails->created_at)) . '</td>';

                if ($formdetails->ld_id == 0) {
                    $formstable .= '<td><a class="badge badge-secondary" href="' . url('contacts/addtolead/' . $formdetails->cnt_id) . '">Add to Lead</a></td>';
                } else {
                    $formstable .= '<td><span class="badge badge-success"><i class="fa fa-check" aria-hidden="true"></i> Added</span></td>';
                }

                //                $formstable .= '<td>Add to Leads</td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                                        <span class="caret"></span>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item text-default text-btn-space" href="' . url('contacts/' . $formdetails->cnt_id . '/edit') . '">Edit</a>
                                        <a class="dropdown-item text-default text-btn-space" href="' . url('contacts/delete/' . $formdetails->cnt_id) . '">Delete</a>
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
        // return view('auth.contacts.index')->with("data",$data);
        return view('auth.contacts.index', compact('data', 'country', 'leaddetails', 'accounts', 'salutations'));
    }

    public function getContacts($uid)
    {
        return Tbl_contacts::where('uid', $uid)
            ->with('Tbl_Accounts')
            ->with('Tbl_leadsource')
            ->with('Tbl_countries')
            ->with('Tbl_states')
            ->where('active', 1)
            ->orderBy('cnt_id', 'desc')
            ->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        if ($user->can('create', Tbl_contacts::class)) {

            $uid = Auth::user()->id;
            $country = Tbl_countries::all();
            $countryoptions = "<option value='0'>Select Country</option>";
            if (count($country) > 0) {
                foreach ($country as $cnt) {
                    $countryoptions .= "<option value='" . $cnt->id . "'>" . $cnt->name . ' - ' . $cnt->sortname . "</option>";
                }
            }
            $data['countryoptions'] = $countryoptions;

            $leadsource = Tbl_leadsource::all();
            $leadsourceoptions = "<option value='0'>Select Lead Source</option>";
            if (count($leadsource) > 0) {
                foreach ($leadsource as $source) {
                    $leadsourceoptions .= "<option value='" . $source->ldsrc_id . "'>" . $source->leadsource . "</option>";
                }
            }
            $data['leadsourceoptions'] = $leadsourceoptions;

            $accounts = Tbl_Accounts::where('uid', $uid)->where('active', 1)->get();
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

            return view('auth.contacts.create')->with('data', $data);
        } else {
            return redirect('/contacts');
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
        //        echo json_encode($request->input());
        //        exit(0);

        $this->validate($request, [
            'first_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:tbl_contacts',    //'last_name' => 'required|max:255',
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
                return redirect('/contacts/create')->with('error', 'Please upload jpg, png and gif images only.');
            }
            //-------------Image Validation----------------------------------
            //            $file = $request->file('userpicture');
            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $file->move('uploads/contacts/', $name);  //public_path().
            $filename = '/uploads/contacts/' . $name;
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

        $addtoLead = ($request->input('addtoLead') != '') ? true : false;

        $formdata = array(
            'uid' => Auth::user()->id,
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'picture' => $filename,
            'mobile' => $request->input('mobile'),
            'phone' => $request->input('phone'),
            'ldsrc_id' => $request->input('leadsource'),
            'acc_id' => $acc_id,
            'notes' => $request->input('notes'),
            'website' => $request->input('website'),
            'country' => $request->input('country'),
            'state' => $request->input('state'),
            'city' => $request->input('city'),
            'street' => $request->input('street'),
            'zip' => $request->input('zip'),
            'google_id' => $request->input('google_id'),
            'facebook_id' => $request->input('facebook_id'),
            'twitter_id' => $request->input('twitter_id'),
            'linkedin_id' => $request->input('linkedin_id'),
            'sal_id' => $request->input('salutation'),
            'designation' => $request->input('designation')
        );

        // echo json_encode($formdata);

        $contacts = $this->addContact($formdata);
        $cnt_id = $contacts->cnt_id;

        if ($cnt_id > 0) {

            if ($addtoLead) {
                $this->addtoLead($cnt_id);
            }

            return redirect('/contacts')->with('success', 'Contact Created Successfully...!');
        } else {
            return redirect('/contacts')->with('error', 'Error occurred. Please try again...!');
        }
    }

    public function addContact($formdata)
    {
        // dd($formdata);
        return Tbl_contacts::create($formdata);
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
        $contact  = Tbl_contacts::find($id);

        if ($user->can('view', $contact)) {

            //        echo json_encode($contact);
            $contact = $this->getContactDetails($id);
            $data['contact'] = $contact;

            $editLink = url('contacts') . '/' . $id . '/edit';
            $data['editLink'] = $editLink;

            return view('auth.contacts.show')->with("data", $data);
        } else {
            return redirect('/contacts');
        }
    }

    public function getContactDetails($id)
    {
        $contacts = Tbl_contacts::with('Tbl_leadsource')
            ->with('Tbl_Accounts')
            ->with('Tbl_countries')
            ->with('Tbl_states')
            ->with('Tbl_salutations')
            ->find($id);

        return $contacts->toArray();
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
        $contact  = Tbl_contacts::find($id);

        if ($user->can('view', $contact)) {

            $uid = Auth::user()->id;
            $contact = Tbl_contacts::with('Tbl_leadsource')
                ->with('Tbl_Accounts')
                ->with('Tbl_countries')
                ->with('Tbl_states')
                ->with('Tbl_salutations')
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
                $states = DB::select('SELECT * FROM `tbl_states` WHERE `country_id`=' . $contact['country']);
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

            $accounts = Tbl_Accounts::where('active', 1)->where('uid', $uid)->get();
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

            $salutations = Tbl_salutations::all();
            $salutationoptions = "<option value='0'>None</option>";
            if (count($salutations) > 0) {
                foreach ($salutations as $salutation) {
                    $salutationselected = ($salutation->sal_id == $contact['sal_id']) ? 'selected' : '';
                    $salutationoptions .= "<option value='" . $salutation->sal_id . "' " . $salutationselected . ">" . $salutation->salutation . "</option>";
                }
            }
            $data['salutationoptions'] = $salutationoptions;

            return view('auth.contacts.edit')->with("data", $data);
        } else {
            return redirect('/contacts');
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
        $contact  = Tbl_contacts::find($id);

        if ($user->can('update', $contact)) {

            $this->validate($request, [
                'first_name' => 'required|max:255',
                // /'last_name' => 'required|max:255',
                'email' => 'required|email|max:255',    //|unique:tbl_contacts,email,' . $id . ',cnt_id
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
                    return redirect('contacts/' . $id . '/edit')->with('error', 'Please upload jpg, png and giff images only.');
                }
                //-------------Image Validation----------------------------------
                $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
                $file->move('uploads/leads/', $name);   //public_path()
                $filename = '/uploads/leads/' . $name;
                //            $leads->picture = $filename;
            }
            $formdata['picture'] = $filename;

            $this->updateContact($formdata, $id);

            $addtoLead = ($request->input('addtoLead') != '') ? true : false;
            if ($addtoLead) {
                $this->addtoLead($id);
            }

            return redirect('/contacts')->with('success', 'Updated Successfully...!');
        } else {
            return redirect('/contacts');
        }
    }

    public function updateContact($formdata, $id)
    {
        $leads = Tbl_contacts::find($id);
        $leads->first_name = (isset($formdata['first_name'])) ? $formdata['first_name'] : $leads->first_name; //$request->input('first_name');
        $leads->last_name = (isset($formdata['last_name'])) ? $formdata['last_name'] : $leads->last_name; //$request->input('last_name');
        $leads->email = (isset($formdata['email'])) ? $formdata['email'] : $leads->email; //$request->input('usermail');
        $leads->mobile = (isset($formdata['mobile'])) ? $formdata['mobile'] : $leads->mobile; //$request->input('mobile');
        $leads->phone = (isset($formdata['phone'])) ? $formdata['phone'] : $leads->phone; //$request->input('phone');
        $leads->ldsrc_id = (isset($formdata['leadsource'])) ? $formdata['leadsource'] : $leads->ldsrc_id; //$request->input('leadsource');
        $leads->acc_id = (isset($formdata['account'])) ? $formdata['account'] : $leads->acc_id; //$acc_id;
        $leads->notes = (isset($formdata['notes'])) ? $formdata['notes'] : $leads->notes; //$request->input('notes');
        $leads->website = (isset($formdata['website'])) ? $formdata['website'] : $leads->website; //$request->input('website');
        $leads->country = (isset($formdata['country'])) ? $formdata['country'] : $leads->country; //$request->input('country');
        $leads->state = (isset($formdata['state'])) ? $formdata['state'] : $leads->state; //$request->input('state');
        $leads->city = (isset($formdata['city'])) ? $formdata['city'] : $leads->city; //$request->input('city');
        $leads->street = (isset($formdata['street'])) ? $formdata['street'] : $leads->street; //$request->input('street');
        $leads->zip = (isset($formdata['zip'])) ? $formdata['zip'] : $leads->zip; //$request->input('zip');
        $leads->google_id = (isset($formdata['google_id'])) ? $formdata['google_id'] : $leads->google_id; //$request->input('google_id');
        $leads->facebook_id = (isset($formdata['facebook_id'])) ? $formdata['facebook_id'] : $leads->facebook_id; //$request->input('facebook_id');
        $leads->twitter_id = (isset($formdata['twitter_id'])) ? $formdata['twitter_id'] : $leads->twitter_id; //$request->input('twitter_id');
        $leads->linkedin_id = (isset($formdata['linkedin_id'])) ? $formdata['linkedin_id'] : $leads->linkedin_id; //$request->input('linkedin_id');
        $leads->picture = ($formdata['picture'] != '') ? $formdata['picture'] : $leads->picture; //$request->input('linkedin_id');
        $leads->sal_id = (isset($formdata['salutation'])) ? $formdata['salutation'] : $leads->sal_id;
        $leads->designation = (isset($formdata['designation'])) ? $formdata['designation'] : $leads->designation;
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

    public function addtoLead($id)
    {
        $contacts = Tbl_contacts::find($id);
        //        echo json_encode($contacts);

        $formdata = array(
            'uid' => Auth::user()->id,
            'fl_id' => 0,
            'cnt_id' => $id,
            'first_name' => $contacts->first_name,
            'last_name' => $contacts->last_name,
            'picture' => $contacts->picture,
            'email' => $contacts->email,
            'mobile' => $contacts->mobile,
            'phone' => $contacts->phone,
            'website' => $contacts->website,
            'notes' => $contacts->notes,
            'acc_id' => $contacts->acc_id,
            'ldsrc_id' => $contacts->ldsrc_id,
            'country' => $contacts->country,
            'state' => $contacts->state,
            'city' => $contacts->city,
            'street' => $contacts->street,
            'zip' => $contacts->zip
        );

        //        echo json_encode($formdata);

        $leads = Tbl_leads::create($formdata);
        $ld_id = $leads->ld_id;

        if ($ld_id > 0) {

            $contacts->ld_id = $ld_id;
            $contacts->save();

            return redirect('/contacts')->with('success', 'Added to Leads Successfully...!');
        } else {
            return redirect('/contacts')->with('error', 'Error occurred. Please try again...!');
        }
    }

    public function import($type)
    {
        return view('auth.contacts.import');
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
                return redirect('/contacts/import/csv')->with('error', 'Please upload .csv only.');
            }
            //-------------Image Validation----------------------------------

            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $path = $request->file('importFile')->getRealPath();
            // $data = Excel::load($path)->get();
            // $exist_rec = '';
            $res = Excel::import(new ContactsImport($uid), request()->file('importFile'));

            if ($res) {
                // echo 'Success';
                return redirect('/contacts')->with('success', 'Uploaded successfully');
            } else {
                // echo 'Failed';
                return redirect('/contacts/import/csv')->with('error', "Error ocurred. Try again later..!");
            }
        } else {
            return redirect('/contacts/import/csv')->with('error', 'Please upload file.');
        }
    }

    public function exportData($type)
    {
        $uid = Auth::user()->id;
        return Excel::download(new ContactsExport($uid), 'contacts.xlsx');
    }

    public function deleteContact($id)
    {
        $user = Auth::user();
        $contact  = Tbl_contacts::find($id);

        if ($user->can('delete', $contact)) {
            $this->delete($id);
            return redirect('/contacts')->with('success', 'Deleted Successfully...');
        } else {
            return redirect('/contacts');
        }
    }

    public function delete($id)
    {
        $account = Tbl_contacts::find($id);
        $account->active = 0;
        return $account->save();
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->input('id');
        return Tbl_contacts::whereIn('cnt_id', $ids)->update(array('active' => 0));
    }

    public function restoreAll(Request $request)
    {
        $ids = $request->input('id');
        return Tbl_contacts::whereIn('cnt_id', $ids)->update(array('active' => 1));
    }
}

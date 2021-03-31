<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Tbl_forms;
use App\Tbl_formleads;
use App\Tbl_leads;
use App\Tbl_deals;
use Excel;

class WebtoleadController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('test:webtolead', ['only' => ['index', 'show', 'create', 'store', 'edit', 'update', 'delete', 'deleteAll', 'formleads', 'import', 'importData', 'export', 'exportData', 'formleadsLatest']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $uid = Auth::user()->id;
        $forms = Tbl_forms::where('uid', $uid)->where('active', 1)->orderBy('form_id', 'desc')->get();
        $total = count($forms);
        if ($total > 0) {
            $formstable = '<div class="table-responsive"><table id="formsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th width="2"></th>';
            $formstable .= '<th>Title</th>';
            $formstable .= '<th>Website</th>';
            $formstable .= '<th>Views</th>';
            $formstable .= '<th>Contacts</th>';
            $formstable .= '<th>Conversion Rate</th>';
            $formstable .= '<th>Created</th>';
            $formstable .= '<th>Preview</th>';
            $formstable .= '<th>Embed Code</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($forms as $formdetails) {

                $formLeads = Tbl_formleads::where('form_id', $formdetails->form_id)->get();

                $formstable .= '<tr>';
                $formstable .= '<td><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input checkAll" id="' . $formdetails->form_id . '"><label class="custom-control-label" for="' . $formdetails->form_id . '"></label></div></td>';
                $formstable .= '<td class="table-title"><a href="' . url('webtolead/formleads/' . $formdetails->form_id) . '">' . $formdetails->title . '</a>&nbsp;</td>';
                $formstable .= '<td>' . $formdetails->post_url . '</td>';
                $formstable .= '<td>' . $formdetails->views . '</td>';
                $formstable .= '<td><a href="' . url('webtolead/formleads/' . $formdetails->form_id) . '">' . count($formLeads) . '</a></td>';
                $formstable .= '<td>0</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '<td><a href="#" onclick="return previewForm(' . $formdetails->form_id . ')" class="badge badge-success">Preview</a></td>';
                $formstable .= '<td><a href="#" onclick="return embedCode(' . $formdetails->form_id . ')" class="badge badge-success">Embed Code</a></td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                      <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                      </button>
                      <div class="dropdown-menu" role="menu">    
                        <a class="dropdown-item text-default text-btn-space" href="' . url('webtolead/' . $formdetails->form_id) . '">View</a>
                        <a class="dropdown-item text-default text-btn-space" href="' . url('webtolead/' . $formdetails->form_id . '/edit') . '">Edit</a>
                        <a class="dropdown-item text-default text-btn-space" href="' . url('webtolead/deleteform/' . $formdetails->form_id) . '">Delete</a>
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

        return view('auth.webtolead.index')->with("data", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        if ($user->can('create', Tbl_forms::class)) {
            return view('auth.webtolead.create');
        } else {
            return redirect('/webtolead');
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
            'title' => 'required|max:255',
            'post_url' => 'required|url|max:255|unique:tbl_forms',
            'redirect_url' => 'required|url|max:255',
            'from_mail' => 'required|email|max:255|',
        ]);

        $formdata = array(
            'uid' => Auth::user()->id,
            'title' => $request->input('title'),
            'post_url' => $request->input('post_url'),
            'redirect_url' => $request->input('redirect_url'),
            'from_mail' => $request->input('from_mail'),
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
            'form_key' => strtotime(date('d-m-y h:i:s')),
        );

        $site_key = $request->input('site_key');
        $secret_key = $request->input('secret_key');

        if (($site_key != '') && ($secret_key != '')) {
            $formdata['site_key'] = $site_key;
            $formdata['secret_key'] = $secret_key;
        }

        //        echo json_encode($formdata);

        $forms = Tbl_forms::create($formdata);
        $form_id = $forms->form_id;

        if ($form_id > 0) {
            return redirect('/webtolead')->with('success', 'Form Created Successfully...!');
        } else {
            return redirect('/webtolead')->with('error', 'Error occurred. Please try again...!');
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
        $user = Auth::user();
        $form = Tbl_forms::find($id);
        if ($user->can('view', $form)) {
            return view('auth.webtolead.show')->with('form', $form);
        } else {
            return redirect('/webtolead');
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
        $form = Tbl_forms::find($id);
        $user = Auth::user();
        if ($user->can('view', $form)) {
            return view('auth.webtolead.edit')->with('form', $form);
        } else {
            return redirect('/webtolead');
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

        $form = Tbl_forms::find($id);
        $user = Auth::user();
        if ($user->can('update', $form)) {
            $this->validate($request, [
                'title' => 'required|max:255',
                'post_url' => 'required|url|max:255',
                'redirect_url' => 'required|url|max:255',
                'from_mail' => 'required|email|max:255',
            ]);

            $site_key = $request->input('site_key');
            $secret_key = $request->input('secret_key');

            $formdata = array(
                'uid' => Auth::user()->id,
                'title' => $request->input('title'),
                'post_url' => $request->input('post_url'),
                'redirect_url' => $request->input('redirect_url'),
                'from_mail' => $request->input('from_mail'),
                'subject' => $request->input('subject'),
                'message' => $request->input('message'),
            );

            $formexists = DB::select('SELECT `form_id` FROM `tbl_forms` WHERE `post_url`="' . $formdata["post_url"] . '" and `form_id`!=' . $id);

            if (count($formexists) > 0) {
                return redirect('webtolead/' . $id . '/edit')->with('error', 'Post url alredy exists');
            } else {
                // echo "Uniqueeeeeeeeeeeeeeeeeeeeeeee";

                $form->title = $request->input('title');
                $form->post_url = $request->input('post_url');
                $form->redirect_url = $request->input('redirect_url');
                $form->from_mail = $request->input('from_mail');
                $form->subject = $request->input('subject');
                $form->message = $request->input('message');

                if (($site_key != '') && ($secret_key != '')) {
                    $form->site_key = $site_key;
                    $form->secret_key = $secret_key;
                }

                $res = $form->save();

                if ($res > 0) {
                    return redirect('/webtolead')->with('success', 'Form Updated Successfully...!');
                } else {
                    return redirect('/webtolead')->with('error', 'Error occurred. Please try again...!');
                }
            }
        } else {
            return redirect('/webtolead');
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
    }

    public function formDelete($id)
    {
        $forms = Tbl_forms::find($id);
        $user = Auth::user();
        if ($user->can('update', $forms)) {
            $forms->active = 0;
            $forms->save();

            //----------Invoice-------------------
            Tbl_formleads::where('form_id', '=', $id)->update(['active' => 0]);

            return redirect('/webtolead')->with('success', 'Form Deleted Successfully...!');
        } else {
            return redirect('/webtolead');
        }
    }

    public function formleads($id)
    {

        $forms = Tbl_forms::find($id);
        $user = Auth::user();
        if ($user->can('view', $forms)) {
            $formleads = Tbl_formleads::where('form_id', $id)->where('active', 1)->with('tbl_leads')->orderBy('fl_id', 'desc')->get();
            $total = count($formleads);
            if ($total > 0) {
                $formstable = '<div class="table-responsive"><table id="formleadsTable" class="table">';
                $formstable .= '<thead>';
                $formstable .= '<tr>';
                $formstable .= '<th width="2"></th>';
                $formstable .= '<th>Name</th>';
                $formstable .= '<th>Email</th>';
                $formstable .= '<th>Mobile</th>';
                $formstable .= '<th>Website</th>';
                $formstable .= '<th>Add to lead</th>';
                $formstable .= '<th>Created</th>';
                $formstable .= '<th>Action</th>';
                $formstable .= '<th class="none">Notes</th>';
                $formstable .= '</tr>';
                $formstable .= '</thead>';
                $formstable .= '<tbody>';
                foreach ($formleads as $formdetails) {
                    $ld_id = 0;
                    $customer_status = '';
                    if ($formdetails->tbl_leads != null) {
                        $ld_id = $formdetails->tbl_leads->ld_id;
                        $customer = Tbl_deals::where('ld_id', $ld_id)->where('deal_status', 1)->first();
                        if ($customer != null) {
                            $customer_status = '<span class="label label-success">Customer</span>';
                        }
                    }

                    $formstable .= '<tr>';
                    $formstable .= '<td><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input checkAll" id="' . $formdetails->fl_id . '"><label class="custom-control-label" for="' . $formdetails->fl_id . '"></label></div></td>';
                    $formstable .= '<td><a href="' . url('webtolead/viewformlead/' . $formdetails->fl_id) . '">' . $formdetails->first_name . '</a>&nbsp; ' . $customer_status . '</td>';
                    $formstable .= '<td><a href="' . url('mails/mailsend/formleads/' . $formdetails->fl_id) . '">' . $formdetails->email . '</a></td>';
                    $formstable .= '<td>' . $formdetails->mobile . '</td>';
                    $formstable .= '<td>' . $formdetails->website . '</td>';
                    if ($formdetails->lead == 0) {
                        $formstable .= '<td><a class="badge badge-secondary" href="' . url('webtolead/addtolead/' . $formdetails->fl_id . '/' . $id) . '">Add to Lead</a></td>';
                    } else {
                        $formstable .= '<td><span class="badge badge-success"><i class="fa fa-check" aria-hidden="true"></i> Added</span></td>';
                    }
                    $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                    $formstable .= '<td>';
                    $formstable .= '<div class="btn-group">
                      <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                      </button>
                      <div class="dropdown-menu" role="menu">    
                        <a class="dropdown-item text-default text-btn-space" href="' . url('webtolead/viewformlead/' . $formdetails->fl_id) . '">View</a>
                        <a class="dropdown-item text-default text-btn-space" href="' . url('webtolead/deleteformlead/' . $formdetails->fl_id . '/' . $id) . '">Delete</a>             
                    </div>
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
            $data['forms'] = $forms;

            return view('auth.webtolead.formleads')->with("data", $data);
        } else {
            return redirect('/webtolead');
        }
    }

    public function formleadView($id)
    {
        $formleads = Tbl_formleads::find($id);
        //        echo json_encode($formleads);
        //        exit(0);

        if ($formleads != '') {

            $form = Tbl_forms::find($formleads->form_id);
            $user = Auth::user();
            if ($user->can('view', $form)) {

                $data['form'] = $form;
                $data['formleads'] = $formleads;

                $leads = Tbl_leads::where('fl_id', $id)->first();
                //        echo json_encode($leads);
                //        exit(0);
                $customer_status = '';
                if ($leads != null) {
                    $ld_id = $leads->ld_id;
                    $customer = Tbl_deals::where('ld_id', $ld_id)->where('deal_status', 1)->first();
                    if ($customer != null) {
                        $customer_status = 'Customer';
                    } else {
                        $customer_status = 'Added';
                    }
                }



                $addtoleadLink = ($formleads->lead == 0) ? url('webtolead/addtolead/' . $formleads->fl_id . '/' . $formleads->form_id) : '#';
                $addtoleadbutton = ($formleads->lead == 0) ? 'Add to lead' : $customer_status;

                $data['addtoleadLink'] = $addtoleadLink;
                $data['addtoleadbutton'] = $addtoleadbutton;

                return view('auth.webtolead.showformlead')->with('data', $data);
            } else {
                return redirect('/webtolead');
            }
        } else {
            return redirect('/webtolead');
        }
    }

    public function formleadDelete($id, $form_id)
    {
        $formleads = Tbl_formleads::find($id);
        $formleads->active = 0;
        $formleads->save();

        return redirect('webtolead/formleads/' . $form_id)->with('success', 'Form Lead Deleted Successfully...!');
    }

    public function addtoLead($id, $form_id)
    {
        $formleads = Tbl_formleads::find($id);
        // echo json_encode($formleads);


        $formdata = array(
            'uid' => Auth::user()->id,
            'fl_id' => $id,
            'first_name' => $formleads->first_name,
            'last_name' => $formleads->last_name,
            'email' => $formleads->email,
            'mobile' => $formleads->mobile,
            'website' => $formleads->website,
            'message' => $formleads->notes,
            'uploaded_from' => 3,
            'uploaded_id' => $id,
        );

        $leads = Tbl_leads::create($formdata);
        $ld_id = $leads->ld_id;

        if ($ld_id > 0) {

            $formleads->lead = 1;
            $res = $formleads->save();

            return redirect('webtolead/formleads/' . $form_id)->with('success', 'Added to Lead Successfully...!');
        } else {
            return redirect('webtolead/formleads/' . $form_id)->with('error', 'Error occurred. Please try again...!');
        }
    }

    public function formleadsLatest($id)
    {
        //        echo 'Latest Form Leads...!';

        $uid = Auth::user()->id;
        $formleads = Tbl_formleads::orderBy('fl_id', 'desc')->where('uid', $uid)->with('tbl_forms')->get();
        $total = 0;
        if (count($formleads) > 0) {
            $formstable = '<div class="table-responsive"><table id="latestformleadsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Form</th>';
            $formstable .= '<th>Email</th>';
            $formstable .= '<th>Mobile</th>';
            $formstable .= '<th>Website</th>';
            $formstable .= '<th>Addto lead</th>';
            $formstable .= '<th>Created</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($formleads as $formdetails) {

                $form_id = $formdetails->tbl_forms->form_id;
                $formstable .= '<tr>';
                $formstable .= '<td><a href="' . url('webtolead/viewformlead/' . $formdetails->fl_id) . '">' . $formdetails->first_name . '</a>&nbsp;</td>';
                $formstable .= '<td>' . $formdetails->tbl_forms->post_url . '</td>';
                $formstable .= '<td>' . $formdetails->email . '</td>';
                $formstable .= '<td>' . $formdetails->mobile . '</td>';
                $formstable .= '<td>' . $formdetails->website . '</td>';
                if ($formdetails->lead == 0) {
                    $formstable .= '<td><a href="' . url('webtolead/addtolead/' . $formdetails->fl_id . '/' . $form_id) . '">Add to Leads</a></td>';
                } else {
                    $formstable .= '<td>Added</td>';
                }
                $formstable .= '<td>' . date('d-m-Y h:i', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                      </button>
                      <ul class="dropdown-menu" role="menu">    
                        <li><a class="text-default text-btn-space" href="' . url('webtolead/viewformlead/' . $formdetails->fl_id) . '">View</a></li>
                        <li><a class="text-default text-btn-space" href="' . url('webtolead/deleteformlead/' . $formdetails->fl_id . '/' . $form_id) . '">Delete</a></li>             
                    </ul></div>';
                $formstable .= '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table></div>';

            $total = count($formleads);
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;
        //        $data['forms'] = $forms;

        return view('auth.webtolead.latestformleads')->with("data", $data);
    }

    public function import($type)
    {
        return view(
            'auth.webtolead.import'
        );
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

            //            echo $file->getClientOriginalExtension();
            //            exit(0);
            // Tell the validator that this file should be an image
            $rules = array(
                'importFile' => 'required', //max 10000kb
                'extension' => 'required|in:csv'
            );

            // Now pass the input and rules into the validator
            $validator = Validator::make($fileArray, $rules);

            // Check to see if validation fails or passes
            if ($validator->fails()) {
                return redirect('/webtolead/import/csv')->with('error', 'Please upload .csv only.');
            }
            //-------------Image Validation----------------------------------



            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $path = $request->file('importFile')->getRealPath();
            $data = Excel::load($path)->get();
            $exist_rec = '';

            if ($data->count()) {
                //                echo json_encode($data);

                foreach ($data as $key => $value) {

                    $exist = Tbl_forms::where('post_url', $value->post_url)->get();

                    if (count($exist) > 0) {
                        $exist_rec .= $value->post_url . ', ';
                        //                        echo $value->post_url . ' exists <br>';
                    } else {
                        //                        echo $value->post_url . ' not exists <br>';

                        $formdata = array(
                            'uid' => $uid,
                            'title' => $value->title,
                            'post_url' => $value->post_url,
                            'redirect_url' => $value->redirect_url,
                            'from_mail' => $value->from_mail,
                            'subject' => $value->subject,
                            'message' => $value->message,
                            'site_key' => $value->site_key,
                            'secret_key' => $value->secret_key,
                        );

                        $arr[] = $formdata;
                    }
                }
                //                echo json_encode($arr);
                if (!empty($arr)) {
                    Tbl_forms::insert($arr);
                }

                if ($exist_rec != '') {
                    $with_status = 'info';
                    $with_message = trim($exist_rec, ",") . ' already exists. Remaing Uploaded successfully...!';
                }

                if ($exist_rec == '') {
                    $with_status = 'success';
                    $with_message = 'Uploaded successfully...!';
                }

                return redirect('/webtolead')->with($with_status, $with_message);
            } else {
                return redirect('/webtolead/import/csv')->with('error', "Please check uploaded file. Data don't exist.");
            }
        } else {
            return redirect('/webtolead/import/csv')->with('error', 'Please upload file.');
        }
    }

    public function exportData($type)
    {

        $uid = Auth::user()->id;
        $deals = DB::table('tbl_forms')
            ->where('tbl_forms.uid', $uid)
            ->select([
                'tbl_forms.form_id',
                'tbl_forms.uid',
                'tbl_forms.title',
                'tbl_forms.post_url',
                'tbl_forms.redirect_url',
                'tbl_forms.from_mail',
                'tbl_forms.views',
                'tbl_forms.submissions',
                'tbl_forms.subject',
                'tbl_forms.message',
                'tbl_forms.site_key',
                'tbl_forms.secret_key',
            ])->get();

        //        echo json_encode($deals);

        $deals_decode = json_decode($deals, true);

        $sheetName = 'forms_' . date('d_m_Y_h_i_s');

        return Excel::create(
            $sheetName,
            function ($excel) use ($deals_decode, $sheetName) {
                $excel->sheet($sheetName, function ($sheet) use ($deals_decode) {
                    $sheet->fromArray($deals_decode);
                });
            }
        )->download($type);
    }

    public function importFormleads($type, $form_id)
    {
        $data['form_id'] = $form_id;
        return view('auth.webtolead.importFormleads')->with('data', $data);
    }

    public function importFormleadsData(Request $request)
    {
        $uid = Auth::user()->id;
        //        echo json_encode($request->input());

        $form_id = $request->input('form_id');

        $filename = '';
        if ($request->hasfile('importFile')) {

            //-------------Image Validation----------------------------------
            $file = $request->file('importFile');
            // Build the input for validation
            $fileArray = array('importFile' => $file, 'extension' => strtolower($file->getClientOriginalExtension()));

            // Tell the validator that this file should be an image
            $rules = array(
                'importFile' => 'required',
                'extension' => 'required|in:csv'
            );

            // Now pass the input and rules into the validator
            $validator = Validator::make($fileArray, $rules);

            // Check to see if validation fails or passes
            if ($validator->fails()) {
                return redirect('/webtolead/importformleads/csv/' . $form_id)->with('error', 'Please upload .csv only.');
            }
            //-------------Image Validation----------------------------------



            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $path = $request->file('importFile')->getRealPath();
            $data = Excel::load($path)->get();
            $exist_rec = '';

            if ($data->count()) {

                foreach ($data as $key => $value) {

                    $exist = Tbl_formleads::where('email', $value->email)->where('form_id', $form_id)->get();

                    if (count($exist) > 0) {
                        $exist_rec .= $value->email . ', ';
                        //                        echo $value->email . ' exists <br>';
                    } else {
                        //                        echo $value->email . ' not exists <br>';
                        $formdata = array(
                            'uid' => $uid,
                            'first_name' => $value->first_name,
                            'last_name' => $value->last_name,
                            'email' => $value->email,
                            'mobile' => $value->mobile,
                            'website' => $value->website,
                            'notes' => $value->notes,
                            'form_id' => $form_id,
                        );

                        $arr[] = $formdata;
                    }
                }
                ////                echo json_encode($arr);
                if (!empty($arr)) {
                    Tbl_formleads::insert($arr);
                }

                if ($exist_rec != '') {
                    $with_status = 'info';
                    $with_message = trim($exist_rec, ",") . ' already exists. Remaing Uploaded successfully...!';
                }

                if ($exist_rec == '') {
                    $with_status = 'success';
                    $with_message = 'Uploaded successfully...!';
                }

                return redirect('/webtolead/formleads/' . $form_id)->with($with_status, $with_message);
            } else {
                return redirect('/webtolead/importformleads/csv/' . $form_id)->with('error', "Please check uploaded file. Data don't exist.");
            }
        } else {
            return redirect('/webtolead/importformleads/csv/' . $form_id)->with('error', 'Please upload file.');
        }
    }

    public function exportFormleads($type, $form_id)
    {

        $uid = Auth::user()->id;
        $deals = DB::table('tbl_formleads')
            ->where('tbl_formleads.form_id', $form_id)
            ->leftJoin('tbl_forms', 'tbl_forms.form_id', '=', 'tbl_formleads.form_id')
            ->select([
                'tbl_formleads.fl_id',
                'tbl_formleads.form_id',
                'tbl_formleads.first_name',
                'tbl_formleads.last_name',
                'tbl_formleads.email',
                'tbl_formleads.mobile',
                'tbl_formleads.website',
                'tbl_formleads.uid',
                'tbl_formleads.notes',
                'tbl_forms.post_url as post_url'
            ])->get();

        //        echo json_encode($deals);

        $deals_decode = json_decode($deals, true);

        $sheetName = 'formleads_' . date('d_m_Y_h_i_s');

        return Excel::create(
            $sheetName,
            function ($excel) use ($deals_decode, $sheetName) {
                $excel->sheet($sheetName, function ($sheet) use ($deals_decode) {
                    $sheet->fromArray($deals_decode);
                });
            }
        )->download($type);
    }

    public function deleteAllforms(Request $request)
    {

        //        return $request->input('id');

        $ids = $request->input('id');
        $result = Tbl_forms::whereIn('form_id', $ids)->update(array('active' => 0));
        if ($result) {
            Tbl_formleads::whereIn('form_id', $ids)->update(array('active' => 0));
        }
        return $result;
    }

    public function deleteAllformleads(Request $request)
    {
        $ids = $request->input('id');
        return Tbl_formleads::whereIn('fl_id', $ids)->update(array('active' => 0));
    }

    // public function getEmbedCode($form_id, $key)    //Request $request
    // {
    //     // $key = $request->input('key');
    //     // $form_id = $request->input('form_id');

    //     $form = Tbl_forms::find($form_id); //$form_id
    //     // echo json_encode($form);
    //     // exit();

    //     $previewform = '';
    //     if (($key == $form->form_key) && ($form_id == $form->form_id)) {



    //         $captcha_script = '';
    //         $captcha_div = '';
    //         if (($form->site_key != '') && ($form->secret_key != '')) {
    //             //                <div class="g-recaptcha" data-sitekey="6LdixH0UAAAAAJGo_rlykZn_tUtXU-6cMdFyqU_7"></div> 
    //             //                <button class="g-recaptcha" data-sitekey="6LeE0n0UAAAAAL8EEDPDguDMws-RVzlb086O6Zhk" data-callback="YourOnSubmitFn">Submit</button>


    //             $captcha_script = "<script src='https://www.google.com/recaptcha/api.js?onload=onloadCallback' async defer ></script><br>";
    //             $captcha_script .= "<script><br>";
    //             $captcha_script .= "var onloadCallback = function() {<br>";
    //             $captcha_script .= "    grecaptcha.execute();<br>";
    //             $captcha_script .= "};<br>";

    //             $captcha_script .= "function setResponse(response) { <br>";
    //             $captcha_script .= "    document.getElementById('captcha-response').value = response; <br>";
    //             $captcha_script .= "}<br>";
    //             $captcha_script .= "</script><br>";
    //             $captcha_div = '<div class="g-recaptcha" data-sitekey="' . $form->site_key . '" data-badge="inline" data-size="invisible" data-callback="setResponse" ></div>';
    //         }
    //         $previewform = $captcha_script;
    //         $previewform .= '<form name="formLead" action="' . url('leadgenerate/submitcontact') . '" method="post" enctype="multipart/form-data"><br>';
    //         $previewform .= '<input type="hidden" name="_token" id="csrf-token" value="' . csrf_token() . '"/><br>';
    //         $previewform .= '<input type="hidden" name="uid" id="uid" value="' . $form->uid . '" /><br>';
    //         $previewform .= '<input type="hidden" name="form_id" id="form_id" value="' . $form->form_id . '" /><br>';
    //         $previewform .= '<input type="hidden" name="purl" id="purl" value="' . $form->post_url . '" /><br>';
    //         $previewform .= '<input type="hidden" name="rurl" id="rurl" value="' . $form->redirect_url . '" /><br>';
    //         $previewform .= '<img src="' . url('leadgenerate/formviews/' . $form->form_id) . '" width="1" height="1" border="0" style="display:none;"/><br>';
    //         $previewform .= '<label>Contact Us</label><br>';
    //         $previewform .= '<label>Fields marked with an <span style="color:#ff0000;">*</span> are required</label><br><br>';
    //         $previewform .= '<label>Full Name <span style="color:#ff0000;">*</span> </label><br><br>';
    //         $previewform .= '<input class="form-control" type="text" name="firstName"  id="firstName" required pattern="[a-zA-Z\s]+"/><br><br>';
    //         $previewform .= '<label>Email Id <span style="color:#ff0000;">*</span></label><br><br>';
    //         $previewform .= '<input class="form-control" type="text" name="emailid"  id="emailid" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$"/><br><br>';
    //         $previewform .= '<label>Phone </label><br><br>';
    //         $previewform .= '<input class="form-control" type="text" name="mobile" id="mobile" maxlength="15" pattern="\d*"/><br><br>';
    //         $previewform .= '<label>Website </label><br><br>';
    //         $previewform .= '<input class="form-control" type="text" name="website" id="website" /><br><br>';
    //         $previewform .= '<label>Message </label><br><br>';
    //         $previewform .= '<textarea name="notes" id="notes" pattern="[A-Za-z0-9]{1,500}"></textarea><br><br>';
    //         $previewform .= '<input type="hidden" id="captcha-response" name="captcha-response" /><br><br>';
    //         $previewform .= $captcha_div . '<br>';
    //         $previewform .= '<input type="submit" name="submitLead"/><br><br>';
    //         $previewform .= '</form>';
    //     }
    //     //        
    //     //        
    //     //        return json_encode($request->input());


    //     return $previewform;
    // }

    public function restoreAllforms(Request $request)
    {

        //        return $request->input('id');

        $ids = $request->input('id');
        $result = Tbl_forms::whereIn('form_id', $ids)->update(array('active' => 1));
        if ($result) {
            Tbl_formleads::whereIn('form_id', $ids)->update(array('active' => 1));
        }
        return $result;
    }

    public function restoreAllformleads(Request $request)
    {
        $ids = $request->input('id');
        return Tbl_formleads::whereIn('fl_id', $ids)->update(array('active' => 1));
    }
}

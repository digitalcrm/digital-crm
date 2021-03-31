<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Tbl_leads;
use App\Tbl_countries;
use App\Tbl_states;
use App\Tbl_industrytypes;
use App\Tbl_leadsource;
use App\Tbl_leadstatus;
use App\Tbl_Accounts;
use App\User;
use App\Tbl_forms;
use App\Tbl_formleads;

class WebtoleadController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('test:webtolead-list', ['only' => ['index', 'show']]);
        $this->middleware('test:webtolead-create', ['only' => ['create', 'store']]);
        $this->middleware('test:webtolead-edit', ['only' => ['edit', 'update']]);
        $this->middleware('test:webtolead-delete', ['only' => ['destroy', 'formdelete', 'deleteAllforms']]);
        $this->middleware('test:webtolead-import', ['only' => ['import', 'importData']]);
        $this->middleware('test:webtolead-export', ['only' => ['export', 'exportData']]);
        $this->middleware('test:webtolead-formleads', ['only' => ['formleads']]);
        $this->middleware('test:webtolead-formleads-view', ['only' => ['formleadView']]);
        $this->middleware('test:webtolead-formleads-delete', ['only' => ['formleadDelete', 'deleteAllformleads']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderby('id', 'asc')->get(['id', 'name']);

        $useroptions = "<option value='All' selected>All</option>";
        //        $i = 0;
        $uid = 'All';
        foreach ($users as $userdetails) {
            //            if ($i == 0) {
            //                $selected = 'selected';
            //                $uid = $userdetails->id;
            //            } else {
            //                $selected = '';
            //            }
            //            $i++;
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";   // . "  " . $selected
        }

        $data['useroptions'] = $useroptions;
        $forms = $this->getForms($uid);

        $data['total'] = $forms['total'];
        $data['table'] = $forms['table'];

        return view('admin.webtolead.index')->with("data", $data);
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
        $form = Tbl_forms::find($id);
        return view('admin.webtolead.show')->with('form', $form);
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
        return view('admin.webtolead.edit')->with('form', $form);
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
            'title' => 'required|max:255',
            'post_url' => 'required|url|max:255',
            'redirect_url' => 'required|url|max:255',
            'from_mail' => 'required|email|max:255',
        ]);


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
            return redirect('admin/webtolead/' . $id . '/edit')->with('error', 'Post url alredy exists');
        } else {
            $form = Tbl_forms::find($id);
            $form->title = $request->input('title');
            $form->post_url = $request->input('post_url');
            $form->redirect_url = $request->input('redirect_url');
            $form->from_mail = $request->input('from_mail');
            $form->subject = $request->input('subject');
            $form->message = $request->input('message');
            $res = $form->save();

            if ($res > 0) {
                return redirect('admin/webtolead')->with('success', 'Form Updated Successfully...!');
            } else {
                return redirect('admin/webtolead')->with('error', 'Error occurred. Please try again...!');
            }
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

    public function getForms($uid)
    {
        if ($uid == 'All') {
            $forms = Tbl_forms::where('active', 1)->orderBy('form_id', 'desc')->get();
        } else {
            $forms = Tbl_forms::where('uid', $uid)->orderBy('form_id', 'desc')->where('active', 1)->get();
        }

        $total = count($forms);
        if ($total > 0) {
            $formstable = '<table id="formsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            //            $formstable .= '<th width="2"><input type="checkbox" id="selectAll"></th>';
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

                $formleads = Tbl_formleads::where('form_id', $formdetails->form_id)->where('active', 1)->get();

                $formstable .= '<tr>';
                //                $formstable .= '<td><input type="checkbox" class="checkAll" id="' . $formdetails->form_id . '"></td>';
                $formstable .= '<td><a href="' . url('admin/webtolead/formleads/' . $formdetails->form_id) . '">' . $formdetails->title . '</a>&nbsp;</td>';
                $formstable .= '<td>' . $formdetails->post_url . '</td>';
                $formstable .= '<td>' . $formdetails->views . '</td>';
                $formstable .= '<td><a href="' . url('admin/webtolead/formleads/' . $formdetails->form_id) . '">' . count($formleads) . '</a></td>';
                $formstable .= '<td>0</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '<td><a class="btn btn-sm badge badge-info py-1"  href="#" onclick="return previewForm(' . $formdetails->form_id . ')">Preview</a></td>';
                $formstable .= '<td><a class="btn btn-sm badge badge-warning py-1" href="#" onclick="return embedCode(' . $formdetails->form_id . ')">Embed Code</a></td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                      <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                  </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item text-default text-btn-space" href="' . url('admin/webtolead/' . $formdetails->form_id) . '">View</a>
                        <a class="dropdown-item text-default text-btn-space" href="' . url('admin/webtolead/' . $formdetails->form_id . '/edit') . '">Edit</a>
                        <a class="dropdown-item text-default text-btn-space" href="' . url('admin/webtolead/formdelete/' . $formdetails->form_id) . '">Delete</a>
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
    }

    public function formdelete($id)
    {
        $forms = Tbl_forms::find($id);
        $forms->active = 0;
        $forms->save();

        //----------Invoice-------------------
        Tbl_formleads::where('form_id', '=', $id)->update(['active' => 0]);

        return redirect('admin/webtolead')->with('success', 'Deleted Successfully...!');
    }

    public function formleads($id)
    {
        $forms = Tbl_forms::find($id);
        $formleads = Tbl_formleads::where('form_id', $id)->where('active', 1)->get();
        $total = 0;
        if (count($formleads) > 0) {
            $formstable = '<table id="formleadsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            //            $formstable .= '<th width="2"><input type="checkbox" id="selectAll"></th>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Email</th>';
            $formstable .= '<th>Mobile</th>';
            $formstable .= '<th>Website</th>';
            $formstable .= '<th>Created</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($formleads as $formdetails) {
                $formstable .= '<tr>';
                //                $formstable .= '<td><input type="checkbox" class="checkAll" id="' . $formdetails->fl_id . '"></td>';
                $formstable .= '<td><a href="' . url('admin/webtolead/viewformlead/' . $formdetails->fl_id) . '">' . $formdetails->first_name . '</a>&nbsp;</td>';
                $formstable .= '<td>' . $formdetails->email . '</td>';
                //' . url('admin/mails/mailsend/formleads/' . $formdetails->fl_id) . '
                $formstable .= '<td>' . $formdetails->mobile . '</td>';
                $formstable .= '<td>' . $formdetails->website . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                      <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                  </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item text-default text-btn-space" href="' . url('admin/webtolead/viewformlead/' . $formdetails->fl_id) . '">View</a>
                        <a class="dropdown-item text-default text-btn-space" href="' . url('admin/webtolead/deleteformlead/' . $formdetails->fl_id . '/' . $id) . '">Delete</a>
                      </div>
                    </div>';
                $formstable .= '</td>';
                $formstable .= '</tr>';

                //                <li><a href="' . url('admin/webtolead/deleteformlead/' . $formdetails->fl_id . '/' . $id) . '">Delete</a></li>
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';

            $total = count($formleads);
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;
        $data['forms'] = $forms;

        return view('admin.webtolead.leads')->with("data", $data);
    }

    public function formleadView($id)
    {
        $formleads = Tbl_formleads::find($id);
        $form = Tbl_forms::find($formleads->form_id);
        $data['form'] = $form;
        $data['formleads'] = $formleads;

        $addtoleadLink = ($formleads->lead == 0) ? url('admin/webtolead/addtolead/' . $formleads->fl_id . '/' . $formleads->form_id) : '#';
        $addtoleadbutton = ($formleads->lead == 0) ? 'Add to lead' : 'Added';

        $data['addtoleadLink'] = $addtoleadLink;
        $data['addtoleadbutton'] = $addtoleadbutton;

        return view('admin.webtolead.showlead')->with('data', $data);
    }

    public function formleadDelete($id, $form_id)
    {
        $formleads = Tbl_formleads::find($id);
        $formleads->active = 0;
        $formleads->save();

        return redirect('admin/webtolead/formleads/' . $form_id)->with('success', 'Form Lead Deleted Successfully...!');
    }

    public function deleteAllforms(Request $request)
    {
        $ids = $request->input('id');
        return Tbl_forms::whereIn('form_id', $ids)->update(array('active' => 0));
    }

    public function deleteAllformleads(Request $request)
    {
        $ids = $request->input('id');
        return Tbl_formleads::whereIn('fl_id', $ids)->update(array('active' => 0));
    }

    public function previewForm(Request $request)
    {
        $form_id = $request->input('form_id');
        $type = $request->input('type');
        $form = Tbl_forms::find($form_id);
        
        if ($type == "preview") {
            $previewform = '<form name="formLead" action="#" method="post" enctype="multipart/form-data">';
            $previewform .= '<input class="form-control" type="hidden" name="uid" id="uid" value="" />';
            $previewform .= '<input class="form-control" type="hidden" name="form_id" id="form_id" value="" />';
            $previewform .= '<input class="form-control" type="hidden" name="purl" id="purl" value="" />';
            $previewform .= '<input class="form-control" type="hidden" name="rurl" id="rurl" value="" />';
            $previewform .= '<img src="#" width="1" height="1" border="0" style="display:none;"/>';
            $previewform .= '<label>Contact Us</label>';
            $previewform .= '<label>Fields marked with an <span style="color:#ff0000;">*</span> are required</label><br>';
            $previewform .= '<label>Full Name <span style="color:#ff0000;">*</span> </label>';
            $previewform .= '<input class="form-control mb-3" type="text" name="firstName"  id="firstName" required pattern="[a-zA-Z\s]+"/>';
            $previewform .= '<label>Email Id <span style="color:#ff0000;">*</span></label>';
            $previewform .= '<input class="form-control mb-3" type="text" name="emailid"  id="emailid" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$"/>';
            $previewform .= '<label>Phone </label>';
            $previewform .= '<input class="form-control mb-3" type="text" name="mobile" id="mobile" maxlength="15" pattern="\d*"/>';
            $previewform .= '<label>Website </label>';
            $previewform .= '<input class="form-control mb-3" type="text" name="website" id="website" />';
            $previewform .= '<label>Message </label>';
            $previewform .= '<textarea class="form-control mb-3" name="notes" id="notes" pattern="[A-Za-z0-9]{1,500}"></textarea>';
            $previewform .= '<input class="btn btn-primary" type="submit" name="submitLead"/><br>';
            $previewform .= '</form>';
        }
        if ($type == "embed code") {

            $captcha_script = '';
            $captcha_div = '';
            if (($form->site_key != '') && ($form->secret_key != '')) {
               
                $captcha_script = "&lt;script src='https://www.google.com/recaptcha/api.js?onload=onloadCallback' async defer &gt;&lt;/script&gt;<br>";
                $captcha_script .= "&lt;script&gt;<br>";
                $captcha_script .= "var onloadCallback = function() {<br>";
                $captcha_script .= "    grecaptcha.execute();<br>";
                $captcha_script .= "};<br>";

                $captcha_script .= "function setResponse(response) { <br>";
                $captcha_script .= "    document.getElementById('captcha-response').value = response; <br>";
                $captcha_script .= "}<br>";
                $captcha_script .= "&lt;/script&gt;<br>";
                $captcha_div = '&lt;div class="g-recaptcha" data-sitekey="' . $form->site_key . '" data-badge="inline" data-size="invisible" data-callback="setResponse" &gt;&lt;/div&gt;';
            }
            $previewform = $captcha_script;
            $previewform .= '&lt;form name="formLead" action="' . url('leadgenerate/submitcontact') . '" method="post" enctype="multipart/form-data"&gt;<br>';
            $previewform .= '&lt;input type="hidden" name="_token" id="csrf-token" value="' . csrf_token() . '"/&gt;<br>';
            $previewform .= '&lt;input type="hidden" name="uid" id="uid" value="' . $form->uid . '" /&gt;<br>';
            $previewform .= '&lt;input type="hidden" name="form_id" id="form_id" value="' . $form->form_id . '" /&gt;<br>';
            $previewform .= '&lt;input type="hidden" name="purl" id="purl" value="' . $form->post_url . '" /&gt;<br>';
            $previewform .= '&lt;input type="hidden" name="rurl" id="rurl" value="' . $form->redirect_url . '" /&gt;<br>';
            $previewform .= '&lt;img src="' . url('leadgenerate/formviews/' . $form->form_id) . '" width="1" height="1" border="0" style="display:none;"/&gt;<br>';
            $previewform .= '&lt;label&gt;Contact Us&lt;/label&gt;<br>';
            $previewform .= '&lt;label&gt;Fields marked with an &lt;span style="color:#ff0000;"&gt;*&lt;/span&gt; are required&lt;/label&gt;&lt;br&gt;<br>';
            $previewform .= '&lt;label&gt;Full Name &lt;span style="color:#ff0000;"&gt;*&lt;/span&gt; &lt;/label&gt;&lt;br&gt;<br>';
            $previewform .= '&lt;input type="text" name="firstName"  id="firstName" required pattern="[a-zA-Z\s]+"/&gt;&lt;br&gt;<br>';
            $previewform .= '&lt;label&gt;Email Id &lt;span style="color:#ff0000;"&gt;*&lt;/span&gt;&lt;/label&gt;&lt;br&gt;<br>';
            $previewform .= '&lt;input type="text" name="emailid"  id="emailid" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$"/&gt;&lt;br&gt;<br>';
            $previewform .= '&lt;label>Phone &lt;/label&gt;&lt;br&gt;<br>';
            $previewform .= '&lt;input type="text" name="mobile" id="mobile" maxlength="15" pattern="\d*"/&gt;&lt;br&gt;<br>';
            $previewform .= '&lt;label>Website &lt;/label&gt;&lt;br&gt;<br>';
            $previewform .= '&lt;input type="text" name="website" id="website" /&gt;&lt;br&gt;<br>';
            $previewform .= '&lt;label>Message &lt;/label&gt;&lt;br&gt;<br>';
            $previewform .= '&lt;textarea name="notes" id="notes" pattern="[A-Za-z0-9]{1,500}"&gt;&lt;/textarea&gt;&lt;br&gt;<br>';
            $previewform .= '&lt;input type="hidden" id="captcha-response" name="captcha-response" /&gt;&lt;br&gt;<br>';
            $previewform .= $captcha_div . '<br>';
            $previewform .= '&lt;input type="submit" name="submitLead"/&gt;&lt;br&gt;<br>';
            $previewform .= '&lt;/form&gt;';
        }
        return $previewform;
    }
}

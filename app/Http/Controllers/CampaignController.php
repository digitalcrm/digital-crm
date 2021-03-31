<?php

namespace App\Http\Controllers;

//  Required Packages
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mail;
use Illuminate\Filesystem\Filesystem;
use PhpImap;
//  Models
use App\User;
use App\Tbl_campaigns;
use App\Tbl_camp_status;
use App\Tbl_camp_types;
use App\Tbl_campaign_mails;
use App\Tbl_mail_templates;
use App\currency;
use App\Tbl_Accounts;
use App\Tbl_contacts;
use App\Tbl_leads;
use App\Tbl_formleads;
use App\Tbl_forms;

class CampaignController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $uid = Auth::user()->id;
        $campaigns = Tbl_campaigns::with('tbl_camp_status')->with('tbl_camp_type')->where('active', 1)->where('uid', $uid)->get();
        //        echo json_encode($campaigns);
        //        exit();

        $total = count($campaigns);
        if ($total > 0) {
            $formstable = '<table id="accountsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th width="2"></th>';
            $formstable .= '<th>Campaign Name</th>';
            $formstable .= '<th>Start Date</th>';
            $formstable .= '<th>End Date</th>';
            $formstable .= '<th>Status</th>';
            $formstable .= '<th>Type</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($campaigns as $formdetails) {

                $status = ($formdetails->tbl_camp_status != '') ? $formdetails->tbl_camp_status->status : '';
                $type = ($formdetails->tbl_camp_type != '') ? $formdetails->tbl_camp_type->type : '';

                $formstable .= '<tr>';
                $formstable .= '<td><div class="custom-control custom-checkbox"><input type="checkbox" class="checkAll custom-control-input" id="' . $formdetails->camp_id . '"><label class="custom-control-label" for="' . $formdetails->camp_id . '"></label></div></td>';
                $formstable .= '<td><a href="' . url('campaigns/mails/sent/' . $formdetails->camp_id) . '">' . $formdetails->name . '</a></td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->start_date)) . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->end_date)) . '</td>';
                $formstable .= '<td>' . $status . ' </td>';
                $formstable .= '<td>' . $type . ' </td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <div class="dropdown-menu" role="menu">
                    <li><a class="dropdown-item text-default text-btn-space" href="' . url('campaigns/' . $formdetails->camp_id) . '">View</a></li>
                    <li><a class="dropdown-item text-default text-btn-space" href="' . url('campaigns/' . $formdetails->camp_id . '/edit') . '">Edit</a></li>
                    <li><a class="dropdown-item text-default text-btn-space" href="' . url('campaigns/deletecamp/' . $formdetails->camp_id) . '">Delete</a></li>
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
        return view('auth.campaigns.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        if ($user->can('create', Tbl_campaigns::class)) {
            $statuses = Tbl_camp_status::all();
            $statusoptions = '<option value="">Select ...</option>';
            foreach ($statuses as $status) {
                $statusoptions .= '<option value="' . $status->status_id . '">' . $status->status . '</option>';
            }

            $types = Tbl_camp_types::all();
            $typeoptions = '<option value="">Select ...</option>';
            foreach ($types as $type) {
                $typeoptions .= '<option value="' . $type->type_id . '">' . $type->type . '</option>';
            }

            $data['statusoptions'] = $statusoptions;
            $data['typeoptions'] = $typeoptions;

            return view('auth.campaigns.create')->with('data', $data);
        } else {
            return redirect('/campaigns');
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
        //        exit();

        $this->validate($request, [
            'name' => 'required|max:255',
            'status' => 'required',
            'type' => 'required',
            'start_date' => 'required|date_format:d-m-Y',
            'end_date' => 'required|date_format:d-m-Y',
        ]);

        $formdata = array(
            'uid' => Auth::user()->id,
            'name' => $request->input('name'),
            'status_id' => $request->input('status'),
            'type_id' => $request->input('type'),
            'start_date' => date('Y-m-d', strtotime($request->input('start_date'))),
            'end_date' => date('Y-m-d', strtotime($request->input('end_date'))),
            'budget' => $request->input('budget'),
            'actual_cost' => $request->input('actual_cost'),
            'expected_cost' => $request->input('expected_cost'),
            'expected_revenue' => $request->input('expected_revenue'),
            'objective' => $request->input('objective'),
            'description' => $request->input('description')
        );

        $campaign = Tbl_campaigns::create($formdata);

        //        echo json_encode($campaign);

        if ($campaign->camp_id > 0) {
            return redirect('/campaigns')->with('success', 'Campaign created successfully...');
        } else {
            return redirect('/campaigns')->with('error', 'Error occurred. Please try again later...');
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

        //        exit();
        $campaign = Tbl_campaigns::with('tbl_camp_status')->with('tbl_camp_type')->find($id);

        if ($user->can('view', $campaign)) {
            //        echo json_encode($campaign);
            $uid = Auth::user()->id;
            $cr_id = Auth::user()->cr_id;
            $currency = currency::find($cr_id);
            $campaign['currency'] = $currency;

            return view('auth.campaigns.show')->with('data', $campaign);
        } else {
            return redirect('/campaigns');
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
        $user = Auth::user();
        $campaign = Tbl_campaigns::with('tbl_camp_status')->with('tbl_camp_type')->find($id);

        if ($user->can('view', $campaign)) {
            $statuses = Tbl_camp_status::all();
            $statusoptions = '<option value="">Select ...</option>';
            foreach ($statuses as $status) {
                $statusSelected = (($campaign->tbl_camp_status != '') && ($campaign->tbl_camp_status->status_id == $status->status_id)) ? 'selected' : '';
                $statusoptions .= '<option value="' . $status->status_id . '" ' . $statusSelected . '>' . $status->status . '</option>';
            }

            $types = Tbl_camp_types::all();
            $typeoptions = '<option value="">Select ...</option>';
            foreach ($types as $type) {
                $typeSelected = (($campaign->tbl_camp_type != '') && ($campaign->tbl_camp_type->type_id == $type->type_id)) ? 'selected' : '';
                $typeoptions .= '<option value="' . $type->type_id . '" ' . $typeSelected . '>' . $type->type . '</option>';
            }

            $data['statusoptions'] = $statusoptions;
            $data['typeoptions'] = $typeoptions;
            $data['campaign'] = $campaign;

            return view('auth.campaigns.edit')->with('data', $data);
        } else {
            return redirect('/campaigns');
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
        $user = Auth::user();
        //        echo json_encode($request->input());
        //        exit();

        $campaign = Tbl_campaigns::find($id);

        if ($user->can('update', $campaign)) {

            $this->validate($request, [
                'name' => 'required|max:255',
                'status' => 'required',
                'type' => 'required',
                'start_date' => 'required|date_format:d-m-Y',
                'end_date' => 'required|date_format:d-m-Y',
            ]);


            $campaign->name = $request->input('name');
            $campaign->status_id = $request->input('status');
            $campaign->type_id = $request->input('type');
            $campaign->start_date = date('Y-m-d', strtotime($request->input('start_date')));
            $campaign->end_date = date('Y-m-d', strtotime($request->input('end_date')));
            $campaign->budget = $request->input('budget');
            $campaign->actual_cost = $request->input('actual_cost');
            $campaign->expected_cost = $request->input('expected_cost');
            $campaign->expected_revenue = $request->input('expected_revenue');
            $campaign->objective = $request->input('objective');
            $campaign->description = $request->input('description');
            $campaign->save();

            return redirect('/campaigns')->with('success', 'Updated successfully...');
        } else {
            return redirect('/campaigns');
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

    public function getForms(Request $request)
    {
        $type = $request->input('type');
        //        return $type;
        $uid = Auth::user()->id;
        $forms = Tbl_forms::where('uid', $uid)->where('active', 1)->get(['form_id', 'title', 'post_url']);
        //        return json_encode($forms);
        //        return count($forms);
        if (count($forms) > 0) {
            $formOptions = "<option value='0'>Select Form ...</option>";
            foreach ($forms as $form) {
                $formOptions .= "<option value='" . $form->form_id . "'>" . $form->title . " - " . $form->post_url . "</option>";
            }
        }
        return $formOptions;
    }

    public function getCampaignSendto(Request $request)
    {
        $type = $request->input('type');
        $form = $request->input('form');

        //        echo $type;
        //        exit();

        $uid = Auth::user()->id;
        $receivers = "";

        //  Accounts
        if ($type == 1) {
            $receivers = Tbl_Accounts::where('uid', $uid)
                ->where('active', 1)
                ->get(['acc_id as id', 'email as value', 'name as text']);
        }

        //  Contacts
        if ($type == 2) {
            $receivers = Tbl_contacts::select('cnt_id as id', 'email as value', DB::raw('CONCAT(first_name, " ", last_name) AS text'))
                ->where('uid', $uid)
                ->where('active', 1)
                ->get();
        }

        //  Leads
        if ($type == 3) {
            $receivers = Tbl_leads::select('ld_id as id', 'email as value', DB::raw('CONCAT(first_name, " ", last_name) AS text'))
                ->where('uid', $uid)
                ->where('active', 1)
                ->get();
        }

        //  Form Leads
        if ($type == 4) {
            $receivers = Tbl_formleads::select('fl_id as id', 'email as value', DB::raw('CONCAT(COALESCE(first_name,""), " ", COALESCE(last_name,"")) AS text'))
                ->where('uid', $uid)
                ->where('form_id', $form)
                ->where('active', 1)
                ->get();
        }

        return json_encode($receivers);
        //        exit();
        //        $receiverOptions = "";
        //
        //        if (count($receivers) > 0) {
        //            $receiverOptions .= "<option value='all'>All</option>";
        //            foreach ($receivers as $receiver) {
        //                $receiverOptions .= "<option value='" . $receiver->id . "'>" . $receiver->name . " - " . $receiver->email . "</option>";
        //            }
        //        }
        //        return $receiverOptions;
    }

    public function showCampaignMail($id)
    {
        $camp_mail = Tbl_campaign_mails::with('tbl_campaigns')->with('tbl_mail_templates')->find($id);
        //        echo json_encode($camp_mail);
        return view('auth.campaigns.mailpreview')->with('data', $camp_mail);
    }

    public function editCampaignMail($id)
    {
        $camp_mail = Tbl_campaign_mails::with('tbl_campaigns')->with('tbl_mail_templates')->find($id);
        //        echo json_encode($camp_mail);

        $templateOptions = '<option value="">Select ...</option>';
        $templates = Tbl_mail_templates::where('active', 1)->get();
        foreach ($templates as $template) {
            $selected = (($camp_mail->temp_id > 0) && ($template->temp_id == $camp_mail->temp_id)) ? 'selected' : '';
            $templateOptions .= '<option value="' . $template->temp_id . '" ' . $selected . '>' . $template->name . '</option>';
        }

        //        echo $templateOptions;
        //        exit();
        $camp_mail['templateOptions'] = $templateOptions;


        return view('auth.campaigns.mailedit')->with('data', $camp_mail);
    }

    public function createCampaignMails($id)
    {
        $uid = Auth::user()->id;
        $cr_id = Auth::user()->cr_id;
        $currency = currency::find($cr_id);
        $campaign = Tbl_campaigns::with('tbl_camp_status')->with('tbl_camp_type')->find($id);
        $campaign['currency'] = $currency;

        //  Email Templates

        $templates = Tbl_mail_templates::where('uid', $uid)->where('active', 1)->get();
        $templateOptions = '<option value="0">Select ...</option>';
        foreach ($templates as $template) {
            $templateOptions .= '<option value="' . $template->temp_id . '">' . $template->name . '</option>';
        }

        $campaign['templateOptions'] = $templateOptions;
        return view('auth.campaigns.mailcreate')->with('data', $campaign);
    }

    public function storeCampaignMails(Request $request, $id)
    {
        //        echo json_encode($request->input());
        //        exit();
        //        $uid = Auth::user()->id;
        //        $camp_id = $id;

        $this->validate($request, [
            'sendto' => 'required',
            'subject' => 'required|max:255',
            'message' => 'required',
        ]);

        $attachments = '';

        if ($request->hasfile('attachment')) {
            $file = $request->file('attachment');

            $extensions = array("xls", "xlsx", "xlm", "xla", "xlc", "xlt", "xlw", "csv", "pdf", "jpeg", "jpg", "png", "gif", "doc", "docx", "txt");

            $result = array($request->file('attachment')->getClientOriginalExtension());

            if (in_array($result[0], $extensions)) {
                $filetype = $file->getClientOriginalExtension();

                $fileArray = array('attachment' => $file);

                // Tell the validator that this file should be an image
                $rules = array(
                    'attachment' => 'max:10000' // max 10000kb
                );

                // Now pass the input and rules into the validator
                $validator = Validator::make($fileArray, $rules);

                // Check to see if validation fails or passes
                if ($validator->fails()) {
                    return redirect('/campaigns/preview/campaignmails/' . $id)->with('error', 'Please upload only 10000kb file');
                } else {
                    $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
                    $file->move('uploads/campaigns/', $name);  //public_path().
                    $filename = '/uploads/campaigns/' . $name;

                    $fileUrl = base_path($filename);

                    //                    $attachments = url('/uploads/campaigns/' . $name);
                    $attachments = '/uploads/campaigns/' . $name;
                }
            }
        }

        $uid = Auth::user()->id;
        $camp_id = $id;
        $temp_id = $request->input('emailtemplates');
        $toIds = $request->input('toIds');
        $toNames = $request->input('toNames');
        $toEmails = $request->input('toEmails');
        $toTypes = $request->input('typeto');
        $message = $request->input('message');
        $subject = $request->input('subject');

        $formdata = array(
            'camp_id' => $camp_id,
            'temp_id' => $temp_id,
            'uid' => $uid,
            'subject' => $subject,
            'message' => $message,
            'emails' => $toEmails,
            'names' => $toNames,
            'type' => $toTypes,
            'attachments' => $attachments,
            'ids' => $toIds,
            'mail_type' => 1,
            'message_type' => 1,
            'status' => 2,
        );

        $camp_mail = Tbl_campaign_mails::create($formdata);
        $camp_mail->attachments = base_path('/uploads/campaigns/') . $camp_mail->attachments;
        //      echo json_encode($camp_mail);
        //      return view('auth.campaigns.mailpreview')->with('data', $camp_mail);
        //      --------------------------------
        //      Sending Mail
        //        $title = 'Digital CRM';
        //        $from_email = Auth::user()->email;
        //        $from_name = Auth::user()->name;
        //
        //        $to_email = explode(",", $toEmails);
        //
        //        Mail::send(['html' => 'emails.default'], ['title' => $title, 'content' => $message], function ($message) use($from_email, $to_email, $subject, $attachments, $from_name) {
        //            $message->subject($subject);
        //            $message->from($from_email, $from_name);   //'sandeepindana@yahoo.com'
        //
        //            if ($attachments != '') {
        //                $message->attach($attachments);
        //            }
        //
        //            $message->to($to_email);   //'isandeep.1609@gmail.com'
        //        });
        //      --------------------------------
        //      Sending Mail

        return redirect('campaigns/mails/preview/' . $camp_mail->cmail_id);
    }

    public function previewCampaignMails($id)
    {

        $camp_mail = Tbl_campaign_mails::with('tbl_campaigns')
            ->with('tbl_mail_templates')
            ->find($id);
        //        echo json_encode($camp_mail);
        return view('auth.campaigns.mailpreview')->with('data', $camp_mail);
    }

    public function get_time_ago($time)
    {
        $time_difference = time() - $time;

        if ($time_difference < 1) {
            return 'less than 1 second ago';
        }
        $condition = array(
            12 * 30 * 24 * 60 * 60 => 'year',
            30 * 24 * 60 * 60 => 'month',
            24 * 60 * 60 => 'day',
            60 * 60 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($condition as $secs => $str) {
            $d = $time_difference / $secs;

            if ($d >= 1) {
                $t = round($d);
                return $t . ' ' . $str . ($t > 1 ? 's' : '') . ' ago';    //'about ' . 
            }
        }
    }

    public function sentCampaignMails($id)
    {
        $uid = Auth::user()->id;
        $cr_id = Auth::user()->cr_id;
        $currency = currency::find($cr_id);
        //        exit();
        $campaign = Tbl_campaigns::with('tbl_camp_status')->with('tbl_camp_type')->find($id);
        //        echo json_encode($campaign);

        $campaign['currency'] = $currency;


        $camp_mails = Tbl_campaign_mails::where('camp_id', $id)->where('active', 1)->with('tbl_campaigns')->with('tbl_mail_templates')->orderBy('cmail_id', 'desc')->get();
        $total = count($camp_mails);

        if ($total > 0) {
            $formstable = '<table class="table table-hover table-striped">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th width="2"></th>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Subject</th>';
            $formstable .= '<th>Attachment</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($camp_mails as $mail) {

                $tdAttachment = ($mail->attachments != '') ? '<i class="fa fa-paperclip"></i>' : '';

                $formstable .= '<tr>';
                $formstable .= '<td><input type="checkbox" class="checkAll" id="' . $mail->cmail_id . '"></td>';
                $formstable .= '<td class="mailbox-name"><a href="' . url('campaigns/mails/show/' . $mail->cmail_id) . '" style="text-decoration:none;">' . substr($mail->names, 0, 25) . ' &lt' . substr($mail->emails, 0, 25) . '&gt</td>';  //from_address
                $formstable .= '<td class="mailbox-subject"><b>' . $mail->subject . '</b></td>';
                $formstable .= '<td class="mailbox-attachment">' . $tdAttachment . '</td>';
                $formstable .= '<td class="mailbox-date">' . $this->get_time_ago(strtotime($mail->created_at)) . '</td>';  //date('d/m/Y', strtotime($mail->date))
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }
        $campaign['total'] = $total;
        $campaign['table'] = $formstable;

        return view('auth.campaigns.sent')->with('data', $campaign);
    }

    public function updateCampaignMail(Request $request, $id)
    {
        //        echo json_encode($request->input());
        //        exit();
        //        $uid = Auth::user()->id;
        //        $camp_id = $id;

        $this->validate($request, [
            'sendto' => 'required',
            'subject' => 'required|max:255',
            'message' => 'required',
        ]);

        $attachments = '';

        if ($request->hasfile('attachment')) {
            $file = $request->file('attachment');

            $extensions = array("xls", "xlsx", "xlm", "xla", "xlc", "xlt", "xlw", "csv", "pdf", "jpeg", "jpg", "png", "gif", "doc", "docx", "txt");

            $result = array($request->file('attachment')->getClientOriginalExtension());

            if (in_array($result[0], $extensions)) {
                $filetype = $file->getClientOriginalExtension();

                $fileArray = array('attachment' => $file);

                // Tell the validator that this file should be an image
                $rules = array(
                    'attachment' => 'max:10000' // max 10000kb
                );

                // Now pass the input and rules into the validator
                $validator = Validator::make($fileArray, $rules);

                // Check to see if validation fails or passes
                if ($validator->fails()) {
                    return redirect('/campaigns/preview/campaignmails/' . $id)->with('error', 'Please upload only 10000kb file');
                } else {
                    $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
                    $file->move('uploads/campaigns/', $name);  //public_path().
                    $filename = '/uploads/campaigns/' . $name;

                    $fileUrl = base_path($filename);

                    //                    $attachments = url('/uploads/campaigns/' . $name);
                    $attachments = '/uploads/campaigns/' . $name;
                }
            }
        }

        $uid = Auth::user()->id;
        $camp_id = $id;
        $temp_id = $request->input('emailtemplates');
        $toIds = $request->input('toIds');
        $toNames = $request->input('toNames');
        $toEmails = $request->input('toEmails');
        $toTypes = $request->input('typeto');
        $message = $request->input('message');
        $subject = $request->input('subject');

        $formdata = array(
            'camp_id' => $camp_id,
            'temp_id' => $temp_id,
            'uid' => $uid,
            'subject' => $subject,
            'message' => $message,
            'emails' => $toEmails,
            'names' => $toNames,
            'type' => $toTypes,
            'attachments' => $attachments,
            'ids' => $toIds,
            'mail_type' => 1,
            'message_type' => 1,
            'status' => 2,
        );

        $camp_mail = Tbl_campaign_mails::create($formdata);
        $camp_mail->attachments = base_path('/uploads/campaigns/') . $camp_mail->attachments;
        //        echo json_encode($camp_mail);
        //        return view('auth.campaigns.mailpreview')->with('data', $camp_mail);

        return redirect('campaigns/mails/preview/' . $camp_mail->cmail_id);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->input('id');
        //        return $ids;
        return Tbl_campaigns::whereIn('camp_id', $ids)->update(array('active' => 0));
    }

    public function deleteCamp($ids)
    {
        $user = Auth::user();
        $campaign = Tbl_campaigns::find($ids);

        if ($user->can('update', $campaign)) {
            $res = Tbl_campaigns::where('camp_id', $ids)->update(array('active' => 0));
            if ($res) {
                return redirect('/campaigns')->with('success', 'Deleted successfully...');
            } else {
                return redirect('/campaigns')->with('error', 'Error occurred. Please try again later...');
            }
        } else {
            return redirect('/campaigns');
        }
    }

    public function deleteCampaignMails(Request $request)
    {
        $ids = $request->input('id');
        //        return $ids;
        return Tbl_campaign_mails::whereIn('cmail_id', $ids)->update(array('active' => 0));
    }

    public function restoreCampaignMails(Request $request)
    {
        $ids = $request->input('id');
        //        return $ids;
        return Tbl_campaign_mails::whereIn('cmail_id', $ids)->update(array('active' => 1));
    }

    public function sendCampaignMail($id)
    {
        $camp_mail = Tbl_campaign_mails::with('tbl_campaigns')->with('tbl_mail_templates')->find($id);
        $subject = $camp_mail->subject;
        $message = $camp_mail->message;
        $attachments = base_path($camp_mail->attachments);

        $from_email = Auth::user()->id;
        $from_name = Auth::user()->name;

        $toEmails = $camp_mail->emails;


        //      Sending Mail
        //      --------------------------------
        $title = 'Digital CRM';
        //        $from_email = Auth::user()->email;
        //        $from_name = Auth::user()->name;

        $to_email = explode(",", $toEmails);

        Mail::send(['html' => 'emails.default'], ['title' => $title, 'content' => $message], function ($message) use ($from_email, $to_email, $subject, $attachments, $from_name) {
            $message->subject($subject);
            //            $message->from($from_email);   //'sandeepindana@yahoo.com'

            if ($attachments != '') {
                $message->attach($attachments);
            }

            $message->to($to_email);   //'isandeep.1609@gmail.com'
        });
        //      --------------------------------
        //      Sending Mail
        //        return view('auth.campaigns.mailpreview')->with('data', $camp_mail);
        if (count(Mail::failures()) > 0) {
            return redirect('campaigns/mails/sent/' . $data['camp_id'])->with('error', 'Failed. Please try again later..!');
        } else {
            return redirect('campaigns/mails/sent/' . $data['camp_id'])->with('success', 'Mail sent successfully..!');
        }
    }

    public function trashCampaignMails($id)
    {
        $uid = Auth::user()->id;
        $cr_id = Auth::user()->cr_id;
        $currency = currency::find($cr_id);
        //        exit();
        $campaign = Tbl_campaigns::with('tbl_camp_status')->with('tbl_camp_type')->find($id);
        //        echo json_encode($campaign);

        $campaign['currency'] = $currency;


        $camp_mails = Tbl_campaign_mails::where('camp_id', $id)->where('active', 0)->with('tbl_campaigns')->with('tbl_mail_templates')->get();
        $total = count($camp_mails);

        if ($total > 0) {
            $formstable = '<table class="table table-hover table-striped">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th width="2"></th>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Subject</th>';
            $formstable .= '<th>Attachment</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($camp_mails as $mail) {

                $tdAttachment = ($mail->attachments != '') ? '<i class="fa fa-paperclip"></i>' : '';

                $formstable .= '<tr>';
                $formstable .= '<td><input type="checkbox" class="checkAll" id="' . $mail->cmail_id . '"></td>';
                $formstable .= '<td class="mailbox-name"><a href="' . url('campaigns/mails/show/' . $mail->cmail_id) . '" style="text-decoration:none;">' . substr($mail->names, 0, 25) . ' &lt' . substr($mail->emails, 0, 25) . '&gt</td>';  //from_address
                $formstable .= '<td class="mailbox-subject"><b>' . $mail->subject . '</b></td>';
                $formstable .= '<td class="mailbox-attachment">' . $tdAttachment . '</td>';
                $formstable .= '<td class="mailbox-date">' . $this->get_time_ago(strtotime($mail->created_at)) . '</td>';  //date('d/m/Y', strtotime($mail->date))
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }
        $campaign['total'] = $total;
        $campaign['table'] = $formstable;

        return view('auth.campaigns.trash')->with('data', $campaign);
    }
}

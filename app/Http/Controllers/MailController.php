<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Mail;
use Illuminate\Filesystem\Filesystem;
// use PhpImap;
//---------Models---------------
use App\Tbl_emails;
use App\Tbl_emailtemplates;
use App\Tbl_emailcategory;
use App\User;
use App\Admin;
use App\Tbl_leads;
use App\Tbl_Accounts;
use App\Tbl_contacts;
use App\Tbl_formleads;
use App\Tbl_forms;
use App\Tbl_mails;
use App\Tbl_smtpsettings;
use App\tbl_verifyuser;
//---------Controllers---------------
use App\Http\Controllers\LeadController;
use App\Http\Controllers\HomeController;
//---------------------Events---------------------
use Event;
use App\Events\SendMail;
// use App\Events\InboundMail;

class MailController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('test:webmails', ['only' => ['index', 'show', 'create', 'store', 'edit', 'update', 'delete', 'deleteAll', 'deleteMail', 'sendMail']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $uid = Auth::user()->id;
        $mails = $this->getMails($uid);
        $total = count($mails);

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
            foreach ($mails as $mail) {

                $tdAttachment = ($mail->attachments != '') ? '<i class="fa fa-paperclip"></i>' : '';

                $formstable .= '<tr>';
                $formstable .= '<td><div class="custom-control custom-checkbox"><input type="checkbox" class="checkAll custom-control-input" id="' . $mail->mail_id . '"><label class="custom-control-label" for="' . $mail->mail_id . '"></label></div></td>';
                $formstable .= '<td class="mailbox-name"><a href="' . url('mails/' . $mail->mail_id) . '" style="text-decoration:none;">' . substr($mail->names, 0, 25) . '</td>';  //from_address   . ' ' . substr($mail->emails, 0, 25)
                $formstable .= '<td class="mailbox-subject"><b>' . $mail->subject . '</b></td>';
                $formstable .= '<td class="mailbox-attachment">' . $tdAttachment . '</td>';
                $formstable .= '<td class="mailbox-date">' . date('d/m/Y', strtotime($mail->date)) . '</td>';  //date('d/m/Y', strtotime($mail->date))
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }
        $data['total'] = $total;
        $data['table'] = $formstable;
        return view('auth.mails.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $leadArray = array();

        $user = Auth::user();
        if ($user->can('create', Tbl_Accounts::class)) {

            $uid = Auth::user()->id;
            $leads = Tbl_leads::where('uid', $uid)->where('active', 1)->get();
            foreach ($leads as $lead) {
                $leadArr['id'] = $lead->ld_id;
                $leadArr['text'] = $lead->first_name . ' ' . $lead->last_name;
                $leadArr['title'] = array($lead->email, 3);
                // $leadArr['type'] = 3;
                $leadArray[] = $leadArr;
            }

            $accounts = Tbl_Accounts::where('uid', $uid)->where('active', 1)->get();
            foreach ($accounts as $lead) {
                $leadArr['id'] = $lead->acc_id;
                $leadArr['text'] = $lead->name;
                $leadArr['title'] = array($lead->email, 1);
                // $leadArr['type'] = 1;
                $leadArray[] = $leadArr;
            }

            $contacts = Tbl_contacts::where('uid', $uid)->where('active', 1)->get();
            foreach ($contacts as $lead) {
                $leadArr['id'] = $lead->cnt_id;
                $leadArr['text'] = $lead->first_name . ' ' . $lead->last_name;
                $leadArr['title'] = array($lead->email, 2);
                // $leadArr['type'] = 2;
                $leadArray[] = $leadArr;
            }

            $formleads = Tbl_formleads::where('uid', $uid)->where('active', 1)->get();
            foreach ($formleads as $lead) {
                $leadArr['id'] = $lead->fl_id;
                $leadArr['text'] = $lead->first_name . ' ' . $lead->last_name;
                $leadArr['title'] = array($lead->email, 4);
                // $leadArr['type'] = 4;
                $leadArray[] = $leadArr;
            }

            // echo json_encode($leadArray);
            // exit();


            $data['leads'] = $leadArray;

            return view('auth.mails.create')->with('data', $data);
        } else {
            return redirect('/mails');
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

        $uid = Auth::user()->id;
        $toIds = $request->input('toIds');
        $toNames = $request->input('toNames');
        $toTitles = $request->input('toTitles');
        // $toEmails = $request->input('toEmails');
        // $toTypes = $request->input('toTypes');
        $message = $request->input('message');
        $subject = $request->input('subject');


        $attachments = '';
        $fileUrl = '';
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
                    return redirect('mails/create')->with('error', 'Please upload only 10000kb file');
                } else {
                    $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
                    $file->move('uploads/attachments/', $name);  //public_path().
                    $filename = '/uploads/attachments/' . $name;

                    $fileUrl = base_path($filename);

                    $attachments = url('/uploads/attachments/' . $name);
                }
            }
        }

        $titles = explode(",", $toTitles);
        $Emails = array();
        $Types = array();

        $formdata = array();

        for ($i = 0; $i < count($titles); $i++) {
            if (($i % 2) == 0) {
                $Emails[] = $titles[$i];
                $Types[] = $titles[$i + 1];
                $formdata[] = array(
                    'uid' => $uid,
                    'subject' => $subject,
                    'message' => $message,
                    'emails' => $titles[$i],
                    'names' => $toNames,
                    'type' => $titles[$i + 1],
                    'attachments' => $attachments,
                    'ids' => $toIds,
                    'mail_type' => 1,
                    'message_type' => 1,
                );
            }
            // if (($i % 2) > 0) {
            //     
            // }


        }

        // $data['emails'] = $Emails;
        // $data['types'] = $Types;
        // echo json_encode($data);
        // exit();

        $toEmails = implode(',', $Emails);
        $toTypes = implode(',', $Types);

        $from_email = Auth::user()->email;

        //        echo json_encode($formdata);
        //
        //        exit(0);

        // if ($attachments != '') {
        //     $formdata['attachments'] = $attachments;
        // }

        $mail = Tbl_mails::insert($formdata);

        $to_email = explode(",", $toEmails);

        if ($mail) {
            $title = 'Digital CRM';

            Mail::send(['html' => 'emails.default'], ['title' => $title, 'content' => $message], function ($message) use ($from_email, $to_email, $subject, $attachments, $fileUrl) {
                $message->subject($subject);
                $message->from($from_email);   //'sandeepindana@yahoo.com'

                if ($attachments != '') {
                    $message->attach($fileUrl);
                }

                $message->to($to_email);   //'isandeep.1609@gmail.com'
            });

            if (count(Mail::failures()) > 0) {
                return redirect('mails/create')->with('error', 'Failed. Please try again later..!');
            } else {
                // $updateStatus = Tbl_mails::find($mail->mail_id);
                Tbl_mails::where('status', 0)->update(array('status' => 1));
                // $updateStatus->status = 1;
                // $updateStatus->save();
                return redirect('mails')->with('success', 'Mail Sent Successfully..!');
            }
        } else {
            return redirect('mails/create')->with('error', 'Failed. Please try again later..!');
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
        $maildetails = Tbl_mails::with('tbl_smtp_settings')->find($id);

        if ($user->can('view', $maildetails)) {

            //        echo json_encode($maildetails);

            return view('auth.mails.show')->with('data', $maildetails);
        } else {
            return redirect('/mails');
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
        //
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

    public function deleteMail($id)
    {
        $user = Auth::user();
        $mail = Tbl_mails::find($id);

        if ($user->can('delete', $mail)) {

            $mail->active = 0;
            $mail->save();

            return redirect('mails')->with('success', 'Mail deleted Successfully');
        } else {
            return redirect('/mails');
        }
    }

    public function deletedMails()
    {
        $uid = Auth::user()->id;
        $mails = Tbl_mails::where('uid', $uid)->where('mail_type', 1)->where('active', 0)->orderBy('mail_id', 'desc')->get();
        $total = count($mails);

        if ($total > 0) {
            $formstable = '<table class="table table-hover table-striped">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th width="2"></th>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Subject</th>';
            $formstable .= '<th>Attachment</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($mails as $mail) {

                $tdAttachment = ($mail->attachments != '') ? '<i class="fa fa-paperclip"></i>' : '';

                $formstable .= '<tr>';
                $formstable .= '<td><div class="custom-control custom-checkbox"><input type="checkbox" class="checkAll custom-control-input" id="' . $mail->mail_id . '"><label class="custom-control-label" for="' . $mail->mail_id . '"></label></div></td>';
                $formstable .= '<td class="mailbox-name"><a href="#" style="text-decoration:none;">' . $mail->names . ' &lt' . $mail->emails . '&gt</td>';  //from_address
                $formstable .= '<td class="mailbox-subject"><b>' . $mail->subject . '</b></td>';
                $formstable .= '<td class="mailbox-attachment">' . $tdAttachment . '</td>';
                $formstable .= '<td class="mailbox-date">' . $this->get_time_ago(strtotime($mail->updated_at)) . '</td>';  //date('d/m/Y', strtotime($mail->date))
                $formstable .= '<td><a class="badge badge-pill badge-success" href="' . url('mails/restore/' . $mail->mail_id) . '">Restore</a></td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }
        $data['total'] = $total;
        $data['table'] = $formstable;

        return view('auth.mails.trash')->with('data', $data);
    }

    public function sendMail($from, $to, $message, $subject, $title)
    {

        Mail::send(['html' => 'emails.default'], ['title' => $title, 'content' => $message], function ($message) use ($from, $to, $subject) {
            $message->subject($subject);
            $message->from($from, config('app.name'));   //'sandeepindana@yahoo.com'
            $message->to($to);   //'isandeep.1609@gmail.com'
        });

        if (count(Mail::failures()) > 0) {
            //            echo 'Failed to send password reset email, please try again.';
            return FALSE;
        } else {
            //            echo "Success";
            return TRUE;
        }
    }

    public function getLeadOptions()
    {
        $uid = Auth::user()->id;

        $leads = Tbl_leads::where('uid', $uid)->where('active', 1)->get(['ld_id', 'first_name', 'last_name', 'email']);
        $leadselect = '<option value="" selected>Select Lead</option>';

        return $leadselect;
    }

    public function UserLoginMail($userdetails, $user_agent, $ipaddress)
    {

        $department = Tbl_emailcategory::where('category', 'User Login Admin')->first();
        $template = Tbl_emailtemplates::where('ecat_id', $department->ecat_id)->first();
        $emails = Tbl_emails::where('ecat_id', $department->ecat_id)->first();

        $home = new HomeController();

        $browser = $home->getBrowser($user_agent);
        $os = $home->getOS($user_agent);
        $time = date('d-m-Y h:i:s');

        // $locationArr = $home->geoCheckIP($ipaddress);
        $country = "";
        $state = "";
        $city = "";
        $location = "";
        // if ($locationArr != '') {
        //     $country = ($locationArr['country'] == 'not found') ? '' : $locationArr['country'];
        //     $state = ($locationArr['state'] == 'not found') ? '' : $locationArr['state'];
        //     $city = ($locationArr['town'] == 'not found') ? '' : $locationArr['town'];
        // } else {

        // }

        // $location = trim($state . ', ' . $city, ", ");

        $subject = $template->subject;
        $user = $userdetails->name;
        $name = $userdetails->name;
        $email = $userdetails->email;
        $click = '<a href="https://digitalcrm.com/">click</a>';
        $title = 'Digital CRM';
        $message = $template->message;
        $from_mail = $userdetails->email; //'aynsoft@digitalcrm.com'
        $to_email = $emails->mail;
        $reset = '<a href="' . route('password.request') . '">reset</a>';

        $beforeStr = $message;
        preg_match_all('/{(\w+)}/', $beforeStr, $matches);
        $afterStr = $beforeStr;
        foreach ($matches[0] as $index => $var_name) {
            if (isset(${$matches[1][$index]})) {
                $afterStr = str_replace($var_name, ${$matches[1][$index]}, $afterStr);
            }
        }
        $content = $afterStr;
        return $this->sendMail($from_mail, $to_email, $content, $subject, $title);
    }

    public function LoginMail($userdetails, $user_agent, $ipaddress)
    {

        $department = Tbl_emailcategory::where('category', 'Login')->first();
        $template = Tbl_emailtemplates::where('ecat_id', $department->ecat_id)->first();
        $emails = Tbl_emails::where('ecat_id', $department->ecat_id)->first();

        $home = new HomeController();

        $browser = $home->getBrowser($user_agent);
        $os = $home->getOS($user_agent);
        $time = date('d-m-Y h:i:s');

        $locationArr = $home->geoCheckIP($ipaddress);

        if ($locationArr != '') {
            $country = ($locationArr['country'] == 'not found') ? '' : $locationArr['country'];
            $state = ($locationArr['state'] == 'not found') ? '' : $locationArr['state'];
            $city = ($locationArr['town'] == 'not found') ? '' : $locationArr['town'];
        } else {
            $country = "";
            $state = "";
            $city = "";
        }

        $location = trim($state . ', ' . $city, ", ");

        $subject = $template->subject;
        $user = $userdetails->name;
        $click = '<a href="https://digitalcrm.com/">click</a>';
        $title = 'Digital CRM';
        $message = $template->message;
        $from_mail = $emails->mail; //'aynsoft@digitalcrm.com'
        $to_email = $userdetails->email;
        $reset = '<a href="' . route('password.request') . '">reset</a>';

        $beforeStr = $message;
        preg_match_all('/{(\w+)}/', $beforeStr, $matches);
        $afterStr = $beforeStr;
        foreach ($matches[0] as $index => $var_name) {
            if (isset(${$matches[1][$index]})) {
                $afterStr = str_replace($var_name, ${$matches[1][$index]}, $afterStr);
            }
        }
        $content = $afterStr;
        return $this->sendMail($from_mail, $to_email, $content, $subject, $title);
    }

    public function registrationMailAdmin($data)
    {
        $department = Tbl_emailcategory::where('category', 'Registration')->first();
        $template = Tbl_emailtemplates::where('ecat_id', $department->ecat_id)->first();
        $emails = Tbl_emails::where('ecat_id', $department->ecat_id)->first();

        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $ipaddress = $_SERVER['REMOTE_ADDR'];

        $home = new HomeController();

        $browser = $home->getBrowser($useragent);
        $os = $home->getOS($useragent);
        $time = date('d-m-Y h:i:s');

        // $location = $home->geoCheckIP($ipaddress);
        // $country = ($location['country'] == 'not found') ? '' : $location['country'];
        // $state = ($location['state'] == 'not found') ? '' : $location['state'];
        // $city = ($location['town'] == 'not found') ? '' : $location['town'];

        $country = '';
        $state =  '';
        $city =  '';

        $user = $data['name'];
        $email = $data['email'];
        $title = 'Digital CRM';

        $subject = $template->subject;
        $message = $template->message;
        $from_mail = $data['email']; //'aynsoft@digitalcrm.com'
        $to_email = $emails->mail;

        $beforeStr = $template->message;
        preg_match_all('/{(\w+)}/', $beforeStr, $matches);
        $afterStr = $beforeStr;
        foreach ($matches[0] as $index => $var_name) {
            if (isset(${$matches[1][$index]})) {
                $afterStr = str_replace($var_name, ${$matches[1][$index]}, $afterStr);
            }
        }
        $content = $afterStr;
        return $this->sendMail($from_mail, $to_email, $content, $subject, $title);
    }

    public function registrationMailUser($data)
    {

        $userdetails = User::with('tbl_verifyuser')->find($data->id);

        $this->registrationMailAdmin($data);

        $department = Tbl_emailcategory::where('category', 'Registration')->first();
        $template = Tbl_emailtemplates::where('ecat_id', $department->ecat_id)->first();
        $emails = Tbl_emails::where('ecat_id', $department->ecat_id)->first();

        $subject = $template->subject;
        $user = $data['name'];
        $click = '<a href="' . url('/user/verify/' . $userdetails->tbl_verifyuser->token) . '">click</a>';
        $title = 'Digital CRM';
        $message = $template->message;
        $from_mail = $emails->mail; //'aynsoft@digitalcrm.com'
        $to_email = $data['email'];

        $beforeStr = $message;
        preg_match_all('/{(\w+)}/', $beforeStr, $matches);
        $afterStr = $beforeStr;
        foreach ($matches[0] as $index => $var_name) {
            if (isset(${$matches[1][$index]})) {
                $afterStr = str_replace($var_name, ${$matches[1][$index]}, $afterStr);
            }
        }
        $content = $afterStr;
        return $this->sendMail($from_mail, $to_email, $content, $subject, $title);
    }

    public function registrationMailSubUser($data)
    {

        $this->registrationMailSubUsertoUser($data);
        $this->registrationMailSubUsertoAdmin($data);

        $department = Tbl_emailcategory::where('category', 'Sub User Registration')->first();
        $template = Tbl_emailtemplates::where('ecat_id', $department->ecat_id)->first();
        $emails = Tbl_emails::where('ecat_id', $department->ecat_id)->first();

        $userdetails = User::find($data->user);

        $subUser = User::with('tbl_verifyuser')->find($data->id);

        //        echo json_encode($userdetails);
        //        exit();

        $mainuser = $userdetails->name;
        $subject = $template->subject;
        $user = $data['name'];
        //        $click = '<a href="https://digitalcrm.com">click</a>';
        $click = '<a href="' . url('/user/verify/' . $subUser->tbl_verifyuser->token) . '">click</a>';
        $title = 'Digital CRM';
        $message = $template->message;
        $from_mail = $emails->mail; //'aynsoft@digitalcrm.com'
        $to_email = $data['email'];

        $beforeStr = $message;
        preg_match_all('/{(\w+)}/', $beforeStr, $matches);
        $afterStr = $beforeStr;
        foreach ($matches[0] as $index => $var_name) {
            if (isset(${$matches[1][$index]})) {
                $afterStr = str_replace($var_name, ${$matches[1][$index]}, $afterStr);
            }
        }
        $content = $afterStr;
        return $this->sendMail($from_mail, $to_email, $content, $subject, $title);
    }

    public function registrationMailSubUsertoUser($data)
    {
        $department = Tbl_emailcategory::where('category', 'Sub User Registration to Main User')->first();
        $template = Tbl_emailtemplates::where('ecat_id', $department->ecat_id)->first();
        $emails = Tbl_emails::where('ecat_id', $department->ecat_id)->first();

        $userdetails = User::find($data->user);

        $user = $userdetails->name;
        $subject = $template->subject;
        $subuserName = $data['name'];
        $subuserEmail = $data['email'];
        $click = '<a href="#">click</a>';
        $title = 'Digital CRM';
        $message = $template->message;
        $from_mail = $emails->mail; //'aynsoft@digitalcrm.com'
        $to_email = $userdetails->email;

        $beforeStr = $message;
        preg_match_all('/{(\w+)}/', $beforeStr, $matches);
        $afterStr = $beforeStr;
        foreach ($matches[0] as $index => $var_name) {
            if (isset(${$matches[1][$index]})) {
                $afterStr = str_replace($var_name, ${$matches[1][$index]}, $afterStr);
            }
        }
        $content = $afterStr;
        return $this->sendMail($from_mail, $to_email, $content, $subject, $title);
    }

    public function registrationMailSubUsertoAdmin($data)
    {
        $department = Tbl_emailcategory::where('category', 'Sub User Registration to Administrator')->first();
        $template = Tbl_emailtemplates::where('ecat_id', $department->ecat_id)->first();
        $emails = Tbl_emails::where('ecat_id', $department->ecat_id)->first();

        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $ipaddress = $_SERVER['REMOTE_ADDR'];

        $home = new HomeController();

        $browser = $home->getBrowser($useragent);
        $os = $home->getOS($useragent);
        $time = date('d-m-Y h:i:s');

        $location = $home->geoCheckIP($ipaddress);

        $country = ($location['country'] == 'not found') ? '' : $location['country'];
        $state = ($location['state'] == 'not found') ? '' : $location['state'];
        $city = ($location['town'] == 'not found') ? '' : $location['town'];

        $subuser = $data['name'];
        $name = $data['name'];
        $email = $data['email'];
        $title = 'Digital CRM';

        $userdetails = User::find($data->user);

        $subject = $template->subject;
        $message = $template->message;

        $user = $userdetails->name;
        $click = '<a href="#">click</a>';
        $title = 'Digital CRM';

        $from_mail = $userdetails->email; //'aynsoft@digitalcrm.com'
        $to_email = $emails->mail;

        $beforeStr = $message;
        preg_match_all('/{(\w+)}/', $beforeStr, $matches);
        $afterStr = $beforeStr;
        foreach ($matches[0] as $index => $var_name) {
            if (isset(${$matches[1][$index]})) {
                $afterStr = str_replace($var_name, ${$matches[1][$index]}, $afterStr);
            }
        }
        $content = $afterStr;
        return $this->sendMail($from_mail, $to_email, $content, $subject, $title);
    }

    public function mailSend($type, $id)
    {

        $data = array();

        if ($type == 'accounts') {

            $account = Tbl_Accounts::find($id);
            $data['typehead'] = 'Account';
            $data['type'] = 1;
            $data['name'] = $account->name;
            $data['email'] = $account->email;
            $data['id'] = $account->acc_id;
        }
        if ($type == 'contacts') {

            $contact = Tbl_contacts::find($id);

            $data['type'] = 2;
            $data['typehead'] = 'Contact';
            $data['name'] = $contact->first_name . ' ' . $contact->last_name;
            $data['email'] = $contact->email;
            $data['id'] = $contact->cnt_id;
        }
        if ($type == 'leads') {

            $lead = Tbl_leads::find($id);

            $data['name'] = $lead->first_name . ' ' . $lead->last_name;
            $data['email'] = $lead->email;
            $data['type'] = 3;
            $data['typehead'] = 'Lead';
            $data['id'] = $lead->ld_id;
        }

        if ($type == 'formleads') {

            $lead = Tbl_formleads::find($id);

            $data['name'] = $lead->first_name . ' ' . $lead->last_name;
            $data['email'] = $lead->email;
            $data['type'] = 4;
            $data['typehead'] = 'Web to Lead';
            $data['id'] = $lead->fl_id;
        }

        if ($type == 'subusers') {

            $user = User::find($id);

            $data['name'] = $user->name;
            $data['email'] = $user->email;
            $data['type'] = 5;
            $data['typehead'] = 'Sub User';
            $data['id'] = $user->id;
        }

        return view('auth.mails.createmail')->with('data', $data);
    }

    public function mailSendAction(Request $request)
    {
        //        echo json_encode($request->input());
        //        exit();

        $userEmail = $request->input('userEmail');
        $to_email = 'testone@digitalcrm.com';
        //        $from_email = Auth::user()->email;
        $from_email = 'demouser@digitalcrm.com';
        $userName = $request->input('userName');
        $message = $request->input('message');
        $subject = $request->input('subject');
        $userType = $request->input('userType');
        $userId = $request->input('userId');
        $uid = Auth::user()->id;

        $redirect = "mails";

        if ($userType == 1) {       //  Accounts
            $redirect = "accounts";
        }
        if ($userType == 2) {       //  Contacts
            $redirect = "contacts";
        }
        if ($userType == 3) {       //  Leads
            $redirect = "leads";
        }
        if ($userType == 4) {       //  web to lead
            $formlead = Tbl_formleads::find($userId);
            $redirect = "webtolead/formleads/" . $formlead->fl_id;
        }
        if ($userType == 5) {       //  Sub Users
            $redirect = "subusers";
        }

        $formdata = array(
            'uid' => $uid,
            'subject' => $subject,
            'message' => $message,
            'emails' => $userEmail,
            'names' => $userName,
            'type' => $userType,
            //            'attachments' => $attachments,
            'ids' => $userId,
            'mail_type' => 1,
            'message_type' => 1,
        );

        //        echo json_encode($formdata);
        //
        //        exit(0);


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
                    return redirect($redirect)->with('error', 'Please upload only 10000kb file');
                } else {
                    $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
                    $file->move('uploads/attachments/', $name);  //public_path().
                    $filename = '/uploads/attachments/' . $name;

                    $fileUrl = base_path($filename);

                    $attachments = url('/uploads/attachments/' . $name);
                }
            }
        }

        if ($attachments != '') {
            $formdata['attachments'] = $attachments;
        }


        $mail = Tbl_mails::create($formdata);

        if ($mail->mail_id > 0) {
            $title = 'Digital CRM';

            Mail::send(['html' => 'emails.default'], ['title' => $title, 'content' => $message], function ($message) use ($from_email, $to_email, $subject, $attachments) {
                $message->subject($subject);
                $message->from($from_email);   //'sandeepindana@yahoo.com'

                if ($attachments != '') {
                    $message->attach($attachments);
                }

                $message->to($to_email);   //'isandeep.1609@gmail.com'
            });

            if (count(Mail::failures()) > 0) {
                return redirect($redirect)->with('error', 'Failed. Please try again later..!');
            } else {
                $updateStatus = Tbl_mails::find($mail->mail_id);
                $updateStatus->status = 1;
                $updateStatus->save();
                return redirect('mails')->with('success', 'Mail Sent Successfully..!');
            }
        } else {
            return redirect($redirect)->with('error', 'Failed. Please try again later..!');
        }
    }

    public function getMails($uid)
    {
        //	mail_type
        return Tbl_mails::where('uid', $uid)->where('mail_type', 1)->where('active', 1)->orderBy('mail_id', 'desc')->get();
    }

    public function printMail($id)
    {
        $maildetails = Tbl_mails::with('tbl_smtp_settings')->find($id);
        return view('auth.mails.print')->with('data', $maildetails);
    }

    public function inbox()
    {
        $uid = Auth::user()->id;
        return $this->getInboundMails($uid);
    }

    public function getUserInboundMails($uid)
    {
        $res = Event::fire(new InboundMail($uid));
        //        return '';
        //        $uid = Auth::user()->id;
        //        return $this->getInboundMails($uid);
    }

    public function getInboundMails($uid)
    {
        $formdata = array();
        $res = '';

        //  Last id inserted in table

        $smtpsettings = Tbl_smtpsettings::where('uid', $uid)->first();
        //        return json_encode($smtpsettings);
        //        echo json_encode($smtpsettings);
        //        exit();
        if (!empty($smtpsettings)) {

            //            return 'Not empty';
            //            exit();

            $ss_id = $smtpsettings->ss_id;
            $host = $smtpsettings->incomingserver;
            $email = $smtpsettings->username;
            $password = $smtpsettings->password;
            $port = $smtpsettings->incomingport;


            $lastMails = Tbl_mails::where('uid', $uid)->where('mail_type', 2)->where('ss_id', $ss_id)->first();

            $lastId = ($lastMails != '') ? $lastMails->id : 0;

            $mailbox = new PhpImap\Mailbox('{' . $host . ':' . $port . '/imap/ssl/novalidate-cert}INBOX', $email, $password, __DIR__);

            // Read all messaged into an array:
            $mailsIds = $mailbox->searchMailbox('ALL');

            //        echo json_encode($mailsIds);
            //        exit(0);

            $totalmailsIds = count($mailsIds);
            $latestmailsIds = array_reverse($mailsIds);

            //            echo 'Total ' . $totalmailsIds . "<br>";
            //            echo 'Latest Id ' . $latestmailsIds[0] . "<br>";
            //            echo 'Last Id ' . $lastId . "<br>";
            //            echo $min = (int) $latestmailsIds[0] - (int) $lastId . "<br>";



            if (((int) $latestmailsIds[0] > (int) $lastId) && ($totalmailsIds > 0)) {

                $min = (int) $latestmailsIds[0] - $lastId;

                for ($i = 0; $i < $min; $i++) {
                    echo $latestmailsIds[$i];
                    $mail = $mailbox->getMail($latestmailsIds[$i]);

                    $Id = $mail->id;
                    $fromAddress = $mail->fromAddress;
                    $fromName = $mail->fromName;
                    $subject = $mail->subject;

                    if ($mail->textHtml != null) {
                        $message_type = 1;
                    }

                    if ($mail->textPlain != null) {
                        $message_type = 2;
                    }


                    $message = ($mail->textPlain != null) ? $mail->textPlain : $mail->textHtml;
                    //                    $message = ($mail->textHtml != null) ? $mail->textHtml : '';
                    $messageDate = $mail->date;

                    $toAddress = array();
                    $toNames = array();

                    foreach ($mail->to as $tomailids => $tonames) {
                        //                    echo $tomailids . ' ' . $tonames . '<br>';
                        $toAddress[] = $tomailids;
                        $toNames[] = $tonames;
                    }


                    $attachments = $mail->getAttachments();
                    $attachs = array();
                    $totalattachs = '';
                    if ($attachments != '') {

                        //                    $tdAttachment = '<i class="fa fa-paperclip"></i>';

                        $filesystem = new Filesystem;
                        foreach ($attachments as $attachment) {

                            $filePath = $attachment->filePath;
                            $filePaths = explode('/', $filePath);
                            $n = count($filePaths) - 1;

                            $file = $filePaths[$n];
                            unlink($file);

                            $path = "downloads\mails\attachments\\";
                            $filesystem->put(base_path($path) . $attachment->name, $attachment->filePath);

                            $attachs[] = $path = "downloads\mails\attachments\\" . $attachment->name;
                        }

                        $totalattachs = implode(",", $attachs);
                    }

                    $formdata[] = array(
                        'uid' => $uid,
                        'id' => $Id,
                        'ss_id' => $ss_id,
                        'subject' => $subject,
                        'from_address' => $fromAddress,
                        'from_name' => $fromName,
                        'message_type' => $message_type,
                        'message' => $message,
                        'date' => $messageDate,
                        'emails' => implode(',', $toAddress),
                        'names' => implode(',', $toNames),
                        'mail_type' => 2,
                        'attachments' => $totalattachs,
                    );
                }

                //                echo "<br>";
                //                echo json_encode($formdata);
                //                exit();
                $res = Tbl_mails::insert($formdata);
            }

            //        echo $res;

            if ($res) {
                return 'success';
            } else {
                return 'error';
            }
        } else {
            return 'please update SMTP Settings...!';
        }
    }

    //
    //    public function getInboundMails($uid) {
    //
    //
    //        $smtpsettings = Tbl_smtpsettings::where('uid', $uid)->first();
    //
    //        $host = $smtpsettings->incomingserver;
    //        $email = $smtpsettings->username;
    //        $password = $smtpsettings->password;
    //        $port = $smtpsettings->incomingport;
    //
    //        $mailbox = new PhpImap\Mailbox('{' . $host . ':' . $port . '/imap/ssl/novalidate-cert}INBOX', $email, $password, __DIR__);
    //
    //// Read all messaged into an array:
    //        $mailsIds = $mailbox->searchMailbox('ALL');
    //
    ////        echo json_encode($mailsIds);
    ////        exit(0);
    //
    //        $totalmailsIds = count($mailsIds);
    //        $latestmailsIds = array_reverse($mailsIds);
    //
    //
    //
    //        $max = ($totalmailsIds > 10) ? 10 : $totalmailsIds;
    //
    //
    //        if ($totalmailsIds > 0) {
    //            $formstable = '<table id=""  class="table table-hover table-striped">';
    //            $formstable .= '<tbody>';
    //            $i = 0;
    //            foreach ($latestmailsIds as $mailsId) {
    //                $i += 1;
    //                $mail = $mailbox->getMail($mailsId);
    //
    //                $attachments = $mail->getAttachments();
    //
    //                $tdAttachment = '';
    //
    //                if ($attachments != '') {
    //
    //                    $tdAttachment = '<i class="fa fa-paperclip"></i>';
    //
    //                    $filesystem = new Filesystem;
    //                    foreach ($attachments as $attachment) {
    //
    //                        $filePath = $attachment->filePath;
    //                        $filePaths = explode('/', $filePath);
    //                        $n = count($filePaths) - 1;
    //
    //                        $file = $filePaths[$n];
    //                        unlink($file);
    ////                    if (!unlink($file)) {
    //////                        echo ("Error deleting $file");
    ////                    } else {
    //////                        echo ("Deleted $file");
    ////                    }
    //
    //                        $path = "downloads\mails\attachments\\";
    //                        $filesystem->put(base_path($path) . $attachment->name, $attachment->filePath);
    //                    }
    //                }
    //
    //                $formstable .= '<tr>';
    //                $formstable .= '<td><input type="checkbox"></td>';
    //                $formstable .= '<td class="mailbox-name"><a href="#">' . $mail->fromName . ' ' . $mail->fromAddress . '</a></td>';
    //                $formstable .= '<td class="mailbox-subject"><b>' . $mail->subject . '</b></td>';
    //                $formstable .= '<td class="mailbox-attachment">' . $tdAttachment . '</td>';
    //                $formstable .= '<td class="mailbox-date">' . $this->get_time_ago(strtotime($mail->date)) . '</td>';  //date('d/m/Y', strtotime($mail->date))
    //                $formstable .= '</tr>';
    //
    //                if ($i == $max) {
    //                    break;
    //                }
    //            }
    //        } else {
    //            $formstable = 'No records available';
    //        }
    //        $data['total'] = $totalmailsIds;
    //        $data['table'] = $formstable;
    //
    //        return $data;
    //    }
    //------------------Mail Details--------------------------
    //                echo "Id : ";
    //                echo $mail->id . '<br>';
    //                echo "From Address/Name : ";
    //                echo $mail->fromAddress . '/' . $mail->fromName . '<br>';   // 
    //                echo "To Address : ";
    //                echo $mail->toString . '<br>';
    //                echo "Subject : ";
    //                echo $mail->subject . '<br>';
    //                echo "Message(textPlain) : ";
    //                echo ($mail->textPlain != null) ? $mail->textPlain : '' . '<br>';
    //                echo "Message(textHtml) : ";
    //                echo ($mail->textHtml != null) ? $mail->textHtml : '' . '<br>';

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

    public function deleteAll(Request $request)
    {
        $ids = $request->input('id');
        return Tbl_mails::whereIn('mail_id', $ids)->update(array('active' => 0));
    }


    public function restoreAll(Request $request)
    {
        $ids = $request->input('id');
        return Tbl_mails::whereIn('mail_id', $ids)->update(array('active' => 1));
    }

    public function restoreMail($id)
    {
        $mail = Tbl_mails::find($id);

        //        echo json_encode($mail);

        $mail->active = 1;
        $mail->save();
        return redirect('/mails')->with('success', 'Restored successfully...');
    }

    public function testCronjobMail()
    {
        $title = "Digital CRM";
        $message = "Testing cron job with mail at " . date('d-m-Y h:i:s a');
        $subject = "Testing cron job at " . date('d-m-Y h:i:s a');
        $attachments = '';
        $from_email = 'demouser@digitalcrm.com';
        $to_email = 'mainuser@digitalcrm.com';
        $result = Mail::send(['html' => 'emails.default'], ['title' => $title, 'content' => $message], function ($message) use ($from_email, $to_email, $subject, $attachments) {
            $message->subject($subject);
            $message->from($from_email);   //'sandeepindana@yahoo.com'

            if ($attachments != '') {
                $message->attach($attachments);
            }

            $message->to($to_email);   //'isandeep.1609@gmail.com'
        });

        return $result;
    }
}

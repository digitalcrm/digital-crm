<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Mail;
//---------Models---------------
use App\Tbl_emails;
use App\Tbl_emailtemplates;
use App\Tbl_departments;
use App\User;
use App\Admin;
use App\Client;
use App\Tbl_Accounts;
use App\Tbl_contacts;
use App\Tbl_leads;
use App\Tbl_formleads;
use App\Tbl_mails;
use App\Tbl_smtpsettings;
use App\Tbl_admin_mails;

class MailController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::user()->id;

        //        echo $id;

        $mails = $this->getMails($id);

        //        echo json_encode($mails);
        //        exit();

        $total = count($mails);

        if ($total > 0) {
            $formstable = '<table class="table table-hover table-striped">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th width="2"><input type="checkbox" id="selectAll"></th>';
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
                $formstable .= '<td><input type="checkbox" class="checkAll" id="' . $mail->mail_id . '"></td>';
                $formstable .= '<td class="mailbox-name"><a href="' . url('admin/mails/' . $mail->mail_id) . '" style="text-decoration:none;">' . $mail->names . ' &lt' . $mail->emails . '&gt</td>';  //from_address
                $formstable .= '<td class="mailbox-subject"><b>' . $mail->subject . '</b></td>';
                $formstable .= '<td class="mailbox-attachment">' . $tdAttachment . '</td>';
                $formstable .= '<td class="mailbox-date">' . $this->get_time_ago(strtotime($mail->date)) . '</td>';  //date('d/m/Y', strtotime($mail->date))
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }
        $data['total'] = $total;
        $data['table'] = $formstable;
        return view('admin.mails.index')->with('data', $data);    //index
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.mails.create');
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
        $maildetails = Tbl_admin_mails::find($id);
        //        echo json_encode($maildetails);
        //        exit();

        return view('admin.mails.show')->with('data', $maildetails);
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

    public function sent()
    {
        return view('admin.mails.sent');
    }

    public function registrationMail($user)
    {
    }

    public function sendMail($from, $to, $message, $subject, $title)
    {

        Mail::send(['html' => 'emails.default'], ['title' => $title, 'content' => $message], function ($message) use ($from, $to, $subject) {
            $message->subject($subject);
            $message->from($from);   //'sandeepindana@yahoo.com'
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
            $data['uid'] = $account->uid;
        }
        if ($type == 'contacts') {

            $contact = Tbl_contacts::find($id);

            $data['type'] = 2;
            $data['typehead'] = 'Contact';
            $data['name'] = $contact->first_name . ' ' . $contact->last_name;
            $data['email'] = $contact->email;
            $data['id'] = $contact->cnt_id;
            $data['uid'] = $contact->uid;
        }
        if ($type == 'leads') {

            $lead = Tbl_leads::find($id);

            $data['name'] = $lead->first_name . ' ' . $lead->last_name;
            $data['email'] = $lead->email;
            $data['type'] = 3;
            $data['typehead'] = 'Lead';
            $data['id'] = $lead->ld_id;
            $data['uid'] = $lead->uid;
        }

        if ($type == 'formleads') {

            $lead = Tbl_formleads::find($id);

            $data['name'] = $lead->first_name . ' ' . $lead->last_name;
            $data['email'] = $lead->email;
            $data['type'] = 4;
            $data['typehead'] = 'Web to Lead';
            $data['id'] = $lead->fl_id;
            $data['uid'] = $lead->uid;
        }

        if ($type == 'subusers') {

            $user = User::find($id);

            $data['name'] = $user->name;
            $data['email'] = $user->email;
            $data['type'] = 5;
            $data['typehead'] = 'Sub User';
            $data['id'] = $user->id;
            $data['uid'] = $user->user;
        }

        if ($type == 'users') {

            $user = User::find($id);

            $data['name'] = $user->name;
            $data['email'] = $user->email;
            $data['type'] = 6;
            $data['typehead'] = 'User';
            $data['id'] = $user->id;
            $data['uid'] = $user->id;
        }

        if ($type == 'admins') {

            $user = Admin::find($id);

            $data['name'] = $user->name;
            $data['email'] = $user->email;
            $data['type'] = 7;
            $data['typehead'] = 'Admin';
            $data['id'] = $user->id;
            $data['uid'] = $user->id;
        }

        if ($type == 'clients') {

            $user = Client::find($id);

            $data['name'] = $user->name;
            $data['email'] = $user->email;
            $data['type'] = 7;
            $data['typehead'] = 'Client';
            $data['id'] = $user->id;
            $data['uid'] = $user->id;
        }

        return view('admin.mails.createmail')->with('data', $data);
    }

    public function mailSendAction(Request $request)
    {

        //        echo json_encode(Auth::user());
        //        echo json_encode($request->input());
        //
        //        echo $to_email = $request->input('userEmail');
        //        echo $from_email = Auth::user()->email;
        //        exit(0);

        $userType = $request->input('userType');
        $type = '';

        if ($userType == 1) {
            $type = 'accounts';
        }

        if ($userType == 2) {
            $type = 'contacts';
        }

        if ($userType == 3) {
            $type = 'leads';
        }

        if ($userType == 4) {
            $type = 'formleads';
        }

        if ($userType == 5) {
            $type = 'subusers';
        }

        if ($userType == 6) {
            $type = 'users';
        }

        if ($userType == 7) {
            $type = 'clients';
        }

        //        $to_email = $request->input('userEmail');
        $to_email = 'testone@digitalcrm.com';
        $from_email = Auth::user()->email;
        //        $from_email = 'demouser@digitalcrm.com';
        $userName = $request->input('userName');
        $message = $request->input('message');
        $subject = $request->input('subject');
        //        $userType = $request->input('userType');
        $userId = $request->input('userId');
        $uid = $request->input('uid');

        $formdata = array(
            'aid' => Auth::user()->id,
            'uid' => $uid,
            'subject' => $subject,
            'message' => $message,
            'emails' => $to_email,
            'names' => $userName,
            'type' => $userType,
            //            'attachments' => $attachments,
            'ids' => $userId,
            'mail_type' => 1
        );

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
                    return redirect('admin/mails/' . $type . '/' . $userId)->with('error', 'Please upload only 10000kb file');
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

        //        echo json_encode($formdata);
        //
        //        exit(0);

        $mail = Tbl_admin_mails::create($formdata);
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
                return redirect('admin/mails/' . $type . '/' . $userId)->with('error', 'Failed. Please try again later..!');
            } else {
                $updateStatus = Tbl_admin_mails::find($mail->mail_id);
                $updateStatus->status = 1;
                $updateStatus->save();
                return redirect('admin/mails')->with('success', 'Mail Sent Successfully..!');
            }
        } else {
            return redirect('admin/mails/' . $type . '/' . $userId)->with('error', 'Failed. Please try again later..!');
        }
    }

    public function getMails($id)
    {
        return Tbl_admin_mails::where('aid', $id)->where('mail_type', 1)->where('active', 1)->orderBy('mail_id', 'desc')->get();
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

    public function deleteAll(Request $request)
    {
        $ids = $request->input('id');

        //        return $ids;

        return Tbl_admin_mails::whereIn('mail_id', $ids)->update(array('active' => 0));
    }

    public function deletedMails()
    {
        $uid = Auth::user()->id;
        $mails = Tbl_admin_mails::where('aid', $uid)->where('mail_type', 1)->where('active', 0)->orderBy('mail_id', 'desc')->get();
        $total = count($mails);

        if ($total > 0) {
            $formstable = '<table class="table table-hover table-striped">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
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
                $formstable .= '<td class="mailbox-name"><a href="#" style="text-decoration:none;">' . $mail->names . ' &lt' . $mail->emails . '&gt</td>';  //from_address
                $formstable .= '<td class="mailbox-subject"><b>' . $mail->subject . '</b></td>';
                $formstable .= '<td class="mailbox-attachment">' . $tdAttachment . '</td>';
                $formstable .= '<td class="mailbox-date">' . $this->get_time_ago(strtotime($mail->updated_at)) . '</td>';  //date('d/m/Y', strtotime($mail->date))
                $formstable .= '<td><a class="btn btn-warning" href="' . url('admin/mails/restore/' . $mail->mail_id) . '">Restore</a></td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }
        $data['total'] = $total;
        $data['table'] = $formstable;

        return view('admin.mails.trash')->with('data', $data);
    }

    public function restoreMail($id)
    {
        $mail = Tbl_admin_mails::find($id);
        $mail->active = 1;
        $mail->save();
        return redirect('admin/mails')->with('success', 'Restored successfully...');
    }

    public function deleteMail($id)
    {
        $mail = Tbl_admin_mails::find($id);
        $mail->active = 0;
        $mail->save();

        return redirect('admin/mails')->with('success', 'Mail deleted Successfully');
    }
}

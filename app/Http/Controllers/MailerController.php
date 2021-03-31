<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Auth;
use Mail;
//---------Models---------------
use App\Tbl_emails;
use App\Tbl_emailtemplates;
use App\Tbl_emailcategory;
use App\User;
use App\Admin;
use App\Tbl_contacts;
use App\Tbl_mails;
use App\Tbl_smtpsettings;
use App\Tbl_mailers;

class MailerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('test:mails', ['only' => ['index', 'show', 'create', 'store', 'edit', 'update', 'delete', 'deleteAll', 'deleteMail', 'mailSendAction']]);
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
            $formstable .= '<th>Email</th>';
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
                $formstable .= '<td class="mailbox-name"><a href="' . url('mailers/' . $mail->mail_id) . '" style="text-decoration:none;">' .  substr($mail->emails, 0, 25) . '</td>';  //from_address  substr($mail->names, 0, 25) . ' ' .
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

        return view('auth.mailers.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('auth.mailers.create');
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

        $maildetails = Tbl_mailers::with('tbl_smtp_settings')->find($id);
        // echo json_encode($maildetails);
        // exit();

        return view('auth.mailers.show')->with('data', $maildetails);
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


    public function getMails($uid)
    {
        //	mail_type
        return Tbl_mailers::where('uid', $uid)->where('mail_type', 1)->where('active', 1)->orderBy('mail_id', 'desc')->get();
    }


    public function mailSendAction(Request $request)
    {
        // echo json_encode($request->input());
        // exit();

        $userEmail = $request->input('userEmail');
        $to_email = $userEmail;
        // $to_email = 'user1@cricketu.com';
        //        $from_email = Auth::user()->email;
        $from_email = 'demouser@digitalcrm.com';
        // $userName = $request->input('userName');
        $message = $request->input('message');
        $subject = $request->input('subject');
        // $userType = $request->input('userType');
        // $userId = $request->input('userId');
        $uid = Auth::user()->id;

        $redirect = "mailers";

        $ctdata = array(
            'uid' => $uid,
            'email' => $userEmail
        );

        $contact = Tbl_contacts::where('uid', $uid)->where('email', $userEmail)->where('active', 1)->first();
        if ($contact != '') {
            $cnt_id = $contact->cnt_id;
        } else {
            $contacts = Tbl_contacts::create($ctdata);
            $cnt_id = $contacts->cnt_id;
        }



        $userName =  "";
        $userType =  2;
        $userId =  $cnt_id;

        // if ($userType == 1) {       //  Accounts
        //     $redirect = "accounts";
        // }
        // if ($userType == 2) {       //  Contacts
        //     $redirect = "contacts";
        // }
        // if ($userType == 3) {       //  Leads
        //     $redirect = "leads";
        // }
        // if ($userType == 4) {       //  web to lead
        //     $formlead = Tbl_formleads::find($userId);
        //     $redirect = "webtolead/formleads/" . $formlead->fl_id;
        // }
        // if ($userType == 5) {       //  Sub Users
        //     $redirect = "subusers";
        // }

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

        $mail = Tbl_mailers::create($formdata);

        if ($mail->mail_id > 0) {
            $title = 'DigitalCRM';

            Mail::send(['html' => 'emails.default'], ['title' => $title, 'content' => $message], function ($message) use ($from_email, $to_email, $subject, $attachments, $fileUrl) {
                $message->subject($subject);
                $message->from($from_email);   //'sandeepindana@yahoo.com'

                if ($attachments != '') {
                    $message->attach($fileUrl);
                }

                $message->to($to_email);   //'isandeep.1609@gmail.com'
            });

            if (count(Mail::failures()) > 0) {
                return redirect($redirect)->with('error', 'Failed. Please try again later..!');
            } else {
                $updateStatus = Tbl_mailers::find($mail->mail_id);
                $updateStatus->status = 1;
                $updateStatus->save();
                return redirect('mailers')->with('success', 'Mail Sent Successfully..!');
            }
        } else {
            return redirect($redirect)->with('error', 'Failed. Please try again later..!');
        }
    }


    public function deleteAll(Request $request)
    {
        $ids = $request->input('id');
        return Tbl_mailers::whereIn('mail_id', $ids)->update(array('active' => 0));
    }

    public function restoreAll(Request $request)
    {
        $ids = $request->input('id');
        return Tbl_mailers::whereIn('mail_id', $ids)->update(array('active' => 1));
    }

    public function restoreMail($id)
    {
        $mail = Tbl_mailers::find($id);

        //        echo json_encode($mail);

        $mail->active = 1;
        $mail->save();
        return redirect('/mailers')->with('success', 'Restored successfully...');
    }


    public function deleteMail($id)
    {
        $mail = Tbl_mailers::find($id);
        $mail->active = 0;
        $mail->save();

        return redirect('/mailers')->with('success', 'Mail deleted Successfully');
    }

    public function deletedMails()
    {
        $uid = Auth::user()->id;
        $mails = Tbl_mailers::where('uid', $uid)->where('mail_type', 1)->where('active', 0)->orderBy('mail_id', 'desc')->get();
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
                $formstable .= '<td class="mailbox-date">' .  date('d/m/Y', strtotime($mail->updated_at))  . '</td>';  //date('d/m/Y', strtotime($mail->date))
                $formstable .= '<td><a class="badge badge-pill badge-success" href="' . url('mailers/restore/' . $mail->mail_id) . '">Restore</a></td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }
        $data['total'] = $total;
        $data['table'] = $formstable;

        return view('auth.mailers.trash')->with('data', $data);
    }
}

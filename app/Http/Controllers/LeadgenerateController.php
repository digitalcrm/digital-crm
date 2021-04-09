<?php

namespace App\Http\Controllers;

use Mail;
use URL;
use Illuminate\Http\Request;
use App\Tbl_forms;
use App\Tbl_formleads;
use App\Tbl_formviews;
use App\Tbl_notifications;
use App\Tbl_emails;
use App\Tbl_emailcategory;
use App\Tbl_emailtemplates;
use App\Tbl_fb_leads;
//------------------------Controllers--------------------------------
use App\Http\Controllers\MailController;

class LeadgenerateController extends Controller
{

    public function formViews($id)
    {
        $formdata = array(
            'form_id' => $id,
        );
        $forms = Tbl_formviews::create($formdata);
        $fv_id = $forms->fv_id;
        if ($fv_id > 0) {
            Tbl_forms::find($id)->increment('views');
        }
        return TRUE;
    }

    public function submitContact(Request $request)
    {


        //        echo json_encode($request->input());
        //        exit(0);
        $uid = $request->input('uid');
        $form_id = $request->input('form_id');
        $post_url = $request->input('purl');
        $redirect_url = $request->input('rurl');
        $first_name = $request->input('firstName');
        $website = $request->input('website');
        $notes = $request->input('notes');
        $email = $request->input('emailid');
        $mobile = $request->input('mobile');
        $captcha_response = $request->input('captcha-response');    //g-recaptcha-response

        $formdetails = Tbl_forms::find($form_id);

        $fromMail = $formdetails->from_mail;
        $toMail = $email;
        $subject = $formdetails->subject;
        $message = $formdetails->message;

        $fl_id = 0;
        if (($formdetails->secret_key != '') && ($formdetails->site_key != '')) {
            if ($captcha_response != '') {
                $secret_key = $formdetails->secret_key;

                $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $captcha_response);
                $responseData = json_decode($verifyResponse);
                //            echo json_encode($responseData);

                if ($responseData->success) {
                    $fl_id = $this->createFormlead($first_name, $email, $mobile, $website, $notes, $form_id, $uid);
                } else {
                    return redirect($post_url);
                }
            } else {
                return redirect($post_url);
            }
        } else {
            $fl_id = $this->createFormlead($first_name, $email, $mobile, $website, $notes, $form_id, $uid);
        }

        if ($fl_id > 0) {

            $this->sendLeadMail($fromMail, $toMail, $subject, $message);
            $this->sendUserMail($uid, $first_name, $email, $mobile, $website, $notes, $post_url, $formdetails);

            return redirect($redirect_url);
        } else {
            return redirect($post_url);
        }
    }

    public function createFormlead($name, $email, $mobile, $website, $notes, $form_id, $uid)
    {
        $formdata = array(
            'first_name' => $name,
            'email' => $email,
            'mobile' => $mobile,
            'website' => $website,
            'notes' => $notes,
            'form_id' => $form_id,
            'uid' => $uid,
        );


        $formlead = Tbl_formleads::create($formdata);

        $fl_id = $formlead->fl_id;

        Tbl_forms::find($form_id)->increment('submissions');


        if ($fl_id > 0) {
            $message = $name . ' web to lead is added';
            $status = 0;
            $type = 6;
            $not_id = $this->createNotification($uid, $message, $status, $type, $fl_id);
        }

        return $fl_id;
    }

    public function createNotification($uid, $message, $status, $type, $id)
    {
        $not_arr = array(
            'message' => $message,
            'uid' => $uid,
            'status' => $status,
            'type' => $type,
            'id' => $id
        );
        $nots = Tbl_notifications::create($not_arr);
        $not_id = $nots->not_id;
        return $not_id;
    }

    public function sendLeadMail($fromMail, $toMail, $subject, $message)
    {

        //----------------------Lead Mail-----------------------------
        // From Mail - Administrator
        // To Mail - User
        //        $title = 'config('app.name');
        //        $content = 'Thank you for contacting us';
        //'mail'
        //        Mail::send(['html' => 'emails.default'], ['title' => $title, 'content' => $message], function ($message) use($fromMail, $toMail, $subject) {
        //            $message->subject($subject);
        //            $message->from($fromMail, 'Administrator');   //'sandeepindana@yahoo.com'
        //            $message->to($toMail);   //'isandeep.1609@gmail.com'
        //        });

        $title = config('app.name');

        $mail = new MailController();
        return $mail->sendMail($fromMail, $toMail, $message, $subject, $title);
    }

    public function sendUserMail($uid, $first_name_ar, $email_ar, $mobile_ar, $website_ar, $notes_ar, $post_url_ar, $formdetails)
    {
        $title = config('app.name');

        $department = Tbl_emailcategory::where('category', 'Lead Added')->first();
        $template = Tbl_emailtemplates::where('ecat_id', $department->ecat_id)->first();
        $emails = Tbl_emails::where('ecat_id', $department->ecat_id)->first();

        //        $userdetails = User::find($uid);

        $from_mail = $emails->mail;
        $to_email = $formdetails->from_mail;

        //-----------------------------Template variables------------------------

        $firstName = $first_name_ar;
        $emailid = $email_ar;
        $mobile = $mobile_ar;
        $website = $website_ar;
        $notes = $notes_ar;
        $post_url = $post_url_ar;

        $name = $first_name_ar;

        //        $subject = $template->subject;
        //-----------Subject-----------------------
        $beforeStr = $template->subject;
        preg_match_all('/{(\w+)}/', $beforeStr, $matches);
        $afterStr = $beforeStr;
        foreach ($matches[0] as $index => $var_name) {
            if (isset(${$matches[1][$index]})) {
                $afterStr = str_replace($var_name, ${$matches[1][$index]}, $afterStr);
            }
        }
        $subject = $afterStr;

        //-----------Subject-----------------------
        $beforeStr = $template->message;
        preg_match_all('/{(\w+)}/', $beforeStr, $matches);
        $afterStr = $beforeStr;
        foreach ($matches[0] as $index => $var_name) {
            if (isset(${$matches[1][$index]})) {
                $afterStr = str_replace($var_name, ${$matches[1][$index]}, $afterStr);
            }
        }
        $content = $afterStr;


        $mail = new MailController();
        return $mail->sendMail($from_mail, $to_email, $content, $subject, $title);
    }

    public function storeFbLead(Request $request)
    {
        $fblead = $request->input();
        $email = (isset($fblead->email)) ? $fblead->email : '';
        $full_name = (isset($fblead->full_name)) ? $fblead->full_name : '';
        $phone_number = (isset($fblead->phone_number)) ? $fblead->phone_number : '';
        $id = (isset($fblead->id)) ? $fblead->id : '';
        $created_time = (isset($fblead->created_time)) ? $fblead->created_time : '';
        $ad_id = (isset($fblead->ad_id)) ? $fblead->ad_id : '';
        $ad_name = (isset($fblead->ad_name)) ? $fblead->ad_name : '';
        $adset_id = (isset($fblead->adset_id)) ? $fblead->adset_id : '';
        $adset_name = (isset($fblead->adset_name)) ? $fblead->adset_name : '';
        $campaign_id = (isset($fblead->campaign_id)) ? $fblead->campaign_id : '';
        $campaign_name = (isset($fblead->campaign_name)) ? $fblead->campaign_name : '';
        $form_id = (isset($fblead->form_id)) ? $fblead->form_id : '';
        $form_name = (isset($fblead->form_name)) ? $fblead->form_name : '';
        $is_organic = (isset($fblead->is_organic)) ? $fblead->is_organic : '';
        $platform = (isset($fblead->platform)) ? $fblead->platform : '';
        $city = (isset($fblead->city)) ? $fblead->city : '';
        $uid = 0;
        $uploaded_by = 3;
        $assigned = 0;
        $file_id = 0;

        $formdata = array(
            'email' => $email,
            'full_name' => $full_name,
            'phone_number' => $phone_number,
            'id' => $id,
            'created_time' => $created_time,
            'ad_id' => $ad_id,
            'ad_name' => $ad_name,
            'adset_id' => $adset_id,
            'adset_name' => $adset_name,
            'campaign_id' => $campaign_id,
            'campaign_name' => $campaign_name,
            'form_id' => $form_id,
            'form_name' => $form_name,
            'is_organic' => $is_organic,
            'platform' => $platform,
            'city' => $city,
            'uid' => $uid,
            'uploaded_by' => $uploaded_by,
            'assigned' => $assigned,
            'file_id' => $file_id,
        );

        $res = Tbl_fb_leads::create($formdata);
        if ($res->fblead_id > 0) {
            return 'success';
        } else {
            return 'error';
        }
    }

    public function getBrowser($user_agent)
    {

        $browser = "Unknown Browser";

        $browser_array = array(
            '/msie/i' => 'Internet Explorer',
            '/firefox/i' => 'Firefox',
            '/safari/i' => 'Safari',
            '/chrome/i' => 'Chrome',
            '/edge/i' => 'Edge',
            '/opera/i' => 'Opera',
            '/netscape/i' => 'Netscape',
            '/maxthon/i' => 'Maxthon',
            '/konqueror/i' => 'Konqueror',
            '/mobile/i' => 'Handheld Browser'
        );

        foreach ($browser_array as $regex => $value) {

            if (preg_match($regex, $user_agent)) {
                $browser = $value;
            }
        }

        return $browser;
    }

    public function getOS($user_agent)
    {

        $os_platform = "Unknown OS Platform";

        $os_array = array(
            '/windows nt 10/i' => 'Windows 10',
            '/windows nt 6.3/i' => 'Windows 8.1',
            '/windows nt 6.2/i' => 'Windows 8',
            '/windows nt 6.1/i' => 'Windows 7',
            '/windows nt 6.0/i' => 'Windows Vista',
            '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
            '/windows nt 5.1/i' => 'Windows XP',
            '/windows xp/i' => 'Windows XP',
            '/windows nt 5.0/i' => 'Windows 2000',
            '/windows me/i' => 'Windows ME',
            '/win98/i' => 'Windows 98',
            '/win95/i' => 'Windows 95',
            '/win16/i' => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i' => 'Mac OS 9',
            '/linux/i' => 'Linux',
            '/ubuntu/i' => 'Ubuntu',
            '/iphone/i' => 'iPhone',
            '/ipod/i' => 'iPod',
            '/ipad/i' => 'iPad',
            '/android/i' => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i' => 'Mobile'
        );

        foreach ($os_array as $regex => $value) {

            if (preg_match($regex, $user_agent)) {
                $os_platform = $value;
            }
        }

        return $os_platform;
    }

    public function getEmbedCode(Request $request)    //$form_id, $key
    {
        $key = $request->input('key');
        $form_id = $request->input('form_id');

        $form = Tbl_forms::find($form_id); //$form_id
        echo json_encode($form);
        exit();

        $previewform = '';
        if (($key == $form->form_key) && ($form_id == $form->form_id)) {



            $captcha_script = '';
            $captcha_div = '';
            if (($form->site_key != '') && ($form->secret_key != '')) {
                //                <div class="g-recaptcha" data-sitekey="6LdixH0UAAAAAJGo_rlykZn_tUtXU-6cMdFyqU_7"></div>
                //                <button class="g-recaptcha" data-sitekey="6LeE0n0UAAAAAL8EEDPDguDMws-RVzlb086O6Zhk" data-callback="YourOnSubmitFn">Submit</button>


                $captcha_script = "<script src='https://www.google.com/recaptcha/api.js?onload=onloadCallback' async defer ></script><br>";
                $captcha_script .= "<script><br>";
                $captcha_script .= "var onloadCallback = function() {<br>";
                $captcha_script .= "    grecaptcha.execute();<br>";
                $captcha_script .= "};<br>";

                $captcha_script .= "function setResponse(response) { <br>";
                $captcha_script .= "    document.getElementById('captcha-response').value = response; <br>";
                $captcha_script .= "}<br>";
                $captcha_script .= "</script><br>";
                $captcha_div = '<div class="g-recaptcha" data-sitekey="' . $form->site_key . '" data-badge="inline" data-size="invisible" data-callback="setResponse" ></div>';
            }
            $previewform = $captcha_script;
            $previewform .= '<form name="formLead" action="' . url('leadgenerate/submitcontact') . '" method="post" enctype="multipart/form-data"><br>';
            $previewform .= '<input type="hidden" name="_token" id="csrf-token" value="' . csrf_token() . '"/><br>';
            $previewform .= '<input type="hidden" name="uid" id="uid" value="' . $form->uid . '" /><br>';
            $previewform .= '<input type="hidden" name="form_id" id="form_id" value="' . $form->form_id . '" /><br>';
            $previewform .= '<input type="hidden" name="purl" id="purl" value="' . $form->post_url . '" /><br>';
            $previewform .= '<input type="hidden" name="rurl" id="rurl" value="' . $form->redirect_url . '" /><br>';
            $previewform .= '<img src="' . url('leadgenerate/formviews/' . $form->form_id) . '" width="1" height="1" border="0" style="display:none;"/><br>';
            $previewform .= '<label>Contact Us</label><br>';
            $previewform .= '<label>Fields marked with an <span style="color:#ff0000;">*</span> are required</label><br><br>';
            $previewform .= '<label>Full Name <span style="color:#ff0000;">*</span> </label><br><br>';
            $previewform .= '<input class="form-control" type="text" name="firstName"  id="firstName" required pattern="[a-zA-Z\s]+"/><br><br>';
            $previewform .= '<label>Email Id <span style="color:#ff0000;">*</span></label><br><br>';
            $previewform .= '<input class="form-control" type="text" name="emailid"  id="emailid" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$"/><br><br>';
            $previewform .= '<label>Phone </label><br><br>';
            $previewform .= '<input class="form-control" type="text" name="mobile" id="mobile" maxlength="15" pattern="\d*"/><br><br>';
            $previewform .= '<label>Website </label><br><br>';
            $previewform .= '<input class="form-control" type="text" name="website" id="website" /><br><br>';
            $previewform .= '<label>Message </label><br><br>';
            $previewform .= '<textarea name="notes" id="notes" pattern="[A-Za-z0-9]{1,500}"></textarea><br><br>';
            $previewform .= '<input type="hidden" id="captcha-response" name="captcha-response" /><br><br>';
            $previewform .= $captcha_div . '<br>';
            $previewform .= '<input type="submit" name="submitLead"/><br><br>';
            $previewform .= '</form>';
        }
        //
        //
        //        return json_encode($request->input());


        return $previewform;
    }

    public function receiveEmail(){

        $fd = fopen("php://stdin", "r");
        $rawEmail = "";
        while (!feof($fd)) {
            $rawEmail .= fread($fd, 1024);
        }
        fclose($fd);

        $parser = new Parser();
        $parser->setText($rawEmail);

        $to = $parser->getHeader('to');
        $from = $parser->getHeader('from');
        $subject = $parser->getHeader('subject');
        $text = $parser->getMessageBody('text');

        $msg = array(
            'to_address'=>$to,
            'from_address'=>$from,
            'subject'=>$subject,
            'message' =>$text
            );


        $query = DB::table('tbl_mails_sample')->insert($msg);

        if($query->mail_id){
            return TRUE;
        }else{
            return FALSE;
        }

    }
}

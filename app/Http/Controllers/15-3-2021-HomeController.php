<?php

namespace App\Http\Controllers;

use Auth;
use Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
//---------Models---------------
use App\User;
use App\currency;
use App\Tbl_forms;
use App\Tbl_formleads;
use App\Tbl_formviews;
use App\Tbl_leads;
use App\Tbl_deals;
use App\Tbl_Accounts;
use App\Tbl_emailtemplates;
use App\Tbl_emailcategory;
use App\Tbl_departments;
use App\Tbl_emails;
use App\tbl_verifyuser;
use App\Tbl_smtpsettings;
use App\Tbl_countries;
use App\Tbl_states;
//---------Controllers-------------------
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubuserController;

//---------------------Events---------------------
//use Event;
//use App\Events\SendMail;
//use App\Events\InboundMail;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $id = Auth::user()->id;
        $userdata = User::with('tbl_countries')->with('tbl_states')->find($id)->toArray(); //
        // echo json_encode($userdata);
        // exit(0);
        $user_currency = '';
        $crlist = currency::all();
        $croptions = "<option value=''>Select Currency</option>";
        foreach ($crlist as $currency) {

            if ($currency->cr_id == $userdata['cr_id']) {
                $selected = 'selected';
                $user_currency = '<span>' . $currency->html_code . '</span>&nbsp;' . $currency->name;
            } else {
                $selected = '';
            }

            //            $selected = ($currency->cr_id == $userdata['cr_id']) ? 'selected' : '';
            $croptions .= "<option value='" . $currency->cr_id . "' " . $selected . ">" . $currency->name . " - " . $currency->code . "</option>";
        }

        $data['userdata'] = $userdata;
        $data['croptions'] = $croptions;
        $data['user_currency'] = $user_currency;

        $dash = new DashboardController();
        $data['global_reports'] = $dash->globalReports($id);

        $subusers = new SubuserController();
        $data['subusers'] = $subusers->getSubusers($id);

        $smtpsettings = Tbl_smtpsettings::where('uid', $id)->first();
        //        echo json_encode($smtpsettings);
        //        exit();
        $data['smtpsettings'] = $smtpsettings;



        return view('auth.user.profile')->with('data', $data);
    }

    public function userUpdate(Request $request, $id)
    {

        //        echo json_encode($request->input());
        //        exit();

        $userdata = User::find($id);

        $this->validate($request, [
            'username' => 'required|max:255',
        ]);


        $filename = '';
        if ($request->hasfile('profilepicture')) {
            $file = $request->file('profilepicture');
            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $file->move('uploads/profile/', $name);    //public_path() . 
            $filename = '/uploads/profile/' . $name;
            $userdata->picture = $filename;
        }

        $userdata->name = $request->input('username');
        $userdata->mobile = $request->input('mobile');
        $userdata->jobtitle = $request->input('jobtitle');
        $userdata->cr_id = $request->input('currency');
        $userdata->daily_reports = $request->input('dailyreports');
        $userdata->newsletter = $request->input('newsletter');
        $userdata->country = $request->input('country');
        $userdata->state = $request->input('state');
        $userdata->city = $request->input('city');
        $userdata->zip = $request->input('zip');
        $userdata->save();
        return redirect('/user/profile')->with('success', 'Updated Successfully...!');
    }

    public function dashboard()
    {

        $dash = new DashboardController();
        //        Cache::flush();
        $uid = Auth::user()->id;
        $forms = Tbl_forms::where('uid', $uid)->where('active', 1)->get();
        $form_Select = "<option value='0'>Select Form</option>";
        $form_id = 0;
        $k = 0;
        if (count($forms) > 0) {
            foreach ($forms as $formdetails) {
                if ($k == 0) {
                    $form_Select .= "<option value='" . $formdetails->form_id . "' selected>" . $formdetails->title . ' - ' . $formdetails->post_url . "</option>";
                    $form_id = $formdetails->form_id;
                    $k++;
                }
                $form_Select .= "<option value='" . $formdetails->form_id . "'>" . $formdetails->title . ' - ' . $formdetails->post_url . "</option>";
            }
        }
        $data['form_id'] = $form_id;
        $data['form_Select'] = $form_Select;
        //----------------------------------------------------------------
        $uid = Auth::user()->id;
        $data['forms'] = count($forms);
        $data['webtolead'] = $this->webtolead($uid);
        $data['leads'] = $this->leads($uid);
        $data['accounts'] = $this->accounts($uid);
        $data['deals'] = $this->deals($uid);

        $data['global_reports'] = $dash->globalReports($uid);

        $salesstage = $dash->salesstageOptions();
        $data['default_salesfunnel'] = $salesstage['default_salesfunnel'];
        $data['salesfunnelOptions'] = $salesstage['salesfunnelOptions'];

        $latestDeals = $dash->getLatestDeals();
        $data['latestDeals'] = $latestDeals;

        $subusers = new SubuserController();
        $data['subusers'] = $subusers->getSubusers($uid);
        // ----------------------------------------------------------------
        //---------------------Events---------------------
        //        Event::fire(new SendMail($uid));
        //        Event::fire(new InboundMail($uid));
        //        exit();
        return view('auth.dashboard')->with('data', $data);
    }

    public function webtolead($id)
    {
        $formleads = Tbl_formleads::where('uid', $id)->get();
        return count($formleads);
    }

    public function leads($id)
    {
        $leads = Tbl_leads::where('uid', $id)->get();
        return count($leads);
    }

    public function accounts($id)
    {
        $accounts = Tbl_Accounts::where('uid', $id)->get();
        return count($accounts);
    }

    public function deals($id)
    {
        $deals = Tbl_deals::where('uid', $id)->get();
        return count($deals);
    }

    public function testMail()
    {
        $department = Tbl_emailcategory::where('category', 'Registration')->first();
        $template = Tbl_emailtemplates::where('ecat_id', $department->ecat_id)->first();
        $emails = Tbl_emails::where('ecat_id', $department->ecat_id)->first();
        //        echo json_encode($template->message);
        //        echo $template->message;
        //'mail'
        $subject = $template->subject;
        $user = 'Sandeep';
        $click = '<a href="https://digitalcrm.com">click</a>';
        $title = 'Test Mail';
        $message = $template->message;
        $from_mail = $emails->mail; //'aynsoft@digitalcrm.com'
        $email = 'testone@digitalcrm.com';

        $beforeStr = $template->message;
        preg_match_all('/{(\w+)}/', $beforeStr, $matches);
        $afterStr = $beforeStr;
        foreach ($matches[0] as $index => $var_name) {
            if (isset(${$matches[1][$index]})) {
                $afterStr = str_replace($var_name, ${$matches[1][$index]}, $afterStr);
            }
        }
        $content = $afterStr;
        //        echo '<br>';
        //        echo $content;
        //        exit(0);

        Mail::send(['html' => 'emails.default'], ['title' => $title, 'content' => $content], function ($message) use ($from_mail, $email) {
            $message->subject('Thank you');
            $message->from($from_mail, 'Administrator');   //'sandeepindana@yahoo.com'
            $message->attach(url('/uploads/default/accounts.png'));
            $message->to($email);   //'isandeep.1609@gmail.com'
        });

        if (count(Mail::failures()) > 0) {
            echo 'Failed to send email, please try again.';
        } else {
            echo "Success";
        }
    }

    public function bootstrapfour()
    {
        return view('auth.bootstrap4');
    }

    public function inbox()
    {
        /* connect to gmail */
        $hostname = '{mail.digitalcrm.com:993/imap/ssl}INBOX';
        $username = 'testone@digitalcrm.com';
        $password = 'Testone@#*';

        /* try to connect */
        $inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Mail: ' . imap_last_error());

        /* grab emails */
        $emails = imap_search($inbox, 'ALL');

        /* if emails are returned, cycle through each... */
        if ($emails) {

            /* begin output var */
            $output = '';

            /* put the newest emails on top */
            rsort($emails);

            /* for every email... */
            foreach ($emails as $email_number) {

                /* get information specific to this email */
                $overview = imap_fetch_overview($inbox, $email_number, 0);
                $message = imap_fetchbody($inbox, $email_number, 2);

                /* output the email header information */
                $output .= '<div class="toggler ' . ($overview[0]->seen ? 'read' : 'unread') . '">';
                $output .= '<span class="subject">' . $overview[0]->subject . '</span> ';
                $output .= '<span class="from">' . $overview[0]->from . '</span>';
                $output .= '<span class="date">on ' . $overview[0]->date . '</span>';
                $output .= '</div>';

                /* output the email body */
                $output .= '<div class="body">' . $message . '</div>';
            }

            echo $output;
        }

        /* close the connection */
        imap_close($inbox);

        return true;
    }

    public function update()
    {
        $id = Auth::user()->id;
        $userdata = User::with('tbl_countries')->with('tbl_states')->find($id)->toArray();

        //  Generating Currency Options

        $user_currency = '';
        $crlist = currency::all();
        $croptions = "<option value=''>Select Currency</option>";
        foreach ($crlist as $currency) {

            if ($currency->cr_id == $userdata['cr_id']) {
                $selected = 'selected';
                $user_currency = '<span>' . $currency->html_code . '</span>&nbsp;' . $currency->name;
            } else {
                $selected = '';
            }
            $croptions .= "<option value='" . $currency->cr_id . "' " . $selected . ">" . $currency->name . " - " . $currency->code . "</option>";
        }

        //  Generating Country Options

        $countrylist = Tbl_countries::all();
        $countryoptions = "<option value='0'>Select Country</option>";
        $country_id = (isset($userdata['country'])) ? $userdata['country'] : 0;
        foreach ($countrylist as $country) {

            if ($country->id == $country_id) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $countryoptions .= "<option value='" . $country->id . "' " . $selected . ">" . $country->name . " - " . $country->sortname . "</option>";
        }

        //  Generating State Options

        $stateoptions = "<option value='0'>Select State</option>";
        $state_id = (isset($userdata['state'])) ? $userdata['state'] : 0;
        if ($country_id > 0) {
            $statelist = Tbl_states::where('country_id', $userdata['country'])->get();
            foreach ($statelist as $state) {
                if ($state->id == $state_id) {
                    $selected = 'selected';
                } else {
                    $selected = '';
                }
                $stateoptions .= "<option value='" . $state->id . "' " . $selected . ">" . $state->name . "</option>";
            }
        }

        $data['userdata'] = $userdata;
        $data['croptions'] = $croptions;
        $data['countryoptions'] = $countryoptions;
        $data['stateoptions'] = $stateoptions;

        return view('auth.user.update')->with('data', $data);
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

    public function geoCheckIP($ip)
    {
        //check, if the provided ip is valid
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            //            throw new InvalidArgumentException("IP is not valid");
            return;
        }
        //contact ip-server
        $response = @file_get_contents('http://www.netip.de/search?query=' . $ip);
        if (empty($response)) {
            //            throw new InvalidArgumentException("Error contacting Geo-IP-Server");
            return;
        }
        //Array containing all regex-patterns necessary to extract ip-geoinfo from page
        $patterns = array();
        $patterns["domain"] = '#Domain: (.*?)&nbsp;#i';
        $patterns["country"] = '#Country: (.*?)&nbsp;#i';
        $patterns["state"] = '#State/Region: (.*?)<br#i';
        $patterns["town"] = '#City: (.*?)<br#i';
        //Array where results will be stored
        $ipInfo = array();
        //check response from ipserver for above patterns
        foreach ($patterns as $key => $pattern) {
            //store the result in array
            $ipInfo[$key] = preg_match($pattern, $response, $value) && !empty($value[1]) ? $value[1] : 'not found';
        }
        return $ipInfo;
    }

    //    public function getSmtpSettings() {
    //        $uid = Auth::user()->id;
    //        $smtpsettings = Tbl_smtpsettings::where('uid', $uid)->first();
    //
    //        if ($smtpsettings) {
    //            return redirect('smtpsettings/create')->with('warning', 'Please enter smtp settings');
    //        } else {
    //            return view('user.smtpedit')->with('data', $smtpsettings);
    //        }
    //    }


    public function bootstrap()
    {

        $id = Auth::user()->id;
        $userdata = User::with('tbl_countries')->with('tbl_states')->find($id)->toArray(); //
        //        echo json_encode($userdata);
        //        exit(0);
        $user_currency = '';
        $crlist = currency::all();
        $croptions = "<option value=''>Select Currency</option>";
        foreach ($crlist as $currency) {

            if ($currency->cr_id == $userdata['cr_id']) {
                $selected = 'selected';
                $user_currency = '<span>' . $currency->html_code . '</span>&nbsp;' . $currency->name;
            } else {
                $selected = '';
            }

            //            $selected = ($currency->cr_id == $userdata['cr_id']) ? 'selected' : '';
            $croptions .= "<option value='" . $currency->cr_id . "' " . $selected . ">" . $currency->name . " - " . $currency->code . "</option>";
        }

        $data['userdata'] = $userdata;
        $data['croptions'] = $croptions;
        $data['user_currency'] = $user_currency;

        $dash = new DashboardController();
        $data['global_reports'] = $dash->globalReports($id);

        $subusers = new SubuserController();
        $data['subusers'] = $subusers->getSubusers($id);

        $smtpsettings = Tbl_smtpsettings::where('uid', $id)->first();
        //        echo json_encode($smtpsettings);
        //        exit();
        $data['smtpsettings'] = $smtpsettings;
        return view('auth.bootstrap4')->with('data', $data);
    }

    public function adminlte()
    {

        $dash = new DashboardController();
        //        Cache::flush();
        $uid = Auth::user()->id;
        $forms = Tbl_forms::where('uid', $uid)->where('active', 1)->get();
        $form_Select = "<option value='0'>Select Form</option>";
        $form_id = 0;
        $k = 0;
        if (count($forms) > 0) {
            foreach ($forms as $formdetails) {
                if ($k == 0) {
                    $form_Select .= "<option value='" . $formdetails->form_id . "' selected>" . $formdetails->title . ' - ' . $formdetails->post_url . "</option>";
                    $form_id = $formdetails->form_id;
                    $k++;
                }
                $form_Select .= "<option value='" . $formdetails->form_id . "'>" . $formdetails->title . ' - ' . $formdetails->post_url . "</option>";
            }
        }
        $data['form_id'] = $form_id;
        $data['form_Select'] = $form_Select;
        //----------------------------------------------------------------
        $uid = Auth::user()->id;
        $data['forms'] = count($forms);
        $data['webtolead'] = $this->webtolead($uid);
        $data['leads'] = $this->leads($uid);
        $data['accounts'] = $this->accounts($uid);
        $data['deals'] = $this->deals($uid);

        $data['global_reports'] = $dash->globalReports($uid);

        $salesstage = $dash->salesstageOptions();
        $data['default_salesfunnel'] = $salesstage['default_salesfunnel'];
        $data['salesfunnelOptions'] = $salesstage['salesfunnelOptions'];

        $latestDeals = $dash->getLatestDeals();
        $data['latestDeals'] = $latestDeals;

        $subusers = new SubuserController();
        $data['subusers'] = $subusers->getSubusers($uid);

        return view('auth.adminlte-boot4')->with('data', $data);
    }
}

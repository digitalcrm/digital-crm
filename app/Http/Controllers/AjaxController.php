<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Tbl_forms;
use App\Tbl_leads;
use App\Tbl_deals;
use App\Tbl_countries;
use App\Tbl_states;
use App\Tbl_industrytypes;
use App\Tbl_leadsource;
use App\Tbl_leadstatus;
use App\Tbl_Accounts;
use App\Tbl_contacts;
use App\User;
use App\Tbl_notifications;
use App\Tbl_events;
use App\currency;
//-----------------Controllers---------------------
use App\Http\Controllers\Admin;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ForecastController;
use App\Http\Controllers\CalendarController;

class AjaxController extends Controller
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

    public function previewForm(Request $request)
    {
        $form_id = $request->input('form_id');
        $type = $request->input('type');
        $form = Tbl_forms::find($form_id);
        // return json_encode($form);
        // return json_encode($form->form_id);




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
                //                <div class="g-recaptcha" data-sitekey="6LdixH0UAAAAAJGo_rlykZn_tUtXU-6cMdFyqU_7"></div> 
                //                <button class="g-recaptcha" data-sitekey="6LeE0n0UAAAAAL8EEDPDguDMws-RVzlb086O6Zhk" data-callback="YourOnSubmitFn">Submit</button>


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
            $previewform .= '&lt;input type="hidden" name="uid" id="uid" value="' . Auth::user()->id . '" /&gt;<br>';
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
            //
            //  --------------------- JQuery Plugin ----------------------------------
            // $previewform = '<pre><code>&lt;div id="formbuilder"&gt;&lt;/div&gt;<br>';
            // $previewform .= '&lt;script src="http://code.jquery.com/jquery-3.2.1.min.js"&gt;&lt;/script&gt;<br>';
            // $previewform .= '&lt;script src="' . asset('assets/webtolead/js/embedcode/plugin.js') . '"&gt;&lt;/script&gt;<br>';
            // $previewform .= '&lt;script&gt;<br>';
            // $previewform .= '$(document).ready(function() {<br>';
            // $previewform .= '   $(\'#formbuilder\').form({<br>';
            // $previewform .= '       form_id:"' . $form->form_id . '" ,<br>';
            // $previewform .= '       key: "' . $form->form_key . '",<br>';
            // $previewform .= '   });<br>';
            // $previewform .= '});<br>';
            // $previewform .= '&lt;/script&gt;</code></pre><br>';
        }
        return $previewform;
    }

    public function getStateoptions(Request $request)
    {
        $country = $request->input('country');
        $states = Tbl_states::where('country_id', $country)->get();
        $stateOption = "<option value='0'>Select State</option>";
        foreach ($states as $state) {
            $stateOption .= "<option value='" . $state->id . "'>" . $state->name . "</option>";
        }
        return $stateOption;
    }

    public function getUserleads(Request $request)
    {
        $uid = $request->input('uid');
        $lead = new LeadController();
        $data = $lead->getLeads($uid);

        return json_encode($data);
    }

    public function getUserwebtoleads(Request $request)
    {
        $uid = $request->input('uid');

        $forms = Tbl_forms::where('uid', $uid)->get();
        //        echo json_encode($forms);
        if (count($forms) > 0) {
            $formstable = '<table id="formleadsTable" class="table table-bordered table-striped">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
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
                $formstable .= '<tr>';
                $formstable .= '<td><a href="' . url('admin/webtolead/formleads/' . $formdetails->form_id) . '">' . $formdetails->title . '</a>&nbsp;</td>';
                $formstable .= '<td>' . $formdetails->post_url . '</td>';
                $formstable .= '<td>' . $formdetails->views . '</td>';
                $formstable .= '<td>' . $formdetails->submissions . '</td>';
                $formstable .= '<td>0</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '<td><a type="button" href="#" onclick="return previewForm(' . $formdetails->form_id . ')">Preview</a></td>';
                $formstable .= '<td><a type="button" href="#" onclick="return embedCode(' . $formdetails->form_id . ')">Embed Code</a></td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                      </button>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="' . url('admin/webtolead/' . $formdetails->form_id) . '">View</a></li>
                        <li><a href="' . url('webtolead/formdelete/' . $formdetails->form_id) . '">Delete</a></li>
                      </ul>
                    </div>';
                $formstable .= '</td>';
                $formstable .= '</tr>';

                //<li><a href="' . url('webtolead/' . $formdetails->form_id . '/edit') . '">Edit</a></li>
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }
        $data['total'] = count($forms);
        $data['table'] = $formstable;

        return json_encode($data);
    }

    public function getLatestnotifications(Request $request)
    {
        //        return Auth::user()->id;
        //        return 'Get Latest Notifications';
        $uid = Auth::user()->id;
        $nots = Tbl_notifications::where('status', 0)->where('uid', $uid)->orderBy('not_id', 'desc')->get();
        //        return count($nots);
        //            <a class="nav-link" data-toggle="dropdown" href="#">
        //               <i class="far fa-bell"></i>
        //               <span class="badge badge-danger navbar-badge">15</span>
        //           </a>
        //           <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        //               <span class="dropdown-item dropdown-header">15 Notifications</span>
        //               <div class="dropdown-divider"></div>
        //               <a href="#" class="dropdown-item">
        //                   <i class="fas fa-envelope mr-2"></i> 4 new messages
        //                   <span class="float-right text-muted text-sm">3 mins</span>
        //               </a>
        //               <div class="dropdown-divider"></div>
        //               <a href="#" class="dropdown-item">
        //                   <i class="fas fa-users mr-2"></i> 8 friend requests
        //                   <span class="float-right text-muted text-sm">12 hours</span>
        //               </a>
        //               <div class="dropdown-divider"></div>
        //               <a href="#" class="dropdown-item">
        //                   <i class="fas fa-file mr-2"></i> 3 new reports
        //                   <span class="float-right text-muted text-sm">2 days</span>
        //               </a>
        //               <div class="dropdown-divider"></div>
        //               <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        //           </div>

        $not_count = count($nots);


        $limsg = "You have no notifications";

        if ($not_count > 1) {
            $limsg = "You have " . $not_count . " notifications";
        }
        if ($not_count == 1) {
            $limsg = "You have " . $not_count . " notification";
        }


        //        $formstable = "<ul class='menu'>";
        $formstable = "<span class='dropdown-item dropdown-header'>" . $limsg . "</span>
    <div class='dropdown-divider'></div>";

        if ($not_count > 0) {
            foreach ($nots as $not) {
                $a = "";
                if ($not->type == 6) {
                    $a = url('webtolead/viewformlead/' . $not->id);
                }

                if ($not->type == 5) {
                    $a = url('calendar/' . $not->id);
                }

                if ($not->type == 7) {
                    $a = url('ecommerce/' . $not->id);
                }

                $click = '';
                if ($not->status == 0) {
                    //                    $not_count += 1;
                    $click = 'onclick="return markAsRead(' . $not->not_id . ')"';
                }

                //                $formstable .= "<li>";
                //                $formstable .= "<a href='" . $a . "' " . $click . ">";
                //                $formstable .= "<i class='fa fa-user text-red'></i> " . $not->message;
                //                $formstable .= "</a>";
                //                $formstable .= "</li>";

                $formstable .= '<a href="' . $a . '" ' . $click . ' class="dropdown-item">';
                $formstable .= '<small><i class="far fa-circle nav-icon"></i>&nbsp;' . $not->message . '</small>';
                $formstable .= '</a>';
                $formstable .= '<div class="dropdown-divider"></div>';
            }
        }
        //        $formstable .= "</ul>";
        $formstable .= '<a href="' . url('notifications') . '" class="dropdown-item dropdown-footer">See All Notifications</a>';


        //        $data['limsg'] = $limsg;
        $data['formstable'] = $formstable;
        $data['not_count'] = $not_count;
        return json_encode($data);
    }

    public function markAllasread(Request $request)
    {
        $uid = Auth::user()->id;
        $nots = DB::update('UPDATE `tbl_notifications` SET `status`=1 WHERE `uid`=' . $uid);
        return $nots;
    }

    public function markAsRead(Request $request)
    {
        $uid = Auth::user()->id;
        $id = $request->input('id');
        //        return json_encode($request->input('id'));

        $nots = DB::update('UPDATE `tbl_notifications` SET `status`=1 WHERE `not_id`=' . $id);
        return $nots;
    }

    public function getUserAccountselect(Request $request)
    {
        $uid = Auth::user()->id;
        $accounts = Tbl_Accounts::where('uid', $uid)->where('active', 1)->get();
        $accountOption = "<option value='0'>Select Account</option>";
        if (count($accounts) > 0) {
            foreach ($accounts as $acnt) {
                $accountOption .= "<option value='" . $acnt->acc_id . "'>" . $acnt->name . "</option>";
            }
        }
        return $accountOption;
    }

    public function getUserContactselect(Request $request)
    {
        $uid = Auth::user()->id;
        $contacts = Tbl_contacts::where('uid', $uid)->where('active', 1)->get();
        $contactOption = "<option value='0'>Select Contact</option>";
        if (count($contacts) > 0) {
            foreach ($contacts as $cnt) {
                $contactOption .= "<option value='" . $cnt->cnt_id . "'>" . $cnt->first_name . ' ' . $cnt->last_name . ' ' . "</option>";
            }
        }

        return $contactOption;
    }

    public function getUserLeadselect(Request $request)
    {
        $uid = Auth::user()->id;
        $leads = Tbl_leads::where('uid', $uid)->where('active', 1)->get();
        $leadOption = "<option value='0'>Select Lead</option>";
        if (count($leads) > 0) {
            foreach ($leads as $ld) {
                $leadOption .= "<option value='" . $ld->ld_id . "'>" . $ld->first_name . ' ' . $ld->last_name . ' ' . "</option>";
            }
        }
        return $leadOption;
    }

    public function getUserEvents(Request $request)
    {
        $uid = Auth::user()->id;
        $calObj = new CalendarController();
        $data = $calObj->getUserEvents($uid);

        //        $events = Tbl_events::where('uid', $uid)->where('active', 1)->get(['ev_id', 'title', 'startDatetime as start', 'endDatetime as end']);
        //        $data = [];
        //        foreach ($events as $event) {
        //            $eveArr['ev_id'] = $event->ev_id;
        //            $eveArr['title'] = $event->title;
        //            $eveArr['start'] = $event->start;
        //            $eveArr['end'] = $event->end;
        //            $eveArr['url'] = url('calendar/' . $event->ev_id);
        //            $data[] = $eveArr;
        //        }
        //
        //
        //        $deals = Tbl_deals::where('uid', $uid)->where('active', 1)->get(['deal_id', 'name as title', 'closing_date as start', 'closing_date as end']);
        //        foreach ($deals as $deal) {
        //            $eveArr['ev_id'] = $deal->ev_id;
        //            $eveArr['title'] = $deal->title;
        //            $eveArr['start'] = $deal->start;
        //            $eveArr['end'] = $deal->end;
        //            $eveArr['url'] = url('deals/' . $deal->deal_id);
        //            $data[] = $eveArr;
        //        }
        //
        return json_encode($data);
    }

    public function getUserForecast(Request $request)
    {
        //        return $request->input('date');
        $date = $request->input('date');
        $uid = Auth::user()->id;

        $fc = new ForecastController();
        $data = $fc->getForecastlist($uid, $date);

        return json_encode($data);
    }
}

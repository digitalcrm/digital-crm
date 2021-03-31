<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
//---------Models---------------
use App\Tbl_countries;
use App\Tbl_states;
use App\currency;
use App\User;
use App\Tbl_Accounts;
use App\Tbl_contacts;
use App\Tbl_leads;
use App\Tbl_salesfunnel;
use App\Tbl_admin_notifications;

//---------Controllers---------------
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\WebtoleadController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\DealController;
use App\Http\Controllers\Admin\CustomersController;
use App\Http\Controllers\Admin\SalesController;
use App\Http\Controllers\Admin\TerritoryController;
use App\Http\Controllers\Admin\ForecastController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\InvoiceController;

class AjaxController extends Controller
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

    public function getUserAccounts(Request $request)
    {
        $uid = $request->input('uid');
        $account = new AccountController();
        $accounts = $account->getAccounts($uid);
        return json_encode($accounts);
    }

    public function getUserContacts(Request $request)
    {
        $uid = $request->input('uid');
        $contact = new ContactController();
        $contacts = $contact->getContacts($uid);
        return json_encode($contacts);
    }

    public function getUserForms(Request $request)
    {
        $uid = $request->input('uid');
        $webtolead = new WebtoleadController();
        $webtoleads = $webtolead->getForms($uid);
        return json_encode($webtoleads);
    }

    public function getUserLeads(Request $request)
    {
        $uid = $request->input('uid');
        $lead = new LeadController();
        $leads = $lead->getLeads($uid);
        return json_encode($leads);
    }

    public function getUserDeals(Request $request)
    {
        $uid = $request->input('uid');
        $stage = $request->input('stage');
        $start = ($request->input('start') != '') ? date('Y-m-d', strtotime($request->input('start'))) : '';
        $end = ($request->input('end') != '') ? date('Y-m-d', strtotime($request->input('end'))) : '';

        $deal = new DealController();
        $deals = $deal->getDeals($uid, $stage, $start, $end);

        $timer = $deal->getFilterTime($start, $end);
        $deals['timer'] = $timer;

        $dealstageVal = 'All';
        if ($stage > 0) {
            $dstages = Tbl_salesfunnel::find($stage);
            $dealstageVal = $dstages->salesfunnel;
        }
        $deals['dealstageVal'] = $dealstageVal;

        $userVal = "All";
        if ($uid > 0) {
            $usersVal = User::find($uid);
            $userVal = $usersVal->name;
        }
        $deals['userVal'] = $userVal;

        return json_encode($deals);
    }

    public function getUserCustomers(Request $request)
    {
        $uid = $request->input('uid');
        $customer = new CustomersController();
        $customers = $customer->getCustomers($uid);
        return json_encode($customers);
    }

    public function getUserSales(Request $request)
    {
        $uid = $request->input('uid');
        $sale = new SalesController();
        $sales = $sale->getSales($uid);
        return json_encode($sales);
    }

    public function getUserTerritory(Request $request)
    {
        $uid = $request->input('uid');
        $territory = new TerritoryController();
        $territories = $territory->getTerritories($uid);
        return json_encode($territories);
    }

    public function getStateoptions(Request $request)
    {
        $country = $request->input('country');
        $states = Tbl_states::where('country_id', $country)->get();
        // return json_encode($states);

        $stateOption = "<option value='0'>Select State</option>";
        foreach ($states as $state) {
            $stateOption .= "<option value='" . $state->id . "'>" . $state->name . "</option>";
        }
        return $stateOption;
    }

    public function getSubuseroptions(Request $request)
    {
        $uid = $request->input('uid');
        $subusers = new UserController();
        return $subusers->getSubuseroptions($uid);
    }

    public function getUserCurrency(Request $request)
    {
        $uid = $request->input('uid');
        $user = User::find($uid);
        $currency = currency::find($user->cr_id);
        return $currency->html_code;
    }

    public function getUserProducts(Request $request)   //
    {
        $uid = $request->input('uid');
        // echo json_encode($uid);
        // exit();

        if ($uid != 'All') {
            $userdet = explode("-", $uid);
            $userid = $userdet[0];
            $usertype = $userdet[1];
        } else {
            $userid = $uid;
            $usertype = 0;
        }
        // echo $userid . ' ' . $usertype;
        $product = new ProductController();
        $data = $product->getProducts($userid, $usertype);
        return json_encode($data);
    }

    public function getUserEvents(Request $request)
    {
        $uid = $request->input('uid');
        $product = new CalendarController();
        return json_encode($product->getUserEvents($uid));
    }

    public function getUserAccountselect(Request $request)
    {
        $uid = $request->input('uid');
        $accounts = Tbl_Accounts::where('uid', $uid)->get();
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
        $uid = $request->input('uid');
        $contacts = Tbl_contacts::where('uid', $uid)->get();
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
        $uid = $request->input('uid');
        $leads = Tbl_leads::where('uid', $uid)->get();
        $leadOption = "<option value='0'>Select Lead</option>";
        if (count($leads) > 0) {
            foreach ($leads as $ld) {
                $leadOption .= "<option value='" . $ld->ld_id . "'>" . $ld->first_name . ' ' . $ld->last_name . ' ' . "</option>";
            }
        }
        return $leadOption;
    }

    public function getUserDocuments(Request $request)
    {
        $uid = $request->input('uid');
        $product = new DocumentController;
        return json_encode($product->getDocuments($uid));
    }

    public function getUserForecast(Request $request)
    {
        //        return $request->input('date');
        $date = $request->input('date');
        $uid = $request->input('uid');
        //        
        $fc = new ForecastController();
        $data = $fc->getForecastlist($uid, $date);
        //
        return $data;

        //        return "Date : " . $date . " uid : " . $uid;
    }

    public function getUserInvoices(Request $request)
    {
        $uid = $request->input('uid');
        $invoice = new InvoiceController;
        return json_encode($invoice->getInvoices($uid));
    }

    public function getLatestnotifications(Request $request)
    {
        //        return Auth::user()->id;
        //        return 'Get Latest Notifications';
        $uid = Auth::user()->id;
        $nots = Tbl_admin_notifications::where('status', 0)->where('uid', $uid)->orderBy('not_id', 'desc')->get();
        return count($nots);


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
                    $a = url('admin/webtolead/viewformlead/' . $not->id);
                }

                if ($not->type == 5) {
                    $a = url('admin/calendar/' . $not->id);
                }

                if ($not->type == 7) {
                    $a = url('admin/ecommerce/' . $not->id);
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
        $formstable .= '<a href="' . url('admin/notifications') . '" class="dropdown-item dropdown-footer">See All Notifications</a>';


        //        $data['limsg'] = $limsg;
        $data['formstable'] = $formstable;
        $data['not_count'] = $not_count;
        return json_encode($data);
    }
}

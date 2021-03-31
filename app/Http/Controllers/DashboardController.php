<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//---------Models---------------
use App\Tbl_salesfunnel;
use App\User;
use App\Tbl_Accounts;
use App\Tbl_contacts;
use App\Tbl_leads;
use App\Tbl_deals;
use App\Tbl_forms;
use App\Tbl_formleads;
use App\Tbl_events;
use App\Tbl_territory;
use App\currency;
//---------Controllers---------------
use App\Http\Controllers\LeadController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\SubuserController;

class DashboardController extends Controller
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


        //        echo "Dashboard";
        $uid = Auth::user()->id;

        $forms = Tbl_forms::where('uid', $uid)->get();
        $form_Select = "<option value='0'>Select Form</option>";
        $form_id = 0;
        $k = 0;
        if (count($forms) > 0) {
            foreach ($forms as $formdetails) {
                if ($k == 0) {
                    $form_id = $formdetails->form_id;
                    $k++;
                }
                $form_Select .= "<option value='" . $formdetails->form_id . "'>" . $formdetails->title . "</option>";
            }
        }
        $data['form_id'] = $form_id;
        $data['form_Select'] = $form_Select;
        //----------------------------------------------------------------

        $data['forms'] = count($forms);
        $data['webtolead'] = $this->webtolead($uid);
        $data['leads'] = $this->leads($uid);
        $data['accounts'] = $this->accounts($uid);
        $data['deals'] = $this->deals($uid);

        $salesstage = $this->salesstageOptions();

        $data['default_salesfunnel'] = $salesstage['default_salesfunnel'];
        $data['salesfunnelOptions'] = $salesstage['salesfunnelOptions'];

        $latestDeals = $this->getLatestDeals();
        $data['latestDeals'] = $latestDeals;

        $subusers = new SubuserController();
        $data['subusers'] = $subusers->getSubusers($uid);



        return view('auth.dashboard')->with('data', $data);    //home
    }

    public function salesstageOptions()
    {
        $forms = Tbl_salesfunnel::all();
        $formSelect = '<option value="0">Select Deal Stage</option>';
        $i = 0;
        $default_form = 0;
        foreach ($forms as $form) {
            if ($i == 0) {
                $default_form = $form->sfun_id;
                $formSelect .= '<option value="' . $form->sfun_id . '" selected>' . $form->salesfunnel . '</option>';
                $i++;
            } else {
                $formSelect .= '<option value="' . $form->sfun_id . '">' . $form->salesfunnel . '</option>';
            }
        }
        $data['default_salesfunnel'] = $default_form;
        $data['salesfunnelOptions'] = $formSelect;
        return $data;
    }

    public function formOptions()
    {
        $uid = Auth::user()->id;
        $forms = Tbl_forms::where('uid', $uid)->where('active', 1)->get();
        $formSelect = '<option value="0">Select Form</option>';
        $i = 0;
        $default_form = 0;
        foreach ($forms as $form) {
            if ($i == 0) {
                $default_form = $form->form_id;
                $formSelect .= '<option value="' . $form->form_id . '" selected>' . $form->title . ' ' . $form->post_url . '</option>';
                $i++;
            } else {
                $formSelect .= '<option value="' . $form->form_id . '">' . $form->title . ' ' . $form->post_url . '</option>';
            }
        }
        $data['default_form'] = $default_form;
        $data['formOptions'] = $formSelect;
        return $data;
    }

    public function getViewsreport(Request $request)
    {
        $form_id = $request->input('form_id');
        // return $form_id;

        $sql = 'SELECT YEAR(`created_at`) AS y, DATE_FORMAT(`created_at`, "%b") as m, COUNT(`fv_id`) as views FROM `tbl_formviews` WHERE `form_id`=' . $form_id . ' GROUP BY y, m';
        // return $sql;

        $formviews = DB::select($sql);
        $values = [];
        $months = [];
        if (count($formviews) > 0) {
            foreach ($formviews as $views) {
                $values[] = $views->views;
                $months[] = $views->m;
            }
        }

        $data['values'] = $values;
        $data['months'] = $months;

        return json_encode($data);
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

    public function getSalesFunnel(Request $request)
    {

        $time = $request->input('time');
        $uid = Auth::user()->id;
        $data = $this->getSalesFunnelData($time, $uid);
        return json_encode($data);
    }

    public function getSalesFunnelData($time, $uid)
    {
        $user = User::find($uid);
        $currency = currency::find($user->cr_id);

        $color = ['#3c8dbc', '#00c0ef', '#f39c12', '#00a65a', '#f56954', '#27c6db'];
        $salesfunnel = Tbl_salesfunnel::all();
        $data = array();
        if ($time == 'day') {
            $date = date('Y-m-d');
            foreach ($salesfunnel as $index => $sfun) {
                $deals = Tbl_deals::where('uid', $uid)->where('sfun_id', $sfun->sfun_id)->where('active', 1)->where(DB::raw('DATE(updated_at)'), $date)->sum('value');
                $data['labels'][] = $sfun->salesfunnel;
                $data['dataset'][] = $deals;
                $data['color'][] = $color[$index]; //$this->getRandomColor();

                if (strtolower($sfun->salesfunnel) == 'won') {
                    $data['wonAmount'] = 'Total Amount <span>&nbsp;' . $currency->html_code . '<span>' . $deals;
                }
            }
            $data['title'] = 'Deals of day ' . date('d/m/Y', strtotime($date));
        }
        if ($time == 'week') {

            $lastmonday = strtotime("last monday");
            $monday = date('w', $lastmonday) == date('w') ? $lastmonday + 7 * 86400 : $lastmonday;

            $sunday = strtotime(date("Y-m-d", $monday) . " +6 days");

            $start = date("Y-m-d", $monday);
            $end = date("Y-m-d", $sunday);

            foreach ($salesfunnel as $index => $sfun) {
                $deals = Tbl_deals::where('uid', $uid)->where('sfun_id', $sfun->sfun_id)->where('active', 1)->whereBetween(DB::raw('DATE(updated_at)'), [$start, $end])->sum('value');
                $data['labels'][] = $sfun->salesfunnel;
                $data['dataset'][] = $deals;
                $data['color'][] = $color[$index];

                if (strtolower($sfun->salesfunnel) == 'won') {
                    $data['wonAmount'] = 'Total Amount <span>&nbsp;' . $currency->html_code . '<span>' . $deals;
                }
            }
            $data['title'] = 'Deals of week ' . date('d/m/Y', strtotime($start)) . ' and ' . date('d/m/Y', strtotime($end));
        }
        if ($time == 'month') {
            $date = date('m');
            foreach ($salesfunnel as $index => $sfun) {
                $deals = Tbl_deals::where('uid', $uid)->where('sfun_id', $sfun->sfun_id)->where('active', 1)->where(DB::raw('MONTH(updated_at)'), $date)->sum('value');
                $data['labels'][] = $sfun->salesfunnel;
                $data['dataset'][] = $deals;
                $data['color'][] = $color[$index];

                if (strtolower($sfun->salesfunnel) == 'won') {
                    $data['wonAmount'] = 'Total Amount <span>&nbsp;' . $currency->html_code . '<span>' . $deals;
                }
            }
            $data['title'] = 'Deals of ' . date('M Y');
        }
        if ($time == 'year') {
            $date = date('Y');
            foreach ($salesfunnel as $index => $sfun) {
                $deals = Tbl_deals::where('uid', $uid)->where('sfun_id', $sfun->sfun_id)->where('active', 1)->where(DB::raw('YEAR(updated_at)'), $date)->sum('value');
                $data['labels'][] = $sfun->salesfunnel;
                $data['dataset'][] = $deals;
                $data['color'][] = $color[$index];

                if (strtolower($sfun->salesfunnel) == 'won') {
                    $data['wonAmount'] = 'Total Amount <span>&nbsp;' . $currency->html_code . '<span>' . $deals;
                }
            }
            $data['title'] = 'Deals of ' . date('Y', strtotime($date));
        }

        return $data;
    }

    public function getSalesFunnelD3(Request $request)
    {

        $time = $request->input('time');
        $uid = Auth::user()->id;
        $data = $this->getSalesFunnelDataD3($time, $uid);
        return json_encode($data);
    }

    public function getSalesFunnelDataD3($time, $uid)
    {
        $user = User::find($uid);
        $currency = currency::find($user->cr_id);

        //        $color = ['#3c8dbc', '#00c0ef', '#f39c12', '#00a65a', '#f56954', '#27c6db'];
        $salesfunnel = Tbl_salesfunnel::all();
        $data = array();
        if ($time == 'day') {
            $date = date('Y-m-d');
            foreach ($salesfunnel as $index => $sfun) {
                $deals = Tbl_deals::where('uid', $uid)->where('sfun_id', $sfun->sfun_id)->where('active', 1)->where(DB::raw('DATE(updated_at)'), $date)->sum('value');
                $dataset[] = array($sfun->salesfunnel, $deals, '#' . $sfun->color);

                if (strtolower($sfun->salesfunnel) == 'won') {
                    $dealK = $this->thousandtoK($deals);
                    $data['wonAmount'] = 'Total Amount <span>&nbsp;' . $currency->html_code . '<span>' . $dealK;
                }
            }
            $data['dataset'] = $dataset;
            $data['title'] = 'Deals of day ' . date('d/m/Y', strtotime($date));
        }
        if ($time == 'week') {

            $lastmonday = strtotime("last monday");
            $monday = date('w', $lastmonday) == date('w') ? $lastmonday + 7 * 86400 : $lastmonday;

            $sunday = strtotime(date("Y-m-d", $monday) . " +6 days");

            $start = date("Y-m-d", $monday);
            $end = date("Y-m-d", $sunday);

            foreach ($salesfunnel as $index => $sfun) {
                $deals = Tbl_deals::where('uid', $uid)->where('sfun_id', $sfun->sfun_id)->where('active', 1)->whereBetween(DB::raw('DATE(updated_at)'), [$start, $end])->sum('value');
                $dataset[] = array($sfun->salesfunnel, $deals, '#' . $sfun->color);

                if (strtolower($sfun->salesfunnel) == 'won') {
                    $dealK = $this->thousandtoK($deals);
                    $data['wonAmount'] = 'Total Amount <span>&nbsp;' . $currency->html_code . '<span>' . $dealK;
                }
            }
            $data['dataset'] = $dataset;
            $data['title'] = 'Deals of week ' . date('d/m/Y', strtotime($start)) . ' and ' . date('d/m/Y', strtotime($end));
        }
        if ($time == 'month') {
            //            $date = date('m');
            $month = date('m');
            $year = date('Y');
            foreach ($salesfunnel as $index => $sfun) {
                $deals = Tbl_deals::where('uid', $uid)
                    ->where('sfun_id', $sfun->sfun_id)
                    ->where('active', 1)
                    ->where(DB::raw('MONTH(updated_at)'), $month)
                    ->where(DB::raw('YEAR(updated_at)'), $year)
                    ->sum('value');

                $dataset[] = array($sfun->salesfunnel, $deals, '#' . $sfun->color);

                if (strtolower($sfun->salesfunnel) == 'won') {
                    $dealK = $this->thousandtoK($deals);
                    $data['wonAmount'] = 'Total Amount <span>&nbsp;' . $currency->html_code . '<span>' . $dealK;
                }
            }
            $data['dataset'] = $dataset;
            $data['title'] = 'Deals of ' . date('M Y');
        }
        if ($time == 'year') {
            $date = date('Y');
            foreach ($salesfunnel as $index => $sfun) {
                $deals = Tbl_deals::where('uid', $uid)->where('sfun_id', $sfun->sfun_id)->where('active', 1)->where(DB::raw('YEAR(updated_at)'), $date)->sum('value');

                $dataset[] = array($sfun->salesfunnel, $deals, '#' . $sfun->color);

                if (strtolower($sfun->salesfunnel) == 'won') {
                    $dealK = $this->thousandtoK($deals);
                    $data['wonAmount'] = 'Total Sales <span class="badge badge-secondary">' . $currency->html_code . '<span>' . $dealK;
                }
            }
            $data['dataset'] = $dataset;
            $data['title'] = 'Deals of ' . date('Y', strtotime($date));
        }

        return $data;
    }

    public function getDealsStage(Request $request)
    {

        $time = $request->input('time');
        $uid = Auth::user()->id;
        $data = $this->getDealsStageData($time, $uid);
        return json_encode($data);
    }

    public function getDealsStageData($time, $uid)
    {
        $user = User::find($uid);
        $currency = currency::find($user->cr_id);
        $salesfunnel = Tbl_salesfunnel::all();
        $data = array();
        if ($time == 'day') {
            $date = date('Y-m-d');
            foreach ($salesfunnel as $sfun) {
                $deals = Tbl_deals::where('uid', $uid)->where('sfun_id', $sfun->sfun_id)->where('active', 1)->where(DB::raw('DATE(updated_at)'), $date)->count();
                $data['labels'][] = $sfun->salesfunnel;
                $data['dataset'][] = $deals;
                //                $data['color'][] = $this->getRandomColor();
            }
            $data['title'] = 'Deals of day ' . date('d-m-Y', strtotime($date));
            $data['total'] = array_sum($data['dataset']);
        }
        if ($time == 'week') {

            $lastmonday = strtotime("last monday");
            $monday = date('w', $lastmonday) == date('w') ? $lastmonday + 7 * 86400 : $lastmonday;

            $sunday = strtotime(date("Y-m-d", $monday) . " +6 days");

            $start = date("Y-m-d", $monday);
            $end = date("Y-m-d", $sunday);

            foreach ($salesfunnel as $sfun) {
                $deals = Tbl_deals::where('uid', $uid)->where('sfun_id', $sfun->sfun_id)->where('active', 1)->whereBetween(DB::raw('DATE(updated_at)'), [$start, $end])->count();
                $data['labels'][] = $sfun->salesfunnel;
                $data['dataset'][] = $deals;
                //                $data['color'][] = $this->getRandomColor();
            }
            $data['title'] = 'Deals of week ' . date('d-m-Y', strtotime($start)) . ' and ' . date('d-m-Y', strtotime($end));
            $data['total'] = array_sum($data['dataset']);
        }
        if ($time == 'month') {
            $date = date('m');
            foreach ($salesfunnel as $sfun) {
                $deals = Tbl_deals::where('uid', $uid)->where('sfun_id', $sfun->sfun_id)->where('active', 1)->where(DB::raw('MONTH(updated_at)'), $date)->count();
                $data['labels'][] = $sfun->salesfunnel;
                $data['dataset'][] = $deals;
                //                $data['color'][] = $this->getRandomColor();
            }
            $data['title'] = 'Deals of month ' . date('M Y');
            $data['total'] = array_sum($data['dataset']);
        }
        if ($time == 'year') {
            $date = date('Y');
            foreach ($salesfunnel as $sfun) {
                $deals = Tbl_deals::where('uid', $uid)->where('sfun_id', $sfun->sfun_id)->where('active', 1)->where(DB::raw('YEAR(updated_at)'), $date)->count();
                $data['labels'][] = $sfun->salesfunnel;
                $data['dataset'][] = $deals;
                //                $data['color'][] = $this->getRandomColor();
            }
            $data['title'] = 'Deals of year ' . date('Y', strtotime($date));
            $data['total'] = array_sum($data['dataset']);
        }

        return $data;
    }

    public function getRandomColor()
    {
        return '#' . dechex(rand(0x000000, 0xFFFFFF));
    }

    public function globalReports($uid)
    {
        $user = User::find($uid);
        $currency = currency::find($user->cr_id);
        $data = array();
        //---------------subusers-----------------------
        $subusers = User::where('user', $uid)->count();
        $data['subusers'] = $subusers;

        //---------------Leads-----------------------
        $leads = Tbl_leads::where('uid', $uid)->where('active', 1)->count();
        $data['leads'] = $leads;

        //---------------Accounts-----------------------
        $accounts = Tbl_Accounts::where('uid', $uid)->where('active', 1)->count();
        $data['accounts'] = $accounts;

        //---------------Contacts-----------------------
        $contacts = Tbl_contacts::where('uid', $uid)->where('active', 1)->count();
        $data['contacts'] = $contacts;

        //---------------Deals-----------------------
        $deals = Tbl_deals::where('uid', $uid)->where('active', 1)->count();
        $data['deals'] = $deals;

        //---------------Won-----------------------
        $won = Tbl_deals::where('uid', $uid)->where('deal_status', 1)->where('active', 1)->sum('value');
        $data['won'] = '<span>' . $currency->html_code . '<span> ' . $won;

        //---------------Lost-----------------------
        // echo $uid;
        // exit();
        $lost = Tbl_deals::where('uid', $uid)->where('deal_status', 2)->where('active', 1)->sum('value');
        // echo $lost;
        

        $data['lost'] = '<span>' . $currency->html_code . '<span> ' . $lost;

        //---------------Sales-----------------------
        $sales = Tbl_deals::where('uid', $uid)->where('deal_status', 1)->where('active', 1)->sum('value');
        $data['sales'] = '<span>' . $currency->html_code . '<span> ' . $sales;

        //---------------Forms-----------------------
        $forms = Tbl_forms::where('uid', $uid)->where('active', 1)->count();
        $data['forms'] = $forms;

        //---------------Formleads-----------------------
        $formleads = Tbl_formleads::where('uid', $uid)->where('active', 1)->count();
        $data['formleads'] = $formleads;

        //---------------Customers-----------------------

        $customers = Tbl_deals::where('active', 1)->with('tbl_salesfunnel')->with('tbl_leadsource')
            ->with(array('tbl_leads' => function ($query) {
                $query->where('tbl_leads.active', 1);
            }))
            ->where('uid', $uid)
            ->where('deal_status', 1)
            ->count();
        $data['customers'] = $customers;

        //---------------Events-----------------------
        $events = Tbl_events::where('uid', $uid)->where('active', 1)->count();
        $data['events'] = $events;

        //---------------Territory-----------------------
        $territory = Tbl_territory::where('uid', $uid)->where('active', 1)->count();
        $data['territory'] = $territory;

        return $data;
    }

    public function getLeadsData(Request $request)
    {
        $time = $request->input('time');
        //        $uid = Auth::user()->id;
        $lead = new ReportsController();
        $data = '';
        if ($time == 'day') {
            $data = $lead->getDayLeads();
        }
        if ($time == 'week') {
            $data = $lead->getWeekLeads();
        }
        if ($time == 'month') {
            $data = $lead->getMonthLeads();
        }
        if ($time == 'year') {
            $data = $lead->getYearLeads();
        }
        return $data;
    }

    public function getAccountsData(Request $request)
    {
        $time = $request->input('time');
        //        $uid = Auth::user()->id;
        $lead = new ReportsController();
        $data = '';
        if ($time == 'day') {
            $data = $lead->getDayAccounts();
        }
        if ($time == 'week') {
            $data = $lead->getWeekAccounts();
        }
        if ($time == 'month') {
            $data = $lead->getMonthAccounts();
        }
        if ($time == 'year') {
            $data = $lead->getYearAccounts();
        }
        return $data;
    }

    public function getContactsData(Request $request)
    {
        $time = $request->input('time');
        $lead = new ReportsController();
        $data = '';
        if ($time == 'day') {
            $data = $lead->getDayContacts();
        }
        if ($time == 'week') {
            $data = $lead->getWeekContacts();
        }
        if ($time == 'month') {
            $data = $lead->getMonthContacts();
        }
        if ($time == 'year') {
            $data = $lead->getYearContacts();
        }
        return $data;
    }

    public function getCustomersData(Request $request)
    {
        $time = $request->input('time');
        $lead = new ReportsController();
        $data = '';
        if ($time == 'day') {
            $data = $lead->getDayCustomers();
        }
        if ($time == 'week') {
            $data = $lead->getWeekCustomers();
        }
        if ($time == 'month') {
            $data = $lead->getMonthCustomers();
        }
        if ($time == 'year') {
            $data = $lead->getYearCustomers();
        }
        return $data;
    }

    public function getSalesData(Request $request)
    {
        $time = $request->input('time');
        $lead = new ReportsController();
        $data = '';
        if ($time == 'day') {
            $data = $lead->getDaySales();
        }
        if ($time == 'week') {
            $data = $lead->getWeekSales();
        }
        if ($time == 'month') {
            $data = $lead->getMonthSales();
        }
        if ($time == 'year') {
            $data = $lead->getYearSales();
        }
        return $data;
    }

    public function getLatestDeals()
    {
        $uid = Auth::user()->id;
        $user = User::find($uid);
        $cr_id = $user->cr_id;
        $currency = currency::find($cr_id);

        $deals = Tbl_deals::where('uid', $uid)->where('active', 1)->with('tbl_leads')
            ->with('Tbl_leadsource')
            ->with('Tbl_salesfunnel')
            ->orderBy('deal_id', 'desc')
            ->take(50)
            ->get();

        $dealstage = Tbl_salesfunnel::all();

        $total = count($deals);
        if ($total > 0) {
            $formstable = '<div class="table-responsive"><table id="dealsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Deal Name</th>';
			$formstable .= '<th></th>';
            $formstable .= '<th>Lead</th>';
            $formstable .= '<th>Amount<span>&nbsp;(' . $currency->html_code . ')</span></th>';
            // $formstable .= '<th>Closing Date</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($deals as $formdetails) {

                $leadimage = ($formdetails['tbl_leads']['picture'] != '') ? $formdetails['tbl_leads']['picture'] : '/uploads/default/leads.png';
                $leadsource = ($formdetails['tbl_leadsource'] != '') ? $formdetails['tbl_leadsource']['leadsource'] : '';
				$sales_color = ($formdetails['tbl_salesfunnel'] != '') ? $formdetails['tbl_salesfunnel']['color'] : '';
				$sales_funnel = ($formdetails['tbl_salesfunnel'] != '') ? $formdetails['tbl_salesfunnel']['salesfunnel'] : '';

                $formstable .= '<tr>';
                $formstable .= '<td class="table-title"><a href="' . url('deals/' . $formdetails['deal_id']) . '">' . $formdetails['name'] . '</a></td>';
				$formstable .= '<td class="table-title"><label style="border: 0;border-radius: 2px;font-size: 11px;font-weight: 400;padding: 1px 5px;width: 80px;  text-align: center;color: #fff;background-color:#' . $sales_color . '">' . $sales_funnel . '</label></td>';
                $formstable .= '<td><img src="' . url($leadimage) . '" width="30" height="24">&nbsp;' . $formdetails['tbl_leads']['first_name'] . ' ' . $formdetails['tbl_leads']['last_name'] . '</td>';
                $formstable .= '<td>' . $formdetails['value'] . '</td>';
                // $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails['closing_date'])) . '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table></div>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;

        return $data;
    }

    public function getSubusers($uid)
    {
        $users = User::where('user', $uid)->orderBy('id', 'desc')->get();
        $total = count($users);
        if ($total > 0) {
            $table = '<div class="table-responsive"><table id="subusersTable" class="table table-bordered table-striped">';
            $table .= '<thead>';
            $table .= '<tr>';
            $table .= '<th>Name</th>';
            $table .= '<th>Email</th>';
            $table .= '<th>Job Title</th>';
            $table .= '<th>Date</th>';
            //            $table .= '<th>Action</th>';
            $table .= '</tr>';
            $table .= '</thead>';
            $table .= '<tbody>';
            foreach ($users as $user) {

                $userimage = ($user->picture != '') ? $user->picture : 'uploads/default/user.png';

                $table .= '<tr>';
                $table .= '<td><img src="' . url($userimage) . '" height="24" width="30">&nbsp;<a href="' . url('/subusers/view/' . $user->id) . '">' . $user->name . '</a></td>';
                $table .= '<td>' . $user->email . '</td>';
                $table .= '<td>' . $user->jobtitle . '</td>';
                $table .= '<td>' . date('d-m-Y', strtotime($user->created_at)) . '</td>';
                //                $table .= '<td>';
                //                $table .= '<div class="btn-group">';
                //                $table .= '<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">';
                //                $table .= '<span class="caret"></span>';
                //                $table .= '<span class="sr-only">Toggle Dropdown</span>';
                //                $table .= '</button>';
                //                $table .= '<ul class="dropdown-menu" role="menu">';
                //                $table .= '<li><a class="text-default text-btn-space" href="' . url('/subusers/' . $user->id) . '">View</a></li>';
                //                $table .= '<li><a class="text-danger text-btn-space" href="#">Delete</a></li>';
                //                $table .= '</ul>';
                //                $table .= ' </div>';
                //                $table .= '</td>';
                $table .= '</tr>';
            }
            $table .= '</tbody>';
            $table .= '</table></div>';
        } else {
            $table = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $table;
        return $data;
    }

    public function thousandtoK($input)
    {
        $input = number_format($input);
        $input_count = substr_count($input, ',');
        if ($input_count != '0') {
            if ($input_count == '1') {
                return substr($input, 0, -4) . 'k';
            } else if ($input_count == '2') {
                return substr($input, 0, -8) . 'mil';
            } else if ($input_count == '3') {
                return substr($input, 0, -12) . 'bil';
            } else {
                return;
            }
        } else {
            return $input;
        }
    }
}

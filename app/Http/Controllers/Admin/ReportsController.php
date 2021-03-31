<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;
use DateTime;
//----------------Models-------------------
use App\User;
use App\Admin;
use App\Tbl_salesfunnel;
use App\Tbl_forms;
use App\Tbl_formleads;
use App\Tbl_leads;
use App\Tbl_Accounts;
use App\Tbl_deals;
use App\Tbl_contacts;
use App\currency;
use App\Tbl_cart_orders;

class ReportsController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('test:reports', ['only' => ['index']]);
    }

    //-----------------Leads------------------------------------    

    public function leads()
    {
        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        $useroptions = "<option value='All'>All</option>";
        //        $u = 0;
        $uid = 'All';
        foreach ($users as $userdetails) {
            //            if ($u == 0) {
            //                $uid = $userdetails->id;
            //                $u+=1;
            //            }
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";   // " . $selected . "
        }
        $data['useroptions'] = $useroptions;
        $data['user'] = $uid;
        return view('admin.reports.leads')->with('data', $data);
    }

    public function getDayLeads(Request $request)
    {

        //        return json_encode($request->input());

        $uid = $request->input('uid');
        $label = 'Leads - Last 30 Days';
        //        return $label;
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 30; $i++) {
            $date = date('Y-m-d', strtotime('today - ' . $i . ' days'));

            if ($uid == 'All') {
                $sql = DB::table('tbl_leads')->select(DB::raw('count(ld_id) as leads'))->where(DB::raw('DATE(created_at)'), $date)->count();
            } else {
                $sql = DB::table('tbl_leads')->select(DB::raw('count(ld_id) as leads'))->where('uid', $uid)->where(DB::raw('DATE(created_at)'), $date)->count();
            }

            $labels[] = date('d M', strtotime($date));
            $formleads[] = $sql;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        return json_encode($data);
    }

    public function getMonthLeads(Request $request)
    {
        $uid = $request->input('uid');

        $label = 'Leads - Last 12 Months';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 12; $i++) {

            $query_date = date('Y-m-d', strtotime('today - ' . $i . ' months'));
            $monthStart = date('Y-m-01', strtotime($query_date));
            $monthEnd = date('Y-m-t', strtotime($query_date));
            $endDate = $monthEnd;
            $startDate = $monthStart;

            $date = date('M y', strtotime($startDate));

            if ($uid == 'All') {
                $sql = DB::table('tbl_leads')->select(DB::raw('count(ld_id) as leads'))->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])->count();
            } else {
                $sql = DB::table('tbl_leads')->select(DB::raw('count(ld_id) as leads'))->where('uid', $uid)->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])->count();
            }

            $labels[] = $date;
            $formleads[] = $sql;    //->leads
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        return json_encode($data);
    }

    public function getWeekLeads(Request $request)
    {
        $uid = $request->input('uid');

        $label = 'Leads - Last 12 Weeks';
        $today = date('Y-m-d');
        $year = date('Y', strtotime($today));
        $week = date('W', strtotime($today));
        $k = $week - 11;
        for ($i = $week; $i >= $k; $i--) {
            $dto = new DateTime();
            $start = $dto->setISODate($year, $i, 1)->format('Y-m-d');
            $end = $dto->setISODate($year, $i, 7)->format('Y-m-d');
            $date = date('d M', strtotime($start));

            if ($uid == 'All') {
                $sql = DB::table('tbl_leads')
                    ->select(DB::raw('count(ld_id) as leads'))
                    ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
                    ->count();
            } else {
                $sql = DB::table('tbl_leads')
                    ->select(DB::raw('count(ld_id) as leads'))
                    ->where('uid', $uid)
                    ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
                    ->count();
            }


            $labels[] = $date;
            $formleads[] = $sql;    //->leads
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        return json_encode($data);
    }

    public function getYearLeads(Request $request)
    {

        //        return json_encode($request->input());
        //        exit(0);
        $uid = $request->input('uid');

        $label = 'Leads - Last 12 Years';
        for ($i = 0; $i < 12; $i++) {
            $year = date("Y", strtotime("-" . $i . " year"));
            if ($uid == 'All') {
                $sql = Tbl_leads::where('active', 1)->where(DB::raw('YEAR(created_at)'), $year)->count();
            } else {
                $sql = Tbl_leads::where('uid', $uid)->where('active', 1)->where(DB::raw('YEAR(created_at)'), $year)->count();
            }

            $labels[] = $year;
            $formleads[] = $sql;
        }

        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        return json_encode($data);
    }

    //-----------------Accounts------------------------------------


    public function accounts()
    {
        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        $useroptions = "<option value='All'>All</option>";
        //        $u = 0;
        $uid = "All";
        foreach ($users as $userdetails) {
            //            if ($u == 0) {
            //                $uid = $userdetails->id;
            //                $u+=1;
            //            }
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";   // " . $selected . "
        }
        $data['useroptions'] = $useroptions;
        $data['user'] = $uid;
        return view('admin.reports.accounts')->with("data", $data);
    }

    public function getDayAccounts(Request $request)
    {
        $uid = $request->input('uid');
        $label = 'Accounts - Last 30 Days';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 30; $i++) {
            $date = date('Y-m-d', strtotime('today - ' . $i . ' days'));

            if ($uid == 'All') {
                $sql = DB::table('tbl_accounts')
                    ->select(DB::raw('count(acc_id) as accounts'))
                    ->where(DB::raw('DATE(created_at)'), $date)
                    ->count();
            } else {
                $sql = DB::table('tbl_accounts')
                    ->select(DB::raw('count(acc_id) as accounts'))
                    ->where('uid', $uid)
                    ->where(DB::raw('DATE(created_at)'), $date)
                    ->count();
            }

            $labels[] = date('d M', strtotime($date));
            $formleads[] = $sql;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        return json_encode($data);
    }

    public function getMonthAccounts(Request $request)
    {
        $uid = $request->input('uid');

        $label = 'Accounts - Last 12 Months';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 12; $i++) {
            $query_date = date('Y-m-d', strtotime('today - ' . $i . ' months'));
            $monthStart = date('Y-m-01', strtotime($query_date));
            $monthEnd = date('Y-m-t', strtotime($query_date));
            $endDate = $monthEnd;
            $startDate = $monthStart;

            $date = date('M y', strtotime($startDate));

            if ($uid == 'All') {
                $sql = DB::table('tbl_accounts')
                    ->select(DB::raw('count(acc_id) as accounts'))
                    ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                    ->count();
            } else {
                $sql = DB::table('tbl_accounts')
                    ->select(DB::raw('count(acc_id) as accounts'))
                    ->where('uid', $uid)
                    ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                    ->count();
            }


            $labels[] = $date;
            $formleads[] = $sql;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        return json_encode($data);
    }

    public function getWeekAccounts(Request $request)
    {
        $uid = $request->input('uid');

        $label = 'Accounts - Last 12 Weeks';
        $today = date('Y-m-d');
        $year = date('Y', strtotime($today));
        $week = date('W', strtotime($today));
        $k = $week - 11;
        for ($i = $week; $i >= $k; $i--) {
            $dto = new DateTime();
            $start = $dto->setISODate($year, $i, 1)->format('Y-m-d');
            $end = $dto->setISODate($year, $i, 7)->format('Y-m-d');
            //            echo "Week:" . $i . " Start date:" . $start . " End date:" . $end . "<br>";

            $date = date('d M', strtotime($start));
            if ($uid == 'All') {
                $sql = DB::table('tbl_accounts')
                    ->select(DB::raw('count(acc_id) as accounts'))
                    ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
                    ->count();
            } else {
                $sql = DB::table('tbl_accounts')
                    ->select(DB::raw('count(acc_id) as accounts'))
                    ->where('uid', $uid)
                    ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
                    ->count();
            }

            $labels[] = $date;
            $formleads[] = $sql;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        return json_encode($data);
    }

    public function getYearAccounts(Request $request)
    {
        $uid = $request->input('uid');

        $label = 'Accounts - Last 12 Years';
        for ($i = 0; $i < 12; $i++) {
            $year = date("Y", strtotime("-" . $i . " year"));
            if ($uid == 'All') {
                $sql = Tbl_Accounts::where('active', 1)->where(DB::raw('YEAR(created_at)'), $year)->count();
            } else {
                $sql = Tbl_Accounts::where('uid', $uid)->where('active', 1)->where(DB::raw('YEAR(created_at)'), $year)->count();
            }

            $labels[] = $year;
            $formleads[] = $sql;
        }

        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        return json_encode($data);
    }

    public function contacts()
    {
        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        $useroptions = "<option value='All'>All</option>";
        //        $u = 0;
        $uid = "All";
        foreach ($users as $userdetails) {
            //            if ($u == 0) {
            //                $uid = $userdetails->id;
            //                $u+=1;
            //            }
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";   // " . $selected . "
        }
        $data['useroptions'] = $useroptions;
        $data['user'] = $uid;
        return view('admin.reports.contacts')->with("data", $data);
    }

    public function getDayContacts(Request $request)
    {
        $uid = $request->input('uid');
        $label = 'Contacts - Last 30 Days';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 30; $i++) {
            $date = date('Y-m-d', strtotime('today - ' . $i . ' days'));

            if ($uid == 'All') {
                $sql = DB::table('tbl_contacts')
                    ->select(DB::raw('count(cnt_id) as contacts'))
                    ->where(DB::raw('DATE(created_at)'), $date)
                    ->count();
            } else {
                $sql = DB::table('tbl_contacts')
                    ->select(DB::raw('count(cnt_id) as contacts'))
                    ->where('uid', $uid)
                    ->where(DB::raw('DATE(created_at)'), $date)
                    ->count();
            }

            $labels[] = date('d M', strtotime($date));
            $formleads[] = $sql;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        return json_encode($data);
    }

    public function getMonthContacts(Request $request)
    {
        $uid = $request->input('uid');

        $label = 'Contacts - Last 12 Months';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 12; $i++) {
            $query_date = date('Y-m-d', strtotime('today - ' . $i . ' months'));
            $monthStart = date('Y-m-01', strtotime($query_date));
            $monthEnd = date('Y-m-t', strtotime($query_date));
            $endDate = $monthEnd;
            $startDate = $monthStart;

            $date = date('M y', strtotime($startDate));
            if ($uid == 'All') {
                $sql = DB::table('tbl_contacts')
                    ->select(DB::raw('count(cnt_id) as contacts'))
                    ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                    ->count();
            } else {
                $sql = DB::table('tbl_contacts')
                    ->select(DB::raw('count(cnt_id) as contacts'))
                    ->where('uid', $uid)
                    ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                    ->count();
            }

            $labels[] = $date;
            $formleads[] = $sql;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        return json_encode($data);
    }

    public function getWeekContacts(Request $request)
    {
        $uid = $request->input('uid');

        $label = 'Contacts - Last 12 Weeks';
        $today = date('Y-m-d');
        $year = date('Y', strtotime($today));
        $week = date('W', strtotime($today));
        $k = $week - 11;
        for ($i = $week; $i >= $k; $i--) {
            $dto = new DateTime();
            $start = $dto->setISODate($year, $i, 1)->format('Y-m-d');
            $end = $dto->setISODate($year, $i, 7)->format('Y-m-d');
            //            echo "Week:" . $i . " Start date:" . $start . " End date:" . $end . "<br>";

            $date = date('d M', strtotime($start));

            if ($uid == 'All') {
                $sql = DB::table('tbl_contacts')
                    ->select(DB::raw('count(cnt_id) as contacts'))
                    ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
                    ->count();
            } else {
                $sql = DB::table('tbl_contacts')
                    ->select(DB::raw('count(cnt_id) as contacts'))
                    ->where('uid', $uid)
                    ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
                    ->count();
            }

            $labels[] = $date;
            $formleads[] = $sql;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        return json_encode($data);
    }

    public function getYearContacts(Request $request)
    {
        $uid = $request->input('uid');

        $label = 'Contacts - Last 12 Years';
        for ($i = 0; $i < 12; $i++) {
            $year = date("Y", strtotime("-" . $i . " year"));

            if ($uid == 'All') {
                $sql = Tbl_contacts::where('active', 1)->where(DB::raw('YEAR(created_at)'), $year)->count();
            } else {
                $sql = Tbl_contacts::where('uid', $uid)->where('active', 1)->where(DB::raw('YEAR(created_at)'), $year)->count();
            }

            $labels[] = $year;
            $formleads[] = $sql;
        }

        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        return json_encode($data);
    }

    public function deals()
    {

        $forms = Tbl_salesfunnel::all();
        $formSelect = '';
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
        $data['default_form'] = $default_form;
        $data['formSelect'] = $formSelect;

        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        $useroptions = "<option value='All'>All</option>";
        $uid = "All";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";
        }
        $data['useroptions'] = $useroptions;
        $data['user'] = $uid;

        return view('admin.reports.deals')->with("data", $data);
    }

    public function getDayDeals(Request $request)
    {
        $uid = $request->input('uid');
        $form_id = $request->input('form_id');

        $label = 'Form Deals - Last 30 Days';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 30; $i++) {
            $date = date('Y-m-d', strtotime('today - ' . $i . ' days'));

            if ($uid == 'All') {
                $sql = DB::table('tbl_deals')
                    ->select(DB::raw('count(deal_id) as deals'))
                    ->where('sfun_id', $form_id)
                    ->where(DB::raw('DATE(created_at)'), $date)
                    ->count();
            } else {
                $sql = DB::table('tbl_deals')
                    ->select(DB::raw('count(deal_id) as deals'))
                    ->where('sfun_id', $form_id)
                    ->where('uid', $uid)
                    ->where(DB::raw('DATE(created_at)'), $date)
                    ->count();
            }


            $labels[] = date('d M', strtotime($date));
            $formleads[] = $sql;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        return json_encode($data);
    }

    public function getMonthDeals(Request $request)
    {
        $uid = $request->input('uid');
        $form_id = $request->input('form_id');

        $label = 'Deals - Last 12 Months';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 12; $i++) {
            $query_date = date('Y-m-d', strtotime('today - ' . $i . ' months'));
            $monthStart = date('Y-m-01', strtotime($query_date));
            $monthEnd = date('Y-m-t', strtotime($query_date));
            $endDate = $monthEnd;
            $startDate = $monthStart;

            $date = date('M y', strtotime($startDate));
            if ($uid == 'All') {
                $sql = DB::table('tbl_deals')
                    ->select(DB::raw('count(deal_id) as deals'))
                    ->where('sfun_id', $form_id)
                    ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                    ->count();
            } else {
                $sql = DB::table('tbl_deals')
                    ->select(DB::raw('count(deal_id) as deals'))
                    ->where('uid', $uid)
                    ->where('sfun_id', $form_id)
                    ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                    ->count();
            }

            $labels[] = $date;
            $formleads[] = $sql;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        return json_encode($data);
    }

    public function getWeekDeals(Request $request)
    {
        $uid = $request->input('uid');
        $form_id = $request->input('form_id');

        $label = 'Deals - Last 12 Weeks';
        $today = date('Y-m-d');
        $year = date('Y', strtotime($today));
        $week = date('W', strtotime($today));
        $k = $week - 11;
        for ($i = $week; $i >= $k; $i--) {
            $dto = new DateTime();
            $start = $dto->setISODate($year, $i, 1)->format('Y-m-d');
            $end = $dto->setISODate($year, $i, 7)->format('Y-m-d');

            $date = date('d M', strtotime($start));
            if ($uid == 'All') {
                $sql = DB::table('tbl_deals')
                    ->select(DB::raw('count(deal_id) as deals'))
                    ->where('sfun_id', $form_id)
                    ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
                    ->count();
            } else {
                $sql = DB::table('tbl_deals')
                    ->select(DB::raw('count(deal_id) as deals'))
                    ->where('uid', $uid)
                    ->where('sfun_id', $form_id)
                    ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
                    ->count();
            }

            $labels[] = $date;
            $formleads[] = $sql;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        return json_encode($data);
    }

    public function getYearDeals(Request $request)
    {
        $uid = $request->input('uid');
        $form_id = $request->input('form_id');
        $time = $request->input('time');

        //        echo $time . ' ' . $form_id;

        $label = 'Deals - Last 12 Years';
        for ($i = 0; $i < 12; $i++) {
            $year = date("Y", strtotime("-" . $i . " year"));

            if ($uid == 'All') {
                $sql = Tbl_deals::where('sfun_id', $form_id)->where('active', 1)->where(DB::raw('YEAR(created_at)'), $year)->count();
            } else {
                $sql = Tbl_deals::where('uid', $uid)->where('sfun_id', $form_id)->where('active', 1)->where(DB::raw('YEAR(created_at)'), $year)->count();
            }

            $labels[] = $year;
            $formleads[] = $sql;
        }

        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        return json_encode($data);
    }

    public function customers()
    {

        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        $useroptions = "<option value='All'>All</option>";
        $uid = "All";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";
        }
        $data['useroptions'] = $useroptions;
        $data['user'] = $uid;
        return view('admin.reports.customers')->with("data", $data);
    }

    public function getDayCustomers(Request $request)
    {
        $uid = $request->input('uid');
        $label = 'Customers - Last 30 Days';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 30; $i++) {
            $date = date('Y-m-d', strtotime('today - ' . $i . ' days'));
            if ($uid == 'All') {
                $sql = DB::table('tbl_deals')
                    ->select(DB::raw('count(DISTINCT(ld_id)) as customers'))
                    ->where('sfun_id', 5)
                    ->where(DB::raw('DATE(closing_date)'), $date)
                    ->count();
            } else {
                $sql = DB::table('tbl_deals')
                    ->select(DB::raw('count(DISTINCT(ld_id)) as customers'))
                    ->where('uid', $uid)
                    ->where('sfun_id', 5)
                    ->where(DB::raw('DATE(closing_date)'), $date)
                    ->count();
            }

            $labels[] = date('d M', strtotime($date));
            $formleads[] = $sql;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        return json_encode($data);
    }

    public function getMonthCustomers(Request $request)
    {
        $uid = $request->input('uid');

        $label = 'Customers - Last 12 Months';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 12; $i++) {
            $query_date = date('Y-m-d', strtotime('today - ' . $i . ' months'));
            $monthStart = date('Y-m-01', strtotime($query_date));
            $monthEnd = date('Y-m-t', strtotime($query_date));
            $endDate = $monthEnd;
            $startDate = $monthStart;

            $date = date('M y', strtotime($startDate));
            if ($uid == 'All') {
                $sql = DB::table('tbl_deals')
                    ->select(DB::raw('count(DISTINCT(ld_id)) as customers'))
                    ->where('sfun_id', 5)
                    ->whereBetween(DB::raw('DATE(closing_date)'), [$startDate, $endDate])
                    ->count();
            } else {
                $sql = DB::table('tbl_deals')
                    ->select(DB::raw('count(DISTINCT(ld_id)) as customers'))
                    ->where('uid', $uid)
                    ->where('sfun_id', 5)
                    ->whereBetween(DB::raw('DATE(closing_date)'), [$startDate, $endDate])
                    ->count();
            }

            $labels[] = $date;
            $formleads[] = $sql;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        return json_encode($data);
    }

    public function getWeekCustomers(Request $request)
    {
        $uid = $request->input('uid');

        $label = 'Customers - Last 12 Weeks';
        $today = date('Y-m-d');
        $year = date('Y', strtotime($today));
        $week = date('W', strtotime($today));
        $k = $week - 11;
        for ($i = $week; $i >= $k; $i--) {
            $dto = new DateTime();
            $start = $dto->setISODate($year, $i, 1)->format('Y-m-d');
            $end = $dto->setISODate($year, $i, 7)->format('Y-m-d');

            $date = date('d M', strtotime($start));
            if ($uid == 'All') {
                $sql = DB::table('tbl_deals')
                    ->select(DB::raw('count(DISTINCT(ld_id)) as customers'))
                    ->where('sfun_id', 5)
                    ->whereBetween(DB::raw('DATE(closing_date)'), [$start, $end])
                    ->count();
            } else {
                $sql = DB::table('tbl_deals')
                    ->select(DB::raw('count(DISTINCT(ld_id)) as customers'))
                    ->where('uid', $uid)
                    ->where('sfun_id', 5)
                    ->whereBetween(DB::raw('DATE(closing_date)'), [$start, $end])
                    ->count();
            }

            $labels[] = $date;
            $formleads[] = $sql;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        return json_encode($data);
    }

    public function getYearCustomers()
    {
        $uid = Auth::user()->id;

        $label = 'Customers - Last 12 Years';
        for ($i = 0; $i < 12; $i++) {
            $year = date("Y", strtotime("-" . $i . " year"));

            if ($uid == 'All') {
                $sql = DB::table('tbl_deals')
                    ->select(DB::raw('count(DISTINCT(ld_id)) as customers'))
                    ->where('deal_status', 1)
                    ->where('active', 1)
                    ->where(DB::raw('YEAR(closing_date)'), $year)
                    ->first();
            } else {
                $sql = DB::table('tbl_deals')
                    ->select(DB::raw('count(DISTINCT(ld_id)) as customers'))
                    ->where('uid', $uid)
                    ->where('sfun_id', 5)
                    ->where('active', 1)
                    ->where(DB::raw('YEAR(closing_date)'), $year)
                    ->first();
            }
            $labels[] = $year;
            $formleads[] = $sql->customers;
        }

        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        return json_encode($data);
    }

    public function sales()
    {
        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        $useroptions = "<option value='All'>All</option>";
        $uid = "All";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";
        }
        $data['useroptions'] = $useroptions;
        $data['user'] = $uid;
        return view('admin.reports.sales')->with("data", $data);
    }

    public function getDaySales(Request $request)
    {
        $uid = $request->input('uid');

        $label = 'Sales - Last 30 Days';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 30; $i++) {
            $date = date('Y-m-d', strtotime('today - ' . $i . ' days'));
            if ($uid == 'All') {
                $sql = DB::table('tbl_deals')
                    //                        ->select(DB::raw('SUM(value) as sales'))
                    ->where('sfun_id', 5)
                    ->where(DB::raw('DATE(closing_date)'), $date)
                    ->sum('value');
            } else {
                $sql = DB::table('tbl_deals')
                    //                        ->select(DB::raw('SUM(value) as sales'))
                    ->where('uid', $uid)
                    ->where('sfun_id', 5)
                    ->where(DB::raw('DATE(closing_date)'), $date)
                    ->sum('value');
            }

            $labels[] = date('d M', strtotime($date));
            $formleads[] = $sql;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        return json_encode($data);
    }

    public function getMonthSales(Request $request)
    {
        $uid = $request->input('uid');

        $label = 'Sales - Last 12 Months';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 12; $i++) {
            $query_date = date('Y-m-d', strtotime('today - ' . $i . ' months'));
            $monthStart = date('Y-m-01', strtotime($query_date));
            $monthEnd = date('Y-m-t', strtotime($query_date));
            $endDate = $monthEnd;
            $startDate = $monthStart;

            $date = date('M y', strtotime($startDate));
            if ($uid == 'All') {
                $sql = DB::table('tbl_deals')
                    //                        ->select(DB::raw('SUM(value) as sales'))
                    ->where('sfun_id', 5)
                    ->whereBetween(DB::raw('DATE(closing_date)'), [$startDate, $endDate])
                    ->sum('value');
            } else {
                $sql = DB::table('tbl_deals')
                    //                        ->select(DB::raw('SUM(value) as sales'))
                    ->where('uid', $uid)
                    ->where('sfun_id', 5)
                    ->whereBetween(DB::raw('DATE(closing_date)'), [$startDate, $endDate])
                    ->sum('value');
            }

            $labels[] = $date;
            $formleads[] = $sql;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        return json_encode($data);
    }

    public function getWeekSales(Request $request)
    {
        $uid = $request->input('uid');

        $label = 'Sales - Last 12 Weeks';
        $today = date('Y-m-d');
        $year = date('Y', strtotime($today));
        $week = date('W', strtotime($today));
        $k = $week - 11;
        for ($i = $week; $i >= $k; $i--) {
            $dto = new DateTime();
            $start = $dto->setISODate($year, $i, 1)->format('Y-m-d');
            $end = $dto->setISODate($year, $i, 7)->format('Y-m-d');

            $date = date('d M', strtotime($start));
            if ($uid == 'All') {
                $sql = DB::table('tbl_deals')
                    //                        ->select(DB::raw('SUM(value) as sales'))
                    ->where('sfun_id', 5)
                    ->whereBetween(DB::raw('DATE(closing_date)'), [$start, $end])
                    ->sum('value');
            } else {
                $sql = DB::table('tbl_deals')
                    //                        ->select(DB::raw('SUM(value) as sales'))
                    ->where('uid', $uid)
                    ->where('sfun_id', 5)
                    ->whereBetween(DB::raw('DATE(closing_date)'), [$start, $end])
                    ->sum('value');
            }

            $labels[] = $date;
            $formleads[] = $sql;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        return json_encode($data);
    }

    public function users()
    {
        $users = User::where('user_type', 1)->orderby('id', 'asc')->get(['id', 'name']);
        $useroptions = "<option value='All'>All</option>";
        $uid = "All";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";
        }
        $data['useroptions'] = $useroptions;
        $data['user'] = $uid;
        return view('admin.reports.users')->with("data", $data);
    }

    public function getDayUsers(Request $request)
    {
        //        $uid = $request->input('uid');
        $label = 'Customers - Last 30 Days';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 30; $i++) {
            $date = date('Y-m-d', strtotime('today - ' . $i . ' days'));
            $sql = DB::table('users')
                ->where('user_type', 1)
                ->where(DB::raw('DATE(created_at)'), $date)
                ->count('id');
            $labels[] = date('d M', strtotime($date));
            $formleads[] = $sql;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);

        return json_encode($data);
    }

    public function getMonthUsers(Request $request)
    {
        //        $uid = $request->input('uid');

        $label = 'Customers - Last 12 Months';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 12; $i++) {
            $query_date = date('Y-m-d', strtotime('today - ' . $i . ' months'));
            $monthStart = date('Y-m-01', strtotime($query_date));
            $monthEnd = date('Y-m-t', strtotime($query_date));
            $endDate = $monthEnd;
            $startDate = $monthStart;

            $date = date('M y', strtotime($startDate));

            $sql = DB::table('users')
                ->where('user_type', 1)
                ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                ->count('id');


            $labels[] = $date;
            $formleads[] = $sql;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        return json_encode($data);
    }

    public function getWeekUsers(Request $request)
    {
        //        $uid = $request->input('uid');

        $label = 'Customers - Last 12 Weeks';
        $today = date('Y-m-d');
        $year = date('Y', strtotime($today));
        $week = date('W', strtotime($today));
        $k = $week - 11;
        for ($i = $week; $i >= $k; $i--) {
            $dto = new DateTime();
            $start = $dto->setISODate($year, $i, 1)->format('Y-m-d');
            $end = $dto->setISODate($year, $i, 7)->format('Y-m-d');

            $date = date('d M', strtotime($start));
            $sql = DB::table('users')
                ->where('user_type', 1)
                ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
                ->count('id');

            $labels[] = $date;
            $formleads[] = $sql;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        return json_encode($data);
    }

    public function getDaySubUsers(Request $request)
    {
        $uid = $request->input('uid');
        $label = 'Customers - Last 30 Days';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 30; $i++) {
            $date = date('Y-m-d', strtotime('today - ' . $i . ' days'));
            if ($uid == 'All') {
                $sql = DB::table('users')
                    ->where('user_type', 0)
                    ->where(DB::raw('DATE(created_at)'), $date)
                    ->count('id');
            } else {
                $sql = DB::table('users')
                    ->where('user_type', 0)
                    ->where('user', $uid)
                    ->where(DB::raw('DATE(created_at)'), $date)
                    ->count('id');
            }


            $labels[] = date('d M', strtotime($date));
            $formleads[] = $sql;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);

        return json_encode($data);
    }

    public function getMonthSubUsers(Request $request)
    {
        $uid = $request->input('uid');

        $label = 'Customers - Last 12 Months';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 12; $i++) {
            $query_date = date('Y-m-d', strtotime('today - ' . $i . ' months'));
            $monthStart = date('Y-m-01', strtotime($query_date));
            $monthEnd = date('Y-m-t', strtotime($query_date));
            $endDate = $monthEnd;
            $startDate = $monthStart;

            $date = date('M y', strtotime($startDate));
            if ($uid == 'All') {
                $sql = DB::table('users')
                    ->where('user_type', 0)
                    ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                    ->count('id');
            } else {
                $sql = DB::table('users')
                    ->where('user_type', 0)
                    ->where('user', $uid)
                    ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                    ->count('id');
            }


            $labels[] = $date;
            $formleads[] = $sql;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        return json_encode($data);
    }

    public function getWeekSubUsers(Request $request)
    {
        $uid = $request->input('uid');

        $label = 'Customers - Last 12 Weeks';
        $today = date('Y-m-d');
        $year = date('Y', strtotime($today));
        $week = date('W', strtotime($today));
        $k = $week - 11;
        for ($i = $week; $i >= $k; $i--) {
            $dto = new DateTime();
            $start = $dto->setISODate($year, $i, 1)->format('Y-m-d');
            $end = $dto->setISODate($year, $i, 7)->format('Y-m-d');

            $date = date('d M', strtotime($start));
            if ($uid == 'All') {
                $sql = DB::table('users')
                    ->where('user_type', 0)
                    ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
                    ->count('id');
            } else {
                $sql = DB::table('users')
                    ->where('user_type', 0)
                    ->where('user', $uid)
                    ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
                    ->count('id');
            }


            $labels[] = $date;
            $formleads[] = $sql;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        return json_encode($data);
    }

    public function webtolead()
    {
        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        $useroptions = "<option value='All'>All Users</option>";
        $uid = "All";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";
        }
        $data['useroptions'] = $useroptions;
        $data['user'] = $uid;



        $forms = Tbl_forms::get(['form_id', 'title']);
        $formSelect = '<option value="All">All Forms</option>';
        $formid = "All";
        foreach ($forms as $form) {
            $formSelect .= '<option value="' . $form->form_id . '">' . $form->title . '</option>';
        }
        $data['formoptions'] = $formSelect;
        $data['form'] = $formid;
        return view('admin.reports.webtolead')->with("data", $data);
    }

    public function getDayFormleads(Request $request)
    { //$time, $form_id
        $form_id = $request->input('form_id');
        $uid = $request->input('uid');

        $label = 'Form Leads - Last 30 Days';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 30; $i++) {
            $date = date('Y-m-d', strtotime('today - ' . $i . ' days'));

            if (($form_id == 'All') && ($uid == 'All')) {
                $sql = DB::table('tbl_formleads')
                    ->where(DB::raw('DATE(created_at)'), $date)
                    ->count('fl_id');
            } else if (($form_id > 0) && ($uid == 'All')) {
                $sql = DB::table('tbl_formleads')
                    ->where('form_id', $form_id)
                    ->where(DB::raw('DATE(created_at)'), $date)
                    ->count('fl_id');
            } else if (($form_id == 'All') && ($uid > 0)) {
                $sql = DB::table('tbl_formleads')
                    ->where('uid', $uid)
                    ->where(DB::raw('DATE(created_at)'), $date)
                    ->count('fl_id');
            } else {
                $sql = DB::table('tbl_formleads')
                    ->where('form_id', $form_id)
                    ->where('uid', $uid)
                    ->where(DB::raw('DATE(created_at)'), $date)
                    ->count('fl_id');
            }

            $labels[] = date('d M', strtotime($date));
            $formleads[] = $sql;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);

        //        echo json_encode($data);

        return json_encode($data);
    }

    public function getMonthFormleads(Request $request)
    {   //$time, $form_id
        //        echo json_encode($request->input());
        //        exit(0);
        $form_id = $request->input('form_id');
        $uid = $request->input('uid');

        $label = 'Leads - Last 12 Months';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 12; $i++) {

            //            $query_date = date('Y-m-d');
            $query_date = date('Y-m-d', strtotime('today - ' . $i . ' months'));
            $monthStart = date('Y-m-01', strtotime($query_date));
            $monthEnd = date('Y-m-t', strtotime($query_date));
            $endDate = $monthEnd;
            $startDate = $monthStart;

            $date = date('M y', strtotime($startDate));

            if (($form_id == 'All') && ($uid == 'All')) {
                $sql = DB::table('tbl_formleads')
                    ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                    ->count('fl_id');
            } else if (($form_id > 0) && ($uid == 'All')) {
                $sql = DB::table('tbl_formleads')
                    ->where('form_id', $form_id)
                    ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                    ->count('fl_id');
            } else if (($form_id == 'All') && ($uid > 0)) {
                $sql = DB::table('tbl_formleads')
                    ->where('uid', $uid)
                    ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                    ->count('fl_id');
            } else {
                $sql = DB::table('tbl_formleads')
                    ->where('form_id', $form_id)
                    ->where('uid', $uid)
                    ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                    ->count('fl_id');
            }

            $labels[] = $date;
            $formleads[] = $sql;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        return json_encode($data);
    }

    public function getWeekFormleads(Request $request)
    {

        //        echo json_encode($request->input());
        //        exit(0);

        $form_id = $request->input('form_id');
        $uid = $request->input('uid');

        $label = 'Leads - Last 12 Weeks';
        $today = date('Y-m-d');
        $year = date('Y', strtotime($today));
        $week = date('W', strtotime($today));
        $k = $week - 11;
        for ($i = $week; $i >= $k; $i--) {
            $dto = new DateTime();
            $start = $dto->setISODate($year, $i, 1)->format('Y-m-d');
            $end = $dto->setISODate($year, $i, 7)->format('Y-m-d');
            $date = date('d M', strtotime($start));


            if (($form_id == 'All') && ($uid == 'All')) {
                $sql = DB::table('tbl_formleads')
                    ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
                    ->count('fl_id');
            } else if (($form_id > 0) && ($uid == 'All')) {
                $sql = DB::table('tbl_formleads')
                    ->where('form_id', $form_id)
                    ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
                    ->count('fl_id');
            } else if (($form_id == 'All') && ($uid > 0)) {
                $sql = DB::table('tbl_formleads')
                    ->where('uid', $uid)
                    ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
                    ->count('fl_id');
            } else {
                $sql = DB::table('tbl_formleads')
                    ->where('form_id', $form_id)
                    ->where('uid', $uid)
                    ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
                    ->count('fl_id');
            }

            $labels[] = $date;
            $formleads[] = $sql;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        //        echo json_encode($data);
        return json_encode($data);
    }

    public function getUserFormOptions(Request $request)
    {
        $uid = $request->input('uid');
        $forms = Tbl_forms::where('uid', $uid)->get(['form_id', 'title']);
        $formSelect = '<option value="All">All Forms</option>';
        $formid = "All";
        foreach ($forms as $form) {
            $formSelect .= '<option value="' . $form->form_id . '">' . $form->title . '</option>';
        }
        $data['formoptions'] = $formSelect;
        $data['form'] = $formid;
        return json_encode($data);
    }


    public function productleads()
    {

        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        $uid = 'All';
        $useroptions = "<option value='All' selected>All</option>";

        $admins = Admin::orderby('id', 'asc')->get(['id', 'name']);
        foreach ($admins as $admin) {
            $useroptions .= "<option value=" . $admin->id . '-1'  . ">" . $admin->name . "</option>";   // " . $selected . "
        }

        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . '-2'  . ">" . $userdetails->name . "</option>";   // " . $selected . "
        }
        $data['useroptions'] = $useroptions;
        $data['user'] = $uid;

        // $uid = 'All';
        // $user_type = 0;

        $data['table'] = '';
        return view('admin.reports.proleads')->with("data", $data);
    }

    public function getDayProductLeads(Request $request)
    {
        // $uid = Auth::user()->id;
        $uid = $request->input('uid');

        if ($uid == 'All') {
            $uid = 'All';
            $user_type = 0;
        } else {
            $user = explode('-', $uid);
            $uid = $user[0];
            $user_type = $user[1];
            // echo json_encode($request->input());
            // exit();
        }

        $label = 'Product Leads - Last 30 Days';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 30; $i++) {
            $date = date('Y-m-d', strtotime('today - ' . $i . ' days'));

            $query = DB::table('tbl_leads');
            $query->where('tbl_leads.active', 1);
            $query->where('tbl_leads.leadtype', 2);
            $query->where(DB::raw('DATE(tbl_leads.created_at)'), $date);
            $query->leftJoin('tbl_products', 'tbl_leads.pro_id', '=', 'tbl_products.pro_id');
            if (($uid > 0)  && ($uid != 'All')) {
                $query->where('tbl_products.uid', $uid);
            }
            if (($uid > 0)  && ($uid != 'All')) {
                $query->where('tbl_products.user_type', $user_type);
            }
            $query->select(
                DB::raw('count(ld_id) as leads')
            );
            $sql = $query->first();

            $labels[] = date('d M', strtotime($date));
            $formleads[] = $sql->leads;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = array_sum($formleads);
        return json_encode($data);
    }

    public function getMonthProductLeads(Request $request)
    {
        // $uid = Auth::user()->id;

        $uid = $request->input('uid');

        if ($uid == 'All') {
            $uid = 'All';
            $user_type = 0;
        } else {
            $user = explode('-', $uid);
            $uid = $user[0];
            $user_type = $user[1];
            // echo json_encode($request->input());
            // exit();
        }

        $label = 'Product Leads - Last 12 Months';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 12; $i++) {

            $query_date = date('Y-m-d', strtotime('today - ' . $i . ' months'));
            $monthStart = date('Y-m-01', strtotime($query_date));
            $monthEnd = date('Y-m-t', strtotime($query_date));
            $endDate = $monthEnd;
            $startDate = $monthStart;

            $date = date('M y', strtotime($startDate));

            $query = DB::table('tbl_leads');
            $query->where('tbl_leads.active', 1);
            $query->where('tbl_leads.leadtype', 2);
            $query->whereBetween(DB::raw('DATE(tbl_leads.created_at)'), [$startDate, $endDate]);
            $query->leftJoin('tbl_products', 'tbl_leads.pro_id', '=', 'tbl_products.pro_id');
            if (($uid > 0)  && ($uid != 'All')) {
                $query->where('tbl_products.uid', $uid);
            }
            if (($uid > 0)  && ($uid != 'All')) {
                $query->where('tbl_products.user_type', $user_type);
            }
            $query->select(
                DB::raw('count(ld_id) as leads')
            );
            $sql = $query->first();

            $labels[] = $date;
            $formleads[] = $sql->leads;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = array_sum($formleads);
        //        echo json_encode($data);
        return json_encode($data);
    }

    public function getWeekProductLeads(Request $request)
    {
        // $uid = Auth::user()->id;

        $uid = $request->input('uid');

        if ($uid == 'All') {
            $uid = 'All';
            $user_type = 0;
        } else {
            $user = explode('-', $uid);
            $uid = $user[0];
            $user_type = $user[1];
            // echo json_encode($request->input());
            // exit();
        }

        $label = 'Product Leads - Last 12 Weeks';
        $today = date('Y-m-d');
        $year = date('Y', strtotime($today));
        $week = date('W', strtotime($today));
        $k = $week - 11;
        for ($i = $week; $i >= $k; $i--) {
            $dto = new DateTime();
            $start = $dto->setISODate($year, $i, 1)->format('Y-m-d');
            $end = $dto->setISODate($year, $i, 7)->format('Y-m-d');
            $date = date('d M', strtotime($start));

            $query = DB::table('tbl_leads');
            $query->where('tbl_leads.active', 1);
            $query->where('tbl_leads.leadtype', 2);
            $query->whereBetween(DB::raw('DATE(tbl_leads.created_at)'), [$start, $end]);
            $query->leftJoin('tbl_products', 'tbl_leads.pro_id', '=', 'tbl_products.pro_id');
            if (($uid > 0)  && ($uid != 'All')) {
                $query->where('tbl_products.uid', $uid);
            }
            if (($uid > 0)  && ($uid != 'All')) {
                $query->where('tbl_products.user_type', $user_type);
            }
            $query->select(
                DB::raw('count(ld_id) as leads')
            );
            $sql = $query->first();

            $labels[] = $date;
            $formleads[] = $sql->leads;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = array_sum($formleads);
        //        echo json_encode($data);
        return json_encode($data);
    }


    public function products()
    {

        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        $uid = 'All';
        $useroptions = "<option value='All' selected>All</option>";

        $admins = Admin::orderby('id', 'asc')->get(['id', 'name']);
        foreach ($admins as $admin) {
            $useroptions .= "<option value=" . $admin->id . '-1'  . ">" . $admin->name . "</option>";   // " . $selected . "
        }

        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . '-2'  . ">" . $userdetails->name . "</option>";   // " . $selected . "
        }
        $data['useroptions'] = $useroptions;
        $data['user'] = $uid;

        // $uid = 'All';
        // $user_type = 0;

        $data['table'] = '';
        return view('admin.reports.products')->with("data", $data);
    }

    public function getDayProducts(Request $request)
    {
        // $uid = Auth::user()->id;
        $uid = $request->input('uid');

        if ($uid == 'All') {
            $uid = 'All';
            $user_type = 0;
        } else {
            $user = explode('-', $uid);
            $uid = $user[0];
            $user_type = $user[1];
            // echo json_encode($request->input());
            // exit();
        }

        $label = 'Products - Last 30 Days';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 30; $i++) {
            $date = date('Y-m-d', strtotime('today - ' . $i . ' days'));

            $query = DB::table('tbl_products');
            $query->where('tbl_products.active', 1);
            $query->where(DB::raw('DATE(tbl_products.created_at)'), $date);
            if (($uid > 0) && ($uid != 'All')) {
                $query->where('tbl_products.uid', $uid);
            }
            if (($uid > 0)  && ($uid != 'All')) {
                $query->where('tbl_products.user_type', $user_type);
            }
            $query->select(
                DB::raw('count(pro_id) as leads')
            );
            $sql = $query->first();

            $labels[] = date('d M', strtotime($date));
            $formleads[] = $sql->leads;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = array_sum($formleads);
        return json_encode($data);
    }

    public function getMonthProducts(Request $request)
    {
        // $uid = Auth::user()->id;

        $uid = $request->input('uid');

        if ($uid == 'All') {
            $uid = 'All';
            $user_type = 0;
        } else {
            $user = explode('-', $uid);
            $uid = $user[0];
            $user_type = $user[1];
            // echo json_encode($request->input());
            // exit();
        }

        $label = 'Product Leads - Last 12 Months';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 12; $i++) {

            $query_date = date('Y-m-d', strtotime('today - ' . $i . ' months'));
            $monthStart = date('Y-m-01', strtotime($query_date));
            $monthEnd = date('Y-m-t', strtotime($query_date));
            $endDate = $monthEnd;
            $startDate = $monthStart;

            $date = date('M y', strtotime($startDate));

            $query = DB::table('tbl_products');
            $query->where('tbl_products.active', 1);
            $query->whereBetween(DB::raw('DATE(tbl_products.created_at)'), [$startDate, $endDate]);
            if (($uid > 0)  && ($uid != 'All')) {
                $query->where('tbl_products.uid', $uid);
            }
            if (($uid > 0)  && ($uid != 'All')) {
                $query->where('tbl_products.user_type', $user_type);
            }
            $query->select(
                DB::raw('count(pro_id) as leads')
            );
            $sql = $query->first();

            $labels[] = $date;
            $formleads[] = $sql->leads;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = array_sum($formleads);
        //        echo json_encode($data);
        return json_encode($data);
    }

    public function getWeekProducts(Request $request)
    {
        // $uid = Auth::user()->id;

        $uid = $request->input('uid');

        if ($uid == 'All') {
            $uid = 'All';
            $user_type = 0;
        } else {
            $user = explode('-', $uid);
            $uid = $user[0];
            $user_type = $user[1];
            // echo json_encode($request->input());
            // exit();
        }

        $label = 'Product Leads - Last 12 Weeks';
        $today = date('Y-m-d');
        $year = date('Y', strtotime($today));
        $week = date('W', strtotime($today));
        $k = $week - 11;
        for ($i = $week; $i >= $k; $i--) {
            $dto = new DateTime();
            $start = $dto->setISODate($year, $i, 1)->format('Y-m-d');
            $end = $dto->setISODate($year, $i, 7)->format('Y-m-d');
            $date = date('d M', strtotime($start));

            $query = DB::table('tbl_products');
            $query->where('tbl_products.active', 1);
            $query->whereBetween(DB::raw('DATE(tbl_products.created_at)'), [$start, $end]);
            if (($uid > 0)  && ($uid != 'All')) {
                $query->where('tbl_products.uid', $uid);
            }
            if (($uid > 0)  && ($uid != 'All')) {
                $query->where('tbl_products.user_type', $user_type);
            }
            $query->select(
                DB::raw('count(pro_id) as leads')
            );
            $sql = $query->first();

            $labels[] = $date;
            $formleads[] = $sql->leads;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = array_sum($formleads);
        //        echo json_encode($data);
        return json_encode($data);
    }

    public function companies()
    {

        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        $uid = 'All';
        $useroptions = "<option value='All' selected>All</option>";

        $admins = Admin::orderby('id', 'asc')->get(['id', 'name']);
        foreach ($admins as $admin) {
            $useroptions .= "<option value=" . $admin->id . '-1'  . ">" . $admin->name . "</option>";   // " . $selected . "
        }

        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . '-2'  . ">" . $userdetails->name . "</option>";   // " . $selected . "
        }
        $data['useroptions'] = $useroptions;
        $data['user'] = $uid;

        // $uid = 'All';
        // $user_type = 0;

        $data['table'] = '';
        return view('admin.reports.companies')->with("data", $data);
    }

    public function getDayCompanies(Request $request)
    {
        // $uid = Auth::user()->id;
        $uid = $request->input('uid');

        if ($uid == 'All') {
            $uid = 'All';
            $user_type = 0;
        } else {
            $user = explode('-', $uid);
            $uid = $user[0];
            $user_type = $user[1];
            // echo json_encode($request->input());
            // exit();
        }

        $label = 'Companies - Last 30 Days';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 30; $i++) {
            $date = date('Y-m-d', strtotime('today - ' . $i . ' days'));

            $query = DB::table('companies');
            $query->where('companies.isActive', 1);
            $query->where(DB::raw('DATE(companies.created_at)'), $date);
            if (($uid > 0) && ($uid != 'All')) {
                $query->where('companies.user_id', $uid);
            }
            // if (($uid > 0)  && ($uid != 'All')) {
            //     $query->where('companies.user_type', $user_type);
            // }
            $query->select(
                DB::raw('count(id) as leads')
            );
            $sql = $query->first();

            $labels[] = date('d M', strtotime($date));
            $formleads[] = $sql->leads;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = array_sum($formleads);
        return json_encode($data);
    }

    public function getMonthCompanies(Request $request)
    {
        // $uid = Auth::user()->id;

        $uid = $request->input('uid');

        if ($uid == 'All') {
            $uid = 'All';
            $user_type = 0;
        } else {
            $user = explode('-', $uid);
            $uid = $user[0];
            $user_type = $user[1];
            // echo json_encode($request->input());
            // exit();
        }

        $label = 'Companies - Last 12 Months';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 12; $i++) {

            $query_date = date('Y-m-d', strtotime('today - ' . $i . ' months'));
            $monthStart = date('Y-m-01', strtotime($query_date));
            $monthEnd = date('Y-m-t', strtotime($query_date));
            $endDate = $monthEnd;
            $startDate = $monthStart;

            $date = date('M y', strtotime($startDate));

            $query = DB::table('companies');
            $query->where('companies.isActive', 1);
            $query->whereBetween(DB::raw('DATE(companies.created_at)'), [$startDate, $endDate]);
            if (($uid > 0)  && ($uid != 'All')) {
                $query->where('companies.uid', $uid);
            }
            // if (($uid > 0)  && ($uid != 'All')) {
            //     $query->where('tbl_products.user_type', $user_type);
            // }
            $query->select(
                DB::raw('count(id) as leads')
            );
            $sql = $query->first();

            $labels[] = $date;
            $formleads[] = $sql->leads;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = array_sum($formleads);
        //        echo json_encode($data);
        return json_encode($data);
    }

    public function getWeekCompanies(Request $request)
    {
        // $uid = Auth::user()->id;

        $uid = $request->input('uid');

        if ($uid == 'All') {
            $uid = 'All';
            $user_type = 0;
        } else {
            $user = explode('-', $uid);
            $uid = $user[0];
            $user_type = $user[1];
            // echo json_encode($request->input());
            // exit();
        }

        $label = 'Companies - Last 12 Weeks';
        $today = date('Y-m-d');
        $year = date('Y', strtotime($today));
        $week = date('W', strtotime($today));
        $k = $week - 11;
        for ($i = $week; $i >= $k; $i--) {
            $dto = new DateTime();
            $start = $dto->setISODate($year, $i, 1)->format('Y-m-d');
            $end = $dto->setISODate($year, $i, 7)->format('Y-m-d');
            $date = date('d M', strtotime($start));

            $query = DB::table('companies');
            $query->where('companies.isActive', 1);
            $query->whereBetween(DB::raw('DATE(companies.created_at)'), [$start, $end]);
            if (($uid > 0)  && ($uid != 'All')) {
                $query->where('companies.uid', $uid);
            }
            // if (($uid > 0)  && ($uid != 'All')) {
            //     $query->where('tbl_products.user_type', $user_type);
            // }
            $query->select(
                DB::raw('count(id) as leads')
            );
            $sql = $query->first();

            $labels[] = $date;
            $formleads[] = $sql->leads;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = array_sum($formleads);
        //        echo json_encode($data);
        return json_encode($data);
    }
}

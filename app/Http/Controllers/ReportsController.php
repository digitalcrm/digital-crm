<?php

namespace App\Http\Controllers;

use App\User;
use DateTime;
use App\currency;
use App\Tbl_deals;
use App\Tbl_forms;
use App\Tbl_leads;
use App\Tbl_Accounts;
use App\Tbl_contacts;
use App\Tbl_formleads;
use App\Tbl_salesfunnel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReportsController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('test:reports', ['only' => ['index', 'show', 'create', 'store', 'edit', 'update', 'delete', 'deleteAll', 'webtolead', 'leads', 'accounts', 'contacts', 'deals', 'customers', 'sales']]);
    }

    public function webtolead()
    {
        $uid = Auth::user()->id;
        //        $data['table'] = $this->Daywebtolead($uid);

        $forms = Tbl_forms::where('uid', $uid)->get(['form_id', 'title']);
        $formSelect = '<option value="0">Select Form</option>';
        $i = 0;
        $default_form = 0;
        foreach ($forms as $form) {
            if ($i == 0) {
                $default_form = $form->form_id;
                $formSelect .= '<option value="' . $form->form_id . '" selected>' . $form->title . '</option>';
                $i++;
            } else {
                $formSelect .= '<option value="' . $form->form_id . '">' . $form->title . '</option>';
            }
        }
        $data['default_form'] = $default_form;
        $data['formSelect'] = $formSelect;
        return view('auth.reports.webtolead')->with("data", $data);
    }

    public function getDayFormleads(Request $request)
    { //$time, $form_id
        //        return 'getDayFormleads';
        $time = $request->input('time');
        $form_id = $request->input('form_id');
        $uid = Auth::user()->id;
        $data['time'] = $time;
        $data['form_id'] = $form_id;

        $label = 'Form Leads - Last 30 Days';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 30; $i++) {
            $date = date('Y-m-d', strtotime('today - ' . $i . ' days'));
            $sql = DB::table('tbl_formleads')
                ->select(DB::raw('count(fl_id) as leads'))
                ->where('form_id', $form_id)
                ->where('uid', $uid)
                ->where('active', 1)
                ->where(DB::raw('DATE(created_at)'), $date)
                ->first();
            $labels[] = date('d M', strtotime($date));
            $formleads[] = $sql->leads;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = array_sum($formleads);

        //        echo json_encode($data);
        return json_encode($data);
    }

    public function getMonthFormleads(Request $request)
    {   //$time, $form_id
        $time = $request->input('time');
        $form_id = $request->input('form_id');

        $uid = Auth::user()->id;
        $data['time'] = $time;
        $data['form_id'] = $form_id;

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
            $sql = DB::table('tbl_formleads')
                ->select(DB::raw('count(fl_id) as leads'))
                ->where('form_id', $form_id)
                ->where('active', 1)
                ->where('uid', $uid)
                ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                ->first();
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

    public function getWeekFormleads(Request $request)
    {

        $time = $request->input('time');
        $form_id = $request->input('form_id');

        $uid = Auth::user()->id;
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
            $sql = DB::table('tbl_formleads')
                ->select(DB::raw('count(fl_id) as leads'))
                ->where('form_id', $form_id)
                ->where('active', 1)
                ->where('uid', $uid)
                ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
                ->first();
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

    public function getYearFormleads(Request $request)
    { //$time, $form_id
        //        return 'getDayFormleads';
        $time = $request->input('time');
        $form_id = $request->input('form_id');

        $uid = Auth::user()->id;
        $data['time'] = $time;
        $data['form_id'] = $form_id;

        $label = 'Form Leads - Last 12 Years';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i < 12; $i++) {
            $year = date("Y", strtotime("-" . $i . " year"));
            $sql = DB::table('tbl_formleads')
                ->select(DB::raw('count(fl_id) as leads'))
                ->where('form_id', $form_id)
                ->where('uid', $uid)
                ->where('active', 1)
                ->where(DB::raw('YEAR(created_at)'), $year)
                ->first();
            $labels[] = $year;
            $formleads[] = $sql->leads;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = array_sum($formleads);
        //        echo json_encode($data);
        return json_encode($data);
    }

    public function leads()
    {
        //        $uid = Auth::user()->id;
        $data['table'] = '';
        return view('auth.reports.leads')->with("data", $data);
    }

    public function getDayLeads()
    {
        $uid = Auth::user()->id;
        $label = 'Leads - Last 30 Days';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 30; $i++) {
            $date = date('Y-m-d', strtotime('today - ' . $i . ' days'));
            $sql = DB::table('tbl_leads')
                ->select(DB::raw('count(ld_id) as leads'))
                ->where('uid', $uid)
                ->where(DB::raw('DATE(created_at)'), $date)
                ->where('active', 1)
                ->first();
            $labels[] = date('d M', strtotime($date));
            $formleads[] = $sql->leads;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = array_sum($formleads);
        return json_encode($data);
    }

    public function getMonthLeads()
    {
        $uid = Auth::user()->id;

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
            $sql = DB::table('tbl_leads')
                ->select(DB::raw('count(ld_id) as leads'))
                ->where('uid', $uid)
                ->where('active', 1)
                ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                ->first();
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

    public function getWeekLeads()
    {
        $uid = Auth::user()->id;

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
            $sql = DB::table('tbl_leads')
                ->select(DB::raw('count(ld_id) as leads'))
                ->where('uid', $uid)
                ->where('active', 1)
                ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
                ->first();
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

    public function getYearLeads()
    {
        $uid = Auth::user()->id;

        $label = 'Leads - Last 12 Years';
        for ($i = 0; $i < 12; $i++) {
            $year = date("Y", strtotime("-" . $i . " year"));
            $sql = Tbl_leads::where('uid', $uid)->where('active', 1)->where(DB::raw('YEAR(created_at)'), $year)->count();
            $labels[] = $year;
            $formleads[] = $sql;
        }

        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = array_sum($formleads);
        return json_encode($data);
    }

    public function accounts()
    {
        $uid = Auth::user()->id;
        $data['table'] = '';
        return view('auth.reports.accounts')->with("data", $data);
    }

    public function getDayAccounts()
    {
        $uid = Auth::user()->id;
        $label = 'Accounts - Last 30 Days';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 30; $i++) {
            $date = date('Y-m-d', strtotime('today - ' . $i . ' days'));
            $sql = DB::table('tbl_accounts')
                ->select(DB::raw('count(acc_id) as accounts'))
                ->where('uid', $uid)
                ->where('active', 1)
                ->where(DB::raw('DATE(created_at)'), $date)
                ->first();
            $labels[] = date('d M', strtotime($date));
            $formleads[] = $sql->accounts;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = array_sum($formleads);
        return json_encode($data);
    }

    public function getMonthAccounts()
    {
        $uid = Auth::user()->id;

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
            $sql = DB::table('tbl_accounts')
                ->select(DB::raw('count(acc_id) as accounts'))
                ->where('uid', $uid)
                ->where('active', 1)
                ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                ->first();
            $labels[] = $date;
            $formleads[] = $sql->accounts;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = array_sum($formleads);
        //        echo json_encode($data);
        return json_encode($data);
    }

    public function getWeekAccounts()
    {
        $uid = Auth::user()->id;

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
            $sql = DB::table('tbl_accounts')
                ->select(DB::raw('count(acc_id) as accounts'))
                ->where('uid', $uid)
                ->where('active', 1)
                ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
                ->first();
            $labels[] = $date;
            $formleads[] = $sql->accounts;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = array_sum($formleads);
        //        echo json_encode($data);
        return json_encode($data);
    }

    public function getYearAccounts()
    {
        $uid = Auth::user()->id;

        $label = 'Accounts - Last 12 Years';
        for ($i = 0; $i < 12; $i++) {
            $year = date("Y", strtotime("-" . $i . " year"));
            $sql = Tbl_Accounts::where('uid', $uid)->where('active', 1)->where(DB::raw('YEAR(created_at)'), $year)->count();
            $labels[] = $year;
            $formleads[] = $sql;
        }

        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = array_sum($formleads);
        return json_encode($data);
    }

    public function contacts()
    {
        $uid = Auth::user()->id;
        $data['table'] = '';
        return view('auth.reports.contacts')->with("data", $data);
    }

    public function getDayContacts()
    {
        $uid = Auth::user()->id;
        $label = 'Contacts - Last 30 Days';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 30; $i++) {
            $date = date('Y-m-d', strtotime('today - ' . $i . ' days'));
            $sql = DB::table('tbl_contacts')
                ->select(DB::raw('count(cnt_id) as contacts'))
                ->where('uid', $uid)
                ->where(DB::raw('DATE(created_at)'), $date)
                ->first();
            $labels[] = date('d M', strtotime($date));
            $formleads[] = $sql->contacts;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = array_sum($formleads);
        return json_encode($data);
    }

    public function getMonthContacts()
    {
        $uid = Auth::user()->id;

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
            $sql = DB::table('tbl_contacts')
                ->select(DB::raw('count(cnt_id) as contacts'))
                ->where('uid', $uid)
                ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                ->first();
            $labels[] = $date;
            $formleads[] = $sql->contacts;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = array_sum($formleads);
        //        echo json_encode($data);
        return json_encode($data);
    }

    public function getWeekContacts()
    {
        $uid = Auth::user()->id;

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
            $sql = DB::table('tbl_contacts')
                ->select(DB::raw('count(cnt_id) as contacts'))
                ->where('uid', $uid)
                ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
                ->first();
            $labels[] = $date;
            $formleads[] = $sql->contacts;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = array_sum($formleads);
        //        echo json_encode($data);
        return json_encode($data);
    }

    public function getYearContacts()
    {
        $uid = Auth::user()->id;

        $label = 'Contacts - Last 12 Years';
        for ($i = 0; $i < 12; $i++) {
            $year = date("Y", strtotime("-" . $i . " year"));
            $sql = Tbl_contacts::where('uid', $uid)->where('active', 1)->where(DB::raw('YEAR(created_at)'), $year)->count();
            $labels[] = $year;
            $formleads[] = $sql;
        }

        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = array_sum($formleads);
        return json_encode($data);
    }

    public function deals()
    {
        $uid = Auth::user()->id;
        //        $data['table'] = $this->Daywebtolead($uid);

        $forms = Tbl_salesfunnel::all();
        $formSelect = '<option value="0">Select Form</option>';
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
        return view('auth.reports.deals')->with("data", $data);
    }

    public function getDayDeals(Request $request)
    {
        //        return json_encode($request->input());
        //        $data['time'] = $request->input('time');
        //        $data['form_id'] = $request->input('form_id');

        $uid = Auth::user()->id;
        $time = $request->input('time');
        $form_id = $request->input('form_id');
        //
        $label = 'Form Deals - Last 30 Days';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 30; $i++) {
            $date = date('Y-m-d', strtotime('today - ' . $i . ' days'));
            $sql = DB::table('tbl_deals')
                ->select(DB::raw('count(deal_id) as deals'))
                ->where('sfun_id', $form_id)
                ->where('uid', $uid)
                ->where('active', 1)
                ->where(DB::raw('DATE(created_at)'), $date)
                ->first();
            $labels[] = date('d M', strtotime($date));
            $formleads[] = $sql->deals;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = array_sum($formleads);
        //        echo json_encode($data);
        return json_encode($data);
    }

    public function getMonthDeals(Request $request)
    {
        $uid = Auth::user()->id;
        $time = $request->input('time');
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
            $sql = DB::table('tbl_deals')
                ->select(DB::raw('count(deal_id) as deals'))
                ->where('uid', $uid)
                ->where('sfun_id', $form_id)
                ->where('active', 1)
                ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                ->first();
            $labels[] = $date;
            $formleads[] = $sql->deals;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = array_sum($formleads);
        //        echo json_encode($data);
        return json_encode($data);
    }

    public function getWeekDeals(Request $request)
    {
        $uid = Auth::user()->id;
        $time = $request->input('time');
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
            //            echo "Week:" . $i . " Start date:" . $start . " End date:" . $end . "<br>";

            $date = date('d M', strtotime($start));
            $sql = DB::table('tbl_deals')
                ->select(DB::raw('count(deal_id) as deals'))
                ->where('uid', $uid)
                ->where('sfun_id', $form_id)
                ->where('active', 1)
                ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
                ->first();
            $labels[] = $date;
            $formleads[] = $sql->deals;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = array_sum($formleads);
        //        echo json_encode($data);
        return json_encode($data);
    }

    public function getYearDeals(Request $request)
    {
        $uid = Auth::user()->id;
        $form_id = $request->input('form_id');
        $time = $request->input('time');

        //        echo $time . ' ' . $form_id;

        $label = 'Deals - Last 12 Years';
        for ($i = 0; $i < 12; $i++) {
            $year = date("Y", strtotime("-" . $i . " year"));
            $sql = Tbl_deals::where('uid', $uid)->where('sfun_id', $form_id)->where('active', 1)->where(DB::raw('YEAR(created_at)'), $year)->count();
            $labels[] = $year;
            $formleads[] = $sql;
        }

        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = array_sum($formleads);
        return json_encode($data);
    }

    public function customers()
    {
        $uid = Auth::user()->id;
        $data['table'] = '';
        return view('auth.reports.customers')->with("data", $data);
    }

    public function getDayCustomers()
    {
        $uid = Auth::user()->id;
        $label = 'Customers - Last 30 Days';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 30; $i++) {
            $date = date('Y-m-d', strtotime('today - ' . $i . ' days'));
            $sql = DB::table('tbl_deals')
                ->select(DB::raw('count(DISTINCT(ld_id)) as customers'))
                ->where('uid', $uid)
                ->where('sfun_id', 5)
                ->where('active', 1)
                ->where(DB::raw('DATE(closing_date)'), $date)
                ->first();
            $labels[] = date('d M', strtotime($date));
            $formleads[] = $sql->customers;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = array_sum($formleads);
        return json_encode($data);
    }

    public function getMonthCustomers()
    {
        $uid = Auth::user()->id;

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
            $sql = DB::table('tbl_deals')
                ->select(DB::raw('count(DISTINCT(ld_id)) as customers'))
                ->where('uid', $uid)
                ->where('sfun_id', 5)
                ->where('active', 1)
                ->whereBetween(DB::raw('DATE(closing_date)'), [$startDate, $endDate])
                ->first();
            $labels[] = $date;
            $formleads[] = $sql->customers;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = array_sum($formleads);
        //        echo json_encode($data);
        return json_encode($data);
    }

    public function getWeekCustomers()
    {
        $uid = Auth::user()->id;

        $label = 'Customers - Last 12 Weeks';
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
            $sql = DB::table('tbl_deals')
                ->select(DB::raw('count(DISTINCT(ld_id)) as customers'))
                ->where('uid', $uid)
                ->where('sfun_id', 5)
                ->where('active', 1)
                ->whereBetween(DB::raw('DATE(closing_date)'), [$start, $end])
                ->first();
            $labels[] = $date;
            $formleads[] = $sql->customers;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = array_sum($formleads);
        //        echo json_encode($data);
        return json_encode($data);
    }

    public function getYearCustomers()
    {
        $uid = Auth::user()->id;

        $label = 'Customers - Last 12 Years';
        for ($i = 0; $i < 12; $i++) {
            $year = date("Y", strtotime("-" . $i . " year"));
            $sql = DB::table('tbl_deals')
                ->select(DB::raw('count(DISTINCT(ld_id)) as customers'))
                ->where('uid', $uid)
                ->where('sfun_id', 5)
                ->where('active', 1)
                ->where(DB::raw('YEAR(closing_date)'), $year)
                ->first();
            $labels[] = $year;
            $formleads[] = $sql->customers;
        }

        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = array_sum($formleads);
        return json_encode($data);
    }

    public function sales()
    {
        $uid = Auth::user()->id;
        $data['table'] = '';
        return view('auth.reports.sales')->with("data", $data);
    }

    public function getDaySales()
    {
        $uid = Auth::user()->id;
        $user = User::find($uid);
        $cr_id = $user->cr_id;
        $currency = currency::find($cr_id);

        $label = 'Sales - Last 30 Days';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 30; $i++) {
            $date = date('Y-m-d', strtotime('today - ' . $i . ' days'));
            $sql = DB::table('tbl_deals')
                ->select(DB::raw('SUM(value) as sales'))
                ->where('uid', $uid)
                ->where('deal_status', 1)
                ->where('active', 1)
                ->where(DB::raw('DATE(closing_date)'), $date)
                ->first();
            $labels[] = date('d M', strtotime($date));
            $formleads[] = $sql->sales;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = '<span>' . $currency->html_code . '</span>&nbsp;' . array_sum($formleads);
        return json_encode($data);
    }

    public function getMonthSales()
    {
        $uid = Auth::user()->id;
        $user = User::find($uid);
        $cr_id = $user->cr_id;
        $currency = currency::find($cr_id);

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
            $sql = DB::table('tbl_deals')
                ->select(DB::raw('SUM(value) as sales'))
                ->where('uid', $uid)
                ->where('deal_status', 1)
                ->where('active', 1)
                ->whereBetween(DB::raw('DATE(closing_date)'), [$startDate, $endDate])
                ->first();
            $labels[] = $date;
            $formleads[] = $sql->sales;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = '<span>' . $currency->html_code . '</span>&nbsp;' . array_sum($formleads);
        //        echo json_encode($data);
        return json_encode($data);
    }

    public function getWeekSales()
    {
        $uid = Auth::user()->id;
        $user = User::find($uid);
        $cr_id = $user->cr_id;
        $currency = currency::find($cr_id);

        $label = 'Sales - Last 12 Weeks';
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
            $sql = DB::table('tbl_deals')
                ->select(DB::raw('SUM(value) as sales'))
                ->where('uid', $uid)
                ->where('deal_status', 1)
                ->where('active', 1)
                ->whereBetween(DB::raw('DATE(closing_date)'), [$start, $end])
                ->first();
            $labels[] = $date;
            $formleads[] = $sql->sales;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = '<span>' . $currency->html_code . '</span>&nbsp;' . array_sum($formleads);
        //        echo json_encode($data);
        return json_encode($data);
    }

    public function getYearSales()
    {
        $uid = Auth::user()->id;
        $user = User::find($uid);
        $cr_id = $user->cr_id;
        $currency = currency::find($cr_id);

        $label = 'Sales - Last 12 Years';
        for ($i = 0; $i < 12; $i++) {
            $year = date("Y", strtotime("-" . $i . " year"));
            $sql = DB::table('tbl_deals')
                ->select(DB::raw('SUM(value) as sales'))
                ->where('uid', $uid)
                ->where('active', 1)
                ->where('deal_status', 1)
                ->where(DB::raw('YEAR(closing_date)'), $year)
                ->first();
            $labels[] = $year;
            $formleads[] = $sql->sales;
        }

        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = '<span>' . $currency->html_code . '</span>&nbsp;' . array_sum($formleads);
        return json_encode($data);
    }



    public function productleads()
    {
        // echo 'Product Leads';
        // exit();
        //        $uid = Auth::user()->id;
        $data['table'] = '';
        return view('auth.reports.proleads')->with("data", $data);
    }

    public function getDayProductLeads()
    {
        $uid = Auth::user()->id;
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
            $query->where('tbl_products.uid', $uid);
            $query->where('tbl_products.user_type', 2);
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

    public function getMonthProductLeads()
    {
        $uid = Auth::user()->id;

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
            // $sql = DB::table('tbl_leads')
            //     ->select(DB::raw('count(ld_id) as leads'))
            //     ->where('uid', $uid)
            //     ->where('active', 1)
            //     ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            //     ->first();

            $query = DB::table('tbl_leads');
            $query->where('tbl_leads.active', 1);
            $query->where('tbl_leads.leadtype', 2);
            $query->whereBetween(DB::raw('DATE(tbl_leads.created_at)'), [$startDate, $endDate]);
            $query->leftJoin('tbl_products', 'tbl_leads.pro_id', '=', 'tbl_products.pro_id');
            $query->where('tbl_products.uid', $uid);
            $query->where('tbl_products.user_type', 2);
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

    public function getWeekProductLeads()
    {
        $uid = Auth::user()->id;

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

            // $sql = DB::table('tbl_leads')
            //     ->select(DB::raw('count(ld_id) as leads'))
            //     ->where('uid', $uid)
            //     ->where('active', 1)
            //     ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
            //     ->first();


            $query = DB::table('tbl_leads');
            $query->where('tbl_leads.active', 1);
            $query->where('tbl_leads.leadtype', 2);
            $query->whereBetween(DB::raw('DATE(tbl_leads.created_at)'), [$start, $end]);
            $query->leftJoin('tbl_products', 'tbl_leads.pro_id', '=', 'tbl_products.pro_id');
            $query->where('tbl_products.uid', $uid);
            $query->where('tbl_products.user_type', 2);
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

    public function getYearProductLeads()
    {
        $uid = Auth::user()->id;

        $label = 'Leads - Last 12 Years';
        for ($i = 0; $i < 12; $i++) {
            $year = date("Y", strtotime("-" . $i . " year"));
            $sql = Tbl_leads::where('uid', $uid)->where('leadtype', 2)->where('active', 1)->where(DB::raw('YEAR(created_at)'), $year)->count();
            $labels[] = $year;
            $formleads[] = $sql;
        }

        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formleads'] = array_reverse($formleads);
        $data['total'] = array_sum($formleads);
        return json_encode($data);
    }

    public function products()
    {
        // echo 'Product Leads';
        // exit();
        //        $uid = Auth::user()->id;
        $data['table'] = '';
        return view('auth.reports.products')->with("data", $data);
    }

    public function getDayProducts()
    {
        $uid = Auth::user()->id;
        $label = 'Products - Last 30 Days';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 30; $i++) {
            $date = date('Y-m-d', strtotime('today - ' . $i . ' days'));

            $query = DB::table('tbl_products');
            $query->where('tbl_products.active', 1);
            $query->where(DB::raw('DATE(tbl_products.created_at)'), $date);
            $query->where('tbl_products.uid', $uid);
            $query->where('tbl_products.user_type', 2);
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

    public function getMonthProducts()
    {
        // $uid = Auth::user()->id;

        $uid = Auth::user()->id;


        $label = 'Product - Last 12 Months';
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
            $query->where('tbl_products.uid', $uid);
            $query->where('tbl_products.user_type', 2);
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

    public function getWeekProducts()
    {
        $uid = Auth::user()->id;

        $label = 'Product - Last 12 Weeks';
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
            $query->where('tbl_products.uid', $uid);
            $query->where('tbl_products.user_type', 2);
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
        $data['table'] = '';
        return view('auth.reports.companies')->with("data", $data);
    }

    public function getDayCompanies()
    {
        $uid = Auth::user()->id;

        $label = 'Companies - Last 30 Days';
        $labels = array();
        $formleads = array();
        for ($i = 0; $i <= 30; $i++) {
            $date = date('Y-m-d', strtotime('today - ' . $i . ' days'));

            $query = DB::table('companies');
            $query->where('companies.isActive', 1);
            $query->where(DB::raw('DATE(companies.created_at)'), $date);
            $query->where('companies.user_id', $uid);

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

    public function getMonthCompanies()
    {
        $uid = Auth::user()->id;


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
            $query->where('companies.user_id', $uid);
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

    public function getWeekCompanies()
    {
        $uid = Auth::user()->id;

        // $uid = $request->input('uid');

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
            $query->where('companies.user_id', $uid);
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

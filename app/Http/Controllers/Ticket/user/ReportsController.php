<?php

namespace App\Http\Controllers\Ticket\user;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Ticket_status;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Null_;

class ReportsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    public function tickets()
    {
        // $data['table'] = '';
        $forms = Ticket_status::all();
        $formSelect = '<option value="0">Select Field</option>';
        $i = 0;
        $default_form = 0;
        foreach ($forms as $form) {
            if ($i == 0) {
                $default_form = $form->id;
                $formSelect .= '<option value="' . $form->id . '" selected>' . $form->name . '</option>';
                $i++;
            } else {
                $formSelect .= '<option value="' . $form->id . '">' . $form->name . '</option>';
            }
        }
        $data['default_form'] = $default_form;
        $data['formSelect'] = $formSelect;
        return view('auth.reports.report_tickets')->with("data", $data);
    }

    public function getDaytickets(Request $request)
    {
        $uid = Auth::user()->id;
        $time = $request->input('time');
        $form_id = $request->input('form_id');
        $label = 'Tickets - Last 30 Days';
        $labels = array();
        $formtickets = array();
        for ($i = 0; $i <= 30; $i++) {
            $date = date('Y-m-d', strtotime('today - ' . $i . ' days'));
            $sql = DB::table('tickets')
                    ->select(DB::raw('count(id) as ticketid'))
                    ->where('status_id', $form_id)
                    ->where('user_id', $uid)
                    ->where(DB::raw('DATE(created_at)'), $date)
                    ->where('deleted_at', Null)
                    ->first();
            $labels[] = date('d M', strtotime($date));
            $formtickets[] = $sql->ticketid;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formtickets'] = array_reverse($formtickets);
        $data['total'] = array_sum($formtickets);
        return json_encode($data);
    }

    public function getMonthtickets(Request $request)
    {
        $uid = Auth::user()->id;
        $time = $request->input('time');
        $form_id = $request->input('form_id');

        $label = 'tickets - Last 12 Months';
        $labels = array();
        $formtickets = array();
        for ($i = 0; $i <= 12; $i++) {

            $query_date = date('Y-m-d', strtotime('today - ' . $i . ' months'));
            $monthStart = date('Y-m-01', strtotime($query_date));
            $monthEnd = date('Y-m-t', strtotime($query_date));
            $endDate = $monthEnd;
            $startDate = $monthStart;

            $date = date('M y', strtotime($startDate));
            $sql = DB::table('tickets')
                    ->select(DB::raw('count(id) as ticketid'))
                    ->where('status_id', $form_id)
                    ->where('user_id', $uid)
                    ->where('deleted_at', Null)
                    ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                    ->first();
            $labels[] = $date;
            $formtickets[] = $sql->ticketid;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formtickets'] = array_reverse($formtickets);
        $data['total'] = array_sum($formtickets);
        return json_encode($data);
    }

    public function getWeektickets(Request $request)
    {
        $uid = Auth::user()->id;
        $time = $request->input('time');
        $form_id = $request->input('form_id');

        $label = 'tickets - Last 12 Weeks';
        $today = date('Y-m-d');
        $year = date('Y', strtotime($today));
        $week = date('W', strtotime($today));
        $k = $week - 11;
        for ($i = $week; $i >= $k; $i--) {
            $dto = new DateTime();
            $start = $dto->setISODate($year, $i, 1)->format('Y-m-d');
            $end = $dto->setISODate($year, $i, 7)->format('Y-m-d');
            $date = date('d M', strtotime($start));
            $sql = DB::table('tickets')
                    ->select(DB::raw('count(id) as ticketid'))
                    ->where('status_id', $form_id)
                    ->where('user_id', $uid)
                    ->where('deleted_at', Null)
                    ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
                    ->first();
            $labels[] = $date;
            $formtickets[] = $sql->ticketid;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formtickets'] = array_reverse($formtickets);
        $data['total'] = array_sum($formtickets);
        return json_encode($data);
    }
}

<?php

namespace App\Http\Controllers\Task\User;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Outcome;
use Illuminate\Support\Facades\Auth;

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

    public function tasks()
    {
        // $data['table'] = '';

        // echo 'Tasks';
        // exit();

        $forms = Outcome::all();
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

        return view('auth.reports.report_tasks')->with("data", $data);
    }

    public function getDaytasks(Request $request)
    {
        $uid = Auth::user()->id;
        $time = $request->input('time');
        $form_id = $request->input('form_id');
        $label = 'Tasks - Last 30 Days';
        $labels = array();
        $formtasks = array();
        for ($i = 0; $i <= 30; $i++) {
            $date = date('Y-m-d', strtotime('today - ' . $i . ' days'));
            $sql = DB::table('todos')
                    ->select(DB::raw('count(id) as taskid'))
                    ->where('outcome_id', $form_id)
                    ->where('user_id', $uid)
                    ->where(DB::raw('DATE(started_at)'), $date)
                    ->where('deleted_at', Null)
                    ->first();
            $labels[] = date('d M', strtotime($date));
            $formtasks[] = $sql->taskid;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formtasks'] = array_reverse($formtasks);
        $data['total'] = array_sum($formtasks);
        return json_encode($data);
    }

    public function getMonthtasks(Request $request)
    {
        $uid = Auth::user()->id;
        $time = $request->input('time');
        $form_id = $request->input('form_id');

        $label = 'Tasks - Last 12 Months';
        $labels = array();
        $formtasks = array();
        for ($i = 0; $i <= 12; $i++) {

            $query_date = date('Y-m-d', strtotime('today - ' . $i . ' months'));
            $monthStart = date('Y-m-01', strtotime($query_date));
            $monthEnd = date('Y-m-t', strtotime($query_date));
            $endDate = $monthEnd;
            $startDate = $monthStart;

            $date = date('M y', strtotime($startDate));
            $sql = DB::table('todos')
                    ->select(DB::raw('count(id) as taskid'))
                    ->where('outcome_id', $form_id)
                    ->where('user_id', $uid)
                    ->where('deleted_at', Null)
                    ->whereBetween(DB::raw('DATE(started_at)'), [$startDate, $endDate])
                    ->first();
            $labels[] = $date;
            $formtasks[] = $sql->taskid;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formtasks'] = array_reverse($formtasks);
        $data['total'] = array_sum($formtasks);
        return json_encode($data);
    }

    public function getWeektasks(Request $request)
    {
        $uid = Auth::user()->id;
        $time = $request->input('time');
        $form_id = $request->input('form_id');

        $label = 'Tasks - Last 12 Weeks';
        $today = date('Y-m-d');
        $year = date('Y', strtotime($today));
        $week = date('W', strtotime($today));
        $k = $week - 11;
        for ($i = $week; $i >= $k; $i--) {
            $dto = new DateTime();
            $start = $dto->setISODate($year, $i, 1)->format('Y-m-d');
            $end = $dto->setISODate($year, $i, 7)->format('Y-m-d');
            $date = date('d M', strtotime($start));
            $sql = DB::table('todos')
                    ->select(DB::raw('count(id) as taskid'))
                    ->where('outcome_id', $form_id)
                    ->where('user_id', $uid)
                    ->where('deleted_at', Null)
                    ->whereBetween(DB::raw('DATE(started_at)'), [$start, $end])
                    ->first();
            $labels[] = $date;
            $formtasks[] = $sql->taskid;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formtasks'] = array_reverse($formtasks);
        $data['total'] = array_sum($formtasks);
//        echo json_encode($data);
        return json_encode($data);
    }
}

<?php

namespace App\Http\Controllers\Service\admin;

use App\User;
use DateTime;
use App\Servcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ServiceReportController extends Controller
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

    public function services()
    {
        $forms = Servcategory::all();
        $formSelect = '';
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

        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        $useroptions = "<option value='All'>All</option>";
        $uid = "All";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";
        }
        $data['useroptions'] = $useroptions;
        $data['user'] = $uid;

        return view('service.admin.service-report')->with("data", $data);
    }

    public function getDayServices(Request $request)
    {
        $uid = $request->input('user_id');
        $form_id = $request->input('form_id');
        $label = 'Services - Last 30 Days';
        $labels = array();
        $formtickets = array();
        for ($i = 0; $i <= 30; $i++) {
            $date = date('Y-m-d', strtotime('today - ' . $i . ' days'));
            if ($uid == 'All') {
                $sql = DB::table('services')
                    ->select(DB::raw('count(id) as serviceId'))
                    ->where('servcategory_id', $form_id)
                    ->where(DB::raw('DATE(created_at)'), $date)
                    ->where('deleted_at', Null)
                    ->count();
            } else {
                $sql = DB::table('services')
                    ->select(DB::raw('count(id) as serviceId'))
                    ->where('servcategory_id', $form_id)
                    ->where('user_id', $uid)
                    ->where(DB::raw('DATE(created_at)'), $date)
                    ->where('deleted_at', Null)
                    ->count();
            }

            $labels[] = date('d M', strtotime($date));
            $formtickets[] = $sql;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['services'] = array_reverse($formtickets);
        $data['total'] = array_sum($formtickets);
        return json_encode($data);
    }
    public function getMonthServices(Request $request)
    {
        $uid = $request->input('user_id');
        $form_id = $request->input('form_id');

        $label = 'Services - Last 12 Months';
        $labels = array();
        $formtickets = array();
        for ($i = 0; $i <= 12; $i++) {

            $query_date = date('Y-m-d', strtotime('today - ' . $i . ' months'));
            $monthStart = date('Y-m-01', strtotime($query_date));
            $monthEnd = date('Y-m-t', strtotime($query_date));
            $endDate = $monthEnd;
            $startDate = $monthStart;

            $date = date('M y', strtotime($startDate));
            if ($uid == 'All') {
                $sql = DB::table('services')
                        ->select(DB::raw('count(id) as serviceId'))
                        ->where('servcategory_id', $form_id)
                        ->where('deleted_at', Null)
                        ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                        ->count();
            } else {
                $sql = DB::table('services')
                        ->select(DB::raw('count(id) as serviceId'))
                        ->where('user_id', $uid)
                        ->where('servcategory_id', $form_id)
                        ->where('deleted_at', Null)
                        ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                        ->count();
            }

            $labels[] = $date;
            $formtickets[] = $sql;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['services'] = array_reverse($formtickets);
        $data['total'] = array_sum($formtickets);
        return json_encode($data);
    }

    public function getWeekServices(Request $request)
    {
        $uid = $request->input('user_id');
        $form_id = $request->input('form_id');

        $label = 'Services - Last 12 Weeks';
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
                $sql = DB::table('services')
                    ->select(DB::raw('count(id) as serviceId'))
                    ->where('servcategory_id', $form_id)
                    ->where('deleted_at', Null)
                    ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
                    ->count();
            } else {
                $sql = DB::table('services')
                    ->select(DB::raw('count(id) as serviceId'))
                    ->where('user_id', $uid)
                    ->where('servcategory_id', $form_id)
                    ->where('deleted_at', Null)
                    ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
                    ->count();
            }
            $labels[] = $date;
            $formtickets[] = $sql;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['services'] = array_reverse($formtickets);
        $data['total'] = array_sum($formtickets);
        return json_encode($data);
    }
}

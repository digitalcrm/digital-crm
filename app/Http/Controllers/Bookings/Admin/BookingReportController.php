<?php

namespace App\Http\Controllers\Bookings\Admin;

use App\User;
use DateTime;
use App\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BookingReportController extends Controller
{
    public function __construct() {
        $this->middleware('auth:admin');
    }

    public function appointment() {
        $forms = BookingService::all();
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
        return view('bookings.reports.admin-bookingevent')->with("data", $data);
    }

    public function getDayAppointment(Request $request)
    {
        $uid = $request->input('user_id');
        $form_id = $request->input('form_id');
        $label = 'Events- Last 30 Days';
        $labels = array();
        $formevents = array();
        for ($i = 0; $i <= 30; $i++) {
            $date = date('Y-m-d', strtotime('today - ' . $i . ' days'));
            if ($uid == 'All') {
                $sql = DB::table('booking_events')
                    ->select(DB::raw('count(id) as eventId'))
                    ->where('booking_service_id', $form_id)
                    ->where(DB::raw('DATE(event_start)'), $date)
                    ->where('deleted_at', Null)
                    ->count();
            } else {
                $sql = DB::table('booking_events')
                    ->select(DB::raw('count(id) as eventId'))
                    ->where('booking_service_id', $form_id)
                    ->where('user_id', $uid)
                    ->where(DB::raw('DATE(event_start)'), $date)
                    ->where('deleted_at', Null)
                    ->count();
            }

            $labels[] = date('d M', strtotime($date));
            $formevents[] = $sql;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formevents'] = array_reverse($formevents);
        $data['total'] = array_sum($formevents);
        return json_encode($data);
    }
    public function getMonthAppointment(Request $request)
    {
        $uid = $request->input('user_id');
        $form_id = $request->input('form_id');

        $label = 'Events- Last 12 Months';
        $labels = array();
        $formevents = array();
        for ($i = 0; $i <= 12; $i++) {

            $query_date = date('Y-m-d', strtotime('today - ' . $i . ' months'));
            $monthStart = date('Y-m-01', strtotime($query_date));
            $monthEnd = date('Y-m-t', strtotime($query_date));
            $endDate = $monthEnd;
            $startDate = $monthStart;

            $date = date('M y', strtotime($startDate));
            if ($uid == 'All') {
                $sql = DB::table('booking_events')
                        ->select(DB::raw('count(id) as eventId'))
                        ->where('booking_service_id', $form_id)
                        ->where('deleted_at', Null)
                        ->whereBetween(DB::raw('DATE(event_start)'), [$startDate, $endDate])
                        ->count();
            } else {
                $sql = DB::table('booking_events')
                        ->select(DB::raw('count(id) as eventId'))
                        ->where('user_id', $uid)
                        ->where('booking_service_id', $form_id)
                        ->where('deleted_at', Null)
                        ->whereBetween(DB::raw('DATE(event_start)'), [$startDate, $endDate])
                        ->count();
            }

            $labels[] = $date;
            $formevents[] = $sql;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formevents'] = array_reverse($formevents);
        $data['total'] = array_sum($formevents);
        return json_encode($data);
    }

    public function getWeekAppointment(Request $request)
    {
        $uid = $request->input('user_id');
        $form_id = $request->input('form_id');

        $label = 'Events- Last 12 Weeks';
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
                $sql = DB::table('booking_events')
                    ->select(DB::raw('count(id) as eventId'))
                    ->where('booking_service_id', $form_id)
                    ->where('deleted_at', Null)
                    ->whereBetween(DB::raw('DATE(event_start)'), [$start, $end])
                    ->count();
            } else {
                $sql = DB::table('booking_events')
                    ->select(DB::raw('count(id) as eventId'))
                    ->where('user_id', $uid)
                    ->where('booking_service_id', $form_id)
                    ->where('deleted_at', Null)
                    ->whereBetween(DB::raw('DATE(event_start)'), [$start, $end])
                    ->count();
            }
            $labels[] = $date;
            $formevents[] = $sql;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formevents'] = array_reverse($formevents);
        $data['total'] = array_sum($formevents);
        return json_encode($data);
    }
}

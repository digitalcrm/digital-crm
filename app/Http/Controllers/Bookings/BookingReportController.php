<?php

namespace App\Http\Controllers\Bookings;

use DateTime;
use App\BookingEvent;
use App\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BookingReportController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function appointment()
    {
        // $data['table'] = '';

        $forms = BookingService::all();
        $formSelect = '<option value="0">Select Field</option>';
        $i = 0;
        $default_form = 0;
        foreach ($forms as $form) {
            if ($i == 0) {
                $default_form = $form->id;
                $formSelect .= '<option value="' . $form->id . '" selected>' . $form->service_name . '</option>';
                $i++;
            } else {
                $formSelect .= '<option value="' . $form->id . '">' . $form->service_name . '</option>';
            }
        }
        $data['default_form'] = $default_form;
        $data['formSelect'] = $formSelect;

        return view('bookings.reports.user-bookingevent')->with("data", $data);
    }

    public function getDayAppointment(Request $request)
    {
        $uid = Auth::user()->id;
        $time = $request->input('time');
        $form_id = $request->input('form_id');
        $label = 'Events - Last 30 Days';
        $labels = array();
        $formAppointment = array();
        for ($i = 0; $i <= 30; $i++) {
            $date = date('Y-m-d', strtotime('today - ' . $i . ' days'));
            $sql = DB::table('booking_events')
                    ->select(DB::raw('count(id) as eventId'))
                    ->where('booking_service_id', $form_id)
                    ->where('user_id', $uid)
                    ->where(DB::raw('DATE(event_start)'), $date)
                    ->where('deleted_at', Null)
                    ->first();
            $labels[] = date('d M', strtotime($date));
            $formAppointment[] = $sql->eventId;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formAppointment'] = array_reverse($formAppointment);
        $data['total'] = array_sum($formAppointment);
        return json_encode($data);
    }

    public function getMonthAppointment(Request $request)
    {
        $uid = Auth::user()->id;
        $time = $request->input('time');
        $form_id = $request->input('form_id');

        $label = 'Events - Last 12 Months';
        $labels = array();
        $formAppointment = array();
        for ($i = 0; $i <= 12; $i++) {

            $query_date = date('Y-m-d', strtotime('today - ' . $i . ' months'));
            $monthStart = date('Y-m-01', strtotime($query_date));
            $monthEnd = date('Y-m-t', strtotime($query_date));
            $endDate = $monthEnd;
            $startDate = $monthStart;

            $date = date('M y', strtotime($startDate));
            $sql = DB::table('booking_events')
                    ->select(DB::raw('count(id) as eventId'))
                    ->where('booking_service_id', $form_id)
                    ->where('user_id', $uid)
                    ->where('deleted_at', Null)
                    ->whereBetween(DB::raw('DATE(event_start)'), [$startDate, $endDate])
                    ->first();
            $labels[] = $date;
            $formAppointment[] = $sql->eventId;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formAppointment'] = array_reverse($formAppointment);
        $data['total'] = array_sum($formAppointment);
        return json_encode($data);
    }

    public function getWeekAppointment(Request $request)
    {
        $uid = Auth::user()->id;
        $time = $request->input('time');
        $form_id = $request->input('form_id');

        $label = 'Events - Last 12 Weeks';
        $today = date('Y-m-d');
        $year = date('Y', strtotime($today));
        $week = date('W', strtotime($today));
        $k = $week - 11;
        for ($i = $week; $i >= $k; $i--) {
            $dto = new DateTime();
            $start = $dto->setISODate($year, $i, 1)->format('Y-m-d');
            $end = $dto->setISODate($year, $i, 7)->format('Y-m-d');
            $date = date('d M', strtotime($start));
            $sql = DB::table('booking_events')
                    ->select(DB::raw('count(id) as eventId'))
                    ->where('booking_service_id', $form_id)
                    ->where('user_id', $uid)
                    ->where('deleted_at', Null)
                    ->whereBetween(DB::raw('DATE(event_start)'), [$start, $end])
                    ->first();
            $labels[] = $date;
            $formAppointment[] = $sql->eventId;
        }
        $data['label'] = $label;
        $data['labels'] = array_reverse($labels);
        $data['formAppointment'] = array_reverse($formAppointment);
        $data['total'] = array_sum($formAppointment);
        return json_encode($data);
    }
}

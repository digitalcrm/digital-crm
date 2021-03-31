<?php

namespace App\Http\Controllers;

use App\BookingEvent;
use App\Tbl_deals;
use App\Tbl_leads;
use App\Tbl_events;
use App\Tbl_Accounts;
use App\Tbl_contacts;
// Models
use App\Tbl_event_types;
use App\Tbl_notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $uid = Auth::user()->id;
        $events = Tbl_events::where('uid', $uid)->where('active', 1)->get();
        //        echo json_encode($events);
        //        exit();
        $total = count($events);

        $deals = Tbl_deals::where('uid', $uid)->where('active', 1)->get();
        $total += count($deals);

        $appointments = BookingEvent::where('user_id', $uid)->where('isActive', 1)->get();
        $total += count($appointments);


        $data['total'] = $total;


        $data['mydealsli'] = $this->getMyDeals($uid);
        $data['myeventsli'] = $this->getMyEvents($uid);
        $data['myappointmentsli'] = $this->getMyAppointments($uid);

        //        echo json_encode($data);
        //        exit();

        return view('auth.calendar.index')->with("data", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $types = Tbl_event_types::all();
        $typeoptions = "<option value='0'>Select Event Type</option>";
        foreach ($types as $type) {
            $typeoptions .= "<option value='" . $type->evtype_id . "'>" . $type->type . "</option>";
        }
        $data['typeoptions'] = $typeoptions;
        return view('auth.calendar.create')->with('data', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //        echo json_encode($request->input());
        //        exit();

        $uid = Auth::user()->id;

        $this->validate($request, [
            'title' => 'required|max:255',
            'startDate' => 'required',
            'endDate' => 'required',
            'startTime' => 'required',
            'endTime' => 'required',
        ]);

        $category = $request->input('category');
        $user = $request->input('user');
        $allday = ($request->input('allday') != '') ? 1 : 0;

        $dateStart = $request->input('startDate');
        $dateEnd = $request->input('endDate');

        $timeStart = $request->input('startTime');
        $timeEnd = $request->input('endTime');

        $startDatetime = date('Y-m-d H:i:s', strtotime($dateStart . ' ' . $timeStart));
        $endDatetime = date('Y-m-d H:i:s', strtotime($dateEnd . ' ' . $timeEnd));
        //        echo $dateStart . ' ' . $timeStart . ' ' . $startDatetime . "<br>";
        //        echo $dateEnd . ' ' . $timeEnd . ' ' . $endDatetime . "<br>";
        //        echo "<br>";
        //        exit();
        if (strtotime($startDatetime) < strtotime($endDatetime)) {
            $formData = array(
                'uid' => $uid,
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'start_date' => $request->input('startDate'),
                'end_date' => $request->input('endDate'),
                'start_time' => $request->input('startTime'),
                'end_time' => $request->input('endTime'),
                'startDatetime' => $startDatetime,
                'endDatetime' => $endDatetime,
                'type' => $category,
                'id' => $user,
                'location' => $request->input('location'),
                'allday' => $allday,
                'evtype_id' => $request->input('type'),
            );

            $event = Tbl_events::create($formData);
            $ev_id = $event->ev_id;
            if ($ev_id > 0) {
                return redirect('/calendar')->with('success', 'Event created successfully');
            } else {
                return redirect('/calendar/create')->with('error', 'Error occurred. Try again later.');
            }
        } else {
            return redirect('/calendar/create')->with('error', 'Please check the dates selected');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = Tbl_events::with('tbl_event_types')->find($id);
        //        echo json_encode($event);
        $data['event'] = $event;
        $data['category'] = '';
        $data['user'] = '';
        if (($event->type == 1) && ($event->id > 0)) {
            $accounts = Tbl_Accounts::find($event->id);
            $data['category'] = 'Accounts';
            $data['user'] = $accounts->name;
        }
        if (($event->type == 2) && ($event->id > 0)) {
            $contacts = Tbl_contacts::find($event->id);
            $data['category'] = 'Contacts';
            $data['user'] = $contacts->first_name . ' ' . $contacts->last_name;
        }
        if (($event->type == 3) && ($event->id > 0)) {
            $leads = Tbl_leads::find($event->id);

            //            echo json_encode($leads);
            //            exit();

            $data['category'] = 'Leads';
            $data['user'] = $leads->first_name . ' ' . $leads->last_name;
        }

        return view('auth.calendar.show')->with('data', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $uid = Auth::user()->id;
        $event = Tbl_events::with('tbl_event_types')->find($id);
        //        echo json_encode($event);

        $data['event'] = $event;
        $data['Accountcategory'] = '';
        $data['Leadscategory'] = '';
        $data['Contactscategory'] = '';
        $data['option'] = '<option value="0">Select...</option>';
        if (($event->type == 1) && ($event->id > 0)) {
            $accounts = Tbl_Accounts::where('uid', $uid)->where('active', 1)->get();

            $accountOption = "<option value='0'>Select Account</option>";
            foreach ($accounts as $acnt) {
                $selectedAccount = ($event->id == $acnt->acc_id) ? 'selected' : '';
                $accountOption .= "<option value='" . $acnt->acc_id . "' " . $selectedAccount . ">" . $acnt->name . "</option>";
            }
            $data['option'] = $accountOption;
            $data['Accountcategory'] = 'selected';
        }
        if (($event->type == 2) && ($event->id > 0)) {
            $contacts = Tbl_contacts::where('uid', $uid)->where('active', 1)->get();

            $contactOption = "<option value='0'>Select Contact</option>";
            foreach ($contacts as $cnt) {
                $selectedContact = ($event->id == $cnt->cnt_id) ? 'selected' : '';
                $contactOption .= "<option value='" . $cnt->cnt_id . "' " . $selectedContact . ">" . $cnt->first_name . ' ' . $cnt->last_name . ' ' . "</option>";
            }
            $data['option'] = $contactOption;
            $data['Contactscategory'] = 'selected';
        }
        if (($event->type == 3) && ($event->id > 0)) {
            $leads = Tbl_leads::where('uid', $uid)->where('active', 1)->get();

            $leadOption = "<option value='0'>Select Lead</option>";
            foreach ($leads as $ld) {
                $selectedLead = ($event->id == $ld->ld_id) ? 'selected' : '';
                $leadOption .= "<option value='" . $ld->ld_id . "' " . $selectedLead . ">" . $ld->first_name . ' ' . $ld->last_name . ' ' . "</option>";
            }
            $data['option'] = $leadOption;
            $data['Leadscategory'] = 'selected';
        }

        $types = Tbl_event_types::all();
        $typeoptions = "<option value='0'>Select Event Type</option>";
        foreach ($types as $type) {
            $evtype = ($event->tbl_event_types != '') ? $event->tbl_event_types->evtype_id : 0;
            $selectedevtype = ($type->evtype_id == $evtype) ? 'selected' : '';
            $typeoptions .= "<option value='" . $type->evtype_id . "' " . $selectedevtype . ">" . $type->type . "</option>";
        }
        $data['typeoptions'] = $typeoptions;

        return view('auth.calendar.edit')->with('data', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //        echo json_encode($request->input());
        $uid = Auth::user()->id;

        $this->validate($request, [
            'title' => 'required|max:255',
            'startDate' => 'required',
            'endDate' => 'required',
            'startTime' => 'required',
            'endTime' => 'required',
        ]);

        $category = $request->input('category');
        $user = $request->input('user');
        $allday = ($request->input('allday') != '') ? 1 : 0;

        $dateStart = $request->input('startDate');
        $dateEnd = $request->input('endDate');

        $timeStart = $request->input('startTime');
        $timeEnd = $request->input('endTime');

        $startDatetime = date('Y-m-d H:i:s', strtotime($dateStart . ' ' . $timeStart));
        $endDatetime = date('Y-m-d H:i:s', strtotime($dateEnd . ' ' . $timeEnd));

        if (strtotime($startDatetime) < strtotime($endDatetime)) {

            $event = Tbl_events::find($id);
            $event->title = $request->input('title');
            $event->description = $request->input('description');
            $event->start_date = $request->input('startDate');
            $event->end_date = $request->input('endDate');
            $event->start_time = $request->input('startTime');
            $event->end_time = $request->input('endTime');
            $event->startDatetime = $startDatetime;
            $event->endDatetime = $endDatetime;
            $event->type = $category;
            $event->id = $user;
            $event->allday = $allday;
            $event->location = $request->input('location');
            $event->evtype_id = $request->input('type');
            $event->save();
            return redirect('/calendar')->with('success', 'Updated successfully...!');
        } else {
            return redirect('/calendar/' . $id . '/edit')->with('error', 'Please check the dates selected');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getUserEvents($uid)
    {
        $data = [];
        $events = Tbl_events::where('uid', $uid)->where('active', 1)->get(['ev_id', 'title', 'startDatetime as start', 'endDatetime as end', 'description', 'type', 'id', 'allday']);
        foreach ($events as $event) {

            $acldata = '';
            $acl_title = '';
            $acl_name = '';
            $acl_mobile = '';
            $acl_phone = '';
            $acl_city = '';

            //  accounts
            if ($event->type == 1) {
                $acl_title = 'Account';
                $acldata = Tbl_Accounts::find($event->id);
                $acl_name = ($acldata != '') ? $acldata->name : '';
                $acl_city = ($acldata != '') ? $acldata->city : '';
            }
            //  contacts
            if ($event->type == 2) {
                $acl_title = 'Contact';
                $acldata = Tbl_contacts::find($event->id);
                $acl_name = ($acldata != '') ? $acldata->first_name . ' ' . $acldata->last_name : '';
                $acl_city = ($acldata != '') ? $acldata->city : '';
            }
            //  leads
            if ($event->type == 3) {
                $acl_title = 'Lead';
                $acldata = Tbl_leads::find($event->id);
                $acl_name = ($acldata != '') ? $acldata->first_name . ' ' . $acldata->last_name : '';
                $acl_mobile = ($acldata != '') ? $acldata->mobile : '';
                $acl_city = ($acldata != '') ? $acldata->city : '';
            }

            $eveArr['ev_id'] = $event->ev_id;
            $eveArr['title'] = $event->title;
            $eveArr['start'] = $event->start;
            $eveArr['end'] = $event->end;
            //            $eveArr['url'] = url('calendar/' . $event->ev_id);
            $eveArr['description'] = $event->description;

            $popup_content = '<div class="card card-widget widget-user-2">
            <div class="card-header">
                <h5 class="">Event</h5>
              </div>
            <div class="card-body p-0">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a href="#" class="nav-link">
                Title &nbsp; <span class="float-right">' . $event->title . '</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                Time &nbsp; <span class="float-right">' . date('d-m-Y', strtotime($event->start)) . '</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                Description &nbsp; <span class="float-right">' . $event->description . '</span>
                </a>
              </li>';
            if ($event->type > 0) {
                $popup_content .= '<li class="nav-item">
                <a href="#" class="nav-link">
                ' . $acl_title . ' &nbsp; <span class="float-right">' . $acl_name . '</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                Mobile &nbsp; <span class="float-right">' . $acl_mobile . '</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                City &nbsp; <span class="float-right">' . $acl_city . '</span>
                </a>
              </li>';
            }
            $popup_content .= '</ul>
          </div>
          </div>';
            $eveArr['popup_content'] = $popup_content;
            $data[] = $eveArr;
        }

        $deals = Tbl_deals::where('uid', $uid)->where('active', 1)->get(['deal_id', 'name as title', 'closing_date as start', 'closing_date as end', 'notes as description', 'ld_id']);
        foreach ($deals as $deal) {

            $acldata = '';
            $acl_title = '';
            $acl_name = '';
            $acl_mobile = '';
            $acl_city = '';

            if ($deal->ld_id > 0) {
                $acldata = Tbl_leads::find($deal->ld_id);
                $acl_name = ($acldata != '') ? $acldata->first_name . ' ' . $acldata->last_name : '';
                $acl_mobile = ($acldata != '') ? $acldata->mobile : '';
                $acl_city = ($acldata != '') ? $acldata->city : '';
            }

            $eveArr['ev_id'] = $deal->ev_id;
            $eveArr['title'] = $deal->title;
            $eveArr['start'] = $deal->start;
            $eveArr['end'] = $deal->end;
            //            $eveArr['url'] = url('deals/' . $deal->deal_id);
            $eveArr['description'] = $deal->description;
            $popup_content = '<div class="card card-widget widget-user-2">
            <div class="card-header">
                <h5 class="">Deal</h5>
              </div>
            <div class="card-body p-0">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a href="#" class="nav-link">
                Title &nbsp; <span class="float-right">' . $deal->title . '</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                Time &nbsp; <span class="float-right">' . date('d-m-Y', strtotime($deal->start)) . '</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                Description &nbsp; <span class="float-right">' . $deal->description . '</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                Lead &nbsp; <span class="float-right">' . $acl_name . '</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                Mobile &nbsp; <span class="float-right">' . $acl_mobile . '</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                City &nbsp; <span class="float-right">' . $acl_city . '</span>
                </a>
              </li>
              </ul>
              </div>
              </div>';
            $eveArr['popup_content'] = $popup_content;

            $data[] = $eveArr;
        }


        $customers = auth()->user()->bookingCustomers()->latest()->get();

        // echo json_encode($customers);
        // exit();

        foreach ($customers as $appointment) {

            $eveArr['ev_id'] = $appointment->id;
            $eveArr['title'] = $appointment->customer_name;
            $eveArr['start'] = $appointment->start_from;
            $eveArr['end'] = $appointment->to_end;
            $eveArr['color'] = 'green !important';
            $eveArr['textColor'] = 'antiquewhite !important';
            $eveArr['url'] = route('bookevents.show', $appointment->bookingEvent->id);

            $popup_content = '<div class="card card-widget widget-user-2">
            <div class="card-header">
                <h5 class="">' . $appointment->bookingEvent->event_name . '</h5>
              </div>
            <div class="card-body p-0">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a href="#" class="nav-link">
                Appointment Name: &nbsp; <span class="float-right">' . $appointment->bookingEvent->event_name . '</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                Description: &nbsp; <span class="float-right">' . $appointment->bookingEvent->event_description . '</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                Start Time: &nbsp; <span class="float-right">' . $appointment->bookingEvent->event_start->isoFormat('MMM/D/YYYY, h:mm a') . '</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                End Time: &nbsp; <span class="float-right">' . $appointment->bookingEvent->event_end->isoFormat('MMM/D/YYYY, h:mm a') . '</span>
                </a>
              </li>
              </ul>
              </div>
              </div>';
            $eveArr['popup_content'] = $popup_content;

            $data[] = $eveArr;
        }
        
        return $data;
    }

    public function getMyEvents($uid)
    {
        $date = date('Y-m-d h:i:s', strtotime("now"));
        $events = Tbl_events::where('uid', $uid)->where('active', 1)->where(DB::raw('startDatetime'), '>=', $date)->get(['ev_id', 'title', 'startDatetime as start', 'endDatetime as end']);
        $myeventsli = '';

        foreach ($events as $event) {

            $date1 = strtotime(date('Y-m-d h:i:s'));
            $date2 = strtotime($event->start);

            $days = abs($date2 - $date1) / (60 * 60 * 24);

            $label = '';
            if (($days > 0) || ($days < 1)) {
                $label = ' closes <small class = "label label-danger"><i class = "fa fa-clock-o"></i>Today</small>';
            }

            if ($days == 1) {
                $label = ' close <label class = "label label-warning"><i class = "fa fa-clock-o"></i> Tomorrow</label>';
            }

            if (($days == 2) || ($days == 3)) {
                $label = 'closes in <label class = "label label-primary"><i class = "fa fa-clock-o"></i> ' . $days . '</label>';
            }

            if (($days > 3) && ($days < 10)) {
                $label = ' close on <label class = "label label-info"><i class = "fa fa-clock-o"></i> ' . date("d-m-Y", strtotime($event->start)) . '</label>';
            }

            if ($days > 10) {
                $label = ' close on <label class = "external-event bg-primary"><i class = "fa fa-clock-o"></i> ' . date("d-m-Y", strtotime($event->start)) . '</label>';
            }

            //            $myeventsli .= '<li >
            //                                <a href="' . url('calendar/' . $event->ev_id) . '">
            //                                    <span class="text">' . $event->title . '</span>
            //                                    ' . $label . '
            //                                </a>
            //                            </li>';
            //            $myeventsli .= '<li><a href="#" data-toggle="popover" title="' . $event->title . '" data-content="' . $event->description . '">' . $event->title . '</a></li>';

            $bgc = array("success", "warning", "info", "primary", "danger");
            $random_keys = array_rand($bgc);
            //            echo $bgc[$random_keys];

            $myeventsli .= '<div class="external-event bg-' . $bgc[$random_keys] . '">' . $event->title . '</div>';
        }

        return $myeventsli;
    }

    public function getMyDeals($uid)
    {
        $date = date("Y-m-d");
        $mydeals = Tbl_deals::where('uid', $uid)->where('active', 1)->where(DB::raw('DATE(closing_date)'), '>=', $date)->get(['deal_id', 'name as title', 'closing_date as start', 'closing_date as end']);

        $mydealsli = '';
        foreach ($mydeals as $mydeal) {
            $label = '';
            if ($date == $mydeal->start) {
                $label = ' closes <small class="label label-danger"><i class="fa fa-clock-o"></i>Today</small>';
            }

            if ($mydeal->start > $date) {

                $date1 = date_create($mydeal->start);
                $date2 = date_create($date);
                $diff = date_diff($date1, $date2);
                $days = $diff->format("%a");
                $label = '';

                if ($days == 1) {
                    $label = ' closes <label class="label label-warning"><i class="fa fa-clock-o"></i> Tomorrow</label>';
                }

                if (($days == 2) || ($days == 3)) {
                    $label = ' close in <label class="label label-primary"><i class="fa fa-clock-o"></i> ' . $days . '</label>';
                }

                if (($days > 3) && ($days < 10)) {
                    $label = ' close on <label class="label label-info"><i class="fa fa-clock-o"></i> ' . date("d-m-Y", strtotime($mydeal->start)) . '</label>';
                }

                if ($days > 10) {
                    $label = ' close on <label class="label label-default"><i class="fa fa-clock-o"></i> ' . date("d-m-Y", strtotime($mydeal->start)) . '</label>';
                }
            }

            $mydealsli .= '<li>
                                <a href="' . url('deals/' . $mydeal->deal_id) . '">
                                    <span class="text">' . $mydeal->title . '</span>
                                    ' . $label . '
                                </a>
                            </li>';
        }

        return $mydealsli;
    }

    public function getMyAppointments($uid)
    {
        $date = date("Y-m-d");
        // dd($date);
        $myappointments = BookingEvent::where('user_id', $uid)
            ->upcoming()
            ->active()
            // ->where(DB::raw('DATE(event_end)'), '>=', $date)
            ->get(['id', 'event_name as title', 'event_start as start', 'event_end as end']);
        // dd($myappointments);
        $myappointmentsli = '';
        foreach ($myappointments as $appointment) {
            $label = '';
            if ($date == $appointment->event_start) {
                $label = ' closes <small class="label label-danger"><i class="fa fa-clock-o"></i>Today</small>';
            }

            if ($appointment->event_start > $date) {

                $date1 = date_create($appointment->event_start);
                $date2 = date_create($date);
                $diff = date_diff($date1, $date2);
                $days = $diff->format("%a");
                // dd($days);
                $label = '';

                if ($days == 1) {
                    $label = ' closes <label class="label label-warning"><i class="fa fa-clock-o"></i> Tomorrow</label>';
                }

                if (($days == 2) || ($days == 3)) {
                    $label = ' close in <label class="label label-primary"><i class="fa fa-clock-o"></i> ' . $days . '</label>';
                }

                if (($days > 3) && ($days < 10)) {
                    $label = ' close on <label class="label label-info"><i class="fa fa-clock-o"></i> ' . date("d-m-Y", strtotime($appointment->event_start)) . '</label>';
                }

                if ($days > 10) {
                    $label = ' close on <label class="label label-default"><i class="fa fa-clock-o"></i> ' . date("d-m-Y", strtotime($appointment->event_start)) . '</label>';
                }
            }

            $myappointmentsli .= '<li>
                                <a href="' . url('bookevents/' . $appointment->id) . '">
                                    <span class="text">' . $appointment->event_name . '</span>
                                    ' . $label . '
                                </a>
                            </li>';
        }

        return $myappointmentsli;
    }

    public function delete($id)
    {
        $eve = Tbl_events::find($id);
        $eve->active = 0;
        $eve->save();
        return redirect('/calendar')->with('success', 'Deleted Successfully...!');
    }

    public function getTodayEvents()
    {

        $today = date('d-m-Y', strtotime('today'));

        $events = Tbl_events::where('cron_status', 0)
            ->where(DB::raw('start_date'), $today)
            ->orwhere(DB::raw('DATE(end_date)'), $today)
            ->get();

        $total = count($events);

        if ($total > 0) {
            $formdata = array();
            foreach ($events as $event) {
                $formdata[] = array(
                    'uid' => $event->uid,
                    'message' => $event->title . ' closes today',
                    'type' => 5,
                    'id' => $event->ev_id,
                );

                $event->cron_status = 1;
                $event->save();
            }
            Tbl_notifications::insert($formdata);
        }

        return json_encode($events);
    }

    public function getTomorrowEvents()
    {

        $tomorrow = date('d-m-Y', strtotime('tomorrow'));

        $events = Tbl_events::where('cron_status', 0)
            ->where(DB::raw('start_date'), $tomorrow)
            ->orwhere(DB::raw('DATE(end_date)'), $tomorrow)
            ->get();

        $total = count($events);

        if ($total > 0) {
            $formdata = array();
            foreach ($events as $event) {
                $formdata[] = array(
                    'uid' => $event->uid,
                    'message' => $event->title . ' closes tomorrow',
                    'type' => 5,
                    'id' => $event->ev_id,
                );

                $event->cron_status = 1;
                $event->save();
            }
            Tbl_notifications::insert($formdata);
        }

        return json_encode($events);
    }
}

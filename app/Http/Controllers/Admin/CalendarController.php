<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Tbl_Accounts;
use App\Tbl_contacts;
use App\Tbl_events;
use App\Tbl_leads;
use App\User;
use Illuminate\Http\Request;

class CalendarController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderby('id', 'asc')->get(['id', 'name']);
//        $uid = 'All';
        $useroptions = "<option value='All' selected>All</option>";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>"; // " . $selected . "
        }
        $data['useroptions'] = $useroptions;
        return view('admin.calendar.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::orderby('id', 'asc')->get(['id', 'name']);
//        $uid = 'All';
        $useroptions = "<option value=''>Select User</option>";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>"; // " . $selected . "
        }
        $data['useroptions'] = $useroptions;
        return view('admin.calendar.create')->with('data', $data);
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

        $this->validate($request, [
            'user' => 'required',
            'title' => 'required|max:255',
            'startDate' => 'required',
            'endDate' => 'required',
            'startTime' => 'required',
            'endTime' => 'required',
        ]);

        $category = $request->input('category');
        $user = $request->input('member');

        $dateStart = $request->input('startDate');
        $dateEnd = $request->input('endDate');

        $timeStart = $request->input('startTime');
        $timeEnd = $request->input('endTime');

        $startDatetime = date('Y-m-d H:i:s', strtotime($dateStart . ' ' . $timeStart));
        $endDatetime = date('Y-m-d H:i:s', strtotime($dateEnd . ' ' . $timeEnd));
//        echo $startDatetime . "<br>";
        //        echo $endDatetime . "<br>";
        //        echo "<br>";
        if (strtotime($startDatetime) < strtotime($endDatetime)) {
            $formData = array(
                'uid' => $request->input('user'),
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
            );

            $event = Tbl_events::create($formData);
            $ev_id = $event->ev_id;
            if ($ev_id > 0) {
                return redirect('admin/calendar')->with('success', 'Event created successfully');
            } else {
                return redirect('admin/calendar/create')->with('error', 'Error occurred. Try again later.');
            }
        } else {
            return redirect('admin/calendar/create')->with('error', 'Please check the dates selected');
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
        $event = Tbl_events::find($id);
//        echo json_encode($event);
        $data['event'] = $event;
        $data['category'] = '';
        $data['user'] = User::find($event->uid);
        $data['member'] = '';
        if (($event->type == 1) && ($event->id > 0)) {
            $accounts = Tbl_Accounts::find($event->id);
            $data['category'] = 'Accounts';
            $data['member'] = $accounts->name;
        }
        if (($event->type == 2) && ($event->id > 0)) {
            $contacts = Tbl_contacts::find($event->id);
            $data['category'] = 'Contacts';
            $data['member'] = $contacts->first_name . ' ' . $contacts->last_name;
        }
        if (($event->type == 3) && ($event->id > 0)) {
            $leads = Tbl_leads::find($event->id);
            $data['category'] = 'Leads';
            $data['member'] = $leads->name . ' ' . $leads->last_name;
        }

        return view('admin.calendar.show')->with('data', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $event = Tbl_events::find($id);
//        echo json_encode($event);

        $uid = $event->uid;

        $data['uid'] = $uid;
        $data['event'] = $event;
        $data['Accountcategory'] = '';
        $data['Leadscategory'] = '';
        $data['Contactscategory'] = '';
        $data['option'] = '<option value="0">Select...</option>';
        if (($event->type == 1) && ($event->id > 0)) {
            $accounts = Tbl_Accounts::where('uid', $uid)->get();

            $accountOption = "<option value='0'>Select Account</option>";
            foreach ($accounts as $acnt) {
                $selectedAccount = ($event->id == $acnt->acc_id) ? 'selected' : '';
                $accountOption .= "<option value='" . $acnt->acc_id . "' " . $selectedAccount . ">" . $acnt->name . "</option>";
            }
            $data['option'] = $accountOption;
            $data['Accountcategory'] = 'selected';
        }
        if (($event->type == 2) && ($event->id > 0)) {
            $contacts = Tbl_contacts::where('uid', $uid)->get();

            $contactOption = "<option value='0'>Select Contact</option>";
            foreach ($contacts as $cnt) {
                $selectedContact = ($event->id == $cnt->cnt_id) ? 'selected' : '';
                $contactOption .= "<option value='" . $cnt->cnt_id . "' " . $selectedContact . ">" . $cnt->first_name . ' ' . $cnt->last_name . ' ' . "</option>";
            }
            $data['option'] = $contactOption;
            $data['Contactscategory'] = 'selected';
        }
        if (($event->type == 3) && ($event->id > 0)) {
            $leads = Tbl_leads::where('uid', $uid)->get();

            $leadOption = "<option value='0'>Select Lead</option>";
            foreach ($leads as $ld) {
                $selectedLead = ($event->id == $ld->ld_id) ? 'selected' : '';
                $leadOption .= "<option value='" . $ld->ld_id . "' " . $selectedLead . ">" . $ld->first_name . ' ' . $ld->last_name . ' ' . "</option>";
            }
            $data['option'] = $leadOption;
            $data['Leadscategory'] = 'selected';
        }

        return view('admin.calendar.edit')->with('data', $data);
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

        $event = Tbl_events::find($id);

        $uid = $event->uid;

        $this->validate($request, [
            'title' => 'required|max:255',
            'startDate' => 'required',
            'endDate' => 'required',
            'startTime' => 'required',
            'endTime' => 'required',
        ]);

        $category = $request->input('category');
        $user = $request->input('user');

        $dateStart = $request->input('startDate');
        $dateEnd = $request->input('endDate');

        $timeStart = $request->input('startTime');
        $timeEnd = $request->input('endTime');

        $startDatetime = date('Y-m-d H:i:s', strtotime($dateStart . ' ' . $timeStart));
        $endDatetime = date('Y-m-d H:i:s', strtotime($dateEnd . ' ' . $timeEnd));

        if (strtotime($startDatetime) < strtotime($endDatetime)) {
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
            $event->save();
            return redirect('admin/calendar')->with('success', 'Updated successfully...!');
        } else {
            return redirect('admin/calendar/' . $id . '/edit')->with('error', 'Please check the dates selected');
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
        if ($uid == 'All') {
            $events = Tbl_events::where('active', 1)->get(['ev_id', 'title', 'startDatetime as start', 'endDatetime as end']);
        } else {
            $events = Tbl_events::where('active', 1)->where('uid', $uid)->get(['ev_id', 'title', 'startDatetime as start', 'endDatetime as end']);
        }
        $data = [];
        foreach ($events as $event) {
            $eveArr['ev_id'] = $event->ev_id;
            $eveArr['title'] = $event->title;
            $eveArr['start'] = $event->start;
            $eveArr['end'] = $event->end;
            $eveArr['url'] = url('admin/calendar/' . $event->ev_id);
            $data[] = $eveArr;
        }
        return $data;
    }

    public function delete($id)
    {
        $eve = Tbl_events::find($id);
        $eve->active = 0;
        $eve->save();
        return redirect('admin/calendar')->with('success', 'Deleted Successfully...!');
    }

}

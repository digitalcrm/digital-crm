<?php

namespace App\Http\Controllers\Admin;

use Mail;
use App\User;
use App\Admin;
use App\currency;
use App\Tbl_deals;
use App\Tbl_forms;
use App\Tbl_leads;
// use Webklex\IMAP\Client;
use App\Tbl_emails;

//---------Models---------------
use App\Tbl_events;
use App\Tbl_invoice;
use App\Tbl_Accounts;
use App\Tbl_contacts;
use App\Tbl_products;
use App\Tbl_documents;
use App\Tbl_formleads;
use App\Tbl_formviews;
use App\Tbl_territory;
use App\Tbl_Appdetails;
use App\Tbl_leadsource;
use App\Tbl_leadstatus;
use App\Tbl_Permissions;
use App\Tbl_salesfunnel;
use App\Tbl_Verifyadmin;
use App\Tbl_emailcategory;
use App\Tbl_emailtemplates;
use Illuminate\Http\Request;
use App\Tbl_Admin_Permissions;
use Webklex\IMAP\Facades\Client;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Admin\MailController;

class AdminController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $yearOptions = '';
        for ($i = 0; $i < 12; $i++) {
            $year = date('Y', strtotime('-' . $i . ' years'));
            $yearOptions .= '<option value="' . $year . '">' . $year . '</option>';
        }
        $data['yearData'] = $this->getReportsYear(date('Y'));
        $data['yearOptions'] = $yearOptions;
        $data['users'] = $this->users();
        $data['subusers'] = $this->subusers();
        $data['forms'] = $this->forms();
        $data['webtolead'] = $this->webtolead();
        $data['leads'] = $this->leads();
        $data['accounts'] = $this->accounts();
        $data['contacts'] = $this->contacts();
        $data['deals'] = $this->deals();
        $data['products'] = $this->products();
        $data['documents'] = $this->documents();
        $data['events'] = $this->events();
        $data['invoices'] = $this->invoices();
        $data['customers'] = $this->customers();
        $data['sales'] = $this->sales();
        $data['territory'] = $this->territory();
        $data['cronjobs'] = $this->cronjobs();

        $data['userOptions'] = $this->userOptions();

        $salesstage = $this->salesstageOptions();

        $data['default_salesfunnel'] = $salesstage['default_salesfunnel'];
        $data['salesfunnelOptions'] = $salesstage['salesfunnelOptions'];


        return view('admin.dashboard')->with('data', $data);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect('admin');
    }

    public function users()
    {
        $users = User::where('user_type', 1)->get();
        return count($users);
    }

    public function subusers()
    {
        $users = User::where('user_type', 0)->get();
        return count($users);
    }

    public function forms()
    {
        $forms = Tbl_forms::where('active', 1)->get();
        return count($forms);
    }

    public function webtolead()
    {
        $formleads = Tbl_formleads::where('active', 1)->get();
        return count($formleads);
    }

    public function leads()
    {
        $leads = Tbl_leads::where('active', 1)->get();
        return count($leads);
    }

    public function accounts()
    {
        $accounts = Tbl_Accounts::where('active', 1)->get();
        return count($accounts);
    }

    public function contacts()
    {
        $accounts = Tbl_contacts::where('active', 1)->get();
        return count($accounts);
    }

    public function deals()
    {
        $deals = Tbl_deals::where('active', 1)->get();
        return count($deals);
    }

    public function products()
    {
        $products = Tbl_products::where('active', 1)->get();
        return count($products);
    }

    public function documents()
    {
        $documents = Tbl_documents::where('active', 1)->get();
        return count($documents);
    }

    public function events()
    {
        $events = Tbl_events::where('active', 1)->get();
        return count($events);
    }

    public function invoices()
    {
        $invoices = Tbl_invoice::where('active', 1)->get();
        return count($invoices);
    }

    public function territory()
    {
        $territory = Tbl_territory::where('active', 1)->get();
        return count($territory);
    }

    public function customers()
    {
        $customers = Tbl_deals::where('active', 1)
            ->with(array('tbl_leads' => function ($query) {
                $query->where('tbl_leads.active', 1);
            }))
            ->where('deal_status', 1)
            ->orderBy('closing_date', 'desc')
            ->get();
        return count($customers);
    }

    public function sales()
    {
        $sales = Tbl_deals::where('active', 1)->with(array('tbl_leads' => function ($query) {
            $query->where('tbl_leads.active', 1);
        }))->where('deal_status', 1)
            ->orderBy('closing_date', 'desc')
            ->sum('value');
        return $sales;
    }

    public function cronjobs()
    {
        return 0;
    }

    public function editprofile()
    {
        $data['userdata'] = Auth::user('admin');
        $data['app_details'] = Tbl_Appdetails::first();
        return view('admin.profile.profile')->with('data', $data);   //profile
    }

    public function update()
    {
        $data['userdata'] = Auth::user('admin');
        return view('admin.profile.update')->with('data', $data);
    }

    public function adminUpdate(Request $request, $id)
    {
        $userdata = Admin::find($id);

        $this->validate($request, [
            'username' => 'required|max:255',
        ]);

        $filename = '';
        if ($request->hasfile('profilepicture')) {
            $file = $request->file('profilepicture');
            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $file->move('uploads/admin/', $name);
            $filename = '/uploads/admin/' . $name;
            $userdata->picture = $filename;

            //            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            //            $file->move('uploads/accounts/', $name);  //public_path().
            //            $filename = '/uploads/accounts/' . $name;
        }

        $userdata->name = $request->input('username');
        $userdata->save();
        return redirect('/admin/profile')->with('success', 'Updated Successfully...!');
    }

    public function getReportsYearData(Request $request)
    {
        $year = $request->input('year');
        return $this->getReportsYear($year);
    }

    public function getReportsYear($year)
    {

        $formstable = '<table id="" class="table">';
        $formstable .= '<thead>';
        $formstable .= '<tr>';
        $formstable .= '<th>Month</th>';
        $formstable .= '<th>Users</th>';
        $formstable .= '<th>Sub Users</th>';
        $formstable .= '<th>Web to Lead</th>';
        $formstable .= '<th>Forms</th>';
        $formstable .= '<th>Leads</th>';
        $formstable .= '<th>Accounts</th>';
        $formstable .= '<th>Contacts</th>';
        $formstable .= '<th>Deals</th>';
        $formstable .= '<th>Products</th>';
        $formstable .= '<th>Documents</th>';
        $formstable .= '<th>Events</th>';
        $formstable .= '<th>Invoices</th>';
        $formstable .= '</tr>';
        $formstable .= '</thead>';
        $formstable .= '<tbody>';
        for ($i = 1; $i <= 12; $i++) {

            $month = date("F", mktime(0, 0, 0, $i, 10));

            $users = User::where('user_type', 1)->where(DB::raw('MONTH(created_at)'), $i)->where(DB::raw('YEAR(created_at)'), $year)->count();
            $subusers = User::where('user_type', 0)->where(DB::raw('MONTH(created_at)'), $i)->where(DB::raw('YEAR(created_at)'), $year)->count();
            $forms = Tbl_forms::where('active', 1)->where(DB::raw('MONTH(created_at)'), $i)->where(DB::raw('YEAR(created_at)'), $year)->count();
            $formleads = Tbl_formleads::where('active', 1)->where(DB::raw('MONTH(created_at)'), $i)->where(DB::raw('YEAR(created_at)'), $year)->count();
            $leads = Tbl_leads::where('active', 1)->where(DB::raw('MONTH(created_at)'), $i)->where(DB::raw('YEAR(created_at)'), $year)->count();
            $accounts = Tbl_Accounts::where('active', 1)->where(DB::raw('MONTH(created_at)'), $i)->where(DB::raw('YEAR(created_at)'), $year)->count();
            $contacts = Tbl_contacts::where('active', 1)->where(DB::raw('MONTH(created_at)'), $i)->where(DB::raw('YEAR(created_at)'), $year)->count();
            $deals = Tbl_deals::where('active', 1)->where(DB::raw('MONTH(created_at)'), $i)->where(DB::raw('YEAR(created_at)'), $year)->count();
            $products = Tbl_products::where('active', 1)->where(DB::raw('MONTH(created_at)'), $i)->where(DB::raw('YEAR(created_at)'), $year)->count();
            $documents = Tbl_documents::where('active', 1)->where(DB::raw('MONTH(created_at)'), $i)->where(DB::raw('YEAR(created_at)'), $year)->count();
            $events = Tbl_events::where('active', 1)->where(DB::raw('MONTH(created_at)'), $i)->where(DB::raw('YEAR(created_at)'), $year)->count();
            $invoices = Tbl_invoice::where('active', 1)->where(DB::raw('MONTH(created_at)'), $i)->where(DB::raw('YEAR(created_at)'), $year)->count();


            $formstable .= '<tr>';
            $formstable .= '<td><b>' . $month . '</b></td>';
            $formstable .= '<td>' . $users . '</td>';
            $formstable .= '<td>' . $subusers . '</td>';
            $formstable .= '<td>' . $forms . '</td>';
            $formstable .= '<td>' . $formleads . '</td>';
            $formstable .= '<td>' . $leads . '</td>';
            $formstable .= '<td>' . $accounts . '</td>';
            $formstable .= '<td>' . $contacts . '</td>';
            $formstable .= '<td>' . $deals . '</td>';
            $formstable .= '<td>' . $products . '</td>';
            $formstable .= '<td>' . $documents . '</td>';
            $formstable .= '<td>' . $events . '</td>';
            $formstable .= '<td>' . $invoices . '</td>';
            $formstable .= '</tr>';
        }
        $formstable .= '</tbody>';
        $formstable .= '</table>';

        return $formstable;
    }

    public function userOptions()
    {

        $users = User::all();
        $userOptions = "<option value='All'>All</option>";
        foreach ($users as $user) {
            $userOptions .= "<option value='" . $user->id . "'>" . $user->name . "</option>";
        }

        return $userOptions;
    }

    public function getSalesFunnelD3(Request $request)
    {

        //        return json_encode($request->input());
        $time = $request->input('time');
        $uid = $request->input('uid');
        $data = $this->getSalesFunnelDataD3($time, $uid);
        return json_encode($data);
    }

    public function getSalesFunnelDataD3($time, $uid)
    {

        //        return $time . ' ' . $uid;
        //        exit(0);

        if ($uid == 'All') {
            $currency = currency::where('status', 1)->first();
        } else {
            $user = User::find($uid);
            $currency = currency::find($user->cr_id);
        }



        $color = ['#3c8dbc', '#00c0ef', '#f39c12', '#00a65a', '#f56954', '#27c6db'];
        $salesfunnel = Tbl_salesfunnel::all();
        $data = array();
        if ($time == 'day') {
            $date = date('Y-m-d');
            foreach ($salesfunnel as $index => $sfun) {

                if ($uid == 'All') {
                    $deals = Tbl_deals::where('uid', $uid)->where('sfun_id', $sfun->sfun_id)->where('active', 1)->where(DB::raw('DATE(updated_at)'), $date)->sum('value');
                } else {
                    $deals = Tbl_deals::where('sfun_id', $sfun->sfun_id)->where('active', 1)->where(DB::raw('DATE(updated_at)'), $date)->sum('value');
                }

                $dataset[] = array($sfun->salesfunnel, $deals, $color[$index]);

                if (strtolower($sfun->salesfunnel) == 'won') {
                    $data['wonAmount'] = 'Total Amount <span>&nbsp;' . $currency->html_code . '<span>' . $deals;
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

                if ($uid == 'All') {
                    $deals = Tbl_deals::where('sfun_id', $sfun->sfun_id)->where('active', 1)->whereBetween(DB::raw('DATE(updated_at)'), [$start, $end])->sum('value');
                } else {
                    $deals = Tbl_deals::where('uid', $uid)->where('sfun_id', $sfun->sfun_id)->where('active', 1)->whereBetween(DB::raw('DATE(updated_at)'), [$start, $end])->sum('value');
                }


                $dataset[] = array($sfun->salesfunnel, $deals, $color[$index]);

                if (strtolower($sfun->salesfunnel) == 'won') {
                    $data['wonAmount'] = 'Total Amount <span>&nbsp;' . $currency->html_code . '<span>' . $deals;
                }
            }
            $data['dataset'] = $dataset;
            $data['title'] = 'Deals of week ' . date('d/m/Y', strtotime($start)) . ' and ' . date('d/m/Y', strtotime($end));
        }
        if ($time == 'month') {
            $month = date('m');
            $year = date('Y');
            foreach ($salesfunnel as $index => $sfun) {

                if ($uid == 'All') {
                    $deals = Tbl_deals::where('sfun_id', $sfun->sfun_id)
                        ->where('active', 1)
                        ->where(DB::raw('MONTH(updated_at)'), $month)
                        ->where(DB::raw('YEAR(updated_at)'), $year)
                        ->sum('value');
                } else {
                    $deals = Tbl_deals::where('uid', $uid)
                        ->where('sfun_id', $sfun->sfun_id)
                        ->where('active', 1)
                        ->where(DB::raw('MONTH(updated_at)'), $month)
                        ->where(DB::raw('YEAR(updated_at)'), $year)
                        ->sum('value');
                }

                $dataset[] = array($sfun->salesfunnel, $deals, $color[$index]);

                if (strtolower($sfun->salesfunnel) == 'won') {
                    $data['wonAmount'] = 'Total Amount <span>&nbsp;' . $currency->html_code . '<span>' . $deals;
                }
            }
            $data['dataset'] = $dataset;
            $data['title'] = 'Deals of ' . date('M Y');
        }
        if ($time == 'year') {
            $date = date('Y');
            foreach ($salesfunnel as $index => $sfun) {

                if ($uid == 'All') {
                    $deals = Tbl_deals::where('sfun_id', $sfun->sfun_id)->where('active', 1)->where(DB::raw('YEAR(updated_at)'), $date)->sum('value');
                } else {
                    $deals = Tbl_deals::where('uid', $uid)->where('sfun_id', $sfun->sfun_id)->where('active', 1)->where(DB::raw('YEAR(updated_at)'), $date)->sum('value');
                }
                $dataset[] = array($sfun->salesfunnel, $deals, $color[$index]);

                if (strtolower($sfun->salesfunnel) == 'won') {
                    $data['wonAmount'] = 'Total Amount <span>&nbsp;' . $currency->html_code . '<span>' . $deals;
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
        $uid = $request->input('uid');
        $data = $this->getDealsStageData($time, $uid);
        return json_encode($data);
    }

    public function getDealsStageData($time, $uid)
    {

        if ($uid == 'All') {
            $currency = currency::where('status', 1)->first();
        } else {
            $user = User::find($uid);
            $currency = currency::find($user->cr_id);
        }

        //        $user = User::find($uid);
        //        $currency = currency::find($user->cr_id);
        $salesfunnel = Tbl_salesfunnel::all();
        $data = array();
        if ($time == 'day') {
            $date = date('Y-m-d');
            foreach ($salesfunnel as $sfun) {

                if ($uid == 'All') {
                    $deals = Tbl_deals::where('sfun_id', $sfun->sfun_id)->where('active', 1)->where(DB::raw('DATE(updated_at)'), $date)->count();
                } else {
                    $deals = Tbl_deals::where('uid', $uid)->where('sfun_id', $sfun->sfun_id)->where('active', 1)->where(DB::raw('DATE(updated_at)'), $date)->count();
                }

                $data['labels'][] = $sfun->salesfunnel;
                $data['dataset'][] = $deals;
            }
            $data['title'] = 'Deals of day ' . date('d-m-Y', strtotime($date));
        }
        if ($time == 'week') {

            $lastmonday = strtotime("last monday");
            $monday = date('w', $lastmonday) == date('w') ? $lastmonday + 7 * 86400 : $lastmonday;

            $sunday = strtotime(date("Y-m-d", $monday) . " +6 days");

            $start = date("Y-m-d", $monday);
            $end = date("Y-m-d", $sunday);

            foreach ($salesfunnel as $sfun) {

                if ($uid == 'All') {
                    $deals = Tbl_deals::where('sfun_id', $sfun->sfun_id)->where('active', 1)->whereBetween(DB::raw('DATE(updated_at)'), [$start, $end])->count();
                } else {
                    $deals = Tbl_deals::where('uid', $uid)->where('sfun_id', $sfun->sfun_id)->where('active', 1)->whereBetween(DB::raw('DATE(updated_at)'), [$start, $end])->count();
                }

                $data['labels'][] = $sfun->salesfunnel;
                $data['dataset'][] = $deals;
            }
            $data['title'] = 'Deals of week ' . date('d-m-Y', strtotime($start)) . ' and ' . date('d-m-Y', strtotime($end));
        }
        if ($time == 'month') {
            $date = date('m');
            foreach ($salesfunnel as $sfun) {

                if ($uid == 'All') {
                    $deals = Tbl_deals::where('sfun_id', $sfun->sfun_id)->where('active', 1)->where(DB::raw('MONTH(updated_at)'), $date)->count();
                } else {
                    $deals = Tbl_deals::where('uid', $uid)->where('sfun_id', $sfun->sfun_id)->where('active', 1)->where(DB::raw('MONTH(updated_at)'), $date)->count();
                }


                $data['labels'][] = $sfun->salesfunnel;
                $data['dataset'][] = $deals;
            }
            $data['title'] = 'Deals of month ' . date('M Y');
        }
        if ($time == 'year') {
            $date = date('Y');
            foreach ($salesfunnel as $sfun) {

                if ($uid == 'All') {
                    $deals = Tbl_deals::where('sfun_id', $sfun->sfun_id)->where('active', 1)->where(DB::raw('YEAR(updated_at)'), $date)->count();
                } else {
                    $deals = Tbl_deals::where('uid', $uid)->where('sfun_id', $sfun->sfun_id)->where('active', 1)->where(DB::raw('YEAR(updated_at)'), $date)->count();
                }

                $data['labels'][] = $sfun->salesfunnel;
                $data['dataset'][] = $deals;
            }
            $data['title'] = 'Deals of year ' . date('Y', strtotime($date));
        }

        return $data;
    }

    public function getLeadsData(Request $request)
    {
        $time = $request->input('time');
        $uid = $request->input('uid');
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

    public function getExtentions()
    {
        return view('admin.extentions.index');
    }

    public function adminlte()
    {

        $yearOptions = '';
        for ($i = 0; $i < 12; $i++) {
            $year = date('Y', strtotime('-' . $i . ' years'));
            $yearOptions .= '<option value="' . $year . '">' . $year . '</option>';
        }
        $data['yearData'] = $this->getReportsYear(date('Y'));
        $data['yearOptions'] = $yearOptions;
        $data['users'] = $this->users();
        $data['subusers'] = $this->subusers();
        $data['forms'] = $this->forms();
        $data['webtolead'] = $this->webtolead();
        $data['leads'] = $this->leads();
        $data['accounts'] = $this->accounts();
        $data['contacts'] = $this->contacts();
        $data['deals'] = $this->deals();
        $data['products'] = $this->products();
        $data['documents'] = $this->documents();
        $data['events'] = $this->events();
        $data['invoices'] = $this->invoices();
        $data['customers'] = $this->customers();
        $data['sales'] = $this->sales();
        $data['territory'] = $this->territory();
        $data['cronjobs'] = $this->cronjobs();

        $data['userOptions'] = $this->userOptions();

        $salesstage = $this->salesstageOptions();

        $data['default_salesfunnel'] = $salesstage['default_salesfunnel'];
        $data['salesfunnelOptions'] = $salesstage['salesfunnelOptions'];


        return view('admin.adminlte-boot4')->with('data', $data);
    }

    public function cronjob()
    {
        return view('admin.cronjob.index');
    }

    public function indexAdmin()
    {
        $users = Admin::where('user_type', '3')->orderBy('id', 'desc')->get();
        // echo json_encode($users);
        // exit();

        $total = count($users);

        if (count($users) > 0) {
            $usertable = '<table id="usersTable" class="table">';
            $usertable .= '<thead>';
            $usertable .= '<tr>';
            $usertable .= '<th>Name</th>';
            $usertable .= '<th>Email</th>';
            $usertable .= '<th>Mobile</th>';
            $usertable .= '<th>Status</th>';
            $usertable .= '<th>Email Verification</th>';
            $usertable .= '<th>Created</th>';
            $usertable .= '<th>Action</th>';
            $usertable .= '<th>Permissions</th>';
            $usertable .= '</tr>';
            $usertable .= '</thead>';
            $usertable .= '<tbody>';
            foreach ($users as $userdetails) {
                $status = ($userdetails->active == 1) ? "Active" : "Blocked";
                $btnstatus = ($userdetails->active == 1) ? "Block" : "Active";
                $bstatus = ($userdetails->active == 0) ? 1 : 0;
                // $jobTitle = ($userdetails->tbl_user_types != '') ? $userdetails->tbl_user_types->type : 'Not Assigned';


                // echo (($userdetails->role != '') ? 'Yes' : 'No') . '<br>';
                // $role = '';
                // if ($userdetails->role != '') {
                //     $role = 'Yes';
                // } else {
                //     $role = '<a href="' . url('admin/users/role/add/' . $userdetails->id) . '">Add Role</a>';
                // }



                // $leads = ($userdetails->tbl_leads != null) ? count($userdetails->tbl_leads) : 0;

                $emailverification = ($userdetails->verified == 1) ? '<small class="text-success badge badge-success"><i class="fa fa-check" aria-hidden="true"></i> Verivified</small>' : '<small class="text-danger badge badge-danger"><i class="fa fa-times" aria-hidden="true"></i> Not Verivified</small>';

                $userimg = ($userdetails->picture != '') ? $userdetails->picture : 'uploads/default/user.png';

                $usertable .= '<tr>';
                $usertable .= '<td><a href="' . url('admin/admins/show/' . $userdetails->id) . '"><img src="' . url($userimg) . '" class="avatar" style="width:25px; height:25px;">' . $userdetails->name . '</a>&nbsp;</td>';
                $usertable .= '<td><a href="' . url('admin/mails/mailsend/admins/' . $userdetails->id) . '">' . $userdetails->email . '</a></td>';
                $usertable .= '<td>' . $userdetails->mobile . '</td>';
                // $usertable .= '<td>' . $jobTitle . '</td>';
                $usertable .= '<td>' . $status . '</td>';
                $usertable .= '<td>' . $emailverification . '</td>';
                $usertable .= '<td>' . date('d-m-Y', strtotime($userdetails->created_at)) . '</td>';
                $usertable .= '<td>';
                $usertable .= '<div class="btn-group">
                      <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                  </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item text-default text-btn-space" href="' . url('admin/admins/edit/' . $userdetails->id) . '">Edit</a>
                        <a class="dropdown-item text-default text-btn-space" href="' . url('admin/admins/block/' . $userdetails->id . '/' . $bstatus) . '" onclick="event.preventDefault(); document.getElementById("block-form").submit();">' . $btnstatus . '</a>
                        <form id="block-form" action="' . url('admin/admins/block/' . $userdetails->id . '/' . $bstatus) . '" method="POST" style="display: none;">
                            @csrf
                        </form>
                      </div>
                    </div>';
                $usertable .= '</td>';
                $usertable .= '<td><a href="' . url('admin/admins/setpermit/' . $userdetails->id) . '" class="btn btn-default">Set Permissions</a></td>';
                $usertable .= '</tr>';
            }
            $usertable .= '</tbody>';
            $usertable .= '</table>';
        } else {
            $usertable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $usertable;

        return view('admin.admins.index')->with('data', $data);
    }

    public function createAdmin()
    {
        return view('admin.admins.create');
    }

    public function storeAdmin(Request $request)
    {
        // echo json_encode($request->input());
        // exit();

        $this->validate($request, [
            'username' => 'required|max:255',
            'email' => 'required|email|max:255|unique:admins',
            'userpass' => 'required|max:255',
        ]);

        $currency = currency::where('status', 1)->first();


        $formdata = array(
            'name' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('userpass')),
            'user_type' => 3,
            'cr_id' => $currency->cr_id,
        );

        // echo json_encode($formdata);
        // exit();

        $user = Admin::create($formdata);
        $uid = $user->id;

        if ($uid > 0) {

            $verifyUser = Tbl_Verifyadmin::create([
                'admin_id' => $uid,
                'token' => strtotime(date('Ymdhis'))
            ]);

            $this->registrationMailUser($user);

            // $from_mail = Auth::user()->email;
            // $to_mail = $formdata['email'];
            // $title = 'Digital CRM';
            // $content = 'You have Registerd Successfully ';
            // Mail::send('mail', ['title' => $title, 'content' => $content], function ($message) use ($from_mail, $to_mail) {
            //     $message->subject('Registered Successfully');   //'sandeepindana@yahoo.com'
            //     $message->from($from_mail, 'Administrator');   //'sandeepindana@yahoo.com'
            //     $message->to($to_mail);   //'isandeep.1609@gmail.com'
            // });


            return redirect('admin/admins')->with('success', 'Created Successfully...!');
        } else {
            return redirect('admin/admins')->with('error', 'Error occurred. Please try again...!');
        }
    }

    public function showAdmin($id)
    {
        $adminDetails = Admin::find($id);
        // echo json_encode($adminDetails);
        $data['userdata'] = $adminDetails;
        return view('admin.admins.show')->with('data', $data);
    }

    public function editAdmin($id)
    {
        $adminDetails = Admin::find($id);
        echo json_encode($adminDetails);
        // $data['userdata'] = $adminDetails;
        // return view('admin.admins.edit')->with('data', $data);
    }

    public function updateAdmin(Request $request, $id)
    {
        echo json_encode($request->input());
        exit();
    }


    public function setPermission($id)
    {
        $userdata = Admin::find($id);
        $data['userdata'] = $userdata;
        // echo json_encode($userdata);
        // exit();

        $permissions = Tbl_Permissions::all();
        // echo json_encode($permissions);
        // exit();
        $table = "<table class='table table-striped table-bordered'>";
        $k = 1;
        foreach ($permissions as $permission) {


            $ext_permits = Tbl_Admin_Permissions::where('admin_id', $id)->where('permission_id', $permission->permission_id)->first();
            $selected = ($ext_permits != '') ? 'checked' : 'noo';

            // echo $selected;
            // echo "<br>";

            if ($k == 1) {
                $table .= "<tr>";
            }

            $table .= "<td><label><input type='checkbox' class='permissions' value='" . $permission->permission_id . "' name='permissions[]' id='" . $permission->id . "' " . $selected . ">&nbsp;" . $permission->name . "</label></td>";
            if ($k == 6) {
                $table .= "</tr>";
                $k = 1;
            } else {
                $k++;
            }
        }

        // exit();

        $table .= "</table>";
        $data['table'] = $table;

        return view('admin.admins.permit')->with('data', $data);
    }

    public function storePermission(Request $request, $id)
    {
        // echo json_encode($request->input());
        // exit();

        $permits = $request->input('permissions');

        $ext_permits = Tbl_Admin_Permissions::where('admin_id', $id)->get();
        $del = '';
        if (count($ext_permits) > 0) {
            $del = Tbl_Admin_Permissions::where('admin_id', $id)->delete();
        }

        $pt = array();

        if (count($permits) > 0) {


            foreach ($permits as $permit) {
                $pt[] = array(
                    'permission_id' => $permit,
                    'admin_id' => $id
                );

                // print_r($permit);
                // exit();
            }
            $ins = Tbl_Admin_Permissions::insert($pt);

            if ($ins) {
                return redirect('/admin/admins')->with('success', 'Updated Successfully...!');
            } else {
                return redirect('/admin/admins')->with('error', 'Error occurred. Please try later...');
            }
        }
        if ($del) {
            return redirect('/admin/admins')->with('success', 'Updated Successfully...!');
        } else {
            return redirect('/admin/admins')->with('error', 'Error occurred. Please try later...');
        }
    }

    public function block($id, $bstatus)
    {
        $userdata = Admin::find($id);
        // echo json_encode($userdata);
        // exit();

        $userdata->active = $bstatus;
        $blockRes = $userdata->save();

        if ($blockRes) {
            if ($bstatus == 1) {

                $from = Auth::user()->email;

                $subject = "Account activated successfully";
                $to = $userdata->email;
                $title = "Digital CRM";
                $message = "Your account has been activated sucessfully. Please click <a href='" . url('/admin') . "'>here</a> to login.";
                $mailObj = new MailController();
                $mailObj->sendMail($from, $to, $message, $subject, $title);
            }
        }

        return redirect('admin/admins')->with('success', 'Updated Successfully...!');
    }


    public function registrationMailUser($data)
    {

        $userdetails = Admin::find($data->id);

        $tbl_Verifyadmin = Tbl_Verifyadmin::where('admin_id', $data->id)->first();

        $department = Tbl_emailcategory::where('category', 'Registration')->first();
        $template = Tbl_emailtemplates::where('ecat_id', $department->ecat_id)->first();
        $emails = Tbl_emails::where('ecat_id', $department->ecat_id)->first();

        $subject = $template->subject;
        $user = $data->name;
        $click = '<a href="' . url('/admin/verify/' . $tbl_Verifyadmin->token) . '">click</a>';
        $title = 'Digital CRM';
        $message = $template->message;
        $from_mail = $emails->mail; //'aynsoft@digitalcrm.com'
        $to_email = $data->email;

        $beforeStr = $message;
        preg_match_all('/{(\w+)}/', $beforeStr, $matches);
        $afterStr = $beforeStr;
        foreach ($matches[0] as $index => $var_name) {
            if (isset(${$matches[1][$index]})) {
                $afterStr = str_replace($var_name, ${$matches[1][$index]}, $afterStr);
            }
        }
        $content = $afterStr;

        $mailObj = new MailController();
        $mailres = $mailObj->sendMail($from_mail, $to_email, $content, $subject, $title);

        return $mailres;
    }


    public function editApp()
    {
        $appdetails = Tbl_Appdetails::firstOrFail();
        // echo json_encode($appdetails);

        return view('admin.profile.editapp')->with('data', $appdetails);
    }

    public function updateApp(Request $request, $id)
    {
        // echo json_encode($request->input());
        // exit();

        // $appdetails = Tbl_Appdetails::first();

        $this->validate($request, [
            'app_name' => 'required|max:255',
        ]);

        $formdata = array();

        $filename = '';
        if ($request->hasfile('app_picture')) {
            $file = $request->file('app_picture');
            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $file->move('uploads/app/', $name);
            $filename = '/uploads/app/' . $name;
            // $appdetails->app_picture = $filename;
            $formdata['app_picture'] = $filename;
        }

        $formdata['app_name'] =  $request->input('app_name');

        // echo json_encode($formdata);
        // exit();

        $res = Tbl_Appdetails::where('app_id', $id)->update($formdata);
        if ($res) {
            return redirect('/admin/profile')->with('success', 'Updated Successfully...!');
        } else {
            return redirect('/admin/profile')->with('error', 'Failed. Try again later...!');
        }
    }

    public function getAdminInbox()
    {

        // $oClient = new Client([
        //     'host'          => 'mailtrap.io',
        //     'port'          => 993,
        //     'encryption'    => 'ssl',
        //     'validate_cert' => true,
        //     'username'      => '31984197b3bea0',
        //     'password'      => '81ad2a06f4e8d4',
        //     'protocol'      => 'imap'
        // ]);

        $oClient = Client::account('default');

        //Connect to the IMAP Server
        $oClient->connect();

        //Get all Mailboxes
        /** @var \Webklex\IMAP\Support\FolderCollection $aFolder */
        $aFolder = $oClient->getFolders();

        //Loop through every Mailbox
        /** @var \Webklex\IMAP\Folder $oFolder */
        foreach ($aFolder as $oFolder) {

            //Get all Messages of the current Mailbox $oFolder
            /** @var \Webklex\IMAP\Support\MessageCollection $aMessage */
            $aMessage = $oFolder->messages()->all()->get();

            /** @var \Webklex\IMAP\Message $oMessage */
            foreach ($aMessage as $oMessage) {
                echo $oMessage->getSubject() . '<br />';
                echo 'Attachments: ' . $oMessage->getAttachments()->count() . '<br />';
                echo $oMessage->getHTMLBody(true);

                //Move the current Message to 'INBOX.read'
                if ($oMessage->moveToFolder('INBOX.read') == true) {
                    echo 'Message has ben moved';
                } else {
                    echo 'Message could not be moved';
                }
            }
        }
    }
}

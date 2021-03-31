<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Auth;
use Illuminate\Http\Request;
use App\User;
use App\currency;
use App\Tbl_forms;
use App\Tbl_formleads;
use App\Tbl_Accounts;
use App\Tbl_contacts;
use App\Tbl_leads;
use App\Tbl_deals;
use App\Tbl_salesfunnel;
use App\Tbl_territory;
use App\Tbl_territory_users;
use App\tbl_verifyuser;
//------------------Controllers------------
//----------
use App\Http\Controllers\MailController;

class SubuserController extends Controller
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
        if (!Gate::allows('isUser')) {
            abort(404, 'Sorry, Page not found..!');
        } else {
            $uid = Auth::user()->id;
            $data = $this->getSubusers($uid);

            return view('auth.subusers.index')->with('data', $data);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.subusers.create');
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
        $data = $request->input();
        $uid = Auth::user()->id;
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'user' => $uid,
            'user_type' => 0,
        ]);

        $verifyUser = tbl_verifyuser::create([
            'uid' => $user->id,
            'token' => strtotime(date('Ymdhis'))
        ]);


        if ($user->id > 0) {
            $mail = new MailController();
            $mail->registrationMailSubUser($user);

            return redirect('/subusers')->with('success', 'User Created Successfully');
        } else {
            return redirect('/subusers')->with('error', 'Error occurred. Please try again...!');
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
        $user = User::find($id);
        echo json_encode($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $user = User::with(array('tbl_forms' => function ($query) {
            $query->where('tbl_forms.active', 1);
        }))->with(array('tbl_formleads' => function ($query) {
            $query->where('tbl_formleads.active', 1);
        }))->with(array('tbl_leads' => function ($query) {
            $query->where('tbl_leads.active', 1);
        }))->with(array('tbl_accounts' => function ($query) {
            $query->where('tbl_accounts.active', 1);
        }))->with(array('tbl_contacts' => function ($query) {
            $query->where('tbl_contacts.active', 1);
        }))->with(array('tbl_deals' => function ($query) {
            $query->where('tbl_deals.active', 1);
        }))->find($id);
        $currency = currency::find($user->cr_id);
        //        echo json_encode($user);
        $data['user'] = $user;
        $data['currency'] = $currency;
        $data['customers'] = Tbl_deals::where('active', 1)
            ->with(array('tbl_leads' => function ($query) {
                $query->where('tbl_leads.active', 1);
            }))
            ->where('uid', $id)
            ->where('deal_status', 1)
            ->count();
        $data['sales'] = Tbl_deals::with(array('tbl_leads' => function ($query) {
            $query->where('tbl_leads.active', 1);
        }))
            ->where('uid', $id)
            ->where('deal_status', 1)
            ->sum('value');
        return view('auth.subusers.view')->with('data', $data);
    }

    public function formleads($id)
    {
        $formleads = Tbl_formleads::orderBy('fl_id', 'desc')->where('uid', $id)->with('tbl_forms')->get();
        $total = count($formleads);
        if ($total > 0) {
            $formstable = '<table id="latestformleadsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Form</th>';
            $formstable .= '<th>Email</th>';
            $formstable .= '<th>Mobile</th>';
            $formstable .= '<th>Website</th>';
            //            $formstable .= '<th>Addto lead</th>';
            $formstable .= '<th>Created</th>';
            //            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($formleads as $formdetails) {

                $form_id = $formdetails->tbl_forms->form_id;
                $formstable .= '<tr>';
                $formstable .= '<td>' . $formdetails->first_name . '&nbsp;</td>';
                $formstable .= '<td>' . $formdetails->tbl_forms->post_url . '</td>';
                $formstable .= '<td>' . $formdetails->email . '</td>';
                $formstable .= '<td>' . $formdetails->mobile . '</td>';
                $formstable .= '<td>' . $formdetails->website . '</td>';
                $formstable .= '<td>' . date('d-m-Y h:i', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;
        $data['user'] = $id;

        return view('auth.subusers.webtolead.formleads')->with("data", $data);
    }

    public function forms($id)
    {
        $forms = Tbl_forms::where('uid', $id)->orderBy('form_id', 'desc')->get();
        $total = count($forms);
        if ($total > 0) {
            $formstable = '<table id="formsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Title</th>';
            $formstable .= '<th>Website</th>';
            $formstable .= '<th>Views</th>';
            $formstable .= '<th>Contacts</th>';
            $formstable .= '<th>Conversion Rate</th>';
            $formstable .= '<th>Created</th>';
            //            $formstable .= '<th>Preview</th>';
            //            $formstable .= '<th>Embed Code</th>';
            //            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($forms as $formdetails) {

                $formLeads = Tbl_formleads::where('form_id', $formdetails->form_id)->get();

                $formstable .= '<tr>';
                $formstable .= '<td class="table-title">' . $formdetails->title . '&nbsp;</td>';
                $formstable .= '<td>' . $formdetails->post_url . '</td>';
                $formstable .= '<td>' . $formdetails->views . '</td>';
                $formstable .= '<td>' . count($formLeads) . '</td>';
                $formstable .= '<td>0</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;
        $data['user'] = $id;

        return view('auth.subusers.webtolead.index')->with("data", $data);
    }

    public function accounts($id)
    {
        $accounts = Tbl_Accounts::with('Tbl_industrytypes')
            ->with('Tbl_accounttypes')
            ->where('uid', $id)->where('active', 1)->get();
        //        echo json_encode($accounts);
        $total = count($accounts);
        if ($total > 0) {
            $formstable = '<table id="accountsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Email</th>';
            $formstable .= '<th>Mobile</th>';
            $formstable .= '<th>Industry</th>';
            $formstable .= '<th>Date</th>';
            //            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($accounts as $formdetails) {

                $accountimage = ($formdetails->picture != '') ? $formdetails->picture : '/uploads/default/accounts.png';

                $formstable .= '<tr>';
                $formstable .= '<td class="table-title"><img src="' . url($accountimage) . '" class="avatar"><a href="' . url('accounts/' . $formdetails->acc_id) . '">' . $formdetails->name . '</a>&nbsp;</td>';
                $formstable .= '<td>' . $formdetails->email . '</td>';
                $formstable .= '<td>' . $formdetails->mobile . '</td>';
                $industrytype = ($formdetails->tbl_industrytypes != null) ? $formdetails->tbl_industrytypes->type : '';
                $formstable .= '<td>' . $industrytype . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;
        $data['user'] = $id;

        return view('auth.subusers.accounts.index')->with("data", $data);
    }

    public function contacts($id)
    {
        $contacts = Tbl_contacts::where('uid', $id)
            ->with('Tbl_Accounts')
            ->with('Tbl_leadsource')
            ->with('Tbl_countries')
            ->with('Tbl_states')
            ->get();
        //        echo json_encode($contacts);
        //        exit(0);
        $total = count($contacts);


        if ($total > 0) {
            $formstable = '<table id="contactsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Email</th>';
            $formstable .= '<th>Mobile</th>';
            $formstable .= '<th>Website</th>';
            $formstable .= '<th>Date</th>';
            //            $formstable .= '<th>Add to Leads</th>';
            //            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($contacts as $formdetails) {
                $customer_status = '';
                $ld_id = 0;
                if ($formdetails->ld_id > 0) {
                    $ld_id = $formdetails->ld_id;
                    $customer = Tbl_deals::where('ld_id', $ld_id)->where('deal_status', 1)->first();
                    if ($customer != null) {
                        $customer_status = '<span class="label label-success">Customer</span>';
                    }
                }

                $contactimage = ($formdetails->picture != '') ? $formdetails->picture : '/uploads/default/contacts.png';

                $formstable .= '<tr>';
                $formstable .= '<td class="table-title"><img src="' . url($contactimage) . '" class="avatar"><a href="' . url('contacts/' . $formdetails->cnt_id) . '">' . $formdetails->first_name . ' ' . $formdetails->last_name . '</a>&nbsp; ' . $customer_status . '</td>';
                $formstable .= '<td>' . $formdetails->email . '</td>';
                $formstable .= '<td>' . $formdetails->mobile . '</td>';
                $formstable .= '<td>' . $formdetails->website . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';

                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;
        $data['user'] = $id;

        return view('auth.subusers.contacts.index')->with("data", $data);
    }

    public function leads($id)
    {
        //        echo "Under Development";
        $leads = Tbl_leads::where('uid', $id)->with('tbl_leadsource')->get();
        $total = count($leads);
        if ($total > 0) {
            $formstable = '<table id="leadsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Deal Stage</th>';
            $formstable .= '<th>Email</th>';
            $formstable .= '<th>Mobile</th>';
            $formstable .= '<th>Lead Source</th>';
            $formstable .= '<th>Website</th>';
            $formstable .= '<th>Date</th>';
            //            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($leads as $formdetails) {
                $customer_status = '';
                $customer = Tbl_deals::where('ld_id', $formdetails->ld_id)->where('deal_status', 1)->first();
                if ($customer != null) {
                    $customer_status = '<span class="label label-success">Customer</span>';
                }
                $dealstages = Tbl_deals::where('ld_id', $formdetails->ld_id)->whereIn('sfun_id', [1, 2, 3, 4])->orderBy('deal_id', 'desc')->with('tbl_salesfunnel')->first();
                $dealstage = ($dealstages != null) ? $dealstages->tbl_salesfunnel->salesfunnel : '';

                $leadsource = ($formdetails->tbl_leadsource != null) ? $formdetails->tbl_leadsource->leadsource : '';

                $leadimage = ($formdetails->picture != '') ? $formdetails->picture : '/uploads/default/leads.png';

                $formstable .= '<tr>';
                $formstable .= '<td class="table-title"><img src="' . url($leadimage) . '" class="avatar""><a href="' . url('leads/' . $formdetails->ld_id) . '">' . $formdetails->first_name . ' ' . $formdetails->last_name . '</a>&nbsp; ' . $customer_status . '</td>';
                $formstable .= '<td>' . $dealstage . '</td>';
                $formstable .= '<td>' . $formdetails->email . '</td>';
                $formstable .= '<td>' . $formdetails->mobile . '</td>';
                $formstable .= '<td>' . $leadsource . '</td>';
                $formstable .= '<td>' . $formdetails->website . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                //                $formstable .= '<td>';
                //                $formstable .= '
                //                    <div class="btn-group">
                //                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                //                    <span class="caret"></span>
                //                    <span class="sr-only">Toggle Dropdown</span>
                //                  </button>
                //                  <ul class="dropdown-menu" role="menu">
                //                    <li><a class="text-default text-btn-space" href="' . url('leads/' . $formdetails->ld_id . '/edit') . '">Edit</a></li>
                //                    <li><a class="text-default text-btn-space" href="' . url('leads/delete/' . $formdetails->ld_id) . '">Delete</a></li>
                //                  </ul>
                //                </div>';
                //                $formstable .= '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;
        $data['user'] = $id;

        return view('auth.subusers.leads.index')->with('data', $data);
    }

    public function deals($id)
    {
        $userDetails = User::find($id);
        $cr_id = $userDetails->cr_id;
        $currency = currency::find($cr_id);

        $deals = Tbl_deals::where('uid', $id)
            ->with('Tbl_leads')
            ->with('Tbl_leadsource')
            ->with('Tbl_salesfunnel')
            ->get()->toArray();

        $dealstage = Tbl_salesfunnel::all();

        $total = count($deals);
        if ($total > 0) {
            $formstable = '<table id="dealsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Deal Name</th>';
            $formstable .= '<th>Deal Stage</th>';
            $formstable .= '<th>Lead</th>';
            $formstable .= '<th>Amount<span>&nbsp;(' . $currency->html_code . ')</span></th>';
            $formstable .= '<th>Closing Date</th>';
            //            $formstable .= '<th>Change Deal Stage</th>';
            //            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($deals as $formdetails) {
                $formstable .= '<tr>';

                $leadimage = ($formdetails['tbl_leads']['picture'] != '') ? $formdetails['tbl_leads']['picture'] : '/uploads/default/leads.png';

                $formstable .= '<td class="table-title"><a href="' . url('deals/' . $formdetails['deal_id']) . '">' . $formdetails['name'] . '</a></td>';
                $formstable .= '<td>' . $formdetails['tbl_salesfunnel']['salesfunnel'] . '</td>';
                $formstable .= '<td><img src="' . url($leadimage) . '" class="avatar"">' . $formdetails['tbl_leads']['first_name'] . ' ' . $formdetails['tbl_leads']['last_name'] . '</td>';
                $formstable .= '<td>' . $formdetails['value'] . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails['closing_date'])) . '</td>';

                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;
        $data['user'] = $id;

        return view('auth.subusers.deals.index')->with("data", $data);
    }

    public function customers($id)
    {
        $userDetails = User::find($id);
        $cr_id = $userDetails->cr_id;
        $currency = currency::find($cr_id);

        $deals = Tbl_deals::with('Tbl_salesfunnel')
            ->with('Tbl_leads')
            ->where('uid', $id)
            ->where('deal_status', 1)
            ->orderBy('closing_date', 'desc')
            ->get();
        //        echo json_encode($deals);
        $total = count($deals);
        if ($total > 0) {
            $formstable = '<table id="customersTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Lead</th>';
            $formstable .= '<th>Deal Name</th>';
            $formstable .= '<th>Amount<span>&nbsp;(' . $currency->html_code . ')</span></th>';
            $formstable .= '<th>Closing Date</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($deals as $formdetails) {

                $leadimage = ($formdetails['tbl_leads']['picture'] != '') ? $formdetails['tbl_leads']['picture'] : '/uploads/default/leads.png';

                $formstable .= '<tr>';
                $formstable .= '<td><img src="' . url($leadimage) . '" class="avatar">' . $formdetails['tbl_leads']['first_name'] . ' ' . $formdetails['tbl_leads']['last_name'] . '</td>';
                $formstable .= '<td class="table-title">' . $formdetails['name'] . '</td>';
                $formstable .= '<td>' . $formdetails['value'] . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails['closing_date'])) . '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;
        $data['user'] = $id;

        return view('auth.subusers.customers.index')->with("data", $data);
    }

    public function sales($id)
    {
        $userDetails = User::find($id);
        $cr_id = $userDetails->cr_id;
        $currency = currency::find($cr_id);

        $deals = Tbl_deals::with('Tbl_salesfunnel')
            ->with('Tbl_leads')
            ->where('uid', $id)
            ->where('deal_status', 1)
            ->orderBy('closing_date', 'desc')
            ->get();
        //        echo json_encode($deals);

        if (count($deals) > 0) {
            $formstable = '<table id="salesTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Amount<span>&nbsp;(' . $currency->html_code . ')</span></th>';
            $formstable .= '<th>Lead</th>';
            $formstable .= '<th>Deal Name</th>';
            $formstable .= '<th>Closing Date</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($deals as $formdetails) {

                $leadimage = ($formdetails['tbl_leads']['picture'] != '') ? $formdetails['tbl_leads']['picture'] : '/uploads/default/leads.png';

                $formstable .= '<tr>';
                $formstable .= '<td>' . $formdetails['value'] . '</td>';
                $formstable .= '<td><img src="' . url($leadimage) . '" class="avatar">' . $formdetails['tbl_leads']['first_name'] . ' ' . $formdetails['tbl_leads']['last_name'] . '</td>';
                $formstable .= '<td class="table-title">' . $formdetails['name'] . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails['closing_date'])) . '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }
        $data['total'] = count($deals);
        $data['table'] = $formstable;
        $data['user'] = $id;

        return view('auth.subusers.sales.index')->with("data", $data);
    }

    public function getSubusers($uid)
    {
        $users = User::where('user', $uid)->orderBy('id', 'desc')->get();
        //        echo json_encode($users);
        //        exit(0);
        $total = count($users);
        if ($total > 0) {
            $table = '<table id="subusersTable" class="table">';
            $table .= '<thead>';
            $table .= '<tr>';
            $table .= '<th>Name</th>';
            $table .= '<th>Email</th>';
            $table .= '<th>Job Title</th>';
            $table .= '<th>Territory</th>';
            $table .= '<th>Date</th>';
            $table .= '<th>Action</th>';
            $table .= '</tr>';
            $table .= '</thead>';
            $table .= '<tbody>';
            foreach ($users as $user) {
                $tuserlist = '';

                $territorylist = Tbl_territory_users::where('suid', $user->id)->get();

                foreach ($territorylist as $tlist) {
                    $territory = Tbl_territory::find($tlist->tid);
                    $tuserlist .= $territory->name . ', ';
                }

                $userimage = ($user->picture != '') ? $user->picture : 'uploads/default/user.png';

                $table .= '<tr>';
                $table .= '<td><img src="' . url($userimage) . '" class="avatar"><a href="' . url('/subusers/view/' . $user->id) . '">' . $user->name . '</a></td>';
                $table .= '<td><a href="' . url('mails/mailsend/subusers/' . $user->id) . '">' . $user->email . '</a></td>';
                $table .= '<td>' . $user->jobtitle . '</td>';
                $table .= '<td>' . trim(substr($tuserlist, 0, 25), ', ') . '</td>';
                $table .= '<td>' . date('d-m-Y', strtotime($user->created_at)) . '</td>';
                $table .= '<td>';
                $table .= '<div class="btn-group">';
                $table .= '<button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">';
                $table .= '<span class="caret"></span>';
                $table .= '</button>';
                $table .= '<div class="dropdown-menu">';
                $table .= '<a class="dropdown-item" href="#">Delete</a>';
                $table .= '</div>';
                $table .= '</div>';
                $table .= '</td>';
                $table .= '</tr>';
            }
            $table .= '</tbody>';
            $table .= '</table>';
        } else {
            $table = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $table;
        return $data;
    }

    //                <div class="btn-group">
    //                          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    //                            <span class="caret"></span>
    //                          </button>
    //                          <div class="dropdown-menu">
    //                            <a class="dropdown-item" href="#">Dropdown link</a>
    //                            <a class="dropdown-item" href="#">Dropdown link</a>
    //                          </div>
    //                        </div>
}

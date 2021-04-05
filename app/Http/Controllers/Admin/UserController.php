<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Mail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

// Models
use App\User;
use App\currency;
use App\Tbl_leads;
use App\Tbl_user_types;
use App\Tbl_projects;
use App\Tbl_features;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// Mail Controller
use App\Http\Controllers\Admin\MailController;

class UserController extends Controller
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
        //        $users = DB::table('users')
        //                ->where('user_type', 1)
        //                ->with('Tbl_leads')
        //                ->orderBy('id', 'desc')
        //                ->get();
        //with('Tbl_leads')->
        $users = User::with('Tbl_user_types')
            ->orderBy('id', 'desc')
            ->paginate(15);

        // echo json_encode($users);
        // exit();

        //where('user_type', 1)->


        $total = $users->total();

        if (count($users) > 0) {
            $usertable = '<table id="usersTable" class="table">';
            $usertable .= '<thead>';
            $usertable .= '<tr>';
            $usertable .= '<th>Name</th>';
            $usertable .= '<th>Email</th>';
            $usertable .= '<th>Mobile</th>';
            $usertable .= '<th>Job Title</th>';
            // $usertable .= '<th>Lead Quota</th>';
            $usertable .= '<th>Status</th>';
            $usertable .= '<th>Email Verification</th>';
            // $usertable .= '<th>Leads</th>';
            //            $usertable .= '<th>Sub Users</th>';
            $usertable .= '<th>Created</th>';
            // $usertable .= '<th>Role</th>';
            $usertable .= '<th>Action</th>';
            $usertable .= '<th>Set Features</th>';
            $usertable .= '</tr>';
            $usertable .= '</thead>';
            $usertable .= '<tbody>';
            foreach ($users as $userdetails) {
                $status = ($userdetails->active == 1) ? "Active" : "Blocked";
                $btnstatus = ($userdetails->active == 1) ? "Block" : "Active";
                $bstatus = ($userdetails->active == 0) ? 1 : 0;
                $jobTitle = ($userdetails->tbl_user_types != '') ? $userdetails->tbl_user_types->type : 'Not Assigned';


                // echo (($userdetails->role != '') ? 'Yes' : 'No') . '<br>';
                // $role = '';
                // if ($userdetails->role != '') {
                //     $role = 'Yes';
                // } else {
                //     $role = '<a href="' . url('admin/users/role/add/' . $userdetails->id) . '">Add Role</a>';
                // }



                $leads = ($userdetails->tbl_leads != null) ? count($userdetails->tbl_leads) : 0;

                //                $subusers = User::where('user', $userdetails->id)->get();

                $emailverification = ($userdetails->email_verified_at !== null) ? '<small class="badge badge-success"><i class="fa fa-check" aria-hidden="true"></i> Verified</small>' : '<small class="badge badge-danger"><i class="fa fa-times" aria-hidden="true"></i> Not Verivified</small>';

                $userimg = $userdetails->profile_img;

                $usertable .= '<tr>';
                $usertable .= '<td><a href="' . url('admin/users/' . $userdetails->id) . '">
                <img src="' . $userimg . '" class="avatar" style="width:25px; height:25px;">' . $userdetails->name . '</a>&nbsp;</td>';
                $usertable .= '<td><a href="' . url('admin/mails/mailsend/users/' . $userdetails->id) . '">' . $userdetails->email . '</a></td>';
                $usertable .= '<td>' . $userdetails->mobile . '</td>';
                $usertable .= '<td>' . $jobTitle . '</td>';
                // $usertable .= '<td>' . $userdetails->quota . ' %' . '</td>';
                $usertable .= '<td>' . $status . '</td>';
                $usertable .= '<td>' . $emailverification . '</td>';
                //                $usertable .= '<td>' . count($subusers) . '</td>';
                // $usertable .= '<td>' . $leads . '</td>';
                $usertable .= '<td>' . date('d-m-Y', strtotime($userdetails->created_at)) . '</td>';
                // $usertable .= '<td>' . $role  . '</td>';
                $usertable .= '<td>';
                $usertable .= '<div class="btn-group">
                      <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                  </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item text-default text-btn-space" href="' . url('admin/users/' . $userdetails->id . '/edit') . '">Edit</a>
                        <a class="dropdown-item text-default text-btn-space" href="' . url('admin/users/block/' . $userdetails->id . '/' . $bstatus) . '" onclick="event.preventDefault(); document.getElementById("block-form").submit();">' . $btnstatus . '</a>
                        <form id="block-form" action="' . url('admin/users/block/' . $userdetails->id . '/' . $bstatus) . '" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a class="dropdown-item text-default text-btn-space" href="' . url('admin/users/subusers/' . $userdetails->id) . '">Sub Users</a>
                        <a class="dropdown-item text-default text-btn-space" href="' . url('admin/users/delete/' . $userdetails->id) . '">Delete</a>
                      </div>
                    </div>';
                $usertable .= '</td>';
                $usertable .= '<td><a href="' . url('admin/users/setfeatures/' . $userdetails->id) . '">Set Features</a></td>';
                $usertable .= '</tr>';
            }
            $usertable .= '</tbody>
            <tfoot><tr>
            <td colspan="9">
            '.
            $users->links()
            .'
            </td>
            </tr>
            </tfoot>';
            $usertable .= '</table>';
        } else {
            $usertable = 'No records available';
        }
        // exit();

        $data['total'] = $total;
        $data['table'] = $usertable;

        return view('admin.users.user')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.createuser');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $user = new users();


        $this->validate($request, [
            'username' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'userpass' => 'required|max:255',
        ]);

        $formdata = array(
            'name' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('userpass')),
        );


        $user = User::create($formdata);
        $uid = $user->id;

        if ($uid > 0) {

            $from_mail = Auth::user()->email;
            $to_mail = $formdata['email'];
            $title = 'Digital CRM';
            $content = 'You have Registerd Successfully ';
            Mail::send('mail', ['title' => $title, 'content' => $content], function ($message) use ($from_mail, $to_mail) {
                $message->subject('Registered Successfully');   //'sandeepindana@yahoo.com'
                $message->from($from_mail, 'Administrator');   //'sandeepindana@yahoo.com'
                $message->to($to_mail);   //'isandeep.1609@gmail.com'
            });


            return redirect('admin/users')->with('success', 'User Created Successfully...!');
        } else {
            return redirect('admin/users')->with('error', 'Error occurred. Please try again...!');
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
        $userdata = User::with('currency')->with('Tbl_user_types')->find($id);
        $data['userdata'] = $userdata;
        return view('admin.users.profile')->with('data', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $userdata = User::find($id)->toArray();
        // echo json_encode($userdata);

        $crlist = currency::all();
        $croptions = "<option value=''>Select Currency</option>";
        foreach ($crlist as $currency) {
            $selected = ($currency->cr_id == $userdata['cr_id']) ? 'selected' : '';
            $croptions .= "<option value='" . $currency->cr_id . "' " . $selected . ">" . $currency->name . " - " . $currency->code . "</option>";
        }

        $data['userdata'] = $userdata;
        $data['croptions'] = $croptions;

        $usertypes = Tbl_user_types::all();
        $usertypeoptions = "<option value=''>Select Role</option>";
        foreach ($usertypes as $usertype) {
            $selected = ($usertype->ut_id == $userdata['user_type']) ? 'selected' : '';
            $usertypeoptions .= "<option value='" . $usertype->ut_id . "' " . $selected . ">" . $usertype->type . "</option>";
        }
        $data['usertypeoptions'] = $usertypeoptions;
        /**
        $roles = Role::all();
        $urole = DB::table('model_has_roles')->where('model_id', $id)->first();
        $roleSelect = "<option value='0'>Select Role</option>";
        foreach ($roles as $role) {
            $rselected = ($role->id == $urole->role_id) ? 'selected' : '';
            $roleSelect .= "<option value='" . $role->id . "' " . $rselected . ">" . $role->name . "</option>";
        }
        $data['roleSelect'] = $roleSelect;
         */
        return view('admin.users.editprofile')->with('data', $data);
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
        // echo json_encode($request->input());
        // exit();

        $userdata = User::with('tbl_user_types')->find($id);

        $this->validate($request, [
            'username' => 'required|max:255',
            'user_type' => 'required',
            // 'weight' => 'required|numeric',
        ], [
            'user_type.required' => 'Please select role'
        ]);


        $filename = '';
        if ($request->hasfile('profilepicture')) {
            $file = $request->file('profilepicture');
            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $file->move('uploads/profile/', $name);
            $filename = '/uploads/profile/' . $name;
            $userdata->picture = $filename;
        }

        $userdata->name = $request->input('username');
        $userdata->mobile = $request->input('mobile');
        $userdata->jobtitle = $request->input('jobtitle');
        $userdata->cr_id = $request->input('currency');
        $userdata->daily_reports = $request->input('dailyreports');
        $userdata->newsletter = $request->input('newsletter');
        $userdata->weight = 0;
        $userdata->user_type = $request->input('user_type');
        $userdata->save();
        /**
        $arole = $request->input('arole');
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        if ($arole > 0) {
            // echo 'Assign Role';
            // exit();
            $userdata->assignRole($arole);

            $urole = Role::find($arole);
            $permits = $urole->permissions;
            $parray = array();
            foreach ($permits as $permit) {
                $parray[] = $permit->id;
            }

            $userdata->syncPermissions($parray);



        }
         */
        return redirect('admin/users/' . $id)->with('success', 'Updated Successfully...!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }

    public function block($id, $bstatus)
    {
        $userdata = User::find($id);
        // echo json_encode($userdata);

        $userdata->active = $bstatus;
        $blockRes = $userdata->save();

        if ($blockRes) {
            if ($bstatus == 1) {

                $from = Auth::user()->email;

                $subject = "Account activated successfully";
                $to = $userdata->email;
                $title = "Digital CRM";
                $message = "Your account has been activated sucessfully. Please click <a href='" . url('/login') . "'>here</a> to login.";
                $mailObj = new MailController();
                // $mailObj->sendMail($from, $to, $message, $subject, $title);
            }
        }



        return redirect('admin/users/')->with('success', 'Updated Successfully...!');
    }

    public function subusers($id)
    {
        $users = User::where('user', $id)->get();
        //        echo json_encode($userdata);

        $total = count($users);

        if (count($users) > 0) {
            $usertable = '<table id="usersTable" class="table">';
            $usertable .= '<thead>';
            $usertable .= '<tr>';
            $usertable .= '<th>Name</th>';
            $usertable .= '<th>Email</th>';
            $usertable .= '<th>Mobile</th>';
            $usertable .= '<th>Job Title</th>';
            $usertable .= '<th>Status</th>';
            $usertable .= '<th>Created</th>';
            $usertable .= '<th>Action</th>';
            $usertable .= '</tr>';
            $usertable .= '</thead>';
            $usertable .= '<tbody>';
            foreach ($users as $userdetails) {
                $status = ($userdetails->block == 1) ? "Blocked" : "Active";
                $btnstatus = ($userdetails->block == 1) ? "Active" : "Block";
                $bstatus = ($userdetails->block == 1) ? 0 : 1;
                $usertable .= '<tr>';
                $usertable .= '<td><a href="' . url('admin/users/' . $userdetails->id) . '">' . $userdetails->name . '</a>&nbsp;</td>';
                $usertable .= '<td><a href="' . url('admin/mails/mailsend/subusers/' . $userdetails->id) . '">' . $userdetails->email . '</a></td>';
                $usertable .= '<td>' . $userdetails->mobile . '</td>';
                $usertable .= '<td>' . $userdetails->jobtitle . '</td>';
                $usertable .= '<td>' . date('d-m-Y', strtotime($userdetails->created_at)) . '</td>';
                $usertable .= '<td>' . $status . '</td>';
                $usertable .= '<td>';
                $usertable .= '<div class="btn-group">
                      <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                      </button>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="' . url('admin/users/' . $userdetails->id . '/edit') . '">Edit</a></li>
                        <li><a href="' . url('admin/users/block/' . $userdetails->id . '/' . $bstatus) . '" onclick="event.preventDefault(); document.getElementById("block-form").submit();">' . $btnstatus . '</a>
                        <form id="block-form" action="' . url('admin/users/block/' . $userdetails->id . '/' . $bstatus) . '" method="POST" style="display: none;">
                            @csrf
                        </form>
                        </li>
                        <li><a href="#">Delete</a></li>
                      </ul>
                    </div>';
                $usertable .= '</td>';
                $usertable .= '</tr>';
            }
            $usertable .= '</tbody>';
            $usertable .= '</table>';
        } else {
            $usertable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $usertable;

        return view('admin.subusers.index')->with('data', $data);
    }

    public function getSubuseroptions($id)
    {
        $users = User::where('user', $id)->get();
        $subuseroptions = '';
        if (count($users) > 0) {
            foreach ($users as $userdetails) {
                $subuseroptions .= '<option value="' . $userdetails->id . '">' . $userdetails->name . '</option>';
            }
        }
        return $subuseroptions;
    }

    public function delete($id)
    {

        $pro_count = Tbl_projects::where('uid', $id)->count();
        if ($pro_count > 0) {
            return redirect('admin/users')->with('error', 'Please remove from projects and delete user');
        } else {

            $userdata = User::findOrFail($id);
            //        echo json_encode($userdata);
            $userdata->delete();
            return redirect('admin/users')->with('success', 'Deleted Successfully...!');
        }
    }

    public function deleteAll(Request $request)
    {

        $users = $request->input('users');
        $pro_count = Tbl_projects::whereIn('uid', explode(",", $users))->count();
        if ($pro_count > 0) {
            return redirect('admin/users')->with('error', 'Please remove from projects and delete user');
        } else {
            User::whereIn('id', explode(",", $users))->delete();
            //        $userdata = User::find($id);
            //        echo json_encode($userdata);
            //        $userdata->delete();
            return redirect('admin/users')->with('success', 'Deleted Successfully...!');
        }
    }

    // public function addRole($id)
    // {
    //     $roles = Role::all();

    //     $roleSelect = "<option value='0'>Select Role</option>";
    //     foreach ($roles as $role) {
    //         $roleSelect .= "<option value='" . $role->role_id . "'>" . $role->name . "</option>";
    //     }

    //     $data['roleSelect'] = $roleSelect;

    // }

    public function setFeatures($id)
    {
        $features = Tbl_features::where('uid', $id)->first();
        // echo json_encode($features);
        // exit();
        $rdata['features'] = $features;
        $rdata['uid'] = $id;

        return view('admin.users.features')->with('rdata', $rdata);
    }

    public function updateFeatures(Request $request, $id)
    {
        // echo json_encode($request->input());
        // exit();

        $features = $request->input();

        $webtolead = ((isset($features['webtolead']) != '') && ($features['webtolead'] == 'on')) ? 1 : 0;
        $accounts = ((isset($features['accounts']) != '') && ($features['accounts'] == 'on')) ? 1 : 0;
        $contacts = ((isset($features['contacts']) != '') && ($features['contacts'] == 'on')) ? 1 : 0;
        $leads = ((isset($features['leads']) != '') && ($features['leads'] == 'on')) ? 1 : 0;
        $deals = ((isset($features['deals']) != '') && ($features['deals'] == 'on')) ? 1 : 0;
        $customers = ((isset($features['customers']) != '') && ($features['customers'] == 'on')) ? 1 : 0;
        $sales = ((isset($features['sales']) != '') && ($features['sales'] == 'on')) ? 1 : 0;
        $orders = ((isset($features['orders']) != '') && ($features['orders'] == 'on')) ? 1 : 0;
        $forecasts = ((isset($features['forecasts']) != '') && ($features['forecasts'] == 'on')) ? 1 : 0;
        $territory = ((isset($features['territory']) != '') && ($features['territory'] == 'on')) ? 1 : 0;
        $products = ((isset($features['products']) != '') && ($features['products'] == 'on')) ? 1 : 0;
        $documents = ((isset($features['documents']) != '') && ($features['documents'] == 'on')) ? 1 : 0;
        $invoices = ((isset($features['invoices']) != '') && ($features['invoices'] == 'on')) ? 1 : 0;
        $projects = ((isset($features['projects']) != '') && ($features['projects'] == 'on')) ? 1 : 0;
        $rds = ((isset($features['rds']) != '') && ($features['rds'] == 'on')) ? 1 : 0;
        $webmails = ((isset($features['webmails']) != '') && ($features['webmails'] == 'on')) ? 1 : 0;
        $mails = ((isset($features['mails']) != '') && ($features['mails'] == 'on')) ? 1 : 0;
        $tasks = ((isset($features['tasks']) != '') && ($features['tasks'] == 'on')) ? 1 : 0;
        $settings = ((isset($features['settings']) != '') && ($features['settings'] == 'on')) ? 1 : 0;
        $reports = ((isset($features['reports']) != '') && ($features['reports'] == 'on')) ? 1 : 0;
        $companies = ((isset($features['companies']) != '') && ($features['companies'] == 'on')) ? 1 : 0;
        $campaigns = ((isset($features['campaigns']) != '') && ($features['campaigns'] == 'on')) ? 1 : 0;
        $productleads = ((isset($features['productleads']) != '') && ($features['productleads'] == 'on')) ? 1 : 0;
        $appointments = ((isset($features['appointments']) != '') && ($features['appointments'] == 'on')) ? 1 : 0;
        $ticketing = ((isset($features['ticketing']) != '') && ($features['ticketing'] == 'on')) ? 1 : 0;

        $updateArr = array(
            'webtolead' => $webtolead,
            'accounts' => $accounts,
            'contacts' => $contacts,
            'leads' => $leads,
            'deals' => $deals,
            'customers' => $customers,
            'sales' => $sales,
            'orders' => $orders,
            'forecasts' => $forecasts,
            'territory' => $territory,
            'products' => $products,
            'documents' => $documents,
            'invoices' => $invoices,
            'projects' => $projects,
            'rds' => $rds,
            'webmails' => $webmails,
            'mails' => $mails,
            'tasks' => $tasks,
            'settings' => $settings,
            'reports' => $reports,
            'companies' => $companies,
            'campaigns' => $campaigns,
            'productleads' => $productleads,
            'appointments' => $appointments,
            'ticketing' => $ticketing,
        );

        $exist =  Tbl_features::where('uid', $id)->first();
        $result = '';
        if ($exist != '') {
            $result = Tbl_features::where('uid', $id)->update($updateArr);
        } else {
            $updateArr['uid'] = $id;
            $feat = Tbl_features::create($updateArr);
            $result = $feat->ft_id;
        }

        if ($result) {
            return redirect('admin/users/')->with('success', 'Features Assigned Successfully..!');
        } else {
            return redirect('admin/users/')->with('error', 'Failed. Try again later..!');
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Admin\MailController;
use Mail;
use Auth;

//---------Models---------------
use App\currency;
use App\Client;

class ClientController extends Controller
{
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
        $users = Client::orderBy('id', 'desc')->get();

        // echo json_encode($users);
        // exit();

        //where('user_type', 1)->


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
            // $usertable .= '<th>Set Features</th>';
            $usertable .= '</tr>';
            $usertable .= '</thead>';
            $usertable .= '<tbody>';
            foreach ($users as $userdetails) {
                $status = ($userdetails->active == 1) ? "Active" : "Blocked";
                $btnstatus = ($userdetails->active == 1) ? "Block" : "Active";
                $bstatus = ($userdetails->active == 0) ? 1 : 0;

                $emailverification = ($userdetails->verified == 1) ? '<small class="text-success badge badge-success"><i class="fa fa-check" aria-hidden="true"></i> Verivified</small>' : '<small class="text-danger badge badge-danger"><i class="fa fa-times" aria-hidden="true"></i> Not Verivified</small>';

                $userimg = ($userdetails->picture != '') ? $userdetails->picture : 'uploads/default/user.png';

                $usertable .= '<tr>';
                $usertable .= '<td><a href="' . url('admin/clients/' . $userdetails->id) . '"><img src="' . url($userimg) . '" class="avatar" style="width:25px; height:25px;">' . $userdetails->name . '</a>&nbsp;</td>';
                $usertable .= '<td><a href="' . url('admin/mails/mailsend/clients/' . $userdetails->id) . '">' . $userdetails->email . '</a></td>';
                $usertable .= '<td>' . $userdetails->mobile . '</td>';
                $usertable .= '<td>' . $status . '</td>';
                $usertable .= '<td>' . $emailverification . '</td>';
                $usertable .= '<td>' . date('d-m-Y', strtotime($userdetails->created_at)) . '</td>';
                $usertable .= '<td>';
                $usertable .= '<div class="btn-group">
                      <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                  </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item text-default text-btn-space" href="' . url('admin/clients/' . $userdetails->id . '/edit') . '">Edit</a>
                        <a class="dropdown-item text-default text-btn-space" href="' . url('admin/clients/block/' . $userdetails->id . '/' . $bstatus) . '" onclick="event.preventDefault(); document.getElementById("block-form").submit();">' . $btnstatus . '</a>
                        <form id="block-form" action="' . url('admin/clients/block/' . $userdetails->id . '/' . $bstatus) . '" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a class="dropdown-item text-default text-btn-space" href="#">Delete</a>
                      </div>
                    </div>';
                // ' . url('admin/clients/delete/' . $userdetails->id) . '
                $usertable .= '</td>';
                // $usertable .= '<td><a href="' . url('admin/users/setfeatures/' . $userdetails->id) . '">Set Features</a></td>';
                $usertable .= '</tr>';
            }
            $usertable .= '</tbody>';
            $usertable .= '</table>';
        } else {
            $usertable = 'No records available';
        }
        // exit();

        $data['total'] = $total;
        $data['table'] = $usertable;

        return view('admin.clients.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Client::with('currency')->with('tbl_countries')->with('tbl_states')->find($id);
        //
        $data['userdata'] = $client;
        return view('admin.clients.show')->with('data', $data);
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
        $client = Client::with('currency')->with('tbl_countries')->with('tbl_states')->find($id);
        // echo json_encode($client);

        $crlist = currency::all();
        $croptions = "<option value=''>Select Currency</option>";
        foreach ($crlist as $currency) {
            $selected = ($currency->cr_id == $client->cr_id) ? 'selected' : '';
            $croptions .= "<option value='" . $currency->cr_id . "' " . $selected . ">" . $currency->name . " - " . $currency->code . "</option>";
        }

        $data['userdata'] = $client;
        $data['croptions'] = $croptions;

        return view('admin.clients.edit')->with('data', $data);
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

        $userdata = Client::with('currency')->with('tbl_countries')->with('tbl_states')->find($id);
        //
        $this->validate($request, [
            'username' => 'required|max:255',
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
        $userdata->cr_id = $request->input('currency');
        $res = $userdata->save();

        if ($res) {
            return redirect('admin/clients/' . $id . '/edit')->with('success', 'Updated Successfully');
        } else {
            return redirect('admin/clients/' . $id . '/edit')->with('error', 'Failed. Try again later');
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

    public function block($id, $bstatus)
    {
        $userdata = Client::find($id);
        // echo json_encode($userdata);
        // exit();

        $userdata->active = $bstatus;
        $blockRes = $userdata->save();

        // if ($blockRes) {
        //     if ($bstatus == 1) {

        //         $from = Auth::user()->email;

        //         $subject = "Account activated successfully";
        //         $to = $userdata->email;
        //         $title = "Digital CRM";
        //         $message = "Your account has been activated sucessfully. Please click <a href='" . url('/login') . "'>here</a> to login.";
        //         $mailObj = new MailController();
        //         // $mailObj->sendMail($from, $to, $message, $subject, $title);
        //     }
        // }



        return redirect('admin/clients/')->with('success', 'Updated Successfully...!');
    }
}

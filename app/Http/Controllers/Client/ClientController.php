<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Admin\MailController;
use Illuminate\Support\Facades\Validator;
use Mail;
use Auth;

//---------Models---------------
use App\currency;
use App\Client;
use App\Tbl_emailcategory;
use App\Tbl_emailtemplates;
use App\Tbl_emails;
use App\Tbl_countries;
use App\Tbl_states;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:client');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // echo 'Welcome to Client Dashboard';

        return view('client.dashboard');
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
        //
        $client = Client::with('currency')
            ->with('Tbl_countries')
            ->with('Tbl_states')
            ->find($id);
        // echo json_encode($client);
        // exit();

        $data['userdata'] = $client;

        return view('client.profile.show')->with('data', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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

        echo json_encode($request->input());
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

    // logout function 
    public function logout(Request $request)
    {
        Auth::guard('client')->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect('clients');
    }


    public function registrationMailClient($data)
    {

        $userdetails = Client::with('tbl_verifyclient')->find($data->id);

        // $this->registrationMailAdmin($data);

        $department = Tbl_emailcategory::where('category', 'Registration')->first();
        $template = Tbl_emailtemplates::where('ecat_id', $department->ecat_id)->first();
        $emails = Tbl_emails::where('ecat_id', $department->ecat_id)->first();

        $subject = $template->subject;
        $user = $data['name'];
        $click = '<a href="' . url('/clients/verify/' . $userdetails->tbl_verifyclient->token) . '">click</a>';
        $title = 'Digital CRM';
        $message = $template->message;
        $from_mail = $emails->mail; //'aynsoft@digitalcrm.com'
        $to_email = $data['email'];

        $beforeStr = $message;
        preg_match_all('/{(\w+)}/', $beforeStr, $matches);
        $afterStr = $beforeStr;
        foreach ($matches[0] as $index => $var_name) {
            if (isset(${$matches[1][$index]})) {
                $afterStr = str_replace($var_name, ${$matches[1][$index]}, $afterStr);
            }
        }
        $content = $afterStr;
        return $this->sendMail($from_mail, $to_email, $content, $subject, $title);
    }

    public function sendMail($from, $to, $message, $subject, $title)
    {

        Mail::send(['html' => 'emails.default'], ['title' => $title, 'content' => $message], function ($message) use ($from, $to, $subject) {
            $message->subject($subject);
            $message->from($from, config('app.name'));   //'sandeepindana@yahoo.com'
            $message->to($to);   //'isandeep.1609@gmail.com'
        });

        if (count(Mail::failures()) > 0) {
            //            echo 'Failed to send password reset email, please try again.';
            return FALSE;
        } else {
            //            echo "Success";
            return TRUE;
        }
    }

    public function clientUpdate(Request $request, $id)
    {
        //

        // echo json_encode($request->input());
        // echo $id;
        // exit();

        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        $filename = '';
        if ($request->hasfile('userpicture')) {

            //-------------Image Validation----------------------------------
            $file = $request->file('userpicture');
            // Build the input for validation
            $fileArray = array('userpicture' => $file);

            // Tell the validator that this file should be an image
            $rules = array(
                'userpicture' => 'mimes:jpeg,jpg,png,gif|max:10000' // max 10000kb
            );

            // Now pass the input and rules into the validator
            $validator = Validator::make($fileArray, $rules);

            // Check to see if validation fails or passes
            if ($validator->fails()) {
                return redirect('/clients/edit' . $id)->with('error', 'Please upload jpg, png and giff images only.');
            }
            //-------------Image Validation----------------------------------
            //            $file = $request->file('userpicture');
            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $file->move('uploads/clients/', $name);  //public_path().
            $filename = '/uploads/clients/' . $name;
            //            $acccount->picture = $filename;
        }

        $formdata = $request->input();
        $fdata['name'] = $formdata['name'];
        $fdata['picture'] = $filename;
        $fdata['cr_id'] = $formdata['currency'];
        $fdata['mobile'] = $formdata['mobile'];
        $fdata['address'] = $formdata['address'];
        $fdata['city'] = $formdata['city'];
        $fdata['zip'] = $formdata['zip'];
        $fdata['country'] = $formdata['country'];
        $fdata['state'] = $formdata['state'];

        // echo json_encode($fdata);
        // exit();

        $res = Client::where('id', $id)->update($fdata);

        if ($res) {
            return redirect('/clients/edit/' . $id)->with('success', 'Updated Successfully');
        } else {
            return redirect('/clients/edit/' . $id)->with('error', 'Error occured. Try again later...!');
        }
    }

    public function clientEdit($id)
    {
        //
        $client = Client::with('currency')
            ->with('Tbl_countries')
            ->with('Tbl_states')
            ->find($id);
        // echo json_encode($client);
        // exit();

        $data['userdata'] = $client;

        $country = Tbl_countries::all();
        $countryoptions = "<option value='0'>Select Country</option>";
        if (count($country) > 0) {
            foreach ($country as $cnt) {
                $selected = ($cnt->id == $client['country']) ? 'selected' : '';
                $countryoptions .= "<option value='" . $cnt->id . "' " . $selected . ">" . $cnt->name . ' - ' . $cnt->sortname . "</option>";
            }
        }
        $data['countryoptions'] = $countryoptions;

        $stateoptions = "<option value='0'>Select State</option>";
        if ($client['country'] > 0) {
            $states = DB::select('SELECT * FROM `tbl_states` WHERE `country_id`=' . $client['country']);
            if (count($states) > 0) {
                foreach ($states as $state) {
                    $stateselected = ($state->id == $client['state']) ? 'selected' : '';
                    $stateoptions .= "<option value='" . $state->id . "' " . $stateselected . ">" . $state->name . "</option>";
                }
            }
        }
        $data['stateoptions'] = $stateoptions;

        $crlist = currency::all();
        $croptions = "<option value=''>Select Currency</option>";
        foreach ($crlist as $currency) {

            if ($currency->cr_id == $client['cr_id']) {
                $selected = 'selected';
                // $user_currency = '<span>' . $currency->html_code . '</span>&nbsp;' . $currency->name;
            } else {
                $selected = '';
            }

            $croptions .= "<option value='" . $currency->cr_id . "' " . $selected . ">" . $currency->name . " - " . $currency->code . "</option>";
        }

        $data['croptions'] = $croptions;

        return view('client.profile.edit')->with('data', $data);
    }


    public function getStateoptions(Request $request)
    {
        $country = $request->input('country');
        $states = Tbl_states::where('country_id', $country)->get();
        $stateOption = "<option value='0'>Select State</option>";
        foreach ($states as $state) {
            $stateOption .= "<option value='" . $state->id . "'>" . $state->name . "</option>";
        }
        return $stateOption;
    }
}

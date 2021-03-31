<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
//---------Models---------------
use App\User;
use App\Tbl_smtpsettings;

class SmtpController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('auth.smtp.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('auth.smtp.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
//        echo json_encode($request->input());

        $uid = Auth::user()->id;

        $this->validate($request, [
            'username' => 'required|max:255',
            'password' => 'required|max:255',
            'outgoingserver' => 'required|max:255',
            'outgoingport' => 'required|max:255',
            'incomingserver' => 'required|max:255',
            'incomingport' => 'required|max:255',
        ]);

        $formdata = array(
            'uid' => $uid,
            'username' => $request->input('username'),
            'password' => $request->input('password'),
            'outgoingserver' => $request->input('outgoingserver'),
            'outgoingport' => $request->input('outgoingport'),
            'incomingserver' => $request->input('incomingserver'),
            'incomingport' => $request->input('incomingport'),
        );

        $smtp = Tbl_smtpsettings::create($formdata);

        if ($smtp->ss_id > 0) {
            return redirect('home')->with('success', 'Created Successfully');
        } else {
            return redirect('home')->with('error', 'Failed. Please try again later');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $uid = Auth::user()->id;

        $smtpsettings = Tbl_smtpsettings::where('uid', $id)->first();
        return view('auth.smtp.edit')->with('data', $smtpsettings);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
//        echo json_encode($request->input());

        $this->validate($request, [
            'username' => 'required|max:255',
            'password' => 'required|max:255',
            'outgoingserver' => 'required|max:255',
            'outgoingport' => 'required|max:255',
            'incomingserver' => 'required|max:255',
            'incomingport' => 'required|max:255',
        ]);

        $smtp = Tbl_smtpsettings::find($id);

//    $smtp->uid' => $uid,
        $smtp->username = $request->input('username');
        $smtp->password = $request->input('password');
        $smtp->outgoingserver = $request->input('outgoingserver');
        $smtp->outgoingport = $request->input('outgoingport');
        $smtp->incomingserver = $request->input('incomingserver');
        $smtp->incomingport = $request->input('incomingport');
        $smtp->save();

        return redirect('home')->with('success', 'Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

}

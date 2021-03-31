<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
//  Import COntrollers
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\AccountController;

global $accountObj;

class AccountsController extends BaseController {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $uid = Auth::user()->id;
        $accountObj = new AccountController();
        $accounts = $accountObj->getAccounts($uid);

//        $data = Auth::user();

        return $this->sendResponse($accounts, 'Records Available');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

//        return $this->sendResponse($request->input(), 'Request Data');

        $uid = Auth::user()->id;

        $validator = Validator::make($request->all(), [
                    'name' => 'required|max:255',
                    'email' => 'email|required|max:255|unique:tbl_accounts',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }

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
                return $this->sendError('Please check uploaded image', $validator->errors());
            }
            //-------------Image Validation----------------------------------


            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $file->move('uploads/accounts/', $name);  //public_path().
            $filename = '/uploads/accounts/' . $name;
        }

        $formdata = array(
            'uid' => $uid,
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'picture' => $filename,
            'mobile' => $request->input('mobile'),
            'phone' => $request->input('phone'),
            'actype_id' => $request->input('accounttype'),
            'intype_id' => $request->input('industrytype'),
            'description' => $request->input('notes'),
            'website' => $request->input('website'),
            'country' => $request->input('country'),
            'state' => $request->input('state'),
            'city' => $request->input('city'),
            'street' => $request->input('street'),
            'zip' => $request->input('zip'),
            'company' => $request->input('company'),
        );

        $accountObj = new AccountController();
        $accounts = $accountObj->addAccount($formdata);
        if ($accounts->acc_id > 0) {
            return $this->sendResponse($accounts, 'Created successfully');
        } else {
            return $this->sendError('Error occurred. Please try again', array());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $accountObj = new AccountController();
        $account = $accountObj->getAccountDetails($id);
        return $this->sendResponse($account, 'Records Available');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
//        updateAccount($formdata, $file, $id)


        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'email|required|max:255|unique:tbl_accounts,email,' . $id . ',acc_id',
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
                return $this->sendError('Please check uploaded image', $validator->errors());
            }
            //-------------Image Validation----------------------------------
//            $file = $request->file('userpicture');
            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $file->move('uploads/accounts/', $name);  //public_path().
            $filename = '/uploads/accounts/' . $name;
        }

        $formdata = $request->input();
        $formdata['picture'] = $filename;

        $accountObj = new AccountController();
        $account = $accountObj->updateAccount($formdata, $id);
        if ($account) {
            return $this->sendResponse($account, 'Updated successfully');
        } else {
            return $this->sendError('Error occurred. Please try again', array());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $accountObj = new AccountController();
        $account = $accountObj->delete($id);
        return $this->sendResponse($account, 'Deleted Successfully');
    }

}

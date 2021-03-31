<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
//  Import COntrollers
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\LeadController;

global $leadObj;

class LeadsController extends BaseController {

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
        $leadObj = new LeadController();
        return $leadObj->getLeads($uid);
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
//        return $this->sendResponse($request->input(), 'Given Input');

        $validator = Validator::make($request->all(), [
                    'first_name' => 'required|max:255',
                    'last_name' => 'required|max:255',
                    'usermail' => 'required|email|max:255|unique:tbl_leads',
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
                return $this->sendError('Validation error', $validator->errors());
            }
            //-------------Image Validation----------------------------------
//            $file = $request->file('userpicture');
            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $file->move('uploads/leads/', $name);  //public_path().
            $filename = '/uploads/leads/' . $name;
        }


        $formdata = array(
            'uid' => Auth::user()->id,
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('usermail'),
            'picture' => $filename,
            'mobile' => $request->input('mobile'),
            'phone' => $request->input('phone'),
            'ldsrc_id' => $request->input('leadsource'),
            'ld_status' => $request->input('leadstatus'),
            'intype_id' => $request->input('industrytype'),
            'acc_id' => $request->input('account'),
            'notes' => $request->input('notes'),
            'website' => $request->input('website'),
            'country' => $request->input('country'),
            'state' => $request->input('state'),
            'city' => $request->input('city'),
            'street' => $request->input('street'),
            'zip' => $request->input('zip'),
        );

        $leadObj = new LeadController();
        $lead = $leadObj->addLead($formdata);

        if ($lead->ld_id > 0) {
            return $this->sendResponse($lead, 'Lead Created Successfully');
        } else {
            return $this->sendError('Error Occurred. Please try again later.', $validator->errors());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $leadObj = new LeadController();
        $lead = $leadObj->getLeadDetails($id);
        return $this->sendResponse($lead, 'Data Available');
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

        $validator = Validator::make($request->all(), [
                    'first_name' => 'required|max:255',
                    'last_name' => 'required|max:255',
                    'email' => 'required|email|max:255|unique:tbl_leads,email,' . $id . ',ld_id',
        ]);


        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        } else {

            $formdata = $request->all();

            $leadObj = new LeadController();
            $lead = $leadObj->updateLead($formdata, $id);

            if ($lead) {

                $leadDetails = $leadObj->getLeadDetails($id);

                return $this->sendResponse($leadDetails, 'Updated Successfully');
            } else {
                return $this->sendError('Error occurred. Please try again later', []);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $leadObj = new LeadController();
        $lead = $leadObj->delete($id);
        if ($lead) {
            return $this->sendResponse([], 'Deleted Successfully');
        } else {
            return $this->sendError('Error occurred. Please try again later', []);
        }
    }

}

<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
//  Import COntrollers
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\ContactController;

global $contactObj;

class ContactsController extends BaseController {

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
        $contactObj = new ContactController();
        $contacts = $contactObj->getContacts($uid);
        return $this->sendResponse($contacts, 'Records Available');
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



        $validator = Validator::make($request->all(), [
                    'first_name' => 'required|max:255',
                    'last_name' => 'required|max:255',
                    'email' => 'required|email|max:255|unique:tbl_contacts',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error.', $validator->errors());
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
                return $this->sendError('Validation error.', $validator->errors());
            }
            //-------------Image Validation----------------------------------
            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $file->move('uploads/contacts/', $name);  //public_path().
            $filename = '/uploads/contacts/' . $name;
        }
//        $formdata['picture'] = $filename;

        $formdata = array(
            'uid' => Auth::user()->id,
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'picture' => $filename,
            'mobile' => $request->input('mobile'),
            'phone' => $request->input('phone'),
            'ldsrc_id' => $request->input('leadsource'),
            'acc_id' => $request->input('account'),
            'notes' => $request->input('notes'),
            'website' => $request->input('website'),
            'country' => $request->input('country'),
            'state' => $request->input('state'),
            'city' => $request->input('city'),
            'street' => $request->input('street'),
            'zip' => $request->input('zip'),
            'google_id' => $request->input('google_id'),
            'facebook_id' => $request->input('facebook_id'),
            'twitter_id' => $request->input('twitter_id'),
            'linkedin_id' => $request->input('linkedin_id'),
        );

        $contactObj = new ContactController();
        $contact = $contactObj->addContact($formdata);

        if ($contact->cnt_id > 0) {
            return $this->sendResponse($contact, 'Contact created successfully');
        } else {
            return $this->sendError('Error occurred. Please try again later', []);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {

//        echo 'Contact ID : ' . $id;

        $contactObj = new ContactController();
        $contact = $contactObj->getContactDetails($id);
        return $this->sendResponse($contact, 'Data Available');
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
                    'email' => 'required|email|max:255|unique:tbl_contacts,email,' . $id . ',cnt_id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        } else {

            $formdata = $request->all();

            $contactObj = new ContactController();
            $contact = $contactObj->updateContact($formdata, $id);

            if ($contact) {
                $contactDetails = $contactObj->getContactDetails($id);
                return $this->sendResponse($contactDetails, 'Updated Successfully');
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
        $contactObj = new ContactController();
        $contact = $contactObj->delete($id);

        if ($contact) {
            return $this->sendResponse([], 'Deleted Successfully');
        } else {
            return $this->sendError('Error occurred. Please try again later', []);
        }
    }

}

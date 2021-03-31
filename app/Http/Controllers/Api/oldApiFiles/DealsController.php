<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
//  Import COntrollers
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\DealController;

global $dealObj;

class DealsController extends BaseController {

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
        $dealObj = new DealController();
        $deals = $dealObj->getDeals($uid);
        return $this->sendResponse($deals, 'Records Available');
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
                    'dealname' => 'required|max:255',
                    'amount' => 'required|numeric',
                    'closingdate' => 'required|date_format:d-m-Y',
                    'lead' => 'required|numeric|min:0|not_in:0',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error.', $validator->errors());
        }

        $sfun_id = $request->input('dealstage');

        $deal_status = 0;
        if ($sfun_id == 5) {
            $deal_status = 1;
        }
        if ($sfun_id == 6) {
            $deal_status = 2;
        }

        $formdata = array(
            'uid' => Auth::user()->id,
            'ld_id' => $request->input('lead'),
            'sfun_id' => $request->input('dealstage'),
            'ldsrc_id' => $request->input('leadsource'),
            'name' => $request->input('dealname'),
            'value' => $request->input('amount'),
            'closing_date' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('closingdate')))),
            'notes' => $request->input('notes'),
            'deal_status' => $deal_status,
        );

        $dealObj = new DealController();
        $deals = $dealObj->addDeal($formdata);

        if ($deals->deal_id > 0) {
            return $this->sendResponse($deals, 'Deal created successfully');
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
        $dealObj = new DealController();
        $deals = $dealObj->getDealdetails($id);
        return $this->sendResponse($deals, 'Data Available');
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
                    'dealname' => 'required|max:255',
                    'amount' => 'required|numeric',
                    'closingdate' => 'required|date_format:d-m-Y',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error.', $validator->errors());
        } else {
            $formdata = $request->all();

            $sfun_id = $request->input('dealstage');

            $deal_status = 0;
            if ($sfun_id == 5) {
                $deal_status = 1;
            }
            if ($sfun_id == 6) {
                $deal_status = 2;
            }

            $formdata['closingdate'] = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('closingdate'))));

            $formdata['deal_status'] = $deal_status;

            $dealObj = new DealController();
            $deal = $dealObj->updateDeal($formdata, $id);
            if ($deal) {
                return $this->sendResponse($deal, 'Updated Successfully');
            } else {
                return $this->sendError('Error occurred. Try again later', []);
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
        $dealObj = new DealController();
        $deal = $dealObj->delete($id);
        if ($deal) {
            return $this->sendResponse($deal, 'Deleted Successfully');
        } else {
            return $this->sendError('Failed. Try again later', []);
        }
    }

}

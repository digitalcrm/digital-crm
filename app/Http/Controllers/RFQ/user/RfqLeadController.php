<?php

namespace App\Http\Controllers\RFQ\user;

use App\RfqLead;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RfqLeadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('store');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $request = request('name');

        $rfq_leads = RfqLead::whereHas('rfq', function($query) use ($request){
            $query->where('user_id', auth()->id())->rfqInquiry($request);
        })->latest()->get();

        return view('rfq.rfq-leads.index',compact('rfq_leads'));
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
        $request->validate([
            'contact_name' => 'required',
            'email' => 'required|string|email|max:255',
            'mobile_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|digits:10',
            'country_id' => 'not_in:0',
        ]);
        RfqLead::create([
            'rfq_id' => $request->rfq_id,
            'contact_name' => $request->contact_name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'address' => $request->address,
            'country_id' => $request->country_id,
            'city' => $request->city,
            'message' => $request->message,
        ]);

        return redirect()->back()->withMessage('quote submitted successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RfqLead  $rfq_lead
     * @return \Illuminate\Http\Response
     */
    public function show(RfqLead $rfq_lead)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RfqLead  $rfq_lead
     * @return \Illuminate\Http\Response
     */
    public function edit(RfqLead $rfq_lead)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RfqLead  $rfq_lead
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RfqLead $rfq_lead)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RfqLead  $rfq_lead
     * @return \Illuminate\Http\Response
     */
    public function destroy(RfqLead $rfq_lead)
    {
        //
    }
}

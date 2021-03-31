<?php

namespace App\Http\Controllers\Api;

use App\RfqLead;
use App\Rules\Captcha;
use Illuminate\Http\Request;
use App\Http\Resources\RfqResource;
use App\Http\Controllers\Controller;

class RfqLeadApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        $this->validate($request, [
            'contact_name' => 'required|max:45',
            'email' => 'required|email',
            'mobile_number' => 'required|digits:10',
            // 'g-recaptcha-response' => 'required', new Captcha(),
        ]);

        $data = RfqLead::create([
            'rfq_id' => $request->rfq_id,
            'contact_name' => $request->contact_name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'address' => $request->address,
            'country_id' => $request->country_id,
            'city' => $request->city,
            'message' => $request->message,
        ]);

        return new RfqResource($data);
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
}

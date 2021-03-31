<?php

namespace App\Http\Controllers\Bookings;

use App\BookingShare;
use App\Rules\googleCaptcha;
use Illuminate\Http\Request;
use App\Mail\SendEventLinkToFriend;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class BookingShareController extends Controller
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
        $validatedData = $request->validate([
            'sender_name'   =>  'required|string|max:75',
            'sender_email'  =>  'required|string|email|max:255',
            'receiver_name' =>  'required|string|max:75',
            'receiver_email'    =>  'required|string|email|max:255',
            'message'   =>  'required',
            'eventId'  =>  'required|not_in:0',
            'g-recaptcha-response' => ['required', new googleCaptcha()],
        ]);

        $validatedData['booking_event_id'] = request('eventId');
        $validatedData['url'] = request('eventURL');

        // dd($validatedData);

        $customerData = BookingShare::create($validatedData);

        Mail::to($customerData->receiver_email)->send(new SendEventLinkToFriend($customerData));

        return redirect()->back()->withMessage('link shared successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BookingShare  $booking_shares
     * @return \Illuminate\Http\Response
     */
    public function show(BookingShare $booking_shares)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BookingShare  $booking_shares
     * @return \Illuminate\Http\Response
     */
    public function edit(BookingShare $booking_shares)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BookingShare  $booking_shares
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BookingShare $booking_shares)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BookingShare  $booking_shares
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookingShare $booking_shares)
    {
        //
    }
}

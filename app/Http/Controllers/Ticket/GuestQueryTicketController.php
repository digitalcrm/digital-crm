<?php

namespace App\Http\Controllers\Ticket;

use App\Ticket;
use Carbon\Carbon;
use App\Tbl_contacts;
use App\Rules\Captcha;
use Illuminate\Http\Request;
use App\Mail\UserTicketStore;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class GuestQueryTicketController extends Controller
{
    public function openticket(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'first_name' => 'bail|required|max:50',
            'last_name' => 'required',
            'email' => 'required',
            'name' => 'required|max:255',
            'description' => 'required',
            'g-recaptcha-response' => 'required', new Captcha(),
        ]);


        if ($validator->fails()) {
            return redirect()
                        ->back()
                        ->with('errors','something went wrong! plz fill form correctly')
                        // ->withErrors($validator)
                        ->withInput();
        }

        $data = Tbl_contacts::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ]);

        // $ticket = $data->cnt_id; #get create contact_id
        // dd($data->cnt_id);

        # using this sort method you don't need define model like Ticket::create()
        # if you have relationship then you can define like this below
        # In my Tbl_contacts model I've tickets() function
        $ticketdata = $data->tickets()->create([
            'ticket_number' => mt_rand(100, getrandmax()),
            'name' => $request->name,
            'description' => $request->description,
            // 'contact_id' => $ticket,
            'status_id' => 1,
            'type_id' => 4,
            'start_date' => Carbon::now(),
            'priority_id' => 2,
        ]);

        Mail::to($data->email)->send(new UserTicketStore($ticketdata));

        return redirect()->back()->with('message','ticket created successfully');
    }

    public function assginticket(Request $request) {

        $ticketid = $request->input('ticketid');

        $userid = $request->input('userid');

        $res =  Ticket::where('id', $ticketid)->update(['user_id' => $userid]);

        return $res;
    }

    public function createticket()
    {
        return view('ticketing.formticket');
    }
}

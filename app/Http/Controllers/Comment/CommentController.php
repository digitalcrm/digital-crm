<?php

namespace App\Http\Controllers\Comment;

use App\Ticket;
use App\Comment;
use Illuminate\Http\Request;
use App\Mail\UserTicketStore;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\CommentStoreRequest;

class CommentController extends Controller
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
    public function store(CommentStoreRequest $request, Ticket $ticket)
    {
        /**
         *  This store method also work but also add a hidden input field in your blade file
         *  where value would be <input type="hidden" name="ticketId" value="{{$ticket->id}}">"
         */


        // $ticket = Ticket::find($request->ticketId);

        // $input = $request->validated();

        // $input['user_id'] = Auth::user()->id;

        // $ticket->comments()->create($input);

        // return redirect()->back();


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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(CommentStoreRequest $request, $ticketId)
    {
        /**
         * Above store method is also working
         * This is the second way I've used for this
         * You can also use store method but don't forgot about "form" field in blade file
         */

        $ticketdata = Ticket::find($ticketId);
        // dd($ticket->tbl_contacts->email);
        $contactemail = $ticketdata->tbl_contacts->email;
        $input = $request->validated();
        $input['user_id'] = Auth::user()->id;
        // $ticketdata->comments()->create($input);

        switch ($request->input('action')) {
            case 'close':
                # when closed button is hitted then status will be changed otherwise not.

                Ticket::where('id',$ticketId)->update([
                    'status_id' => 3,
                ]);

                # Here two condition is follwed first if ticket is closed without filling textarea
                # And ticket closed with filled textarea

                // if ($request->filled('message')) {
                //     $ticketmessage = $ticketdata->comments()->create($input);
                //     Mail::to($contactemail)->send(new UserTicketStore($ticketdata, $ticketmessage));
                // } else {
                //     Mail::to($contactemail)->send(new UserTicketStore($ticketdata));
                // }

                # Refactor code below compared to above code
                $ticketmessage = '';

                if ($request->filled('message')) {
                    $ticketmessage = $ticketdata->comments()->create($input);
                }
                Mail::to($contactemail)->send(new UserTicketStore($ticketdata, $ticketmessage));

                return redirect( route('tickets.index',['all' => 'true']) )->with('message','Ticket is closed');
                break;

            default:
                # normal action == send button
                $ticketmessage = $ticketdata->comments()->create($input);

                Mail::to($contactemail)->send(new UserTicketStore($ticketdata, $ticketmessage));

                return redirect()->back();
                break;
        }


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

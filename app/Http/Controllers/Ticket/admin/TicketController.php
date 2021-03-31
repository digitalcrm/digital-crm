<?php

namespace App\Http\Controllers\Ticket\admin;

use App\User;
use App\Ticket;
use App\Priority;
use App\Ticket_type;
use App\Ticket_status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use phpDocumentor\Reflection\Types\Null_;

class TicketController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $status = Ticket_status::get();
        $tickettype = Ticket_type::get();
        $priorities = Priority::get();
        $users = User::all();
        $tickets = Ticket::with('ticketStatus','ticketType','tbl_contacts')->latest();

        if (request('filterBy')) {

            $filterBy = request('filterBy');

            $tickets = $tickets->when($filterBy, function($query, $filterBy) {
                $query->withStatus($filterBy);
            })->get();

        } elseif (request('filterByPriority')) {

            $filterByPriority = request('filterByPriority');
            $tickets = $tickets->when($filterByPriority, function($query, $filterByPriority) {
                        $query->withPriority($filterByPriority);
                    })->get();

        } elseif(request('UnassignedTickets')) {
            $tickets = $tickets->withUnassignedTickets()->get();
        } else {
            // dd('this is all tickets');
            // $tickets = $tickets->with('users', function($query) {
                //     $query->where('active',1);
                // })->withoutUnassignedTickets()->get(); # if above two condition false then it will execute

            $tickets = $tickets->has('users')->with(['users'=> function($query){
                $query->active(); # Model Users scope method active call
            }])->latest()->get();

        }

        return view('ticketing.admin.index',compact('tickets','status','tickettype','priorities','users'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        return view('ticketing.admin.view', [
            'ticket' => $ticket,
        ]);
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

<?php

namespace App\Http\Controllers\Ticket\settings;

use App\Http\Controllers\Controller;
use App\Ticket_type;
use Illuminate\Http\Request;

class TickettypeController extends Controller
{
    public function __construct() {

        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets_type = Ticket_type::all();

        return view('ticketing.settings.type.index',compact('tickets_type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ticketing.settings.type.create');
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
            'name' => 'required|unique:ticket_types|max:35',
        ]);

        Ticket_type::create([
            'name' => $request->name,
        ]);


        return redirect(route('tickettype.index'))->with('success', 'type created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ticket_type  $tickettype
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket_type $tickettype)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ticket_type  $tickettype
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket_type $tickettype)
    {
        return view('ticketing.settings.type.edit', compact('tickettype'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ticket_type  $tickettype
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket_type $tickettype)
    {
        $request->validate([
            'name' => 'required|unique:ticket_types|max:35',
        ]);

        $tickettype->update([
            'name' => $request->name,
        ]);

        return redirect(route('tickettype.index'))->with('success', 'type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ticket_type  $tickettype
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket_type $tickettype)
    {
        $tickettype->delete();

        return redirect(route('tickettype.index'))->with('success', 'type deleted successfully');
    }
}

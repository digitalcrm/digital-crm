<?php

namespace App\Http\Controllers\Ticket\settings;

use App\Http\Controllers\Controller;
use App\Ticket_status;
use Illuminate\Http\Request;

class TicketstatusController extends Controller
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
        $ticketstatus = Ticket_status::get();

        return view('ticketing.settings.status.index',compact('ticketstatus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ticketing.settings.status.create');
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
            'name' => 'required|unique:ticket_statuses|max:25'
        ]);

        Ticket_status::create([
            'name' => $request->name,
        ]);

        return redirect(route('ticketstatus.index'))->with('success','Status created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ticket_status  $ticketstatus
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket_status $ticketstatus)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ticket_status  $ticketstatus
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket_status $ticketstatus)
    {
        return view('ticketing.settings.status.edit',compact('ticketstatus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ticket_status  $ticketstatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket_status $ticketstatus)
    {
        $request->validate([
            'name' => 'required|unique:ticket_statuses|max:25'
        ]);

        $ticketstatus->update([
            'name' => $request->name,
        ]);

        return redirect(route('ticketstatus.index'))->with('success','Status updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ticket_status  $ticketstatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket_status $ticketstatus)
    {
        $ticketstatus->delete();

        return redirect()->back()->with('success','Status deleted successfully');
    }
}

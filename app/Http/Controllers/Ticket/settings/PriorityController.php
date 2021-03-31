<?php

namespace App\Http\Controllers\Ticket\settings;

use App\Http\Controllers\Controller;
use App\Priority;
use Illuminate\Http\Request;

class PriorityController extends Controller
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
        return view('ticketing.settings.priority.index',[

            'priorities' => Priority::get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ticketing.settings.priority.create');
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
            'name' => 'required|unique:priorities|max:35',
        ]);

        Priority::create([
            'name' => $request->name,
        ]);

        return redirect(route('priorities.index'))->with('success','priority created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Priority  $priority
     * @return \Illuminate\Http\Response
     */
    public function show(Priority $priority)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Priority  $priority
     * @return \Illuminate\Http\Response
     */
    public function edit(Priority $priority)
    {
        return view('ticketing.settings.priority.edit', compact('priority'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Priority  $priority
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Priority $priority)
    {
        $request->validate([
            'name' => 'required|unique:priorities|max:35',
        ]);

        $priority->update([
            'name' => $request->name,
        ]);

        return redirect(route('priorities.index'))->with('success','priority updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Priority  $priority
     * @return \Illuminate\Http\Response
     */
    public function destroy(Priority $priority)
    {
        $priority->delete();

        return redirect(route('priorities.index'))->with('success','priority deleted successfully');
    }
}

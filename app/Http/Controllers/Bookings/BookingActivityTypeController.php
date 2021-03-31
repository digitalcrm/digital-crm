<?php

namespace App\Http\Controllers\Bookings;

use App\BookingActivity;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookingActivityTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activities = BookingActivity::all();

        return view('bookings.admin.activity_types.index', compact('activities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bookings.admin.activity_types.create');
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
            'title' => 'required|unique:booking_activities|max:25',
        ]);

        BookingActivity::create($validatedData);

        return redirect(route('activity_type.index'))->withMessage('Booking Activity created successfully');
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
    public function edit(BookingActivity $activity_type)
    {
        return view('bookings.admin.activity_types.edit', compact('activity_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BookingActivity $activity_type)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:25|unique:booking_activities,title,'.$activity_type->id,
        ]);

        $activity_type->update($validatedData);

        return redirect(route('activity_type.index'))->withMessage('Booking Activity updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookingActivity $activity_type)
    {
        $activity_type->delete();

        return redirect(route('activity_type.index'))->withMessage('Booking Activity deleted successfully');

    }
}

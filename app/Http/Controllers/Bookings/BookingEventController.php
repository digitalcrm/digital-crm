<?php

namespace App\Http\Controllers\Bookings;

use Carbon\Carbon;
use App\BookingEvent;
use App\BookingService;
use App\BookingActivity;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\BookingEventRequest;
use App\Http\Resources\BookingEventResource;

class BookingEventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       switch (request('events')) {
        // case 'today':
        // $bookevents = auth()->user()->bookingEvents()->today()->get();

        // break;

        case 'upcoming':
            $bookevents = auth()->user()->bookingEvents()->upcoming()->latest()->get();

            break;

        case 'completed':
            $bookevents = auth()->user()->bookingEvents()->completed()->latest()->get();

                break;

        default:
            $bookevents = auth()->user()->bookingEvents()->latest()->get();
            break;
       }

        return view('bookings.user.events.index', compact('bookevents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', BookingEvent::class);

        $services = BookingService::all();
        $activities = BookingActivity::pluck('title','id');
        $timeDuration = config('time_duration.hours');
        // dd($timeDuration);
        return view('bookings.user.events.create', compact('services', 'timeDuration','activities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookingEventRequest $request)
    {
        $this->authorize('create', BookingEvent::class);

        $bookingEventRequestData = $request->validated();

        $bookingEventRequestData['booking_service_id'] = 1;

        // dd($bookingEventRequestData);

        // dd($bookingEventRequestData);
        auth()->user()->bookingevents()->create($bookingEventRequestData);

        return redirect(route('bookevents.index',['events'=>'upcoming']))->withMessage('events created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(BookingEvent $bookevent)
    {
        Gate::authorize('view', $bookevent);

        return view('bookings.user.events.show', compact('bookevent'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(BookingEvent $bookevent)
    {
        Gate::authorize('update', $bookevent);
        try {
            $services = BookingService::all();
            $activities = BookingActivity::pluck('title','id');
            $timeDuration = config('time_duration.hours');

        } catch (\Throwable $e) {

            report($e);

            return false;
        }

        return view('bookings.user.events.edit', compact('services', 'timeDuration', 'bookevent','activities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BookingEventRequest $request, BookingEvent $bookevent)
    {
        Gate::authorize('update', $bookevent);

        $bookingEventRequestData = $request->validated();
        // $bookevent->load('bookingService');

        $bookevent->update($bookingEventRequestData);

        return redirect(route('bookevents.index',['events'=>'upcoming']))->withMessage('booking event updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookingEvent $bookevent)
    {
        Gate::authorize('view', $bookevent);

        $bookevent->delete();

        return redirect()->back()->withMessage($bookevent->event_name . ' deleted successfully');
    }

    public function allBookingEvent()
    {
        return view('bookings.user.event_calendar');
    }

    public function calendarlist()
    {
        $event = auth()->user()->bookingevents()->active()->get();

        return BookingEventResource::collection($event);

    }

    public function eventStatus(Request $request)
    {
        $bookingEvent = BookingEvent::find($request->eventId);

        $bookingEvent->isActive = $request->eventStatus;

        $bookingEvent->save();

        return response()->json(['success'=>'Event Status changed succesfully']);
    }

}

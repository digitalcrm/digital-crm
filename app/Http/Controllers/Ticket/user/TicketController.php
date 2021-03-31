<?php

namespace App\Http\Controllers\Ticket\user;

use App\User;
use App\Ticket;
use App\Comment;
use App\Priority;
use App\Ticket_type;
use App\Tbl_contacts;
use App\Tbl_products;
use App\Ticket_status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\CreateTicketRequest;
use App\Mail\UserTicketStore;

class TicketController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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

        # Dynamic scoping used
        // $filterBy = request('filterBy'); # for status
        // $tickets = Ticket::with('users','ticketStatus','ticketType','tbl_contacts')
        //             ->byAuthUser()
        //             ->when($filterBy, function($query, $filterBy) {
        //                 // ->where('status_id',$filterBY) # this is used without scopeQuery
        //                 // but it will use the Id, If you want to name in URL modified this query
        //                 $query->withStatus($filterBy); # Here I applied scopeQuery
        //             })
        //             ->latest()
        //             ->get();

        #Filter Ticket or get hole ticket
        // $tickets = Ticket::with('users','ticketStatus','ticketType','tbl_contacts','priority')->byAuthUser()->latest();
        $tickets = Ticket::with('users','ticketStatus','ticketType','tbl_contacts','priority')->byAuthUser()->latest();

        if (request('filterBy')) {

            $filterBy = request('filterBy');

            $tickets = $tickets->when($filterBy, function($query, $filterBy) {
                $query->withStatus($filterBy);
            })->get();

        } elseif (request('filterByPriority')) {

            $filterByPriority = request('filterByPriority');

            $tickets = $tickets->when($filterByPriority, function($query, $filterByPriority) {
                        // $query->where('priority', $filterByPriority);
                        $query->withPriority($filterByPriority);
                    })->get();

        } else {
            $tickets = $tickets->get(); # if above two condition false then it will execute
        }

        # Below code is used for sorting the ticket based on ticket_status
        # Not working
        // if (request('filterByPriority') == 'Low') {
        //     $tickets = $tickets->sortBy('priority');
        // } elseif (request('filterByPriority') == 'Medium') {
        //     $tickets = $tickets->sortBy('priority');
        // }

        return view('ticketing.user.index',compact('tickets','status','tickettype','priorities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Ticket $tickets)
    {
        $users = User::where('active', '=', 1)->latest()->get();
        $contacts = auth()->user()->tbl_contacts()->get();
        $products = Tbl_products::where('active','=',1)->get();
        $ticket_status = Ticket_status::all();
        $ticket_type = Ticket_type::all();
        $priorities = Priority::all();


        return view('ticketing.user.create',compact('tickets', 'users', 'ticket_status', 'ticket_type','contacts','products','priorities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTicketRequest $request)
    {

        $tickets = $request->validated();

        // $tickets['ticket_number'] = 'TK' . date('Y') . strtoupper(str_random(10));
        $tickets['ticket_number'] = mt_rand(100, getrandmax());

        if($request->hasFile('ticket_image')){

            $originalname = $request->ticket_image->getClientOriginalName();

            # $extension = $request->path->extension();

            $tickets['ticket_image'] = $request->ticket_image->storeAs('ticket', $originalname, 'public'); # filepath=>media/filename.png

        }

        // dd($contactemail);
        $ticketdata = Ticket::create($tickets);

        $find_contact = Tbl_contacts::find($ticketdata->contact_id);

        /*
        # simple mail sending
        Mail::raw($ticketdata->description, function ($message) use ($find_contact, $ticketdata) {
            $message->from(auth()->user()->email);
            $message->to($find_contact['email'])->subject($ticketdata->name);
        });
        */

        Mail::to($find_contact['email'])->send(new UserTicketStore($ticketdata));

        return redirect(route('tickets.index',['all' =>'true']))->with('message', 'Ticket created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        return view('ticketing.user.view', [
            'ticket' => $ticket,
            // 'comments' => Comment::all(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket)
    {
        $ticket_status = Ticket_status::all();

        // $contacts = auth()->user()->tbl_contacts()->get();

        // $contacts = collect(DB::table('tbl_contacts')
        //             ->where('active',1)
        //             ->where(function ($query) {
        //                 $query->where('uid',Auth::user()->id)
        //                 ->orWhere('uid',0);
        //             })
        //             ->get()->toArray())->mapInto(Tbl_contacts::class)->all();

        $contacts = Tbl_contacts::where('active',1)
            ->where(function (Builder $query) {
                return $query->where('uid',Auth::user()->id)
                    ->orWhere('uid',0);
            })
            ->get();

        $products = Tbl_products::where('active','=',1)->get();

        $ticket_type = Ticket_type::all();

        $priorities = Priority::all();

        return view('ticketing.user.edit',compact('ticket','ticket_status','ticket_type','contacts','products','priorities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateTicketRequest $request, Ticket $ticket)
    {
        $input = $request->validated();

        if($request->hasFile('ticket_image')){

            $originalname = $request->ticket_image->getClientOriginalName();

            # $extension = $request->path->extension();

            $input['ticket_image'] = $request->ticket_image->storeAs('ticket', $originalname, 'public'); # filepath=>media/filename.png

        }
        // dd($input);
        $ticket->update($input);

        return redirect(route('tickets.index',['all' => 'true']))->with('message','Ticket updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return back()->with('message', 'Ticket deleted successfully');
    }

    /**
     * closed the tickets
     */
    public function closed(Ticket $ticketid)
    {
        $ticketid->update([
            'status_id' => 3,
        ]);
        // dd($ticketid->tbl_contacts->email);
        $ticketSubject = $ticketid->name;
        $ticketMessage = $ticketid->description;
        $customerEmail= $ticketid->tbl_contacts->email;

        Mail::raw($ticketMessage, function ($message) use ($customerEmail, $ticketSubject) {
            $message->sender(auth()->user()->email);
            $message->to($customerEmail);
            $message->subject('Ticket Closed: '.$ticketSubject);
        });

        return redirect()->back()->with('message','Ticket is closed');
    }

    public function deletealltickets(Request $request) {
        $ids = $request->ids;

        Ticket::whereIn('id',explode(",",$ids))->delete();

        return response()->json(['status'=>true,'message'=>"Tickets successfully deleted."]);
    }

    public function kanban()
    {
        $taskstatus = Ticket_status::with(['tickets' => function ($query) {
            $query->with('users','ticketType','tbl_contacts')->where('user_id', Auth::user()->id);
        }])->get();

        return view('ticketing.user.kanban',compact('taskstatus'));
    }

    public function changedstatus(Request $request)

    {

        $dealIds = $request->input('ticket_id');//get Todo id for task
        // dd(explode("_", $dealIds));
        $dealIds = explode("_", $dealIds);

        $d = count($dealIds) - 1;
        // dd($dealIds[$d]);
        $deal_id = $dealIds[$d];


        //below will check in which card it moved
        $stageIds = $request->input('status_id');//get outcome id

        $stageIds = explode("_", $stageIds);

        $s = count($stageIds) - 1;
        // dd($stageIds[$s]);
        $sfun_id = $stageIds[$s];


        //from card
        $fromIds = $request->input('from_id');

        $fromIds = explode("_", $fromIds);

        $f = count($fromIds) - 1;
        // dd($fromIds[$f]);
        $from_id = $fromIds[$f];



        $fromStage = Ticket_status::find($from_id);
        // dd($fromStage->name);
        $fromStageId = str_replace(" ", "_", $fromStage->name) . '_' . $from_id;


        $toStage = Ticket_status::find($sfun_id);
        // dd($toStage->name);
        $toStageId = str_replace(" ", "_", $toStage->name) . '_' . $sfun_id;

        $deals = Ticket::find($deal_id);

        $deals->status_id = $sfun_id;

        $deal_status = null;

        // if ($sfun_id == 9) {

        //     $deal_status = now();

        // }

        // $deals->completed_at = $deal_status;

        $res = $deals->save();

        if ($res) {

            $data['fromStageId'] =  $fromStageId;

            $data['toStageId'] =  $toStageId;

            return json_encode($data);

        } else {

            return 'error';

        }

    }
}

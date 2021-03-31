<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
//  Model
use App\Tbl_admin_notifications;
use App\Tbl_Accounts;
use App\Tbl_contacts;
use App\Tbl_deals;
use App\Tbl_leads;
use App\User;

class NotificationController extends Controller
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
        $aid = Auth::user()->id;
        $nots = Tbl_admin_notifications::where('aid', $aid)->orderBy('ad_not_id', 'desc')->get();
        $unread = Tbl_admin_notifications::where('aid', $aid)->where('status', 0)->count();
        //        echo json_encode($nots);
        $total = count($nots);
        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Message</th>';
            $formstable .= '<th>Type</th>';
            $formstable .= '<th>Status</th>';
            $formstable .= '<th>User</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($nots as $not) {
                $user = User::find($not->uid);
                $type = "";
                $link = "";
                if ($not->type == 1) {
                    $type = "Accounts";
                    $link = url('admin/accounts/' . $not->id);
                }
                if ($not->type == 2) {
                    $type = "Contacts";
                    $link = url('admin/contacts/' . $not->id);
                }
                if ($not->type == 3) {
                    $type = "Leads";
                    $link = url('admin/leads/' . $not->id);
                }
                if ($not->type == 4) {
                    $type = "Deals";
                    $link = url('admin/deals/' . $not->id);
                }
                if ($not->type == 5) {
                    $type = "Events";
                    $link = "#";
                }
                if ($not->type == 6) {
                    $type = "Web to Lead";
                    $link = url('admin/webtolead/viewformlead/' . $not->id);
                }
                if ($not->type == 7) {
                    $type = "Cart Orders";
                    $link = url('admin/ecommerce/orders/' . $not->id);
                }

                $formstable .= '<tr>';
                $formstable .= '<td><a href="' . url('admin/notifications/read/' . $not->ad_not_id) . '">' . $not->message . '</a></td>';
                $formstable .= '<td>' . $type . '</td>';
                $formstable .= '<td>' . (($not->status == 0) ? 'Unread' : 'Read') . '</td>';
                $formstable .= '<td>' . $user->name . '</td>';
                $formstable .= '<td>' . date("d-m-Y", strtotime($not->created_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<a class="btn btn-primary" href="' . url('admin/notifications/delete/' . $not->ad_not_id) . '">Delete</a>&nbsp;';
                $formstable .= '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        //        echo $formstable;

        $data['total'] = $total;
        $data['unread'] = $unread;
        $data['table'] = $formstable;

        return view('admin.notifications.index')->with("data", $data);
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
    }

    public function delete($id)
    {
        $not = Tbl_admin_notifications::where('ad_not_id', $id)->delete();
        if ($not) {
            return redirect('admin/notifications')->with('success', 'Deleted Successfully');
        } else {
            return redirect('admin/notifications')->with('error', 'Failed. Please try again later');
        }
    }

    public function readNotification($id)
    {
        $not = Tbl_admin_notifications::find($id);
        //        echo json_encode($not);
        //        exit();

        $link = "";
        if ($not->type == 1) {
            $link = 'admin/accounts/' . $not->id;
        }
        if ($not->type == 2) {
            $link = 'admin/contacts/' . $not->id;
        }
        if ($not->type == 3) {
            $link = 'admin/leads/' . $not->id;
        }
        if ($not->type == 4) {
            $link = 'admin/deals/' . $not->id;
        }
        if ($not->type == 5) {
            $link = "";
        }
        if ($not->type == 6) {
            $link = 'admin/webtolead/viewformlead/' . $not->id;
        }
        if ($not->type == 7) {
            $link = 'admin/ecommerce/' . $not->id;
        }
        $not->status = 1;
        $not->save();

        if ($link != "") {
            return redirect($link);
        } else {
            return back();
        }
    }

    public function getUnreadNotificationCount($id)
    {
        return Tbl_admin_notifications::where('aid', $id)->where('status', 0)->count();
    }

    public function getUnreadNotificationsList(Request $request)
    {

        //        return $request->input('aid');
        // <div class="dropdown-divider"></div>
        //                     <a href="#" class="dropdown-item">
        //                         <i class="fas fa-envelope mr-2"></i> 4 new messages
        //                         <span class="float-right text-muted text-sm">3 mins</span>
        //                     </a>

        $id = $request->input('aid');
        //        return json_encode($request->input());
        $nots = Tbl_admin_notifications::where('aid', $id)->where('status', 0)->get();
        $total = count($nots);
        $notlist = "";
        if ($total > 0) {
            // $notlist = "<li>";
            // $notlist .= "<ul class='menu'>";
            foreach ($nots as $not) {
                $notlist .= '<div class="dropdown-divider"></div>';
                $notlist .= '<a href="' . url('admin/notifications/read/' . $not->ad_not_id) . '" class="dropdown-item">';
                $notlist .= '<i class="far fa-circle-o mr-2"></i><small>' . substr($not->message, 0,40) . '</small>';
                $notlist .= '</a>';
                // $notlist .= '<a href="' . url('admin/notifications/read/' . $not->ad_not_id) . '"><i class="fa fa-circle-o"></i>' . $not->message . '</a>';
                // $notlist .= '</li>';
            }
            // $notlist .= "</ul>";
            // $notlist .= "</li>";
        } else {
            // $notlist .= "<li class = 'header' >You have no notifications</li>";
            $notlist .= '<div class="dropdown-divider"></div>';
            $notlist .= '<a href="#" class="dropdown-item">';
            $notlist .= '<i class="far fa-circle-o mr-2"></i>You have no notifications';
            $notlist .= '</a>';
        }
        // $notlist .= "<li class='footer'><a href=" . url('admin/notifications') . ">View all</a></li>";
        $notlist .= '<div class="dropdown-divider"></div>';
        $notlist .= '<a href="' . url('admin/notifications') . '" class="dropdown-item">';
        $notlist .= '<i class="fa fa-circle-o mr-2"></i><small>View All</small>';
        $notlist .= '</a>';

        $data['unread'] = $total;
        $data['notlist'] = $notlist;
        return json_encode($data);
    }
}

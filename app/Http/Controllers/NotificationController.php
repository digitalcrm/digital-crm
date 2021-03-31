<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Tbl_leads;
use App\Tbl_forms;
use App\Tbl_formleads;
use App\Tbl_notifications;

class NotificationController extends Controller
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
    public function index()
    {
        $uid = Auth::user()->id;
        $nots = Tbl_notifications::where('uid', $uid)
            ->orderBy('not_id', 'desc')
            ->get();

        if (count($nots) > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th width="2"></th>';
            $formstable .= '<th>Message</th>';
            $formstable .= '<th>Type</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($nots as $not) {
                $type = '';
                $a = '';
                if ($not->type == 1) {
                    $type = 'Accounts';
                }
                if ($not->type == 2) {
                    $type = 'Contacts';
                }
                if ($not->type == 3) {
                    $type = 'Leads';
                }
                if ($not->type == 4) {
                    $type = 'Deals';
                }
                if ($not->type == 5) {
                    $type = 'Events';
                    $a = '<a href="' . url('calendar/' . $not->id) . '" onclick="return markAsRead(' . $not->id . ')">' . $not->message . '</a>';
                }
                if ($not->type == 6) {
                    $type = 'Web to lead';
                    $a = '<a href="' . url('webtolead/viewformlead/' . $not->id) . '" onclick="return markAsRead(' . $not->id . ')">' . $not->message . '</a>';
                }

                if ($not->type == 7) {
                    $type = 'Cart Orders';
                    $a = '<a href="' . url('ecommerce/' . $not->id) . '" onclick="return markAsRead(' . $not->id . ')">' . $not->message . '</a>';
                }

                $formstable .= '<tr>';
                $formstable .= '<td><input type="checkbox" class="checkAll" id="' . $not->not_id . '"></td>';
                $formstable .= '<td>';
                $formstable .= $a;
                $formstable .= '</td>';
                $formstable .= '<td>' . $type . '</td>';
                $formstable .= '<td>';
                $formstable .= '<div class="">
                        <a class="text-default text-btn-space" href="' . url('notifications/delete/' . $not->not_id) . '">Delete</a>
                    </div>';
                $formstable .= '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        //                $formstable .= '<a href="' . url('webtolead/viewformlead/' . $not->id) . '">' . $not->message . '</a>';
        //                $formstable .= '<form id="logout-form" action="{{ route(\'logout\') }}" method="POST" style="display: none;">
        //                                            @csrf
        //                                        </form>';



        $data['total'] = count($nots);
        $data['table'] = $formstable;

        return view('auth.notifications.index')->with("data", $data);
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
        //
    }


    public function delete($id)
    {
        $account = Tbl_notifications::find($id);
        $res = $account->delete();
        if ($res) {
            return redirect('/notifications')->with('success', 'Deleted Successfully...');
        } else {
            return redirect('/notifications')->with('error', 'Error occured. Please try again ...!');
        }
    }


    public function deleteAll(Request $request)
    {
        $ids = $request->input('id');
        $res = Tbl_notifications::whereIn('not_id', $ids)->delete();
        return $res;
    }
}

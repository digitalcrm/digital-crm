<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Tbl_territory;
use App\Tbl_territory_users;
use App\Tbl_deals;
use App\currency;

class TerritoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('test:territory', ['only' => ['index', 'show', 'create', 'store', 'edit', 'update', 'delete', 'deleteAll']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $uid = Auth::user()->id;
        if (Gate::allows('isUser')) {

            $cr_id = Auth::user()->cr_id;
            $currency = currency::find($cr_id);

            $terrtories = Tbl_territory::where('uid', $uid)
                ->where('active', 1)
                ->with('tbl_territory_users')
                ->orderBy('tid', 'desc')
                ->get();

            $total = count($terrtories);
            $data['total'] = $total;

            if ($total > 0) {
                $formstable = '<div class="table-responsive"><table id="example1" class="table">';
                $formstable .= '<thead>';
                $formstable .= '<tr>';
                $formstable .= '<th width="2"></th>';   //<input type="checkbox" id="selectAll">
                $formstable .= '<th>Name</th>';
                $formstable .= '<th>Users</th>';
                $formstable .= '<th>Deals</th>';
                $formstable .= '<th>Description</th>';
                $formstable .= '<th>Date</th>';
                $formstable .= '<th>Action</th>';
                $formstable .= '</tr>';
                $formstable .= '</thead>';
                $formstable .= '<tbody>';
                foreach ($terrtories as $formdetails) {

                    $subusers = User::whereIn('id', explode(',', $formdetails->subusers))->get(['name']);
                    $subusernames = '';
                    foreach ($subusers as $subuser) {
                        $subusernames .= $subuser->name . ', ';
                    }

                    $deals = Tbl_deals::whereIn('uid', explode(',', $formdetails->subusers))->whereIn('sfun_id', [1, 2, 3, 4])->sum('value');

                    $formstable .= '<tr>';
                    $formstable .= '<td><div class="custom-control custom-checkbox"><input type="checkbox" class="checkAll custom-control-input" id="' . $formdetails->tid . '"><label class="custom-control-label" for="' . $formdetails->tid . '"></label></div></td>';
                    $formstable .= '<td class="table-title"><a href="' . url('territory/' . $formdetails->tid) . '">' . $formdetails->name . '</a>&nbsp;</td>';
                    $formstable .= '<td>' . substr($subusernames, 0, 25) . '</td>';
                    $formstable .= '<td>' . $currency->html_code . ' ' . $deals . '</td>';
                    $formstable .= '<td>' . substr($formdetails->description, 0, 15) . '</td>';
                    $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                    $formstable .= '<td>';
                    $formstable .= '
                    <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                  </button>
                    <div class="dropdown-menu">
                    <a class="dropdown-item text-default text-btn-space" href="' . url('territory/' . $formdetails->tid . '/edit') . '">Edit</a>
                    <a class="dropdown-item text-default text-btn-space" href="' . url('territory/delete/' . $formdetails->tid) . '">Delete</a>
                  </div>
                </div>';
                    $formstable .= '</td>';
                    $formstable .= '</tr>';
                }
                $formstable .= '</tbody>';
                $formstable .= '</table></div>';
            } else {
                $formstable = 'No records available';
            }
            $data['table'] = $formstable;
        } else {
            $terrtories = Tbl_territory_users::where('suid', $uid)->with('tbl_territory')->get();

            $total = count($terrtories);
            $data['total'] = $total;

            if ($total > 0) {
                $formstable = '<div class="table-responsive"><table id="example1" class="table">';
                $formstable .= '<thead>';
                $formstable .= '<tr>';
                $formstable .= '<th>Territory</th>';
                $formstable .= '<th>Date</th>';
                $formstable .= '</tr>';
                $formstable .= '</thead>';
                $formstable .= '<tbody>';
                foreach ($terrtories as $formdetails) {
                    $formstable .= '<tr>';
                    $formstable .= '<td class="table-title">' . $formdetails['tbl_territory']->name . '&nbsp;</td>';
                    $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                    $formstable .= '</tr>';
                }
                $formstable .= '</tbody>';
                $formstable .= '</table></div>';
            } else {
                $formstable = 'No records available';
            }
            $data['table'] = $formstable;
        }
        return view('auth.territory.index')->with("data", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $uid = Auth::user()->id;
        $users = User::where('user', $uid)->get();
        $useroptions = "";
        foreach ($users as $user) {
            $useroptions .= "<option value='" . $user->id . "'>$user->name</option>";
        }
        $data['useroptions'] = $useroptions;
        return view('auth.territory.create')->with('data', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $uid = Auth::user()->id;

        $territory = $request->input('name');
        $subusersArr = $request->input('subusers');
        $description = $request->input('description');
        $exist = Tbl_territory::whereRaw('LOWER(name) LIKE ? ', strtolower($territory))->where('uid', $uid)->get();


        if (count($exist) > 0) {
            return redirect('/territory')->with('error', 'Given territory already exists..!');
        } else if (count($subusersArr) <= 0) {
            return redirect('/territory')->with('error', 'Please select a user...!');
        } else {
            $subusers = implode(",", $subusersArr);

            $formdata = array(
                'uid' => $uid,
                'name' => $territory,
                'subusers' => $subusers,
                'description' => $description,
            );

            $territory = Tbl_territory::create($formdata);
            $tid = $territory->tid;
            $tuformdata = array();
            if ($tid > 0) {
                foreach ($subusersArr as $suid) {
                    $tuformdata[] = array(
                        'uid' => $uid,
                        'tid' => $tid,
                        'suid' => (int) $suid,
                    );
                }

                Tbl_territory_users::insert($tuformdata);

                return redirect('/territory')->with('success', 'Territory Created Successfully...!');
            } else {
                return redirect('/territory')->with('error', 'Error occurred. Please try again...!');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $territory = Tbl_territory::with('tbl_territory_users')->find($id);

        $subusers = $territory->tbl_territory_users;
        if ($subusers != '') {
            $subusersArr = explode(',', $subusers);
            $formstable = '<div class="table-responsive"><table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Sub User</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($subusers as $subuser) {

                $userdetails = User::find($subuser->suid);

                $formstable .= '<tr>';
                $formstable .= '<td class="table-title">' . $userdetails->name . '&nbsp;</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($subuser->created_at)) . '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table></div>';
        } else {
            $formstable = 'No records available';
        }

        $data['territory'] = $territory;
        $data['subuserstable'] = $formstable;
        return view('auth.territory.show')->with('data', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $territory = Tbl_territory::find($id);
        $data['territory'] = $territory;

        $uid = Auth::user()->id;
        $users = User::where('user', $uid)->get();
        $useroptions = "";
        foreach ($users as $user) {
            $tusers = Tbl_territory_users::where('tid', $id)->where('suid', $user->id)->get();
            $selected = (count($tusers) > 0) ? 'selected' : '';
            $useroptions .= "<option value='" . $user->id . "' " . $selected . ">$user->name</option>";
        }
        $data['useroptions'] = $useroptions;



        return view('auth.territory.edit')->with('data', $data);
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

        $uid = Auth::user()->id;
        $territoryName = $request->input('name');
        $subusersArr = $request->input('subusers');
        $description = $request->input('description');
        $subusers = implode(",", $subusersArr);

        $exist = Tbl_territory::whereRaw('LOWER(name) LIKE ? ', strtolower($territoryName))->where('uid', $uid)->where('tid', '!=', $id)->get();

        if (count($exist) > 0) {
            return redirect('/territory/' . $id . '/edit')->with('error', 'Given territory already exists..!');
        } else if (count($subusersArr) <= 0) {
            return redirect('/territory/' . $id . '/edit')->with('error', 'Please select a user...!');
        } else {
            $territory = Tbl_territory::find($id);
            $territory->name = $territoryName;
            $territory->subusers = $subusers;
            $territory->description = $description;
            $territory->save();

            Tbl_territory_users::where('tid', $id)->delete();

            foreach ($subusersArr as $suid) {
                $tuformdata[] = array(
                    'uid' => $uid,
                    'tid' => $id,
                    'suid' => (int) $suid,
                );
            }

            Tbl_territory_users::insert($tuformdata);
        }

        return redirect('/territory')->with('success', 'Territory Updated Successfully...!');
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
        $territory = Tbl_territory::find($id);
        $territory->active = 0;
        $territory->save();
        return redirect('/territory')->with('success', 'Deleted Successfully...!');
    }

    public function setterritoryUsers($tid, $suid)
    {
        $territoryUsers = Tbl_territory_users::where('tid', $tid)->get(['suid']);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->input('id');
        return Tbl_territory::whereIn('tid', $ids)->update(array('active' => 0));
    }

    public function restoreAll(Request $request)
    {
        $ids = $request->input('id');
        return Tbl_territory::whereIn('tid', $ids)->update(array('active' => 1));
    }
}

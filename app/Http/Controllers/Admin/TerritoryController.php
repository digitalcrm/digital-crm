<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//------------Models-------------------
use App\User;
use App\Tbl_territory;
use App\Tbl_territory_users;

class TerritoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('test:territory-list', ['only' => ['index', 'show']]);
        $this->middleware('test:territory-create', ['only' => ['create', 'store']]);
        $this->middleware('test:territory-edit', ['only' => ['edit', 'update']]);
        $this->middleware('test:territory-delete', ['only' => ['destroy']]);
        $this->middleware('test:territory-import', ['only' => ['import', 'importData']]);
        $this->middleware('test:territory-export', ['only' => ['export', 'exportData']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('user', 0)->orderby('id', 'asc')->get(['id', 'name']);
        $uid = 'All';
        $useroptions = "<option value='All' selected>All</option>";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";   // " . $selected . "
        }
        $data['useroptions'] = $useroptions;
        $territories = $this->getTerritories($uid);
        $data['table'] = $territories['table'];
        $data['total'] = $territories['total'];

        return view('admin.territory.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //        $uid = Auth::user()->id;
        $users = User::where('user', 0)->get();
        $useroptions = "";
        $subuseroptions = "";
        $i = 0;
        $selected = '';
        foreach ($users as $user) {
            if ($i == 0) {
                $subusers = User::where('user', $user->id)->get();
                if (count($subusers) > 0) {
                    $selected = 'selected';
                    $i++;

                    foreach ($subusers as $subuser) {
                        $subuseroptions .= "<option value=" . $subuser->id . ">" . $subuser->name . "</option>";   // " . $selected . "
                    }
                }
            } else {
                $selected = '';
            }

            $useroptions .= "<option value='" . $user->id . "' " . $selected . ">$user->name</option>";
        }
        $data['useroptions'] = $useroptions;
        $data['subuseroptions'] = $subuseroptions;
        return view('admin.territory.create')->with('data', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //        echo json_encode($request->input());
        $uid = $request->input('user');
        $territoryName = $request->input('name');
        $subusersArr = $request->input('subusers');
        $subusers = implode(",", $subusersArr);

        $exist = Tbl_territory::whereRaw('LOWER(name) LIKE ? ', strtolower($territoryName))->where('uid', $uid)->get();

        if (count($exist) > 0) {
            return redirect('admin/territory/create')->with('error', 'Given territory already exists..!');
        } else if (count($subusersArr) <= 0) {
            return redirect('admin/territory/create')->with('error', 'Please select a user...!');
        } else {
            $formdata = array(
                'uid' => $uid,
                'name' => $territoryName,
                'subusers' => $subusers,
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

                return redirect('admin/territory')->with('success', 'Territory Created Successfully...!');
            } else {
                return redirect('admin/territory')->with('error', 'Error occurred. Please try again...!');
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
        //        echo json_encode($territory);
        $data['territory'] = $territory;

        $user = User::find($territory->uid);
        $data['user'] = $user;
        $subusers = $territory->tbl_territory_users;
        $subuserslist = User::whereIn('id', $subusers)->get();
        if ($subuserslist != '') {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Sub User</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($subuserslist as $subuser) {
                $subuserdetails = User::find($subuser->suid);
                $formstable .= '<tr>';
                $formstable .= '<td>' . $subuser->name . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($subuser->created_at)) . '</td>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }
        $data['subusertable'] = $formstable;
        return view('admin.territory.show')->with('data', $data);
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

        $uid = $territory->uid;
        $users = User::where('user', $uid)->get();
        $useroptions = "";
        foreach ($users as $user) {
            $tusers = Tbl_territory_users::where('tid', $id)->where('suid', $user->id)->get();
            $selected = (count($tusers) > 0) ? 'selected' : '';
            $useroptions .= "<option value='" . $user->id . "' " . $selected . ">$user->name</option>";
        }
        $data['useroptions'] = $useroptions;
        return view('admin.territory.edit')->with('data', $data);
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
        //        echo json_encode($request->input());


        $territory = Tbl_territory::find($id);

        $uid = $territory->uid;
        $territoryName = $request->input('name');
        $subusersArr = $request->input('subusers');
        $subusers = implode(",", $subusersArr);

        $exist = Tbl_territory::whereRaw('LOWER(name) LIKE ? ', strtolower($territoryName))->where('uid', $uid)->where('tid', '!=', $id)->get();

        if (count($exist) > 0) {
            return redirect('admin/territory/' . $id . '/edit')->with('error', 'Given territory already exists..!');
        } else if (count($subusersArr) <= 0) {
            return redirect('admin/territory/' . $id . '/edit')->with('error', 'Please select a user...!');
        } else {

            $territory->name = $territoryName;
            $territory->subusers = $subusers;
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
        return redirect('admin/territory')->with('success', 'Territory Updated Successfully...!');
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

    public function getTerritories($uid)
    {
        if ($uid == 'All') {
            $territories = Tbl_territory::with('tbl_territory_users')->get();
        } else {
            $territories = Tbl_territory::where('uid', $uid)->with('tbl_territory_users')->get();
        }

        $total = count($territories);
        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>User</th>';
            $formstable .= '<th>Sub Users</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($territories as $formdetails) {

                $user = User::find($formdetails->uid);

                if ($user != '') {

                    $subusers = explode(',', $formdetails->subusers);
                    $subusersName = User::whereIn('id', $subusers)->get(['name']);


                    $formstable .= '<tr>';
                    $formstable .= '<td class="table-title"><a href="' . url('admin/territory/' . $formdetails->tid) . '">' . $formdetails->name . '</a>&nbsp;</td>';
                    $formstable .= '<td>' . $user->name . '</td>';
                    $formstable .= '<td>' . $subusersName->implode('name', ', ') . '</td>';
                    $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                    $formstable .= '<td>';
                    $formstable .= '<div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item text-default text-btn-space" href="' . url('admin/territory/' . $formdetails->tid . '/edit') . '">Edit</a>
                    <a class="dropdown-item text-default text-btn-space" href="' . url('admin/territory/delete/' . $formdetails->tid) . '">Delete</a>
                  </div>
                </div>';
                    $formstable .= '</td>';
                    $formstable .= '</tr>';
                }
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }
        $data['table'] = $formstable;
        $data['total'] = $total;

        return $data;
    }

    public function getSubuseroptions($id)
    {
        $subusers = User::where('user', $id)->get();
        if (count($subusers) > 0) {
            foreach ($subusers as $subuser) {
                $subuseroptions .= "<option value=" . $subuser->id . ">" . $subuser->name . "</option>";
            }
        } else {
            $subuseroptions = "<option value=''>None</option>";
        }
        return $subuseroptions;
    }
}

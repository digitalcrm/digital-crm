<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
// Models
use App\Tbl_user_types;

class UsertypeController extends Controller
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
        $accounttypes = Tbl_user_types::all();
        $total = count($accounttypes);
        if (count($accounttypes) > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>User Type</th>';
            $formstable .= '<th>Weight</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($accounttypes as $types) {
                $formstable .= '<tr>';
                $formstable .= '<td>' . $types->type . '</td>';
                $formstable .= '<td>' . $types->weight . '</td>';
                $formstable .= '<td>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2" href="' . url('admin/usertypes/' . $types->ut_id . '/edit') . '">Edit</a>';
                // $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2"  href="' . url('admin/usertypes/delete/' . $types->actype_id) . '">Delete</a>';
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
        $data['table'] = $formstable;

        return view('admin.usertypes.index')->with("data", $data);
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
        $accounttype = Tbl_user_types::find($id);
        // echo json_encode($accounttype);
        // exit();
        return view('admin.usertypes.edit')->with('data', $accounttype);
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
        // echo json_encode($request->input());
        $this->validate($request, [
            'type' => 'required|max:255|unique:tbl_user_types,type,' . $id . ',ut_id',
            'weight' => 'required',
        ]);

        $units = Tbl_user_types::find($id);

        $units->type = $request->input('type');
        $units->weight = $request->input('weight');
        $units->save();

        return redirect('admin/usertypes')->with('success', 'Updated Successfully...!');
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
}

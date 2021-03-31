<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;
//---------Models---------------
use App\Tbl_units;

class UnitController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware('auth:admin');
    }

    public function index() {
        $data = array();
        $units = Tbl_units::all();
        $total = count($units);
        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Unit</th>';
            $formstable .= '<th>Sort Name</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($units as $types) {
                $formstable .= '<tr>';
                $formstable .= '<td>' . $types->name . '</td>';
                $formstable .= '<td>' . $types->sortname . '</td>';
                $formstable .= '<td>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2" href="' . url('admin/units/' . $types->unit_id . '/edit') . '">Edit</a>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2"  href="#">Delete</a>';
                $formstable .= '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;
        return view('admin.units.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin.units.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
//        echo json_encode($request->input());
//        exit(0);

        $this->validate($request, [
            'name' => 'required|max:255|unique:tbl_units',
            'sortname' => 'required|max:255|unique:tbl_units',
                ], [
            'name.unique' => 'Given unit already exists !',
            'sortname.unique' => 'Given sortname already exists !',
        ]);

//        echo $request->input('account_type');

        $formdata = array(
            'name' => $request->input('name'),
            'sortname' => $request->input('sortname'),
        );
        $types = Tbl_units::create($formdata);
        if ($types->unit_id > 0) {
            return redirect('admin/units')->with('success', 'Created Successfully...!');
        } else {
            return redirect('admin/units')->with('error', 'Error occurred. Please try again...!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $units = Tbl_units::find($id);
        return view('admin.units.edit')->with('data', $units);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        //        'email' => 'unique:emails,emailid,'.$request->id,

        $this->validate($request, [
            'name' => 'required|max:255|unique:tbl_units,name,' . $id . ',unit_id',
            'sortname' => 'required|max:255|unique:tbl_units,sortname,' . $id . ',unit_id',
                ], [
            'name.unique' => 'Given unit already exists !',
            'sortname.unique' => 'Given sortname already exists !',
        ]);

        $units = Tbl_units::find($id);

        $units->name = $request->input('name');
        $units->sortname = $request->input('sortname');
        $units->save();

        return redirect('admin/units')->with('success', 'Updated Successfully...!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

}

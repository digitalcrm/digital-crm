<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;
//---------Models---------------
use App\Tbl_industrytypes;

class IndustryTypeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $industrytypes = Tbl_industrytypes::all();
        $total = count($industrytypes);
        if (count($industrytypes) > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Industry Type</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($industrytypes as $types) {
                $formstable .= '<tr>';
                $formstable .= '<td>' . $types->type . '</td>';
                $formstable .= '<td>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2" href="' . url('admin/industrytypes/' . $types->intype_id . '/edit') . '">Edit</a>&nbsp;';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2" href="' . url('admin/industrytypes/delete/' . $types->intype_id) . '">Delete</a>&nbsp;';
                /*
                  $formstable .= '<div class="btn-group">
                  <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                  <span class="caret"></span>
                  <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                  <li><a href="' . url('admin/industrytypes/' . $types->intype_id . '/edit') . '">Edit</a></li>
                  <li><a href="' . url('admin/industrytypes/delete/' . $types->intype_id) . '">Delete</a></li>
                  </ul>
                  </div>';
                 */
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

        return view('admin.industrytypes.industrytypes')->with("data", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin.industrytypes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
//        echo json_encode($request->input());
        $this->validate($request, [
            'type' => 'required|max:255|unique:tbl_industrytypes',
        ]);

//        echo $request->input('account_type');

        $formdata = array(
            'type' => $request->input('type'),
        );
        $types = Tbl_industrytypes::create($formdata);
        if ($types->intype_id > 0) {
            return redirect('admin/industrytypes')->with('success', 'Created Successfully...!');
        } else {
            return redirect('admin/industrytypes')->with('error', 'Error occurred. Please try again...!');
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
        $accounttype = Tbl_industrytypes::find($id);
        return view('admin.industrytypes.edit')->with('data', $accounttype);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $types = Tbl_industrytypes::find($id);
        $types->type = $request->input('type');
        $types->save();
        return redirect('admin/industrytypes')->with('success', 'Updated Successfully...!');
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

    public function delete($id) {
        $types = Tbl_industrytypes::find($id);
        $types->delete();
        return redirect('admin/industrytypes')->with('success', 'Deleted Successfully...!');
    }

}

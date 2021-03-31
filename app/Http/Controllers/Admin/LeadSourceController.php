<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;
//---------Models---------------
use App\Tbl_leadsource;

class LeadSourceController extends Controller {

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
        $leadsource = Tbl_leadsource::all();
        $total = count($leadsource);
        if (count($leadsource) > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Lead Source</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($leadsource as $types) {
                $formstable .= '<tr>';
                $formstable .= '<td>' . $types->leadsource . '</td>';
                $formstable .= '<td>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2" href="' . url('admin/leadsource/' . $types->ldsrc_id . '/edit') . '">Edit</a>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2" href="' . url('admin/leadsource/delete/' . $types->ldsrc_id) . '">Delete</a>';
                /*
                $formstable .= '<div class="btn-group">
                      <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                      </button>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="' . url('admin/leadsource/' . $types->ldsrc_id . '/edit') . '">Edit</a></li>
                        <li><a href="' . url('admin/leadsource/delete/' . $types->ldsrc_id) . '">Delete</a></li>
                      </ul>
                    </div>';
                 * 
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

        return view('admin.leadsource.leadsource')->with("data", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin.leadsource.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'leadsource' => 'required|max:255|unique:tbl_leadsource',
        ]);

//        echo $request->input('status');

        $formdata = array(
            'leadsource' => $request->input('leadsource'),
        );

//        echo json_encode($formdata);

        $types = Tbl_leadsource::create($formdata);
        if ($types->ldsrc_id > 0) {
            return redirect('admin/leadsource')->with('success', 'Created Successfully...!');
        } else {
            return redirect('admin/leadsource')->with('error', 'Error occurred. Please try again...!');
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
        $accounttype = Tbl_leadsource::find($id);
        return view('admin.leadsource.edit')->with('data', $accounttype);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $types = Tbl_leadsource::find($id);
        $types->leadsource = $request->input('leadsource');
        $types->save();
        return redirect('admin/leadsource')->with('success', 'Updated Successfully...!');
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
        $types = Tbl_leadsource::find($id);
        $types->delete();
        return redirect('admin/leadsource')->with('success', 'Deleted Successfully...!');
    }

}

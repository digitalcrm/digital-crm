<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;
//---------Models---------------
use App\Tbl_salesfunnel;

class DealStageController extends Controller {

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
        $accounttypes = Tbl_salesfunnel::all();
        $total = count($accounttypes);
        if (count($accounttypes) > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Deal Stage</th>';
            $formstable .= '<th>Color</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($accounttypes as $types) {
                $formstable .= '<tr>';
                $formstable .= '<td>' . $types->salesfunnel . '</td>';
                $formstable .= '<td><label style="color:#' . $types->color . ';">color</label></td>';
                $formstable .= '<td>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2"  href="' . url('admin/dealstage/' . $types->sfun_id . '/edit') . '">Edit</a>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2"  href="' . url('admin/dealstage/delete/' . $types->sfun_id) . '">Delete</a>';
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

        return view('admin.dealstage.dealstage')->with("data", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin.dealstage.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $this->validate($request, [
            'salesfunnel' => 'required|max:255|unique:tbl_salesfunnel',
            'color' => 'required|max:255',
        ]);

//        echo $request->input('account_type');

        $formdata = array(
            'salesfunnel' => $request->input('salesfunnel'),
            'color' => trim($request->input('color'), '#') ,
        );

        $types = Tbl_salesfunnel::create($formdata);
        if ($types->sfun_id > 0) {
            return redirect('admin/dealstage')->with('success', 'Created Successfully...!');
        } else {
            return redirect('admin/dealstage')->with('error', 'Error occurred. Please try again...!');
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
        $accounttype = Tbl_salesfunnel::find($id);
        return view('admin.dealstage.edit')->with('data', $accounttype);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $types = Tbl_salesfunnel::find($id);
        $types->salesfunnel = $request->input('salesfunnel');
        $types->color = $request->input('color');
        $types->save();
        return redirect('admin/dealstage')->with('success', 'Updated Successfully...!');
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
        $types = Tbl_salesfunnel::find($id);
        $types->delete();
        return redirect('admin/dealstage')->with('success', 'Deleted Successfully...!');
    }

}

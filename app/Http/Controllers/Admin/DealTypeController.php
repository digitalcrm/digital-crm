<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
//---------Models---------------
use App\Tbl_deal_types;

class DealTypeController extends Controller
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
        $accounttypes = Tbl_deal_types::all();
        $total = count($accounttypes);
        if (count($accounttypes) > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Deal Type</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($accounttypes as $types) {
                $formstable .= '<tr>';
                $formstable .= '<td>' . $types->type . '</td>';
                $formstable .= '<td>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2"  href="' . url('admin/dealtypes/' . $types->dl_id . '/edit') . '">Edit</a>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2"  href="' . url('admin/dealtypes/delete/' . $types->dl_id) . '">Delete</a>';
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

        return view('admin.dealtypes.index')->with("data", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.dealtypes.create');
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
        // echo json_encode($request->input());

        $this->validate($request, [
            'type' => 'required|max:255|unique:tbl_deal_types',
        ]);

        $formdata = array(
            'type' => $request->input('type'),
        );
        $types = Tbl_deal_types::create($formdata);
        if ($types->dl_id > 0) {
            return redirect('admin/dealtypes')->with('success', 'Deal type Created Successfully...!');
        } else {
            return redirect('admin/dealtypes')->with('error', 'Error occurred. Please try again...!');
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
        $accounttype = Tbl_deal_types::find($id);
        return view('admin.dealtypes.edit')->with('data', $accounttype);
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
        // exit();


        $this->validate($request, [
            'type' => 'required|max:255|unique:tbl_deal_types,type,' . $id . ',dl_id',
        ], [
            'type.unique' => 'Given Deal Type already exists !',
        ]);

        $type = $request->input('type');
        $res = Tbl_deal_types::where('dl_id', $id)->update(['type' => $type]);
        if ($res) {
            return redirect('admin/dealtypes')->with('success', 'Updated Successfully...!');
        } else {
            return redirect('admin/dealtypes')->with('error', 'Error occurred. Please try again...!');
        }
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
        $res = Tbl_deal_types::where('dl_id', $id)->delete();
        if ($res) {
            return redirect('admin/dealtypes')->with('success', 'Deleted Successfully...!');
        } else {
            return redirect('admin/dealtypes')->with('error', 'Error occurred. Please try again...!');
        }
    }
}

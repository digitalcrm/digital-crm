<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
//---------Models---------------
use App\Tbl_rdpriority;

class RdpriorityController extends Controller
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

        $accounttypes = Tbl_rdpriority::all();
        // echo json_encode($accounttypes);
        // exit();
        $total = count($accounttypes);
        if (count($accounttypes) > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Rd Priority</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($accounttypes as $types) {
                $formstable .= '<tr>';
                $formstable .= '<td>' . $types->priority . '</td>';
                $formstable .= '<td>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2"  href="' . url('admin/rdprioritys/' . $types->rdpr_id . '/edit') . '">Edit</a>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2"  href="' . url('admin/rdprioritys/delete/' . $types->rdpr_id) . '">Delete</a>';
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

        return view('admin.rdpriority.index')->with("data", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.rdpriority.create');
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
            'priority' => 'required|max:255|unique:tbl_rd_priority',
        ]);

        $formdata = array(
            'priority' => $request->input('priority'),
        );

        $types = Tbl_rdpriority::create($formdata);

        if ($types->rdpr_id > 0) {
            return redirect('admin/rdprioritys')->with('success', 'Created Successfully...!');
        } else {
            return redirect('admin/rdprioritys')->with('error', 'Error occurred. Please try again...!');
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
        $accounttype = Tbl_rdpriority::find($id);
        return view('admin.rdpriority.edit')->with('data', $accounttype);
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

        $this->validate($request, [
            'priority' => 'required|max:255|unique:tbl_rd_priority,priority,' . $id . ',rdpr_id',
        ]);

        $formdata = array(
            'priority' => $request->input('priority'),
        );

        $res = Tbl_rdpriority::where('rdpr_id', $id)->update($formdata);

        if ($res > 0) {
            return redirect('admin/rdprioritys')->with('success', 'Updated Successfully...!');
        } else {
            return redirect('admin/rdprioritys')->with('error', 'Error occurred. Please try again...!');
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
        $types = Tbl_rdpriority::find($id);
        $types->delete();
        return redirect('admin/rdprioritys')->with('success', 'Deleted Successfully...!');
    }
}

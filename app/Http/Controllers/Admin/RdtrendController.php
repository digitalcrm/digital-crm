<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
//---------Models---------------
use App\Tbl_rdtrend;

class RdtrendController extends Controller
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
        $accounttypes = Tbl_rdtrend::all();
        $total = count($accounttypes);
        if (count($accounttypes) > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Rd Trend</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($accounttypes as $types) {
                $formstable .= '<tr>';
                $formstable .= '<td>' . $types->trend . '</td>';
                $formstable .= '<td>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2"  href="' . url('admin/rdtrends/' . $types->rdtr_id . '/edit') . '">Edit</a>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2"  href="' . url('admin/rdtrends/delete/' . $types->rdtr_id) . '">Delete</a>';
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

        return view('admin.rdtrends.index')->with("data", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.rdtrends.create');
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

        $this->validate($request, [
            'trend' => 'required|max:255|unique:tbl_rd_trends',
        ]);

        $formdata = array(
            'trend' => $request->input('trend'),
        );

        $types = Tbl_rdtrend::create($formdata);

        if ($types->rdtr_id > 0) {
            return redirect('admin/rdtrends')->with('success', 'Created Successfully...!');
        } else {
            return redirect('admin/rdtrends')->with('error', 'Error occurred. Please try again...!');
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
        $accounttype = Tbl_rdtrend::find($id);
        return view('admin.rdtrends.edit')->with('data', $accounttype);
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
        $this->validate($request, [
            'trend' => 'required|max:255|unique:tbl_rd_trends,trend,' . $id . ',rdtr_id',
        ]);

        $formdata = array(
            'trend' => $request->input('trend'),
        );

        $res = Tbl_rdtrend::where('rdtr_id', $id)->update($formdata);

        if ($res > 0) {
            return redirect('admin/rdtrends')->with('success', 'Updated Successfully...!');
        } else {
            return redirect('admin/rdtrends')->with('error', 'Error occurred. Please try again...!');
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
        $types = Tbl_rdtrend::find($id);
        $types->delete();
        return redirect('admin/rdtrends')->with('success', 'Deleted Successfully...!');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
//---------Models---------------
use App\Tbl_paymentstatus;

class PaymentStatusController extends Controller
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
        $accounttypes = Tbl_paymentstatus::all();
        $total = count($accounttypes);
        if (count($accounttypes) > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Payment Status</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($accounttypes as $types) {
                $formstable .= '<tr>';
                $formstable .= '<td>' . $types->status . '</td>';
                $formstable .= '<td>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2"  href="' . url('admin/paymentstatus/' . $types->paystatus_id . '/edit') . '">Edit</a>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2"  href="' . url('admin/paymentstatus/delete/' . $types->paystatus_id) . '">Delete</a>';
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

        return view('admin.paymentstatus.index')->with("data", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.paymentstatus.create');
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
            'status' => 'required|max:255|unique:tbl_paymentstatus',
        ]);

        $formdata = array(
            'status' => $request->input('status'),
        );

        $types = Tbl_paymentstatus::create($formdata);

        if ($types->paystatus_id > 0) {
            return redirect('admin/paymentstatus')->with('success', 'Payment Status Created Successfully...!');
        } else {
            return redirect('admin/paymentstatus')->with('error', 'Error occurred. Please try again...!');
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
        $accounttype = Tbl_paymentstatus::find($id);
        return view('admin.paymentstatus.edit')->with('data', $accounttype);
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
            'status' => 'required|max:255|unique:tbl_paymentstatus,status,' . $id . ',paystatus_id',
        ], [
            'status.unique' => 'Given Payment Status already exists !',
        ]);

        $type = $request->input('status');
        $res = Tbl_paymentstatus::where('paystatus_id', $id)->update(['status' => $type]);
        if ($res) {
            return redirect('admin/paymentstatus')->with('success', 'Updated Successfully...!');
        } else {
            return redirect('admin/paymentstatus')->with('error', 'Error occurred. Please try again...!');
        }
    }

    public function delete($id)
    {
        $res = Tbl_paymentstatus::where('paystatus_id', $id)->delete();
        if ($res) {
            return redirect('admin/paymentstatus')->with('success', 'Deleted Successfully...!');
        } else {
            return redirect('admin/paymentstatus')->with('error', 'Error occurred. Please try again...!');
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
}

<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
//------------Models-----------------
use App\Tbl_tax;

class TaxController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $uid = Auth::user()->id;
        $taxlist = Tbl_tax::with('tbl_invoice_products')->where('active', 1)->where('uid', $uid)->get();    //->where('active', 1)
        //        echo json_encode($accounts);
        $total = count($taxlist);
        if ($total > 0) {
            $formstable = '<table id="accountsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th width="2"></th>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Tax</th>';
            $formstable .= '<th>Products</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($taxlist as $formdetails) {
                $products = ($formdetails->tbl_invoice_products != '') ? count($formdetails->tbl_invoice_products) : 0;
                $formstable .= '<tr>';
                $formstable .= '<td>
				<div class="custom-control custom-checkbox mr-sm-2">
				<input type="checkbox" class="checkAll custom-control-input" id="' . $formdetails->tax_id . '"><label class="custom-control-label" for="' . $formdetails->tax_id . '"></label>
				</div>
				</td>';
                $formstable .= '<td><a href="' . url('tax/' . $formdetails->tax_id) . '">' . $formdetails->name . '</td>';
                $formstable .= '<td>' . $formdetails->tax . ' %</td>';
                $formstable .= '<td>' . $products . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                  </button>
                  <div class="dropdown-menu" role="menu">
                    <a class="dropdown-item text-default text-btn-space" href="' . url('tax/' . $formdetails->tax_id . '/edit') . '">Edit</a>
                    <a class="dropdown-item text-default text-btn-space" href="' . url('tax/deletetax/' . $formdetails->tax_id) . '">Delete</a>
                  </div>
                </div>';

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

        return view('auth.tax.index')->with("data", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.tax.create')->with("data", '');
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

        $uid = Auth::user()->id;

        $this->validate($request, [
            'name' => 'required|max:255',
            'tax' => 'required|numeric|max:255',
        ]);

        $formdata = array(
            'uid' => $uid,
            'name' => $request->input('name'),
            'tax' => $request->input('tax'),
        );

        $tax = Tbl_tax::create($formdata);
        if ($tax->tax_id) {
            return redirect('/tax')->with('success', 'Created Successfully...!');
        } else {
            return redirect('/tax')->with('error', 'Error occurred. Please try later...!');
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
        $tax = Tbl_tax::find($id);
        return view('auth.tax.show')->with("data", $tax);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tax = Tbl_tax::find($id);
        return view('auth.tax.edit')->with("data", $tax);
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


        $uid = Auth::user()->id;

        $this->validate($request, [
            'name' => 'required|max:255',
            'tax' => 'required|numeric|max:255',
        ]);

        $name = $request->input('name');
        $tax = $request->input('tax');


        $exist = Tbl_tax::where(strtolower('name'), strtolower($name))->where('uid', $uid)->where('tax_id', '!=', $id)->get();

        //        echo json_encode($exist);

        if (count($exist) > 0) {
            return redirect('/tax' . $id . '/edit')->with('error', 'Given name already taken...!');
        } else {
            $taxDetails = Tbl_tax::find($id);
            $taxDetails->name = $name;
            $taxDetails->tax = $tax;
            $taxDetails->save();
            return redirect('/tax')->with('success', 'Updated Successfully...!');
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

    public function deleteAll(Request $request)
    {
        $ids = $request->input('id');
        return Tbl_tax::whereIn('tax_id', $ids)->update(array('active' => 0));
    }

    public function deleteTax($ids)
    {

        $res = Tbl_tax::where('tax_id', $ids)->update(array('active' => 0));
        if ($res) {
            return redirect('/tax')->with('success', 'Deleted Successfully.');
        } else {
            return redirect('/tax')->with('error', 'Error occurred. Please try again later...!');
        }
    }
}

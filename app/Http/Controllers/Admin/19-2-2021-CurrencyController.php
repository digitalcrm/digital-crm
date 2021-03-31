<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Auth;
//---------Models---------------
use App\currency;

class CurrencyController extends Controller {

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
        $currencies = currency::all();
        $total = count($currencies);
        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Currency</th>';
            $formstable .= '<th>Code</th>';
            $formstable .= '<th>Html Code</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($currencies as $currency) {
                $default = '';
                if ($currency->status == 1) {
                    $default = '<span class = "badge badge-success">Default</span>';
                } else {
                    $default = '';
                }

                $formstable .= '<tr>';
                $formstable .= '<td>' . $currency->name . '&nbsp;' . $default . '</td>';
                $formstable .= '<td>' . $currency->code . '</td>';
                $formstable .= '<td>' . $currency->html_code . '</td>';
                $formstable .= '<td>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2"  href="' . url('admin/currency/' . $currency->cr_id . '/edit') . '">Edit</a>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2"  href="#">Delete</a>';
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

        return view('admin.currency.index')->with("data", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin.currency.create');
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
            'name' => 'required|max:255|unique:currency',
            'code' => 'required|max:25|unique:currency',
            'html_code' => 'required|max:25',
                ], [
            'name.unique' => 'Given currency already exists !',
            'code.unique' => 'Given code already exists !',
            'name.required' => 'Currency is required !',
            'code.required' => 'Code is required !',
        ]);

        $default = 0;
        if ($request->input('default') != '') {
            $default = ($request->input('default') == 'on') ? 1 : 0;
        }

        $formdata = array(
            'name' => $request->input('name'),
            'code' => strtoupper($request->input('code')),
            'html_code' => $request->input('html_code'),
            'status' => 0,
        );

        $currency = currency::create($formdata);
        if ($currency->cr_id > 0) {

            if ($default > 0) {

                $defaultCurrency = currency::where('status', 1)->first();
                $defaultCurrency->status = 0;
                $defaultCurrency->save();

                $currency->status = 1;
                $currency->save();
            }

            return redirect('/admin/currency')->with('success', 'Currency created successfully');
        } else {
            return redirect('/admin/currency')->with('error', 'Error occurred. Please try again later');
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
        $currency = currency::find($id);
        return view('admin.currency.edit')->with("data", $currency);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
//        echo json_encode($request->input());
//  unique:tbl_units,name,' . $id . ',unit_id
        $currency = currency::find($id);

        $this->validate($request, [
            'name' => 'required|max:255',
            'code' => 'required|max:25',
            'html_code' => 'required|max:25',
                ], [
            'name.required' => 'Currency is required !',
            'code.required' => 'Code is required !',
        ]);

        $name = $request->input('name');
        $code = $request->input('code');
        $html_code = $request->input('html_code');

//        $name_exists = currency::where(strtolower('name'), strtolower($name))->where('cr_id', '!=', $id)->count();
//        $code_exists = currency::where(strtolower('code'), strtolower($code))->where('cr_id', '!=', $id)->count();
//        if ($name_exists > 0) {
//            return redirect('/admin/currency/' . $id . '/edit')->with('error', 'Given currency already exists !');
//        } else if ($code_exists > 0) {
//            return redirect('/admin/currency/' . $id . '/edit')->with('error', 'Given code already exists !');
//        } else {
        $currency->name = $name;
        $currency->code = $code;
        $currency->html_code = $html_code;
        $currency->save();

        $default = 0;
        if ($request->input('default') != '') {

            $defaultCurrency = currency::where('status', 1)->first();
            $defaultCurrency->status = 0;
            $defaultCurrency->save();

            $currency->status = 1;
            $currency->save();
        }
//        }
        return redirect('/admin/currency/')->with('success', 'Updated Successfully!');
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

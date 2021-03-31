<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Auth;
//---------Models---------------
use App\Tbl_countries;

class CountryController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
        $countries = Tbl_countries::all();
        $total = count($countries);
        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Country</th>';
            $formstable .= '<th>Sort Name</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($countries as $country) {
                $formstable .= '<tr>';
                $formstable .= '<td>' . $country->name . '</td>';
                $formstable .= '<td>' . $country->sortname . '</td>';
                $formstable .= '<td>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2"  href="' . url('admin/country/' . $country->id . '/edit') . '">Edit</a>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2"  href="' . url('admin/country/delete/' . $country->id) . '">Delete</a>';
                //' . url('admin/country/delete/' . $types->sfun_id) . '
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

        return view('admin.country.index')->with("data", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.country.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255|unique:tbl_countries',
            'sortname' => 'required|max:25|unique:tbl_countries',
        ], [
            'name.unique' => 'Given country already exists !',
            'sortname.unique' => 'Given sort name already exists !',
        ]);

        $formdata = array(
            'sortname' => strtoupper($request->input('sortname')),
            'name' => $request->input('name'),
        );

        $country = Tbl_countries::create($formdata);

        if ($country->id > 0) {
            return redirect('/admin/country')->with('success', 'Country created successfully');
        } else {
            return redirect('/admin/country')->with('error', 'Error occurred. Please try again later');
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
        $country = Tbl_countries::find($id);
        return view('admin.country.show')->with('data', $country);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $country = Tbl_countries::find($id);
        return view('admin.country.edit')->with('data', $country);
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

        $this->validate($request, [
            'name' => 'required|max:255|unique:tbl_countries',
            'sortname' => 'required|max:25|unique:tbl_countries',
        ], [
            'name.unique' => 'Given country already exists !',
            'sortname.unique' => 'Given sort name already exists !',
        ]);


        /*
         * , [
          'name.unique' => 'Given country already exists !',
          'sortname.unique' => 'Given sort name already exists !',
          ]
         */

        $name = $request->input('name');
        $sortname = $request->input('sortname');

        $country = Tbl_countries::find($id);
        $country->name = $name;
        $country->sortname = $sortname;
        $country->save();

        return redirect('/admin/country')->with('success', 'Country updated successfully!');
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
        //
        $country = Tbl_countries::where('id', $id)->delete();    //Tbl_countries::find($id);
        if ($country) {
            return redirect('/admin/country')->with('success', 'Country Deleted successfully!');
        } else {
            return redirect('/admin/country')->with('error', 'Failed. Try again later...!');
        }
    }
}

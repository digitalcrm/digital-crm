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
use App\Tbl_states;

class StateController extends Controller {

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
        $country = $this->getCountryOptions();
        $id = $country['cid'];
        $data = $this->getStates($id);
        $data['countryOptions'] = $country['countryOptions'];
        return view('admin.states.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $countryOptions = $this->getCountryOptions();
        return view('admin.states.create')->with('data', $countryOptions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required|max:255',
            'country' => 'required',
                ], [
            'name.required' => 'Please enter state!',
            'country.required' => 'Please select country!',
        ]);

        $name = $request->input('name');
        $country = $request->input('country');
        $exists = Tbl_states::where(strtolower('name'), strtolower($name))->where('country_id', $country)->count();

        if ($exists > 0) {
            return redirect('/admin/states/create')->with('error', 'Given state already exists!');
        } else {
            $formdata = array(
                'country_id' => $country,
                'name' => ucwords($name),
            );

            $state = Tbl_states::create($formdata);

            if ($state->id > 0) {
                return redirect('/admin/states')->with('success', 'State created successfully');
            } else {
                return redirect('/admin/states')->with('error', 'Error occurred. Please try again later');
            }
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
        $state = Tbl_states::find($id);
        return view('admin.states.edit')->with('data', $state);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $state = Tbl_states::find($id);

        $this->validate($request, [
            'name' => 'required|max:255',
                ], [
            'name.required' => 'Please enter state!',
        ]);

        $name = $request->input('name');
        $exists = Tbl_states::where(strtolower('name'), strtolower($name))->where('id', '!=', $id)->where('country_id', $state->country_id)->count();

        if ($exists > 0) {
            return redirect('/admin/states/' . $id . '/edit')->with('error', 'Given state already exists!');
        } else {

            $state->name = $name;
            $state->save();
            return redirect('/admin/states')->with('success', 'State updated successfully');
        }
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

    public function getCountryOptions() {
        $countries = Tbl_countries::get(['id', 'name']);
        $countryOptions = '';
        $i = 0;
        $cid = 0;
        $selected = '';
        foreach ($countries as $country) {
            if ($i == 0) {
                $cid = $country->id;
                $selected = 'selected';
                $i++;
            } else {
                $selected = '';
            }
            $countryOptions .= '<option value="' . $country->id . '" ' . $selected . '>' . $country->name . '</option>';
        }
        $data['cid'] = $cid;
        $data['countryOptions'] = $countryOptions;
        return $data;
    }

    public function getStates($id) {
        if ($id == 'All') {
            $states = Tbl_states::get(['id', 'name']);
        } else {
            $states = Tbl_states::where('country_id', $id)->get(['id', 'name']);
        }

        $total = count($states);
        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($states as $state) {
                $formstable .= '<tr>';
                $formstable .= '<td>' . $state->name . '</td>';
                $formstable .= '<td>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2"  href="' . url('admin/states/' . $state->id . '/edit') . '">Edit</a>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2"  href="#">Delete</a>';
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

        return $data;
    }

    public function getCountryStates(Request $request) {
        $id = $request->input('country');
        $data = $this->getStates($id);
        return json_encode($data);
    }

}

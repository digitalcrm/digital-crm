<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;
//---------Models---------------
use App\Tbl_leadstatus;

class LeadStatusController extends Controller
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
        $leadstatus = Tbl_leadstatus::all();
        $total = count($leadstatus);
        if (count($leadstatus) > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Lead Status</th>';
            $formstable .= '<th>Deal</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($leadstatus as $types) {
                $formstable .= '<tr>';
                $formstable .= '<td>' . $types->status . '</td>';
                $formstable .= '<td>' . (($types->deal == 1) ? 'Yes' : 'No') . '</td>';
                $formstable .= '<td>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2" href="' . url('admin/leadstatus/' . $types->ldstatus_id . '/edit') . '">Edit</a>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2" href="' . url('admin/leadstatus/delete/' . $types->ldstatus_id) . '">Delete</a>';
                /*
                  $formstable .= '<div class="btn-group">
                  <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                  <span class="caret"></span>
                  <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                  <li><a href="' . url('admin/leadstatus/' . $types->ldstatus_id . '/edit') . '">Edit</a></li>
                  <li><a href="' . url('admin/leadstatus/delete/' . $types->ldstatus_id) . '">Delete</a></li>
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

        return view('admin.leadstatus.leadstatus')->with("data", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.leadstatus.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //        echo json_encode($request->input('event'));
        //        exit();

        $this->validate($request, [
            'status' => 'required|max:255|unique:tbl_leadstatus',
        ]);

        $event = ($request->input('event') !== null) ? 1 : 0;

        //        echo $request->input('status');

        $formdata = array(
            'status' => $request->input('status'),
            'deal' => $event,
        );

        //        echo json_encode($formdata);
        //        exit();

        $types = Tbl_leadstatus::create($formdata);
        if ($types->ldstatus_id > 0) {
            return redirect('admin/leadstatus')->with('success', 'Created Successfully...!');
        } else {
            return redirect('admin/leadstatus')->with('error', 'Error occurred. Please try again...!');
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
        $accounttype = Tbl_leadstatus::find($id);
        return view('admin.leadstatus.edit')->with('data', $accounttype);
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
        //        exit();

        $event = ($request->input('deal') !== null) ? 1 : 0;

        $types = Tbl_leadstatus::find($id);
        $types->status = $request->input('status');
        $types->deal = $event;
        $types->save();
        return redirect('admin/leadstatus')->with('success', 'Updated Successfully...!');
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
        $types = Tbl_leadstatus::find($id);
        $types->delete();
        return redirect('admin/leadstatus')->with('success', 'Deleted Successfully...!');
    }
}

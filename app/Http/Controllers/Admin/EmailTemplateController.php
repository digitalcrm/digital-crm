<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;
//---------Models---------------
use App\Tbl_emailtemplates;
use App\Tbl_emailcategory;

class EmailTemplateController extends Controller {

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
        $templates = Tbl_emailtemplates::all(); //with('tbl_emailcategory')->
//        echo json_encode($templates);
//        exit(0);
        $total = count($templates);
        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
//            $formstable .= '<th>Category</th>';
            $formstable .= '<th>Subject</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($templates as $temp) {
                $formstable .= '<tr>';
//                $formstable .= '<td>' . $temp->tbl_emailcategory->category . '</td>';
                $formstable .= '<td>' . $temp->subject . '</td>';
                $formstable .= '<td>';
//                $formstable .= '<div class="btn-group">
//                      <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
//                        <span class="caret"></span>
//                        <span class="sr-only">Toggle Dropdown</span>
//                      </button>
//                      <ul class="dropdown-menu" role="menu">
//                        <li><a href="' . url('admin/emailtemplates/' . $temp->temp_id . '/edit') . '">Edit</a></li>
//                        <li><a href="' . url('admin/emailtemplates/delete/' . $temp->temp_id) . '">Delete</a></li>
//                      </ul>
//                    </div>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2" href="' . url('admin/emailtemplates/' . $temp->temp_id . '/edit') . '">Edit</a>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2" href="' . url('admin/emailtemplates/delete/' . $temp->temp_id) . '">Delete</a>';
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

        return view('admin.emailtemplates.templates')->with("data", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
//        $departments = Tbl_emailcategory::all();
//        $options = '<option value="0">Select Email Category</option>';
//        foreach ($departments as $types) {
//            $options .= '<option value="' . $types->ecat_id . '">' . $types->category . '</option>';
//        }
//        $data['department_options'] = $options;
//        ->with('data', $data)
        return view('admin.emailtemplates.create');
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
//            'ecat_id' => 'required|max:255',
            'subject' => 'required|max:255',
            'message' => 'required|max:1000',
        ]);

//        echo $request->input('status');

        $formdata = array(
            'name' => $request->input('name'),
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
//            'ecat_id' => $request->input('ecat_id'),
        );

//        echo json_encode($formdata);

        $types = Tbl_emailtemplates::create($formdata);
        if ($types->temp_id > 0) {
            return redirect('admin/emailtemplates')->with('success', 'Created Successfully...!');
        } else {
            return redirect('admin/emailtemplates')->with('error', 'Error occurred. Please try again...!');
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
        $accounttype = Tbl_emailtemplates::find($id);
        $data['emailtemplate'] = $accounttype;

//        $departments = Tbl_emailcategory::all();
//        $options = '<option value="">Select Email Category</option>';
//        foreach ($departments as $types) {
//            $selected = ($types->ecat_id == $accounttype->ecat_id) ? 'selected' : '';
//            $options .= '<option value="' . $types->ecat_id . '" ' . $selected . '>' . $types->category . '</option>';
//        }
//        $data['department_options'] = $options;
//        echo json_encode($data);
//        exit(0);

        return view('admin.emailtemplates.edit')->with('data', $data);
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

        $this->validate($request, [
//            'ecat_id' => 'required|max:255',
            'subject' => 'required|max:255',
            'message' => 'required|min:25',
        ]);

        $types = Tbl_emailtemplates::find($id);
//        $types->ecat_id = $request->input('ecat_id');
        $types->subject = $request->input('subject');
        $types->message = $request->input('message');
        $types->save();
        return redirect('admin/emailtemplates')->with('success', 'Updated Successfully...!');
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
        $types = Tbl_emailtemplates::find($id);
        $types->delete();
        return redirect('admin/emailtemplates')->with('success', 'Deleted Successfully...!');
    }

}

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;
//--------------Models-----------------
use App\Tbl_emails;
use App\Tbl_emailcategory;

class EmailController extends Controller {

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
        $emails = Tbl_emails::all();    //with('tbl_emailcategory')->get()
        $total = count($emails);
        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Mail</th>';
//            $formstable .= '<th>Category</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($emails as $temp) {
                $formstable .= '<tr>';
                $formstable .= '<td>' . $temp->mail . '</td>';
//                $formstable .= '<td>' . $temp->tbl_emailcategory->category . '</td>';
                $formstable .= '<td>';
                $formstable .= '<a href="' . url('admin/emails/' . $temp->mail_id . '/edit') . '" class="btn badge badge-secondary py-1 px-2 mr-2">Edit</a></li>
                        <a href="" class="btn badge badge-secondary py-1 px-2 mr-2">Delete</a>';
                //' . url('admin/emails/delete/' . $temp->mail_id) . '
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

        return view('admin.emails.index')->with("data", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
//        $departments = Tbl_emailcategory::all();
//        $options = '<option value="">Select Email Category</option>';
//        foreach ($departments as $types) {
//            $options .= '<option value="' . $types->ecat_id . '">' . $types->category . '</option>';
//        }
//        $data['department_options'] = $options;
//        ->with("data", $data)
        return view('admin.emails.create');
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
            'mail' => 'required|email|max:255',
//            'ecat_id' => 'required|max:255|unique:tbl_emails',
                ], [
            'mail.email' => 'The email must be a valid email address.',
//            'ecat_id.unique' => 'Given department already exists !',
        ]);

        $formdata = array(
//            'ecat_id' => $request->input('ecat_id'),
            'mail' => $request->input('mail'),
        );
        $types = Tbl_emails::create($formdata);
        if ($types->ecat_id > 0) {
            return redirect('admin/emails')->with('success', 'Created Successfully...!');
        } else {
            return redirect('admin/emails')->with('error', 'Error occurred. Please try again...!');
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
        $email = Tbl_emails::find($id);
        $data['email'] = $email;

//        $departments = Tbl_emailcategory::all();
//        $options = '<option value="">Select Email Category</option>';
//        foreach ($departments as $types) {
//            $selected = ($types->ecat_id == $email->ecat_id) ? 'selected' : '';
//            $options .= '<option value="' . $types->ecat_id . '" ' . $selected . '>' . $types->category . '</option>';
//        }
//        $data['department_options'] = $options;
        return view('admin.emails.edit')->with('data', $data);
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
            'mail' => 'required|email|max:255',
//            'dep_id' => 'required|max:255|unique:tbl_emails',
                ], [
            'mail.email' => 'The email must be a valid email address.',
//            'dep_id.unique' => 'Given department already exists !',
        ]);

//        $ecat_id = $request->input('ecat_id');
        $mailId = $request->input('mail');

        $exist = Tbl_emails::where('mail_id', '!=', $id)->where('mail', '=', $mailId)->count();  //where('ecat_id', $ecat_id)->
//        echo json_encode($exist);
//        exit();
        if ($exist > 0) {
            return redirect('admin/emails')->with('error', 'Given mail id already exists...!');
        } else {
            $mail = Tbl_emails::find($id);
            $mail->mail = $mailId;
//            $mail->ecat_id = ecat_id;
            $mail->save();
            return redirect('admin/emails')->with('success', 'Updated Successfully...!');
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

}

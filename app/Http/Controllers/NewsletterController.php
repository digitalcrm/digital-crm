<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Tbl_newsletter;

class NewsletterController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $uid = Auth::user()->id;
        $letters = Tbl_newsletter::where('uid', $uid)->get();
        if (count($letters) > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Title</th>';
            $formstable .= '<th>Subject</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($letters as $formdetails) {
                $formstable .= '<tr>';
                $formstable .= '<td class="table-title"><a href="' . url('newsletter/' . $formdetails->nl_id) . '">' . $formdetails->title . '</a>&nbsp;</td>';
                $formstable .= '<td>' . $formdetails->subject . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a class="text-default text-btn-space" href="' . url('newsletter/' . $formdetails->nl_id . '/edit') . '">Edit</a></li>
                    <li><a class="text-default text-btn-space" href="' . url('newsletter/delete/' . $formdetails->nl_id) . '">Delete</a></li>
                  </ul>
                </div>';

                $formstable .= '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = count($letters);
        $data['table'] = $formstable;

        return view('auth.newsletter.index')->with("data", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('auth.newsletter.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $this->validate($request, [
            'category' => 'required|max:255',
            'title' => 'required|max:255',
            'subject' => 'required|max:255',
            'message' => 'required',
        ]);

        $filename = '';
        if ($request->hasfile('document')) {

            //-------------Image Validation----------------------------------
            $file = $request->file('document');
            // Build the input for validation
            $fileArray = array('document' => $file);

            // Tell the validator that this file should be an image
            $rules = array(
                'document' => 'mimes:pdf,doc,docx,jpeg,jpg,png,gif|max:10000' // max 10000kb
            );

            // Now pass the input and rules into the validator
            $validator = Validator::make($fileArray, $rules);

            // Check to see if validation fails or passes
            if ($validator->fails()) {
                return redirect('/newsletter/create')->with('error', 'Please check uploaded file.');
            }
            //-------------Image Validation----------------------------------
//            $file = $request->file('userpicture');
            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $file->move('uploads/newsletter/', $name);  //public_path().
            $filename = '/uploads/newsletter/' . $name;
        }


        $formdata = array(
            'uid' => Auth::user()->id,
            'title' => $request->input('title'),
            'subject' => $request->input('subject'),
            'attachment' => $filename,
            'message' => $request->input('message'),
            'type' => $request->input('category'),
        );

//        echo json_encode($formdata);

        $letter = Tbl_newsletter::create($formdata);
        if ($letter->nl_id > 0) {
            return redirect('/newsletter')->with('success', 'Created Successfully...!');
        } else {
            return redirect('/newsletter')->with('error', 'Error occurred. Please try again...!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $letter = Tbl_newsletter::find($id);
        return view('auth.newsletter.show')->with('data', $letter);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $letter = Tbl_newsletter::find($id);
        return view('auth.newsletter.edit')->with('data', $letter);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        $this->validate($request, [
            'category' => 'required|max:255',
            'title' => 'required|max:255',
            'subject' => 'required|max:255',
            'message' => 'required',
        ]);

        $filename = '';
        if ($request->hasfile('document')) {

            //-------------Image Validation----------------------------------
            $file = $request->file('document');
            // Build the input for validation
            $fileArray = array('document' => $file);

            // Tell the validator that this file should be an image
            $rules = array(
                'document' => 'mimes:pdf,doc,docx,jpeg,jpg,png,gif|max:10000' // max 10000kb
            );

            // Now pass the input and rules into the validator
            $validator = Validator::make($fileArray, $rules);

            // Check to see if validation fails or passes
            if ($validator->fails()) {
                return redirect('/newsletter/create')->with('error', 'Please check uploaded file.');
            }
            //-------------Image Validation----------------------------------
//            $file = $request->file('userpicture');
            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $file->move('uploads/newsletter/', $name);  //public_path().
            $filename = '/uploads/newsletter/' . $name;
        }


        $letter = Tbl_newsletter::find($id);
        $letter->title = $request->input('title');
        $letter->subject = $request->input('subject');
        $letter->message = $request->input('message');
        $letter->type = $request->input('category');
        if ($filename != '') {
            $letter->attachment = $filename;
        }
        $letter->save();
        return redirect('/newsletter')->with('success', 'Updated Successfully...!');
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

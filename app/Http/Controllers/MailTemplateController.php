<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//  Required Packages
use Auth;
use Illuminate\Support\Facades\Validator;
//  Models
use App\Tbl_mail_templates;

class MailTemplateController extends Controller
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
        $templates = Tbl_mail_templates::where('uid', $uid)
            ->where('active', 1)
            ->get();

        $total = count($templates);

        if ($total > 0) {
            $formstable = '<table id="accountsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th width="2"></th>';
            $formstable .= '<th>Template Name</th>';
            $formstable .= '<th>Subject</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($templates as $formdetails) {
                $formstable .= '<tr>';
                $formstable .= '<td><div class="custom-control custom-checkbox"><input type="checkbox" class="checkAll custom-control-input" id="' . $formdetails->temp_id . '"><label class="custom-control-label" for="' . $formdetails->temp_id . '"></label></div></td>';
                $formstable .= '<td><a href="' . url('mailtemplates/' . $formdetails->temp_id) . '">' . $formdetails->name . '</a></td>';
                $formstable .= '<td>' . $formdetails->subject . ' </td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                  </button>
                  <div class="dropdown-menu" role="menu">
                    <a class="dropdown-item text-default text-btn-space" href="' . url('mailtemplates/' . $formdetails->temp_id . '/edit') . '">Edit</a>
                    <a class="dropdown-item text-default text-btn-space" href="' . url('mailtemplates/deletetemplate/' . $formdetails->temp_id) . '">Delete</a>
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
        return view('auth.mailtemplates.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.mailtemplates.create');
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

        $this->validate($request, [
            'name' => 'required|unique:tbl_mail_templates|max:255',
            'subject' => 'required',
            'message' => 'required',
        ], [
            'name.required' => 'Template name is required',
            'name.unique' => 'Template name is already taken',
        ]);


        $formdata = array(
            'uid' => Auth::user()->id,
            'name' => $request->input('name'),
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
        );

        $template = Tbl_mail_templates::create($formdata);

        if ($template->temp_id > 0) {
            return redirect('mailtemplates')->with('success', 'Template created successfully');
        } else {
            return redirect('mailtemplates')->with('error', 'Failed. Try again later');
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
        $template = Tbl_mail_templates::find($id);
        return view('auth.mailtemplates.show')->with('data', $template);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $template = Tbl_mail_templates::find($id);
        //        echo json_encode($template);
        return view('auth.mailtemplates.edit')->with('data', $template);
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
            'name' => 'required|max:255|unique:tbl_mail_templates,name,' . $id . ',temp_id', //
            'subject' => 'required',
            'message' => 'required',
        ], [
            'name.required' => 'Template name is required',
            'name.unique' => 'Template name is already taken',
        ]);

        $template = Tbl_mail_templates::find($id);
        $template->name = $request->input('name');
        $template->subject = $request->input('subject');
        $template->message = $request->input('message');
        $template->save();
        return redirect('mailtemplates')->with('success', 'Template updated successfully');
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

    public function getTemplateDetails(Request $request)
    {
        $id = $request->input('temp_id');

        //        echo $id;

        $template = Tbl_mail_templates::find($id);
        $template->message = strip_tags($template->message);
        return json_encode($template);
    }


    public function deleteTemplate($id)
    {
        $template = Tbl_mail_templates::find($id);
        $res = $template->delete();
        if ($res) {
            return redirect('mailtemplates')->with('success', 'Deleted successfully');
        } else {
            return redirect('mailtemplates')->with('error', 'Error occured. Try again later..');
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
//  Models
use App\Tbl_file_manager;
use App\Tbl_file_category;

class FileManagerController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $files = Tbl_file_manager::where('active', 1)->with('tbl_file_category')->get();
        $total = count($files);
        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Category</th>';
            $formstable .= '<th>Type</th>';
            $formstable .= '<th>Size</th>';
            $formstable .= '<th>Date</th>';
//            $formstable .= '<th>Share</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($files as $formdetails) {

                $faicon = '';

                if (($formdetails['type'] == 'csv') || ($formdetails['type'] == 'xls') || ($formdetails['type'] == 'xlsx')) {
                    $faicon = '<i class="fa fa-file-excel-o" aria-hidden="true"></i>';
                }

                if ($formdetails['type'] == 'pdf') {
                    $faicon = '<i class="fa fa-file-pdf-o" aria-hidden="true"></i>';
                }

                if ($formdetails['type'] == 'txt') {
                    $faicon = '<i class="fa fa-file-text" aria-hidden="true"></i>';
                }

                if (($formdetails['type'] == 'doc') || ($formdetails['type'] == 'docx')) {
                    $faicon = '<i class="fa fa-file-word-o" aria-hidden="true"></i>';
                }

                if (($formdetails['type'] == 'jpeg') || ($formdetails['type'] == 'png') || ($formdetails['type'] == 'gif')) {
                    $faicon = '<i class="fa fa-file-image-o" aria-hidden="true"></i>';
                }

                $category = '';
                if ($formdetails['tbl_file_category'] != '') {
                    $category = $formdetails['tbl_file_category']['category'];
                }

                $formstable .= '<tr>';
                $formstable .= '<td class="table-title"><a href="' . url('files/' . $formdetails['file_id']) . '">' . $faicon . '&nbsp; ' . $formdetails['name'] . '</a></td>';
                $formstable .= '<td>' . $category . '</td>';
                $formstable .= '<td>' . $formdetails['type'] . '</td>';
                $formstable .= '<td>' . $formdetails['size'] . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails['created_at'])) . '</td>';
//                $formstable .= '<td><div class="btn-group">
//                  <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
//                    <span class="caret"></span>
//                    <span class="sr-only">Toggle Dropdown</span>
//                  </button>
//                  <ul class="dropdown-menu" role="menu">
//                    <li><a class="text-default text-btn-space" href="' . url('admin/files/' . $formdetails['file_id'] . '/edit') . '">Edit</a></li>
//                    <li><a class="text-default text-btn-space" href="' . url('admin/files/delete/' . $formdetails['file_id']) . '">Delete</a></li>
//                  </ul>
//                </div></td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;

        return view('auth.filemanager.index')->with("data", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $data = Tbl_file_manager::find($id);

        if (($data->content_type == "image/png") || ($data->content_type == "image/jpeg") || ($data->content_type == "image/gif") || ($data->content_type == "application/pdf")) {
            $data->file = url($data->file);
//            return redirect('/documents');
        } else {
            $document = explode("/", $data->file);
            $filename = $document[count($document) - 1];
            $myFile = str_replace("storage", "", storage_path()) . $data->file;
            $headers = ['Content-Type: ' . $data->content_type];
            return response()->download($myFile, $filename, $headers);
        }

//        echo json_encode($data);
//        exit();
        return view('auth.filemanager.show')->with('data', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
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

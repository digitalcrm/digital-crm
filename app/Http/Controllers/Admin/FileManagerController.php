<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Auth;
use File;
//  Models
use App\Tbl_file_manager;
use App\Tbl_file_category;

class FileManagerController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware('auth:admin');
    }

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
            $formstable .= '<th>Share</th>';
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
                $formstable .= '<td class="table-title"><a href="' . url('admin/files/' . $formdetails['file_id']) . '">' . $faicon . '&nbsp; ' . $formdetails['name'] . '</a></td>';
                $formstable .= '<td>' . $category . '</td>';
                $formstable .= '<td>' . $formdetails['type'] . '</td>';
                $formstable .= '<td>' . $formdetails['size'] . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails['created_at'])) . '</td>';
                $formstable .= '<td><div class="btn-group">
                  <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a class="text-default text-btn-space" href="' . url('admin/files/' . $formdetails['file_id'] . '/edit') . '">Edit</a></li>
                    <li><a class="text-default text-btn-space" href="' . url('admin/files/delete/' . $formdetails['file_id']) . '">Delete</a></li>
                  </ul>
                </div></td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;

        return view('admin.filemanager.index')->with("data", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $category = Tbl_file_category::all();
        $option = '<option value=0>Select Category...</option>';
        foreach ($category as $cat) {
            $option .= '<option value="' . $cat->fc_id . '">' . $cat->category . '</option>';
        }

        $data['categoryoptions'] = $option;

        return view('admin.filemanager.create')->with("data", $data);
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
            'name' => 'required|max:255|unique:tbl_file_manager',
            'category' => 'required',
        ]);

        $filename = '';
        $filetype = '';
        $filesize = '';
        $filemimetype = '';
        if ($request->hasfile('file')) {

            //-------------Image Validation----------------------------------
            $file = $request->file('file');
            // Build the input for validation
//            echo $file->getClientOriginalExtension();
//            exit(0);

            $extensions = array("xls", "xlsx", "xlm", "xla", "xlc", "xlt", "xlw", "csv", "pdf", "jpeg", "jpg", "png", "gif", "doc", "docx", "txt");

            $result = array($request->file('file')->getClientOriginalExtension());

            if (in_array($result[0], $extensions)) {
                $filetype = $file->getClientOriginalExtension();

                $fileArray = array('file' => $file);

                // Tell the validator that this file should be an image
                $rules = array(
                    'document' => 'max:10000' // max 10000kb
                );

                // Now pass the input and rules into the validator
                $validator = Validator::make($fileArray, $rules);

                // Check to see if validation fails or passes
                if ($validator->fails()) {
                    return redirect('admin/files/create')->with('error', 'Please check uploaded file');
                } else {

                    //-------------Image Validation----------------------------------
//            $file = $request->file('document');
                    $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
                    $file->move('uploads/files/', $name);  //public_path().
                    $filename = '/uploads/files/' . $name;

                    $fileUrl = base_path($filename);

                    $filesize = number_format(File::size($fileUrl) / 1048576, 2) . ' MB';
                    $filemimetype = File::mimeType($fileUrl);
                    $mimetype = explode('/', $filemimetype);
//                    $filetype = $mimetype[1];
                }
                $fileUrl = base_path($filename);

                $formdata = array(
                    'uid' => Auth::user()->id,
                    'fc_id' => $request->input('category'),
                    'name' => $request->input('name'),
                    'file' => $filename,
                    'size' => $filesize,
                    'type' => $filetype,
                    'content_type' => $filemimetype,
                );

                $document = Tbl_file_manager::create($formdata);
                $file_id = $document->file_id;

                if ($file_id > 0) {
                    return redirect('/admin/files')->with('success', 'Uploaded Successfully...!');
                } else {
                    return redirect('/admin/files')->with('error', 'Error occurred. Please try again...!');
                }
            } else {
                return redirect('/admin/files')->with('error', 'Please check uploaded file..!');
            }
        } else {
            return redirect('/admin/files/create')->with('error', 'Please check uploaded file');
        }
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
        return view('admin.filemanager.show')->with('data', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $files = Tbl_file_manager::with('tbl_file_category')->find($id);
        $data['files'] = $files;

        $category = Tbl_file_category::all();
        $option = '<option value=0>Select Category...</option>';
        foreach ($category as $cat) {
            $selected = '';
            if ($files->tbl_file_category != '') {
                $selected = ($files->tbl_file_category->fc_id == $cat->fc_id) ? 'selected' : '';
            }
            $option .= '<option value="' . $cat->fc_id . '" ' . $selected . '>' . $cat->category . '</option>';
        }

        $data['categoryoptions'] = $option;

        return view('admin.filemanager.edit')->with('data', $data);
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
            'name' => 'required|max:255|unique:tbl_file_manager,name,' . $id . ',file_id',
            'category' => 'required',
        ]);

        $filename = '';
        $filetype = '';
        $filesize = '';
        $filemimetype = '';


        if ($request->hasfile('file')) {

            //-------------Image Validation----------------------------------
            $file = $request->file('file');
            // Build the input for validation
//            echo $file->getClientOriginalExtension();
//            exit(0);

            $extensions = array("xls", "xlsx", "xlm", "xla", "xlc", "xlt", "xlw", "csv", "pdf", "jpeg", "jpg", "png", "gif", "doc", "docx", "txt");

            $result = array($request->file('file')->getClientOriginalExtension());

            if (in_array($result[0], $extensions)) {
                $filetype = $file->getClientOriginalExtension();

                $fileArray = array('file' => $file);

                // Tell the validator that this file should be an image
                $rules = array(
                    'document' => 'max:10000' // max 10000kb
                );

                // Now pass the input and rules into the validator
                $validator = Validator::make($fileArray, $rules);

                // Check to see if validation fails or passes
                if ($validator->fails()) {
                    return redirect('admin/files/' . $id . '/edit')->with('error', 'Please upload only 10000kb file');
                } else {

                    //-------------Image Validation----------------------------------
//            $file = $request->file('document');
                    $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
                    $file->move('uploads/files/', $name);  //public_path().
                    $filename = '/uploads/files/' . $name;

                    $fileUrl = base_path($filename);

                    $filesize = number_format(File::size($fileUrl) / 1048576, 2) . ' MB';
                    $filemimetype = File::mimeType($fileUrl);
                    $mimetype = explode('/', $filemimetype);
//                    $filetype = $mimetype[1];
                }

                $document = tbl_file_manager::find($id);
                $document->name = $request->input('name');
                $document->size = $filesize;
                $document->type = $filetype;
                $document->content_type = $filemimetype;
                $document->file = $filename;
                $document->save();

                return redirect('/admin/files')->with('success', 'Uploaded Successfully...!');
            } else {
                return redirect('/admin/files')->with('error', 'Please check uploaded file..!');
            }
        } else {
            return redirect('/admin/files/' . $id . '/edit')->with('error', 'Please check uploaded file');
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

    public function delete($id) {
        $document = tbl_file_manager::find($id);
        $document->active = 0;
        $document->save();
        return redirect('/admin/files')->with('success', 'Deleted Successfully...!');
    }

}

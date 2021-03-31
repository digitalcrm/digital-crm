<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use File;
//---------Models---------------
use App\User;
use App\Tbl_documents;

class DocumentController extends Controller
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
        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        $uid = 'All';
        $useroptions = "<option value='All' selected>All</option>";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>"; // " . $selected . "
        }
        $data['useroptions'] = $useroptions;
        $accounts = $this->getDocuments($uid);

        $data['table'] = $accounts['table'];
        $data['total'] = $accounts['total'];

        return view('admin.documents.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        $useroptions = "<option value=''>Select User</option>";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>"; // " . $selected . "
        }
        $data['useroptions'] = $useroptions;
        return view('admin.documents.create')->with('data', $data);
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
            'name' => 'required|max:255',
        ]);

        $filename = '';
        $filetype = '';
        $filesize = '';
        $filemimetype = '';
        if ($request->hasfile('document')) {

            //-------------Image Validation----------------------------------
            $file = $request->file('document');
            // Build the input for validation
            //            echo $file->getClientOriginalExtension();
            //            exit(0);

            $extensions = array("xls", "xlsx", "xlm", "xla", "xlc", "xlt", "xlw", "csv", "pdf", "jpeg", "jpg", "png", "gif", "doc", "docx", "txt");

            $result = array($request->file('document')->getClientOriginalExtension());

            if (in_array($result[0], $extensions)) {
                $filetype = $file->getClientOriginalExtension();

                $fileArray = array('document' => $file);

                // Tell the validator that this file should be an image
                $rules = array(
                    'document' => 'max:10000' // max 10000kb
                );

                // Now pass the input and rules into the validator
                $validator = Validator::make($fileArray, $rules);

                // Check to see if validation fails or passes
                if ($validator->fails()) {
                    return redirect('admin/documents/create')->with('error', 'Please upload only 10000kb file');
                } else {

                    //-------------Image Validation----------------------------------
                    //            $file = $request->file('document');
                    $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
                    $file->move('uploads/documents/', $name);  //public_path().
                    $filename = '/uploads/documents/' . $name;

                    $fileUrl = base_path($filename);

                    $filesize = number_format(File::size($fileUrl) / 1048576, 2) . ' MB';
                    $filemimetype = File::mimeType($fileUrl);
                    $mimetype = explode('/', $filemimetype);
                    //                    $filetype = $mimetype[1];
                }
                $fileUrl = base_path($filename);
                //                echo 'base path : ' . base_path() . '<br>';
                //                echo 'fileUrl : ' . $fileUrl . '<br>';
                //                echo 'File Size : ' . File::size($fileUrl) . '<br>';
                //                echo $file_size = number_format(File::size($fileUrl) / 1048576, 2) . ' MB' . '<br>';
                //                echo $file_type = File::mimeType($fileUrl) . '<br>';
                //                exit(0);

                $formdata = array(
                    'uid' => $request->input('selectUser'),
                    'name' => $request->input('name'),
                    'document' => $filename,
                    'size' => $filesize,
                    'type' => $filetype,
                    'content_type' => $filemimetype,
                );

                $document = Tbl_documents::create($formdata);
                $doc_id = $document->doc_id;

                if ($doc_id > 0) {
                    return redirect('admin/documents')->with('success', 'Uploaded Successfully...!');
                } else {
                    return redirect('admin/documents/create')->with('error', 'Error occurred. Please try again...!');
                }
            }
        } else {
            return redirect('admin/documents/create')->with('error', 'Please check uploaded file');
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
        $data = Tbl_documents::find($id);
        //        echo url($data->document);
        //        echo $myFile;
        //        exit(0);

        if (($data->content_type == "image/png") || ($data->content_type == "image/jpeg") || ($data->content_type == "image/gif") || ($data->content_type == "application/pdf")) {
            $data->document = url($data->document);
            //            return redirect('/documents');
        } else {
            $document = explode("/", $data->document);
            $filename = $document[count($document) - 1];
            $myFile = str_replace("storage", "", storage_path()) . $data->document;
            $headers = ['Content-Type: ' . $data->content_type];
            return response()->download($myFile, $filename, $headers);
        }

        return view('admin.documents.show')->with('data', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Tbl_documents::find($id);
        return view('admin.documents.edit')->with('data', $data);
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

        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        $filename = '';
        $filetype = '';
        $filesize = '';
        $filemimetype = '';


        if ($request->hasfile('document')) {

            //-------------Image Validation----------------------------------
            $file = $request->file('document');
            // Build the input for validation
            //            echo $file->getClientOriginalExtension();
            //            exit(0);

            $extensions = array("xls", "xlsx", "xlm", "xla", "xlc", "xlt", "xlw", "csv", "pdf", "jpeg", "jpg", "png", "gif", "doc", "docx", "txt");

            $result = array($request->file('document')->getClientOriginalExtension());

            if (in_array($result[0], $extensions)) {
                $filetype = $file->getClientOriginalExtension();

                $fileArray = array('document' => $file);

                // Tell the validator that this file should be an image
                $rules = array(
                    'document' => 'max:10000' // max 10000kb
                );

                // Now pass the input and rules into the validator
                $validator = Validator::make($fileArray, $rules);

                // Check to see if validation fails or passes
                if ($validator->fails()) {
                    return redirect('admin/documents/' . $id . '/edit')->with('error', 'Please upload only 10000kb file');
                } else {

                    //-------------Image Validation----------------------------------
                    //            $file = $request->file('document');
                    $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
                    $file->move('uploads/documents/', $name);  //public_path().
                    $filename = '/uploads/documents/' . $name;

                    $fileUrl = base_path($filename);

                    $filesize = number_format(File::size($fileUrl) / 1048576, 2) . ' MB';
                    $filemimetype = File::mimeType($fileUrl);
                    $mimetype = explode('/', $filemimetype);
                    //                    $filetype = $mimetype[1];
                }
                //                $fileUrl = base_path($filename);
                //                echo 'base path : ' . base_path() . '<br>';
                //                echo 'fileUrl : ' . $fileUrl . '<br>';
                //                echo 'File Size : ' . File::size($fileUrl) . '<br>';
                //                echo $file_size = number_format(File::size($fileUrl) / 1048576, 2) . ' MB' . '<br>';
                //                echo $file_type = File::mimeType($fileUrl) . '<br>';
                //                exit(0);

                $document = Tbl_documents::find($id);
                $document->name = $request->input('name');
                $document->size = $filesize;
                $document->type = $filetype;
                $document->content_type = $filemimetype;
                $document->document = $filename;
                $document->save();

                return redirect('admin/documents')->with('success', 'Uploaded Successfully...!');
            }
        } else {
            return redirect('admin/documents/' . $id . '/edit')->with('error', 'Please check uploaded file');
        }
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
        $document = Tbl_documents::find($id);
        $document->active = 0;
        $document->save();
        return redirect('admin/documents')->with('success', 'Deleted Successfully...!');
    }

    public function getDocuments($uid)
    {
        //        $uid = Auth::user()->id;

        if ($uid == "All") {
            $documents = Tbl_documents::where('active', 1)->get()->toArray();
        } else {
            $documents = Tbl_documents::where('active', 1)->where('uid', $uid)->get()->toArray();
        }
        $total = count($documents);
        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Type</th>';
            $formstable .= '<th>Size</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Share</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($documents as $formdetails) {

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

                $formstable .= '<tr>';
                $formstable .= '<td class="table-title"><a href="' . url('admin/documents/' . $formdetails['doc_id']) . '">' . $faicon . '&nbsp; ' . $formdetails['name'] . '</a></td>';
                $formstable .= '<td>' . $formdetails['type'] . '</td>';
                $formstable .= '<td>' . $formdetails['size'] . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails['created_at'])) . '</td>';
                $formstable .= '<td><div class="btn-group">
                  <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a class="text-default text-btn-space" href="' . url('admin/documents/' . $formdetails['doc_id'] . '/edit') . '">Edit</a></li>
                    <li><a class="text-default text-btn-space" href="' . url('admin/documents/delete/' . $formdetails['doc_id']) . '">Delete</a></li>
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

        return $data;
    }
}

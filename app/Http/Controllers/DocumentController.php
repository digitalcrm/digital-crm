<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use File;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
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
        $this->middleware('auth');
        $this->middleware('test:documents', ['only' => ['index', 'show', 'create', 'store', 'edit', 'update', 'delete', 'deleteAll']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $uid = Auth::user()->id;
        $documents = Tbl_documents::where('uid', $uid)->where('active', 1)->orderBy('doc_id', 'desc')->get()->toArray();
        $total = count($documents);
        if ($total > 0) {
            $formstable = '<div class="table-responsive"><table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th width="2"></th>';   //<input type="checkbox" id="selectAll">
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Type</th>';
            $formstable .= '<th>Size</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
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
                $formstable .= '<td><div class="custom-control custom-checkbox"><input type="checkbox" class="checkAll custom-control-input" id="' . $formdetails['doc_id'] . '"><label class="custom-control-label" for="' . $formdetails['doc_id'] . '"></label></div></td>';
                $formstable .= '<td class="table-title"><a href="' . url('documents/' . $formdetails['doc_id']) . '">' . $faicon . '&nbsp; ' . $formdetails['name'] . '</a></td>';
                $formstable .= '<td>' . $formdetails['type'] . '</td>';
                $formstable .= '<td>' . $formdetails['size'] . '</td>';
                $formstable .= '<td>' . date('m-d-Y', strtotime($formdetails['created_at'])) . '</td>';
                $formstable .= '<td><div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item text-default text-btn-space" href="' . url('documents/' . $formdetails['doc_id'] . '/edit') . '">Edit</a>
                    <a class="dropdown-item text-default text-btn-space" href="' . url('documents/delete/' . $formdetails['doc_id']) . '">Delete</a>
                  </div>
                </div></td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table></div>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;

        return view('auth.documents.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        if ($user->can('create', Tbl_documents::class)) {
            return view('auth.documents.create');
        } else {
            return redirect('/documents');
        }
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
        //        exit();
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
                    return redirect('/documents/create')->with('error', 'Please check uploaded file');
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

                $formdata = array(
                    'uid' => Auth::user()->id,
                    'name' => $request->input('name'),
                    'document' => $filename,
                    'size' => $filesize,
                    'type' => $filetype,
                    'content_type' => $filemimetype,
                );

                $document = Tbl_documents::create($formdata);
                $doc_id = $document->doc_id;

                if ($doc_id > 0) {
                    return redirect('/documents')->with('success', 'Uploaded Successfully...!');
                } else {
                    return redirect('/documents')->with('error', 'Error occurred. Please try again...!');
                }
            } else {
                return redirect('/documents')->with('error', 'Please check uploaded file..!');
            }
        } else {
            return redirect('/documents/create')->with('error', 'Please check uploaded file');
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

        $user = Auth::user();
        if ($user->can('view', $data)) {

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


            return view('auth.documents.show')->with('data', $data);
        } else {
            return redirect('/documents');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        $data = Tbl_documents::find($id);
        if ($user->can('edit', $data)) {
            return view('auth.documents.edit')->with('data', $data);
        } else {
            return redirect('/documents');
        }
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
        $data = Tbl_documents::find($id);

        $user = Auth::user();
        if ($user->can('update', $data)) {
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
                        return redirect('documents/' . $id . '/edit')->with('error', 'Please upload only 10000kb file');
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

                    $document = Tbl_documents::find($id);
                    $document->name = $request->input('name');
                    $document->size = $filesize;
                    $document->type = $filetype;
                    $document->content_type = $filemimetype;
                    $document->document = $filename;
                    $document->save();

                    return redirect('/documents')->with('success', 'Uploaded Successfully...!');
                } else {
                    return redirect('/documents')->with('error', 'Please check uploaded file..!');
                }
            } else {
                return redirect('documents/' . $id . '/edit')->with('error', 'Please check uploaded file');
            }
        } else {
            return redirect('/documents');
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
        $user = Auth::user();
        $doc = Tbl_documents::find($id);

        if ($user->can('update', $doc)) {
            $doc->active = 0;
            $doc->save();
            return redirect('/documents')->with('success', 'Deleted Successfully...!');
        } else {
            return redirect('/documents');
        }
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->input('id');
        return Tbl_documents::whereIn('doc_id', $ids)->update(array('active' => 0));
    }

    public function restoreAll(Request $request)
    {
        $ids = $request->input('id');
        return Tbl_documents::whereIn('doc_id', $ids)->update(array('active' => 1));
    }
}

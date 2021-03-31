<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Auth;
use File;
use Excel;
//---------Models---------------
use App\User;
use App\Tbl_leads;
use App\Tbl_fb_leads;
use App\Tbl_leads_csv_files;

class LeadCsvController extends Controller {

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
        $files = Tbl_leads_csv_files::all();
        $total = count($files);
        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Size</th>';
            $formstable .= '<th>Status</th>';
            $formstable .= '<th>Import Time</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($files as $formdetails) {

                $faicon = '';

                if (($formdetails['type'] == 'csv') || ($formdetails['type'] == 'xls') || ($formdetails['type'] == 'xlsx')) {
                    $faicon = '<i class="fa fa-file-excel-o" aria-hidden="true"></i>';
                }

                $formstable .= '<tr>';
                $formstable .= '<td class="table-title"><a href="' . url('admin/leadcsv/' . $formdetails['lc_id']) . '">' . $faicon . '&nbsp; ' . $formdetails['original_name'] . '</a></td>';
                $formstable .= '<td>' . $formdetails['size'] . '</td>';
                $formstable .= '<td>' . (($formdetails['status'] == 0) ? 'Not Imported' : 'Imported') . '</td>';
                $formstable .= '<td>' . (($formdetails['status'] == 0) ? '' : date('d-m-Y', strtotime($formdetails['import_time']))) . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails['created_at'])) . '</td>';
                $formstable .= '<td>';
                if (($formdetails['status'] == 0)) {
                    $formstable .= '<a class="btn btn-default" href="#" onclick="return submitLeadData(' . $formdetails['file_id'] . ');">Import</a>&nbsp;';
                } else {
                    $formstable .= '<a class="btn btn-default" href="' . url('admin/fbleads/' . $formdetails['file_id']) . '">View Leads</a>&nbsp;';
                }

                //<a class="btn btn-default" href="' . url('admin/leadcsv/import/' . $formdetails['file_id']) . '">Import</a>
                $formstable .= '<a class="btn btn-default" href="' . url('admin/leadcsv/delete/' . $formdetails['file_id']) . '">Delete</a>&nbsp;';
                $formstable .='</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;

        return view('admin.leadcsv.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin.leadcsv.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

//        echo json_encode($request->input());

        $uid = Auth::user()->id;

        $filename = '';
        $fileoriginalname = '';
        $filetype = '';
        $filesize = '';
        $filemimetype = '';
        if ($request->hasfile('document')) {

            //-------------Image Validation----------------------------------
            $file = $request->file('document');
            // Build the input for validation
//            echo json_encode($request->file());
//            echo $file->getClientOriginalName();
//            exit(0);

            $fileoriginalname = $file->getClientOriginalName();


            $exist = $this->fileExist($fileoriginalname);

            if ($exist) {
                return redirect('admin/leadcsv/create')->with('error', 'Uploade file already exists');
            }

            $extensions = array("xls", "xlsx", "xlm", "xla", "xlc", "xlt", "xlw", "csv", "txt");

            $result = array($request->file('document')->getClientOriginalExtension());

            if (in_array($result[0], $extensions)) {
                $filetype = $file->getClientOriginalExtension();

                $fileArray = array('document' => $file);

                // Tell the validator that this file should be an image
                $rules = array(
                    'document' => 'max:10000', // max 10000kb
                );

                // Now pass the input and rules into the validator
                $validator = Validator::make($fileArray, $rules);

                // Check to see if validation fails or passes
                if ($validator->fails()) {
                    return redirect('admin/leadcsv/create')->with('error', 'Please upload only 10000kb file');
                } else {

                    //-------------Image Validation----------------------------------
//            $file = $request->file('document');
                    $name = date('d-m-Y-h-i-s') . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
                    $file->move('uploads/leadcsv/', $name);  //public_path().
                    $filename = '/uploads/leadcsv/' . $name;

//                    echo str_replace('/', "\\", $filename);
//                    exit();
//                    $fileUrl = base_path(str_replace('/', "\\", $filename));
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

                $formdata = array(
                    'uid' => $uid,
                    'name' => $name,
                    'original_name' => $fileoriginalname,
                    'document' => $fileUrl,
                    'size' => $filesize,
                    'type' => $filetype,
                    'content_type' => $filemimetype,
                    'uploaded_by' => 1
                );

                $lead_csv = Tbl_leads_csv_files::create($formdata);
                $file_id = $lead_csv->file_id;

                if ($file_id > 0) {
                    return redirect('admin/leadcsv')->with('success', 'Uploaded Successfully...!');
                } else {
                    return redirect('admin/leadcsv/create')->with('error', 'Error occurred. Please try again...!');
                }
            }
        } else {
            return redirect('admin/leadcsv/create')->with('error', 'Please check uploaded file');
        }
    }

    public function fileExist($name) {
        $files = Tbl_leads_csv_files::where('original_name', $name)->get();
        if (count($files) > 0) {
            return TRUE;
        } else {
            return FALSE;
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

    public function importLeadFromCSV($id) {
        
    }

    public function importleaddata(Request $request) {
//        echo "<h1>Page Under Development</h1>";

        $file_id = $request->input('file_id');
        $file = Tbl_leads_csv_files::find($file_id);
//        echo json_encode($file);
//        exit();
//        $fileTest = 'C:\xampp\htdocs\crm\\uploads\leadcsv\vikassir.csv';  //812porush-test    sample    812porush-test.csv  8,12porush_2_test.csv
        $data = Excel::load($file->document)->get();  //    $fileTest
//        echo json_encode($data);
        $data_Arr = array();
//        $lead_Arr = array();
        if ($data->count()) {
            foreach ($data as $key => $value) {
//                echo json_encode($value);
//                print_r($value);
//                exit();


                $existRec = Tbl_fb_leads::where('id', $value->id)->get();

                if (count($existRec) == 0) {

                    //  Mobile number
                    $mobile = '';
                    if (strpos($value->phone_number, 'p:+') !== false) {
                        $mobile = str_replace('p:+', '', $value->phone_number);
                    } else {
                        $mobile = $value->phone_number;
                    }

                    //  Lead Id 
                    $id = '';
                    if (strpos($value->id, 'l:') !== false) {
                        $id = str_replace('l:', '', $value->id);
                    } else {
                        $id = $value->id;
                    }

                    //  Ad Id 
                    $ad_id = '';
                    if (strpos($value->ad_id, 'ag:') !== false) {
                        $ad_id = str_replace('ag:', '', $value->ad_id);
                    } else {
                        $ad_id = $value->ad_id;
                    }

                    //  Adset Id 
                    $adset_id = '';
                    if (strpos($value->adset_id, 'as:') !== false) {
                        $adset_id = str_replace('as:', '', $value->adset_id);
                    } else {
                        $adset_id = $value->adset_id;
                    }

                    //  Form Id 
                    $form_id = '';
                    if (strpos($value->form_id, 'f:') !== false) {
                        $form_id = str_replace('f:', '', $value->form_id);
                    } else {
                        $form_id = $value->form_id;
                    }

                    //  Campaign Id 
                    $campaign_id = '';
                    if (strpos($value->campaign_id, 'c:') !== false) {
                        $campaign_id = str_replace('c:', '', $value->campaign_id);
                    } else {
                        $campaign_id = $value->campaign_id;
                    }


                    $formdata = array(
                        'id' => $id, // $value->id,
                        'created_time' => $value->created_time,
                        'ad_id' => $ad_id, //$value->ad_id,
                        'ad_name' => $value->ad_name,
                        'adset_id' => $adset_id, //$value->adset_id,
                        'adset_name' => $value->adset_name,
                        'campaign_id' => $campaign_id, //$value->campaign_id,
                        'campaign_name' => $value->campaign_name,
                        'form_id' => $form_id, //$value->form_id,
                        'form_name' => $value->form_name,
                        'is_organic' => $value->is_organic,
                        'platform' => $value->platform,
                        'full_name' => $value->full_name,
                        'city' => $value->city,
                        'phone_number' => $mobile, //$value->phone_number,
                        'uid' => Auth::user(0)->id,
                        'uploaded_by' => 1,
                        'file_id' => $file_id
                    );
                    $data_Arr[] = $formdata;

//                    $leaddata = array(
//                        'uid' => 0,
//                        'first_name' => $value->full_name,
//                        'city' => $value->city,
//                        'mobile' => $mobile, // $value->phone_number,
////                        'fblead_id' => $value->fblead_id,
//                        'uploaded_from' => 5,
//                        'uploaded_id' => $id //$value->id
//                    );
//                    $lead_Arr[] = $leaddata;
                }

//                exit();
            }
//            echo json_encode($data_Arr);

            if (!empty($data_Arr)) {
                Tbl_fb_leads::insert($data_Arr);

                $file->status = 1;
                $file->import_time = date("Y-m-d h:i:s");
                $file->save();

                //  Table leads
//                Tbl_leads::insert($lead_Arr);

                return redirect('admin/leadcsv')->with('success', "Imported Successfully");
            } else {
                return redirect('admin/leadcsv')->with('error', "Records already exist");
            }
        } else {
            return redirect('admin/leadcsv')->with('error', "Please check uploaded file. Data don't exist.");
        }
    }

    public function getFbAdManager() {
        $leads = Tbl_fb_leads::where('uploaded_by', 3)->orderBy('fblead_id', 'desc')->get();
//        echo json_encode($leads);
//        exit();

        $total = count($leads);

        if ($total > 0) {
            $formstable = '<table id="leadsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
//            $formstable .= '<th width="2"><input type="checkbox" id="selectAll"></th>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>City</th>';
            $formstable .= '<th>Mobile</th>';
            $formstable .= '<th>Status</th>';
            $formstable .= '<th>Date</th>';
//            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($leads as $formdetails) {
                $formstable .= '<tr>';
//                $formstable .= '<td><input type="checkbox" class="checkAll" id="' . $formdetails->ld_id . '"></td>';
                $formstable .= '<td><a href="#">' . substr($formdetails->full_name, 0, 30) . '</a>&nbsp;</td>';
                $formstable .= '<td>' . $formdetails->city . '</td>';
                $formstable .= '<td>' . $formdetails->phone_number . '</td>';
                $formstable .= '<td>' . (($formdetails->assigned == 1) ? 'Assigned' : 'Not Assigned') . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
//                $formstable .= '<td>';
//                $formstable .= '<select id="user' . $formdetails->fblead_id . '" name="user' . $formdetails->fblead_id . '" onchange="return assignUser(\'user' . $formdetails->fblead_id . '\',' . $formdetails->fblead_id . ');">';
//                $formstable .= $useroptions;
//                $formstable .= '</select>';
//                $formstable .= '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;

        return view('admin.leadcsv.fbadmanager')->with('data', $data);
    }

}

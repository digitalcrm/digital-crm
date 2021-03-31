<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;

//  Models
use App\Tbl_rds;
use App\Tbl_industrytypes;
use App\Tbl_rdtypes;
use App\Tbl_rdpriority;
use App\Tbl_rdtrend;
use App\Tbl_products;
use App\Tbl_productcategory;

class RdController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('test:rds', ['only' => ['index', 'show', 'create', 'store', 'edit', 'update', 'delete', 'deleteAll']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $uid = Auth::user()->id;
        $type = "All";
        $intype_id = "All";
        $rdpr_id = "All";
        $pro_id = "All";
        $status = "All";
        $procat_id = "All";
        $create_date = "";
        $submit_date = "";
        $upload_date = "";

        // $create_date, $submit_date, $upload_date
        $create_date_start = "";
        $create_date_end = "";
        $submit_date_start = "";
        $submit_date_end = "";
        $upload_date_start = "";
        $upload_date_end = "";

        $data = $this->getRdlist($type, $intype_id, $rdpr_id, $pro_id, $status, $create_date_start, $create_date_end, $submit_date_start, $submit_date_end, $upload_date_start, $upload_date_end, $procat_id);

        $rdtypes = Tbl_rdtypes::all();
        $rdtypeoptions = "<option value='All'>All</option>";
        if (count($rdtypes) > 0) {
            foreach ($rdtypes as $rdtype) {
                $rdtypeoptions .= "<option value='" . $rdtype->rdtype_id . "'>" . $rdtype->type . "</option>";
            }
        }
        $data['rdtypeoptions'] = $rdtypeoptions;

        $rdprtypes = Tbl_rdpriority::all();
        $rdprtypeoptions = "<option value='All'>All</option>";
        if (count($rdprtypes) > 0) {
            foreach ($rdprtypes as $rdprtype) {
                $rdprtypeoptions .= "<option value='" . $rdprtype->rdpr_id . "'>" . $rdprtype->priority . "</option>";
            }
        }
        $data['rdprtypeoptions'] = $rdprtypeoptions;

        //  Products
        $products = Tbl_products::where('active', 1)->get();    //where('uid', $uid)->
        $productoptions = '<option value="All">All</option>';
        foreach ($products as $product) {
            $productoptions .= '<option value="' . $product->pro_id . '">' . $product->name . '</option>';
        }
        $data['productoptions'] = $productoptions;

        $industry = Tbl_industrytypes::all();
        $industryoptions = "<option value='All'>All</option>";
        if (count($industry) > 0) {
            foreach ($industry as $int) {
                $industryoptions .= "<option value='" . $int->intype_id . "'>" . $int->type . "</option>";
            }
        }
        $data['industryoptions'] = $industryoptions;

        // Product Category
        $categorys = Tbl_productcategory::all();
        $categoryoptions = "<option value='All'>All</option>";
        if (count($categorys) > 0) {
            foreach ($categorys as $int) {
                $categoryoptions .= "<option value='" . $int->procat_id . "'>" . $int->category . "</option>";
            }
        }
        $data['categoryoptions'] = $categoryoptions;

        $data['createDate'] = $this->getCreationDates();
        $data['submitDate'] = $this->getSubmissionDates();
        $data['uploadDate'] = $this->getUploadedDates();

        $data['CreationDateVal'] = "All";
        $data['SubmitDateVal'] = "All";
        $data['UploadDateVal'] = "All";
        $data['typeRd'] = "All";
        $data['priorityVal'] = "All";
        $data['productName'] = "All";
        $data['productCategory'] = "All";
        $data['statusName'] = "All";

        return view('auth.rds.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $industry = Tbl_industrytypes::all();
        $industryoptions = "<option value=''>Select Industry Type</option>";
        if (count($industry) > 0) {
            foreach ($industry as $int) {
                $industryoptions .= "<option value='" . $int->intype_id . "'>" . $int->type . "</option>";
            }
        }
        $data['industryoptions'] = $industryoptions;

        $rdtypes = Tbl_rdtypes::all();
        $rdtypeoptions = "<option value=''>Select RD Type</option>";
        if (count($rdtypes) > 0) {
            foreach ($rdtypes as $rdtype) {
                $rdtypeoptions .= "<option value='" . $rdtype->rdtype_id . "'>" . $rdtype->type . "</option>";
            }
        }
        $data['rdtypeoptions'] = $rdtypeoptions;

        $rdprtypes = Tbl_rdpriority::all();
        $rdprtypeoptions = "<option value=''>Select Priority</option>";
        if (count($rdprtypes) > 0) {
            foreach ($rdprtypes as $rdprtype) {
                $rdprtypeoptions .= "<option value='" . $rdprtype->rdpr_id . "'>" . $rdprtype->priority . "</option>";
            }
        }
        $data['rdprtypeoptions'] = $rdprtypeoptions;

        $rdtrends = Tbl_rdtrend::all();
        $rdtrendoptions = "<option value='0'>Select Trend</option>";
        if (count($rdtrends) > 0) {
            foreach ($rdtrends as $rdtrend) {
                $rdtrendoptions .= "<option value='" . $rdtrend->rdtr_id . "'>" . $rdtrend->trend . "</option>";
            }
        }
        $data['rdtrendoptions'] = $rdtrendoptions;

        //  Products
        $products = Tbl_products::where('active', 1)->get();    //where('uid', $uid)->
        $productoptions = '<option value="">Select Product...</option>';
        foreach ($products as $product) {
            $productoptions .= '<option value="' . $product->pro_id . '">' . $product->name . '</option>';
        }
        $data['productoptions'] = $productoptions;



        return view('auth.rds.create')->with('data', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // echo json_encode($request->input());
        // exit();

        $this->validate($request, [
            'title' => 'required|max:255',
            'pro_id' => 'required|max:255',

            'rdtype_id' => 'required|max:255',
            'rdpr_id' => 'required|max:255',
        ], [
            'pro_id.required' => 'Select product',

            'rdtype_id.required' => 'Select RD Type',
            'rdpr_id.required' => 'Select Priority',
        ]);

        // /'intype_id' => 'required|max:255',  'intype_id.required' => 'Select Industry Type',

        $createdate = ($request->input('createdate') != '') ? date('Y-m-d', strtotime($request->input('createdate'))) : NULL;
        $submitdate = ($request->input('submitdate') != '') ? date('Y-m-d', strtotime($request->input('submitdate'))) : NULL;
        $uploadeddate = ($request->input('uploadeddate') != '') ? date('Y-m-d', strtotime($request->input('uploadeddate'))) : NULL;

        $formdata = array(
            'uid' => Auth::user()->id,
            'title' => $request->input('title'),
            'rdtype_id' => $request->input('rdtype_id'),
            'intype_id' => 0,
            'rdpr_id' => $request->input('rdpr_id'),
            'rdtr_id' => $request->input('rdtr_id'),
            'pro_id' => $request->input('pro_id'),
            // 'status' => $request->input('status'),
            'link' => $request->input('link'),
            'creation_date' => $createdate,
            'uploaded_date' => $uploadeddate,
            'submission_date' => $submitdate,
            'status' => 0,
            'user_type' => 2,
            'status' => $request->input('rd_status'),
        );

        $res = Tbl_rds::create($formdata);

        if ($res->rd_id > 0) {
            return redirect('/rds')->with('success', 'Rd Created Successfully');
        } else {
            return redirect('/rds')->with('error', 'Error occured. Try again later...!');
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
        $rds = Tbl_rds::with('tbl_rdtypes')->with('tbl_industrytypes')->with('tbl_rd_priority')->with('tbl_rd_trends')->with('tbl_products')->find($id);
        $pro_category = '';
        if ($rds->pro_id > 0) {
            $product = Tbl_products::where('active', 1)->with('Tbl_productcategory')->find($rds->pro_id);
            $pro_category = ($product->tbl_productcategory != '') ? $product->tbl_productcategory->category : '';
        }
        $rds['pro_category'] = $pro_category;
        // echo json_encode($rds);
        // exit();
        return view('auth.rds.show')->with('data', $rds);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rds = Tbl_rds::with('tbl_rdtypes')->with('tbl_industrytypes')->with('tbl_rd_priority')->with('tbl_rd_trends')->with('tbl_products')->find($id);
        // echo json_encode($rds);
        // exit();

        // $industry = Tbl_industrytypes::all();
        // $industryoptions = "<option value='0'>Select Industry Type</option>";
        // if (count($industry) > 0) {
        //     foreach ($industry as $int) {
        //         $in_selected = (($rds->intype_id > 0) && ($int->intype_id == $rds->intype_id)) ? 'selected' : '';
        //         $industryoptions .= "<option value='" . $int->intype_id . "' " . $in_selected . ">" . $int->type . "</option>";
        //     }
        // }
        // $rds['industryoptions'] = $industryoptions;

        $rdtypes = Tbl_rdtypes::all();
        $rdtypeoptions = "<option value=''>Select RD Type</option>";
        if (count($rdtypes) > 0) {
            foreach ($rdtypes as $rdtype) {
                $rd_selected = (($rds->rdtype_id > 0) && ($rdtype->rdtype_id == $rds->rdtype_id)) ? 'selected' : '';
                $rdtypeoptions .= "<option value='" . $rdtype->rdtype_id . "' " . $rd_selected . ">" . $rdtype->type . "</option>";
            }
        }
        $rds['rdtypeoptions'] = $rdtypeoptions;

        $rdprtypes = Tbl_rdpriority::all();
        $rdprtypeoptions = "<option value=''>Select Priority</option>";
        if (count($rdprtypes) > 0) {
            foreach ($rdprtypes as $rdprtype) {
                $rdpr_selected = (($rds->rdpr_id > 0) && ($rdprtype->rdpr_id == $rds->rdpr_id)) ? 'selected' : '';
                $rdprtypeoptions .= "<option value='" . $rdprtype->rdpr_id . "' " . $rdpr_selected . ">" . $rdprtype->priority . "</option>";
            }
        }
        $rds['rdprtypeoptions'] = $rdprtypeoptions;

        $rdtrends = Tbl_rdtrend::all();
        $rdtrendoptions = "<option value='0'>Select Trend</option>";
        if (count($rdtrends) > 0) {
            foreach ($rdtrends as $rdtrend) {
                $rdtr_selected = (($rds->rdtr_id > 0) && ($rdtrend->rdtr_id == $rds->rdtr_id)) ? 'selected' : '';
                $rdtrendoptions .= "<option value='" . $rdtrend->rdtr_id . "' " . $rdtr_selected . ">" . $rdtrend->trend . "</option>";
            }
        }
        $rds['rdtrendoptions'] = $rdtrendoptions;

        //  Products
        $pro_category = '';
        $products = Tbl_products::where('active', 1)->with('Tbl_productcategory')->get();    //where('uid', $uid)->
        $productoptions = '<option value="">Select Product...</option>';
        foreach ($products as $product) {
            $pro_selected = (($rds->pro_id > 0) && ($product->pro_id == $rds->pro_id)) ? 'selected' : '';
            if ($pro_selected == 'selected') {
                // echo json_encode($product);
                // exit();
                $pro_category = ($product->tbl_productcategory != '') ? $product->tbl_productcategory->category : '';
            }
            $productoptions .= '<option value="' . $product->pro_id . '" ' . $pro_selected . '>' . $product->name . '</option>';
        }
        $rds['productoptions'] = $productoptions;
        $rds['pro_category'] = $pro_category;

        return view('auth.rds.edit')->with('data', $rds);
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
        //
        // echo json_encode($request->input());
        // exit();

        $this->validate($request, [
            'title' => 'required|max:255',
            'pro_id' => 'required|max:255',
            'rdtype_id' => 'required|max:255',
            'rdpr_id' => 'required|max:255',
        ], [
            'pro_id.required' => 'Select product',
            'rdtype_id.required' => 'Select RD Type',
            'rdpr_id.required' => 'Select Priority',
        ]);

        // 'intype_id' => 'required|max:255',   'intype_id.required' => 'Select Industry Type',

        // $createdate = date('Y-m-d', strtotime($request->input('createdate')));
        // $submitdate = date('Y-m-d', strtotime($request->input('submitdate')));
        // $uploadeddate = ($request->input('uploadeddate') != '') ? date('Y-m-d', strtotime($request->input('uploadeddate'))) : '';

        $createdate = ($request->input('createdate') != '') ? date('Y-m-d', strtotime($request->input('createdate'))) : NULL;
        $submitdate = ($request->input('submitdate') != '') ? date('Y-m-d', strtotime($request->input('submitdate'))) : NULL;
        $uploadeddate = ($request->input('uploadeddate') != '') ? date('Y-m-d', strtotime($request->input('uploadeddate'))) : NULL;


        $rdData = Tbl_rds::find($id);

        $formdata = array(
            'uid' => Auth::user()->id,
            'title' => $request->input('title'),
            'rdtype_id' => $request->input('rdtype_id'),
            'intype_id' => 0,
            'rdpr_id' => $request->input('rdpr_id'),
            'rdtr_id' => $request->input('rdtr_id'),
            'pro_id' => $request->input('pro_id'),
            // 'status' => $request->input('status'),
            'link' => $request->input('link'),
            'creation_date' => $createdate,
            'uploaded_date' => $uploadeddate,
            'submission_date' => $submitdate,
            'status' => 0,
            'user_type' => 2,
            'status' => $request->input('rd_status'),
        );

        $res = Tbl_rds::where('rd_id', $id)->update($formdata);

        if ($res) {
            return redirect('/rds')->with('success', 'Rd Updated Successfully');
        } else {
            return redirect('/rds')->with('error', 'Error occured. Try again later...!');
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
        $res = Tbl_rds::where('rd_id', $id)->update(array('active' => 0));

        if ($res) {
            return redirect('/rds')->with('success', 'Rd Deleted Successfully');
        } else {
            return redirect('/rds')->with('error', 'Error occured. Try again later...!');
        }
    }

    public function getRdlist($type, $intype_id, $rdpr_id, $pro_id, $status, $create_date_start, $create_date_end, $submit_date_start, $submit_date_end, $upload_date_start, $upload_date_end, $procat_id)
    {

        $uid = Auth::user()->id;
        $data = array();
        $rds = '';

        //-----------------------------------------
        $query = DB::table('tbl_rds')->where('tbl_rds.active', 1)->where('tbl_rds.user_type', 2);
        if ($uid > 0) {
            $query->where('tbl_rds.uid', $uid);
        }
        if ($type > 0) {
            $query->where('tbl_rds.rdtype_id', $type);
        }
        if ($intype_id > 0) {
            $query->where('tbl_rds.intype_id', $intype_id);
        }
        if ($rdpr_id > 0) {
            $query->where('tbl_rds.rdpr_id', $rdpr_id);
        }
        if ($pro_id > 0) {
            $query->where('tbl_rds.pro_id', $pro_id);
        }
        if ($status > 0) {
            $query->where('tbl_rds.status', $status);
        }

        if (($create_date_start != '') && ($create_date_end != '')) {
            $query->whereBetween(DB::raw('DATE(tbl_rds.creation_date)'), [$create_date_start, $create_date_end]);
        }
        if (($submit_date_start != '') && ($submit_date_end != '')) {
            $query->whereBetween(DB::raw('DATE(tbl_rds.submission_date)'), [$submit_date_start, $submit_date_end]);
        }
        if (($upload_date_start != '') && ($upload_date_end != '')) {
            $query->whereBetween(DB::raw('DATE(tbl_rds.uploaded_date)'), [$upload_date_start, $upload_date_end]);
        }

        if ($procat_id > 0) {
            $proArray = array();
            $products = Tbl_products::where('procat_id', $procat_id)->get();

            if (count($products) > 0) {

                foreach ($products as $product) {
                    $proArray[] = $product->pro_id;
                }
                $query->whereIn('tbl_rds.pro_id', $proArray);
            } else {
                $data['total'] = 0;
                $data['table'] = 'No records available';
                return $data;
            }
        }

        $query->leftJoin('tbl_rdtypes', 'tbl_rds.rdtype_id', '=', 'tbl_rdtypes.rdtype_id');
        $query->leftJoin('tbl_industrytypes', 'tbl_rds.intype_id', '=', 'tbl_industrytypes.intype_id');
        $query->leftJoin('tbl_rd_priority', 'tbl_rds.rdpr_id', '=', 'tbl_rd_priority.rdpr_id');
        $query->leftJoin('tbl_rd_trends', 'tbl_rds.rdtr_id', '=', 'tbl_rd_trends.rdtr_id');
        $query->leftJoin('tbl_products', 'tbl_rds.pro_id', '=', 'tbl_products.pro_id');
        $query->orderBy('rd_id', 'desc');
        $query->select(
            'tbl_rds.*',
            'tbl_rdtypes.type as rd_type',
            'tbl_industrytypes.type as industry_type',
            'tbl_rd_priority.priority as rd_priority',
            'tbl_rd_trends.trend as rd_trend',
            'tbl_products.name as product'
        );
        $rds = $query->get();

        // echo json_encode($rds);
        // exit();

        // if ($type == 'All') {
        //     $rds = Tbl_rds::where('active', 1)->where('uid', $uid)->with('tbl_rdtypes')->with('tbl_industrytypes')->with('tbl_rd_priority')->with('tbl_rd_trends')->orderBy('rd_id', 'desc')->get();
        // }

        // if ($type > 0) {
        //     $rds = Tbl_rds::where('active', 1)->where('uid', $uid)->where('rdtype_id', $type)->with('tbl_rdtypes')->with('tbl_industrytypes')->with('tbl_rd_priority')->with('tbl_rd_trends')->orderBy('rd_id', 'desc')->get();
        // }

        $total = count($rds);
        if ($total > 0) {
            $formstable = '<div class="table-responsive"><table id="dealsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th width="2"></th>';
            $formstable .= '<th>Title</th>';
            $formstable .= '<th>Product</th>';
            $formstable .= '<th>Product Category</th>';
            // $formstable .= '<th>Industry Type</th>';
            $formstable .= '<th>RD Type</th>';
            $formstable .= '<th>Priority</th>';
            $formstable .= '<th>Status</th>';
            $formstable .= '<th>Link</th>';
            $formstable .= '<th>Creation Date</th>';
            $formstable .= '<th>Submission Date</th>';
            $formstable .= '<th>Uploaded Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($rds as $formdetails) {

                $creation_date = ($formdetails->creation_date != '') ? date('m-d-Y', strtotime($formdetails->creation_date)) : '';
                $submission_date = ($formdetails->submission_date != '') ? date('m-d-Y', strtotime($formdetails->submission_date)) : '';
                $uploaded_date = ($formdetails->uploaded_date != '') ? date('m-d-Y', strtotime($formdetails->uploaded_date)) : '';

                $pro_category = '';
                if ($formdetails->pro_id > 0) {
                    $productDetails = Tbl_products::where('active', 1)->with('Tbl_productcategory')->find($formdetails->pro_id);
                    $pro_category = ((isset($productDetails->tbl_productcategory))&&($productDetails->tbl_productcategory != '')) ? $productDetails->tbl_productcategory->category : '';
                }

                // $rdstatus = '';
                // if ($formdetails->status == 1) {
                //     $rdstatus = 'Yes';
                // }

                // if ($formdetails->status == 2) {
                //     $rdstatus = 'No';
                // }


                $statusId = '"' . 'status' . $formdetails->rd_id . '"';
                $statusSelect = "<select id=" . $statusId . " onchange='return ChangeStatus(" . $statusId . "," . $formdetails->rd_id . ");'>";
                $statusSelect .= "<option value='0'>None</option>";
                $statusSelect .= "<option value='1' " . (($formdetails->status == 1) ? 'selected' : '') . ">Yes</option>";
                $statusSelect .= "<option value='2' " . (($formdetails->status == 2) ? 'selected' : '') . ">No</option>";
                $statusSelect .= "</select>";


                $formstable .= '<tr>';
                $formstable .= '<td><div class="custom-control custom-checkbox"><input type="checkbox" class="checkAll custom-control-input" id="' . $formdetails->rd_id . '"><label class="custom-control-label" for="' . $formdetails->rd_id . '"></label></div></td>';
                // $formstable .= '<td><div class="custom-control custom-checkbox"><input type="checkbox" class="checkAll custom-control-input" id="' . $formdetails->ld_id . '"><label class="custom-control-label" for="' . $formdetails->ld_id . '"></label></div></td>';
                $formstable .= '<td><label class="control-label"><a href="' . url('rds/' . $formdetails->rd_id) . '">' . $formdetails->title . '</a></label></td>';
                $formstable .= '<td>' . (($formdetails->product != '') ? $formdetails->product : '') . '</td>';
                $formstable .= '<td>' . $pro_category . '</td>';
                // $formstable .= '<td>' . (($formdetails->industry_type != '') ? $formdetails->industry_type : '') . '</td>';   //(($formdetails->tbl_industrytypes != '') ? $formdetails->tbl_industrytypes->type : '')
                $formstable .= '<td>' . (($formdetails->rd_type != '') ? $formdetails->rd_type : '') . '</td>';   //(($formdetails->tbl_rdtypes != '') ? $formdetails->tbl_rdtypes->type : '')
                $formstable .= '<td>' . (($formdetails->rd_priority != '') ? $formdetails->rd_priority : '') . '</td>';
                $formstable .= '<td>' . $statusSelect . '</td>';    //$rdstatus
                $formstable .= '<td>' . $formdetails->link . '</td>';
                $formstable .= '<td>' . $creation_date . '</td>';
                $formstable .= '<td>' . $submission_date  . '</td>';
                $formstable .= '<td>' . $uploaded_date  . '</td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                      <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                      </button>
                        <div class="dropdown-menu">
                          <a class="dropdown-item text-default text-btn-space" href="' . url('rds/' . $formdetails->rd_id . '/edit') . '">Edit</a>
                          <a class="dropdown-item text-default text-btn-space" href="' . url('rds/delete/' . $formdetails->rd_id) . '">Delete</a>
                        </div>
                    </div>';
                $formstable .= '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table></div>';
        } else {
            $formstable = 'No records available';
        }
        $data['total'] = $total;
        $data['table'] = $formstable;
        return $data;
    }

    public function AjaxchangeRdStatus(Request $request)
    {

        $rd_id = $request->input('id');
        $status = $request->input('status');
        return $this->changeRdStatus($rd_id, $status);
    }

    public function changeRdStatus($id, $status)
    {
        return Tbl_rds::where('rd_id', $id)->update(array('status' => $status));
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->input('id');
        // $res = Tbl_rds::whereIn('not_id', $ids)->delete();
        $res = Tbl_rds::whereIn('rd_id', $ids)->update(array('active' => 0));
        return $res;
    }

    public function AjaxGetRdList(Request $request)
    {

        // return json_encode($request->input());
        // exit();
        $type = $request->input('type');
        $intype_id = $request->input('intype_id');
        $rdpr_id = $request->input('rdpr_id');
        $pro_id = $request->input('pro_id');
        $status = $request->input('status');
        $procat_id = $request->input('procat_id');

        // $create_date = ($request->input('create_date') != '') ? date('Y-m-d', strtotime($request->input('create_date'))) : '';
        // $submit_date = ($request->input('submit_date') != '') ? date('Y-m-d', strtotime($request->input('submit_date'))) : '';
        // $upload_date = ($request->input('upload_date') != '') ? date('Y-m-d', strtotime($request->input('upload_date'))) : '';

        $create_date_start = ($request->input('create_date_start') != '') ? date('Y-m-d', strtotime($request->input('create_date_start'))) : '';
        $create_date_end = ($request->input('create_date_end') != '') ? date('Y-m-d', strtotime($request->input('create_date_end'))) : '';

        $submit_date_start = ($request->input('submit_date_start') != '') ? date('Y-m-d', strtotime($request->input('submit_date_start'))) : '';
        $submit_date_end = ($request->input('submit_date_end') != '') ? date('Y-m-d', strtotime($request->input('submit_date_end'))) : '';

        $upload_date_start = ($request->input('upload_date_start') != '') ? date('Y-m-d', strtotime($request->input('upload_date_start'))) : '';
        $upload_date_end = ($request->input('upload_date_end') != '') ? date('Y-m-d', strtotime($request->input('upload_date_end'))) : '';

        $createDates = $this->getCreationDates();
        $submitDates = $this->getSubmissionDates();
        $uploadDates = $this->getUploadedDates();

        if (($createDates['oDateCreate'] == $create_date_start) && ($createDates['lDateCreate'] == $create_date_end)) {
            $create_date_start = '';
            $create_date_end = '';
        }

        if (($submitDates['oDateSubmit'] == $submit_date_start) && ($submitDates['lDateSubmit'] == $submit_date_end)) {
            $submit_date_start = '';
            $submit_date_end = '';
        }

        if (($uploadDates['oDateUpload'] == $upload_date_start) && ($uploadDates['lDateUpload'] == $upload_date_end)) {
            $upload_date_start = '';
            $upload_date_end = '';
        }

        $data = $this->getRdlist($type, $intype_id, $rdpr_id, $pro_id, $status, $create_date_start, $create_date_end, $submit_date_start, $submit_date_end, $upload_date_start, $upload_date_end, $procat_id);  //$create_date, $submit_date, $upload_date

        $data['CreationDateVal'] = $this->getFilterTimeCreationDate($create_date_start, $create_date_end);
        $data['SubmitDateVal'] = $this->getFilterTimeSubmissionDate($submit_date_start, $submit_date_end);
        $data['UploadDateVal'] = $this->getFilterTimeUploadDate($upload_date_start, $upload_date_end);

        $typeRd = "All";
        $priorityVal = "All";
        $productName = "All";
        $productCategory = "All";
        $statusName = "All";

        if ($type > 0) {
            $Tbl_rdtypes = Tbl_rdtypes::find($type);
            $typeRd = $Tbl_rdtypes->type;
        }
        if ($rdpr_id > 0) {
            $Tbl_rdpriority = Tbl_rdpriority::find($rdpr_id);
            $priorityVal = $Tbl_rdpriority->priority;
        }
        if ($pro_id > 0) {
            $Tbl_products = Tbl_products::find($pro_id);
            $productName = $Tbl_products->name;
        }
        if ($status > 0) {
            $statusName = ($status == 1) ? 'Yes' : 'No';
        }
        if ($procat_id > 0) {
            $Tbl_productcategory = Tbl_productcategory::find($procat_id);
            $productCategory = $Tbl_productcategory->category;
        }

        $data['typeRd'] = $typeRd;
        $data['priorityVal'] = $priorityVal;
        $data['productName'] = $productName;
        $data['productCategory'] = $productCategory;
        $data['statusName'] = $statusName;

        return json_encode($data);
        // return json_encode($request->input());
    }

    public function getCreationDates()
    {
        $uid = Auth::user()->id;

        $oldDate = Tbl_rds::where('uid', $uid)->where(DB::raw('DATE(creation_date)'), '!=', '')->orderBy('creation_date', 'asc')->first();
        // echo json_encode($oldDate);
        // $cDate = date('Y-m-d', strtotime($oldDate->creation_date));
        
        $cDate = ($oldDate != '') ? date('Y-m-d', strtotime($oldDate->creation_date)) : date('Y-m-d');
        $data['oDateCreate'] = $cDate;

        $latDate = Tbl_rds::where('uid', $uid)->where(DB::raw('DATE(creation_date)'), '!=', '')->orderBy('creation_date', 'desc')->first();
        // echo json_encode($oldDate);
        // $lDate = date('Y-m-d', strtotime($latDate->creation_date));
        
        $lDate = ($latDate != '') ? date('Y-m-d', strtotime($latDate->creation_date)) : date('Y-m-d');
        $data['lDateCreate'] = $lDate;

        return $data;
    }

    public function getSubmissionDates()
    {
        $uid = Auth::user()->id;

        $oldDate = Tbl_rds::where('uid', $uid)->where(DB::raw('DATE(submission_date)'), '!=', '')->orderBy('submission_date', 'asc')->first();
        // echo json_encode($oldDate);
        // $cDate = date('Y-m-d', strtotime($oldDate->submission_date));
        $cDate = ($oldDate != '') ? date('Y-m-d', strtotime($oldDate->submission_date)) : date('Y-m-d');
        $data['oDateSubmit'] = $cDate;

        $latDate = Tbl_rds::where('uid', $uid)->where(DB::raw('DATE(submission_date)'), '!=', '')->orderBy('submission_date', 'desc')->first();
        // echo json_encode($oldDate);
        // $lDate = date('Y-m-d', strtotime($latDate->submission_date));
        $lDate = ($latDate != '') ? date('Y-m-d', strtotime($latDate->submission_date)) : date('Y-m-d');
        $data['lDateSubmit'] = $lDate;

        return $data;
    }

    public function getUploadedDates()
    {
        $uid = Auth::user()->id;

        $oldDate = Tbl_rds::where('uid', $uid)->where(DB::raw('DATE(uploaded_date)'), '!=', '')->orderBy('uploaded_date', 'asc')->first();
        // echo json_encode($oldDate);
        // $cDate = date('Y-m-d', strtotime($oldDate->uploaded_date));

        $cDate = ($oldDate != '') ? date('Y-m-d', strtotime($oldDate->uploaded_date)) : date('Y-m-d');
        $data['oDateUpload'] = $cDate;

        $latDate = Tbl_rds::where('uid', $uid)->where(DB::raw('DATE(uploaded_date)'), '!=', '')->orderBy('uploaded_date', 'desc')->first();
        // echo json_encode($oldDate);
        // $lDate = date('Y-m-d', strtotime($latDate->uploaded_date));

        $lDate = ($latDate != '') ? date('Y-m-d', strtotime($latDate->uploaded_date)) : date('Y-m-d');
        $data['lDateUpload'] = $lDate;

        return $data;
    }

    public function getFilterTimeCreationDate($start, $end)
    {
        // $crDates = $this->getCreationDates();
        // $oldDate = $crDates['oDateCreate'];
        // $latestDate = $crDates['lDateCreate'];

        $today = date('Y-m-d');

        $yesterday = date('Y-m-d', strtotime("-1 days"));

        $sevendays = date('Y-m-d', strtotime("-6 days"));

        $thirtydays = date('Y-m-d', strtotime("-29 days"));

        $current_month_form = date('Y-m-01', strtotime(date('Y-m-d')));

        $current_month_to =  date('Y-m-t', strtotime(date('Y-m-d')));

        $last_month_form = date('Y-m-01', strtotime('-1 MONTH'));

        $last_month_to =  date('Y-m-t', strtotime(date('Y-m-01') . ' -1 MONTH'));

        $timer = "";

        if (($start ==  '') && ($end == '')) {
            $timer = "All";
        } else if (($start ==  $yesterday) && ($end == $yesterday)) {
            $timer = "Yesterday";
        } else if ($start ==  $end) {
            $timer = "Today";
        } else if (($start ==  $sevendays) && ($end == $today)) {
            $timer = "Last 7 Days";
        } else if (($start ==  $thirtydays) && ($end == $today)) {
            $timer = "Last 30 Days";
        } else if (($start ==  $current_month_form) && ($end == $current_month_to)) {
            $timer = "This Month";
        } else if (($start ==  $last_month_form) && ($end == $last_month_to)) {
            $timer = "Last Month";
        } else {
            $start = date('m-d-Y', strtotime($start));
            $end = date('m-d-Y', strtotime($end));

            $timer = "From:" . $start . ' To:' . $end;
        }

        return $timer;
    }

    public function getFilterTimeSubmissionDate($start, $end)
    {
        // $crDates = $this->getSubmissionDates();
        // $oldDate = $crDates['oDateSubmit'];
        // $latestDate = $crDates['lDateSubmit'];

        $today = date('Y-m-d');

        $yesterday = date('Y-m-d', strtotime("-1 days"));

        $sevendays = date('Y-m-d', strtotime("-6 days"));

        $thirtydays = date('Y-m-d', strtotime("-29 days"));

        $current_month_form = date('Y-m-01', strtotime(date('Y-m-d')));

        $current_month_to =  date('Y-m-t', strtotime(date('Y-m-d')));

        $last_month_form = date('Y-m-01', strtotime('-1 MONTH'));

        $last_month_to =  date('Y-m-t', strtotime(date('Y-m-01') . ' -1 MONTH'));

        $timer = "";

        if (($start ==  '') && ($end == '')) {
            $timer = "All";
        } else if (($start ==  $yesterday) && ($end == $yesterday)) {
            $timer = "Yesterday";
        } else if ($start ==  $end) {
            $timer = "Today";
        } else if (($start ==  $sevendays) && ($end == $today)) {
            $timer = "Last 7 Days";
        } else if (($start ==  $thirtydays) && ($end == $today)) {
            $timer = "Last 30 Days";
        } else if (($start ==  $current_month_form) && ($end == $current_month_to)) {
            $timer = "This Month";
        } else if (($start ==  $last_month_form) && ($end == $last_month_to)) {
            $timer = "Last Month";
        } else {
            $start = date('m-d-Y', strtotime($start));
            $end = date('m-d-Y', strtotime($end));

            $timer = "From:" . $start . ' To:' . $end;
        }

        return $timer;
    }

    public function getFilterTimeUploadDate($start, $end)
    {
        // $crDates = $this->getCreationDates();
        // $oldDate = $crDates['oDateUpload'];
        // $latestDate = $crDates['lDateUpload'];

        $today = date('Y-m-d');

        $yesterday = date('Y-m-d', strtotime("-1 days"));

        $sevendays = date('Y-m-d', strtotime("-6 days"));

        $thirtydays = date('Y-m-d', strtotime("-29 days"));

        $current_month_form = date('Y-m-01', strtotime(date('Y-m-d')));

        $current_month_to =  date('Y-m-t', strtotime(date('Y-m-d')));

        $last_month_form = date('Y-m-01', strtotime('-1 MONTH'));

        $last_month_to =  date('Y-m-t', strtotime(date('Y-m-01') . ' -1 MONTH'));

        $timer = "";

        if (($start ==  '') && ($end == '')) {
            $timer = "All";
        } else if (($start ==  $yesterday) && ($end == $yesterday)) {
            $timer = "Yesterday";
        } else if ($start ==  $end) {
            $timer = "Today";
        } else if (($start ==  $sevendays) && ($end == $today)) {
            $timer = "Last 7 Days";
        } else if (($start ==  $thirtydays) && ($end == $today)) {
            $timer = "Last 30 Days";
        } else if (($start ==  $current_month_form) && ($end == $current_month_to)) {
            $timer = "This Month";
        } else if (($start ==  $last_month_form) && ($end == $last_month_to)) {
            $timer = "Last Month";
        } else {
            $start = date('m-d-Y', strtotime($start));
            $end = date('m-d-Y', strtotime($end));

            $timer = "From:" . $start . ' To:' . $end;
        }

        return $timer;
    }
}

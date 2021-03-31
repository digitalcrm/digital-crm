<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Tbl_deals;
use App\Tbl_salesfunnel;
use App\Tbl_leads;
use App\currency;
use App\Tbl_products;
use App\Tbl_paymentstatus;
use App\Tbl_Accounts;
use Excel;

class SalesController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('test:sales', ['only' => ['index', 'show', 'create', 'store', 'edit', 'update', 'delete', 'deleteAll', 'import', 'importData', 'export', 'exportData']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $uid = Auth::user()->id;
        // $cr_id = Auth::user()->cr_id;
        // $currency = currency::find($cr_id);

        $day = ""; //date('Y-m-d');
        $weekDay = ""; //date('Y-m-d', strtotime($day . ' - 1 week'));
        $status = "All";
        $data = $this->getSalesData($uid, $weekDay, $day, $status);

        $paymentstatus = Tbl_paymentstatus::all();

        $paymentstatusOptions = "<option value='All'>All</option>";
        foreach ($paymentstatus as $pystatus) {
            $paymentstatusOptions .= "<option value='" . $pystatus->paystatus_id . "'>" . $pystatus->status . "</option>";
        }
        $data['paymentstatusOptions'] = $paymentstatusOptions;

        return view('auth.sales.index')->with("data", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $deals = Tbl_deals::with('Tbl_leads')
            ->with('Tbl_leadsource')
            ->with('Tbl_salesfunnel')
            ->find($id);
        $data['deals'] = $deals->toArray();
        return view('auth.sales.show')->with("data", $data);
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

    public function exportData($type)
    {

        $uid = Auth::user()->id;
        $deals = DB::table('tbl_deals')
            ->where('tbl_deals.uid', $uid)
            ->where('tbl_deals.sfun_id', 5)
            ->leftJoin('tbl_leads', 'tbl_leads.ld_id', '=', 'tbl_deals.ld_id')
            ->leftJoin('tbl_accounts', 'tbl_accounts.acc_id', '=', 'tbl_deals.acc_id')
            ->select([
                'tbl_deals.value as amount', DB::raw('CONCAT(tbl_leads.first_name," ",tbl_leads.last_name) as customer'),
                'tbl_deals.deal_id',
                'tbl_deals.uid',
                'tbl_deals.name',
                DB::raw('DATE_FORMAT(tbl_deals.closing_date,"%d-%m-%Y") as closing_date'),
                'tbl_deals.notes',
                'tbl_accounts.name as account',
            ])->get();

        //        echo json_encode($deals);

        $deals_decode = json_decode($deals, true);

        $sheetName = 'sales_' . date('d_m_Y_h_i_s');

        return Excel::create($sheetName, function ($excel) use ($deals_decode, $sheetName) {
            $excel->sheet($sheetName, function ($sheet) use ($deals_decode) {
                $sheet->fromArray($deals_decode);
            });
        })->download($type);
    }

    public function import($type)
    {
        return view('auth.sales.import');
    }

    public function importData(Request $request)
    {
        $uid = Auth::user()->id;
        //        echo json_encode($request->input());

        $filename = '';
        if ($request->hasfile('importFile')) {

            //-------------Image Validation----------------------------------
            $file = $request->file('importFile');
            // Build the input for validation
            $fileArray = array('importFile' => $file, 'extension' => strtolower($file->getClientOriginalExtension()));

            // Tell the validator that this file should be an image
            $rules = array(
                'importFile' => 'required', // max 10000kb
                'extension' => 'required|in:csv'
            );

            // Now pass the input and rules into the validator
            $validator = Validator::make($fileArray, $rules);

            // Check to see if validation fails or passes
            if ($validator->fails()) {
                return redirect('/sales/import/csv')->with('error', 'Please upload .csv only.');
            }
            //-------------Image Validation----------------------------------

            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $path = $request->file('importFile')->getRealPath();
            $data = Excel::load($path)->get();
            $exist_rec = '';

            if ($data->count()) {
                //                echo json_encode($data);
                foreach ($data as $key => $value) {

                    $sql = "SELECT `ld_id` FROM `tbl_leads` WHERE uid =" . $uid . " and CONCAT(`first_name`,' ',`last_name`) ='" . $value->customer . "'";
                    //                    echo $sql.'<br>';
                    $exist = DB::select($sql);
                    if (count($exist) > 0) {

                        //                        $exist_rec .= $value->lead . ', ';
                        $acc_id = 0;
                        $ld_id = 0;
                        $sfun_id = 0;
                        $deal_status = 1;
                        $ldsrc_id = 0;

                        foreach ($exist as $leadId) {
                            $ld_id = $leadId->ld_id;
                            //                            echo 'Lead Id : ' . $leadId->ld_id . ' ' . $value->lead . ' exists <br>';
                        }

                        if ($value->account != '') {
                            $accounts = Tbl_Accounts::where(strtolower('name'), strtolower($value->account))->first();
                            if ($accounts != '') {
                                $acc_id = $accounts->acc_id;
                            }
                        }
                        //
                        ////                        if ($value->salesfunnel == 'Won') {
                        ////                            $deal_status = 1;
                        ////                        }
                        ////                        if ($value->salesfunnel == 'Lost') {
                        ////                            $deal_status = 2;
                        ////                        }
                        ////                        if ($value->leadsource != '') {
                        ////                            $ldsrc = Tbl_leadsource::where(strtolower('leadsource'), strtolower($value->leadsource))->first();
                        ////                            if ($ldsrc != '') {
                        ////                                $ldsrc_id = $ldsrc->ldsrc_id;
                        ////                            }
                        ////                        }
                        //                        if ($value->salesfunnel != '') {
                        $sfun = Tbl_salesfunnel::where(strtolower('salesfunnel'), strtolower('Won'))->first();
                        if ($sfun != '') {
                            $sfun_id = $sfun->sfun_id;
                        }
                        //                        }
                        $formdata = array(
                            'uid' => $uid,
                            'ld_id' => $ld_id,
                            'sfun_id' => $sfun_id,
                            'ldsrc_id' => $ldsrc_id,
                            'deal_status' => $deal_status,
                            'name' => $value->name,
                            'value' => $value->amount,
                            'closing_date' => date('Y-m-d', strtotime(str_replace('/', '-', $value->closing_date))),
                            'notes' => $value->notes,
                        );

                        $arr[] = $formdata;
                    } else {
                        //                        echo $value->lead . ' not exists <br>';
                        $exist_rec .= $value->customer . ', ';
                    }
                }
                //                echo json_encode($arr);
                if (!empty($arr)) {
                    Tbl_deals::insert($arr);
                }

                if ($exist_rec != '') {
                    $with_status = 'info';
                    $with_message = trim($exist_rec, ",") . 'not exists. Remaing Uploaded successfully...!';
                }

                if ($exist_rec == '') {
                    $with_status = 'success';
                    $with_message = 'Uploaded successfully...!';
                }
                return redirect('/sales')->with($with_status, $with_message);
            } else {
                return redirect('/sales/import/csv')->with('error', "Please check uploaded file. Data don't exist.");
            }
        } else {
            return redirect('/sales/import/csv')->with('error', 'Please upload file.');
        }
    }

    public function getSalesFilter(Request $request)
    {

        // return json_encode($request->input());

        $uid = Auth::user()->id;

        $status = $request->input('status');
        $start = ($request->input('start') != '') ? date('Y-m-d', strtotime($request->input('start'))) : '';
        $end = ($request->input('end') != '') ? date('Y-m-d', strtotime($request->input('end'))) : '';

        $data = $this->getSalesData($uid, $start, $end, $status);

        return json_encode($data);
    }

    public function getSalesData($uid, $start, $end, $status)
    {
        $cr_id = Auth::user()->cr_id;
        $currency = currency::find($cr_id);

        $paymentstatus = Tbl_paymentstatus::all();

        $query = DB::table('tbl_deals')->where('tbl_deals.uid', $uid)->where('tbl_deals.active', 1)->where('tbl_deals.deal_status', 1);

        if ($status > 0) {
            $query->where('tbl_deals.paystatus_id', $status);
        }

        if (($start != '') && ($end != '')) {
            $query->whereBetween(DB::raw('DATE(tbl_deals.closing_date)'), [$start, $end]);
        }
        $query->leftJoin('tbl_salesfunnel', 'tbl_deals.sfun_id', '=', 'tbl_salesfunnel.sfun_id');
        $query->leftJoin('tbl_products', 'tbl_deals.pro_id', '=', 'tbl_products.pro_id');
        $query->leftJoin('tbl_leads', 'tbl_deals.ld_id', '=', 'tbl_leads.ld_id');
        $query->orderBy('deal_id', 'desc');
        $query->select(
            'tbl_deals.*',
            'tbl_salesfunnel.salesfunnel as salesfunnel',
            'tbl_products.name as product',
            'tbl_leads.first_name',
            'tbl_leads.last_name',
            'tbl_leads.picture'
        );
        $deals = $query->get();
        // echo json_encode($deals);
        // exit();
        // $deals = Tbl_deals::with('tbl_salesfunnel')->with('Tbl_products')
        //     ->with(array('tbl_leads' => function ($query) {
        //         $query->where('tbl_leads.active', 1);
        //     }))
        //     ->where('uid', $uid)
        //     ->where('deal_status', 1)
        //     ->whereBetween(DB::raw('DATE(closing_date)'), [$start, $end])
        //     ->orderBy('closing_date', 'desc')
        //     ->get();
        //        echo json_encode($deals);

        if (count($deals) > 0) {
            $formstable = '<div class="table-responsive"><table id="salesTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Amount</th>';
            $formstable .= '<th>Lead</th>';
            $formstable .= '<th>Deal Name</th>';
            $formstable .= '<th>Closing Date</th>';
            $formstable .= '<th>Product</th>';
            $formstable .= '<th>Payment Status</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($deals as $formdetails) {

                // $leadimage = ($formdetails['tbl_leads']['picture'] != '') ? $formdetails['tbl_leads']['picture'] : '/uploads/default/leads.png';
                // $product = ($formdetails['pro_id'] > 0) ? $formdetails['tbl_products']['name'] : '';

                // //  ----------------------Select Tag--------------------------------
                $paystatusSelecttagidentifier = "'" . "payStatus" . $formdetails->deal_id . "'";
                $paystatusSelecttag = '<select class="btn btn-sm btn-default dropdown-toggle" id="payStatus' . $formdetails->deal_id . '" name="payStatus' . $formdetails->deal_id . '" onchange="return changePayStatus(' . $paystatusSelecttagidentifier . ',' . $formdetails->deal_id . '    );">';
                $paystatusSelecttag .= '<option value="0">Select</option>';
                foreach ($paymentstatus as $statuslist) {
                    $ldslected = ((int) $statuslist->paystatus_id === (int) $formdetails->paystatus_id) ? 'selected' : '';
                    $paystatusSelecttag .= '<option value="' . $statuslist->paystatus_id  . '" ' . $ldslected . '>' . $statuslist->status . '</option>';
                }

                $leadimage = ($formdetails->picture != '') ? $formdetails->picture : '/uploads/default/leads.png';

                $formstable .= '<tr>';
                $formstable .= '<td>' . $currency->html_code . ' ' . $formdetails->value . '</td>';
                $formstable .= '<td><img src="' . url($leadimage) . '" class="avatar"><a href="' . url('leads/' . $formdetails->ld_id) . '" target="_blank">&nbsp;' . $formdetails->first_name . ' ' . $formdetails->last_name . '</a></td>';
                $formstable .= '<td class="table-title"><a href="' . url('customers/' . $formdetails->deal_id) . '">' . $formdetails->name . '</a></td>';
                $formstable .= '<td>' . date('m-d-Y', strtotime($formdetails->closing_date)) . '</td>';
                $formstable .= '<td>' . $formdetails->product . '</td>';
                $formstable .= '<td>' . $paystatusSelecttag . '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table></div>';
        } else {
            $formstable = 'No records available';
        }
        $data['total'] = count($deals);
        $data['table'] = $formstable;

        return $data;
    }

    public function updateDealPayStatus(Request $request)
    {
        // return json_encode($request->input());

        $id = $request->input('id');
        $status = $request->input('status');

        $res =  Tbl_deals::where('deal_id', $id)->update(['paystatus_id' => $status]);
        return $res;
    }
}

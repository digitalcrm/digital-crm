<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
//-----------------Models-------------------------
use App\Tbl_deals;
use App\Tbl_salesfunnel;
use App\Tbl_leads;
use App\currency;
use App\User;
use App\Tbl_paymentstatus;

class SalesController extends Controller
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
        $useroptions = "<option value='All' Selected>All</option>";
        $uid = 'All';
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";    // . "  " . $selected 
        }
        $data['useroptions'] = $useroptions;

        $day = ""; //date('Y-m-d');
        $weekDay = ""; //date('Y-m-d', strtotime($day . ' - 1 week'));
        $status = "All";

        $deals = $this->getSales($uid, $weekDay, $day, $status);

        $data['table'] = $deals['table'];
        $data['total'] = $deals['total'];

        $paymentstatus = Tbl_paymentstatus::all();

        $paymentstatusOptions = "<option value='All'>All</option>";
        foreach ($paymentstatus as $pystatus) {
            $paymentstatusOptions .= "<option value='" . $pystatus->paystatus_id . "'>" . $pystatus->status . "</option>";
        }
        $data['paymentstatusOptions'] = $paymentstatusOptions;

        return view('admin.sales.index')->with("data", $data);
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
        //
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

    public function getSales($uid, $start, $end, $status)
    {
        // if ($uid == 'All') {
        //     $currency = currency::where('status', 1)->first();
        //     $deals = Tbl_deals::with('Tbl_salesfunnel')
        //         ->with('Tbl_leads')
        //         ->where('deal_status', 1)
        //         ->orderBy('closing_date', 'desc')
        //         ->get();
        // } else {

        //     $user = User::find($uid);
        //     $currency = currency::find($user->cr_id);
        //     $deals = Tbl_deals::with('Tbl_salesfunnel')
        //         ->with('Tbl_leads')
        //         ->where('uid', $uid)
        //         ->where('deal_status', 1)
        //         ->orderBy('closing_date', 'desc')
        //         ->get();
        // }


        $paymentstatus = Tbl_paymentstatus::all();
        $currency = "";
        $query = DB::table('tbl_deals')->where('tbl_deals.active', 1)->where('tbl_deals.deal_status', 1);
        if (($uid > 0) && ($uid != 'All')) {
            $user = User::find($uid);
            $currency = currency::find($user->cr_id);
            $query->where('tbl_deals.uid', $uid);
        }

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
            'tbl_leads.ld_id',
            'tbl_leads.first_name',
            'tbl_leads.last_name',
            'tbl_leads.picture'
        );
        $deals = $query->get();
        // echo json_encode($deals);
        // exit();

        if (count($deals) > 0) {
            $formstable = '<table id="salesTable" class="table">';
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

                if ($uid == 'All') {
                    $user = User::with('currency')->find($formdetails->uid);
                    $currency = $user['currency'];
                }


                //  ----------------------Select Tag--------------------------------
                $paystatusSelecttagidentifier = "'" . "payStatus" . $formdetails->deal_id . "'";
                $paystatusSelecttag = '<select class="btn btn-sm btn-default dropdown-toggle" id="payStatus' . $formdetails->deal_id . '" name="payStatus' . $formdetails->deal_id . '" onchange="return changePayStatus(' . $paystatusSelecttagidentifier . ',' . $formdetails->deal_id . '    );">';
                $paystatusSelecttag .= '<option value="0">Select</option>';
                foreach ($paymentstatus as $statuslist) {
                    $ldslected = ((int) $statuslist->paystatus_id === (int) $formdetails->paystatus_id) ? 'selected' : '';
                    $paystatusSelecttag .= '<option value="' . $statuslist->paystatus_id  . '" ' . $ldslected . '>' . $statuslist->status . '</option>';
                }

                $leadimage = ($formdetails->picture != '') ? $formdetails->picture : '/uploads/default/leads.png';

                $formstable .= '<tr>';
                $formstable .= '<td>' . $currency['html_code'] . ' ' . $formdetails->value . '</td>';
                $formstable .= '<td><img src="' . url($leadimage) . '" class="avatar"><a href="' . url('admin/leads/' . $formdetails->ld_id) . '" target="_blank">' . substr($formdetails->first_name . ' ' . $formdetails->last_name, 0, 25) . '</a></td>';
                $formstable .= '<td class="table-title"><a href="' . url('admin/customers/' . $formdetails->deal_id) . '">' . $formdetails->name . '</a></td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->closing_date)) . '</td>';
                $formstable .= '<td>' . $formdetails->product . '</td>';
                $formstable .= '<td>' . $paystatusSelecttag . '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = count($deals);
        $data['table'] = $formstable;

        return $data;
    }


    public function getSalesFilter(Request $request)
    {

        // return json_encode($request->input());
        // exit();

        $uid = $request->input('user');

        $status = $request->input('status');
        $start = ($request->input('start') != '') ? date('Y-m-d', strtotime($request->input('start'))) : '';
        $end = ($request->input('end') != '') ? date('Y-m-d', strtotime($request->input('end'))) : '';

        $data = $this->getSales($uid, $start, $end, $status);

        return json_encode($data);
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

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tbl_deals;
use App\Tbl_salesfunnel;
use App\Tbl_leads;
use App\currency;
use App\User;

class CustomersController extends Controller
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

        $deals = $this->getCustomers($uid);

        $data['table'] = $deals['table'];
        $data['total'] = $deals['total'];

        return view('admin.customers.index')->with("data", $data);
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
        $deals = Tbl_deals::with('Tbl_leads')
            ->with('Tbl_leadsource')
            ->with('Tbl_salesfunnel')
            ->find($id);
        $data['deals'] = $deals->toArray();
        return view('admin.customers.show')->with("data", $data);
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

    public function getCustomers($uid)
    {

        if ($uid == 'All') {
            $currency = currency::where('status', 1)->first();
            $deals = Tbl_deals::with('Tbl_salesfunnel')
                ->with('Tbl_leads')
                ->where('deal_status', 1)
                ->orderBy('closing_date', 'desc')
                ->get();
        } else {
            $user = User::find($uid);
            $currency = currency::find($user->cr_id);
            $deals = Tbl_deals::with('Tbl_salesfunnel')
                ->with('Tbl_leads')
                ->where('uid', $uid)
                ->where('deal_status', 1)
                ->orderBy('closing_date', 'desc')
                ->get();
        }
        //        echo json_encode($deals);

        if (count($deals) > 0) {
            $formstable = '<table id="customersTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Lead</th>';
            $formstable .= '<th>Deal Name</th>';
            $formstable .= '<th>Amount</th>';
            $formstable .= '<th>Closing Date</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($deals as $formdetails) {


                //                if ($uid == 'All') {
                //                    $user = User::find($formdetails->uid);
                //                    $currency = currency::find($user->cr_id);
                //                }

                $formstable .= '<tr>';
                $formstable .= '<td><a href="' . url('admin/leads/' . $formdetails['tbl_leads']['ld_id']) . '" target="_blank">' . $formdetails['tbl_leads']['first_name'] . ' ' . $formdetails['tbl_leads']['last_name'] . '</a></td>';
                $formstable .= '<td class="table-title"><a href="' . url('admin/customers/' . $formdetails['deal_id']) . '">' . $formdetails['name'] . '</a></td>';
                $formstable .= '<td><span>' . $currency->html_code . '</span>&nbsp;' . $formdetails['value'] . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails['closing_date'])) . '</td>';
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
}

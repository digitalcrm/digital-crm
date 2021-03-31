<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Support\Facades\Validator;

use App\Imports\DealsImport;
use App\Exports\DealsExport;
use Maatwebsite\Excel\Facades\Excel;
//---------Models---------------
use App\Tbl_Accounts;
use App\Tbl_leads;
use App\Tbl_countries;
use App\Tbl_states;
use App\Tbl_deals;
use App\Tbl_industrytypes;
use App\Tbl_leadsource;
use App\Tbl_leadstatus;
use App\Tbl_salesfunnel;
use App\User;
use App\currency;
use App\Tbl_deal_types;
use App\Tbl_projects;
use App\Tbl_products;


//use Controllers
use App\Http\Controllers\Admin\ProjectController;

class DealController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('test:deal-list', ['only' => ['index', 'show']]);
        $this->middleware('test:deal-create', ['only' => ['create', 'store']]);
        $this->middleware('test:deal-edit', ['only' => ['edit', 'update']]);
        $this->middleware('test:deal-delete', ['only' => ['destroy', 'deleteDeal', 'deleteAll']]);
        $this->middleware('test:deal-import', ['only' => ['import', 'importData']]);
        $this->middleware('test:deal-export', ['only' => ['export', 'exportData']]);
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

        $stage = 0;
        // $day = date('Y-m-d');
        // $weekDay = date('Y-m-d', strtotime($day . ' - 30 days'));   // 1 week

        $day = '';
        $weekDay = '';

        $deals = $this->getDeals($uid, $stage, $day, $weekDay);

        $data['table'] = $deals['table'];
        $data['total'] = $deals['total'];

        $dealstage = Tbl_salesfunnel::all();
        $dealstageoptions = "<option value='All'>All</option>";
        if (count($dealstage) > 0) {
            foreach ($dealstage as $stage) {
                $dealstageoptions .= "<option value='" . $stage->sfun_id . "'>" . $stage->salesfunnel . "</option>";
            }
        }
        $data['dealstageoptions'] = $dealstageoptions;

        $data['closeDate'] = $this->getCloseDates();

        $data['timer'] = 'All';
        $data['dealstageVal'] = 'All';
        $data['userVal'] = 'All';

        return view('admin.deals.index')->with("data", $data);
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
            ->with('Tbl_deal_types')
            ->with('Tbl_products')
            ->find($id);
        $data['deals'] = $deals->toArray();
        $data['editlink'] = url('admin/deals/' . $id . '/edit');


        $product = ($deals['pro_id'] > 0) ? $deals['tbl_products']['name'] : '';
        $data['product'] = $product;

        $acc_id = $deals['tbl_leads']['acc_id'];
        $account = '';
        $company = '';
        if ($acc_id > 0) {
            $accountDetails = Tbl_Accounts::find($acc_id);
            $account = $accountDetails->name;
            $company = $accountDetails->company;
        }
        $data['account'] = $account;
        $data['company'] = $company;

        $user = User::find($deals['uid']);
        $currency = currency::find($user->cr_id);
        $data['currency'] = $currency;

        return view('admin.deals.show')->with("data", $data);
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
            ->with('Tbl_deal_types')
            ->find($id);
        $data['deal'] = $deals;


        $leads = Tbl_leads::all();
        $leadoptions = "<option value=''>Select Lead</option>";
        if (count($leads) > 0) {
            foreach ($leads as $lead) {
                $leadselected = ($deals->tbl_leads->ld_id == $lead->ld_id) ? 'selected' : '';
                $leadoptions .= "<option value='" . $lead->ld_id . "' " . $leadselected . ">" . $lead->first_name . " " . $lead->last_name . "</option>";
            }
        }
        $leadoptions .= "<option disabled>---</option>";
        $leadoptions .= "<option value='NewLead'>Add Lead</option>";
        $data['leadoptions'] = $leadoptions;

        $leadsource = Tbl_leadsource::all();
        $leadsourceoptions = "<option value='0'>Select Lead Source</option>";
        if (count($leadsource) > 0) {
            foreach ($leadsource as $source) {
                $leadsourceselected = (($deals->tbl_leadsource != '') && ($deals->tbl_leadsource->ldsrc_id == $source->ldsrc_id)) ? 'selected' : '';
                $leadsourceoptions .= "<option value='" . $source->ldsrc_id . "' " . $leadsourceselected . ">" . $source->leadsource . "</option>";
            }
        }
        $data['leadsourceoptions'] = $leadsourceoptions;

        $dealstage = Tbl_salesfunnel::all();
        $dealstageoptions = "<option value=''>Select Deal Stage</option>";
        if (count($dealstage) > 0) {
            foreach ($dealstage as $stage) {
                $dealstageselected = (($deals->tbl_salesfunnel != '') && ($deals->tbl_salesfunnel->sfun_id == $stage->sfun_id)) ? 'selected' : '';
                $dealstageoptions .= "<option value='" . $stage->sfun_id . "' " . $dealstageselected . ">" . $stage->salesfunnel . "</option>";
            }
        }
        $data['dealstageoptions'] = $dealstageoptions;


        //  Products
        $products = Tbl_products::where('active', 1)->get();    //where('uid', $uid)->
        $productoptions = '<option value="">Select ...</option>';
        foreach ($products as $product) {
            $productselected = (($deals->tbl_products != '') && ($deals->tbl_products->pro_id == $product->pro_id)) ? 'selected' : '';
            $productoptions .= '<option value="' . $product->pro_id . '" ' . $productselected . '>' . $product->name . '</option>';
        }
        $data['productoptions'] = $productoptions;

        //  Deal Type Options
        $dtypes = Tbl_deal_types::all();    //where('uid', $uid)->
        $dtypeoptions = '<option value="">Select Deal Type...</option>';
        foreach ($dtypes as $dtype) {
            $dtypeselected = (($deals->tbl_deal_types != '') && ($deals->tbl_deal_types->dl_id == $dtype->dl_id)) ? 'selected' : '';
            $dtypeoptions .= '<option value="' . $dtype->dl_id . '" ' . $dtypeselected . '>' . $dtype->type . '</option>';
        }
        $data['dealtype_options'] = $dtypeoptions;

        $uid = $deals->uid;
        $user = User::with('currency')->find($uid)->toArray();
        $data['user'] = $user;
        return view('admin.deals.edit')->with('data', $data);
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
            'dealname' => 'required|max:255',
            'amount' => 'required|numeric',
            'closingdate' => 'required|date_format:d-m-Y',
        ]);

        $ld_id = 0;
        if ($request->input('lead') == 'NewLead') {
            $arr_account = array(
                'uid' => Auth::user()->id,
                'first_name' => $request->input('addLead'),
            );
            $leadss = Tbl_leads::create($arr_account);
            $ld_id = $leadss->ld_id;
        } else {
            $ld_id = $request->input('lead');
        }

        $sfun_id = $request->input('dealstage');

        $deal_status = 0;
        if ($sfun_id == 5) {
            $deal_status = 1;
        }
        if ($sfun_id == 6) {
            $deal_status = 2;
        }

        $deals = Tbl_deals::find($id);
        $deals->ld_id = $ld_id;
        $deals->sfun_id = $request->input('dealstage');
        $deals->ldsrc_id = $request->input('leadsource');
        $deals->name = $request->input('dealname');
        $deals->value = $request->input('amount');
        $deals->closing_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('closingdate'))));
        $deals->notes = $request->input('notes');
        $deals->dl_id = $request->input('dl_id');
        $deals->deal_status = $deal_status;
        $deals->save();

        $proexist = Tbl_projects::where('deal_id', $id)->count();

        if ($deal_status == 1) {
            if ($proexist > 0) {
                Tbl_projects::where('deal_id', $id)->update(array('active' => 1));
            } else {
                $projectObj = new ProjectController();
                $project = $projectObj->createProject($id);
            }
        } else {
            if ($proexist > 0) {
                Tbl_projects::where('deal_id', $id)->update(array('active' => 0));
            }
        }

        return redirect('admin/deals/' . $id)->with('success', 'Deal Updated Successfully...!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }

    public function deleteDeal($id)
    {
        $deals = Tbl_deals::find($id);
        $deals->active = 0;
        $deals->save();
        return redirect('admin/deals')->with('success', 'Deleted Successfully...!');
    }

    public function getDeals($uid, $stage, $start, $end)
    {

        // return 'uid ' . $uid . ' stage ' . $stage . ' start ' . $start . ' end ' . $end;
        // exit();

        $deals = '';

        $query = DB::table('tbl_deals')->where('tbl_deals.active', 1);

        if (($uid > 0) && ($uid != 'All')) {
            $query->where('tbl_deals.uid', $uid);
        }

        if (($stage > 0) && ($stage != 'All')) {
            $query->where('tbl_deals.sfun_id', $stage);
        }

        if (($start != '') && ($end != '')) {
            $query->whereBetween(DB::raw('DATE(tbl_deals.closing_date)'), [$start, $end]);
        }

        $query->leftJoin('tbl_leads', 'tbl_leads.ld_id', '=', 'tbl_deals.ld_id');
        $query->leftJoin('tbl_leadsource', 'tbl_leadsource.ldsrc_id', '=', 'tbl_deals.ldsrc_id');
        $query->leftJoin('tbl_salesfunnel', 'tbl_salesfunnel.sfun_id', '=', 'tbl_deals.sfun_id');
        $query->leftJoin('tbl_deal_types', 'tbl_deal_types.dl_id', '=', 'tbl_deals.dl_id');
        $query->leftJoin('tbl_products', 'tbl_products.pro_id', '=', 'tbl_deals.pro_id');
        $query->orderBy('tbl_deals.deal_id', 'desc');
        $deals = $query->get(['tbl_deals.*', 'tbl_leads.first_name',  'tbl_leads.last_name', 'tbl_leadsource.leadsource', 'tbl_salesfunnel.salesfunnel', 'tbl_deal_types.type as deal_type', 'tbl_products.name as product']);

        // echo json_encode($deals);
        // exit();

        // echo $uid;
        // exit();

        if (($uid > 0) && ($uid != 'All')) {
            $user = User::find($uid);
            $currency = currency::find($user->cr_id);
        }

        $total = count($deals);
        if (count($deals) > 0) {
            $formstable = '<table id="dealsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            //            $formstable .= '<th width="2"><input type="checkbox" id="selectAll"></th>';
            $formstable .= '<th>Deal Name</th>';
            $formstable .= '<th>Deal Stage</th>';
            $formstable .= '<th>Deal Type</th>';
            $formstable .= '<th>Lead</th>';
            $formstable .= '<th>Account</th>';
            $formstable .= '<th>Company</th>';
            $formstable .= '<th>Product</th>';
            $formstable .= '<th>Amount</th>';
            $formstable .= '<th>Closing Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($deals as $formdetails) {

                if ($uid == 'All') {
                    $user = User::with('currency')->find($formdetails->uid);
                    // echo json_encode($user);
                    // exit();
                    $currency = $user['currency'];
                    // $currency = currency::find($user->cr_id);
                }

                // $product = ($formdetails->tbl_products->pro_id > 0) ? $formdetails->tbl_products->name : '';

                $acc_id = $formdetails->acc_id; //$formdetails->tbl_leads->acc_id;
                $account = '';
                $company = '';
                if ($acc_id > 0) {
                    $accountDetails = Tbl_Accounts::find($acc_id);
                    $account = $accountDetails->name;
                    $company = $accountDetails->company;
                }

                //                if ($uid == 'All') {
                //                    $user = User::find($formdetails['uid']);
                //                    $currency = currency::find($user->cr_id);
                //                }

                $formstable .= '<tr>';
                //                $formstable .= '<td><input type="checkbox" class="checkAll" id="' . $formdetails['deal_id'] . '"></td>';
                $formstable .= '<td><a href="' . url('admin/deals/' . $formdetails->deal_id) . '">' . $formdetails->name . '</a></td>';
                $formstable .= '<td>' . $formdetails->salesfunnel . '</td>';   //$formdetails->tbl_salesfunnel->salesfunnel
                $formstable .= '<td>' . $formdetails->deal_type . '</td>';   //$formdetails->tbl_deal_types->type
                $formstable .= '<td><a href="' . url('admin/leads/' . $formdetails->ld_id) . '" target="_blank">' . substr($formdetails->first_name . ' ' . $formdetails->last_name, 0, 25) . '</a></td>';   //$formdetails->tbl_leads->first_name . ' ' . $formdetails->tbl_leads->last_name
                $formstable .= '<td>' . $account . '</td>';
                $formstable .= '<td>' . $company . '</td>';
                $formstable .= '<td>' . $formdetails->product . '</td>';
                $formstable .= '<td><span>' . $currency['html_code'] . '</span>&nbsp;' . $formdetails->value . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->closing_date)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                      <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                  </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item text-default text-btn-space" href="' . url('admin/deals/' . $formdetails->deal_id) . '">View</a>
                        <a class="dropdown-item text-default text-btn-space" href="' . url('admin/deals/' . $formdetails->deal_id . '/edit') . '">Edit</a>
                        <a class="dropdown-item text-default text-btn-space" href="' . url('admin/deals/delete/' . $formdetails->deal_id) . '">Delete</a>
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

        return $data;
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->input('id');
        return Tbl_deals::whereIn('deal_id', $ids)->update(array('active' => 0));
    }

    public function getCloseDates()
    {

        $oldDate = Tbl_deals::where('active', 1)->where(DB::raw('DATE(closing_date)'), '!=', '')->orderBy('closing_date', 'asc')->first();
        // echo json_encode($oldDate);
        $cDate = '';
        if ($oldDate != '') {
            $cDate = date('Y-m-d', strtotime($oldDate->closing_date));
        }
        $data['oDateClose'] = $cDate;

        $latDate = Tbl_deals::where('active', 1)->where(DB::raw('DATE(closing_date)'), '!=', '')->orderBy('closing_date', 'desc')->first();
        // echo json_encode($oldDate);
        $lDate = '';
        if ($latDate != '') {
            $lDate = date('Y-m-d', strtotime($latDate->closing_date));
        }
        $data['lDateClose'] = $lDate;

        return $data;
    }


    public function import($type)
    {
        $users = User::orderby('id', 'asc')->get(['id', 'name']);

        $useroptions = "<option value=''>Select User...</option>";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";
        }
        $data['useroptions'] = $useroptions;

        return view('admin.deals.import')->with('data', $data);
    }

    public function importData(Request $request)
    {

        // echo json_encode($request->input());
        // exit();

        // $uid = Auth::user()->id;
        $uid = $request->input('selectUser');

        $filename = '';
        if ($request->hasfile('importFile')) {

            //-------------Image Validation----------------------------------
            $file = $request->file('importFile');
            // Build the input for validation
            $fileArray = array('importFile' => $file, 'extension' => strtolower($file->getClientOriginalExtension()));

            //            echo $file->getClientOriginalExtension();
            //            exit(0);
            // Tell the validator that this file should be an image
            $rules = array(
                'importFile' => 'required', // max 10000kb
                'extension' => 'required|in:csv'
            );

            // Now pass the input and rules into the validator
            $validator = Validator::make($fileArray, $rules);

            // Check to see if validation fails or passes
            if ($validator->fails()) {
                return redirect('admin/deals/import/csv')->with('error', 'Please upload .csv only.');
            }
            //-------------Image Validation----------------------------------

            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $path = $request->file('importFile')->getRealPath();

            $res = Excel::import(new DealsImport($uid), request()->file('importFile'));

            if ($res) {
                echo 'Success';
                return redirect('admin/deals')->with('success', 'Uploaded successfully');
            } else {
                // echo 'Failed';
                return redirect('admin/deals/import/csv')->with('error', "Error ocurred. Try again later..!");
            }
        } else {
            return redirect('admin/deals/import/csv')->with('error', 'Please upload file.');
        }
    }

    public function export($type)
    {
        $users = User::orderby('id', 'asc')->get(['id', 'name']);

        $useroptions = "<option value=''>Select User...</option>";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";
        }
        $data['useroptions'] = $useroptions;

        return view('admin.deals.export')->with('data', $data);
    }

    public function exportData(Request $request)
    {
        // echo json_encode($request->input());
        // exit();

        // $uid = Auth::user()->id;
        $uid = $request->input('selectUser');
        return Excel::download(new DealsExport($uid), 'deals.xlsx');

        // if ($res) {
        //     // echo 'Success';
        //     return redirect('admin/accounts/export/csv')->with('success', 'Downloaded successfully');
        // } else {
        //     // echo 'Failed';
        //     return redirect('admin/accounts/export/csv')->with('error', "Error ocurred. Try again later..!");
        // }
    }

    public function getFilterTime($start, $end)
    {
        $getCloseDates = $this->getCloseDates();

        $oldDate = $getCloseDates['oDateClose'];
        $latestDate = $getCloseDates['lDateClose'];

        $today = date('Y-m-d');

        $yesterday = date('Y-m-d', strtotime("-1 days"));

        $sevendays = date('Y-m-d', strtotime("-6 days"));

        $thirtydays = date('Y-m-d', strtotime("-29 days"));

        $current_month_form = date('Y-m-01', strtotime(date('Y-m-d')));

        $current_month_to =  date('Y-m-t', strtotime(date('Y-m-d')));

        $last_month_form = date('Y-m-01', strtotime('-1 MONTH'));

        $last_month_to =  date('Y-m-t', strtotime(date('Y-m-01') . ' -1 MONTH'));

        $timer = "";

        if (($start ==  $oldDate) && ($end == $latestDate)) {
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
            $start = date('d-m-Y', strtotime($start));
            $end = date('d-m-Y', strtotime($end));

            $timer = "From:" . $start . ' To:' . $end;
        }

        return $timer;
    }
}

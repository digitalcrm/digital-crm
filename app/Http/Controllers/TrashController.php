<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

//------------Models----------------------
use App\Tbl_leads;
use App\Tbl_Accounts;
use App\Tbl_contacts;
use App\Tbl_invoice;
use App\Tbl_products;
use App\Tbl_deals;
use App\Tbl_forms;
use App\Tbl_formleads;
use App\Tbl_events;
use App\Tbl_documents;
use App\User;
use App\Tbl_territory;
use App\currency;
use App\Tbl_salesfunnel;
use App\Tbl_cart_orders;

class TrashController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('auth.trash.index');
    }

    public function accounts()
    {
        $uid = Auth::user()->id;
        //        echo $uid;
        $accounts = Tbl_Accounts::with('Tbl_industrytypes')->with('Tbl_accounttypes')->where('uid', $uid)->where('active', 0)->get();
        $total = count($accounts);
        if ($total > 0) {
            $formstable = '<table id="accountsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th width="2"></th>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Email</th>';
            $formstable .= '<th>Mobile</th>';
            $formstable .= '<th>Industry</th>';
            $formstable .= '<th>Account Type</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($accounts as $formdetails) {

                $accountimage = ($formdetails->picture != '') ? $formdetails->picture : '/uploads/default/accounts.png';

                $formstable .= '<tr>';
                $formstable .= '<td>
				<div class="custom-control custom-checkbox">
				<input type="checkbox" class="checkAll custom-control-input" id="' . $formdetails->acc_id . '"><label class="custom-control-label" for="' . $formdetails->acc_id . '"></label>
				</div>
				</td>';
                $formstable .= '<td class="table-title"><img src="' . url($accountimage) . '" class="avatar">' . $formdetails->name . '</td>';
                //<a href="' . url('accounts/' . $formdetails->acc_id) . '"></a>&nbsp;
                $formstable .= '<td>' . $formdetails->email . '</td>';
                $formstable .= '<td>' . $formdetails->mobile . '</td>';
                $industrytype = ($formdetails->tbl_industrytypes != null) ? $formdetails->tbl_industrytypes->type : '';
                $formstable .= '<td>' . $industrytype . '</td>';
                $accounttype = ($formdetails->tbl_accounttypes != null) ? $formdetails->tbl_accounttypes->account_type : '';
                $formstable .= '<td>' . $accounttype . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->updated_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<a class="badge badge-success" href="' . url('trash/restore/accounts/' . $formdetails->acc_id) . '">Restore</a>';
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

        return view('auth.trash.accounts')->with("data", $data);
    }

    public function contacts()
    {
        $uid = Auth::user()->id;
        $contacts = Tbl_contacts::where('uid', $uid)
            ->with('Tbl_Accounts')
            ->with('Tbl_leadsource')
            ->with('Tbl_countries')
            ->with('Tbl_states')
            ->where('active', 0)
            ->get();

        $total = count($contacts);


        if ($total > 0) {
            $formstable = '<table id="contactsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th width="2"></th>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Email</th>';
            $formstable .= '<th>Mobile</th>';
            $formstable .= '<th>Website</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($contacts as $formdetails) {
                $contactimage = ($formdetails->picture != '') ? $formdetails->picture : '/uploads/default/contacts.png';

                $formstable .= '<tr>';
                $formstable .= '<td>
				<div class="custom-control custom-checkbox">
				<input type="checkbox" class="checkAll custom-control-input" id="' . $formdetails->cnt_id . '"><label class="custom-control-label" for="' . $formdetails->cnt_id . '"></label>
				</div>
				</td>';
                $formstable .= '<td class="table-title"><img src="' . url($contactimage) . '" class="avatar">' . $formdetails->first_name . ' ' . $formdetails->last_name . '</td>';
                //<a href="' . url('contacts/' . $formdetails->cnt_id) . '"></a>&nbsp; ' . $customer_status . '
                $formstable .= '<td>' . $formdetails->email . '</td>';
                $formstable .= '<td>' . $formdetails->mobile . '</td>';
                $formstable .= '<td>' . $formdetails->website . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->updated_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<a class="badge badge-success" href="' . url('trash/restore/contacts/' . $formdetails->cnt_id) . '">Restore</a>';
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

        return view('auth.trash.contacts')->with("data", $data);
    }

    public function leads()
    {
        $uid = Auth::user()->id;
        $cr_id = Auth::user()->cr_id;
        $currency = currency::find($cr_id);


        $leads = Tbl_leads::where('uid', $uid)->where('active', 0)->with('tbl_leadsource')->get();
        $total = 0;
        if (count($leads) > 0) {
            $formstable = '<table id="leadsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th width="2"></th>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Amount</th>';
            $formstable .= '<th>Email</th>';
            $formstable .= '<th>Mobile</th>';
            $formstable .= '<th>Lead Source</th>';
            $formstable .= '<th>Website</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($leads as $formdetails) {

                $dealstages = Tbl_deals::where('ld_id', $formdetails->ld_id)->whereIn('sfun_id', [1, 2, 3, 4])->sum('value');

                $leadsource = ($formdetails->tbl_leadsource != null) ? $formdetails->tbl_leadsource->leadsource : '';

                $leadimage = ($formdetails->picture != '') ? $formdetails->picture : '/uploads/default/leads.png';

                $formstable .= '<tr>';
                $formstable .= '<td>
				<div class="custom-control custom-checkbox">
				<input type="checkbox" class="checkAll custom-control-input" id="' . $formdetails->ld_id . '"><label class="custom-control-label" for="' . $formdetails->ld_id . '"></label>
				</div>
				</td>';
                $formstable .= '<td class="table-title"><img src="' . url($leadimage) . '" class="avatar">' . $formdetails->first_name . ' ' . $formdetails->last_name . '</td>';
                // <a href="' . url('leads/' . $formdetails->ld_id) . '"></a>&nbsp; ' . $customer_status . '
                $formstable .= '<td>' . $currency->html_code . ' ' . $dealstages . '</td>';
                $formstable .= '<td>' . $formdetails->email . '</td>';
                $formstable .= '<td>' . $formdetails->mobile . '</td>';
                $formstable .= '<td>' . $leadsource . '</td>';
                $formstable .= '<td>' . $formdetails->website . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->updated_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<a class="badge badge-success" href="' . url('trash/restore/leads/' . $formdetails->ld_id) . '">Restore</a>';
                $formstable .= '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';

            $total = count($leads);
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;

        return view('auth.trash.leads')->with("data", $data);
    }

    public function deals()
    {
        $uid = Auth::user()->id;
        $cr_id = Auth::user()->cr_id;
        $currency = currency::find($cr_id);

        $deals = Tbl_deals::where('uid', $uid)->where('active', 0)->with('tbl_leads')->with('tbl_leadsource')->with('tbl_salesfunnel')->get()->toArray();

        $total = count($deals);
        if ($total > 0) {
            $formstable = '<table id="dealsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th width="2"></th>';
            $formstable .= '<th>Deal Name</th>';
            $formstable .= '<th>Deal Stage</th>';
            $formstable .= '<th>Lead</th>';
            $formstable .= '<th>Amount<span>&nbsp;(' . $currency->html_code . ')</span></th>';
            $formstable .= '<th>Closing Date</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($deals as $formdetails) {

                $salesfunnel = ($formdetails['tbl_salesfunnel'] != '') ? $formdetails['tbl_salesfunnel']['salesfunnel'] : '';

                $formstable .= '<tr>';

                $leadimage = ($formdetails['tbl_leads']['picture'] != '') ? $formdetails['tbl_leads']['picture'] : '/uploads/default/leads.png';
                $formstable .= '<td>
				<div class="custom-control custom-checkbox">
				<input type="checkbox" class="checkAll custom-control-input" id="' . $formdetails['deal_id'] . '"><label class="custom-control-label" for="' . $formdetails['deal_id'] . '"></label>
				</div>
				</td>';
                $formstable .= '<td class="table-title"><a href="' . url('deals/' . $formdetails['deal_id']) . '">' . $formdetails['name'] . '</a></td>';
                $formstable .= '<td>' . $salesfunnel  . '</td>';
                $formstable .= '<td><img src="' . url($leadimage) . '" class="avatar">' . $formdetails['tbl_leads']['first_name'] . ' ' . $formdetails['tbl_leads']['last_name'] . '</td>';
                $formstable .= '<td>' . $formdetails['value'] . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails['closing_date'])) . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails['updated_at'])) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<a class="badge  badge-success" href="' . url('trash/restore/deals/' . $formdetails['deal_id']) . '">Restore</a>';
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

        return view('auth.trash.deals')->with("data", $data);
    }

    //--------------------Forms---------------------------------
    public function forms()
    {
        $uid = Auth::user()->id;

        $forms = Tbl_forms::where('uid', $uid)->where('active', 0)->orderBy('form_id', 'desc')->get();
        $total = count($forms);
        if ($total > 0) {
            $formstable = '<table id="formsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th width="2"></th>';
            $formstable .= '<th>Title</th>';
            $formstable .= '<th>Website</th>';
            $formstable .= '<th>Views</th>';
            $formstable .= '<th>Contacts</th>';
            $formstable .= '<th>Created</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($forms as $formdetails) {

                $formLeads = Tbl_formleads::where('form_id', $formdetails->form_id)->get();

                $formstable .= '<tr>';
                $formstable .= '<td><div class="custom-control custom-checkbox">
				<input type="checkbox" class="checkAll custom-control-input" id="' . $formdetails['deal_id'] . '"><label class="custom-control-label" for="' . $formdetails['deal_id'] . '"></label>
				</div></td>';
                $formstable .= '<td class="table-title">' . $formdetails->title . '</td>';
                $formstable .= '<td>' . $formdetails->post_url . '</td>';
                $formstable .= '<td>' . $formdetails->views . '</td>';
                $formstable .= '<td>' . count($formLeads) . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->updated_at)) . '</td>';
                $formstable .= '<td><a class="badge  badge-success" href="' . url('trash/restore/forms/' . $formdetails->form_id) . '">Restore</a></td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;

        return view('auth.trash.forms')->with("data", $data);
    }

    //--------------------Form Leads---------------------------------
    public function formleads()
    {
        $uid = Auth::user()->id;

        $formleads = Tbl_formleads::where('uid', $uid)->where('active', 0)->with('tbl_forms')->get();
        $total = count($formleads);
        if ($total > 0) {
            $formstable = '<table id="formleadsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th width="2"></th>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Form</th>';
            $formstable .= '<th>Email</th>';
            $formstable .= '<th>Mobile</th>';
            $formstable .= '<th>Website</th>';
            $formstable .= '<th>Created</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($formleads as $formdetails) {

                $formstable .= '<tr>';
                $formstable .= '<td><div class="custom-control custom-checkbox">
				<input type="checkbox" class="checkAll custom-control-input" id="' . $formdetails['deal_id'] . '"><label class="custom-control-label" for="' . $formdetails['deal_id'] . '"></label>
				</div></td>';
                $formstable .= '<td class="table-title">' . $formdetails->first_name . '</td>';
                $formstable .= '<td>' . $formdetails->tbl_forms->title . '</td>';
                $formstable .= '<td>' . $formdetails->email . '</td>';
                $formstable .= '<td>' . $formdetails->mobile . '</td>';
                $formstable .= '<td>' . $formdetails->website . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->updated_at)) . '</td>';
                $formstable .= '<td><a class="badge  badge-success" href="' . url('trash/restore/formleads/' . $formdetails->fl_id . '/' . $formdetails->form_id) . '">Restore</a></td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;

        return view('auth.trash.formleads')->with("data", $data);
    }

    // Product Leads

    public function getProductLeads()
    {
        $uid = Auth::user()->id;

        // $query = DB::table('tbl_cart_orders');
        // $query->where('tbl_cart_orders.active', 0);
        // $query->leftJoin('tbl_products', 'tbl_cart_orders.pro_id', '=', 'tbl_products.pro_id');
        // $query->leftJoin('tbl_post_order_stage', 'tbl_cart_orders.pos_id', '=', 'tbl_post_order_stage.pos_id');
        // $query->leftJoin('tbl_countries', 'tbl_cart_orders.country', '=', 'tbl_countries.id');
        // $query->leftJoin('tbl_states', 'tbl_cart_orders.state', '=', 'tbl_states.id');
        // $query->where('tbl_products.uid', $uid);
        // $query->where('tbl_products.user_type', 2);
        // $query->select(
        //     'tbl_cart_orders.*',
        //     'tbl_products.procat_id',
        //     'tbl_products.pro_id',
        //     'tbl_products.uid as userid',
        //     'tbl_products.user_type',
        //     'tbl_products.name as pname',
        //     'tbl_products.vendor',
        //     'tbl_products.size',
        //     'tbl_products.price',
        //     'tbl_post_order_stage.stage as stage',
        //     'tbl_countries.name as countryname',
        //     'tbl_states.name as statename',
        // );
        // $orders = $query->get();

        // // echo json_encode($orders);
        // // exit();

        // $total = count($orders);

        // if ($total > 0) {
        //     $formstable = '<table id="example1" class="table">';
        //     $formstable .= '<thead>';
        //     $formstable .= '<tr>';
        //     $formstable .= '<th width="2"></th>';
        //     $formstable .= '<th>Lead</th>';
        //     $formstable .= '<th>Order Number</th>';
        //     $formstable .= '<th>Product</th>';
        //     $formstable .= '<th>Brand</th>';
        //     $formstable .= '<th>Quantity</th>';
        //     $formstable .= '<th>Total Amount</th>';
        //     $formstable .= '<th>Post Order Stage</th>';
        //     $formstable .= '<th>Shipping Date</th>';
        //     $formstable .= '<th>Date</th>';
        //     $formstable .= '<th>Action</th>';
        //     $formstable .= '</tr>';
        //     $formstable .= '</thead>';
        //     $formstable .= '<tbody>';
        //     foreach ($orders as $formdetails) {


        //         $userid = $formdetails->userid;
        //         $user = User::with('currency')->find($userid);

        //         // echo json_encode($user);
        //         // exit();


        //         $formstable .= '<tr>';
        //         $formstable .= '<td><input type="checkbox" class="checkAll" id="' . $formdetails->coid . '"></td>';
        //         //  ' . url('leads/order/' . $formdetails->coid) . '
        //         $formstable .= '<td><a href="#">' . $formdetails->name . '</a></td>';
        //         $formstable .= '<td>' . $formdetails->number . '</td>';
        //         $formstable .= '<td>' . $formdetails->pname . '</td>';
        //         $formstable .= '<td>' . $formdetails->vendor . '</td>';
        //         $formstable .= '<td>' . $formdetails->quantity . '</td>';
        //         $formstable .= '<td><span>' . $user->currency->html_code . '</span>&nbsp;' . $formdetails->total_amount . '</td>';
        //         $formstable .= '<td>' . $formdetails->stage . '</td>';
        //         $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->shipping_date)) . '</td>';
        //         $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
        //         $formstable .= '<td>';
        //         $formstable .= '<a class="badge  badge-success"  href="' . url('trash/restore/prolead/' . $formdetails->coid) . '">Restore</a>';
        //         $formstable .= '</td>';
        //         $formstable .= '</tr>';
        //     }
        //     $formstable .= '</tbody>';
        //     $formstable .= '</table>';
        // } else {
        //     $formstable = 'No records available';
        // }
        // $data['table'] = $formstable;
        // $data['total'] = $total;
        //-----------------------------------------
        $query = DB::table('tbl_leads')->where('tbl_leads.active', 0)->where('tbl_leads.leadtype', 2);
        if (($uid > 0) && ($uid != "All")) {
            $query->where('tbl_leads.uid', $uid);
        }
        $query->leftJoin('tbl_products', 'tbl_leads.pro_id', '=', 'tbl_products.pro_id');
        $query->orderBy('tbl_leads.ld_id', 'desc');
        $query->select(
            'tbl_leads.*',
            'tbl_products.name as product'
        );

        $leads = $query->get();

        // echo json_encode($leads);
        // exit();

        $total = count($leads);
        if ($total > 0) {
            $formstable = '<div class="table-responsive"><table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            // $formstable .= '<th></th>';
            $formstable .= '<th width="230">Lead Name</th>';
            $formstable .= '<th>Email</th>';
            $formstable .= '<th>Mobile</th>';
            $formstable .= '<th>Product</th>';
            $formstable .= '<th>Date</th>';
            // $formstable .= '<th>Add Deal</th>';
            // $formstable .= '<th>Add as Customer</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '<th>Notes</th>';    // class="none"
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($leads as $formdetails) {
                $customer_status = '';
                $customer = Tbl_deals::where('ld_id', $formdetails->ld_id)->where('deal_status', 1)->first();
                if ($customer != null) {
                    $customer_status = '<span class="badge badge-success"><i class="fa fa-check" aria-hidden="true"></i> Customer</span>';
                }

                $dealstages = Tbl_deals::where('ld_id', $formdetails->ld_id)->where('deal_status', 0)->where('active', 1)->sum('value'); //->whereIn('sfun_id', [1, 2, 3, 4])


                $leadimage = ($formdetails->picture != '') ? $formdetails->picture : '/uploads/default/leads.png';

                $formstable .= '<tr>';
                $formstable .= '<td class="table-title">';
                $formstable .= '<img src="' . url($leadimage) . '" class="avatar"> ';
                $formstable .= '<h6>' . substr($formdetails->first_name . ' ' . $formdetails->last_name, 0, 25) . '<h6>';
                $formstable .= $customer_status;
                $formstable .= '</td>';
                $formstable .= '<td>' . $formdetails->email . '</td>';
                $formstable .= '<td>' . $formdetails->mobile . '</td>';
                $formstable .= '<td>' . $formdetails->product . '</td>';    //(($formdetails->tbl_products != '') ? $formdetails->tbl_products->name : '')
                $formstable .= '<td>' . date('m-d-Y', strtotime($formdetails->created_at)) . '</td>';
                // $formstable .= '<td><a class="badge badge-success" href="' . url('admin/leads/adddeal/' . $formdetails->ld_id) . '">Add Deal</a></td>';
                // $formstable .= '<td><a class="badge badge-success" href="' . url('admin/leads/addcustomer/' . $formdetails->ld_id) . '">Add as Customer</a></td>';
                $formstable .= '<td>';
                $formstable .= '<a class="badge  badge-success"  href="' . url('trash/restore/prolead/' . $formdetails->ld_id) . '">Restore</a>';
                // $formstable .= '
                //     <div class="btn-group">
                //   <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                //     <span class="caret"></span>
                //   </button>
                //   <ul class="dropdown-menu" role="menu">
                //     <a class="dropdown-item text-default text-btn-space" href="' . url('admin/leads/product/edit/' . $formdetails->ld_id) . '">Edit</a>
                //     <a class="dropdown-item text-default text-btn-space" href="' . url('admin/leads/product/delete/' . $formdetails->ld_id) . '">Delete</a>
                //   </ul>
                // </div>';
                $formstable .= '</td>';
                $formstable .= '<td>' . $formdetails->notes . '</td>';
                $formstable .= '</tr>';
                // <a class="dropdown-item text-default text-btn-space" href="' . url('leads/assign/' . $formdetails->ld_id) . '">Assign to Subuser</a>
            }
            $formstable .= '</tbody>';
            $formstable .= '</table></div>';
        } else {
            $formstable = 'No records available';
        }


        $data['total'] = $total;
        $data['table'] = $formstable;

        return view('auth.trash.proleads')->with("data", $data);
    }

    //--------------------Territory---------------------------------
    public function territory()
    {
        $uid = Auth::user()->id;

        $cr_id = Auth::user()->cr_id;
        $currency = currency::find($cr_id);

        $terrtories = Tbl_territory::where('uid', $uid)->where('active', 0)->with('tbl_territory_users')->get();

        $total = count($terrtories);
        $data['total'] = $total;

        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th width="2"></th>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Users</th>';
            $formstable .= '<th>Description</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($terrtories as $formdetails) {

                $subusers = User::whereIn('id', explode(',', $formdetails->subusers))->get(['name']);
                $subusernames = '';
                foreach ($subusers as $subuser) {
                    $subusernames .= $subuser->name . ', ';
                }

                $deals = Tbl_deals::whereIn('uid', explode(',', $formdetails->subusers))->whereIn('sfun_id', [1, 2, 3, 4])->sum('value');

                $formstable .= '<tr>';
                $formstable .= '<td><div class="custom-control custom-checkbox">
				<input type="checkbox" class="checkAll custom-control-input" id="' . $formdetails['deal_id'] . '"><label class="custom-control-label" for="' . $formdetails['deal_id'] . '"></label>
				</div></td>';
                $formstable .= '<td class="table-title">' . $formdetails->name . '</td>';
                $formstable .= '<td>' . substr($subusernames, 0, 25) . '</td>';
                $formstable .= '<td>' . substr($formdetails->description, 0, 15) . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->updated_at)) . '</td>';
                $formstable .= '<td><a class="badge  badge-success" href="' . url('trash/restore/territory/' . $formdetails->tid) . '">Restore</a></td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }
        $data['table'] = $formstable;

        return view('auth.trash.territory')->with("data", $data);
    }

    //--------------------Products---------------------------------

    public function products()
    {
        $uid = Auth::user()->id;



        $cr_id = Auth::user()->cr_id;
        $currency = currency::find($cr_id);


        $products = Tbl_products::with('Tbl_units')
            ->where('uid', $uid)
            ->where('active', 0)
            ->orderBy('pro_id', 'desc')
            ->get();
        $total = count($products);
        if ($total > 0) {
            $formstable = '<table id="productsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th width="2"></th>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Size</th>';
            $formstable .= '<th>Price<span>&nbsp;(' . $currency->html_code . ')</span></th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($products as $formdetails) {
                $img = ($formdetails->picture) ? $formdetails->picture : '/uploads/default/products.jpg';
                $formstable .= '<tr>';
                $formstable .= '<td><div class="custom-control custom-checkbox">
				<input type="checkbox" class="checkAll custom-control-input" id="' . $formdetails['deal_id'] . '"><label class="custom-control-label" for="' . $formdetails['deal_id'] . '"></label>
				</div></td>';
                $formstable .= '<td class="table-title">';
                $formstable .= '<img src="' . url($img) . '" class="avatar">';
                $formstable .= '<a href="' . url('products/' . $formdetails->pro_id) . '">' . $formdetails->name . '</a>&nbsp;';
                $formstable .= '</td>';
                $formstable .= '<td>' . $formdetails->size . '</td>';
                $formstable .= '<td>' . $formdetails->price . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<a class="badge  badge-success" href="' . url('trash/restore/products/' . $formdetails->pro_id) . '">Restore</a></li>';
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

        return view('auth.trash.products')->with("data", $data);
    }

    //--------------------Documents---------------------------------
    public function documents()
    {
        $uid = Auth::user()->id;

        $documents = Tbl_documents::where('uid', $uid)->where('active', 0)->get()->toArray();
        $total = count($documents);
        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th width="2"></th>';
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
                $formstable .= '<td><div class="custom-control custom-checkbox">
				<input type="checkbox" class="checkAll custom-control-input" id="' . $formdetails['doc_id'] . '"><label class="custom-control-label" for="' . $formdetails['doc_id'] . '"></label>
				</div></td>';
                $formstable .= '<td class="table-title"><a href="' . url('documents/' . $formdetails['doc_id']) . '">' . $faicon . '&nbsp; ' . $formdetails['name'] . '</a></td>';
                $formstable .= '<td>' . $formdetails['type'] . '</td>';
                $formstable .= '<td>' . $formdetails['size'] . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails['created_at'])) . '</td>';
                $formstable .= '<td><a class="badge  badge-success" href="' . url('trash/restore/documents/' . $formdetails['doc_id']) . '">Restore</a></td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;

        return view('auth.trash.documents')->with('data', $data);
    }

    //--------------------Invoices---------------------------------
    public function invoices()
    {
        $uid = Auth::user()->id;

        $user = User::find($uid);
        $currency = currency::find($user->cr_id);

        $invoices = Tbl_invoice::where('uid', $uid)->where('active', 0)->get();
        $total = count($invoices);
        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th width="2"></th>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Bill To</th>';
            $formstable .= '<th>Amount  (' . $currency->html_code . ')</th>';
            $formstable .= '<th>Status</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($invoices as $formdetails) {

                $formstable .= '<tr>';
                $formstable .= '<td><div class="custom-control custom-checkbox">
				<input type="checkbox" class="checkAll custom-control-input" id="' . $formdetails['deal_id'] . '"><label class="custom-control-label" for="' . $formdetails['deal_id'] . '"></label>
				</div></td>';
                $formstable .= '<td class="table-title">' . $formdetails->name . '</td>';
                $formstable .= '<td>' . $formdetails->billto . '</td>';
                $formstable .= '<td>' . $formdetails->total_amount . '</td>';
                $status = ($formdetails->status == 1) ? 'Send' : 'Draft';
                $formstable .= '<td>' . $status . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->updated_at)) . '</td>';
                $formstable .= '<td><a class="badge  badge-success" href="' . url('trash/restore/invoice/' . $formdetails->inv_id) . '">Restore</a></td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;

        return view('auth.trash.invoices')->with("data", $data);
    }

    //--------------------Events---------------------------------
    public function events()
    {
        $uid = Auth::user()->id;
        $events = Tbl_events::where('uid', $uid)->where('active', 0)->get(['ev_id', 'title', 'startDatetime as start', 'endDatetime as end', 'description']);
        $total = count($events);
        if ($total > 0) {
            $formstable = '<table id="productsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th width="2"></th>';
            $formstable .= '<th>Title</th>';
            $formstable .= '<th>Start Time</th>';
            $formstable .= '<th>End Time</th>';
            $formstable .= '<th>Description</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($events as $formdetails) {
                $formstable .= '<tr>';
                $formstable .= '<td><div class="custom-control custom-checkbox">
				<input type="checkbox" class="checkAll custom-control-input" id="' . $formdetails['deal_id'] . '"><label class="custom-control-label" for="' . $formdetails['deal_id'] . '"></label>
				</div></td>';
                $formstable .= '<td class="table-title">' . $formdetails->description . '</td>';
                $formstable .= '</td>';
                $formstable .= '<td>' . date('d-m-Y h:i:s', strtotime($formdetails->start)) . '</td>';
                $formstable .= '<td>' . date('d-m-Y h:i:s', strtotime($formdetails->end)) . '</td>';
                $formstable .= '<td>' . substr($formdetails->description, 0, 20) . '</td>';
                $formstable .= '<td><a class="badge  badge-success" href="' . url('trash/restore/events/' . $formdetails->ev_id) . '">Restore</a></td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;

        return view('auth.trash.events')->with("data", $data);
    }

    //--------------------Restore Accounts---------------------------------
    public function restoreAccounts($id)
    {
        $lead = Tbl_Accounts::find($id);
        $lead->active = 1;
        $lead->save();
        return redirect('/accounts')->with('success', 'Account Restored Successfully..!');
    }

    //--------------------Restore Leads---------------------------------
    public function restoreLeads($id)
    {
        $lead = Tbl_leads::find($id);
        $lead->active = 1;
        $lead->save();

        //----------Deals-------------------
        Tbl_deals::where('ld_id', '=', $id)->update(['active' => 1]);

        //----------Invoice-------------------
        Tbl_invoice::where('ld_id', '=', $id)->update(['active' => 1]);

        return redirect('/leads')->with('success', 'Lead Restored Successfully..!');
    }

    //--------------------Restore Contacts---------------------------------
    public function restoreContacts($id)
    {
        $lead = Tbl_contacts::find($id);
        $lead->active = 1;
        $lead->save();
        return redirect('/contacts')->with('success', 'Contact Restored Successfully..!');
    }

    //--------------------Restore Deals---------------------------------
    public function restoreDeals($id)
    {
        $lead = Tbl_deals::find($id);
        $lead->active = 1;
        $lead->save();
        return redirect('/deals')->with('success', 'Deal Restored Successfully..!');
    }

    //--------------------Restore Forms---------------------------------
    public function restoreForms($id)
    {
        $lead = Tbl_forms::find($id);
        $lead->active = 1;
        $lead->save();

        //----------Invoice-------------------
        Tbl_formleads::where('form_id', '=', $id)->update(['active' => 1]);

        return redirect('/webtolead')->with('success', 'Form Restored Successfully..!');
    }

    //--------------------Restore Forms Leads---------------------------------
    public function restoreFormleads($id, $form_id)
    {
        $lead = Tbl_formleads::find($id);
        $lead->active = 1;
        $lead->save();
        return redirect('/webtolead/formleads/' . $form_id)->with('success', 'Lead Restored Successfully..!');
    }


    //--------------------Restore Product Leads---------------------------------
    public function restoreProductleads($id)
    {
        $lead = Tbl_leads::find($id);
        $lead->active = 1;
        $lead->save();
        return redirect('leads/getproductleads/list')->with('success', 'Lead Restored Successfully..!');
    }

    //--------------------Restore Invoices---------------------------------
    public function restoreInvoice($id)
    {
        $lead = Tbl_invoice::find($id);
        $lead->active = 1;
        $lead->save();
        return redirect('/invoice')->with('success', 'Invoice Restored Successfully..!');
    }

    //--------------------Restore Products---------------------------------
    public function restoreProducts($id)
    {
        $lead = Tbl_products::find($id);
        $lead->active = 1;
        $lead->save();
        return redirect('/products')->with('success', 'Product Restored Successfully..!');
    }

    //--------------------Restore Documents---------------------------------
    public function restoreDocuments($id)
    {
        $lead = Tbl_documents::find($id);
        $lead->active = 1;
        $lead->save();
        return redirect('/documents')->with('success', 'Document Restored Successfully..!');
    }

    //--------------------Restore Events---------------------------------
    public function restoreEvents($id)
    {
        $event = Tbl_events::find($id);
        $event->active = 1;
        $event->save();
        return redirect('/calendar')->with('success', 'Restored Successfully..!');
    }

    //--------------------Restore Events---------------------------------
    public function restoreTerritory($id)
    {
        $territory = Tbl_territory::find($id);
        $territory->active = 1;
        $territory->save();
        return redirect('/territory')->with('success', 'Restored Successfully..!');
    }
}

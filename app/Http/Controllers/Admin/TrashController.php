<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

//------------Models----------------------
use App\Tbl_forms;
use App\Tbl_formleads;
use App\Tbl_leads;
use App\Tbl_Accounts;
use App\Tbl_contacts;
use App\Tbl_invoice;
use App\Tbl_products;
use App\Tbl_deals;
use App\Tbl_events;
use App\Tbl_documents;
use App\User;
use App\Admin;
use App\Tbl_territory;
use App\currency;
use App\Tbl_salesfunnel;
use App\Tbl_accounttypes;
use App\Tbl_industrytypes;
use App\Tbl_leadsource;
use App\Tbl_leadstatus;
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
        $this->middleware('auth:admin');
    }

    public function index()
    {
        return view('admin.trash.index');
    }

    public function getUseroptions()
    {
        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        $uid = "All";
        $useroptions = "<option value='All'>All</option>";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";
        }
        $data['useroptions'] = $useroptions;
        $data['user'] = $uid;
        return $data;
    }

    //--------------------------Accounts----------------------------------
    public function accounts()
    {
        $data = $this->getUseroptions();
        $accounts = $this->getAccounts($data['user']);
        $data['table'] = $accounts['table'];
        $data['total'] = $accounts['total'];
        return view('admin.trash.accounts')->with("data", $data);
    }

    public function getUserAccounts(Request $request)
    {
        $uid = $request->input('uid');
        $data = $this->getAccounts($uid);
        return json_encode($data);
    }

    public function getAccounts($uid)
    {
        if ($uid == 'All') {
            $accounts = Tbl_Accounts::with('Tbl_industrytypes')->with('Tbl_accounttypes')->where('active', 0)->get();
        } else {
            $accounts = Tbl_Accounts::with('Tbl_industrytypes')->with('Tbl_accounttypes')->where('uid', $uid)->where('active', 0)->get();
        }

        $total = count($accounts);
        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
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
                $formstable .= '<td class="table-title"><img class="avatar" src="' . url($accountimage) . '">' . $formdetails->name . '</td>';
                $formstable .= '<td>' . $formdetails->email . '</td>';
                $formstable .= '<td>' . $formdetails->mobile . '</td>';
                $industrytype = ($formdetails->tbl_industrytypes != null) ? $formdetails->tbl_industrytypes->type : '';
                $formstable .= '<td>' . $industrytype . '</td>';
                $accounttype = ($formdetails->tbl_accounttypes != null) ? $formdetails->tbl_accounttypes->account_type : '';
                $formstable .= '<td>' . $accounttype . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->updated_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<a class="btn badge badge-info py-1 px-2" href="' . url('admin/trash/restore/accounts/' . $formdetails->acc_id) . '">Restore</a>';
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

    public function restoreAccounts($id)
    {
        $lead = Tbl_Accounts::find($id);
        $lead->active = 1;
        $lead->save();
        return redirect('admin/accounts')->with('success', 'Restored Successfully..!');
    }

    //-------------------------Leads------------------------------
    public function leads()
    {
        $data = $this->getUseroptions();
        $accounts = $this->getLeads($data['user']);
        $data['table'] = $accounts['table'];
        $data['total'] = $accounts['total'];
        return view('admin.trash.leads')->with("data", $data);
    }

    public function getUserLeads(Request $request)
    {
        $uid = $request->input('uid');
        $data = $this->getLeads($uid);
        return json_encode($data);
    }

    public function getLeads($uid)
    {
        if ($uid == 'All') {
            $leads = Tbl_leads::where('active', 0)->with('tbl_leadsource')->get();
        } else {
            $leads = Tbl_leads::where('uid', $uid)->where('active', 0)->with('tbl_leadsource')->get();
        }

        $total = 0;
        if (count($leads) > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
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
                $user = User::find($formdetails->uid);
                $cr_id = $user->cr_id;
                $currency = currency::find($cr_id);

                $dealstages = Tbl_deals::where('ld_id', $formdetails->ld_id)->whereIn('sfun_id', [1, 2, 3, 4])->where('active', 0)->sum('value');

                $leadsource = ($formdetails->tbl_leadsource != null) ? $formdetails->tbl_leadsource->leadsource : '';

                $leadimage = ($formdetails->picture != '') ? $formdetails->picture : '/uploads/default/leads.png';

                $formstable .= '<tr>';
                $formstable .= '<td class="table-title"><img class="avatar" src="' . url($leadimage) . '">' . $formdetails->first_name . ' ' . $formdetails->last_name . '</td>';
                $formstable .= '<td>' . $currency->html_code . ' ' . $dealstages . '</td>';
                $formstable .= '<td>' . $formdetails->email . '</td>';
                $formstable .= '<td>' . $formdetails->mobile . '</td>';
                $formstable .= '<td>' . $leadsource . '</td>';
                $formstable .= '<td>' . $formdetails->website . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->updated_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<a class="btn badge badge-info py-1 px-2 text-btn-space" href="' . url('admin/trash/restore/leads/' . $formdetails->ld_id) . '">Restore</a>';
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

        return $data;
    }

    public function restoreLeads($id)
    {
        $lead = Tbl_leads::find($id);
        $lead->active = 1;
        $lead->save();

        //----------Deals-------------------
        Tbl_deals::where('ld_id', '=', $id)->update(['active' => 1]);

        //----------Invoice-------------------
        Tbl_invoice::where('ld_id', '=', $id)->update(['active' => 1]);

        return redirect('admin/leads')->with('success', 'Restored Successfully..!');
    }

    //-------------------------Contacts------------------------------
    public function contacts()
    {
        $data = $this->getUseroptions();
        $accounts = $this->getContacts($data['user']);
        $data['table'] = $accounts['table'];
        $data['total'] = $accounts['total'];
        return view('admin.trash.contacts')->with("data", $data);
    }

    public function getUserContacts(Request $request)
    {
        $uid = $request->input('uid');
        $data = $this->getContacts($uid);
        return json_encode($data);
    }

    public function getContacts($uid)
    {

        if ($uid == 'All') {
            $contacts = Tbl_contacts::with('Tbl_Accounts')
                ->with('Tbl_leadsource')
                ->with('Tbl_countries')
                ->with('Tbl_states')
                ->where('active', 0)
                ->get();
        } else {
            $contacts = Tbl_contacts::where('uid', $uid)
                ->with('Tbl_Accounts')
                ->with('Tbl_leadsource')
                ->with('Tbl_countries')
                ->with('Tbl_states')
                ->where('active', 0)
                ->get();
        }

        $total = count($contacts);

        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
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
                $formstable .= '<td class="table-title"><img class="avatar" src="' . url($contactimage) . '">' . $formdetails->first_name . ' ' . $formdetails->last_name . '</td>';
                $formstable .= '<td>' . $formdetails->email . '</td>';
                $formstable .= '<td>' . $formdetails->mobile . '</td>';
                $formstable .= '<td>' . $formdetails->website . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->updated_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<a class="btn badge badge-info py-1 px-2 text-btn-space" href="' . url('admin/trash/restore/contacts/' . $formdetails->cnt_id) . '">Restore</a>';
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

    public function restoreContacts($id)
    {
        $lead = Tbl_contacts::find($id);
        $lead->active = 1;
        $lead->save();
        return redirect('admin/contacts')->with('success', 'Restored Successfully..!');
    }

    //-------------------------Deals------------------------------
    public function deals()
    {
        $data = $this->getUseroptions();
        $accounts = $this->getDeals($data['user']);
        $data['table'] = $accounts['table'];
        $data['total'] = $accounts['total'];
        return view('admin.trash.deals')->with("data", $data);
    }

    public function getUserDeals(Request $request)
    {
        $uid = $request->input('uid');
        $data = $this->getDeals($uid);
        return json_encode($data);
    }

    public function getDeals($uid)
    {
        if ($uid == 'All') {
            $deals = Tbl_deals::where('active', 0)->with('tbl_leads')->with('tbl_leadsource')->with('tbl_salesfunnel')->get()->toArray();
        } else {
            $deals = Tbl_deals::where('uid', $uid)->where('active', 0)->with('tbl_leads')->with('tbl_leadsource')->with('tbl_salesfunnel')->get()->toArray();
        }

        $total = count($deals);
        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Deal Name</th>';
            $formstable .= '<th>Deal Stage</th>';
            $formstable .= '<th>Lead</th>';
            $formstable .= '<th>Amount</th>';
            $formstable .= '<th>Closing Date</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($deals as $formdetails) {

                $user = User::find($formdetails['uid']);
                $cr_id = $user->cr_id;
                $currency = currency::find($cr_id);

                $formstable .= '<tr>';

                $leadimage = ($formdetails['tbl_leads']['picture'] != '') ? $formdetails['tbl_leads']['picture'] : '/uploads/default/leads.png';

                $formstable .= '<td class="table-title"><a href="' . url('deals/' . $formdetails['deal_id']) . '">' . $formdetails['name'] . '</a></td>';
                $formstable .= '<td>' . $formdetails['tbl_salesfunnel']['salesfunnel'] . '</td>';
                $formstable .= '<td><img class="avatar" src="' . url($leadimage) . '">' . $formdetails['tbl_leads']['first_name'] . ' ' . $formdetails['tbl_leads']['last_name'] . '</td>';
                $formstable .= '<td>' . $currency->html_code . ' ' . $formdetails['value'] . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails['closing_date'])) . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails['updated_at'])) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<a class="btn badge badge-info py-1 px-2 text-btn-space" href="' . url('admin/trash/restore/deals/' . $formdetails['deal_id']) . '">Restore</a>';
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

    public function restoreDeals($id)
    {
        $lead = Tbl_deals::find($id);
        $lead->active = 1;
        $lead->save();
        return redirect('admin/deals')->with('success', 'Restored Successfully..!');
    }

    //-------------------------Web to lead Forms------------------------------  
    public function forms()
    {
        $data = $this->getUseroptions();
        $accounts = $this->getForms($data['user']);
        $data['table'] = $accounts['table'];
        $data['total'] = $accounts['total'];
        return view('admin.trash.forms')->with("data", $data);
    }

    public function getUserFroms(Request $request)
    {
        $uid = $request->input('uid');
        $data = $this->getForms($uid);
        return json_encode($data);
    }

    public function getForms($uid)
    {
        if ($uid == 'All') {
            $forms = Tbl_forms::where('active', 0)->orderBy('form_id', 'desc')->get();
        } else {
            $forms = Tbl_forms::where('uid', $uid)->where('active', 0)->orderBy('form_id', 'desc')->get();
        }

        $total = count($forms);
        if ($total > 0) {
            $formstable = '<table id="formsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
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
                $formstable .= '<td class="table-title">' . $formdetails->title . '</td>';
                $formstable .= '<td>' . $formdetails->post_url . '</td>';
                $formstable .= '<td>' . $formdetails->views . '</td>';
                $formstable .= '<td>' . count($formLeads) . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->updated_at)) . '</td>';
                $formstable .= '<td><a class="btn badge badge-info py-1 px-2" href="' . url('admin/trash/restore/forms/' . $formdetails->form_id) . '">Restore</a></td>';
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

    public function restoreForms($id)
    {
        $lead = Tbl_forms::find($id);
        $lead->active = 1;
        $lead->save();

        //----------Invoice-------------------
        Tbl_formleads::where('form_id', '=', $id)->update(['active' => 1]);

        return redirect('admin/webtolead')->with('success', 'Form Restored Successfully..!');
    }

    //-------------------------Web to lead Forms------------------------------  
    public function formleads()
    {
        $data = $this->getUseroptions();
        $accounts = $this->getFormleads($data['user']);
        $data['table'] = $accounts['table'];
        $data['total'] = $accounts['total'];
        return view('admin.trash.formleads')->with("data", $data);
    }

    public function getUserFromleads(Request $request)
    {
        $uid = $request->input('uid');
        $data = $this->getFormleads($uid);
        return json_encode($data);
    }

    public function getFormleads($uid)
    {
        if ($uid == 'All') {
            $formleads = Tbl_formleads::where('active', 0)->with('tbl_forms')->get();
        } else {
            $formleads = Tbl_formleads::where('uid', $uid)->where('active', 0)->with('tbl_forms')->get();
        }

        $total = count($formleads);
        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
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
                $formstable .= '<td class="table-title">' . $formdetails->first_name . '</td>';
                $formstable .= '<td>' . $formdetails->tbl_forms->title . '</td>';
                $formstable .= '<td>' . $formdetails->email . '</td>';
                $formstable .= '<td>' . $formdetails->mobile . '</td>';
                $formstable .= '<td>' . $formdetails->website . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->updated_at)) . '</td>';
                $formstable .= '<td><a class="btn badge badge-info py-1 px-2" href="' . url('admin/trash/restore/formleads/' . $formdetails->fl_id . '/' . $formdetails->form_id) . '">Restore</a></td>';
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

    public function restoreFormleads($id, $form_id)
    {
        $lead = Tbl_formleads::find($id);
        $lead->active = 1;
        $lead->save();
        return redirect('admin/webtolead/formleads/' . $form_id)->with('success', 'Restored Successfully..!');
    }

    //-------------------------Products------------------------------  
    public function products()
    {
        $data = $this->getUseroptions();
        $accounts = $this->getProducts($data['user']);
        $data['table'] = $accounts['table'];
        $data['total'] = $accounts['total'];
        return view('admin.trash.products')->with("data", $data);
    }

    public function getUserProducts(Request $request)
    {
        $uid = $request->input('uid');
        $data = $this->getProducts($uid);
        return json_encode($data);
    }

    public function getProducts($uid)
    {
        if ($uid == 'All') {
            $products = Tbl_products::with('Tbl_units')
                ->where('active', 0)
                ->orderBy('pro_id', 'desc')
                ->get();
        } else {
            $products = Tbl_products::with('Tbl_units')
                ->where('uid', $uid)
                ->where('active', 0)
                ->orderBy('pro_id', 'desc')
                ->get();
        }

        $total = count($products);
        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Size</th>';
            $formstable .= '<th>Price</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($products as $formdetails) {
                $img = ($formdetails->picture) ? $formdetails->picture : '/uploads/default/products.jpg';

                $cr_id = $formdetails->uid;
                $currency = currency::find($cr_id);

                $formstable .= '<tr>';
                $formstable .= '<td class="table-title">';
                $formstable .= '<img src="' . url($img) . '" width="30" height="30">' . $formdetails->name . '</td>';
                $formstable .= '<td>' . $formdetails->size . '</td>';
                $formstable .= '<td>' . $currency->html_code . ' ' . $formdetails->price . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<a class="btn badge badge-info py-1 px-2" href="' . url('admin/trash/restore/products/' . $formdetails->pro_id) . '">Restore</a></li>';
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

    public function restoreProducts($id)
    {
        $lead = Tbl_products::find($id);
        $lead->active = 1;
        $lead->save();
        return redirect('admin/products')->with('success', 'Restored Successfully..!');
    }

    //-------------------------Documents------------------------------  
    public function documents()
    {
        $data = $this->getUseroptions();
        $accounts = $this->getDocuments($data['user']);
        $data['table'] = $accounts['table'];
        $data['total'] = $accounts['total'];
        return view('admin.trash.documents')->with("data", $data);
    }

    public function getUserDocuments(Request $request)
    {
        $uid = $request->input('uid');
        $data = $this->getDocuments($uid);
        return json_encode($data);
    }

    public function getDocuments($uid)
    {
        if ($uid == 'All') {
            $documents = Tbl_documents::where('active', 0)->get()->toArray();
        } else {
            $documents = Tbl_documents::where('uid', $uid)->where('active', 0)->get()->toArray();
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
                $formstable .= '<td class="table-title"><a href="' . url('admin/documents/' . $formdetails['doc_id']) . '">' . $faicon . '&nbsp; ' . $formdetails['name'] . '</a></td>';
                $formstable .= '<td>' . $formdetails['type'] . '</td>';
                $formstable .= '<td>' . $formdetails['size'] . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails['created_at'])) . '</td>';
                $formstable .= '<td><a class="btn badge badge-info py-1 px-2 text-btn-space" href="' . url('admin/trash/restore/documents/' . $formdetails['doc_id']) . '">Restore</a></td>';
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

    public function restoreDocuments($id)
    {
        $lead = Tbl_documents::find($id);
        $lead->active = 1;
        $lead->save();
        return redirect('admin/documents')->with('success', 'Restored Successfully..!');
    }

    //-------------------------Invoices------------------------------  
    //    public function invoices() {
    //        $data = $this->getUseroptions();
    //        $accounts = $this->getInvoices($data['user']);
    //        $data['table'] = $accounts['table'];
    //        $data['total'] = $accounts['total'];
    //        return view('admin.trash.invoices')->with("data", $data);
    //    }
    //
    //    public function getUserInvoices(Request $request) {
    //        $uid = $request->input('uid');
    //        $data = $this->getInvoices($uid);
    //        return json_encode($data);
    //    }
    //
    //    public function getInvoices() {
    //        if ($uid == 'All') {
    //            $invoices = Tbl_invoice::where('active', 0)->get();
    //        } else {
    //            $invoices = Tbl_invoice::where('uid', $uid)->where('active', 0)->get();
    //        }
    //
    //
    //        $total = count($invoices);
    //        if ($total > 0) {
    //            $formstable = '<table id="example1" class="table">';
    //            $formstable .= '<thead>';
    //            $formstable .= '<tr>';
    //            $formstable .= '<th>Name</th>';
    //            $formstable .= '<th>Bill To</th>';
    //            $formstable .= '<th>Amount</th>';
    //            $formstable .= '<th>Status</th>';
    //            $formstable .= '<th>Date</th>';
    //            $formstable .= '<th>Action</th>';
    //            $formstable .= '</tr>';
    //            $formstable .= '</thead>';
    //            $formstable .= '<tbody>';
    //            foreach ($invoices as $formdetails) {
    //
    //                $formstable .= '<tr>';
    //                $formstable .= '<td class="table-title">' . $formdetails->name . '</td>';
    //                $formstable .= '<td>' . $formdetails->billto . '</td>';
    //                $formstable .= '<td>' . $currency->html_code . ' ' . $formdetails->total_amount . '</td>';
    //                $status = ($formdetails->status == 1) ? 'Send' : 'Draft';
    //                $formstable .= '<td>' . $status . '</td>';
    //                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->updated_at)) . '</td>';
    //                $formstable .= '<td><a class="btn badge badge-info py-1 px-2" href="' . url('trash/restore/invoice/' . $formdetails->inv_id) . '">Restore</a></td>';
    //                $formstable .= '</tr>';
    //            }
    //            $formstable .= '</tbody>';
    //            $formstable .= '</table>';
    //        } else {
    //            $formstable = 'No records available';
    //        }
    //
    //        $data['total'] = $total;
    //        $data['table'] = $formstable;
    //
    //        return $data;
    //    }
    //-------------------------Events - Calendar------------------------------  

    public function events()
    {
        $data = $this->getUseroptions();
        $accounts = $this->getEvents($data['user']);
        $data['table'] = $accounts['table'];
        $data['total'] = $accounts['total'];
        return view('admin.trash.events')->with("data", $data);
    }

    public function getUserEvents(Request $request)
    {
        $uid = $request->input('uid');
        $data = $this->getEvents($uid);
        return json_encode($data);
    }

    public function getEvents($uid)
    {
        if ($uid == 'All') {
            $events = Tbl_events::where('active', 0)->get(['ev_id', 'title', 'startDatetime as start', 'endDatetime as end', 'description']);
        } else {
            $events = Tbl_events::where('uid', $uid)->where('active', 0)->get(['ev_id', 'title', 'startDatetime as start', 'endDatetime as end', 'description']);
        }

        $total = count($events);
        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Titlw</th>';
            $formstable .= '<th>Start Time</th>';
            $formstable .= '<th>End Time</th>';
            $formstable .= '<th>Description</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($events as $formdetails) {
                $formstable .= '<tr>';
                $formstable .= '<td class="table-title">' . $formdetails->description . '</td>';
                $formstable .= '</td>';
                $formstable .= '<td>' . date('d-m-Y h:i:s', strtotime($formdetails->start)) . '</td>';
                $formstable .= '<td>' . date('d-m-Y h:i:s', strtotime($formdetails->end)) . '</td>';
                $formstable .= '<td>' . substr($formdetails->description, 0, 20) . '</td>';
                $formstable .= '<td><a class="btn badge badge-info py-1 px-2" href="' . url('admin/trash/restore/events/' . $formdetails->ev_id) . '">Restore</a></td>';
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

    public function restoreEvents($id)
    {
        $event = Tbl_events::find($id);
        $event->active = 1;
        $event->save();
        return redirect('admin/calendar')->with('success', 'Restored Successfully..!');
    }

    //-------------------------Events - Calendar------------------------------  

    public function territory()
    {
        $data = $this->getUseroptions();
        $accounts = $this->getTerritories($data['user']);
        $data['table'] = $accounts['table'];
        $data['total'] = $accounts['total'];
        return view('admin.trash.territory')->with("data", $data);
    }

    public function getUserTerritory(Request $request)
    {
        $uid = $request->input('uid');
        $data = $this->getTerritories($uid);
        return json_encode($data);
    }

    public function getTerritories($uid)
    {
        if ($uid == 'All') {
            $terrtories = Tbl_territory::where('active', 0)->with('tbl_territory_users')->get();
        } else {
            $terrtories = Tbl_territory::where('uid', $uid)->where('active', 0)->with('tbl_territory_users')->get();
        }

        $total = count($terrtories);
        $data['total'] = $total;

        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
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

                $formstable .= '<tr>';
                $formstable .= '<td class="table-title">' . $formdetails->name . '</td>';
                $formstable .= '<td>' . rtrim(substr(rtrim($subusernames, ' '), 0, 25), ',') . '</td>';
                $formstable .= '<td>' . substr($formdetails->description, 0, 15) . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->updated_at)) . '</td>';
                $formstable .= '<td><a class="btn badge badge-info py-1 px-2" href="' . url('admin/trash/restore/territory/' . $formdetails->tid) . '">Restore</a></td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }
        $data['table'] = $formstable;

        return $data;
    }

    public function restoreTerritory($id)
    {
        $territory = Tbl_territory::find($id);
        $territory->active = 1;
        $territory->save();
        return redirect('admin/territory')->with('success', 'Restored Successfully..!');
    }

    //---------------------Invoices--------------------------------------

    public function invoices()
    {
        $data = $this->getUseroptions();
        $accounts = $this->getInvoices($data['user']);
        $data['table'] = $accounts['table'];
        $data['total'] = $accounts['total'];
        return view('admin.trash.invoices')->with("data", $data);
    }

    public function getUserInvoices(Request $request)
    {
        $uid = $request->input('uid');
        $data = $this->getInvoices($uid);
        return json_encode($data);
    }

    public function getInvoices($uid)
    {

        if ($uid == 'All') {
            $invoices = Tbl_invoice::where('active', 0)->get();
        } else {
            $invoices = Tbl_invoice::where('uid', $uid)->where('active', 0)->get();
        }

        $total = count($invoices);
        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Bill To</th>';
            $formstable .= '<th>Amount  ()</th>';
            $formstable .= '<th>Status</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($invoices as $formdetails) {

                $user = User::find($formdetails->uid);
                $currency = currency::find($user->cr_id);

                $formstable .= '<tr>';
                $formstable .= '<td class="table-title">' . $formdetails->name . '</td>';
                $formstable .= '<td>' . $formdetails->billto . '</td>';
                $formstable .= '<td>' . $currency->html_code . ' ' . $formdetails->total_amount . '</td>';
                $status = ($formdetails->status == 1) ? 'Send' : 'Draft';
                $formstable .= '<td>' . $status . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->updated_at)) . '</td>';
                $formstable .= '<td><a class="btn badge badge-info py-1 px-2" href="' . url('admin/trash/restore/invoice/' . $formdetails->inv_id) . '">Restore</a></td>';
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

    public function restoreInvoice($id)
    {
        $inv = Tbl_invoice::find($id);
        $inv->active = 1;
        $inv->save();
        return redirect('admin/invoices')->with('success', 'Restored Successfully...!');
    }


    // Product Leads


    public function getProductLeads()
    {
        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        $useroptions = "<option value='All' selected>All</option>";

        $admins = Admin::orderby('id', 'asc')->get(['id', 'name']);
        foreach ($admins as $admin) {
            $useroptions .= "<option value=" . $admin->id . '-1'  . ">" . $admin->name . "</option>";   // " . $selected . "
        }

        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . '-2'  . ">" . $userdetails->name . "</option>";   // " . $selected . "
        }
        $data['useroptions'] = $useroptions;

        $uid = 'All';
        $user_type = 0;

        $accounts = $this->getUserProductLeads($uid, $user_type);
        $data['table'] = $accounts['table'];
        $data['total'] = $accounts['total'];
        return view('admin.trash.proleads')->with("data", $data);
    }


    public function getUserProductLeadsAjax(Request $request)
    {
        $uid = $request->input('uid');

        // return json_encode($uid);

        if ($uid == 'All') {
            $uid = 'All';
            $user_type = 0;
        } else {
            $user = explode('-', $uid);
            $uid = $user[0];
            $user_type = $user[1];
        }

        $data = $this->getUserProductLeads($uid, $user_type);
        return json_encode($data);
    }


    public function getUserProductLeads($uid, $user_type)
    {
        // $uid = Auth::user()->id;

        // echo $uid;
        // exit();

        // $query = DB::table('tbl_cart_orders');
        // $query->where('tbl_cart_orders.active', 0);
        // $query->leftJoin('tbl_products', 'tbl_cart_orders.pro_id', '=', 'tbl_products.pro_id');
        // $query->leftJoin('tbl_post_order_stage', 'tbl_cart_orders.pos_id', '=', 'tbl_post_order_stage.pos_id');
        // $query->leftJoin('tbl_countries', 'tbl_cart_orders.country', '=', 'tbl_countries.id');
        // $query->leftJoin('tbl_states', 'tbl_cart_orders.state', '=', 'tbl_states.id');
        // if ($uid != 'All') {
        //     $query->where('tbl_products.uid', $uid);
        // }
        // if ($user_type > 0) {
        //     $query->where('tbl_products.user_type', $user_type);
        // }
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
        //     // $formstable .= '<th width="2"></th>';
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
        //         // $formstable .= '<td><input type="checkbox" class="checkAll" id="' . $formdetails->coid . '"></td>';
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
        //         $formstable .= '<a class="badge  badge-success"  href="' . url('admin/trash/restore/prolead/' . $formdetails->coid) . '">Restore</a>';
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


        $user = User::find($uid);

        // $cr_id = $user->cr_id;

        // $currency = currency::find($cr_id);

        $leadstatuslist = Tbl_leadstatus::all();

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
                $formstable .= '<a class="badge  badge-success"  href="' . url('admin/trash/restore/prolead/' . $formdetails->ld_id) . '">Restore</a>';
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




        return $data;
        // return view('admin.trash.proleads')->with("data", $data);
    }


    //--------------------Restore Product Leads---------------------------------
    public function restoreProductleads($id)
    {
        // $lead = Tbl_cart_orders::find($id);
        $lead = Tbl_leads::find($id);
        $lead->active = 1;
        $lead->save();
        return redirect('admin/trash/productleads')->with('success', 'Lead Restored Successfully..!');
    }

    // Currency
    public function currencys()
    {
        $currencies = currency::where('active', 0)->get();
        $total = count($currencies);
        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Currency</th>';
            $formstable .= '<th>Code</th>';
            $formstable .= '<th>Html Code</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($currencies as $currency) {
                $default = '';
                if ($currency->status == 1) {
                    $default = '<span class = "badge badge-success">Default</span>';
                } else {
                    $default = '';
                }

                $formstable .= '<tr>';
                $formstable .= '<td>' . $currency->name . '&nbsp;' . $default . '</td>';
                $formstable .= '<td>' . $currency->code . '</td>';
                $formstable .= '<td>' . $currency->html_code . '</td>';
                $formstable .= '<td>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2"  href="' . url('admin/trash/restore/currency/' . $currency->cr_id) . '">Restore</a>';
                $formstable .= '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        //        echo $formstable;

        $data['total'] = $total;
        $data['table'] = $formstable;

        return view('admin.trash.currency')->with("data", $data);
    }


    public function restoreCurrency($id)
    {
        $lead = currency::find($id);
        $lead->active = 1;
        $lead->save();
        return redirect('admin/trash/currency')->with('success', 'Restored Successfully..!');
    }
}

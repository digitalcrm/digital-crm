<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Ticket;
use App\Tbl_deals;
use App\Tbl_forms;
use App\Tbl_leads;
use App\Tbl_orders;
use App\Tbl_Accounts;
use App\Tbl_contacts;
use App\Tbl_formleads;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
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

    //  Function to search results
    public function getSearchData(Request $request) {

        $selectedField = $request->get('searchfield');//This would be used for checking which value is selected
        // dd($selectedField);
        $uid = Auth::user()->id;
        $total = 0;
        $table = '';
        $search = $request->input('search');
        $data['search'] = $search;

        //accounts data
        $accounts = Tbl_Accounts::where('name', 'like', '%' . $search . '%')
        ->orwhere('email', 'like', '%' . $search . '%')
        ->orwhere('mobile', 'like', '%' . $search . '%')
        ->orwhere('phone', 'like', '%' . $search . '%')
        ->where('active', 1)
        // ->where('uid', $uid)
        ->distinct('acc_id')
        ->get();
        $total+=count($accounts);

        // search Tickets
        $tickets = Ticket::where('ticket_number', 'like', '%' . $search . '%')
        ->orwhere('name', 'like', '%' . $search . '%')
        ->get();
        $total+=count($tickets);

        //Orders
        $orders = Tbl_orders::where('number', 'like', '%' .$search . '%')
            ->distinct('oid')
            ->get();
            $total+=count($orders);

            //Contact data
            $contacts = Tbl_contacts::where('first_name', 'like', '%' . $search . '%')
            ->orwhere('last_name', 'like', '%' . $search . '%')
            ->orwhere('email', 'like', '%' . $search . '%')
            ->orwhere('mobile', 'like', '%' . $search . '%')
            ->orwhere('phone', 'like', '%' . $search . '%')
            ->where('active', 1)
            // ->where('uid', $uid)
            ->distinct('cnt_id')
            ->get();
            $total+=count($contacts);

            //leads data
            $leads = Tbl_leads::where('first_name', 'like', '%' . $search . '%')
            ->orwhere('last_name', 'like', '%' . $search . '%')
            ->orwhere('email', 'like', '%' . $search . '%')
            ->orwhere('mobile', 'like', '%' . $search . '%')
            ->orwhere('phone', 'like', '%' . $search . '%')
            ->where('active', 1)
            // ->where('uid', $uid)
            ->distinct('ld_id')
            ->get();
            $total+=count($leads);

            //deal data
            $deals = Tbl_deals::where('name', 'like', '%' . $search . '%')
            ->where('active', 1)
            // ->where('uid', $uid)
            ->with('tbl_leads')
            ->distinct('deal_id')
            ->get();
            $total+=count($deals);

            //customer data
            $customers = Tbl_deals::where('name', 'like', '%' . $search . '%')
            ->where('active', 1)
            ->where('deal_status', 1)
            // ->where('uid', $uid)
            ->with('tbl_leads')
            ->distinct('deal_id')
            ->get();
            $total+=count($customers);

            //sales data
            $sales = Tbl_deals::where('value', 'like', '%' . $search . '%')
            ->where('active', 1)
            ->where('deal_status', 1)
            // ->where('uid', $uid)
            ->with('tbl_leads')
            ->distinct('deal_id')
            ->get();
            $total+=count($sales);

            //  Get results - forms table=> web-to-lead-data
            $forms = Tbl_forms::where('title', 'like', '%' . $search . '%')
            ->orwhere('from_mail', 'like', '%' . $search . '%')
            ->where('active', 1)
            // ->where('uid', $uid)
            ->distinct('form_id')
            ->get();
            $total+=count($forms);


            //Condition for get search value if dropdown value is selecte suchas All, Lead, Deal, Account and Contact
            if($selectedField == 'All') {

                if (count($accounts) > 0) {
                    foreach ($accounts as $account) {
                        $table .= '<tr>';
                        $table .= '<td><a href="' . url('accounts/' . $account->acc_id) . '" target="_blank">' . $account->name . '</a></td>';
                        $table .= '<td>' . $account->email . '</td>';
                        $table .= '<td>' . $account->mobile . '</td>';
                        $table .= '<td>' . $account->phone . '</td>';
                        $table .= '<td>Account</td>';
                        $table .= '</tr>';
                    }
                }

                // Ticket data
                if (count($tickets) > 0) {
                    foreach ($tickets as $ticket) {
                        $table .= '<tr>';
                        $table .= '<td><a href="' . url('tickets/' . $ticket->id) . '" target="_blank">' . $ticket->name . '</a></td>';
                        $table .= '<td>' . $ticket->tbl_contacts->email . '</td>';
                        // $table .= '<td>' . $ticket->ticket_number . '</td>';
                        $table .= '<td>' . $ticket->tbl_contacts->mobile . '</td>';
                        // $table .= '<td>' . $ticket->tbl_contacts->fullname() . '</td>';
                        $table .= '<td>' . $ticket->tbl_contacts->phone . '</td>';
                        $table .= '<td>Ticket</td>';
                        $table .= '</tr>';
                    }
                }


                //  Get results - contacts table
                if (count($contacts) > 0) {
                    foreach ($contacts as $contact) {
                        $table .= '<tr>';
                        $table .= '<td><a href="' . url('contacts/' . $contact->cnt_id) . '" target="_blank">' . $contact->first_name . ' ' . $contact->last_name . '</a></td>';
                        $table .= '<td>' . $contact->email . '</td>';
                        $table .= '<td>' . $contact->mobile . '</td>';
                        $table .= '<td>' . $contact->phone . '</td>';
                        $table .= '<td>Contact</td>';
                        $table .= '</tr>';
                    }
                }

                //  Get results - leads table
                if (count($leads) > 0) {
                    foreach ($leads as $lead) {
                        $table .= '<tr>';
                        $table .= '<td><a href="' . url('leads/' . $lead->ld_id) . '" target="_blank">' . $lead->first_name . ' ' . $lead->last_name . '</a></td>';
                        $table .= '<td>' . $lead->email . '</td>';
                        $table .= '<td>' . $lead->mobile . '</td>';
                        $table .= '<td>' . $lead->phone . '</td>';
                        $table .= '<td>Lead</td>';
                        $table .= '</tr>';
                    }
                }

                //  Get results - formleads table
                $formleads = Tbl_formleads::where('first_name', 'like', '%' . $search . '%')
                ->orwhere('last_name', 'like', '%' . $search . '%')
                ->orwhere('email', 'like', '%' . $search . '%')
                ->orwhere('mobile', 'like', '%' . $search . '%')
                ->where('active', 1)
                // ->where('uid', $uid)
                ->distinct('fl_id')
                ->get();
                $total+=count($formleads);

                if (count($formleads) > 0) {
                    foreach ($formleads as $formlead) {
                        $table .= '<tr>';
                        $table .= '<td><a href="' . url('webtolead/viewformlead/' . $formlead->fl_id) . '" target="_blank">' . $formlead->first_name . ' ' . $formlead->last_name . '</a></td>';
                        $table .= '<td>' . $formlead->email . '</td>';
                        $table .= '<td>' . $formlead->mobile . '</td>';
                        $table .= '<td></td>';
                        $table .= '<td>Form Lead</td>';
                        $table .= '</tr>';
                    }
                }


                // web to lead
                if (count($forms) > 0) {
                    foreach ($forms as $form) {
                        $table .= '<tr>';
                        $table .= '<td><a href="' . url('webtolead/formleads/' . $form->form_id) . '" target="_blank">' . $form->title . '</a></td>';
                        $table .= '<td>' . $form->from_mail . '</td>';
                        $table .= '<td></td>';
                        $table .= '<td></td>';
                        $table .= '<td>Form</td>';
                        $table .= '</tr>';
                    }
                }

                //  Get results - deals table

                if (count($deals) > 0) {
                    foreach ($deals as $deal) {
                        $table .= '<tr>';
                        $table .= '<td><a href="' . url('deals/' . $deal->deal_id) . '" target="_blank">' . $deal->name . '</a></td>';
                        $table .= '<td>' . $deal->tbl_leads->email . '</td>';
                        $table .= '<td></td>';
                        $table .= '<td></td>';
                        $table .= '<td>Deal</td>';
                        $table .= '</tr>';
                    }
                }

                // Orders
                if (count($orders) > 0) {
                    foreach ($orders as $order) {
                        $table .= '<tr>';
                        $table .= '<td><a href="' . url('orders/' . $order->oid) . '" target="_blank">' . $order->tbl_deals->name . '</a></td>';
                        $table .= '<td></td>';
                        $table .= '<td></td>';
                        $table .= '<td></td>';
                        $table .= '<td>Order</td>';
                        $table .= '</tr>';
                    }
                }

            }elseif($selectedField == 'Lead') {
                //  Get results - leads table
                if (count($leads) > 0) {
                    foreach ($leads as $lead) {
                        $table .= '<tr>';
                        $table .= '<td><a href="' . url('leads/' . $lead->ld_id) . '" target="_blank">' . $lead->first_name . ' ' . $lead->last_name . '</a></td>';
                        $table .= '<td>' . $lead->email . '</td>';
                        $table .= '<td>' . $lead->mobile . '</td>';
                        $table .= '<td>' . $lead->phone . '</td>';
                        $table .= '<td>Lead</td>';
                        $table .= '</tr>';
                    }
                }
                // dd("Lead is selected");

            } elseif($selectedField == 'Contact') {
                // Contact search value get
                if (count($contacts) > 0) {
                    foreach ($contacts as $contact) {
                        $table .= '<tr>';
                        $table .= '<td><a href="' . url('contacts/' . $contact->cnt_id) . '" target="_blank">' . $contact->first_name . ' ' . $contact->last_name . '</a></td>';
                        $table .= '<td>' . $contact->email . '</td>';
                        $table .= '<td>' . $contact->mobile . '</td>';
                        $table .= '<td>' . $contact->phone . '</td>';
                        $table .= '<td>Contact</td>';
                        $table .= '</tr>';
                    }
                }
                // dd("Contact");

            } elseif ($selectedField == 'Account') {
                //Account search value get
                if (count($accounts) > 0) {
                    foreach ($accounts as $account) {
                        $table .= '<tr>';
                        $table .= '<td><a href="' . url('accounts/' . $account->acc_id) . '" target="_blank">' . $account->name . '</a></td>';
                        $table .= '<td>' . $account->email . '</td>';
                        $table .= '<td>' . $account->mobile . '</td>';
                        $table .= '<td>' . $account->phone . '</td>';
                        $table .= '<td>Account</td>';
                        $table .= '</tr>';
                    }
                }
                // dd("Account");

            } elseif($selectedField == 'Deal') {
                //  Get results - deals table
                if (count($deals) > 0) {
                    foreach ($deals as $deal) {
                        $table .= '<tr>';
                        $table .= '<td><a href="' . url('deals/' . $deal->deal_id) . '" target="_blank">' . $deal->name . '</a></td>';
                        $table .= '<td>' . $deal->tbl_leads->email . '</td>';
                        $table .= '<td></td>';
                        $table .= '<td></td>';
                        $table .= '<td>Deal</td>';
                        $table .= '</tr>';
                    }
                }
                // dd("Deal value is slected");
            } elseif($selectedField == 'Webtolead') {
                //  Get results - deals table
                if (count($forms) > 0) {
                    foreach ($forms as $form) {
                        $table .= '<tr>';
                        $table .= '<td><a href="' . url('webtolead/formleads/' . $form->form_id) . '" target="_blank">' . $form->title . '</a></td>';
                        $table .= '<td>' . $form->from_mail . '</td>';
                        $table .= '<td></td>';
                        $table .= '<td></td>';
                        $table .= '<td>Form</td>';
                        $table .= '</tr>';
                    }
                }
                // dd("Deal value is slected");
            } elseif($selectedField == 'Order') {
                // Orders
                if (count($orders) > 0) {
                    foreach ($orders as $order) {
                        $table .= '<tr>';
                        $table .= '<td><a href="' . url('deals/' . $order->oid) . '" target="_blank">' . $order->tbl_deals->name . '</a></td>';
                        $table .= '<td><a href="' . url('orders/' . $order->oid) . '">' . $order->number . '</a></td>';
                        $table .= '<td></td>';
                        $table .= '<td></td>';
                        $table .= '<td>Order</td>';
                        $table .= '</tr>';
                    }
                }
            } elseif($selectedField == 'Customer'){
                if (count($customers) > 0) {
                    foreach ($customers as $customer) {
                        $table .= '<tr>';
                        $table .= '<td><a href="' . url('customers/profile/' . $customer['deal_id']) . '">' . $customer['tbl_leads']['first_name'] . ' ' . $customer['tbl_leads']['last_name'] . '</a></td>';
                        $table .= '<td class="table-title"><a href="' . url('customers/' . $customer['deal_id']) . '">' . $customer['name'] . '</a></td>';
                        $table .= '<td>' . $customer['value'] . '</td>';
                        $table .= '<td>' . date('d-m-Y', strtotime($customer['closing_date'])) . '</td>';
                        $table .= '<td>customer</td>';
                        $table .= '</tr>';
                    }
                }
            } elseif($selectedField == 'Sales'){
                if (count($sales) > 0) {
                    foreach ($sales as $sale) {
                        $table .= '<tr>';
                        $table .= '<td>' . $sale['tbl_leads']['first_name'] . ' ' . $sale['tbl_leads']['last_name'] . '</td>';
                        $table .= '<td class="table-title"><a href="' . url('customers/' . $sale->deal_id) . '">' . $sale->name . '</a></td>';
                        $table .= '<td>' . $sale['value'] . '</td>';
                        $table .= '<td>' . date('d-m-Y', strtotime($sale['closing_date'])) . '</td>';
                        $table .= '<td>sales</td>';
                        $table .= '</tr>';
                    }
                }
            } elseif($selectedField == 'Ticket'){
                //Tickets
                if (count($tickets) > 0) {
                 foreach ($tickets as $ticket) {
                     $table .= '<tr>';
                     $table .= '<td><a href="' . url('tickets/' . $ticket->id) . '" target="_blank">' . $ticket->name . '</a></td>';
                     $table .= '<td>' . $ticket->ticket_number . '</td>';
                     $table .= '<td>' . $ticket->tbl_contacts->fullname() . '</td>';
                     $table .= '<td>' . $ticket->tbl_contacts->email . '</td>';
                     $table .= '<td>Ticket</td>';
                     $table .= '</tr>';
                 }
             }
         }

            // Common Part to get result in table form
            if ($total > 0) {
                $formstable = '<table id="example1" class="table">';
                $formstable .= '<thead>';
                $formstable .= '<tr>';
                $formstable .= '<th id="changehead1">Name</th>';
                $formstable .= '<th id="changehead2">Email</th>';
                $formstable .= '<th id="changehead3">Mobile</th>';
                $formstable .= '<th id="changehead4">Phone</th>';
                $formstable .= '<th>Type</th>';
                // $formstable .= '<th>Action</th>';
                $formstable .= '</tr>';
                $formstable .= '</thead>';
                $formstable .= '<tbody>';
                $formstable .= $table;
                $formstable .= '</tbody>';
                $formstable .= '</table>';
            } else {
                $formstable = "No data available";
            }

            $data['total'] = $total;
            $data['table'] = $formstable;

            return view('admin.search', compact('data','selectedField'));

        }
    }

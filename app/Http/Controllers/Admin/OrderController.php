<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Tbl_leads;
use App\Tbl_deals;
use App\Tbl_deal_events;
use App\User;
use App\currency;
use App\Tbl_products;
use App\Tbl_deal_order_stage_events;
use App\Tbl_orders;
use App\Tbl_deliveryby;
use App\Tbl_post_order_stage;
use App\Tbl_units;
use App\Tbl_countries;
use App\Tbl_leadsource;
use App\Tbl_states;
use App\Tbl_salesfunnel;
use App\Tbl_lossreasons;

class OrderController extends Controller
{
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
        $users = User::where('user_type', 1)->orderby('id', 'asc')->get(['id', 'name']);
        $useroptions = "<option value='All' Selected>All</option>";
        $uid = 'All';
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";    // . "  " . $selected 
        }
        $data['useroptions'] = $useroptions;

        $deals = $this->getOrders($uid);

        $data['table'] = $deals['table'];
        $data['total'] = $deals['total'];

        return view('admin.orders.index')->with("data", $data);
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
        // echo $id;
        $order = Tbl_orders::with('tbl_deals')->with('tbl_post_order_stage')->with('tbl_deliveryby')->find($id);

        // Lead
        $lead = Tbl_leads::with('Tbl_leadsource')
            ->with('Tbl_industrytypes')
            ->with('Tbl_leadstatus')
            ->with('Tbl_Accounts')
            ->with('Tbl_countries')
            ->with('Tbl_states')
            ->with('Tbl_salutations')
            ->with('Tbl_products')
            ->find($order->tbl_deals->ld_id);
        $order['lead'] = $lead;

        // Product
        $product = Tbl_products::find($order->tbl_deals->pro_id);
        $order['product'] = $product;

        // Leadsource
        $leadsource = Tbl_leadsource::find($order->tbl_deals->ldsrc_id);
        $order['leadsource'] = $leadsource;

        // Loss reason
        $lossreason = Tbl_lossreasons::find($order->tbl_deals->lr_id);
        $order['lossreason'] = $lossreason;

        // echo json_encode($order);
        // exit();

        // Deal Events
        $dealevents = Tbl_deal_events::where('deal_id', $order->deal_id)->orderBy('dev_id', 'desc')->get();

        $dealeventsUl = 'Log is not available...';

        if ($dealevents != '') {

            $events = $dealevents;

            if (count($events) > 0) {
                $dealeventsUl = '<ul class="timeline timeline-inverse">';

                foreach ($events as $event) {
                    $from = Tbl_salesfunnel::find($event->sfun_from);
                    $to = Tbl_salesfunnel::find($event->sfun_to);

                    $from_fun = ($from != '') ? ' from ' . $from->salesfunnel : '';
                    $to_fun = ($to != '') ? ' to ' . $to->salesfunnel : '';


                    $dealeventsUl .= '<li class="time-label"><span class="bg-grey">' . date('d M Y h:i a', strtotime($event->event_time)) . '</span></li>';
                    $dealeventsUl .= '<li>
                            <i class="fa fa-suitcase bg-default"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header no-border">Deal status changed ' . $from_fun . $to_fun . ' </h3>
                            </div>
                        </li>';
                }
                $dealeventsUl .= '<li>
                    <i class="fa fa-clock-o bg-gray"></i>
                  </li>';
                $dealeventsUl .= '</ul>';
            }
        }

        $order['dealEvents'] = $dealeventsUl;

        // Order Events
        $orderevents = Tbl_deal_order_stage_events::where('oid', $id)->orderBy('dos_id', 'desc')->get();

        $eventsul = 'Log is not available...';

        if ($orderevents != "") {

            $events = $orderevents;

            if (count($events) > 0) {
                $eventsul = '<ul class="timeline timeline-inverse">';

                foreach ($events as $event) {

                    if (($event['os_from'] == 0) && ($event['os_to'] > 0)) {
                        $to = Tbl_post_order_stage::find($event['os_to']);

                        $eventsul .= '<li class="time-label"><span class="bg-grey">' . date('d M Y h:i a', strtotime($event['os_time'])) . '</span></li>';
                        $eventsul .= '<li>
                            <i class="fa fa-suitcase bg-default"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header no-border">Order status status from ' . $to->stage . ' </h3>
                            </div>
                        </li>';
                    }

                    if (($event['os_from'] > 0) && ($event['os_to'] > 0)) {

                        $from = Tbl_post_order_stage::find($event['os_from']);
                        $to = Tbl_post_order_stage::find($event['os_to']);

                        $eventsul .= '<li class="time-label"><span class="bg-grey">' . date('d M Y h:i a', strtotime($event['os_time'])) . '</span></li>';
                        $eventsul .= '<li>
                            <i class="fa fa-suitcase bg-default"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header no-border">Order status changed from ' . $from->stage . ' to ' . $to->stage . ' </h3>
                            </div>
                        </li>';
                    }
                }

                $eventsul .= '<li>
                    <i class="fa fa-clock-o bg-gray"></i>
                  </li>';

                $eventsul .= '</ul>';
            }
        }

        $order['eventsul'] = $eventsul;

        // Currency
        $user = User::find($order->tbl_deals->uid);
        $cr_id = $user->cr_id;
        $currency = currency::find($cr_id);
        $order['currency'] = $currency;

        // echo json_encode($order);
        // exit();

        return view('admin.orders.show')->with("data", $order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Tbl_orders::with('tbl_deals')->with('tbl_post_order_stage')->with('tbl_deliveryby')->find($id);

        // Lead
        $lead = Tbl_leads::with('Tbl_countries')->with('Tbl_states')->find($order->tbl_deals->ld_id);
        $order['lead'] = $lead;

        // echo json_encode($order);
        // exit();

        // Product
        $product = Tbl_products::find($order->tbl_deals->pro_id);
        $order['product'] = $product;

        // Leadsource
        $leadsource = Tbl_leadsource::find($order->tbl_deals->ldsrc_id);
        $order['leadsource'] = $leadsource;

        // Loss reason
        $lossreason = Tbl_lossreasons::find($order->tbl_deals->lr_id);
        $order['lossreason'] = $lossreason;

        // Currency
        $cr_id = Auth::user()->cr_id;
        $currency = currency::find($cr_id);
        $order['currency'] = $currency;

        // Deals
        $dealoptions = $this->getDealOptions($order->deal_id);
        $order['dealoptions'] = $dealoptions;

        // Post order stage
        $orderoptions = $this->getPostOrderStageOptions($order->pos_id);
        $order['orderoptions'] = $orderoptions;

        // Delivery Options
        $deliveryoptions = $this->getDeliveryOptions($order->delivery_by);
        $order['deliveryoptions'] = $deliveryoptions;

        // Product Options
        $productoptions = $this->getProductOptions($order->tbl_deals->pro_id);
        $order['productoptions'] = $productoptions;

        // Lead Options
        $leadoptions = $this->getLeadOptions($order->tbl_deals->ld_id);
        $order['leadoptions'] = $leadoptions;

        // Country Options
        $countryoptions = $this->getCountryOptions($order->lead->country);
        $order['countryoptions'] = $countryoptions;

        // State Options
        if ($order->lead->country > 0) {
            $stateoptions = $this->getStateOptions($order->lead->state, $order->lead->country);
            $order['stateoptions'] = $stateoptions;
        } else {

            $stateoptions = '<option value="0">Select State...</option>';
            $order['stateoptions'] = $stateoptions;
        }

        return view('admin.orders.edit')->with('data', $order);
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

        // echo json_encode($request->input());
        // exit();

        $uid = $request->input('user');

        $ld_id = $request->input('ld_id');

        $this->validate($request, [
            'deal_id' => 'required|numeric',
            'ld_id' => 'required|numeric',
            'pro_id' => 'required|numeric',
            'number' => 'required|numeric|unique:tbl_orders,number,' . $id . ',oid',
            'order_date' => 'required|date_format:d-m-Y',
            'shipping_date' => 'required|date_format:d-m-Y',
            'quantity' => 'required|numeric|min:1',
        ], [
            'deal_id.required' => 'Plaese select deal',
            'ld_id.required' => 'Plaese select lead',
            'pro_id.required' => 'Plaese select product',
            'pro_id.required' => 'Plaese select product',
            'number.required' => 'Order number is required',
            'number.unique' => 'Order number is already taken',
        ]);

        $shipping_date = ($request->input('shipping_date') != '') ? date('Y-m-d', strtotime($request->input('shipping_date'))) : '';
        $order_date = ($request->input('order_date') != '') ? date('Y-m-d', strtotime($request->input('order_date'))) : '';
        $quantity = ($request->input('quantity') > 0) ? $request->input('quantity') : 1;

        $formdata = array(
            'deal_id' => $request->input('deal_id'),
            // 'pro_id' => $request->input('pro_id'),
            'deal_id' => $request->input('deal_id'),
            'order_date' => $order_date,
            'shipping_date' => $shipping_date,
            'quantity' => $quantity,
            'total_amount' => $request->input('total_amount'),
            'delivery_charges' => $request->input('delivery_charges'),
            'verify_by' => $request->input('verify_by'),
            'remarks' => $request->input('remarks'),
            'pos_id' => $request->input('pos_id'),
            'delivery_by' => $request->input('dlb_id'),
            'number' => $request->input('number'),
        );

        $leadData = array(
            'street' => $request->input('address'),
            'country' => $request->input('country'),
            'state' => $request->input('state'),
            'zip' => $request->input('zip'),
            'city' => $request->input('city'),
        );

        $order = Tbl_orders::where('oid', $id)->update($formdata);

        if ($order > 0) {
            Tbl_leads::where('ld_id', $ld_id)->update($leadData);
            return redirect('admin/orders')->with('success', "Order Updated Successfully");
        } else {
            return redirect('admin/orders')->with('error', "Failed... Try again later..!");
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

    public function getOrders($uid)
    {
        $user = "";
        $currency = "";
        $cr_id = "";

        if ($uid == 'All') {
            $orders = Tbl_orders::with('tbl_deals')->with('tbl_post_order_stage')->with('tbl_deliveryby')->orderBy('oid', 'desc')->get();
        } else {
            $orders = Tbl_orders::where('uid', $uid)->with('tbl_deals')->with('tbl_post_order_stage')->with('tbl_deliveryby')->orderBy('oid', 'desc')->get();

            $user = User::find($uid);
            $cr_id = $user->cr_id;
            $currency = currency::find($cr_id);
        }

        // echo json_encode($orders);
        // exit();


        $total = count($orders);
        if ($total > 0) {
            $formstable = '<table id="dealsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Order Number</th>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Lead</th>';
            $formstable .= '<th>Product</th>';
            $formstable .= '<th>Total Amount</th>';
            $formstable .= '<th>Order Date</th>';
            $formstable .= '<th>Shipping Date</span></th>';
            $formstable .= '<th>Post Order Stage</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($orders as $formdetails) {

                $product = "";
                if ($formdetails->tbl_deals->pro_id > 0) {
                    // echo json_encode($formdetails);
                    // exit();
                    $product = Tbl_products::find($formdetails->tbl_deals->pro_id);
                }


                if ($uid == 'All') {
                    $user = User::find($formdetails->tbl_deals->uid);
                    $cr_id = $user->cr_id;
                    $currency = currency::find($cr_id);
                }

                $lead = Tbl_leads::with('Tbl_countries')->with('Tbl_states')->find($formdetails->tbl_deals->ld_id);
                $product = Tbl_products::find($formdetails->tbl_deals->pro_id);


                $formstable .= '<tr>';
                $formstable .= '<td><a href="' . url('admin/orders/' . $formdetails->oid) . '">' . $formdetails->number . '</a></td>';
                $formstable .= '<td><a href="' . url('admin/deals/' . $formdetails->deal_id) . '">' . $formdetails->tbl_deals->name . '</a></td>';
                $formstable .= '<td>' . $lead->first_name . ' ' . $lead->last_name . '</td>';
                $formstable .= '<td>' . (($product != '') ? $product->name : '') . '</td>';
                $formstable .= '<td><span>' . $currency->html_code . '</span> ' . $formdetails->total_amount . '</td>';
                $formstable .= '<td>' . (($formdetails->order_date != '') ? date('d-m-Y', strtotime($formdetails->order_date)) : '') . '</td>';
                $formstable .= '<td>' . (($formdetails->shipping_date != '') ? date('d-m-Y', strtotime($formdetails->shipping_date)) : '') . '</td>';
                $formstable .= '<td>' . (($formdetails->tbl_post_order_stage != '') ? $formdetails->tbl_post_order_stage->stage : '') . '</td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                      <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                      </button>
                        <div class="dropdown-menu">
                          <a class="dropdown-item text-default text-btn-space" href="' . url('admin/orders/' . $formdetails->oid . '/edit') . '">Edit</a>
                          <a class="dropdown-item text-default text-btn-space" href="#">Delete</a>
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

    public function getUserOrders(Request $request)
    {
        $uid = $request->input('id');
        $data = $this->getOrders($uid);
        return json_encode($data);
    }

    public function getDeliveryOptions($dopid)
    {
        $deliverys = Tbl_deliveryby::all();
        $deliveryoptions = "<option value='0'>Select ...</option>";
        if (count($deliverys) > 0) {
            foreach ($deliverys as $delivery) {
                $deliveryselected = ($dopid == $delivery->dlb_id) ? 'selected' : '';
                $deliveryoptions .= "<option value='" . $delivery->dlb_id . "' " . $deliveryselected . " >" . $delivery->delivery_by . "</option>";
            }
        }

        return $deliveryoptions;
    }

    public function getPostOrderStageOptions($posid)
    {
        $orders = Tbl_post_order_stage::all();
        $orderoptions = "<option value='0'>Select Post Order Stage</option>";
        if (count($orders) > 0) {
            foreach ($orders as $order) {
                $orderselected = ($posid == $order->pos_id) ? 'selected' : '';
                $orderoptions .= "<option value='" . $order->pos_id . "' " . $orderselected . ">" . $order->stage . "</option>";
            }
        }

        return $orderoptions;
    }

    public function getDealOptions($dealid)
    {
        $uid = Auth::user()->id;
        $deals = Tbl_deals::where('uid', $uid)->where('active', 1)->where('deal_status', 0)->get();
        $dealoptions = "<option value=''>Select Deal</option>";
        if (count($deals) > 0) {
            foreach ($deals as $deal) {
                $dealselected = ($dealid == $deal->deal_id) ? 'selected' : '';
                $dealoptions .= "<option value='" . $deal->deal_id . "' " . $dealselected . ">" . $deal->name . "</option>";
            }
        }

        return $dealoptions;
    }

    public function getCountryOptions($id)
    {
        $country = Tbl_countries::all();
        $countryoptions = "<option value='0'>Select Country</option>";
        if (count($country) > 0) {
            foreach ($country as $cnt) {
                $selected = ($id == $cnt->id) ? 'selected' : '';
                $countryoptions .= "<option value='" . $cnt->id . "' " . $selected . ">" . $cnt->name . ' - ' . $cnt->sortname . "</option>";
            }
        }

        return $countryoptions;
    }

    public function getStateOptions($id, $country)
    {
        $states = Tbl_states::where('country_id', $country)->get();
        $stateOption = "<option value='0'>Select State</option>";
        foreach ($states as $state) {
            $selected = ($id == $state->id) ? 'selected' : '';
            $stateOption .= "<option value='" . $state->id . "' " . $selected . ">" . $state->name . "</option>";
        }
        return $stateOption;
    }

    public function getProductOptions($proid)
    {
        $uid = Auth::user()->id;
        $deliverys = Tbl_products::where('uid', $uid)->where('active', 1)->get();
        $deliveryoptions = "<option value=''>Select Product...</option>";
        if (count($deliverys) > 0) {
            foreach ($deliverys as $delivery) {
                $deliveryselected = ($proid == $delivery->pro_id) ? 'selected' : '';
                $deliveryoptions .= "<option value='" . $delivery->pro_id . "' " . $deliveryselected . " >" . $delivery->name . "</option>";
            }
        }

        return $deliveryoptions;
    }

    public function getLeadOptions($proid)
    {
        $uid = Auth::user()->id;
        $deliverys = Tbl_leads::where('uid', $uid)->where('active', 1)->get();
        $deliveryoptions = "<option value=''>Select Lead...</option>";
        if (count($deliverys) > 0) {
            foreach ($deliverys as $delivery) {
                $deliveryselected = ($proid == $delivery->ld_id) ? 'selected' : '';
                $deliveryoptions .= "<option value='" . $delivery->ld_id . "' " . $deliveryselected . " >" . substr($delivery->first_name . ' ' . $delivery->last_name, 0, 25) . "</option>";
            }
        }

        return $deliveryoptions;
    }
}

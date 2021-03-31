<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
//---------Models---------------
use App\Tbl_productcategory;
use App\Tbl_products;
use App\Tbl_user_cart;
use App\currency;
use App\Admin;
use App\User;
use App\Tbl_units;
use App\Tbl_countries;
use App\Tbl_states;
use App\Tbl_cart_orders;
use App\Consumer;
use App\Tbl_post_order_stage;

class EcommerceController extends Controller
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
        //
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
        // $cart_order = Tbl_cart_orders::with('Tbl_countries')->with('Tbl_states')->find($id);
        // $pro_id = $cart_order->pro_id;
        // $cid = $cart_order->uid;

        // $product = $this->getProductDetails($pro_id);
        // $data['product'] = $product['product'];
        // $data['user'] = $product['user'];
        // // echo json_encode($product);
        // // exit();

        // // $country = Tbl_countries::all();
        // // $countryoptions = "<option value='0'>Select Country</option>";
        // // if (count($country) > 0) {
        // //     foreach ($country as $cnt) {
        // //         $countryoptions .= "<option value='" . $cnt->id . "'>" . $cnt->name . ' - ' . $cnt->sortname . "</option>";
        // //     }
        // // }
        // // $product['countryoptions'] = $countryoptions;

        // $consumer = Consumer::find($cid);
        // $data['consumer'] = $consumer;
        // $data['cart_order'] = $cart_order;
        // // echo json_encode($data);
        // // exit();

        $cart_order = Tbl_cart_orders::with('Tbl_countries')->with('Tbl_states')->find($id);
        // echo json_encode($cart_order);
        // exit();
        $data['cart_order'] = $cart_order;

        $product = $this->getProductDetails($cart_order->pro_id);
        $data['product'] = $product['product'];

        $data['product'] = $cart_order->tbl_products;

        $user = User::find($cart_order->uid);
        $data['user'] = $user;

        return view('admin.ecommerce.show')->with('data', $data);
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
        $cart_order = Tbl_cart_orders::with('Tbl_countries')->with('Tbl_states')->find($id);
        $pro_id = $cart_order->pro_id;
        $cid = $cart_order->uid;

        $product = $this->getProductDetails($pro_id);
        $data['product'] = $product['product'];
        $data['user'] = $product['user'];

        $consumer = Consumer::find($cid);
        $data['consumer'] = $consumer;
        $data['cart_order'] = $cart_order;

        $country = Tbl_countries::all();
        $countryoptions = "<option value='0'>Select Country</option>";
        if (count($country) > 0) {
            foreach ($country as $cnt) {
                $selected = ($cnt->id == $cart_order['country']) ? 'selected' : '';
                $countryoptions .= "<option value='" . $cnt->id . "' " . $selected . ">" . $cnt->name . ' - ' . $cnt->sortname . "</option>";
            }
        }
        $data['countryoptions'] = $countryoptions;

        $stateoptions = "<option value='0'>Select State</option>";
        if ($cart_order['country'] > 0) {
            $states = DB::select('SELECT * FROM `tbl_states` WHERE `country_id`=' . $cart_order['country']);
            if (count($states) > 0) {
                foreach ($states as $state) {
                    $stateselected = ($state->id == $cart_order['state']) ? 'selected' : '';
                    $stateoptions .= "<option value='" . $state->id . "' " . $stateselected . ">" . $state->name . "</option>";
                }
            }
        }
        $data['stateoptions'] = $stateoptions;

        $orderst = Tbl_post_order_stage::all();
        $orderoptions = "<option value='0'>Select Post Order Stage</option>";
        if (count($orderst) > 0) {
            foreach ($orderst as $order) {
                $orderselected = ($cart_order->pos_id == $order->pos_id) ? 'selected' : '';
                $orderoptions .= "<option value='" . $order->pos_id . "' " . $orderselected . ">" . $order->stage . "</option>";
            }
        }
        $data['orderoptions'] = $orderoptions;

        return view('admin.ecommerce.edit')->with('data', $data);
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
            'number' => 'required|unique:tbl_cart_orders,number,' . $id . ',coid',
            'name' => 'required|max:255',
            'email' => 'email|required|max:255',
            'mobile' => 'max:255',
            'quantity' => 'required',
            'price' => 'required',
            'city' => 'required',
            'zip' => 'required',
        ], [
            'number.required' => 'Number is required',
            'number.unique' => 'Given Order number already in use',
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'mobile.required' => 'Mobile is required',
            'quantity.required' => 'Quantity is required',
            'price.required' => 'Price is required',
            'city.required' => 'City is required',
            'zip.required' => 'Zip Code is required',
        ]);

        $data = $request->input();

        $formdata = array(
            // 'uid' => 0, //$uid
            'pos_id' => $data['pos_id'],
            'number' => $data['number'],
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'pro_id' => $id,
            'order_date' => date('Y-m-d'),
            'shipping_date' => date('Y-m-d', strtotime($data['shipping_date'])),
            'delivery_charges' => 0,
            'total_amount' => $data['total_amount'],
            'quantity' => $data['quantity'],
            'price' => $data['price'],
            'remarks' => '',
            'address' => $data['address'],
            'country' => $data['country'],
            'state' => $data['state'],
            'city' => $data['city'],
            'zip' => $data['zip'],
        );

        // echo json_encode($formdata);
        // exit();

        $res = Tbl_cart_orders::where('coid', $id)->update($formdata);

        if ($res) {
            return redirect('admin/ecommerce/' . $id)->with('success', 'Updated Successfully...!');
        } else {
            return redirect('admin/ecommerce/' . $id)->with('error', 'Failed. Try again later...!');
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

    public function ajaxGetUserOrders(Request $request)
    {
        $cid = $request->input('cid');
        $orders = $this->getOrdersList($cid);
        echo json_encode($orders);
    }

    public function getOrders($user)
    {

        $orders = $this->getOrdersList($user);
        // $data['orders'] = $orders;
        $orders['useroptions'] = $this->getConsumerOptions();

        return view('admin.ecommerce.orders')->with('data', $orders);
    }

    public function getConsumerOptions()
    {
        $users = $this->getConsumers();
        $uid = 'All';
        $useroptions = "<option value='All' selected>All</option>";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";   // " . $selected . "
        }
        return $useroptions;
    }

    public function getConsumers()
    {
        return Consumer::orderby('id', 'desc')->get();
    }

    public function getOrdersList($user)
    {
        $query = DB::table('tbl_cart_orders');
        $query->where('tbl_cart_orders.active', 1);
        $query->leftJoin('tbl_products', 'tbl_cart_orders.pro_id', '=', 'tbl_products.pro_id');
        $query->leftJoin('consumers', 'tbl_cart_orders.uid', '=', 'consumers.id');
        $query->leftJoin('tbl_post_order_stage', 'tbl_cart_orders.pos_id', '=', 'tbl_post_order_stage.pos_id');
        $query->leftJoin('tbl_countries', 'tbl_cart_orders.country', '=', 'tbl_countries.id');
        $query->leftJoin('tbl_states', 'tbl_cart_orders.state', '=', 'tbl_states.id');
        if ($user > 0) {
            $query->where('uid', $user);
        }
        $query->select(
            'tbl_cart_orders.*',
            'tbl_products.procat_id',
            'tbl_products.pro_id',
            'tbl_products.uid as userid',
            'tbl_products.user_type',
            'tbl_products.name as pname',
            'consumers.name as cname',
            'tbl_post_order_stage.stage as stage',
            'tbl_countries.name as countryname',
            'tbl_states.name as statename',
        );
        $orders = $query->get();

        // echo json_encode($orders);
        // exit();

        $total = count($orders);

        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th width="2"></th>';
            $formstable .= '<th>Enquiry Name</th>';
            $formstable .= '<th>Order Number</th>';
            $formstable .= '<th>Product</th>';
            $formstable .= '<th>Quantity</th>';
            $formstable .= '<th>Total Amount</th>';
            $formstable .= '<th>Customer</th>';
            $formstable .= '<th>Post Order Stage</th>';
            $formstable .= '<th>Shipping Date</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($orders as $formdetails) {


                if ($formdetails->user_type == 1) {
                    $userid = $formdetails->userid;
                    $user = Admin::with('currency')->find($userid);
                } else {
                    $userid = $formdetails->userid;
                    $user = User::with('currency')->find($userid);
                }

                // echo json_encode($user);
                // exit();


                $formstable .= '<tr>';
                $formstable .= '<td><input type="checkbox" class="checkAll" id="' . $formdetails->coid . '"></td>';
                //  
                $formstable .= '<td><a href="' . url('admin/ecommerce/' . $formdetails->coid) . '">' . $formdetails->name . '</td>';
                $formstable .= '<td><a href="' . url('admin/ecommerce/' . $formdetails->coid) . '">' . $formdetails->number . '</td>';
                $formstable .= '<td><a href="' . url('admin/ecommerce/' . $formdetails->coid) . '">' . $formdetails->pname . '</td>';
                $formstable .= '<td>' . $formdetails->quantity . '</td>';
                $formstable .= '<td><span>' . $user->currency->html_code . '</span>&nbsp;' . $formdetails->total_amount . '</td>';
                $formstable .= '<td>' . $formdetails->cname . '</td>';
                $formstable .= '<td>' . $formdetails->stage . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->shipping_date)) . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item text-default text-btn-space" href="' . url('admin/ecommerce/' . $formdetails->coid) . '/edit">Edit</a>
                    <a class="dropdown-item text-default text-btn-space" href="#">Delete</a>
                  </div>
                </div>';
                //  ' . url('admin/accounts/' . $formdetails->coid . '/edit') . '
                // ' . url('admin/accounts/delete/' . $formdetails->coid) . '
                $formstable .= '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }
        $data['table'] = $formstable;
        $data['total'] = $total;

        return $data;
    }

    public function getConsumersList()
    {
        $consumers = $this->getConsumers();
        // echo json_encode($consumers);
        $total = count($consumers);

        if (count($consumers) > 0) {
            $usertable = '<table id="usersTable" class="table">';
            $usertable .= '<thead>';
            $usertable .= '<tr>';
            $usertable .= '<th>Name</th>';
            $usertable .= '<th>Email</th>';
            $usertable .= '<th>Mobile</th>';
            $usertable .= '<th>Status</th>';
            $usertable .= '<th>Email Verification</th>';
            $usertable .= '<th>Created</th>';
            // $usertable .= '<th>Action</th>';
            // $usertable .= '<th>Permissions</th>';
            $usertable .= '</tr>';
            $usertable .= '</thead>';
            $usertable .= '<tbody>';
            foreach ($consumers as $userdetails) {
                $status = ($userdetails->active == 1) ? "Active" : "Blocked";
                $btnstatus = ($userdetails->active == 1) ? "Block" : "Active";
                $bstatus = ($userdetails->active == 0) ? 1 : 0;
                $emailverification = ($userdetails->verified == 1) ? '<small class="text-success badge badge-success"><i class="fa fa-check" aria-hidden="true"></i> Verivified</small>' : '<small class="text-danger badge badge-danger"><i class="fa fa-times" aria-hidden="true"></i> Not Verivified</small>';

                $userimg = ($userdetails->picture != '') ? $userdetails->picture : 'uploads/default/user.png';

                $usertable .= '<tr>';
                $usertable .= '<td><a href="' . url('admin/ecommerce/consumers/show/' . $userdetails->id) . '"><img src="' . url($userimg) . '" class="avatar" style="width:25px; height:25px;">' . $userdetails->name . '</a>&nbsp;</td>';
                $usertable .= '<td><a href="#">' . $userdetails->email . '</a></td>';
                //' . url('admin/mails/mailsend/admins/' . $userdetails->id) . '
                $usertable .= '<td>' . $userdetails->mobile . '</td>';
                // $usertable .= '<td>' . $jobTitle . '</td>';
                $usertable .= '<td>' . $status . '</td>';
                $usertable .= '<td>' . $emailverification . '</td>';
                $usertable .= '<td>' . date('d-m-Y', strtotime($userdetails->created_at)) . '</td>';
                // $usertable .= '<td>';
                // $usertable .= '<div class="btn-group">
                //       <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                //     <span class="caret"></span>
                //   </button>
                //       <div class="dropdown-menu">
                //         <a class="dropdown-item text-default text-btn-space" href="' . url('admin/ecommerce/consumers/edit/' . $userdetails->id) . '">Edit</a>
                //         <a class="dropdown-item text-default text-btn-space" href="' . url('admin/ecommerce/consumers/block/' . $userdetails->id . '/' . $bstatus) . '" onclick="event.preventDefault(); document.getElementById("block-form").submit();">' . $btnstatus . '</a>
                //         <form id="block-form" action="' . url('admin/admins/block/' . $userdetails->id . '/' . $bstatus) . '" method="POST" style="display: none;">
                //             @csrf
                //         </form>
                //       </div>
                //     </div>';
                // $usertable .= '</td>';
                // $usertable .= '<td><a href="' . url('admin/admins/setpermit/' . $userdetails->id) . '" class="btn btn-default">Set Permissions</a></td>';
                $usertable .= '</tr>';
            }
            $usertable .= '</tbody>';
            $usertable .= '</table>';
        } else {
            $usertable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $usertable;

        return view('admin.ecommerce.consumers')->with('data', $data);
    }


    public function showConsumer($cid)
    {
        $adminDetails = Consumer::find($cid);
        // echo json_encode($adminDetails);
        $data['userdata'] = $adminDetails;
        return view('admin.ecommerce.showconsumer')->with('data', $data);
    }

    public function getProductDetails($id)
    {
        $products = Tbl_products::with('Tbl_units')->with('Tbl_productcategory')->find($id);
        //    echo json_encode($products);
        //    exit();

        $data['product'] = $products;
        $uid = '';
        $user = '';
        if ($products->user_type == 1) {
            $uid = $products->uid;
            $user = Admin::with('currency')->find($uid);
        } else {
            $uid = $products->uid;
            $user = User::with('currency')->find($uid);
        }

        $data['user'] = $user;

        return $data;
    }
}

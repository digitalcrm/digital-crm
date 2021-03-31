<?php

namespace App\Http\Controllers;

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

        // // $consumer = Consumer::find($cid);
        // // $data['consumer'] = $consumer;
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

        return view('auth.ecommerce.show')->with('data', $data);
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

        return view('auth.ecommerce.edit')->with('data', $data);
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
        $this->validate($request, [
            //  unique:tbl_units,name,' . $id . ',unit_id
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

        // echo json_encode($data);
        // exit();

        $formdata = array(
            // 'uid' => 0, //$uid
            'pos_id' => $data['pos_id'],
            'number' => $data['number'],
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            // 'pro_id' => $id,
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
            return redirect('ecommerce/' . $id)->with('success', 'Updated Successfully...!');
        } else {
            return redirect('ecommerce/' . $id)->with('error', 'Failed. Try again later...!');
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


    public function getOrders()
    {
        $uid = Auth::user()->id;
        // echo $uid;
        // exit();
        $orders = $this->getOrdersList($uid);
        return view('auth.ecommerce.orders')->with('data', $orders);
    }

    public function getOrdersList($user)
    {

        // echo $user;
        // exit();

        // $cr_id = Auth::user()->cr_id;
        // $currency = currency::find($cr_id);

        // $orders = Tbl_cart_orders::with('tbl_products')->where('uid', $user)->with('tbl_post_order_stage')->where('active', 1)->where('user_type', 2)->get();

        // // echo json_encode($orders);
        // // exit();

        // $total = count($orders);

        // if ($total > 0) {
        //     $formstable = '<table id="example1" class="table">';
        //     $formstable .= '<thead>';
        //     $formstable .= '<tr>';
        //     $formstable .= '<th width="2"></th>';
        //     $formstable .= '<th>Enquiry Name</th>';
        //     $formstable .= '<th>Order Number</th>';
        //     $formstable .= '<th>Product</th>';
        //     $formstable .= '<th>Quantity</th>';
        //     $formstable .= '<th>Total Amount</th>';
        //     // $formstable .= '<th>Customer</th>';
        //     $formstable .= '<th>Post Order Stage</th>';
        //     $formstable .= '<th>Shipping Date</th>';
        //     $formstable .= '<th>Date</th>';
        //     $formstable .= '<th>Action</th>';
        //     $formstable .= '</tr>';
        //     $formstable .= '</thead>';
        //     $formstable .= '<tbody>';
        //     foreach ($orders as $formdetails) {


        //         // if ($formdetails->user_type == 1) {
        //         //     $userid = $formdetails->userid;
        //         //     $user = Admin::with('currency')->find($userid);
        //         // } else {
        //         //     $userid = $formdetails->userid;
        //         $user = User::with('currency')->find($user);
        //         // }

        //         // echo json_encode($user);
        //         // exit();

        //         $tbl_post_order_stage = ($formdetails->tbl_post_order_stage != '') ? $formdetails->tbl_post_order_stage->stage : '';

        //         $formstable .= '<tr>';
        //         $formstable .= '<td><input type="checkbox" class="checkAll" id="' . $formdetails->coid . '"></td>';
        //         //  
        //         $formstable .= '<td><a href="' . url('ecommerce/' . $formdetails->coid) . '">' . $formdetails->name . '</td>';
        //         $formstable .= '<td><a href="' . url('ecommerce/' . $formdetails->coid) . '">' . $formdetails->number . '</td>';
        //         $formstable .= '<td><a href="' . url('ecommerce/' . $formdetails->coid) . '">' . $formdetails->tbl_products->name . '</td>';
        //         $formstable .= '<td>' . $formdetails->quantity . '</td>';
        //         $formstable .= '<td><span>' . $currency->html_code . '</span>&nbsp;' . $formdetails->total_amount . '</td>';
        //         // $formstable .= '<td>' . $formdetails->cname . '</td>';
        //         $formstable .= '<td>' . $tbl_post_order_stage . '</td>';
        //         $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->shipping_date)) . '</td>';
        //         $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
        //         $formstable .= '<td>';
        //         $formstable .= '<div class="btn-group">
        //           <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
        //             <span class="caret"></span>
        //           </button>
        //           <div class="dropdown-menu">
        //             <a class="dropdown-item text-default text-btn-space" href="' . url('ecommerce/' . $formdetails->coid) . '/edit">Edit</a>
        //             <a class="dropdown-item text-default text-btn-space" href="#">Delete</a>
        //           </div>
        //         </div>';
        //         //  ' . url('admin/ecommerce/' . $formdetails->coid) . '/edit
        //         //  ' . url('admin/accounts/' . $formdetails->coid . '/edit') . '
        //         // ' . url('admin/accounts/delete/' . $formdetails->coid) . '
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

        // return $data;


        $query = DB::table('tbl_cart_orders');
        $query->where('tbl_cart_orders.active', 1);
        $query->leftJoin('tbl_products', 'tbl_cart_orders.pro_id', '=', 'tbl_products.pro_id');
        $query->leftJoin('tbl_post_order_stage', 'tbl_cart_orders.pos_id', '=', 'tbl_post_order_stage.pos_id');
        $query->leftJoin('tbl_countries', 'tbl_cart_orders.country', '=', 'tbl_countries.id');
        $query->leftJoin('tbl_states', 'tbl_cart_orders.state', '=', 'tbl_states.id');
        $query->where('tbl_products.uid', $user);
        $query->where('tbl_products.user_type', 2);
        $query->orderByDesc('tbl_cart_orders.coid');
        $query->select(
            'tbl_cart_orders.*',
            'tbl_products.procat_id',
            'tbl_products.pro_id',
            'tbl_products.uid as userid',
            'tbl_products.user_type',
            'tbl_products.name as pname',
            'tbl_products.vendor',
            'tbl_products.size',
            'tbl_products.price',
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
            $formstable .= '<th>Lead</th>';
            $formstable .= '<th>Order Number</th>';
            $formstable .= '<th>Product</th>';
            $formstable .= '<th>Brand</th>';
            $formstable .= '<th>Quantity</th>';
            $formstable .= '<th>Total Amount</th>';
            $formstable .= '<th>Post Order Stage</th>';
            $formstable .= '<th>Shipping Date</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($orders as $formdetails) {


                $userid = $formdetails->userid;
                $user = User::with('currency')->find($userid);

                // echo json_encode($user);
                // exit();


                $formstable .= '<tr>';
                $formstable .= '<td><input type="checkbox" class="checkAll" id="' . $formdetails->coid . '"></td>';
                //  ' . url('leads/order/' . $formdetails->coid) . '
                // url('leads/product/' . $formdetails->coid)
                $formstable .= '<td><a href="' . url('ecommerce/' . $formdetails->coid) . '">' . $formdetails->name . '</a></td>';
                $formstable .= '<td>' . $formdetails->number . '</td>';
                $formstable .= '<td>' . $formdetails->pname . '</td>';
                $formstable .= '<td>' . $formdetails->vendor . '</td>';
                $formstable .= '<td>' . $formdetails->quantity . '</td>';
                $formstable .= '<td><span>' . $user->currency->html_code . '</span>&nbsp;' . $formdetails->total_amount . '</td>';
                $formstable .= '<td>' . $formdetails->stage . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->shipping_date)) . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item text-default text-btn-space" href="' . url('ecommerce/' . $formdetails->coid) . '/edit">Edit</a>
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

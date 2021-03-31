<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Models
use App\Tbl_countries;
use App\Tbl_states;
use App\Tbl_cart_orders;
use App\currency;
use App\Admin;
use App\User;

// echo json_encode($products);
// exit();



?>
@if(count($products) > 0)
<div class="row">


    @foreach ($products as $k => $formdetails)
    <?php
    $img = ($formdetails->picture) ? $formdetails->picture : '/uploads/default/products.jpg';
    if ($formdetails->user_type == 1) {
        $userId = $formdetails->uid;
        $userdt = Admin::with('currency')->find($userId);
    }
    if ($formdetails->user_type == 2) {
        $userId = $formdetails->uid;
        $userdt = User::with('currency')->find($userId);
    }
    
    $featured = ($formdetails->featured > 0) ? 'featured-product' : '';


    //  consumers
    // url('shop/ajax/getproductdetails/'.$formdetails->pro_id)
    ?>
    <div class="col-lg-3 col-md-3 mb-3">
        <div class="cardd border h-100 card-shop">
            <a href="{{url('shop/product/'.$formdetails->slug)}}" target="_blank">
			<img class="card-img-top card-img-top-custom {{$featured}}" src="{{url($img)}}" alt="" width="301" height="172">
			</a>
            <div class="card-body">
                <div class="small card-text">{{$formdetails->vendor}}</div>
                <h6 class="productName">
                    <a href="{{url('shop/product/'.$formdetails->slug)}}" target="_blank">{{substr(strip_tags($formdetails->name),0,60).'...'}}</a>
                </h6>
                <!-- <p class="">{{substr(strip_tags($formdetails->description),0,60).'...'}}</p> -->
                <p class="font-weight-bold"><span>{!!$userdt->currency->html_code!!}</span>&nbsp;{{$formdetails->price}}</p>

            </div>
            <!-- <div class="card-footer"> -->
            <!-- <a href="{{url('consumers/ajax/buynow/' . $formdetails->pro_id)}}" class="btn btn-warning"><i class="fas fa-bolt"></i>&nbsp;Buy Now</a> -->
            <!-- <a href="#" onclick="return buynowProduct({!!$formdetails->pro_id!!})" class="btn btn-warning"><i class="fas fa-bolt"></i>&nbsp;Buy Now</a> -->
            <!-- </div> -->
        </div>
    </div>
    @endforeach
</div>

<div id="appendRow" class="d-flex justify-content-center mt-3">
    {!! $products->links() !!}
</div>

@else
<div>
    No products Available
</div>
@endif
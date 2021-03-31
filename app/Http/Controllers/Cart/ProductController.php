<?php

namespace App\Http\Controllers\Cart;

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
use Session;


class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('consumerauth:cart');
    }

    //
    public function index()
    {
        // echo 'Welcome Products';
        // exit();
        //
        $skipRec = 0;
        $min_price = 0;
        $max_price = 0;
        $products = $this->getProducts($skipRec, $min_price, $max_price);
        // echo json_encode($products);
        // exit();

        $maxPrice = $this->getProductMaxPrice();
        $details['maxPrice'] = $maxPrice;

        return view('cart.products.index', compact('products'))->with('details', $details);

        // return view('cart.products.index');
    }

    public function getProducts($skipRec, $min_price, $max_price)
    {
        $addtoCartUrl = url('consumers/login'); //consumers/ajax/ajaxaddtocart/{proId}
        if (Auth::user() != '') {
            $addtoCartUrl = url('consumers/ajax/addtocart/{proId}');
        }

        $defaultCurrency = currency::where('status', 1)->first();

        $query = DB::table('tbl_products')->where('tbl_products.active', 1)->where('tbl_products.store', 1);
        $query->leftJoin('tbl_product_category', 'tbl_products.procat_id', '=', 'tbl_product_category.procat_id');
        $query->leftJoin('tbl_units', 'tbl_products.unit', '=', 'tbl_units.unit_id');
        if ($skipRec > 0) {
            $query->skip($skipRec);
        }
        if (($min_price >= 0) && ($max_price > 0)) {
            $query->whereBetween('price', [$min_price, $max_price]);
        }
        $query->orderBy('tbl_products.pro_id', 'DESC');
        // $query->limit(8);
        // $query->paginate(8);
        $query->select(
            'tbl_products.*',
            'tbl_product_category.procat_id',
            'tbl_product_category.category',
            'tbl_units.name as uname',
            'tbl_units.sortname'
        );
        // $products = $query->get();
        $products = $query->paginate(8);
        return $products;
    }

    public function getProductMaxPrice()
    {
        $max = Tbl_products::max('price');
        return $max;
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use OpenGraph;
//---------Models---------------
use App\Tbl_productcategory;
use App\Tbl_product_subcategory;
use App\Tbl_products;
use App\Tbl_user_cart;
use App\currency;
use App\Admin;
use App\User;
use App\Tbl_units;
use App\Tbl_countries;
use App\Tbl_states;
use App\Tbl_cart_orders;
use App\Tbl_admin_notifications;
use App\Tbl_notifications;
use App\Tbl_leads;
use App\Company;
use App\Tbl_search_keywords;

class ConsumerController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:consumer');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $uid = Auth::user()->id;
        // echo json_encode(Auth::user());
        // exit();
        // $sessionData = $this->accessSessionData();

        //
        $skipRec = 0;
        $min_price = 0;
        $max_price = 0;
        $procatId = 0;
        $prosubcatId = 0;
        $keyword = '';
        $sortby = 'recent';
        $products = $this->getProducts($skipRec, $min_price, $max_price, $procatId, $prosubcatId, $keyword, $sortby);
        // echo json_encode($products);
        // exit();
        // $proCartCount = $this->getUserCartProducts($uid);
        // $products['proCartCount'] = $products;

        $maxPrice = $this->getProductMaxPrice();
        $details['maxPrice'] = $maxPrice;

        // $procatOptions = $this->getProductCategoryOptions(0);
        // $details['procatOptions'] = $procatOptions;

        $procatMenu = $this->getProductCategoryMenu();
        $details['procatMenu'] = $procatMenu;

        $details['keyword'] = $keyword;

        return view('consumer.dashboard', compact('products'))->with('details', $details);
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
        //
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

    // logout function
    public function logout(Request $request)
    {
        Auth::guard('consumer')->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect('shop');    //consumers
    }


    public function profileView()
    {
        $data['userdata'] = Auth::user();
        return view('consumer.profile.profile')->with('data', $data);
    }

    public function getProducts($skipRec, $min_price, $max_price, $procatId, $prosubcatId, $keyword, $sortby)
    {

        // echo $min_price . ' ' . $max_price . ' ' . $procatId . ' ' . $prosubcatId . ' ' . $keyword . ' ' . $sortby;
        // exit();

        $proUrl = url('/');
        $catVal = '';
        $subcatVal = '';

        if (($procatId == 0) && ($keyword != '')) {

            $catVal = $this->findProCategoryByKeyword($keyword);
            $procatId = (($catVal != '')) ? $catVal->procat_id : 0; //   && ($catVal > 0)
            // echo json_encode($catVal);
            // exit();
        }

        if (($prosubcatId == 0) && ($keyword != '')) {
            $subcatVal = $this->findProSubCategoryByKeyword($keyword);
            $prosubcatId = (($subcatVal != '')) ? $subcatVal->prosubcat_id : 0; //    && ($subcatVal > 0)
            // echo json_encode($subcatVal);
            // exit();
        }

        // echo $procatId . ' ' . $prosubcatId;
        // exit();
        //  DB::table('tbl_products')->
        $query = Tbl_products::where('tbl_products.procat_id', '>', 0)->where('tbl_products.user_type', 2)->where('tbl_products.prosubcat_id', '>', 0)->where('tbl_products.company', '>', 0)->where('tbl_products.user_type', 2)->with('users.currency');
        $query->join('companies', 'tbl_products.company', '=', 'companies.id');
        $query->leftJoin('tbl_product_category', 'tbl_products.procat_id', '=', 'tbl_product_category.procat_id');
        $query->leftJoin('tbl_product_subcategory', 'tbl_products.prosubcat_id', '=', 'tbl_product_subcategory.prosubcat_id');
        $query->leftJoin('tbl_units', 'tbl_products.unit', '=', 'tbl_units.unit_id');


        if ($keyword != '') {
            $query->where('tbl_products.company', '>', 0);
            $query->where('tbl_products.active', 1); //  tbl_products.
            $query->where('tbl_products.store', 1);  //  tbl_products.

            $query->where('tbl_products.name', 'like', '%' . $keyword . '%');
            $query->orWhere('tbl_products.vendor', 'like', '%' . $keyword . '%');
            $query->orWhere('tbl_products.tags', 'like', '%' . $keyword . '%');
            if ($procatId > 0) {
                $query->orWhere('tbl_products.procat_id', $procatId);
            }
            if ($prosubcatId > 0) {
                $query->orWhere('tbl_products.prosubcat_id', $prosubcatId);
            }
        }

        if (($min_price >= 0) && ($max_price > 0)) {
            $query->whereBetween('price', [$min_price, $max_price]);
        }

        $query->where('tbl_products.active', 1); //  tbl_products.
        $query->where('tbl_products.store', 1);  //  tbl_products.
        $query->orderByDesc('tbl_products.pro_id');  //  tbl_products.

        // if ($vendors != '') {
        //     $query->union($vendors);
        // }

        // if ($sortby == 'recent') {
        //     $query->orderBy('pro_id', 'DESC');  //  tbl_products.
        // }

        // if ($sortby == 'popular') {
        //     $query->orderBy('views', 'DESC');   //  tbl_products.
        // }

        // if ($sortby == 'priceAsc') {
        //     $query->orderBy('price', 'ASC');    //  tbl_products.
        // }

        // if ($sortby == 'priceDesc') {
        //     $query->orderBy('price', 'DESC');   //  tbl_products.
        // }

        $query->select(
            'tbl_products.*',
            DB::raw('CONCAT("' . $proUrl . '", "", tbl_products.picture) AS picture'),
            'tbl_product_category.procat_id',
            'tbl_product_category.category',
            'tbl_product_category.slug as category_slug',
            'tbl_product_subcategory.prosubcat_id',
            'tbl_product_subcategory.category as subcategory',
            'tbl_product_subcategory.slug as subcategory_slug',
            'tbl_units.name as uname',
            'tbl_units.sortname',
            'companies.id as c_id',
            'companies.c_name',
            'companies.slug as c_slug',
            'companies.city as c_city',
        );

        $products = $query->get();
        // return $products;

        // echo json_encode($products);
        // exit();
        $results = count($products);
        $this->setCountSearchkeyword($keyword, $results);
        if ($results > 0) {
            if ($keyword != '') {
                $products = $query->paginate(50);
                $products->setpath('');
                $products->appends(
                    array(
                        'keyword' => $keyword
                    )
                );
            } else {
                $products = $query->paginate(50);
            }
            return $products;
        } else {
            return $products;
        }

        // echo json_encode($products);
        // exit();

    }

    public function searchProductByVendor($skipRec, $min_price, $max_price, $procatId, $prosubcatId, $keyword, $sortby)
    {
        $proUrl = url('/');
        //DB::table('tbl_products')->
        $vendors = Tbl_products::where('tbl_products.procat_id', '>', 0)->where('tbl_products.prosubcat_id', '>', 0)->where('tbl_products.company', '>', 0)->where('tbl_products.user_type', 2)->with('users.currency')
            ->where('vendor', 'like', '%' . $keyword . '%')->where('active', 1)->where('store', 1);
        $vendors->join('companies', 'tbl_products.company', '=', 'companies.id');
        $vendors->leftJoin('tbl_product_category', 'tbl_products.procat_id', '=', 'tbl_product_category.procat_id');
        $vendors->leftJoin('tbl_product_subcategory', 'tbl_products.prosubcat_id', '=', 'tbl_product_subcategory.prosubcat_id');
        $vendors->leftJoin('tbl_units', 'tbl_products.unit', '=', 'tbl_units.unit_id');
        $vendors->select(
            'tbl_products.*',
            DB::raw('CONCAT("' . $proUrl . '", "", tbl_products.picture) AS picture'),
            'tbl_product_category.procat_id',
            'tbl_product_category.category',
            'tbl_product_category.slug as category_slug',
            'tbl_product_subcategory.prosubcat_id',
            'tbl_product_subcategory.category as subcategory',
            'tbl_product_subcategory.slug as subcategory_slug',
            'tbl_units.name as uname',
            'tbl_units.sortname',
            'companies.id as c_id',
            'companies.c_name',
            'companies.slug as c_slug',
            'companies.city as c_city',
        );

        $products = $vendors->get();

        if (count($products) > 0) {
            foreach ($products as $pro) {
                if ($pro->user_type == 1) {
                    $uid = $pro->uid;
                    $user = Admin::with('currency')->find($uid);
                    $pro->currency = $user->currency;
                } else {
                    $uid = $pro->uid;
                    $user = User::with('currency')->find($uid);
                    $pro->currency = $user->currency;
                }
                // $data[] = $pro;
            }
        }
        $results = count($products);
        $this->setCountSearchkeyword($keyword, $results);
        if ($results > 0) {
            if ($keyword != '') {
                $products = $vendors->paginate(50);
                $products->setpath('');
                $products->appends(
                    array(
                        'keyword' => $keyword
                    )
                );
            } else {
                $products = $vendors->paginate(50);
            }
            return $products;
        } else {
            return $products;
        }
    }


    public function productBuyNow($data, $id)
    {

        $productDetails = Tbl_products::find($id);

        // echo json_encode($productDetails);
        // exit();

        $uid = $productDetails->uid;
        $user_type = $productDetails->user_type;
        // $uid = 0;
        // if (Auth::user()) {
        //     $uid = Auth::user()->id;
        // }

        $country = ($data['country'] > 0) ? $data['country'] : 0;
        $state = 0;    //($data['state'] > 0) ? $data['state'] : 0
        $total_amount = $data['price'] * $data['quantity'];


        $filename = '';

        $leaddata = array(
            'uid' => $uid,
            'first_name' => $data['name'],
            'last_name' => '',
            'email' => $data['email'],
            'picture' => $filename,
            'mobile' => $data['mobile'],
            'phone' => '',
            'ldsrc_id' => 0,
            'ldstatus_id' => 0,
            'intype_id' => 0,
            'acc_id' => 0,
            'notes' => $data['message'],
            'website' => '',
            'country' => $country,
            'state' => $state,
            'city' => $data['city'],
            'street' => $data['address'],
            'zip' => '',
            'company' => $productDetails->company,
            'sal_id' => 0,
            'designation' => '',
            'pro_id' => $id,
            'leadtype' => 2
        );
        $lead = Tbl_leads::create($leaddata);

        if ($lead) {
            $ld_id = $lead->ld_id;

            $formdata = array(
                'uid' => $uid, //$uid
                'user_type' => $user_type,
                'name' => $data['name'],
                'email' => $data['email'],
                'mobile' => $data['mobile'],
                'pro_id' => $id,
                'order_date' => date('Y-m-d'),
                'shipping_date' => date('Y-m-d'),
                'delivery_charges' => 0,
                'total_amount' => $total_amount, //$data['total_amount']
                'quantity' => $data['quantity'],
                'price' => $data['price'],
                'remarks' => '',
                'address' => $data['address'],
                'country' => $country,
                'state' => $state,
                'city' => $data['city'],
                'zip' => '',    //  $data['zip']
                'message' => $data['message'],
                'ld_id' => $ld_id
            );

            $res = Tbl_cart_orders::create($formdata);
            return $res;
        } else {
            return false;
        }
    }


    public function productAddtoCart($id)
    {
        $uid = Auth::user()->id;
        // echo "Add to cart page is under development";

        $cartArr = array(
            'uid' => $uid,
            'pro_id' => $id,
            'status' => 0,
            'quantity' => 1
        );

        $exists = Tbl_user_cart::where('status', 0)->where('uid', $uid)->where('pro_id', $id)->first();

        if ($exists != '') {
            return 'exist';
        } else {

            $cart = Tbl_user_cart::where('pro_id', $id)->where('status', 1)->firstOrCreate($cartArr);
            if ($cart->uc_id > 0) {
                return 'success';
            } else {
                return 'error';
            }
        }
    }

    public function getUserCartProductsList()
    {
        $carts = $this->getUserCartProducts();
        // echo json_encode($carts);


        if ($carts != '') {
            $cartDiv = '<ul class="products-list product-list-in-card pl-2 pr-2">';
            foreach ($carts as $cart) {

                $cpicture = ($cart->tbl_products->picture != '') ? $cart->tbl_products->picture : '/uploads/default/products.jpg';

                $cartDiv .= '<li class="item">';
                $cartDiv .= '<div class="product-img">';
                $cartDiv .= '<img src="' . url($cpicture) . '" alt="Product" class="img-size-50">';
                $cartDiv .= '</div>';
                $cartDiv .= '<div class="product-info">';
                $cartDiv .= '<a href="javascript:void(0)" class="product-title">' . $cart->tbl_products->name;
                $cartDiv .= '<span class="badge badge-warning float-right">$' . $cart->tbl_products->price . '</span></a>';
                $cartDiv .= '<span class="product-description">';
                $cartDiv .= $cart->tbl_products->description;
                $cartDiv .= '</span>';
                $cartDiv .= '</div>';
                $cartDiv .= '</li>';
            }
            $cartDiv .= '</ul>';
        } else {
            $cartDiv = 'No Records available...!';
        }

        $data['cartDiv'] = $cartDiv;

        return view('consumer.products.cart')->with('data', $data);
    }

    public function getUserCartProducts()
    {
        $uid = Auth::user()->id;

        $carts  = Tbl_user_cart::where('uid', $uid)->where('status', 0)->with('Tbl_products')->get();
        // echo json_encode($carts);
        // exit();

        return $carts;
    }

    public function ajaxUserCartProducts(Request $request)
    {
        // return json_encode($request->input());

        $carts = $this->getUserCartProducts();

        return ($carts != '') ? count($carts) : 0;
    }

    public function ajaxAddtoCart(Request $request)
    {

        $proId = $request->input('proId');
        // return json_encode(Auth::user());
        // exit();

        $uid = Auth::user();
        $res = $this->productAddtoCart($proId);
        $proCount = 0;
        if ($res == 'success') {
            $proCart = $this->getUserCartProducts($uid);
            $proCount = ($proCart != '') ? count($proCart) : 0;
        }
        $data['status'] = $res;
        $data['proCount'] = $proCount;

        return json_encode($data);
    }

    public function getProductDetails($id)
    {
        $products = Tbl_products::with('Tbl_units')->with('Tbl_productcategory')->with('Tbl_product_subcategory')->where('active', 1)->with('users.currency')->find($id);

        if ($products != '') {

            // echo json_encode($products);
            // exit();

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

            //Add Product View

            $this->productAddView($id);

            //  Slide Show
            $slides = ($products['slideshowpics'] != '') ? explode(",", $products['slideshowpics']) : array('/uploads/default/products.jpg');
            $itemli = '';
            $itemdiv = '';
            $k = 0;

            foreach ($slides as $slide) {
                $liactive = ($k == 0) ? 'active' : '';
                $itemli .= '<li data-target="#carousel-custom" data-slide-to="' . $k . '" class="' . $liactive . '">';
                $itemli .= '<img class="" height="50" width="100" src="' . url($slide) . '" alt="Product">';
                $itemli .= '</li>';
                //
                $itemdiv .= '<div class="carousel-item ' . $liactive . '">';
                $itemdiv .= '<img class="d-block w-100" src="' . url($slide) . '" alt="Product">';
                $itemdiv .= '</div>';

                //

                $k++;
            }

            $slide = '';
            $slide .= '<div id="carousel-custom" class="carousel slide" data-ride="carousel">';
            $slide .= '<div class="carousel-outer">';
            $slide .= '<div class="carousel-inner">';
            $slide .= $itemdiv;
            $slide .= '</div>';
            $slide .= '<a class="carousel-control-prev" href="#carousel-custom" role="button" data-slide="prev">';
            $slide .= '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
            $slide .= '<span class="sr-only">Previous</span>';
            $slide .= '</a>';
            $slide .= '<a class="carousel-control-next" href="#carousel-custom" role="button" data-slide="next">';
            $slide .= '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
            $slide .= '<span class="sr-only">Next</span>';
            $slide .= '</a>';
            $slide .= '</div>';
            $slide .= '<ol class="carousel-indicators mCustomScrollbar">';
            $slide .= $itemli;
            $slide .= '</ol>';
            $slide .= '</div>';


            $data['slide'] = $slide;
            return $data;
        } else {
            return '';
        }
    }

    public function getProductMaxPrice()
    {
        $max = Tbl_products::max('price');
        return $max;
    }

    public function buyNowProduct($pro_id)
    {
        // echo 'Page is under development...';

        $product = $this->getProductDetails($pro_id);
        // echo json_encode($product);
        // exit();

        $country = Tbl_countries::all();
        $countryoptions = "<option value='0'>Select Country</option>";
        if (count($country) > 0) {
            foreach ($country as $cnt) {
                $countryoptions .= "<option value='" . $cnt->id . "'>" . $cnt->name . ' - ' . $cnt->sortname . "</option>";
            }
        }
        $product['countryoptions'] = $countryoptions;

        return view('consumer.products.buynow')->with('data', $product);
    }

    public function buyNowProductSlug($slug)
    {
        // echo 'Page is under development...';

        $product = $this->getProductDetailsSlug($slug);
        // echo json_encode($product);
        // exit();

        $country = Tbl_countries::all();
        $countryoptions = "<option value='0'>Select Country</option>";
        if (count($country) > 0) {
            foreach ($country as $cnt) {
                $countryoptions .= "<option value='" . $cnt->id . "'>" . $cnt->name . ' - ' . $cnt->sortname . "</option>";
            }
        }
        $product['countryoptions'] = $countryoptions;


        return view('consumer.products.buynow')->with('data', $product);
    }

    public function buyNowAction(Request $request, $id)
    {
        // echo json_encode($request->input());

        $uid = Auth::user()->id;

        // $formdata = array(
        //     'uid' => $uid,
        //     'pro_id' => $id,
        //     'order_date' => date('Y-m-d'),
        //     'shipping_date' => date('Y-m-d'),
        //     'delivery_charges' => 0,
        //     'total_amount' => $request->input('total_amount'),
        //     'quantity' => $request->input('quantity'),
        //     'price' => $request->input('price'),
        //     'remarks' => '',
        //     'address' => $request->input('address'),
        //     'country' => $request->input('country'),
        //     'state' => $request->input('state'),
        //     'city' => $request->input('city'),
        //     'zip' => $request->input('zip'),
        // );

        // echo json_encode($formdata);

        // $res = Tbl_cart_orders::create($formdata);

        // if ($res->coid > 0) {
        //     return redirect('consumers/ajax/buynow/' . $id)->with('success', 'Order is taken. Thank you for contacting...!');
        // } else {
        //     return redirect('consumers/ajax/buynow/' . $id)->with('error', 'Error occurred. Please try again later...!');
        // }
    }


    public function productPages($skipRec, $min_price, $max_price)
    {

        // $skipRec = 0;
        // $min_price = 0;
        // $max_price = 0;

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
        $query->paginate(8);
        $query->select(
            'tbl_products.*',
            'tbl_product_category.procat_id',
            'tbl_product_category.category',
            'tbl_units.name as uname',
            'tbl_units.sortname'
        );
        $products = $query->get();

        return $products;
    }

    public function ajaxBuyNow($proId)
    {
        // if (Auth::user()) {
        //     return url('consumers/ajax/buynow/' . $proId);
        // } else {
        return url('shop/login');  // consumers
        // }
    }

    public function checkAuth(Request $request)
    {
        if (Auth::user()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function productAddView($id)
    {
        $product = Tbl_products::find($id);
        $views = $product->views;
        $views += 1;
        $product->views = $views;
        $product->save();
    }

    public function getProductSiteMap()
    {
        $procats = Tbl_productcategory::with('tbl_product_subcategory')->get(); //  with('tbl_products')->
        $data['procats'] = $procats;
        // echo json_encode($procats);
        // exit();

        if ($procats != '') {
            $oneli = '';
            $twoli = '';
            $kli = 1;

            foreach ($procats as $procat) {
                $lielement = '<ul>';
                $lielement .= '<li><a href="' . url('shop/sitemap/products/category/' . $procat->slug) . '"><strong>' . ucfirst($procat->category) . '</strong></a>';
                $lielement .= '<div style="float:right;"><a href="' . url('shop/sitemap/productcategory/feed/' . $procat->slug) . '"><span class="badge badge-primary">Rss</span></a></div>';
                $lielement .= '</li>';
                $prosubcats = ($procat->tbl_product_subcategory != '') ? $procat->tbl_product_subcategory : '';
                if ($prosubcats != '') {
                    $lielement .= '<ul>';
                    foreach ($prosubcats as $prosubcat) {
                        $lielement .= '<li><a href="' . url('shop/sitemap/products/subcategory/' . $prosubcat->slug) . '">' . ucfirst($prosubcat->category) . '</a>';
                        $lielement .= '<div style="float:right;"><a href="' . url('shop/sitemap/productsubcategory/feed/' . $prosubcat->slug) . '"><span class="badge badge-primary">Rss</span></a></div>';
                        $lielement .= '</li>';
                        // $products = Tbl_products::where('prosubcat_id', $prosubcat->prosubcat_id)->get();
                        // if ($products != '') {
                        //     $lielement .= '<ul>';
                        //     foreach ($products as $product) {
                        //         $lielement .= '<li><a href="' . url('shop/product/' . $product->slug) . '">' . $product->name . '</a></li>';
                        //     }
                        //     $lielement .= '</ul>';
                        // }
                    }
                    $lielement .= '</ul>';
                }
                $lielement .= '</ul>';

                if (($kli % 2) == 0) {
                    $twoli .= $lielement;
                } else {
                    $oneli .= $lielement;
                }
                $kli++;
            }
        }

        // echo $lielement;
        // exit();

        $data['oneli'] = $oneli;
        $data['twoli'] = $twoli;

        $url = url()->current();
        $ogp = $this->fetchOpenGraphMetadata($url);
        // echo $ogp;
        // exit();

        $metadata['og_title'] = $ogp->title;
        $metadata['og_site_name'] = $ogp->site_name;
        $metadata['og_url'] = $ogp->url;
        $metadata['og_description'] = $ogp->description;
        $data['metadata'] = $metadata;
        return $data;
    }

    public function getProductCategoryOptions($id)
    {
        $procats = Tbl_productcategory::all();

        $catOptions = "<option value='0'>All</option>";
        foreach ($procats as $procat) {
            $catselect = ($procat->procat_id == $id) ? 'selected' : '';
            $catOptions .= "<option value='" . $procat->procat_id . "' " . $catselect . ">" . $procat->category . "</option>";
        }

        return $catOptions;
    }

    public function getProductSubCategoryOptions($id, $procat_id)
    {
        $procats = Tbl_product_subcategory::where('procat_id', $procat_id)->get();

        $catOptions = "<option value='0'>All</option>";
        foreach ($procats as $procat) {
            $catselect = ($procat->prosubcat_id == $id) ? 'selected' : '';
            $catOptions .= "<option value='" . $procat->prosubcat_id . "' " . $catselect . ">" . $procat->category . "</option>";
        }
        return $catOptions;
    }

    public function getSiteMapProductCategory($slug)
    {


        $procats = Tbl_productcategory::where('slug', $slug)->with('tbl_product_subcategory')->first(); //  with('tbl_products')->
        $data['procats'] = $procats;
        // echo json_encode($procats);
        // exit();
        $oneli = '';
        $twoli = '';
        $kli = 1;
        if ($procats != '') {


            // foreach ($procats as $procat) {
            //     $lielement = '<ul>';
            //     $lielement .= '<li><a href="' . url('shop/sitemap/products/category/' . $procat->slug) . '"><strong>' . $procat->category . '</strong></a></li>';
            $prosubcats = ($procats->tbl_product_subcategory != '') ? $procats->tbl_product_subcategory : '';
            if ($prosubcats != '') {
                foreach ($prosubcats as $prosubcat) {
                    $lielement = '<ul>';

                    $lielement .= '<li><a href="' . url('shop/sitemap/products/subcategory/' . $prosubcat->slug) . '"><strong>' . ucfirst($prosubcat->category) . '</strong></a>';
                    $lielement .= '<div style="float:right;"><a href="' . url('shop/sitemap/productsubcategory/feed/' . $prosubcat->slug) . '"><span class="badge badge-primary">Rss</span></a></div>';
                    $lielement .= '</li>';
                    // $products = Tbl_products::where('prosubcat_id', $prosubcat->prosubcat_id)->get();
                    // if ($products != '') {
                    //     $lielement .= '<ul>';
                    //     foreach ($products as $product) {
                    //         $lielement .= '<li><a href="' . url('shop/product/' . $product->slug) . '">' . ucfirst($product->name) . '</a></li>';
                    //     }
                    //     $lielement .= '</ul>';
                    // }
                    $lielement .= '</ul>';
                    if (($kli % 2) == 0) {
                        $twoli .= $lielement;
                    } else {
                        $oneli .= $lielement;
                    }
                    $kli++;
                }
            }

            // }
        }

        // echo $lielement;
        // exit();

        $data['oneli'] = $oneli;
        $data['twoli'] = $twoli;





        $url = url()->current();
        $ogp = $this->fetchOpenGraphMetadata($url);
        // echo $ogp;
        // exit();

        $metadata['og_title'] = $ogp->title;
        $metadata['og_site_name'] = $ogp->site_name;
        $metadata['og_url'] = $ogp->url;
        $metadata['og_description'] = $ogp->description;
        $data['metadata'] = $metadata;
        return $data;
        // return $procats;
    }

    public function getSiteMapProductSubCategory($slug)
    {
        $prosubcat = Tbl_product_subcategory::where('slug', $slug)->first();

        $data['prosubcat'] = $prosubcat;

        $procats = Tbl_productcategory::where('procat_id', $prosubcat->procat_id)->first();
        // echo json_encode($procats);
        $data['procats'] = $procats;

        $products = Tbl_products::where('prosubcat_id', $prosubcat->prosubcat_id)->get();
        // echo json_encode($products);
        $data['products'] =  $products;


        $url = url()->current();
        $ogp = $this->fetchOpenGraphMetadata($url);

        $metadata['og_title'] = $ogp->title;
        $metadata['og_site_name'] = $ogp->site_name;
        $metadata['og_url'] = $ogp->url;
        $metadata['og_description'] = $ogp->description;
        $data['metadata'] = $metadata;

        return $data;
    }


    public function getProductDetailsSlug($slug)
    {
        $data = array();
        // $products = Tbl_products::with('Tbl_units')->with('Tbl_productcategory')->with('Tbl_product_subcategory')->where('active', 1)->find($id);
        $products = Tbl_products::with('Tbl_units')->with('users.currency')
            ->with('Tbl_productcategory')
            ->with('Tbl_product_subcategory')
            ->with('company')
            ->where('active', 1)
            ->where('slug', $slug)
            ->first();
        // return json_encode($products);
        // echo $products->company;
        // exit();
        if ($products != '') {

            // echo json_encode($products);
            // exit();

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
            $data['picture'] = url($products->picture);
            //  Slide show pics
            $slidePics = array();
            $slideshowpics = explode(",", $products->slideshowpics);
            foreach ($slideshowpics as $pic) {
                $slidePics[] = url($pic);
            }
            $data['slidePics'] = $slidePics;

            $company = Company::with('Tbl_countries')->with('Tbl_states')->find($products->company);
            // $data['cdetails'] = $company;
            $data['c_country'] = $company->tbl_countries;
            $data['c_state'] = $company->tbl_states;
        }


        return $data;
    }

    public function fetchOpenGraphMetadata($url)
    {
        // return OpenGraph::fetch('https://digitalcrm.com');

        // echo $url;
        // exit();

        $url = 'https://digitalcrm.com';

        $CURL = 'https://laravelopengraph.herokuapp.com/api/fetch?url=' . $url . '&allMeta=true&language=en_GB';

        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $CURL);
        $contents = curl_exec($c);
        curl_close($c);

        return json_decode($contents);
    }

    public function slugGetProductDetailsRssFeed($slug)
    {
        $productDetails = Tbl_products::where('slug', $slug)->with('Tbl_units')->with('Tbl_productcategory')->with('Tbl_product_subcategory')->first();
        // echo json_encode($productDetails);
        // exit();

        $img = ($productDetails->picture) ? $productDetails->picture : '/uploads/default/products.jpg';
        if ($productDetails->user_type == 1) {
            $userId = $productDetails->uid;
            $userdt = Admin::with('currency')->find($userId);
        }
        if ($productDetails->user_type == 2) {
            $userId = $productDetails->uid;
            $userdt = User::with('currency')->find($userId);
        }

        $data['product'] = $productDetails;
        $data['currency'] = $userdt->currency;
        $data['product_image'] = url($img);
        // echo json_encode($data);
        // exit();

        return $data;
    }

    public function getRssfeedProducts()
    {
        $products = Tbl_products::where('active', 1)->with('Tbl_units')->with('Tbl_productcategory')->with('Tbl_product_subcategory')->get();
        // echo json_encode($products);
        // exit();
        $data = array();
        foreach ($products as $product) {

            $currency = '';
            $img = ($product->picture) ? $product->picture : '/uploads/default/products.jpg';
            if ($product->user_type == 1) {
                $userId = $product->uid;
                $userdt = Admin::with('currency')->find($userId);
                $currency = $userdt->currency;
            }
            if ($product->user_type == 2) {
                $userId = $product->uid;
                $userdt = User::with('currency')->find($userId);
                $currency = $userdt->currency;
            }

            $category = ($product->tbl_productcategory != '') ? $product->tbl_productcategory->category : '';
            $subcategory = ($product->tbl_product_subcategory != '') ? $product->tbl_product_subcategory->category : '';
            $units = ($product->tbl_units != '') ? $product->tbl_units->sortname : '';

            $data[] = array(
                'name' => $product->name,
                'description' => $product->description,
                'size' => $product->size,
                'units' => $units,
                'price' => $currency->html_code . ' ' . $product->price,
                'category' => $category,
                'subcategory' => $subcategory,
                'image' => url($img),
                'slug' => $product->slug,
                'vendor' => $product->vendor
            );
        }
        return $data;
    }

    public function getRssfeedProduct($slug)
    {
        $product = Tbl_products::where('slug', $slug)->with('Tbl_units')->with('Tbl_productcategory')->with('Tbl_product_subcategory')->first();
        // return $products;
        $currency = '';
        $img = ($product->picture) ? $product->picture : '/uploads/default/products.jpg';
        if ($product->user_type == 1) {
            $userId = $product->uid;
            $userdt = Admin::with('currency')->find($userId);
            $currency = $userdt->currency;
        }
        if ($product->user_type == 2) {
            $userId = $product->uid;
            $userdt = User::with('currency')->find($userId);
            $currency = $userdt->currency;
        }

        $category = ($product->tbl_productcategory != '') ? $product->tbl_productcategory->category : '';
        $subcategory = ($product->tbl_product_subcategory != '') ? $product->tbl_product_subcategory->category : '';
        $units = ($product->tbl_units != '') ? $product->tbl_units->sortname : '';

        return array(
            'name' => $product->name,
            'description' => $product->description,
            'size' => $product->size,
            'units' => $units,
            'price' => $currency->html_code . ' ' . $product->price,
            'category' => $category,
            'subcategory' => $subcategory,
            'image' => url($img),
            'slug' => $product->slug,
            'vendor' => $product->vendor
        );
    }

    public function getRssfeedProductCategories()
    {
        $procats = Tbl_productcategory::with('tbl_product_subcategory')->get();
        // echo json_encode($procats);
        // exit();
        return $procats;
    }

    public function getRssfeedProductCategory($slug)
    {
        $procats = Tbl_productcategory::where('slug', $slug)->with('tbl_product_subcategory')->first();
        // echo json_encode($procats);
        // exit();
        return $procats;
    }

    public function getRssfeedProductSubCategory($slug)
    {
        $procats = Tbl_product_subcategory::where('slug', $slug)->with('tbl_products')->first();
        // echo json_encode($procats);
        // exit();
        // return $procats;
        $data = array();
        $data['procat'] = $procats;
        $proArr = array();
        if ($procats->tbl_products != '') {

            $products = $procats->tbl_products;

            foreach ($products as $product) {

                $currency = '';
                $img = ($product->picture) ? $product->picture : '/uploads/default/products.jpg';
                if ($product->user_type == 1) {
                    $userId = $product->uid;
                    $userdt = Admin::with('currency')->find($userId);
                    $currency = $userdt->currency;
                }
                if ($product->user_type == 2) {
                    $userId = $product->uid;
                    $userdt = User::with('currency')->find($userId);
                    $currency = $userdt->currency;
                }


                $proArr[] = array(
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $currency->html_code . ' ' . $product->price,
                    'image' => url($img),
                    'slug' => $product->slug,
                    'vendor' => $product->vendor
                );
            }
        }
        $data['proArr'] = $proArr;
        return $data;
    }


    public function getProductCategoryMenu()
    {
        $procats = Tbl_productcategory::with('Tbl_product_subcategory')->get();
        // echo json_encode($procats);
        // exit();

        $lielement = '';
        foreach ($procats as $procat) {

            $procatId = 'procatId' . $procat->procat_id;
            $procatIdArgs = "'" . $procatId . "'";
            $lielement .= '<li class="nav-item has-treeview catmenu" id="' . $procatId . '" onclick="return proCatMenu(' . $procatIdArgs . ')">';
            $lielement .= '<a href="' . url('/shop/search/products/category/' . $procat->slug) . '" class="nav-link nav-dropdown-toggle">';
            // $lielement .= '<i class="nav-icon far fa-circle icon-size"></i>';
            $lielement .= '<p>';
            $lielement .= ucfirst($procat->category);
            $lielement .= '<i class="fas fa-angle-left right"></i>';
            $lielement .= '</p>';
            $lielement .= '</a>';

            if ($procat->tbl_product_subcategory != '') {
                $prosubcats = $procat->tbl_product_subcategory;
                $lielement .= '<ul class="nav nav-treeview">';
                foreach ($prosubcats as $prosubcat) {
                    $prosubcatId = 'prosubcatId' . $prosubcat->prosubcat_id;
                    $prosubcatIdArgs = "'" . $prosubcatId . "'";
                    $lielement .= '<li class="nav-item subcatmenu" id="' . $prosubcatId . '" onclick="return proSubcatMenu(' . $prosubcatIdArgs . ',' . $procatIdArgs . ')">';
                    $lielement .= '<a href="' . url('/shop/search/products/subcategory/' . $prosubcat->slug) . '"  class="nav-link">'; //onclick="return getSubcatProducts(' . $prosubcat->prosubcat_id . ')"
                    // $lielement .= '<i class="far fa-circle nav-icon icon-size text-danger"></i>';
                    $lielement .= '<p class="sub-nav">' . ucfirst($prosubcat->category) . '</p>';
                    $lielement .= '</a>';
                    $lielement .= '</li>';
                }
                $lielement .= '</ul>';
            }

            $lielement .= '</li>';
        }

        return $lielement;
    }

    public function getProductsbySearch($keyword)
    {

        // echo 'fdslkkf ' . $keyword;
        // exit();
        $skipRec = 0;
        $min_price = 0;
        $max_price = 0;
        $procatId = 0;
        $prosubcatId = 0;
        $sortby = 'recent';
        $products = $this->getProducts($skipRec, $min_price, $max_price, $procatId, $prosubcatId, $keyword, $sortby);
        return $products;
    }

    // public function getProductBrandslist()
    // {
    //     $brands = DB::table('tbl_products')->where('tbl_products.vendor', '!=', null)->distinct('vendor')->select(
    //         'tbl_products.vendor'
    //     )->get();
    //     // return $brands;
    //     if ($brands != '') {
    //         $oneli = '';
    //         $twoli = '';
    //         $threeli = '';
    //         $kli = 1;

    //         foreach ($brands as $brand) {
    //             $lielement = '<ul>';
    //             $lielement .= '<li><a href="' . url('shop/search/product/' . $brand->vendor) . '"><strong>' . ucfirst($brand->vendor) . '</strong></a></li>';
    //             $lielement .= '</ul>';

    //             if (($kli % 3) == 0) {
    //                 $threeli .= $lielement;
    //             } else if (($kli % 2) == 0) {
    //                 $twoli .= $lielement;
    //             } else {
    //                 $oneli .= $lielement;
    //             }
    //             $kli++;
    //         }
    //     }

    //     $data['oneli'] = $oneli;
    //     $data['twoli'] = $twoli;
    //     $data['threeli'] = $threeli;

    //     return $data;
    // }

    public function getSearchProductCategoryList()
    {

        $procats = Tbl_productcategory::all(); //  with('tbl_products')->
        $data['procats'] = $procats;
        // echo json_encode($procats);
        // exit();
        $oneli = '';
        $twoli = '';
        $kli = 1;
        if ($procats != '') {
            foreach ($procats as $procat) {
                $lielement = '<ul>';
                $lielement .= '<li><a href="' . url('shop/search/products/category/' . $procat->slug) . '"><strong>' . ucfirst($procat->category) . '</strong></a>';
                // $lielement .= '<div style="float:right;"><a href="' . url('shop/sitemap/productsubcategory/feed/' . $prosubcat->slug) . '"><span class="badge badge-primary">Rss</span></a></div>';
                $lielement .= '</li>';
                $lielement .= '</ul>';
                if (($kli % 2) == 0) {
                    $twoli .= $lielement;
                } else {
                    $oneli .= $lielement;
                }
                $kli++;
            }
        }

        // echo $lielement;
        // exit();

        $data['oneli'] = $oneli;
        $data['twoli'] = $twoli;
        return $data;
    }

    public function getSearchProductSubCategoryList()
    {
        $procats = Tbl_productcategory::with('Tbl_product_subcategory')->get();
        // $data['procats'] = $procats;
        // echo json_encode($procats);
        // exit();
        $oneli = '';
        $twoli = '';
        $kli = 1;
        if ($procats != '') {
            foreach ($procats as $procat) {
                $lielement = '<ul>';
                $lielement .= '<li><a href="' . url('shop/search/products/category/' . $procat->slug) . '"><strong>' . $procat->category . '</strong></a></li>';
                $prosubcats = ($procat->tbl_product_subcategory != '') ? $procat->tbl_product_subcategory : '';
                if ($prosubcats != '') {
                    foreach ($prosubcats as $prosubcat) {
                        $lielement .= '<ul>';
                        $lielement .= '<li><a href="' . url('shop/search/products/subcategory/' . $prosubcat->slug) . '"><strong>' . ucfirst($prosubcat->category) . '</strong></a>';
                        // $lielement .= '<div style="float:right;"><a href="' . url('shop/sitemap/productsubcategory/feed/' . $prosubcat->slug) . '"><span class="badge badge-primary">Rss</span></a></div>';
                        $lielement .= '</li>';
                        $lielement .= '</ul>';
                    }
                }
                $lielement .= '</ul>';

                if (($kli % 2) == 0) {
                    $twoli .= $lielement;
                } else {
                    $oneli .= $lielement;
                }
                $kli++;
            }
        }

        // echo $lielement;
        // exit();

        $data['oneli'] = $oneli;
        $data['twoli'] = $twoli;
        return $data;
    }
    public function getSearchProductTagsList()
    {
        $procats = Tbl_products::where('active', 1)->where('store', 1)->get(['tags']);
        // $data['procats'] = $procats;
        // echo json_encode($procats);
        // exit();
        $tagsex = array();
        $oneli = '';
        $twoli = '';
        $kli = 1;
        if ($procats != '') {
            foreach ($procats as $procat) {

                $tags = explode(',', $procat->tags);

                foreach ($tags as $tag) {

                    if (!in_array($tag, $tagsex)) {
                        $tagsex[] = $tag;
                        $lielement = '<ul>';
                        $lielement .= '<li><a href="' . url('shop/search/products/tags/' . $tag) . '"><strong>' . ucfirst($tag) . '</strong></a>';
                        // $lielement .= '<div style="float:right;"><a href="' . url('shop/sitemap/productsubcategory/feed/' . $prosubcat->slug) . '"><span class="badge badge-primary">Rss</span></a></div>';
                        $lielement .= '</li>';
                        $lielement .= '</ul>';
                        if (($kli % 2) == 0) {
                            $twoli .= $lielement;
                        } else {
                            $oneli .= $lielement;
                        }
                        $kli++;
                    }
                }
            }
        }


        // echo $lielement;
        // exit();

        $data['oneli'] = $oneli;
        $data['twoli'] = $twoli;
        return $data;
    }

    public function findProCategoryByKeyword($slug)
    {
        $soneq = DB::table('tbl_product_category')
            ->where('tbl_product_category.category', 'like', '%' . $slug . '%')
            ->orWhere('tbl_product_category.slug', 'like', '%' . $slug . '%')
            ->select('tbl_product_category.*')
            ->first();

        if ($soneq != '') {
            return $soneq;
        } else {
            return '';
        }
    }

    public function findProSubCategoryByKeyword($slug)
    {
        $stwoq = DB::table('tbl_product_subcategory')
            ->where('tbl_product_subcategory.category', 'like', '%' . $slug . '%')
            ->orWhere('tbl_product_subcategory.slug', 'like', '%' . $slug . '%')
            ->select('tbl_product_subcategory.*')
            ->first();

        if ($stwoq != '') {
            return $stwoq;
        } else {
            return '';
        }
    }

    // public function getProductsByCategory($id)
    // {
    //     $query = DB::table('tbl_products')
    //         ->where('tbl_products.active', 1)
    //         ->where('tbl_products.store', 1)
    //         ->where('tbl_products.procat_id', $id);
    //     $query->leftJoin('tbl_product_category', 'tbl_products.procat_id', '=', 'tbl_product_category.procat_id');
    //     $query->leftJoin('tbl_units', 'tbl_products.unit', '=', 'tbl_units.unit_id');
    // }


    // getSearchProductCategory

    public function getProductsByCategory($keyword)
    {
        $proUrl = url('/');

        $products = array();
        $soneq = DB::table('tbl_product_category')
            ->where('tbl_product_category.category', 'like', '%' . $keyword . '%')
            ->orWhere('tbl_product_category.slug', 'like', '%' . $keyword . '%')
            ->select('tbl_product_category.procat_id')
            ->first();

        // echo json_encode($soneq);
        // exit();

        if ($soneq != '') {

            $procatId = $soneq->procat_id;

            $defaultCurrency = currency::where('status', 1)->first();

            $products = Tbl_products::has('Tbl_productcategory')->with('users.currency')
                ->has('Tbl_product_subcategory')
                ->has('company')
                ->with('Tbl_units')
                ->with('Tbl_productcategory')
                ->with('Tbl_product_subcategory')
                ->with('company')
                ->where('active', 1)
                ->where('tbl_products.procat_id', $procatId)
                ->where('tbl_products.user_type', 2)
                ->select("tbl_products.*", DB::raw("CONCAT('" . $proUrl . "','',tbl_products.picture) as picture"))
                ->orderByDesc('tbl_products.pro_id');
            // echo json_encode($products);
            // exit();
            // $data = array();

            $pros = $products->get();
            if (count($pros) > 0) {
                foreach ($pros as $pro) {
                    if ($pro->user_type == 1) {
                        $uid = $pro->uid;
                        $user = Admin::with('currency')->find($uid);
                        $pro->currency = $user->currency;
                    } else {
                        $uid = $pro->uid;
                        $user = User::with('currency')->find($uid);
                        $pro->currency = $user->currency;
                    }
                    // $data[] = $pro;
                }
            }

            if (count($pros) > 0) {

                // echo 'exists';
                // exit();

                if ($keyword != '') {
                    $products = $products->paginate(16);
                    $products->setpath('');
                    $products->appends(
                        array(
                            'keyword' => $keyword
                        )
                    );
                } else {
                    $products = $products->paginate(16);
                }
                return $products;
            } else {
                // echo 'not exists';
                // exit();

                $products = array(
                    'status' => 'error',
                    'message' => 'No products available'
                );

                return $products;
            }


            // $query = DB::table('tbl_products');
            // $query->leftJoin('tbl_product_category', 'tbl_products.procat_id', '=', 'tbl_product_category.procat_id');
            // $query->leftJoin('tbl_units', 'tbl_products.unit', '=', 'tbl_units.unit_id');

            // if ($procatId > 0) {
            //     $query->where('tbl_products.procat_id', $procatId);
            // }

            // $query->where('active', 1); //  tbl_products.
            // $query->where('store', 1);  //  tbl_products.

            // $query->select(
            //     'tbl_products.*',
            //     DB::raw('CONCAT("' . $proUrl . '", "", tbl_products.picture) AS picture'),
            //     'tbl_product_category.procat_id',
            //     'tbl_product_category.category',
            //     'tbl_units.name as uname',
            //     'tbl_units.sortname'
            // );

            // $products = $query->get();

            // $data = array();
            // if (count($products) > 0) {
            //     foreach ($products as $pro) {
            //         if ($pro->user_type == 1) {
            //             $uid = $pro->uid;
            //             $user = Admin::with('currency')->find($uid);
            //             $pro->currency = $user->currency;
            //         } else {
            //             $uid = $pro->uid;
            //             $user = User::with('currency')->find($uid);
            //             $pro->currency = $user->currency;
            //         }
            //         $data[] = $pro;
            //     }
            // }

            // return $data;


        } else {
            return $products;
        }
    }

    public function getProductsBySubCategory($keyword)
    {
        $proUrl = url('/');

        // echo $keyword;
        // exit();
        $products = array();
        $stwoq = DB::table('tbl_product_subcategory')
            ->where('tbl_product_subcategory.category', 'like', '%' . $keyword . '%')
            ->orWhere('tbl_product_subcategory.slug', 'like', '%' . $keyword . '%')
            ->select('tbl_product_subcategory.prosubcat_id')
            ->first();

        // echo json_encode($stwoq);
        // exit();


        if ($stwoq != '') {
            $prosubcatId = $stwoq->prosubcat_id;

            $defaultCurrency = currency::where('status', 1)->first();


            $proUrl = url('/');
            $products = Tbl_products::has('Tbl_productcategory')->with('users.currency')
                ->has('Tbl_product_subcategory')
                ->has('company')
                ->with('Tbl_units')
                ->with('Tbl_productcategory')
                ->with('Tbl_product_subcategory')
                ->with('company')
                ->where('active', 1)
                ->where('tbl_products.user_type', 2)
                ->where('tbl_products.prosubcat_id', $prosubcatId)
                ->select("tbl_products.*", DB::raw("CONCAT('" . $proUrl . "','',tbl_products.picture) as picture"))
                ->orderByDesc('tbl_products.pro_id');

            // return $products;

            // $data = array();

            $pros = $products->get();
            if (count($pros) > 0) {
                foreach ($pros as $pro) {
                    if ($pro->user_type == 1) {
                        $uid = $pro->uid;
                        $user = Admin::with('currency')->find($uid);
                        $pro->currency = $user->currency;
                    } else {
                        $uid = $pro->uid;
                        $user = User::with('currency')->find($uid);
                        $pro->currency = $user->currency;
                    }
                    // $data[] = $pro;
                }
            }

            if (count($pros) > 0) {
                if ($keyword != '') {
                    $products = $products->paginate(16);
                    $products->setpath('');
                    $products->appends(
                        array(
                            'keyword' => $keyword
                        )
                    );
                } else {
                    $products = $products->paginate(16);
                }
                return $products;
            } else {

                $products = array(
                    'status' => 'error',
                    'message' => 'No products available'
                );

                return $products;
            }


            // return $data;
        }

        //     $query = DB::table('tbl_products');
        //     $query->leftJoin('tbl_product_category', 'tbl_products.procat_id', '=', 'tbl_product_category.procat_id');
        //     $query->leftJoin('tbl_units', 'tbl_products.unit', '=', 'tbl_units.unit_id');

        //     if ($prosubcatId > 0) {
        //         $query->where('tbl_products.prosubcat_id', $prosubcatId);
        //     }

        //     // $query->limit(8);
        //     // $query->paginate(8);
        //     $query->where('active', 1); //  tbl_products.
        //     $query->where('store', 1);  //  tbl_products.
        //     $query->skip($skip);
        //     $query->take($take);


        //     $query->select(
        //         'tbl_products.*',
        //         DB::raw('CONCAT("' . $proUrl . '", "", tbl_products.picture) AS picture'),
        //         'tbl_product_category.procat_id',
        //         'tbl_product_category.category',
        //         'tbl_units.name as uname',
        //         'tbl_units.sortname'
        //     );
        //     // echo $keyword;
        //     // exit();

        //     $products = $query->get();
        //     // echo json_encode($products);
        //     // exit();


        //     $data = array();
        //     if (count($products) > 0) {
        //         foreach ($products as $pro) {
        //             if ($pro->user_type == 1) {
        //                 $uid = $pro->uid;
        //                 $user = Admin::with('currency')->find($uid);
        //                 $pro->currency = $user->currency;
        //             } else {
        //                 $uid = $pro->uid;
        //                 $user = User::with('currency')->find($uid);
        //                 $pro->currency = $user->currency;
        //             }
        //             $data[] = $pro;
        //         }
        //     }
        //     return $data;
        // } else {
        //     return $products;
        // }
    }

    public function getSearchProductTags($keyword)
    {
        $products = array();
        $addtoCartUrl = url('shop/login'); //consumers/ajax/ajaxaddtocart/{proId}   consumers
        if (Auth::user() != '') {
            $addtoCartUrl = url('shop/ajax/addtocart/{proId}'); // consumers
        }

        $defaultCurrency = currency::where('status', 1)->first();
        // DB::table('tbl_products')->
        $query = Tbl_products::with('users.currency');

        $query->leftJoin('tbl_product_category', 'tbl_products.procat_id', '=', 'tbl_product_category.procat_id');
        $query->leftJoin('tbl_units', 'tbl_products.unit', '=', 'tbl_units.unit_id');

        $query->where('tbl_products.tags', 'like', '%' . $keyword . '%');

        $query->where('active', 1); //tbl_products.
        $query->where('store', 1);  //tbl_products.
        $query->orderByDesc('tbl_products.pro_id');
        $query->select(
            'tbl_products.*',
            'tbl_product_category.procat_id',
            'tbl_product_category.category',
            'tbl_units.name as uname',
            'tbl_units.sortname'
        );

        $products = $query->get();
        // echo json_encode($products);
        // exit();

        if (count($products) > 0) {

            if ($keyword != '') {
                $products = $query->paginate(16);
                $products->setpath('');
                $products->appends(
                    array(
                        'keyword' => $keyword
                    )
                );
            } else {
                $products = $query->paginate(16);
            }
        }

        // echo json_encode($products);
        // exit();
        return $products;
    }

    // public function searchProductByVendor($slug)
    // {
    // }

    public function searchProductByTags($slug)
    {
    }

    public function searchProductByPrice($slug)
    {
    }

    public function getProductsLatest()
    {
    }

    public function getProductsLatestFeatured($skip, $take)
    {
        $proUrl = url('/');
        //  DB::table('tbl_products')->
        $query = Tbl_products::where('tbl_products.user_type', 2)->with('users.currency');
        $query->join('companies', 'tbl_products.company', '=', 'companies.id');
        $query->leftJoin('tbl_product_category', 'tbl_products.procat_id', '=', 'tbl_product_category.procat_id');
        $query->leftJoin('tbl_product_subcategory', 'tbl_products.prosubcat_id', '=', 'tbl_product_subcategory.prosubcat_id');
        $query->leftJoin('tbl_units', 'tbl_products.unit', '=', 'tbl_units.unit_id');
        $query->where('active', 1); //  tbl_products.
        $query->where('store', 1);  //  tbl_products.
        $query->where('featured', 1);  //  tbl_products.
        $query->where('tbl_products.procat_id', '>', 0);  //  tbl_products.
        $query->where('tbl_products.prosubcat_id', '>', 0);  //  tbl_products.
        $query->where('tbl_products.company', '>', 0);  //  tbl_products.
        $query->orderByDesc('tbl_products.pro_id');
        $query->skip($skip);
        $query->take($take);
        $query->select(
            'tbl_products.*',
            DB::raw('CONCAT("' . $proUrl . '", "", tbl_products.picture) AS picture'),
            'tbl_product_category.procat_id',
            'tbl_product_category.category',
            'tbl_product_category.slug as category_slug',
            'tbl_product_subcategory.prosubcat_id',
            'tbl_product_subcategory.category as subcategory',
            'tbl_product_subcategory.slug as subcategory_slug',
            'companies.id as c_id',
            'companies.c_name',
            'companies.slug as c_slug',
            'companies.city as c_city',
            'tbl_units.name as uname',
            'tbl_units.sortname'
        );

        $products = $query->get();
        // return $products;
        $data = array();
        if (count($products) > 0) {
            foreach ($products as $pro) {
                if ($pro->user_type == 1) {
                    $uid = $pro->uid;
                    $user = Admin::with('currency')->find($uid);
                    $pro->currency = $user->currency;
                } else {
                    $uid = $pro->uid;
                    $user = User::with('currency')->find($uid);
                    $pro->currency = $user->currency;
                }
                $data[] = $pro;
            }
        }

        return $data;
    }

    public function getProductBrandslist()
    {
        $brands = DB::table('tbl_products')->where('tbl_products.user_type', 2)->where('vendor', '!=', null)->distinct('vendor')->select(
            'tbl_products.vendor'
        )->get();
        return $brands;
    }

    public function getProductCategoryList($skip, $take)
    {
        // $brands = DB::table('tbl_product_category')
        //     ->join('tbl_product_subcategory', 'tbl_product_category.procat_id', '=', 'tbl_product_subcategory.procat_id')
        //     ->select(
        //         'tbl_product_category.*',
        //         'tbl_product_subcategory.prosubcat_id as prosubcat_id',
        //         'tbl_product_subcategory.category as subcategory',
        //         'tbl_product_subcategory.slug as subcategory_slug'
        //     )->get();
        // return $brands;

        // $query->skip($skip);
        // $query->take($take);



        $procats = Tbl_productcategory::has('Tbl_product_subcategory')
            ->with('Tbl_product_subcategory')
            ->skip($skip)
            ->take($take)
            ->get();
        return $procats;
    }

    public function getProductsList($skip, $take)
    {
        //         $user = User::select("users.*"
        // 		,DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"))
        //         ->get();

        $proUrl = url('/');
        $products = Tbl_products::has('Tbl_productcategory')
            ->has('Tbl_product_subcategory')
            ->has('company')
            ->with('Tbl_units')
            ->with('Tbl_productcategory')
            ->with('Tbl_product_subcategory')
            ->with('company')
            ->where('active', 1)
            ->select("tbl_products.*", DB::raw("CONCAT('" . $proUrl . "','',tbl_products.picture) as picture"))
            ->orderByDesc('tbl_products.pro_id')
            ->skip($skip)
            ->take($take)
            ->get();

        // return $products;

        $data = array();
        if (count($products) > 0) {
            foreach ($products as $pro) {
                if ($pro->user_type == 1) {
                    $uid = $pro->uid;
                    $user = Admin::with('currency')->find($uid);
                    $pro->currency = $user->currency;
                } else {
                    $uid = $pro->uid;
                    $user = User::with('currency')->find($uid);
                    $pro->currency = $user->currency;
                }
                $data[] = $pro;
            }
        }

        return $data;
    }


    public function createAdminNotification($userId, $userType, $notifyMessage, $notifyType, $typeId)
    {

        $notArray = array(
            'aid' => $userId,
            'uid' => $userId,
            'status' => 0,
            'type' => $notifyType,
            'id' => $typeId,
            'message' => $notifyMessage
        );

        $res = Tbl_admin_notifications::create($notArray);

        return $res;
    }

    public function createUserNotification($userId, $userType, $notifyMessage, $notifyType, $typeId)
    {

        $notArray = array(
            'aid' => 1,
            'uid' => $userId,
            'status' => 0,
            'type' => $notifyType,
            'id' => $typeId,
            'message' => $notifyMessage
        );

        $res = Tbl_notifications::create($notArray);

        return $res;
    }

    public function setCountSearchkeyword($keyword, $results)
    {
        $counter = $this->checkDuplicateCategory($keyword);
        if ($counter != '') {
            return Tbl_search_keywords::where('sk_id', $counter->sk_id)
                ->update([
                    'counter' => DB::raw('counter+1'),
                    'results' => $results
                ]);
        } else {
            return Tbl_search_keywords::create([
                'keyword' => $keyword,
                'counter' => 1,
                'results' => $results
            ]);
        }
    }

    public function checkDuplicateCategory($keyword)
    {
        return Tbl_search_keywords::select('sk_id', 'counter')->where('keyword', 'like', $keyword . '%')
            ->first();
    }


    public function getProductCategoryOptionList()
    {
        $procats = Tbl_productcategory::all();
        return $procats;
    }

    public function getProductSubCategoryOptionList($id)
    {
        $procats = Tbl_product_subcategory::where('procat_id', $id)->get();
        return $procats;
    }
}

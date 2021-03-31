<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Session;
use Auth;
use Session;
use OpenGraph;
// Controllers
use App\Http\Controllers\Api\ConsumerController;
//  Models
use App\Tbl_countries;
use App\Tbl_states;
use App\Tbl_cart_orders;
use App\Tbl_product_subcategory;
use App\Tbl_productcategory;
use App\Tbl_emailcategory;
use App\Tbl_emailtemplates;
use App\Tbl_emails;
use PhpParser\Node\Stmt\Function_;
use App\Rules\googleCaptcha;
use Mail;


class AjaxController extends Controller
{


    public function ajaxGetProducts(Request $request)
    {

        // echo json_encode($request->input());
        // exit();

        $proObj = new ConsumerController();

        $skip = $request->input('skip');
        $min_price = $request->input('min_price');
        $max_price = $request->input('max_price');
        $procatId = $request->input('procatId');
        $prosubcatId = $request->input('prosubcatId');
        $keyword = $request->input('keyword');
        $sortby = $request->input('sortby');
        // $procatId, $prosubcatId
        // echo $keyword;
        // exit();

        $products = $proObj->getProducts($skip, $min_price, $max_price, $procatId, $prosubcatId, $keyword, $sortby);
        return $products;
        // echo json_encode($products);
        // exit();
        // echo 'Ajax';
        // return view('consumer.products.list', compact('products'));     //->render()
    }

    public function ajaxAddtoCart(Request $request) //$proId
    {
        // echo json_encode(Auth::user());
        $proId = $request->input('proId');
        echo  $proId;
        exit();
    }

    public function ajaxBuyNow($pro_id)
    {
        $proObj = new ConsumerController();
        return $proObj->buyNowProduct($pro_id);
    }

    public function slugBuyNow($slug)
    {
        $proObj = new ConsumerController();
        return $proObj->buyNowProductSlug($slug);
    }

    public function buyNowAction(Request $request, $id)
    {
        // echo json_encode($request->input());
        // exit();

        $urData = $request->input();

        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'email|required|max:255',
            'mobile' => 'required|max:255',
            'quantity' => 'required',
            // 'price' => 'required',
            // 'address' => 'required',
            // 'address' => 'required',
            // 'city' => 'required',
            // 'zip' => 'required',
            'g-recaptcha-response' => ['required', new googleCaptcha()],
        ]);

        // , [
        //     'g-recaptcha-response.required' => 'Please select google catcha',
        // ]


        $fdata = $request->input();
        $proObj = new ConsumerController();
        $res = $proObj->productBuyNow($fdata, $id);

        $prodetails = $proObj->getProductDetails($id);
        $proslug = $prodetails['product']->slug;

        if ($res->coid > 0) {
            //  consumers
            //  shop/ajax/ajaxbuynow/.$id
            $coid = $res->coid;
            $cartOrder = Tbl_cart_orders::find($coid);

            // Admin Mail 

            $department = Tbl_emailcategory::where('category', 'Cart Order Placed')->first();
            $template = Tbl_emailtemplates::where('ecat_id', $department->ecat_id)->first();
            $emails = Tbl_emails::where('ecat_id', $department->ecat_id)->first();

            $subject = $template->subject;
            $tmpmessage = $template->message;
            $tmpemail = $emails->mail;

            $proPicture = ($prodetails['product']->picture != '') ? url($prodetails['product']->picture) : url('/uploads/default/products.jpg');

            $prName = $prodetails['product']->name;
            $prPicture = '<img src="' . $proPicture . '" height="25" width="25">';
            $prBrand = $prodetails['product']->vendor;
            $prPrice = '<span>' . $prodetails['user']['currency']['html_code'] . '</span>' . $prodetails['product']->price;
            $prSize = $prodetails['product']->size;
            $prUnits = ($prodetails['product']['tbl_units'] != '') ? $prodetails['product']['tbl_units']->sortname : '';

            $cntryName = '';
            if ($cartOrder->country > 0) {
                $country = Tbl_countries::find($cartOrder->country);
                $cntryName = $country->name;
            }

            $urName = $cartOrder->name;
            $urEmail = $cartOrder->email;
            $urMobile = $cartOrder->mobile;
            $urOrderDate = date('d-m-Y', strtotime($cartOrder->order_date));
            $urQuantity = $cartOrder->quantity;
            $urPrice = '<span>' . $prodetails['user']['currency']['html_code'] . '</span>' . ' ' . $cartOrder->price;
            $urTotalAmount = '<span>' . $prodetails['user']['currency']['html_code'] . '</span>' . ' ' . $cartOrder->total_amount;
            $urAddress = $cartOrder->address;
            $urCountry = $cntryName;
            $urCity = $cartOrder->city;
            $urMessage = $cartOrder->message;


            $beforeStr = $tmpmessage;
            preg_match_all('/{(\w+)}/', $beforeStr, $matches);
            $afterStr = $beforeStr;
            foreach ($matches[0] as $index => $var_name) {
                if (isset(${$matches[1][$index]})) {
                    $afterStr = str_replace($var_name, ${$matches[1][$index]}, $afterStr);
                }
            }
            $content = $afterStr;


            // echo $content;
            // exit();

            //  For Admin
            $from_mail = $cartOrder->email;
            $to_email = $tmpemail;
            $title = 'Digital CRM';
            $this->sendMail($from_mail, $to_email, $content, $subject, $title);

            //  For Consumer
            $from_mail = $tmpemail;
            $to_email = $cartOrder->email;
            $title = 'Digital CRM';
            $this->sendMail($from_mail, $to_email, $content, $subject, $title);


            return redirect('shop/product/buynow/' . $proslug)->with('success', 'Order is taken. Thank you for contacting...!');
        } else {
            //  consumers
            return redirect('shop/product/buynow/' . $proslug)->with('error', 'Error occurred. Please try again later...!');
        }
    }



    public function slugGetProductDetails($slug)
    {
        $proObj = new ConsumerController();
        $product = $proObj->getProductDetailsSlug($slug);
        if ($product != '') {
            return view('consumer.products.show')->with('data', $product);
        } else {
            return redirect()->back()->with('error', 'Product is not in display');
        }
    }

    public function ajaxGetProductDetails($id)
    {
        $proObj = new ConsumerController();
        $product = $proObj->getProductDetails($id);

        if ($product != '') {
            return view('consumer.products.show')->with('data', $product);
        } else {
            return redirect()->back()->with('error', 'Product is not in display');
        }
    }

    public function getStateoptions(Request $request)
    {
        $country = $request->input('country');
        $states = Tbl_states::where('country_id', $country)->get();
        $stateOption = "<option value='0'>Select State</option>";
        foreach ($states as $state) {
            $stateOption .= "<option value='" . $state->id . "'>" . $state->name . "</option>";
        }
        return $stateOption;
    }

    public function getSiteMap()
    {
        //  ->with('data', $product)
        $proObj = new ConsumerController();
        $products = $proObj->getProductSiteMap();

        // echo json_encode($products);
        // exit();
        return view('consumer.products.sitemap')->with('data', $products);
    }

    public function getProductSubCategoryOptions(Request $request)
    {
        $procatId = $request->input('procatId');
        $proObj = new ConsumerController();
        $catOptions = $proObj->getProductSubCategoryOptions(0, $procatId);
        return $catOptions;
    }

    public function getSiteMapProductCategory($slug)
    {
        $proObj = new ConsumerController();
        $products = $proObj->getSiteMapProductCategory($slug);
        return view('consumer.products.cat_sitemap')->with('data', $products);
    }

    public function getSiteMapProductSubCategory($slug)
    {
        // echo $slug;
        // exit();

        $proObj = new ConsumerController();
        $products = $proObj->getSiteMapProductSubCategory($slug);
        return view('consumer.products.subcat_sitemap')->with('data', $products);

        // return $this->searchProductKeyword($slug);
    }

    public function fetchOpenGraphMetadata()
    {
        $url = url('shop/sitemap/products');
        $proObj = new ConsumerController();
        $products = $proObj->fetchOpenGraphMetadata($url);
        return $products;
    }

    public function slugGetProductDetailsRssFeed($slug)
    {
        $proObj = new ConsumerController();
        $products = $proObj->slugGetProductDetailsRssFeed($slug);
        // return $products;
        return view('consumer.products.rssfeed_product_details')->with('data', $products);
    }

    public function getRssfeedProducts()
    {
        $proObj = new ConsumerController();
        $products = $proObj->getRssfeedProducts();
        // return $products;
        // return view('consumer.products.rssfeed_products')->with('data', $products);
        return response()->view('consumer.products.rssfeed_products', [
            'products' => $products,
        ])->header('Content-Type', 'text/xml');
    }

    public function getRssfeedProduct($slug)
    {
        $proObj = new ConsumerController();
        $products = $proObj->getRssfeedProduct($slug);
        return response()->view('consumer.products.rssfeed_product', [
            'product' => $products,
        ])->header('Content-Type', 'text/xml');
    }

    public function getRssfeedProductCategories()
    {
        $proObj = new ConsumerController();
        $products = $proObj->getRssfeedProductCategories();
        return response()->view('consumer.products.rssfeed_productcategories', [
            'procats' => $products,
        ])->header('Content-Type', 'text/xml');
    }

    public function getRssfeedProductCategory($slug)
    {
        $proObj = new ConsumerController();
        $products = $proObj->getRssfeedProductCategory($slug);
        return response()->view('consumer.products.rssfeed_product_category', [
            'procat' => $products,
        ])->header('Content-Type', 'text/xml');
    }

    public function getRssfeedProductSubCategory($slug)
    {
        $proObj = new ConsumerController();
        $products = $proObj->getRssfeedProductSubCategory($slug);
        return response()->view('consumer.products.rssfeed_product_subcategory', [
            'procat' => $products,
        ])->header('Content-Type', 'text/xml');
    }

    public function searchProduct(Request $request)
    {
        $keyword = $request->get('keyword');
        $proObj = new ConsumerController();
        $products = $proObj->getProductsbySearch($keyword);
        // $products->appends($request->only('keyword'));
        return view('consumer.products.list', compact('products'))->render();
    }

    public function searchProductKeyword($keyword)
    {
        // echo $keyword;

        $proObj = new ConsumerController();
        $products = $proObj->getProductsbySearch($keyword);

        $maxPrice = $proObj->getProductMaxPrice();
        $details['maxPrice'] = $maxPrice;

        $procatMenu = $proObj->getProductCategoryMenu();
        $details['procatMenu'] = $procatMenu;

        $details['keyword'] = $keyword;


        $details['category'] = '';
        $details['subcategory'] = '';

        $details['procatId'] = 0;
        $details['prosubcatId'] = 0;
        $sortby = 'recent';
        $details['sortby'] = $sortby;

        $details['title'] = '';
        return view('consumer.dashboard', compact('products'))->with('details', $details);
    }

    public function getProductBrandslist()
    {
        $proObj = new ConsumerController();
        $brands = $proObj->getProductBrandslist();
        return view('consumer.products.brandslist')->with('data', $brands);
        // echo json_encode($products);
        // exit();
    }

    public function getSearchProductCategory($slug)
    {

        // echo 'flag in ' . $slug;
        // exit();

        $products = array();
        $proObj = new ConsumerController();


        $products = $proObj->getSearchProductCategory($slug);

        $category = '';
        $procatId = '';
        $prosubcatId = '';
        $sortby = 'recent';

        if (count($products) > 0) {
            $categoryVal = $proObj->findProCategoryByKeyword($slug);
            $category = $categoryVal->category;

            $procatId = 'procatId' . $categoryVal->procat_id;
        }

        $maxPrice = $proObj->getProductMaxPrice();
        $details['maxPrice'] = $maxPrice;

        $procatMenu = $proObj->getProductCategoryMenu();
        $details['procatMenu'] = $procatMenu;

        $details['keyword'] = $slug;

        $details['category'] = $category;
        $details['subcategory'] = '';

        $details['procatId'] = $procatId;
        $details['prosubcatId'] = $prosubcatId;
        $details['sortby'] = $sortby;
        $details['title'] = 'Products';

        return view('consumer.dashboard', compact('products'))->with('details', $details);


        //  Old Code
        // return $this->searchProductKeyword($slug);
    }

    public function getSearchProductSubCategory($slug)
    {
        // echo $keyword;
        // exit();

        $products = array();
        $proObj = new ConsumerController();

        $products = $proObj->getSearchProductSubCategory($slug);

        $subcategory = '';
        $category = '';
        $procatId = '';
        $prosubcatId = '';
        $sortby = 'recent';

        if (count($products) > 0) {
            $subcategoryVal = $proObj->findProSubCategoryByKeyword($slug);
            $subcategory = $subcategoryVal->category;

            $categoryVal = Tbl_productcategory::find($subcategoryVal->procat_id);
            $category = $categoryVal->category;

            $procatId = 'procatId' . $subcategoryVal->procat_id;
            $prosubcatId = 'prosubcatId' . $subcategoryVal->prosubcat_id;
        }

        $details['keyword'] = $slug;

        $details['category'] = $category;
        $details['subcategory'] = $subcategory;

        $maxPrice = $proObj->getProductMaxPrice();
        $details['maxPrice'] = $maxPrice;

        $procatMenu = $proObj->getProductCategoryMenu();
        $details['procatMenu'] = $procatMenu;

        $details['procatId'] = $procatId;
        $details['prosubcatId'] = $prosubcatId;
        $details['sortby'] = $sortby;

        $details['title'] = 'Products';
        return view('consumer.dashboard', compact('products'))->with('details', $details);

        //  Old Code
        // return $this->searchProductKeyword($slug);
    }

    public function getSearchProductTags($slug)
    {

        // echo $slug;
        // exit();

        $products = array();
        $proObj = new ConsumerController();

        $procatId = '';
        $prosubcatId = '';
        $sortby = 'recent';

        $products = $proObj->getSearchProductTags($slug);

        $maxPrice = $proObj->getProductMaxPrice();
        $details['maxPrice'] = $maxPrice;

        $procatMenu = $proObj->getProductCategoryMenu();
        $details['procatMenu'] = $procatMenu;

        $details['keyword'] = $slug;

        $details['category'] = '';
        $details['subcategory'] = '';

        $details['procatId'] = $procatId;
        $details['prosubcatId'] = $prosubcatId;
        $details['sortby'] = $sortby;
        $details['title'] = 'Products';

        return view('consumer.dashboard', compact('products'))->with('details', $details);



        //  Old Code
        // return $this->searchProductKeyword($slug);
    }


    public function getSearchProductCategoryList()
    {
        $proObj = new ConsumerController();
        $products = $proObj->getSearchProductCategoryList();
        return view('consumer.products.cat_search')->with('data', $products);
    }


    public function getSearchProductSubCategoryList()
    {
        $proObj = new ConsumerController();
        $products = $proObj->getSearchProductSubCategoryList();
        return view('consumer.products.subcat_search')->with('data', $products);
    }

    public function getSearchProductTagsList()
    {
        $proObj = new ConsumerController();
        $products = $proObj->getSearchProductTagsList();
        return view('consumer.products.tags_search')->with('data', $products);
    }

    public function formGetProducts(Request $request)
    {
        // echo json_encode($request->input());
        // exit();

        $proObj = new ConsumerController();

        // $skip = $request->input('skip');
        $skip = 0;
        $min_price = $request->input('minPrice');
        $max_price = $request->input('maxPrice');
        $procatId = $request->input('catId');
        $prosubcatId = $request->input('subcatId');
        $keyword = $request->input('searchWord');
        $sortby = $request->input('sortType');
        $searchType = $request->input('searchType');
        // $procatId, $prosubcatId
        // echo $keyword;
        // exit();

        $products = $proObj->getProducts($skip, $min_price, $max_price, $procatId, $prosubcatId, $keyword, $sortby);
        // echo json_encode($products);
        // exit();
        $maxPrice = $proObj->getProductMaxPrice();
        $details['maxPrice'] = $maxPrice;

        $procatMenu = $proObj->getProductCategoryMenu();
        $details['procatMenu'] = $procatMenu;

        $details['keyword'] = $keyword;


        $details['category'] = '';
        $details['subcategory'] = '';

        $details['procatId'] = $procatId;
        $details['prosubcatId'] = $prosubcatId;
        $details['sortby'] = $sortby;

        $title = '';
        if ($searchType == 'keyword') {
            $title = 'Products based on word ' . $keyword;
        }

        if ($searchType == 'price') {
            $title = 'Products based on price ranging from ' . $min_price . ' - ' . $max_price;
        }

        if (($searchType == 'sortby') && ($sortby == 'recent')) {
            $title = 'Sort by Recent';
        }

        if (($searchType == 'sortby') && ($sortby == 'popular')) {
            $title = 'Sort by Popular Products';
        }

        if (($searchType == 'sortby') && ($sortby == 'priceAsc')) {
            $title = 'Sort by Price - Low to High';
        }

        if (($searchType == 'sortby') && ($sortby == 'priceDesc')) {
            $title = 'Sort by Price - High to Low';
        }

        $details['title'] = $title;

        return view('consumer.dashboard', compact('products'))->with('details', $details);
    }

    public function sendMail($from, $to, $message, $subject, $title)
    {

        Mail::send(['html' => 'emails.default'], ['title' => $title, 'content' => $message], function ($message) use ($from, $to, $subject) {
            $message->subject($subject);
            $message->from($from, config('app.name'));   //'sandeepindana@yahoo.com'
            $message->to($to);   //'isandeep.1609@gmail.com'
        });

        if (count(Mail::failures()) > 0) {
            //            echo 'Failed to send password reset email, please try again.';
            return FALSE;
        } else {
            //            echo "Success";
            return TRUE;
        }
    }
}

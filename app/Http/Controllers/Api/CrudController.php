<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Support\Facades\Validator;
use Mail;

// Controllers
use spatie\ray;
use App\Http\Controllers\Api\ConsumerController;

//  Models
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
use App\Tbl_emailcategory;
use App\Tbl_emailtemplates;
use App\Tbl_emails;


class CrudController extends Controller
{
    //
    public function getProductsList($skip, $take)
    {
        $consumerObj = new ConsumerController();
        $products = $consumerObj->getProductsList($skip, $take);
        // echo json_encode($products);
        // exit();
        return $products;
    }

    public function getProducts()
    {
        // ray('My first ray call');
        // exit();

        $consumerObj = new ConsumerController();
        $skipRec = 0;
        $min = 0;
        $max = 0;
        $procatId = 0;
        $prosubcatId = 0;
        $keyword = '';
        $sortby = 'recent';

        // echo "this function";
        // exit();

        $products = $consumerObj->getProducts($skipRec, $min, $max, $procatId, $prosubcatId, $keyword, $sortby);
        // $products = $consumerObj->getProductsList();
        // echo json_encode($products);
        // exit();

        // $details['products'] = $products;

        // $maxPrice = $consumerObj->getProductMaxPrice();
        // $details['maxPrice'] = $maxPrice;

        // $procatMenu = $consumerObj->getProductCategoryMenu();
        // $details['procatMenu'] = $procatMenu;

        // $details['keyword'] = $keyword;


        // $details['category'] = '';
        // $details['subcategory'] = '';

        // $details['procatId'] = $procatId;
        // $details['prosubcatId'] = $prosubcatId;
        // $details['sortby'] = $sortby;
        // $details['title'] = '';

        return $products;

        // return view('consumer.dashboard', compact('products'))->with('details', $details);
    }


    public function getProductCategoryList($skip, $take)
    {
        // $procats = Tbl_productcategory::with('Tbl_product_subcategory')->get();
        // echo json_encode($procats);
        // exit();

        // return $procats;

        $consumerObj = new ConsumerController();
        $data = $consumerObj->getProductCategoryList($skip, $take);
        return $data;
    }

    //  Request $request
    public function getProductSubCategoryList($id, $skip, $take)
    {
        // $id = $request->input('id');
        $procats = Tbl_product_subcategory::where('procat_id', $id)
            ->skip($skip)
            ->take($take)
            ->get();
        // echo json_encode($procats);
        // exit();

        return $procats;
    }

    //  Request $request
    public function getProductDetails($slug) //  $id
    {
        $consumerObj = new ConsumerController();
        $data = $consumerObj->getProductDetailsSlug($slug);
        if ($data != '') {
            return $data;
        } else {
            return array();
        }
    }

    public function getProductsLatestFeatured($skip, $take)
    {

        // echo $skip . ' ' . $take;
        $consumerObj = new ConsumerController();
        $data = $consumerObj->getProductsLatestFeatured($skip, $take);
        if ($data != '') {
            return $data;
        } else {
            return array();
        }
    }


    public function getProductBrandslist()
    {
        // echo 'getProductBrandslist';
        $consumerObj = new ConsumerController();
        $data = $consumerObj->getProductBrandslist();
        // if ($data != '') {
        return $data;
        // } else {
        //     return array();
        // }
    }

    public function getProductsByCategory($slug)
    {
        $consumerObj = new ConsumerController();
        $data = $consumerObj->getProductsByCategory($slug);
        return $data;
    }

    public function getProductsBySubCategory($slug)
    {
        $consumerObj = new ConsumerController();
        $data = $consumerObj->getProductsBySubCategory($slug);
        return $data;
    }

    public function StoreProductByNow(Request $request)
    {
        // echo json_encode($request->input());

        $data = $request->input();

        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            // 'email' => 'email|required|max:255',
            'mobile' => 'required|max:255',
            'quantity' => 'required|numeric|gt:0',
            'pro_id' => 'required|numeric|gt:0',
            // 'price' => 'required',
            // 'address' => 'required',
            // 'country' => 'required',
            // 'city' => 'required',
            // 'zip' => 'required',

        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        } else {
            // echo 'success';
            $id = $request->input('pro_id');
            $consumerObj = new ConsumerController();
            $res = $consumerObj->productBuyNow($data, $id);

            $prodetails = $consumerObj->getProductDetails($id);

            // echo json_encode($prodetails);
            // exit();

            $proslug = $prodetails['product']->slug;
            $userId = $prodetails['product']->uid;
            $userType = $prodetails['product']->user_type;

            $proPicture = ($prodetails['product']->picture != '') ? url($prodetails['product']->picture) : url('/uploads/default/products.jpg');

            $prId = $prodetails['product']->pro_id;
            $prCompany = $prodetails['product']->company;
            $prName = $prodetails['product']->name;
            $prPicture = '<img src="' . $proPicture . '" height="25" width="25">';
            $prBrand = $prodetails['product']->vendor;
            $prPrice = '<span>' . $prodetails['user']['currency']['html_code'] . '</span>' . $prodetails['product']->price;
            $prSize = $prodetails['product']->size;
            $prUnits = ($prodetails['product']['tbl_units'] != '') ? $prodetails['product']['tbl_units']->sortname : '';

            if ($res->coid > 0) {
                //  consumers
                //  shop/ajax/ajaxbuynow/.$id
                $coid = $res->coid;
                $cartOrder = Tbl_cart_orders::find($coid);

                $urName = $cartOrder->name;
                $urEmail = $cartOrder->email;
                $urMobile = $cartOrder->mobile;

                if ($cartOrder->email != '') {
                    // Admin Mail

                    $department = Tbl_emailcategory::where('category', 'Cart Order Placed')->first();
                    $template = Tbl_emailtemplates::where('ecat_id', $department->ecat_id)->first();
                    $emails = Tbl_emails::where('ecat_id', $department->ecat_id)->first();

                    $subject = $template->subject;
                    $tmpmessage = $template->message;
                    $tmpemail = $emails->mail;



                    $cntryName = '';
                    if ($cartOrder->country > 0) {
                        $country = Tbl_countries::find($cartOrder->country);
                        $cntryName = $country->name;
                    }


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
                    $title = config('app.name');
                    $this->sendMail($from_mail, $to_email, $content, $subject, $title);

                    //  For Consumer
                    $from_mail = $tmpemail;
                    $to_email = $cartOrder->email;
                    $title = config('app.name');
                    $this->sendMail($from_mail, $to_email, $content, $subject, $title);
                }

                // Notification
                $notifyMessage = $urName . ' placed an order on ' . $prName;
                $notifyType = '7';
                $typeId = $coid;


                // echo $userType;
                // exit(0);

                if ($userType == 1) {
                    $consumerObj->createAdminNotification($userId, $userType, $notifyMessage, $notifyType, $typeId);
                }

                if ($userType == 2) {
                    $consumerObj->createUserNotification($userId, $userType, $notifyMessage, $notifyType, $typeId);
                }
                //  'shop/product/buynow/' . $proslug
                // return redirect(url()->previous())->with('success', 'Order is taken. Thank you for contacting...!');

                $resultSet = array(
                    'status' => 'success',
                    'message' => 'Order is taken. Thank you for contacting...!'
                );

                return $resultSet;
            } else {

                $resultSet = array(
                    'status' => 'error',
                    'message' => 'Error occurred. Try again later....!'
                );

                return $resultSet;
            }
        }
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

    public function getUserCurrency($uid, $user_type)
    {

        if ($user_type ==  1) {
            $user = Admin::with('currency')->find($uid);
            return $user->currency;
        }
        if ($user_type ==  2) {
            $user = User::with('currency')->find($uid);
            return $user->currency;
        }
    }

    public function searchProductKeyword($keyword)
    {
        $consumerObj = new ConsumerController();
        $skipRec = 0;
        $min = 0;
        $max = 0;
        $procatId = 0;
        $prosubcatId = 0;
        // $keyword = '';
        $sortby = 'recent';

        // echo "this function";
        // exit();

        $products = $consumerObj->getProducts($skipRec, $min, $max, $procatId, $prosubcatId, $keyword, $sortby);

        return $products;
    }

    public function searchProductByVendor($keyword)
    {
        $consumerObj = new ConsumerController();
        $skipRec = 0;
        $min = 0;
        $max = 0;
        $procatId = 0;
        $prosubcatId = 0;
        // $keyword = '';
        $sortby = 'recent';

        // echo "this function";
        // exit();

        $products = $consumerObj->searchProductByVendor($skipRec, $min, $max, $procatId, $prosubcatId, $keyword, $sortby);

        return $products;
    }

    public function getProductCategoryOptionList()
    {
        // return "getProductCategoryOptionList";
        $consumerObj = new ConsumerController();
        $procats = $consumerObj->getProductCategoryOptionList();
        return $procats;
    }

    public function getProductSubCategoryOptionList($id)
    {
        $consumerObj = new ConsumerController();
        $procats = $consumerObj->getProductSubCategoryOptionList($id);
        return $procats;
    }
}

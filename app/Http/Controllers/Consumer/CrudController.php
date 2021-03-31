<?php

namespace App\Http\Controllers\Consumer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
// Controllers

use spatie\ray;

use App\Http\Controllers\Consumer\ConsumerController;

class CrudController extends Controller
{
    //


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
        $products = $consumerObj->getProducts($skipRec, $min, $max, $procatId, $prosubcatId, $keyword, $sortby);

        // // $proCartCount = $this->getUserCartProducts($uid);
        // // $products['proCartCount'] = $products;

        $maxPrice = $consumerObj->getProductMaxPrice();
        $details['maxPrice'] = $maxPrice;

        // $procatOptions = $consumerObj->getProductCategoryOptions(0);
        // $details['procatOptions'] = $procatOptions;

        $procatMenu = $consumerObj->getProductCategoryMenu();
        $details['procatMenu'] = $procatMenu;

        $details['keyword'] = $keyword;


        $details['category'] = '';
        $details['subcategory'] = '';

        $details['procatId'] = $procatId;
        $details['prosubcatId'] = $prosubcatId;
        $details['sortby'] = $sortby;
        $details['title'] = '';

        return view('consumer.dashboard', compact('products'))->with('details', $details);
    }
}

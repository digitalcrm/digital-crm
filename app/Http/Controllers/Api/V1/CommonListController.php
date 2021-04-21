<?php

namespace App\Http\Controllers\Api\V1;

use App\currency;
use App\Tbl_leadstatus;
use App\Tbl_accounttypes;
use App\Tbl_industrytypes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tbl_countries;
use App\Tbl_deal_types;
use App\Tbl_leadsource;
use App\Tbl_product_subcategory;
use App\Tbl_productcategory;
use App\Tbl_states;
use App\Tbl_units;

class CommonListController extends Controller
{
    public function getcurrency()
    {
        $getcurrency = currency::select('cr_id','name','code','html_code')->get();

        return response()->json(['currency' => $getcurrency]);
    }

    public function accounttypes()
    {
        $accounttypes = Tbl_accounttypes::select('actype_id', 'account_type')->get();

        return response()->json(['type' => $accounttypes]);
    }

    public function leadtypes()
    {
        $leadType = Tbl_leadstatus::select('ldstatus_id', 'status')->get();

        return response()->json(['status' => $leadType]);
    }

    public function industrytypes()
    {
        $industryType = Tbl_industrytypes::select('intype_id','type')->get();

        return response()->json(['type' => $industryType]);
    }

    public function units()
    {
        $units = Tbl_units::select('unit_id', 'name', 'sortname')->get();

        return response()->json(['unit' => $units]);
    }

    public function leadSource()
    {
        $leadSource = Tbl_leadsource::select('ldsrc_id','leadsource')->get();

        return response()->json(['leadsource' => $leadSource]);
    }

    public function dealType()
    {
        $dealType = Tbl_deal_types::select('dl_id', 'type')->get();

        return response()->json(['type' => $dealType]);
    }

    public function country(string $sortname = null) 
    {
        $countries = Tbl_countries::select('id', 'sortname', 'name')
                ->when(!is_null($sortname), function($query) use ($sortname) {
                    $query->where('sortname', $sortname);
                })
                ->get();

        return response()->json(['countries' => $countries]);
    }

    public function state(int $country_id = null) 
    {
        $states = Tbl_states::select('id', 'name', 'country_id')
        ->when(!is_null($country_id), function($query) use ($country_id) {
            $query->where('country_id',$country_id);
        })
        ->get();

        return response()->json(['states' => $states]);
    }

    public function listCategory() 
    {
        $categories = Tbl_productcategory::select('procat_id', 'category', 'slug')->get();

        return response()->json(['categories' => $categories]);
    }

    public function listSubCategory(int $catgory_id = null) 
    {
        $subCategory = Tbl_product_subcategory::select('prosubcat_id', 'procat_id', 'category', 'slug')
        ->when(!is_null($catgory_id), function($query) use ($catgory_id) {
            $query->where('procat_id',$catgory_id);
        })
        ->get();

        return response()->json(['subcategory' => $subCategory]);
    }
}

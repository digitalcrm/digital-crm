<?php

namespace App\Http\Controllers\Api\V1;

use App\Tbl_products;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use Illuminate\Database\Eloquent\Builder;

class ProductV1Controller extends Controller
{
    public $paginateData = 5;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Tbl_products::query();

        $prodLists = $query->whereHas('company', function (Builder $query) {
                $query->isActive();
            })
            ->whereHas('users', function ($query) {
                $query->isActive();
            })
            ->has('tbl_productcategory')
            ->with(
                [
                'users:id,name,email,active,cr_id',
                'users.currency:cr_id,name,code,html_code',
                'tbl_productcategory:procat_id,category,slug',
                'company:id,c_name,slug,c_mobileNum,c_whatsappNum,c_email,country_id,address,city,state_id',
                'company.tbl_countries','company.tbl_states'
                ]
            )
            ->when(request('sort'), function($q) {
                $this->paginateData = request('sort');
            })
            ->isFeatured(request('featured'))
            ->isActive()
            ->latest()
            ->paginate($this->paginateData)->withQueryString();
        
        return new ProductCollection($prodLists);
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
    public function show(Tbl_products $product)
    {   
        $prodDetails =  $product->whereHas('company', function (Builder $query) {
            $query->isActive();
        })  ->whereHas('users', function ($query) {
            $query->isActive();
        })
        ->has('tbl_productcategory')
        ->with(
            [
            'tbl_units',
            'users:id,name,email,active,cr_id',
            'users.currency:cr_id,name,code,html_code',
            'tbl_productcategory:procat_id,category,slug',
            'company:id,c_name,slug,c_mobileNum,c_whatsappNum,c_email,country_id,address,city,state_id',
            'company.tbl_countries','company.tbl_states',
            'tbl_pro_subcategory.tbl_product_subcategory',
            ]
        )->findOrFail($product->pro_id);

        return new ProductResource($prodDetails);
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
}

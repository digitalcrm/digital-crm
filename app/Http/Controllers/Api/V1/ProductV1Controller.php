<?php

namespace App\Http\Controllers\Api\V1;

use App\Tbl_leads;
use App\Tbl_products;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\LeadResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreProductRequest;
use App\Traits\ProductSlugTrait;

class ProductV1Controller extends Controller
{

    use ProductSlugTrait;

    public function __construct()
    {
        $this->middleware('auth:api')->only(['store','update','destroy','leads']);
    }

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
    public function store(StoreProductRequest $request)
    {
        $validatedData = $request->validated();

        $validatedData['slug'] = $this->createSlug(request('name'));

        $validatedData['uid'] = auth()->user()->id;

        $validatedData['store'] = 1;
        
        if (empty(request('min_quantity'))) {
            $validatedData['min_quantity'] = 1;
        } else {
            $validatedData['min_quantity'] = request('min_quantity');
        }

        if (empty(request('unit'))) {
            $validatedData['unit'] = 0;
        } else {
            $validatedData['unit'] = request('unit');
        }

        if($request->hasFile('picture')){
            $file = request('picture');
            $fname = time() . '.' . $file->getClientOriginalExtension();   
            $file->move('uploads/products/', $fname);
            $validatedData['picture'] = '/uploads/products/' . $fname;
        }
        
        $createdData = Tbl_products::create($validatedData);

        $response =  new ProductResource($createdData);

        return response()->json(['status' => 'success', 'data' => $response]);
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

    /**
     * get product leads where leadtype 2 in tbl_leads table
     *
     * @return \Illuminate\Http\Response
     */
    public function leads()
    {
        $productLead = auth()->user()->product_leads()->paginate(15);

        return LeadResource::collection($productLead);
    }
}

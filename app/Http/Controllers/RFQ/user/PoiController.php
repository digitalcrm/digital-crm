<?php

namespace App\Http\Controllers\RFQ\user;

use App\Poi;
use App\currency;
use App\Tbl_products;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PoiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('listproducts');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product_of_interests = Poi::get();
        return view('rfq.productofinterest.user.index', compact('product_of_interests'));
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
    public function destroy(Poi $poi)
    {
        $poi->delete();

        return redirect()->route('pois.index')->withMessage('product interest deleted successfully');
    }

    public function listproducts()
    {
        $product_lists_based_on_requests = Tbl_products::with(['tbl_productcategory','tbl_product_subcategory'])
                            ->categoryFilter(request('category'))
                            ->subcategoryFilter(request('subcategory')) 
                            ->productOfInterestName(request('productname'))                  
                            ->isActive()->latest()->paginate(50)->withQueryString();
        $currency = currency::where('status',1)->first();
        return view('rfq.productofinterest.product-lists', compact('product_lists_based_on_requests','currency'));
    }

}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Product as ProductResource;
use App\Http\Resources\ProductCollection;
use Illuminate\Support\Facades\Validator;

use App\Tbl_products;
use App\Tbl_productcategory;
use App\Tbl_product_subcategory;
use App\Tbl_units;
use App\currency;
use App\Tbl_Accounts;
use App\Company;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $uid = auth()->user()->id;
        $products = Tbl_products::where('uid', $uid)->where('user_type', 2)
            ->with('tbl_units')
            ->with('tbl_productcategory')
            ->with('tbl_product_subcategory')
            ->with('tbl_leads')
            ->with('tbl_cart_orders')
            ->where('active', 1)
            ->orderBy('pro_id', 'desc')
            ->paginate(10);

        return ProductResource::collection($products);
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
        // echo json_encode(auth()->user());
        // echo json_encode($request->input());
        $uid = auth()->user()->id;
        $data = $request->input();

        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            'price' => 'required|max:255',
            'description' => 'required|max:255',
            'procat_id' => 'required|numeric',
            'prosubcat_id' => 'required|numeric',
            'company' => 'required',
            'store' => 'required|numeric',
            // 'picture' => 'required|image',
        ]);


        $filename = '';
        $slideimags = array();

        if ($validator->fails()) {
            return response(['status' => 'error', 'message' => $validator->errors()]);
        } else {

            $file = $request->file('picture');
            // Build the input for validation
            $fileArray = array('picture' => $file);

            // Tell the validator that this file should be an image
            $rules = array(
                'picture' => 'required|mimes:jpeg,jpg,png,gif|max:10000' // max 10000kb
            );

            // Now pass the input and rules into the validator
            $validator = Validator::make($fileArray, $rules);

            // Check to see if validation fails or passes
            if ($validator->fails()) {
                return response(['status' => 'error', 'message' => $validator->errors()]);
            } else {
                // return 'success';
                $fname = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
                $file->move('uploads/products/', $fname);  //public_path().
                $filename = '/uploads/products/' . $fname;
                $slideimags[] = $filename;

                $active =  1;
                $store = $request->input('store');
                $units = 0;
                $procat_id = ($request->input('procat_id') != '') ? $request->input('procat_id') : 0;
                $prosubcat_id = ($request->input('prosubcat_id') != '') ? $request->input('prosubcat_id') : 0;
                $slideshowpics = (count($slideimags) > 0) ? implode(",", $slideimags) : '';
                $slug = $this->createSlug($request->input('name'));
                $current_stock = 0;
                $supply_price = 0;
                $stock = 0;
                $vendor = '';
                $tags = '';
                $location = '';

                $formdata = array(
                    'uid' => $uid,
                    'name' => $request->input('name'),
                    'picture' => $filename,
                    'price' => $request->input('price'),
                    'unit' => $units,
                    'size' => $request->input('size'),
                    'description' => $request->input('description'),
                    'enable' => $active,
                    'procat_id' => $procat_id,
                    'user_type' => 2,
                    'prosubcat_id' => $prosubcat_id, // $request->input('prosubcat_id'),
                    'vendor' => $vendor,
                    'tags' => $tags,
                    'store' => $store,
                    'slideshowpics' => $slideshowpics,
                    'slug' => $slug,
                    'productid' => '',
                    'productsku' => '',
                    'qrcode' => '',
                    'supply_price' => $supply_price,    //$request->input('supply_price')
                    'current_stock' => $current_stock,
                    'company' => $request->input('company'),
                    'location' => $location,
                    'stock' => $stock
                );

                //    echo json_encode($formdata);
                //    exit();

                $accounts = Tbl_products::create($formdata);
                if ($accounts->pro_id > 0) {
                    return response(['status' => 'success', 'message' => 'Product Created Successfully', 'result' => $accounts->pro_id]);;
                } else {
                    return response(['status' => 'error', 'message' => $validator->errors()]);
                }
            }
        }
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
        $uid = auth()->user()->id;
        // echo $uid;
        // exit();

        $products = Tbl_products::where('uid', $uid)->where('user_type', 2)->where('pro_id', $id)
            ->with('tbl_units')
            ->with('tbl_productcategory')
            ->with('tbl_product_subcategory')
            ->with('tbl_leads')
            ->with('tbl_cart_orders')
            ->where('active', 1)
            ->first();

        return new ProductResource($products);
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

    //For Generating Unique Slug Our Custom function
    public function createSlug($title, $id = 0)
    {
        // Normalize the title
        $slug = str_slug($title);
        // Get any that could possibly be related.
        // This cuts the queries down by doing it once.
        $allSlugs = $this->getRelatedSlugs($slug, $id);
        // If we haven't used it before then we are all good.
        if (!$allSlugs->contains('slug', $slug)) {
            return $slug;
        }
        // Just append numbers like a savage until we find not used.
        for ($i = 1; $i <= 10; $i++) {
            $newSlug = $slug . '-' . $i;
            if (!$allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }
        throw new \Exception('Can not create a unique slug');
    }

    protected function getRelatedSlugs($slug, $id = 0)
    {
        return Tbl_products::select('slug')->where('slug', 'like', $slug . '%')
            ->where('pro_id', '<>', $id)
            ->get();
    }
}

<?php

namespace App\Http\Controllers;

use App\User;
use App\Company;
use App\currency;
use App\Tbl_units;
use App\Tbl_Accounts;
use App\Tbl_products;
use App\Tbl_productcategory;
use Illuminate\Http\Request;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use Illuminate\Validation\Rule;
use App\Tbl_product_subcategory;
use Illuminate\Support\Facades\DB;
// use Excel;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('test:products', ['only' => ['index', 'show', 'create', 'store', 'edit', 'update', 'delete', 'deleteAll', 'import', 'importData', 'export', 'exportData']]);
        $this->middleware('verified');
        $this->middleware('isActive');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $uid = Auth::user()->id;

        $cr_id = Auth::user()->cr_id;

        $data = $this->getProductsList($uid);

        return view('auth.products.index')->with("data", $data);
    }

    public function getProductsList($uid)
    {
        $defaultCurrency = currency::where('status', 1)->first();

        $user = User::with('currency')->find($uid);
        $currency = ($user->currency != '') ? $user->currency : $defaultCurrency;

        $products = Tbl_products::with('tbl_units')->with('tbl_productcategory')    //->where('uid', $uid)
            ->with('tbl_leads')
            ->with('tbl_cart_orders')
            ->where('active', 1)
            ->where('uid', $uid)
            ->orderBy('pro_id', 'desc')
            ->get();

        // echo json_encode($products);
        // exit();

        if (count($products) > 0) {
            $formstable = '<div class="table-responsive"><table id="productsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Size/ Units</th>';
            $formstable .= '<th>Price</th>';
            $formstable .= '<th>Category</th>';
            $formstable .= '<th>Company</th>';
            $formstable .= '<th>Views</th>';
            $formstable .= '<th>Leads</th>';
            $formstable .= '<th>Status</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($products as $formdetails) {
                $img = ($formdetails->picture) ? $formdetails->picture : '/uploads/default/products.jpg';
                $sortname = ($formdetails->unit > 0) ? $formdetails->tbl_units->sortname : '';

                $category = ($formdetails->procat_id > 0) ? $formdetails->tbl_productcategory->category : '';

                $storeStat = ($formdetails->store > 0) ? "On" : 'Off';

                // $leadsCnt =  ($formdetails->procat_id > 0)

                $cartOrders = ($formdetails->tbl_cart_orders != '') ? count($formdetails->tbl_cart_orders) : 0;

                $c_name = '';
                if ($formdetails->company > 0) {
                    $company = Company::find($formdetails->company);
                    $c_name = ($company != '') ? $company->c_name : '';
                }

                $formstable .= '<tr>';
                $formstable .= '<td class="table-title">';
                $formstable .= '<img src="' . url($img) . '" class="avatar">';
                $formstable .= '<a href="' . url('products/' . $formdetails->pro_id) . '">' . $formdetails->name . '</a>&nbsp;';
                $formstable .= '</td>';
                $formstable .= '<td>' . $formdetails->size . ' ' . $sortname . '</td>';  //
                $formstable .= '<td>' . $currency->html_code . ' ' . $formdetails->price . '</td>';
                $formstable .= '<td>' . $category . '</td>';
                $formstable .= '<td>' . $c_name . '</td>';  //  $formdetails->vendor
                $formstable .= '<td>' . $formdetails->views . '</td>';
                $formstable .= '<td><a href="'.url('/leads/getproductleads/list').'">' . $cartOrders . '</a></td>';
                $formstable .= '<td>' . $storeStat . '</td>';
                $formstable .= '<td>' . date('m-d-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                  </button>
                  <div class="dropdown-menu">
                    <li><a class="dropdown-item text-default text-btn-space" href="' . url('products/' . $formdetails->pro_id . '/edit') . '">Edit</a></li>
                    <li><a class="dropdown-item text-default text-btn-space" href="' . url('products/delete/' . $formdetails->pro_id) . '">Delete</a></li>
                  </div>
                </div>';
                $formstable .= '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table></div>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = count($products);
        $data['table'] = $formstable;

        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $uid = Auth::user()->id;
        $user = Auth::user();
        if ($user->can('create', Tbl_products::class)) {
            $user = User::find($uid);
            $data['user'] = $user;

            //  Units

            $units = Tbl_units::all();
            $option = '<option value="0">Select Unit</option>';
            foreach ($units as $unit) {
                $option .= '<option value="' . $unit->unit_id . '">' . $unit->name . ' (' . $unit->sortname . ')</option>';
            }
            $data['unitOptions'] = $option;

            //  Product Category

            $procategory = Tbl_productcategory::all();
            $categoryoption = '<option value="">Select ...</option>';
            foreach ($procategory as $category) {
                $categoryoption .= '<option value="' . $category->procat_id . '">' . $category->category . '</option>';
            }
            $data['categoryoption'] = $categoryoption;

            $companys = $this->getCompanyList();
            $companyoption = '<option value="">Select ...</option>';
            foreach ($companys as $company) {
                $companyoption .= '<option value="' . $company->id . '">' . $company->c_name . '</option>';
            }
            $data['companyoption'] = $companyoption;

            return view('auth.products.create')->with("data", $data);
        } else {
            return redirect('/products');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        // echo json_encode($request->input());
        // exit();

        $this->validate($request, [
            'name' => 'required|max:255',
            'price' => 'required|numeric',
            'description' => 'required',
            'procat_id' => 'required|numeric',
            // 'prosubcat_id' => 'required',
            'prosubcatId' => 'required',
            'billtoId' => 'required|numeric',
            'picture' => 'required|image',
            'company' => 'required',
            'min_quantity' => 'nullable|numeric',
            // 'productid' => 'required|max:255',
            // 'productsku' => 'required|max:255',
            // 'qrcode' => 'required|max:255',
            // 'supply_price' => 'required|max:255',
            // 'current_stock' => 'required|max:255',

            // 'location' => 'required|max:255',
            // 'stock' => 'required'
        ], [
            // 'min_quantity.required' => 'Minimum order of quantity is required',
            // 'min_quantity.numeric' => 'Check given value',
            'billtoId.required' => 'Check given Sub category',
            'billtoId.numeric' => 'Check given Sub category',
            'prosubcatId.required' => 'Product Sub Category is required',
        ]);


        $filename = '';
        $slideimags = array();

        if ($request->hasfile('picture')) {

            //-------------Image Validation----------------------------------
            $file = $request->file('picture');
            // Build the input for validation
            $fileArray = array('picture' => $file);

            // Tell the validator that this file should be an image
            $rules = array(
                'picture' => 'mimes:jpeg,jpg,png,gif|max:10000' // max 10000kb
            );

            // Now pass the input and rules into the validator
            $validator = Validator::make($fileArray, $rules);

            // Check to see if validation fails or passes
            if ($validator->fails()) {
                return redirect('/products/create')->with('error', 'Please upload jpg, png and giff images only.');
            }
            //-------------Image Validation----------------------------------
            //            $file = $request->file('picture');
            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $file->move('uploads/products/', $name);  //public_path().
            $filename = '/uploads/products/' . $name;
            $slideimags[] = $filename;
        } else {
            return redirect('/products/create')->with('error', 'Please upload product image');
        }

        // if ($request->hasfile('slideshowpics')) {

        //     foreach ($request->file('slideshowpics') as $simg) {
        //         // print_r($simg);
        //         $fileArray = array('slideshowpics' => $simg);
        //         $rules = array(
        //             'slideshowpics' => 'mimes:jpeg,jpg,png,gif|max:10000' // max 10000kb
        //         );
        //         $validator = Validator::make($fileArray, $rules);

        //         if ($validator->fails()) {
        //             return redirect('admin/products/create')->with('error', 'Please upload jpg, png and giff images only.');
        //         }

        //         $sname = $this->RemoveSpecialChar($simg->getClientOriginalName());
        //         // $sname = $kname . '.' . $simg->getClientOriginalExtension();   //getClientOriginalName()
        //         $simg->move('uploads/products/', $sname);  //public_path().
        //         $slideimags[] = '/uploads/products/' . $sname;
        //     }
        // }

        // echo json_encode($slideimags);
        // exit();

        $active =  1;
        $store = ($request->input('store') != '') ? 1 : 0;
        $units = ($request->input('units') > 0) ? $request->input('units') : 0;
        // $prosubcat_id = ($request->input('prosubcat_id') != '') ? $request->input('prosubcat_id') : 0;
        $prosubcat_id = ($request->input('billtoId') != '') ? $request->input('billtoId') : 0;
        $slideshowpics = (count($slideimags) > 0) ? implode(",", $slideimags) : '';
        $slug = $this->createSlug($request->input('name'));
        $current_stock = 0;
        // $supply_price = ($request->input('supply_price') != '') ? $request->input('supply_price') : 0;
        // $stock = ($request->input('stock') != '') ? $request->input('stock') : 0;

        $productid = 0;
        $productsku = 0;
        $qrcode = '';
        $supply_price = 0;    //$request->input('supply_price')
        $current_stock = 0;
        $location = '';
        $stock = 0;

        if (empty(request('min_quantity'))) {
            $min_quantity = 1;
        } else {
            $min_quantity = request('min_quantity');
        }

        $formdata = array(
            'uid' => Auth::user()->id,
            'name' => $request->input('name'),
            'picture' => $filename,
            'price' => $request->input('price'),
            'unit' => $units,
            'size' => $request->input('size'),
            'description' => $request->input('description'),
            'enable' => $active,
            'procat_id' => $request->input('procat_id'),
            'user_type' => 2,
            'prosubcat_id' => $prosubcat_id, // $request->input('prosubcat_id'),
            'vendor' => $request->input('vendor'),
            'tags' => $request->input('tags'),
            'store' => $store,
            'slideshowpics' => $slideshowpics,
            'slug' => $slug,
            'productid' => $productid,
            'productsku' => $productsku,
            'qrcode' => $qrcode,
            'supply_price' => $supply_price,    //$request->input('supply_price')
            'current_stock' => $current_stock,
            'company' => $request->input('company'),
            'location' => $location,
            'stock' => $stock,
            'min_quantity' => $min_quantity,
        );

        //    echo json_encode($formdata);
        //    exit();

        $accounts = Tbl_products::create($formdata);
        if ($accounts->pro_id > 0) {
            return redirect('/products')->with('success', 'Created Successfully');
        } else {
            return redirect('/products')->with('error', 'Error occurred. Please try again...!');
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
        $user = Auth::user();
        $products = Tbl_products::with('Tbl_units')->with('Tbl_productcategory')->with('Tbl_product_subcategory')->with('company')->find($id);

        if ($user->can('view', $products)) {

            // echo json_encode($products);
            // exit();

            $data['product'] = $products;

            $editLink = url('products') . '/' . $id . '/edit';
            $data['editLink'] = $editLink;

            $uid = Auth::user()->id;
            $user = User::find($uid);
            $units = Tbl_units::all();
            $option = '<option value="">Select Unit</option>';
            $unitt = '';
            foreach ($units as $unit) {
                $selected = (($products['tbl_units'] != '') && ($unit->unit_id == $products['tbl_units']->unit_id)) ? 'selected' : '';
                $option .= '<option value="' . $unit->unit_id . '" ' . $selected . '>' . $unit->name . '</option>';
                if ($unit != '') {
                    if ($selected != '') {
                        $unitt = $unit;
                    }
                }
            }

            $data['unitOptions'] = $option;
            $data['user'] = $user;
            $data['unit'] = $unit;

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


            return view('auth.products.show')->with("data", $data);
        } else {
            return redirect('/products');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $products = Tbl_products::with('Tbl_units')->with('Tbl_productcategory')->with('Tbl_product_subcategory')->with('company')->find($id);

        //    echo json_encode($products);
        //    exit();
        $user = Auth::user();
        if ($user->can('view', $products)) {

            $data['product'] = $products;

            $procat_id = ($products['tbl_productcategory'] != '') ? $products['tbl_productcategory']->procat_id : 0;

            $uid = Auth::user()->id;
            $user = User::find($uid);
            $data['user'] = $user;

            //  Units

            $units = Tbl_units::all();
            $option = '<option value="">Select Unit</option>';
            foreach ($units as $unit) {
                $selected = (($products['tbl_units'] != '') && ($unit->unit_id == $products['tbl_units']->unit_id)) ? 'selected' : '';
                $option .= '<option value="' . $unit->unit_id . '" ' . $selected . '>' . $unit->name . '</option>';
            }
            $data['unitOptions'] = $option;

            //  Product Category

            $procategory = Tbl_productcategory::all();
            $categoryoption = '<option value="0">Select ...</option>';
            foreach ($procategory as $category) {
                $catselected = ($category->procat_id == $procat_id) ? 'selected' : '';
                $categoryoption .= '<option value="' . $category->procat_id . '" ' . $catselected . '>' . $category->category . '</option>';
            }
            $data['categoryoption'] = $categoryoption;

            // $prosubcatOptions = '<option value="0">Select ...</option>';
            // if ($products->procat_id > 0) {
            //     $prosubcategory = Tbl_product_subcategory::where('procat_id', $products->procat_id)->get();
            //     foreach ($prosubcategory as $category) {
            //         $cat_selected = ($category->prosubcat_id == $products->prosubcat_id) ? 'selected' : '';
            //         $prosubcatOptions .= '<option value="' . $category->prosubcat_id . '" ' . $cat_selected . '>' . $category->category . '</option>';
            //     }
            // }

            // $data['prosubcatOptions'] = $prosubcatOptions;

            $leadArray = array();
            $billtoId = '';
            $billtolabel = '';
            if ($products->procat_id > 0) {
                $prosubcategory = Tbl_product_subcategory::where('procat_id', $products->procat_id)->get();
                foreach ($prosubcategory as $category) {

                    if ($category->prosubcat_id === $products->prosubcat_id) {
                        $billtoId = $category->prosubcat_id;
                        $billtolabel = $category->category;
                    }
                    $leadArr['id'] = $category->prosubcat_id;
                    $leadArr['label'] = $category->category;
                    $leadArray[] = $leadArr;
                }
            }

            $data['leadArray'] = $leadArray;
            $data['billtoId'] = $billtoId;
            $data['billtolabel'] = $billtolabel;

            $companys = $this->getCompanyList();
            $companyoption = '<option value="">Select ...</option>';
            foreach ($companys as $company) {
                $comSelect = ($company->id == $products->company) ? 'selected' : '';
                $companyoption .= '<option value="' . $company->id . '" ' . $comSelect . '>' . $company->c_name . '</option>';
            }
            $data['companyoption'] = $companyoption;

            return view('auth.products.edit')->with("data", $data);
        } else {
            return redirect('/products');
        }
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
        // echo json_encode($request->input());
        // exit();

        $user = Auth::user();
        $product = Tbl_products::find($id);
        if ($user->can('update', $product)) {

            $this->validate($request, [
                'name' => 'required|max:255',
                'price' => 'required',
                'description' => 'required',
                'procat_id' => 'required|numeric',
                // 'prosubcat_id' => 'required|numeric',
                'prosubcatId' => 'required',
                'billtoId' => 'required|numeric',
                'company' => 'required',
                'min_quantity' => 'required|numeric',
                // 'picture' => 'required|image',

                // 'productid' => 'required|max:255',
                // 'productsku' => 'required|max:255',
                // 'qrcode' => 'required|max:255',
                // 'supply_price' => 'required|max:255',

                // 'location' => 'required|max:255',
                // 'stock' => 'required'
            ], [
                'min_quantity.required' => 'Minimum order of quantity is required',
                'min_quantity.numeric' => 'Check given value',
                'billtoId.required' => 'Check given Sub category',
                'billtoId.numeric' => 'Check given Sub category',
                'prosubcatId.required' => 'Product Sub Category is required',
            ]);

            $filename = '';
            $slideimags = array();

            if ($request->hasfile('picture')) {

                //-------------Image Validation----------------------------------
                $file = $request->file('picture');
                // Build the input for validation
                $fileArray = array('picture' => $file);

                // Tell the validator that this file should be an image
                $rules = array(
                    'picture' => 'mimes:jpeg,jpg,png,gif|max:10000' // max 10000kb
                );

                // Now pass the input and rules into the validator
                $validator = Validator::make($fileArray, $rules);

                // Check to see if validation fails or passes
                if ($validator->fails()) {
                    return redirect('products/' . $id . '/edit')->with('error', 'Please upload jpg, png and giff images only.');
                }
                //-------------Image Validation----------------------------------
                //            $file = $request->file('picture');
                $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
                $file->move('uploads/products/', $name);  //public_path().
                $filename = '/uploads/products/' . $name;
                $slideimags[] = $filename;
            }


            $units = ($request->input('units') > 0) ? $request->input('units') : 0;
            $store = ($request->input('store') != '') ? 1 : 0;
            // $prosubcat_id = ($request->input('prosubcat_id') != '') ? $request->input('prosubcat_id') : 0;
            $prosubcat_id = ($request->input('billtoId') != '') ? $request->input('billtoId') : 0;

            // echo 'prosubcat_id : ' . $prosubcat_id;
            // exit();

            $slideshowpics = (count($slideimags) > 0) ? implode(",", $slideimags) : '';
            $slug = $this->createSlug($request->input('name'));
            $active = 1;

            $productid = 0;
            $productsku = 0;
            $qrcode = '';
            $supply_price = 0;    //$request->input('supply_price')
            $current_stock = 0;
            $location = '';
            $stock = 0;


            $product->name = $request->input('name');
            $product->price = $request->input('price');
            $product->unit = $units;
            $product->size = $request->input('size');
            $product->description = $request->input('description');
            $product->store = $store;
            if ($filename != '') {
                $product->picture = $filename;
            }
            $product->procat_id = $request->input('procat_id');
            $product->prosubcat_id = $prosubcat_id; // $request->input('prosubcat_id');
            $product->vendor = $request->input('vendor');
            $product->tags = $request->input('tags');
            if ($slideshowpics != '') {
                $product->slideshowpics = $slideshowpics;
            }
            $product->slug = $slug;

            $product->productid = $productid;
            $product->productsku = $productsku;
            $product->qrcode = $qrcode;
            $product->supply_price = $supply_price;
            $product->current_stock = $current_stock;
            $product->company = $request->input('company');
            $product->location = $location;
            $product->stock = $stock;
            $product->min_quantity = $request->input('min_quantity');
            $product->save();

            return redirect('/products')->with('success', 'Updated Successfully');
        } else {
            return redirect('/products');
        }
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

    public function getProductDetails($id)
    {
        return Tbl_products::with('Tbl_units')->with('Tbl_productcategory')->find($id);
    }

    public function delete($id)
    {
        $user = Auth::user();
        $product = Tbl_products::find($id);
        if ($user->can('delete', $product)) {

            $product->active = 0;
            $product->save();
            return redirect('/products')->with('success', 'Deleted Successfully');
        } else {
            return redirect('/products');
        }
    }

    public function exportData($type)
    {
        $uid = Auth::user()->id;
        $user_type = 2;
        return Excel::download(new ProductsExport($uid, $user_type), 'products.xlsx');

        // $uid = Auth::user()->id;
        // $deals = DB::table('tbl_products')
        //     ->where('tbl_products.uid', $uid)
        //     ->where('tbl_products.active', 1)
        //     ->leftJoin('tbl_units', 'tbl_products.unit', '=', 'tbl_units.unit_id')
        //     ->select([
        //         'tbl_products.pro_id',
        //         'tbl_products.uid',
        //         'tbl_products.name',
        //         'tbl_products.price',
        //         'tbl_products.size',
        //         'tbl_units.sortname as units',
        //         'tbl_products.description',
        //     ])->get();

        // //        echo json_encode($deals);
        // //
        // $deals_decode = json_decode($deals, true);

        // $sheetName = 'products_' . date('d_m_Y_h_i_s');

        // return Excel::create($sheetName, function ($excel) use ($deals_decode, $sheetName) {
        //     $excel->sheet($sheetName, function ($sheet) use ($deals_decode) {
        //         $sheet->fromArray($deals_decode);
        //     });
        // })->download($type);
    }

    public function import($type)
    {
        return view('auth.products.import');
    }

    public function importData(Request $request)
    {
        $uid = Auth::user()->id;
        // echo json_encode($request->input());

        $filename = '';
        if ($request->hasfile('importFile')) {

            //-------------Image Validation----------------------------------
            $file = $request->file('importFile');
            // Build the input for validation
            $fileArray = array('importFile' => $file, 'extension' => strtolower($file->getClientOriginalExtension()));

            //            echo $file->getClientOriginalExtension();
            //            exit(0);
            // Tell the validator that this file should be an image
            $rules = array(
                'importFile' => 'required', // max 10000kb
                'extension' => 'required|in:csv'
            );

            // Now pass the input and rules into the validator
            $validator = Validator::make($fileArray, $rules);

            // Check to see if validation fails or passes
            if ($validator->fails()) {
                return redirect('/products/import/csv')->with('error', 'Please upload .csv only.');
            }
            //-------------Image Validation----------------------------------

            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $path = $request->file('importFile')->getRealPath();
            $user_type = 2;
            $res = Excel::import(new ProductsImport($uid, $user_type), request()->file('importFile'));

            if ($res) {
                // echo 'Success';
                return redirect('/products')->with('success', 'Uploaded successfully');
            } else {
                // echo 'Failed';
                return redirect('/products/import/csv')->with('error', "Error ocurred. Try again later..!");
            }
        } else {
            return redirect('/products/import/csv')->with('error', 'Please upload file.');
        }
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->input('id');
        return Tbl_products::whereIn('pro_id', $ids)->update(array('active' => 0));
    }

    public function restoreAll(Request $request)
    {
        $ids = $request->input('id');
        return Tbl_products::whereIn('pro_id', $ids)->update(array('active' => 1));
    }

    public function ajaxGetProductDetails(Request $request)
    {
        $id = $request->input('id');
        $product = $this->getProductDetails($id);

        return json_encode($product);
    }

    public function ajaxGetProductSubCategory(Request $request)
    {
        $id = $request->input('procat_id');
        $product = $this->getProductSubCategoryOptions($id);

        return $product;
    }

    public function getProductSubCategoryOptions($id)
    {

        $procategory = Tbl_product_subcategory::where('procat_id', $id)->get();
        // $categoryoption = '<option value="">Select ...</option>';
        $leadArray = array();
        foreach ($procategory as $category) {
            // $categoryoption .= '<option value="' . $category->prosubcat_id . '">' . $category->category . '</option>';
            $leadArr['id'] = $category->prosubcat_id;
            $leadArr['label'] = $category->category;
            // $leadArr['value'] = $category->category;
            $leadArray[] = $leadArr;
        }
        // return $categoryoption;

        return json_encode($leadArray);
    }

    public function RemoveSpecialChar($str)
    {

        // Using str_replace() function
        // to replace the word
        $res = str_replace(array(
            '\'', '"',
            ',', ';', '<', '>'
        ), ' ', $str);

        // Returning the result
        return $res;
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

    public function getCompanyList()
    {
        // $accounts = Tbl_Accounts::where('company', '!=', null)->where('active', 1)->orderBy('acc_id')
        //     ->groupBy('company')
        //     ->groupBy('acc_id')
        //     ->get(['acc_id', 'company']);

        $uid = Auth::user()->id;

        $accounts = Company::where('user_id', $uid)->get();

        // echo json_encode($accounts);
        // exit();

        return $accounts;
    }

    public function getInventory()
    {
        $uid = Auth::user()->id;

        $cr_id = Auth::user()->cr_id;
        $currency = currency::find($cr_id);

        $products = Tbl_products::where('active', 1)
            ->where('uid', $uid)
            ->orderBy('pro_id', 'desc')
            ->get();

        // echo json_encode($products);
        // exit();

        if (count($products) > 0) {
            $formstable = '<div class="table-responsive"><table id="productsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Product SKU</th>';
            $formstable .= '<th>Company</th>';
            $formstable .= '<th>location</th>';
            $formstable .= '<th>Supply Pice</th>';
            $formstable .= '<th>Sale Price</th>';
            $formstable .= '<th>Current Stock</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($products as $formdetails) {
                $img = ($formdetails->picture) ? $formdetails->picture : '/uploads/default/products.jpg';

                $supply_price = ($formdetails->supply_price > 0) ? $formdetails->supply_price : 0;

                $stockOptions = '<option value="0" ' . (($formdetails->current_stock == 0) ? 'selected' : '') . '>Out of Stock</option>';
                $stockOptions .= '<option value="1" ' . (($formdetails->current_stock == 1) ? 'selected' : '') . '>Low Stock</option>';
                $stockOptions .= '<option value="2" ' . (($formdetails->current_stock == 2) ? 'selected' : '') . '>In Stock</option>';

                $csid = "'" . "cs" . $formdetails->pro_id . "'";

                $formstable .= '<tr>';
                $formstable .= '<td class="table-title">';
                $formstable .= '<img src="' . url($img) . '" class="avatar">';
                $formstable .= '<a href="' . url('products/inventory/show/' . $formdetails->pro_id) . '">' . $formdetails->name . '</a>&nbsp;';
                $formstable .= '</td>';
                $formstable .= '<td>' .  $formdetails->productsku . '</td>';
                $formstable .= '<td>' . $formdetails->company . '</td>';
                $formstable .= '<td>' . $formdetails->location . '</td>';
                $formstable .= '<td>' . $currency->html_code . ' ' . $supply_price . '</td>';
                $formstable .= '<td>' . $currency->html_code . ' ' . $formdetails->price . '</td>';
                $formstable .= '<td><select id=' . $csid . ' onchange="return changeCurrentStock(' . $csid . ',' . $formdetails->pro_id . ')">' . $stockOptions . '</select></td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                  </button>
                  <div class="dropdown-menu">
                    <li><a class="dropdown-item text-default text-btn-space" href="' . url('products/inventory/edit/' . $formdetails->pro_id) . '">Edit</a></li>
                  </div>
                </div>';
                $formstable .= '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table></div>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = count($products);
        $data['table'] = $formstable;

        return view('auth.products.inventory')->with("data", $data);
    }

    public function updateCurrentStockStatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');

        // echo json_encode($request->input());
        // exit();

        $updateArray = array(
            'current_stock' => $status
        );

        $upres = Tbl_products::where('pro_id', $id)->update($updateArray);
        return $upres;
        // echo json_encode($upres);
    }

    public function showInventory($id)
    {
        $uid = Auth::user()->id;
        $user = User::find($uid);
        $data['user'] = $user;

        $products = Tbl_products::find($id);
        $data['product'] = $products;
        $company_name = '';
        if ($products->company > 0) {
            $account = Tbl_Accounts::where('acc_id', $products->company)->first();
            $company_name = $account->company;
        }
        $data['company_name'] = $company_name;

        return view('auth.products.show_inventory')->with("data", $data);
    }

    public function editInventory($id)
    {
        $products = Tbl_products::find($id);

        //    echo json_encode($products);
        //    exit();

        $data['product'] = $products;
        $productoption = $this->getProductOptions($id);
        $data['productoption'] = $productoption;

        // $companys = $this->getCompanyList();
        // $companyoption = '<option value="">Select ...</option>';
        // foreach ($companys as $company) {
        //     $comSelect = ($company->acc_id == $products->company) ? 'selected' : '';
        //     $companyoption .= '<option value="' . $company->acc_id . '" ' . $comSelect . '>' . $company->company . '</option>';
        // }
        // $data['companyoption'] = $companyoption;


        return view('auth.products.edit_inventory')->with("data", $data);
    }

    public function updateInventory(Request $request, $id)
    {
        // echo json_encode($request->input());
        // exit();

        $this->validate($request, [
            'productid' => 'required|max:255',
            'productsku' => 'required|max:255',
            'qrcode' => 'required|max:255',
            'supply_price' => 'required|max:255',
            // 'company' => 'required',
            'location' => 'required|max:255'
        ]);

        $current_stock = 0;

        $formdata = array(
            'productid' => $request->input('productid'),
            'productsku' => $request->input('productsku'),
            'qrcode' => $request->input('qrcode'),
            'supply_price' => $request->input('supply_price'),
            'current_stock' => $current_stock,
            // 'company' => $request->input('company'),
            'location' => $request->input('location')
        );

        $upres = Tbl_products::where('pro_id', $id)->update($formdata);

        if ($upres) {
            return redirect('/products/inventory/list')->with('success', 'Updated Successfully');
        } else {
            return redirect('/products/inventory/list')->with('error', 'Error occurred. Please try again...!');
        }
    }

    public function createInventory()
    {
        $uid = Auth::user()->id;

        // echo 'createInventory';
        $proId = 0;
        $productoption = $this->getProductOptions($proId);
        $data['productoption'] = $productoption;

        // echo json_encode($products);
        // exit();

        // $data['product'] = $products;


        // $companys = $this->getCompanyList();
        // $companyoption = '<option value="">Select ...</option>';
        // foreach ($companys as $company) {
        //     $comSelect = ($company->acc_id == $products->company) ? 'selected' : '';
        //     $companyoption .= '<option value="' . $company->acc_id . '" ' . $comSelect . '>' . $company->company . '</option>';
        // }
        // $data['companyoption'] = $companyoption;


        return view('auth.products.create_inventory')->with("data", $data);
    }

    public function storeInventory(Request $request)
    {

        $this->validate($request, [
            'product' => 'required|numeric',
            'productid' => 'required|max:255',
            'productsku' => 'required|max:255',
            'qrcode' => 'required|max:255',
            'supply_price' => 'required|numeric',
            // 'company' => 'required',
            'location' => 'required|max:255'
        ]);

        // echo json_encode($request->input());
        // exit();

        $current_stock = 0;

        $id = $request->input('product');

        $formdata = array(
            'productid' => $request->input('productid'),
            'productsku' => $request->input('productsku'),
            'qrcode' => $request->input('qrcode'),
            'supply_price' => $request->input('supply_price'),
            'current_stock' => $current_stock,
            'location' => $request->input('location')
        );

        $upres = Tbl_products::where('pro_id', $id)->update($formdata);

        if ($upres) {
            return redirect('/products/inventory/list')->with('success', 'Inventory Added Successfully');
        } else {
            return redirect('/products/inventory/list')->with('error', 'Error occurred. Please try again...!');
        }
    }

    public function getProductOptions($proId)
    {
        $uid = Auth::user()->id;

        $products = Tbl_products::where('uid', $uid)->where('active', 1)->get();

        $productoption = '<option value="">Select ...</option>';
        foreach ($products as $product) {
            $comSelect = ($product->pro_id == $proId) ? 'selected' : '';
            $productoption .= '<option value="' . $product->pro_id . '" ' . $comSelect . '>' . $product->name . '</option>';
        }
        return $productoption;
    }

    // public function getCompanyOptions($proId)
    // {
    //     $uid = Auth::user()->id;

    //     $products = Tbl_products::where('uid', $uid)->where('active', 1)->get();

    //     $productoption = '<option value="">Select ...</option>';
    //     foreach ($products as $product) {
    //         $comSelect = ($product->pro_id == $proId) ? 'selected' : '';
    //         $productoption .= '<option value="' . $product->pro_id . '" ' . $comSelect . '>' . $product->name . '</option>';
    //     }
    //     return $productoption;
    // }
}

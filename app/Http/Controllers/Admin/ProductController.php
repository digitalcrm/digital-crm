<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
//---------Models---------------
use App\Tbl_productcategory;
use App\Tbl_product_subcategory;
use App\Tbl_products;
use App\Tbl_units;
use App\Tbl_leads;
use App\User;
use App\currency;
use App\Tbl_fb_leads;
use App\Tbl_product_forms;
use App\Tbl_group_products;
use Auth;
use App\Admin;
use App\Tbl_cart_orders;
use App\Tbl_Accounts;
use App\Tbl_search_keywords;

use App\Imports\ProductsImport;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;


class ProductController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('test:product-list', ['only' => ['index', 'show']]);
        $this->middleware('test:product-create', ['only' => ['create', 'store']]);
        $this->middleware('test:product-edit', ['only' => ['edit', 'update']]);
        $this->middleware('test:product-delete', ['only' => ['destroy', 'delete', 'deleteAll']]);
        // $this->middleware('test:product-import', ['only' => ['import', 'importData']]);
        // $this->middleware('test:product-export', ['only' => ['export', 'exportData']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        $uid = 'All';
        $useroptions = "<option value='All' selected>All</option>";

        $admins = Admin::orderby('id', 'asc')->get(['id', 'name']);
        foreach ($admins as $admin) {
            $useroptions .= "<option value=" . $admin->id . '-1'  . ">" . $admin->name . "</option>";   // " . $selected . "
        }

        // $useroptions .= "<option value='" . Auth::user()->id . '-1' . "' >" . Auth::user()->name . "</option>";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . '-2'  . ">" . $userdetails->name . "</option>";   // " . $selected . "
        }
        $data['useroptions'] = $useroptions;

        // $uid = Auth::user()->id;
        $uid = 'All';
        $user_type = 0;
        $accounts = $this->getProducts($uid, $user_type);

        $data['table'] = $accounts['table'];
        $data['total'] = $accounts['total'];

        return view('admin.products.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        //        $useroptions = "<option value=''>Select User</option>";
        //        foreach ($users as $userdetails) {
        //            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";   // " . $selected . "
        //        }
        //        $data['useroptions'] = $useroptions;
        //        echo json_encode(Auth::user());
        //        exit();

        $cr_id = Auth::user()->cr_id;
        $currency = currency::find($cr_id);
        $data['currency'] = $currency;
        //        echo json_encode($currency);
        //        exit();
        //  Product Category

        $procategory = Tbl_productcategory::all();
        $categoryoption = '<option value="">Select ...</option>';
        foreach ($procategory as $category) {
            $categoryoption .= '<option value="' . $category->procat_id . '">' . $category->category . '</option>';
        }
        $data['categoryoption'] = $categoryoption;

        $units = Tbl_units::all();
        $option = '<option value="0">Select Unit</option>';
        foreach ($units as $unit) {
            $option .= '<option value="' . $unit->unit_id . '">' . $unit->name . ' (' . $unit->sortname . ')</option>';
        }

        $data['unitOptions'] = $option;


        //  Forms Option Tag
        $forms = Tbl_fb_leads::distinct('form_id')->get(['form_id']);   //Tbl_leads
        $formoptions = "<option value='0'>Select Form...</option>";
        foreach ($forms as $form) {
            $formExist = Tbl_product_forms::where('form_id', $form->form_id)->count();
            $selected = "";
            $assigned = "";
            $disabled = "";
            if ($formExist > 0) {
                $disabled = "disabled";
                $assigned = "(Assigned)";
            }
            $formoptions .= "<option value=" . $form->form_id . " " . $disabled . ">" . $form->form_id . "&nbsp;<small>" . $assigned . "</small>" . "</option>";    // . "  " . $selected
        }
        $formoptions .= "<option value='none'>None</option>";
        //        exit();
        $data['formoptions'] = $formoptions;


        $companys = $this->getCompanyList();
        $companyoption = '<option value="">Select ...</option>';
        foreach ($companys as $company) {
            $companyoption .= '<option value="' . $company->acc_id . '">' . $company->company . '</option>';
        }
        $data['companyoption'] = $companyoption;



        return view('admin.products.create')->with("data", $data);
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
            'price' => 'required',
            'description' => 'required',
            'procat_id' => 'required|numeric',
            'prosubcat_id' => 'required|numeric',
            'picture' => 'required|image',
            'company' => 'required',
            'min_quantity' => 'required|numeric',
            // 'productid' => 'required|max:255',
            // 'productsku' => 'required|max:255',
            // 'qrcode' => 'required|max:255',
            // 'supply_price' => 'required|max:255',
            // 'current_stock' => 'required|max:255',

            // 'location' => 'required|max:255',
            // 'stock' => 'required'
        ], [
            'min_quantity.required' => 'Minimum order of quantity is required',
            'min_quantity.numeric' => 'Check given value',
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
                return redirect('admin/products/create')->with('error', 'Please upload jpg, png and giff images only.');
            }
            //-------------Image Validation----------------------------------
            //            $file = $request->file('picture');
            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $file->move('uploads/products/', $name);  //public_path().
            $filename = '/uploads/products/' . $name;
            $slideimags[] = $filename;
        }

        // if ($request->hasfile('slideshowpics')) {

        //     // dd($request->file('slideshowpics'));
        //     // exit();

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

        $active = 1;
        $store = ($request->input('store') != '') ? 1 : 0;
        $prosubcat_id = ($request->input('prosubcat_id') != '') ? $request->input('prosubcat_id') : 0;
        $slideshowpics = (count($slideimags) > 0) ? implode(",", $slideimags) : '';
        $current_stock = 0;
        $supply_price = ($request->input('supply_price') != '') ? $request->input('supply_price') : 0;
        $stock = ($request->input('stock') != '') ? $request->input('stock') : 0;


        $slug = $this->createSlug($request->input('name'));

        $productid = 0;
        $productsku = 0;
        $qrcode = '';
        $supply_price = 0;    //$request->input('supply_price')
        $current_stock = 0;
        $location = '';
        $stock = 0;

        $formdata = array(
            'uid' => Auth::user()->id,
            'name' => $request->input('name'),
            'picture' => $filename,
            'price' => $request->input('price'),
            'unit' => $request->input('units'),
            'size' => $request->input('size'),
            'description' => $request->input('description'),
            'enable' => $active,
            'procat_id' => $request->input('procat_id'),
            'store' => $store,
            'prosubcat_id' => $prosubcat_id,
            'vendor' => $request->input('vendor'),
            'tags' => $request->input('tags'),
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
            'min_quantity' => $request->input('min_quantity')
        );

        $accounts = Tbl_products::create($formdata);
        if ($accounts->pro_id > 0) {
            return redirect('admin/products')->with('success', 'Created Successfully');
        } else {
            return redirect('admin/products')->with('error', 'Error occurred. Please try again...!');
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
        $products = Tbl_products::with('Tbl_units')->with('Tbl_productcategory')->with('Tbl_product_subcategory')->find($id);
        //    echo json_encode($products);
        //    exit();

        $data['product'] = $products;

        $editLink = url('admin/products') . '/' . $id . '/edit';
        $data['editLink'] = $editLink;

        $uid = $products->uid;
        $user = User::find($uid);
        $units = Tbl_units::all();
        $option = '<option value="">Select Unit</option>';
        $unitt = '';
        foreach ($units as $unit) {
            $selected = (($products['tbl_units'] != '') && ($unit->unit_id == $products['tbl_units']->unit_id)) ? 'selected' : '';
            $option .= '<option value="' . $unit->unit_id . '" ' . $selected . '>' . $unit->name . ' (' . $unit->sortname . ')</option>';
            if ($unit != '') {
                if ($selected != '') {
                    $unitt = $unit;
                }
            }
        }

        $data['unitOptions'] = $option;
        $data['user'] = $user;
        $data['unit'] = $unitt;

        $leads = $this->getProductLeads($id);
        $data['total'] = $leads['total'];
        $data['table'] = $leads['table'];

        return view('admin.products.show')->with("data", $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $products = Tbl_products::with('Tbl_units')->with('Tbl_productcategory')->with('Tbl_product_subcategory')->with('Tbl_product_forms')->find($id);
        //        $products_forms = Tbl_product_forms::where('pro_id', $products->pro_id)->first();
        //    echo json_encode($products);
        //        echo json_encode(Auth::user());
        //    exit();
        //  Tbl_product_forms
        $products_forms = (count($products->tbl_product_forms) > 0) ? $products->tbl_product_forms[0] : '';
        $pf_form_id = ($products_forms != '') ? $products_forms->form_id : 0;
        $data['product'] = $products;
        $uid = $products->uid;
        //  User

        $user = User::find($uid);
        $cr_id = '';
        if ($user == null) {
            $cr_id = Auth::user()->cr_id;
        } else {
            $cr_id = $user->cr_id;
        }
        $data['user'] = $products;

        //  Currency
        $currency = currency::find($cr_id);
        $data['currency'] = $currency;

        //  Units
        $units = Tbl_units::all();
        $option = '<option value="0">Select Unit</option>';
        foreach ($units as $unit) {
            $selected = ($unit->unit_id == (($products['tbl_units'] != null) ? $products['tbl_units']->unit_id : 0)) ? 'selected' : '';
            $option .= '<option value="' . $unit->unit_id . '" ' . $selected . '>' . $unit->name . ' (' . $unit->sortname . ')</option>';
        }
        $data['unitOptions'] = $option;

        //        echo json_encode($data);
        //        exit();
        //  Forms Option Tag
        $forms = Tbl_fb_leads::distinct('form_id')->get(['form_id']);   //Tbl_leads
        $formoptions = "<option value='0'>Select Form...</option>";
        if (count($forms) > 0) {
            foreach ($forms as $form) {
                $formExist = Tbl_product_forms::where('form_id', $form->form_id)->count();
                //            echo $formExist;
                //            echo '<br>';
                $selected = ($form->form_id == $pf_form_id) ? 'selected' : '';
                $assigned = "";
                $disabled = "";
                if ($formExist > 0) {
                    $disabled = "disabled";
                    $assigned = "(Assigned)";
                }
                $formoptions .= "<option value=" . $form->form_id . " " . $disabled . " " . $selected . ">" . $form->form_id . "&nbsp;<small>" . $assigned . "</small>" . "</option>";    // . "  " . $selected
            }
        }
        $formoptions .= "<option value='none'>None</option>";
        //        exit();
        $data['formoptions'] = $formoptions;
        //        echo json_encode($formoptions);
        //        exit();

        //  Product Category
        $procategory = Tbl_productcategory::all();
        $categoryoption = '<option value="">Select ...</option>';
        foreach ($procategory as $category) {
            $cat_selected = ($category->procat_id == $products->procat_id) ? 'selected' : '';
            $categoryoption .= '<option value="' . $category->procat_id . '" ' . $cat_selected . '>' . $category->category . '</option>';
        }
        $data['categoryoption'] = $categoryoption;

        //  Product Category

        $prosubcatOptions = '<option value="">Select ...</option>';
        if ($products->procat_id > 0) {
            $prosubcategory = Tbl_product_subcategory::where('procat_id', $products->procat_id)->get();
            foreach ($prosubcategory as $category) {
                $cat_selected = ($category->prosubcat_id == $products->prosubcat_id) ? 'selected' : '';
                $prosubcatOptions .= '<option value="' . $category->prosubcat_id . '" ' . $cat_selected . '>' . $category->category . '</option>';
            }
        }

        $data['prosubcatOptions'] = $prosubcatOptions;

        $companys = $this->getCompanyList();
        $companyoption = '<option value="">Select ...</option>';
        foreach ($companys as $company) {
            $comSelect = ($company->acc_id == $products->company) ? 'selected' : '';
            $companyoption .= '<option value="' . $company->acc_id . '" ' . $comSelect . '>' . $company->company . '</option>';
        }
        $data['companyoption'] = $companyoption;

        return view('admin.products.edit')->with("data", $data);
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
        //        echo json_encode($request->input());
        //        exit();
        $this->validate($request, [
            'name' => 'required|max:255|unique:tbl_products,name,' . $id . ',pro_id',
            'price' => 'required',
            'description' => 'required',
            'procat_id' => 'required|numeric',
            'prosubcat_id' => 'required|numeric',
            // 'picture' => 'required|image',
            'company' => 'required',
            'min_quantity' => 'required|numeric',

            // 'productid' => 'required|max:255',
            // 'productsku' => 'required|max:255',
            // 'qrcode' => 'required|max:255',
            // 'supply_price' => 'required|max:255',

            // 'location' => 'required|max:255',
            // 'stock' => 'required'
        ], [
            'min_quantity.required' => 'Minimum order of quantity is required',
            'min_quantity.numeric' => 'Check given value',
        ]);

        $active = ($request->input('active') != '') ? 1 : 0;
        $productName = $request->input('name');
        $form_id = $request->input('forms');
        //        echo $form_id;
        //        exit();

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
                return redirect('admin/products/' . $id . '/edit')->with('error', 'Please upload jpg, png and giff images only.');
            }
            //-------------Image Validation----------------------------------
            //            $file = $request->file('picture');
            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $file->move('uploads/products/', $name);  //public_path().
            $filename = '/uploads/products/' . $name;
            $slideimags[] = $filename;
        }


        // if ($request->hasfile('slideshowpics')) {

        //     // dd($request->file('slideshowpics'));
        //     // exit();

        //     foreach ($request->file('slideshowpics') as $simg) {
        //         // print_r($simg);
        //         $fileArray = array('slideshowpics' => $simg);
        //         $rules = array(
        //             'slideshowpics' => 'mimes:jpeg,jpg,png,gif|max:10000' // max 10000kb
        //         );
        //         $validator = Validator::make($fileArray, $rules);

        //         if ($validator->fails()) {
        //             return redirect('admin/products/edit')->with('error', 'Please upload jpg, png and giff images only.');
        //         }

        //         $sname = $this->RemoveSpecialChar($simg->getClientOriginalName());
        //         // $sname = $kname . '.' . $simg->getClientOriginalExtension();   //getClientOriginalName()
        //         $simg->move('uploads/products/', $sname);  //public_path().
        //         $slideimags[] = '/uploads/products/' . $sname;
        //     }
        // }

        // echo json_encode($slideimags);
        // exit();

        $store = ($request->input('store') != '') ? 1 : 0;
        $prosubcat_id = ($request->input('prosubcat_id') != '') ? $request->input('prosubcat_id') : 0;
        $slideshowpics = (count($slideimags) > 0) ? implode(",", $slideimags) : '';

        $slug = $this->createSlug($request->input('name'));

        $productid = 0;
        $productsku = 0;
        $qrcode = '';
        $supply_price = 0;    //$request->input('supply_price')
        $current_stock = 0;
        $location = '';
        $stock = 0;

        $product = Tbl_products::find($id);
        $uid = $product->uid;

        //        $exist = Tbl_products::whereRaw('LOWER(name) LIKE ? ', strtolower($productName))->where('pro_id', $id)->where('uid', $uid)->get();
        //
        //        if (count($exist) > 0) {
        //            return redirect('admin/products/' . $id . '/edit')->with('error', 'Given product already exists..!');
        //        }

        $product->name = $request->input('name');
        $product->price = $request->input('price');
        $product->unit = $request->input('units');
        $product->size = $request->input('size');
        $product->description = $request->input('description');
        $product->enable = $active;
        $product->store = $store;
        $product->procat_id = $request->input('procat_id');
        if ($filename != '') {
            $product->picture = $filename;
        }
        $product->prosubcat_id = $prosubcat_id; //$request->input('prosubcat_id');
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

        $product->save();

        if (($form_id != "") && ($form_id != 0) && ($form_id != 'none')) {
            $pro_form = Tbl_product_forms::where('pro_id', $id)->first();
            if (($pro_form != '') && ($pro_form->form_id != $form_id)) {
                $pro_form->form_id = $form_id;
                $pro_form->save();
            } else {
                Tbl_product_forms::create(array('pro_id' => $id, 'form_id' => $form_id));
            }
        }

        if (($form_id == 0) || ($form_id == 'none')) {
            $pro_form = Tbl_product_forms::where('pro_id', $id)->delete();
        }


        return redirect('admin/products')->with('success', 'Updated Successfully');
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

    public function delete($id)
    {
        $product = Tbl_products::find($id);
        $product->active = 0;
        $product->save();


        //  Deleting group products
        Tbl_group_products::where('pro_id', $id)->delete();

        return redirect('admin/products')->with('success', 'Deleted Successfully');
    }

    public function getProducts($id, $user_type)
    {

        $defaultCurrency = currency::where('status', 1)->first();

        $query = DB::table('tbl_products')->where('tbl_products.active', 1);
        if (($id > 0) && ($id != 'All')) {
            $query->where('tbl_products.uid', $id);
            $query->where('tbl_products.user_type', $user_type);
        }
        $query->leftJoin('tbl_product_category', 'tbl_products.procat_id', '=', 'tbl_product_category.procat_id');
        $query->leftJoin('tbl_units', 'tbl_products.unit', '=', 'tbl_units.unit_id');
        // $query->leftJoin('tbl_cart_orders', 'tbl_products.pro_id', '=', 'tbl_cart_orders.pro_id');
        $query->orderBy('tbl_products.pro_id', 'desc');
        $query->select(
            'tbl_products.*',
            'tbl_product_category.procat_id',
            'tbl_product_category.category',
            'tbl_units.name as uname',
            'tbl_units.sortname',
        );
        $products = $query->get();

        // echo json_encode($products);
        // exit(0);

        if (count($products) > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Price</th>';
            $formstable .= '<th>Category</th>';
            $formstable .= '<th>Size</th>';
            $formstable .= '<th>Brand</th>';
            $formstable .= '<th>Views</th>';
            $formstable .= '<th>Leads</th>';
            $formstable .= '<th>Status</th>';
            $formstable .= '<th>Featured</th>';
            $formstable .= '<th>Created At</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($products as $formdetails) {


                //                if ($id == 'All') {
                //                    $user = User::find($formdetails->uid);
                //                    $currency = currency::find($user->cr_id);
                //                }


                // $tbl_product_forms = (count($formdetails->tbl_product_forms) > 0) ? $formdetails->tbl_product_forms : '';

                $user = "";
                $sortname = ($formdetails->unit > 0) ? $formdetails->sortname : '';
                if ($formdetails->user_type == 1) {
                    $user = Admin::with('currency')->find($formdetails->uid);
                    $currency = ($user->currency != '') ? $user->currency : $defaultCurrency;
                }

                if ($formdetails->user_type == 2) {
                    $user = User::with('currency')->find($formdetails->uid);
                    $currency = ($user->currency != '') ? $user->currency : $defaultCurrency;
                }

                $storeStat = ($formdetails->store > 0) ? "On" : 'Off';

                $cartOrders = Tbl_leads::where('leadtype', 2)->where('active', 1)->where('pro_id', $formdetails->pro_id)->get();
                $orderCount = ($cartOrders != '') ? count($cartOrders) : 0;

                $featured = ($formdetails->featured > 0) ? 'checked' : '';


                $img = ($formdetails->picture) ? $formdetails->picture : '/uploads/default/products.jpg';
                $formstable .= '<tr>';
                $formstable .= '<td class="table-title">';
                $formstable .= '<img src="' . url($img) . '" class="avatar">';
                $formstable .= '<a href="' . url('admin/products/' . $formdetails->pro_id) . '">' . $formdetails->name . '</a>&nbsp;';
                $formstable .= '</td>';
                $formstable .= '<td><span>' . $currency->html_code . '</span>&nbsp;' . $formdetails->price . '</td>';
                $formstable .= '<td>' . $formdetails->category . '</td>';   // ($formdetails->tbl_productcategory != '') ? $formdetails->tbl_productcategory->category : ''
                $formstable .= '<td>' . $formdetails->size . ' ' . $sortname . '</span></td>';
                $formstable .= '<td>' . $formdetails->vendor . '</td>';
                $formstable .= '<td>' . $formdetails->views . '</td>';
                $formstable .= '<td><a href="' . url('admin/products/' . $formdetails->pro_id) . '">' . $orderCount . '</a></td>';
                $formstable .= '<td>' . $storeStat . '</td>';
                $formstable .= '<td><input type="checkbox" name="my-checkbox" ' . $featured . ' onclick="return changeFeaturedStatus(' . $formdetails->pro_id . ',' . $formdetails->featured . ')" data-bootstrap-switch></td>';    //checked
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item text-default text-btn-space" href="' . url('admin/products/' . $formdetails->pro_id . '/edit') . '">Edit</a>
                    <a class="dropdown-item text-default text-btn-space" href="' . url('admin/products/delete/' . $formdetails->pro_id) . '">Delete</a>
                  </div>
                </div>';

                $formstable .= '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }


        // echo $formstable;
        // exit();

        $data['total'] = count($products);
        $data['table'] = $formstable;

        return $data;
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->input('id');


        //  Deleting group products
        Tbl_group_products::whereIn('pro_id', $ids)->delete();

        return Tbl_products::whereIn('pro_id', $ids)->update(array('active' => 0));
    }

    public function getProductDetails($id)
    {
        return Tbl_products::with('Tbl_units')->with('Tbl_productcategory')->find($id);
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
        $categoryoption = '<option value="">Select ...</option>';
        foreach ($procategory as $category) {
            $categoryoption .= '<option value="' . $category->prosubcat_id . '">' . $category->category . '</option>';
        }
        return $categoryoption;
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


    public function export($type)
    {
        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        $useroptions = "<option value='0' selected>Select User</option>";

        $admins = Admin::orderby('id', 'asc')->get(['id', 'name']);
        foreach ($admins as $admin) {
            $useroptions .= "<option value=" . $admin->id . '-1'  . ">" . $admin->name . "</option>";   // " . $selected . "
        }

        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . '-2'  . ">" . $userdetails->name . "</option>";   // " . $selected . "
        }
        $data['useroptions'] = $useroptions;

        return view('admin.products.export')->with('data', $data);
    }

    public function exportData(Request $request)
    {
        $uid = $request->input('selectUser');
        if ($uid != 0) {
            $user = explode('-', $uid);
            $uid = $user[0];
            $type = $user[1];
            // echo json_encode($request->input());
            // exit();
            return Excel::download(new ProductsExport($uid, $type), 'products.xlsx');
        } else {
            return redirect('admin/products/export/csv')->with('error', 'Please select user');
        }
    }

    public function import($type)
    {
        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        $useroptions = "<option value='0' selected>Select User</option>";

        $admins = Admin::orderby('id', 'asc')->get(['id', 'name']);
        foreach ($admins as $admin) {
            $useroptions .= "<option value=" . $admin->id . '-1'  . ">" . $admin->name . "</option>";   // " . $selected . "
        }

        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . '-2'  . ">" . $userdetails->name . "</option>";   // " . $selected . "
        }
        $data['useroptions'] = $useroptions;

        return view('admin.products.import')->with('data', $data);
    }

    public function importData(Request $request)
    {
        // $uid = Auth::user()->id;
        // echo json_encode($request->input());

        $uid = $request->input('selectUser');
        $user_type = 0;
        if ($uid != 0) {
            $user = explode('-', $uid);
            $uid = $user[0];
            $user_type = $user[1];
        }

        // echo $user_type;
        // exit();

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
                return redirect('admin/products/import/csv')->with('error', 'Please upload .csv only.');
            }
            //-------------Image Validation----------------------------------

            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $path = $request->file('importFile')->getRealPath();
            $res = Excel::import(new ProductsImport($uid, $user_type), request()->file('importFile'));

            if ($res) {
                // echo 'Success';
                return redirect('admin/products')->with('success', 'Uploaded successfully');
            } else {
                // echo 'Failed';
                return redirect('admin/products/import/csv')->with('error', "Error ocurred. Try again later..!");
            }
        } else {
            return redirect('admin/products/import/csv')->with('error', 'Please upload file.');
        }
    }

    public function ajaxUpdateFeatureStatus(Request $request)
    {

        // echo json_encode($request->input());
        // exit();
        $id = $request->input('id');
        $status = $request->input('status');


        if ($status == 0) {
            $status = 1;
        } else {
            $status = 0;
        }

        $product = Tbl_products::find($id);
        $product->featured = $status;
        $product->save();

        return 'success';
    }


    public function getCompanyList()
    {
        $accounts = Tbl_Accounts::where('company', '!=', null)->where('active', 1)->orderBy('acc_id')
            ->groupBy('company')
            ->groupBy('acc_id')
            ->get(['acc_id', 'company']);

        // echo json_encode($accounts);
        // exit();

        return $accounts;
    }

    public function getInventoryList($uid, $user_type)
    {

        $defaultCurrency = currency::where('status', 1)->first();

        // $products = Tbl_products::where('active', 1)
        //     ->where('uid', $uid)
        //     ->orderBy('pro_id', 'desc')
        //     ->get();

        $query = DB::table('tbl_products')->where('active', 1);
        if ($uid > 0) {
            $query->where('uid', $uid);
            $query->where('user_type', $user_type);
        }
        $query->orderBy('pro_id', 'desc');
        $query->select(
            'tbl_products.*'
        );
        $products = $query->get();


        // echo json_encode($products);
        // exit();

        if (count($products) > 0) {
            $formstable = '<div class="table-responsive"><table id="example1" class="table">';
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
                $user = "";
                if ($formdetails->user_type == 1) {
                    $user = Admin::with('currency')->find($formdetails->uid);
                    $currency = ($user->currency != '') ? $user->currency : $defaultCurrency;
                }

                if ($formdetails->user_type == 2) {
                    $user = User::with('currency')->find($formdetails->uid);
                    $currency = ($user->currency != '') ? $user->currency : $defaultCurrency;
                }


                $img = ($formdetails->picture) ? $formdetails->picture : '/uploads/default/products.jpg';

                $supply_price = ($formdetails->supply_price > 0) ? $formdetails->supply_price : 0;

                $stockOptions = '<option value="0" ' . (($formdetails->current_stock == 0) ? 'selected' : '') . '>Out of Stock</option>';
                $stockOptions .= '<option value="1" ' . (($formdetails->current_stock == 1) ? 'selected' : '') . '>Low Stock</option>';
                $stockOptions .= '<option value="2" ' . (($formdetails->current_stock == 2) ? 'selected' : '') . '>In Stock</option>';

                $csid = "'" . "cs" . $formdetails->pro_id . "'";

                $formstable .= '<tr>';
                $formstable .= '<td class="table-title">';
                $formstable .= '<img src="' . url($img) . '" class="avatar">';
                $formstable .= '<a href="' . url('admin/products/inventory/show/' . $formdetails->pro_id) . '">' . $formdetails->name . '</a>&nbsp;';
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
                    <li><a class="dropdown-item text-default text-btn-space" href="' . url('admin/products/inventory/edit/' . $formdetails->pro_id) . '">Edit</a></li>
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

    public function getInventory()
    {
        $id = 'All';
        $user_type = 0;

        $data = $this->getInventoryList($id, $user_type);

        $uid = Auth::user()->id;

        $cr_id = Auth::user()->cr_id;
        $currency = currency::find($cr_id);


        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        $uid = 'All';
        $useroptions = "<option value='All' selected>All</option>";

        $admins = Admin::orderby('id', 'asc')->get(['id', 'name']);
        foreach ($admins as $admin) {
            $useroptions .= "<option value=" . $admin->id . '-1'  . ">" . $admin->name . "</option>";   // " . $selected . "
        }

        // $useroptions .= "<option value='" . Auth::user()->id . '-1' . "' >" . Auth::user()->name . "</option>";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . '-2'  . ">" . $userdetails->name . "</option>";   // " . $selected . "
        }
        $data['useroptions'] = $useroptions;

        // $uid = Auth::user()->id;

        return view('admin.products.inventory')->with("data", $data);
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

        return view('admin.products.show_inventory')->with("data", $data);
    }

    public function editInventory($id)
    {
        $products = Tbl_products::find($id);

        //    echo json_encode($products);
        //    exit();

        $data['product'] = $products;


        $companys = $this->getCompanyList();
        $companyoption = '<option value="">Select ...</option>';
        foreach ($companys as $company) {
            $comSelect = ($company->acc_id == $products->company) ? 'selected' : '';
            $companyoption .= '<option value="' . $company->acc_id . '" ' . $comSelect . '>' . $company->company . '</option>';
        }
        $data['companyoption'] = $companyoption;


        return view('admin.products.edit_inventory')->with("data", $data);
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
            'company' => 'required',
            'location' => 'required|max:255'
        ]);

        $current_stock = 0;

        $formdata = array(
            'productid' => $request->input('productid'),
            'productsku' => $request->input('productsku'),
            'qrcode' => $request->input('qrcode'),
            'supply_price' => $request->input('supply_price'),
            'current_stock' => $current_stock,
            'company' => $request->input('company'),
            'location' => $request->input('location')
        );

        $upres = Tbl_products::where('pro_id', $id)->update($formdata);

        if ($upres) {
            return redirect('/admin/products/inventory/list')->with('success', 'Updated Successfully');
        } else {
            return redirect('/admin/products/inventory/list')->with('error', 'Error occurred. Please try again...!');
        }
    }

    public function getUserInventory(Request $request)   //
    {
        $uid = $request->input('uid');
        // echo json_encode($uid);
        // exit();

        if ($uid != 'All') {
            $userdet = explode("-", $uid);
            $userid = $userdet[0];
            $usertype = $userdet[1];
        } else {
            $userid = $uid;
            $usertype = 0;
        }
        // echo $userid . ' ' . $usertype;
        $product = new ProductController();
        $data = $product->getInventoryList($userid, $usertype);
        return json_encode($data);
    }

    public function getProductAnalytics()
    {
        return $this->getSearchKeywordAnalytics();
    }

    public function getSearchKeywordAnalytics()
    {
        $keywords = Tbl_search_keywords::all();
        $total = count($keywords);
        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Count</th>';
            $formstable .= '<th>No of Results</th>';
            $formstable .= '<th>Last Searched</th>';
            // $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($keywords as $keyword) {
                $formstable .= '<tr>';
                $formstable .= '<td>' . $keyword->keyword . '</td>';
                $formstable .= '<td>' . $keyword->counter . '</td>';
                $formstable .= '<td>' . $keyword->results . '</td>';
                $formstable .= '<td>' . date('d-m-Y h:i', strtotime($keyword->updated_at)) . '</td>';
                // $formstable .= '<td>';
                // $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2"  href="' . url('admin/states/' . $keywords->id . '/edit') . '">Edit</a>';
                // $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2"  href="#">Delete</a>';
                // $formstable .= '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }
        $data['total'] = $total;
        $data['table'] = $formstable;

        // return $data;
        return view('admin.products.s_ana_keyword')->with("data", $data);
    }

    public function getProductLeads($proId)
    {
        $leads = Tbl_leads::where('leadtype', 2)->where('active', 1)->where('pro_id', $proId)->orderby('ld_id', 'desc')->get();
        $total = ($leads != '') ? count($leads) : 0;
        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Email</th>';
            $formstable .= '<th>Mobile</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($leads as $lead) {
                $formstable .= '<tr>';
                $formstable .= '<td><a href="' . url('admin/leads/' . $lead->ld_id) . '">' . substr($lead->first_name . ' ' . $lead->last_name, 0, 25) . '</a></td>';
                $formstable .= '<td>' . $lead->email . '</td>';
                $formstable .= '<td>' . $lead->mobile . '</td>';
                $formstable .= '<td>' . date('d-m-Y h:i', strtotime($lead->created_at)) . '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }
        $data['total'] = $total;
        $data['table'] = $formstable;

        return $data;
    }
}

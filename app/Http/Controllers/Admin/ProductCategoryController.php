<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

//---------Models---------------
use App\Tbl_productcategory;
use App\Tbl_products;

class ProductCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $accounttypes = Tbl_productcategory::all();
        $total = count($accounttypes);
        if (count($accounttypes) > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Category</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($accounttypes as $types) {

                $img = ($types->picture) ? $types->picture : '/uploads/default/category-default.png';

                $formstable .= '<tr>';
                $formstable .= '<td><img src="' . url($img) . '" alt="' . $types->category . '" width="25" height="25"/>&nbsp;';
                $formstable .= $types->category . '</td>';
                $formstable .= '<td>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2"  href="' . url('admin/productcategorys/' . $types->procat_id . '/edit') . '">Edit</a>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2"  href="' . url('admin/productcategorys/delete/' . $types->procat_id) . '">Delete</a>';
                $formstable .= '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        //        echo $formstable;

        $data['total'] = $total;
        $data['table'] = $formstable;

        return view('admin.productcategory.index')->with("data", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.productcategory.create');
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
        // echo json_encode($request->input());
        // exit();

        $this->validate($request, [
            'category' => 'required|max:255|unique:tbl_product_category',
        ]);

        $filename = '';
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
                return redirect('admin/productcategorys/create')->with('error', 'Please upload jpg, png and giff images only.');
            }
            //-------------Image Validation----------------------------------

            $file = $request->file('picture');
            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $file->move('uploads/product_category/', $name);  //public_path().
            $filename = '/uploads/product_category/' . $name;
        }

        $slug = $this->createSlug($request->input('category'));

        $formdata = array(
            'category' => $request->input('category'),
            'slug' => $slug,
            'picture' => $filename
        );

        $types = Tbl_productcategory::create($formdata);

        if ($types->procat_id > 0) {
            return redirect('admin/productcategorys')->with('success', 'Created Successfully...!');
        } else {
            return redirect('admin/productcategorys')->with('error', 'Error occurred. Please try again...!');
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
        $accounttype = Tbl_productcategory::find($id);
        return view('admin.productcategory.edit')->with('data', $accounttype);
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
        // echo json_encode($request->input());
        // exit();

        $this->validate($request, [
            'category' => 'required|max:255|unique:tbl_product_category,category,' . $id . ',procat_id',
        ]);

        $slug = $this->createSlug($request->input('category'));

        $filename = '';
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
                return redirect('admin/productcategorys/create')->with('error', 'Please upload jpg, png and giff images only.');
            }
            //-------------Image Validation----------------------------------

            $file = $request->file('picture');
            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $file->move('uploads/product_category/', $name);  //public_path().
            $filename = '/uploads/product_category/' . $name;
        }

        $formdata = array(
            'category' => $request->input('category'),
            'slug' => $slug,
            'picture' => $filename
        );

        $res = Tbl_productcategory::where('procat_id', $id)->update($formdata);

        if ($res > 0) {
            return redirect('admin/productcategorys')->with('success', 'Updated Successfully...!');
        } else {
            return redirect('admin/productcategorys')->with('error', 'Error occurred. Please try again...!');
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

    public function delete($id)
    {
        $types = Tbl_productcategory::find($id);
        $types->delete();
        return redirect('admin/productcategorys')->with('success', 'Deleted Successfully...!');
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
        return Tbl_productcategory::select('slug')->where('slug', 'like', $slug . '%')
            ->where('procat_id', '<>', $id)
            ->get();
    }
}

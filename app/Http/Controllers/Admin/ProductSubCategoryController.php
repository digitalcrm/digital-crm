<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
//---------Models---------------
use App\Tbl_productcategory;
use App\Tbl_product_subcategory;

use App\Imports\ProductSubCategoryImport;
use Maatwebsite\Excel\Facades\Excel;

class ProductSubCategoryController extends Controller
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
        $accounttypes = Tbl_product_subcategory::with('tbl_product_category')->paginate(100);
        // echo json_encode($accounttypes);
        // exit();
        $total = count($accounttypes);
        if (count($accounttypes) > 0) {
            // $formstable = '<table id="example1" class="table">';
            $formstable = '<table id="subcattable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Category</th>';
            $formstable .= '<th>Main Category</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($accounttypes as $types) {
                $formstable .= '<tr>';
                $formstable .= '<td>' . $types->category . '</td>';
                $formstable .= '<td>' . $types->tbl_product_category->category . '</td>';
                $formstable .= '<td>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2"  href="' . url('admin/productsubcategorys/' . $types->prosubcat_id . '/edit') . '">Edit</a>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2"  href="' . url('admin/productsubcategorys/delete/' . $types->prosubcat_id) . '">Delete</a>';
                $formstable .= '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>
            <tfoot><tr><td>
            '.$accounttypes->onEachSide(5)->links().'
            </td></tr></tfoot>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        //        echo $formstable;

        $data['total'] = $total;
        $data['table'] = $formstable;

        return view('admin.productsubcategory.index')->with("data", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $cats = Tbl_productcategory::all();
        $catoptions = "<option value=''>Select Product Category</option>";
        foreach ($cats as $cat) {
            $catoptions .= "<option value='" . $cat->procat_id . "'>" . $cat->category . "</option>";
        }

        return view('admin.productsubcategory.create')->with('data', $catoptions);
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
            'category' => 'required|max:255|unique:tbl_product_subcategory',
            'procat_id' => 'required',
        ], [
            'category.required' => 'Please enter category',
            'category.unique' => 'Given category already exists',
            'procat_id.required' => 'Please select product category',

        ]);

        $slug = $this->createSlug($request->input('category'));

        $formdata = array(
            'category' => $request->input('category'),
            'procat_id' => $request->input('procat_id'),
            'slug' => $slug
        );

        $types = Tbl_product_subcategory::create($formdata);

        if ($types->prosubcat_id > 0) {
            return redirect('admin/productsubcategorys')->with('success', 'Created Successfully...!');
        } else {
            return redirect('admin/productsubcategorys')->with('error', 'Error occurred. Please try again...!');
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

        $scategory = Tbl_product_subcategory::find($id);
        $data['scategory'] = $scategory;

        $cats = Tbl_productcategory::all();
        $catoptions = "<option value=''>Select Product Category</option>";
        foreach ($cats as $cat) {
            $cselected = ($scategory->procat_id == $cat->procat_id) ? 'selected' : '';
            $catoptions .= "<option value='" . $cat->procat_id . "' " . $cselected . ">" . $cat->category . "</option>";
        }
        $data['catoptions'] = $catoptions;

        return view('admin.productsubcategory.edit')->with('data', $data);
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
            'category' => 'required|max:255|unique:tbl_product_subcategory,category,' . $id . ',prosubcat_id',
            'procat_id' => 'required',
        ], [
            'category.required' => 'Please enter category',
            'category.unique' => 'Given category already exists',
            'procat_id.required' => 'Please select product category',

        ]);

        $slug = $this->createSlug($request->input('category'));

        $formdata = array(
            'category' => $request->input('category'),
            'procat_id' => $request->input('procat_id'),
            'slug' => $slug
        );

        $res = Tbl_product_subcategory::where('prosubcat_id', $id)->update($formdata);

        if ($res) {
            return redirect('admin/productsubcategorys')->with('success', 'Updated Successfully...!');
        } else {
            return redirect('admin/productsubcategorys')->with('error', 'Error occurred. Please try again...!');
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
        $types = Tbl_product_subcategory::find($id);
        $types->delete();
        return redirect('admin/productsubcategorys')->with('success', 'Deleted Successfully...!');
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
        return Tbl_product_subcategory::select('slug')->where('slug', 'like', $slug . '%')
            ->where('procat_id', '<>', $id)
            ->get();
    }
    public function import($type)
    {
        $procats = Tbl_productcategory::all();
        $useroptions = "<option value=''>Select Product Category</option>";
        foreach ($procats as $procat) {
            $useroptions .= "<option value=" . $procat->procat_id . ">" . $procat->category . "</option>";   // " . $selected . "
        }
        $data['useroptions'] = $useroptions;

        return view('admin.productsubcategory.import')->with('data', $data);
    }

    public function importData(Request $request)
    {
        // echo json_encode($request->input());
        // exit();

        $procat_id = $request->input('procat_id');

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
            $res = Excel::import(new ProductSubCategoryImport($procat_id), request()->file('importFile'));

            if ($res) {
                // echo 'Success';
                return redirect('admin/productsubcategorys')->with('success', 'Uploaded successfully');
            } else {
                // echo 'Failed';
                return redirect('admin/productsubcategorys/import/csv')->with('error', "Error ocurred. Try again later..!");
            }
        } else {
            return redirect('admin/productsubcategorys/import/csv')->with('error', 'Please upload file.');
        }
    }
}

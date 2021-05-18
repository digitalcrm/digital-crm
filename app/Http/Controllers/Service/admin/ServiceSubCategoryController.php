<?php

namespace App\Http\Controllers\Service\admin;

use App\Servcategory;
use App\ServSubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Imports\ServiceSubcategoryImport;
use Maatwebsite\Excel\Facades\Excel;

class ServiceSubCategoryController extends Controller
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
        $subcategories = ServSubCategory::latest()->paginate(10);
        return view('service.admin.servicesubcategory.index', compact('subcategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Servcategory::latest()->get(['id','name']);

        return view('service.admin.servicesubcategory.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            if ($request->hasFile('fileImport')) {
                
                $request->validate([
                    'fileImport' => 'required|file|max:1024',
                    'servcategory_id' => 'required|numeric|not_in:0',
                ]);
    
                return $this->importSubCategory();
            } 
            else {
                $validatedData = $request->validate([
                    'name' => 'required|string|max:255',
                    'servcategory_id' => 'required|numeric|not_in:0',
                ]);
        
                ServSubCategory::create($validatedData);
                return redirect(route('service-subcategories.index'))->withMessage('Successfully Created');
            }
        } catch (\Throwable $th) {
            return back()->withError('something went wrong! '. $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ServSubCategory  $service_subcategory
     * @return \Illuminate\Http\Response
     */
    public function show(ServSubCategory $service_subcategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ServSubCategory  $service_subcategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ServSubCategory $service_subcategory)
    {
        $categories = Servcategory::latest()->get(['id','name']);

        return view('service.admin.servicesubcategory.edit',compact(['service_subcategory','categories']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ServSubCategory  $service_subcategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ServSubCategory $service_subcategory)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'servcategory_id' => 'required|numeric|not_in:0',
        ]);

        $service_subcategory->update($validatedData);

        return redirect(route('service-subcategories.index'))->withMessage('Successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ServSubCategory  $service_subcategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServSubCategory $service_subcategory)
    {
        $service_subcategory->delete();

        return redirect()->back()->withSuccess($service_subcategory->name.' Deleted Successfully');
    }

    private function importSubCategory()
    {   
        $import = new ServiceSubcategoryImport(request('servcategory_id'));

        $import->import(request()->file('fileImport'));
        
        if ($import->failures()->isNotEmpty()) {
            return back()->withFailures($import->failures());
        }

        return redirect(route('service-subcategories.index'))->withMessage('Successfully Imported');
    }
}

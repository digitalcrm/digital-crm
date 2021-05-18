<?php

namespace App\Http\Controllers\Service\admin;

use App\Http\Controllers\Controller;
use App\Servcategory;
use Illuminate\Http\Request;

class ServiceCategoryController extends Controller
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
        $categories = ServCategory::withCount('servSubCategory')->latest()->paginate(10);
        return view('service.admin.servicecategory.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('service.admin.servicecategory.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:1024',
        ]);

        if (request()->hasFile('image')) {
            $validatedData['image'] = request('image')->storePublicly('serviceCategory', 'public');
        }

        Servcategory::create($validatedData);

        return redirect(route('service-categories.index'))->withMessage('Successfully Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Servcategory  $servcategory
     * @return \Illuminate\Http\Response
     */
    public function show(Servcategory $servcategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Servcategory  $service_category
     * @return \Illuminate\Http\Response
     */
    public function edit(Servcategory $service_category)
    {
        return view('service.admin.servicecategory.edit', compact('service_category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Servcategory  $service_category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Servcategory $service_category)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:1024',
        ]);

        if (request()->hasFile('image')) {
            $validatedData['image'] = request('image')->storePublicly('serviceCategory', 'public');
        }

        $service_category->update($validatedData);

        return redirect(route('service-categories.index'))->withMessage('Successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Servcategory  $service_category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Servcategory $service_category)
    {
        $service_category->delete();

        return redirect()->back()->withSuccess($service_category->name.' Deleted Successfully');
    }
}

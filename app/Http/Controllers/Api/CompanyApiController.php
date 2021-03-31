<?php

namespace App\Http\Controllers\Api;

use App\Company;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyCollection;
use App\Http\Resources\CompanyResource;
use Illuminate\Http\Request;

class CompanyApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comapnies = Company::with(['tbl_products'])->isActive()->latest()->get();
        return new CompanyCollection($comapnies);
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
     * @param  int  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        $company->increment('views');

        $companyDetail = $company->with(['tbl_countries', 'tbl_states', 'tbl_products'])->findOrFail($company->id);

        return new CompanyResource($companyDetail);
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

    public function getCompaniesOptionList()
    {
        $uid = auth()->user()->id;

        $companies = Company::where('user_id', $uid)->get();

        return $companies;
    }
}

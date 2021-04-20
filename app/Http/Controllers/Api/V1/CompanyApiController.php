<?php

namespace App\Http\Controllers\Api\V1;

use App\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\CompanyCollection;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\StoreCompanyRequest;

class CompanyApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only(['store','update','destroy']);
    }

    public $paginateData = 10;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comapnies = Company::whereHas('users', function ($query) {
            $query->where('active', 1);
        })->whereHas('tbl_products', function (Builder $query) {
            $query->where('active', 1)->where('enable', 1);
        })
        ->when(request('sort'), function($q) {
            $this->paginateData = request('sort');
        })
        ->isActive()
        ->latest()
        ->paginate($this->paginateData)->withQueryString();

        return new CompanyCollection($comapnies);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCompanyRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('c_logo')) {
            $validatedData['c_logo'] = request('c_logo')->storePublicly('companyLogo', 'public');
        }

        if ($request->hasFile('document')) {
            $validatedData['document'] = request('document')->storePublicly('companyCatalog', 'public');
        }

        if ($request->hasFile('c_cover')) {
            $validatedData['c_cover'] = request('c_cover')->storePublicly('companyCover', 'public');
        }

        if (empty($request->employees)) {
            $validatedData['employees'] = 0;
        }

        $createdData = auth()->user()->company()->create($validatedData);
        
        $response = new CompanyResource($createdData);

        return response()->json(['status' => 'success','company' => $response]);
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

        $companyDetail = $company->whereHas('users', function ($query) {
            $query->where('active', 1);
        })->whereHas('tbl_products', function (Builder $query) {
            $query->where('active', 1)->where('enable', 1);
        })->with(['tbl_countries', 'tbl_states', 'tbl_products'])->findOrFail($company->id);

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

    // public function getCompaniesOptionList()
    // {
    //     $uid = auth()->user()->id;

    //     $companies = Company::where('user_id', $uid)->get();

    //     return $companies;
    // }
}

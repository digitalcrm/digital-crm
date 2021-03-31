<?php

namespace App\Http\Controllers\APi\V1;

use App\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyCollection;

class CompanyV1Controller extends Controller
{
    public function byCity(Request $request)
    {
        $comapnies = Company::with(['users:id,name,email', 'tbl_products'])->when(request('city'), function ($query) {
            return $query->search(request('city'));
        })->isActive()->latest()->get();

        return new CompanyCollection($comapnies);
    }
}

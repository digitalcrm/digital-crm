<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function profile()
    {
        $profile = Auth::user();
        return new UserResource($profile);
    }

    public function product()
    {
        try {
            if(request('limit')) {
                $product = auth()->user()->tbl_products()->latest()->paginate(request('limit'))->withQueryString();
            } else {
                $product = auth()->user()->tbl_products()->latest()->get();
            }
            return UserResource::collection($product);
        
        } catch (\Throwable $th) {
            return abort(404);
        }
        
    }

    public function company()
    {
        try {
            if (request('limit')) {
                $company = auth()->user()->company()->latest()->paginate(request('limit'))->withQueryString();
            } else {
                $company = auth()->user()->company()->latest()->get();
            }
            return UserResource::collection($company);

        } catch (\Throwable $th) {
            return abort(404);
        }
    }
}

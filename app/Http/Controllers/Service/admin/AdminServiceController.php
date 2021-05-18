<?php

namespace App\Http\Controllers\Service\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        return view('service.admin.index');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class SettingsController extends Controller {

    public function __construct() {
        $this->middleware('auth:admin');
    }

    public function index() {
        return view('admin.settings.index');
    }
    public function ticketsettings() {
        return view('admin.settings.ticketsetting');
    }

}

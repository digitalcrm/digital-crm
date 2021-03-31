<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('test:settings', ['only' => ['index']]);
    }

    public function index()
    {
        return view('auth.settings.index');
    }
}

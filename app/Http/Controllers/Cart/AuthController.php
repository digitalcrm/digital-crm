<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Session;
// Models

use App\Consumer;
use App\Tbl_products;


class AuthController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('consumerauth:cart');
    // }
    //
    public function loginForm()
    {
        return view('cart.login');
    }

    public function loginAction(Request $request)
    {
        // echo json_encode($request->input());
        // exit();
        $email = $request->input('email');
        $password = $request->input('password');
        // $hash_password = Hash::make($password);

        //  ->where('password', $hash_password)
        $consumer = Consumer::where('email', $email)->first();

        if ($consumer && Hash::check($request->password, $consumer->password)) {
            $request->session()->put('user', $consumer);
            return redirect('/cart/products')->with('success', 'Login Successfully');
        } else {
            return redirect('/cart')->with('error', 'Please check credentials');
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect('cart');
    }

    public function registerForm()
    {
        return view('cart.register');
    }

    public function registerAction(Request $request)
    {
        echo json_encode($request->input());
    }

    // public function getProducts()
    // {
    // }
}

<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\tbl_verifyuser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Rules\Captcha;
//------------------Controllers----------------------
use App\Http\Controllers\MailController;
use App\Tbl_features;

class RegisterController extends Controller
{
    /*
      |--------------------------------------------------------------------------
      | Register Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles the registration of new users as well as their
      | validation and creation. By default this controller uses a trait to
      | provide this functionality without requiring any additional code.
      |
     */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        $messages = array(
            'required' => ':attribute field is required.',
            'unique' => 'Email Id is already exists.',
            'min' => 'The field has to be 8 chars long!',
        );

        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'g-recaptcha-response' => 'required', new Captcha(),
        ], $messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {


        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $verifyUser = tbl_verifyuser::create([
            'uid' => $user->id,
            'token' => strtotime(date('Ymdhis'))
        ]);

        //  This below features add moved in User model
        // $addFeatures = Tbl_features::create([
        //     'uid' => $user->id,
        // ]);

        // $mail = new MailController();
        // $mailres = $mail->registrationMailUser($user);

        return $user;




        //----------------------------
        //        return User::create([
        //                    'name' => $data['name'],
        //                    'email' => $data['email'],
        //                    'password' => Hash::make($data['password']),
        //        ]);
    }
}

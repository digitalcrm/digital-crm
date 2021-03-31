<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
//  Import Base Controller
use App\Http\Controllers\Api\BaseController;
//  Import Models
use App\User;

class AuthController extends BaseController {

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'email' => 'required|email|unique:users',
                    'password' => 'required|min:6',
                    'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }

        $input = $request->all();
        $input['passport'] = bcrypt($input['password']);
        $user = User::create($input);
        $data['token'] = $user->createToken('crm')->accessToken;
        $data['result'] = $user;

        return $this->sendResponse($data, 'Registered Successfully');
    }

}

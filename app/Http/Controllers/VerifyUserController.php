<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Admin;
use App\Client;
use App\tbl_verifyuser;
use App\Tbl_Verifyadmin;
use App\Tbl_verifyclient;

class VerifyUserController extends Controller
{

    public function verifyUser($token)
    {

        //        echo json_encode($token);


        $verifyUser = tbl_verifyuser::where('token', $token)->with('users')->first();

        //        echo json_encode($verifyUser);
        //        exit();

        if (isset($verifyUser)) {
            $user = $verifyUser->users;

            if (!$user->verified) {

                $userdetails = User::find($user->id);

                $userdetails->verified = 1;
                $userdetails->save();
                $status = "Your email is verified. You can now login.";
            } else {
                $status = "Your email is already verified. You can now login.";
            }
        } else {
            return redirect('/login')->with('error', "Sorry your email cannot be identified.");
        }

        return redirect('/login')->with('success', $status);
    }

    public function verifyAdmin($token)
    {

        //        echo json_encode($token);


        $verifyUser = Tbl_Verifyadmin::where('token', $token)->with('Admin')->first();

        // echo json_encode($verifyUser);
        // exit();

        if (isset($verifyUser)) {
            $user = $verifyUser->admin;
            // echo json_encode($user);
            // exit();

            if (!$user->verified) {



                $userdetails = Admin::find($user->id);

                $userdetails->verified = 1;
                $userdetails->save();
                $status = "Your email is verified. You can now login.";
            } else {
                $status = "Your email is already verified. You can now login.";
            }
        } else {
            return redirect('/admin')->with('error', "Sorry your email cannot be identified.");
        }

        return redirect('/admin')->with('success', $status);
    }

    public function verifyClient($token)
    {

        //        echo json_encode($token);


        $verifyUser = Tbl_verifyclient::where('token', $token)->with('client')->first();

        // echo json_encode($verifyUser);
        // exit();
        $status = '';
        if (isset($verifyUser)) {
            $user = $verifyUser->client;


            if (!$user->verified) {
                // echo json_encode($user);
                // exit();
                $userdetails = Client::find($user->id);

                $userdetails->verified = 1;
                $userdetails->save();
                $status = "Your email is verified. You can now login.";
            } else {
                $status = "Your email is already verified. You can now login.";
                // echo $status;
                // exit();
            }
        } else {
            return redirect('/clients')->with('error', "Sorry your email cannot be identified.");
        }
        // echo $status;
        // exit();
        return redirect('/clients')->with('success', $status);
    }
}

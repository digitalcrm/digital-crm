<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Admin;
use App\currency;
use App\Tbl_leads;
use App\Tbl_Accounts;
use App\Tbl_leadstatus;
use App\tbl_verifyuser;
use App\Tbl_accounttypes;
use App\Tbl_industrytypes;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\AccountResourceCollection;


class apiController extends Controller
{

    public $successStatus = 200;
    
    public function __construct()
    {
        $this->middleware('auth:api')->only(['logout','profile']);
    }
    /*
        Admin Login/Register
    */
    public function adminlogin(Request $req)
    {
        #Not working this comment code leter will fixed
        // if(Auth::attempt(['email' => request('email'), 'password' => request('password')]))
        // {
        //     $user = Auth::Admin();
        //     $success['token'] =  $user->createToken('Admin_Token')->accessToken;
        //     return response()->json(['success' => $success], 200);
        // }
        // else
        // {
        //     return response()->json(['error'=>'Unauthenticated'], 401);
        // }

        $user = Admin::where('email', $req->email)->first();
        if ($user != null) {
            if (Hash::check($req->password, $user->password)) {
                return response()->json($data = [
                    'status' => 200,
                    'msg' => 'Success',
                    'user' => Admin::where('id', $user->id)->select('*')->first()
                ]);
            } else {
                return response()->json($data = [
                    'status' => 201,
                    'msg' => 'Wrong Credentials'
                ]);
            }
        } else {
            return response()->json($data = [
                'status' => 400,
                'msg' => 'Not Registered'
            ]);
        }
    }

    public function adminregister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:8',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = Admin::create($input);
        $token =  $user->createToken('RegisterAdminToken')->accessToken;
        $name =  $user->name;
        return response()->json(['success' => $token, 'username' => $name], $this->successStatus);
    }


    /*
    User Login/Register
    */
    public function userlogin()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::User();

            $secrets =  $user->createToken('client-token');
            $token = $secrets->accessToken;
            $expires = $secrets->token->expires_at->diffInSeconds(now());

            return response()->json(['token_type' => 'Bearer', 'expires_in' => $expires, 'access_token' => $token, 'clientData' => $user], 200);
        } else {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
    }

    public function userregister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'mobile' => 'required|digits:10|unique:users,mobile',
            'company' => 'required|max:55|string',
            'password' => 'required|string|min:8',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $input = $request->all();

        $input['password'] = Hash::make($input['password']);
        $input['cr_id'] = 47;
        $input['country'] = 101;

        $user = User::create($input);

        $user->company()->create([
            'c_name' => $input['company'],
            'personal_name' => $input['name'],
            'c_email' => $input['email'],
            'c_mobileNum' => $input['mobile'],
            'c_whatsappNum' => $input['mobile'],
        ]);

        $verifyUser = tbl_verifyuser::create([
            'uid' => $user->id,
            'token' => strtotime(date('Ymdhis'))
        ]);

        $token =  $user->createToken('register-token')->accessToken;

        $name =  $user->name;

        return response()->json(['success' => $token, 'username' => $name, 'message' => 'User Registered Successfully'], $this->successStatus);
    }

    public function details()
    {
        $_all_users = User::all();
        return response()->json(['success' => $_all_users]);
    }

    public function logout(Request $request)
    {
        #This will log the user out from the current device where he requested to log out
        // $access_token = $request->user()->token();
        // $access_token->revoke();
        // return response()->json(['success'=>'succesfully logout'], $this->successStatus);

        #If you want to log out from all the devices where he's logged in. Then do this instead.Or in function no need of Request $request for this case.

        DB::table('oauth_access_tokens')
            ->where('user_id', Auth::user()->id)
            ->update([
                'revoked' => true
            ]);
        return response()->json(['success' => 'succesfully logout from all devices'], $this->successStatus);
    }

    public function profile(User $profile)
    {
        // $profile = Auth::user();
        // return response()->json(['user'=>$profile]);

        return new UserResource($profile);
    }

    public function updateuserdetails(Request $request, User $user)
    {
        $userdata = [
            'name' => $request->name,
            'mobile' => $request->mobile,
            'jobtitle' => $request->jobtitle,
        ];
        // $userdata = $request->all();

        if ($request->hasfile('picture')) {
            $file = $request->file('picture');
            $name = time() . '.' . $file->getClientOriginalExtension();
            $file->move('uploads/profile/', $name);
            $filename = '/uploads/profile/' . $name;
            $userdata['picture'] = $filename;
        }

        $user->update($userdata);

        // dd($user);

        return response()->json(['updated Data' => $userdata, 'message' => 'Profile Successfully Updated ' . $user->name], Response::HTTP_CREATED);
    }
    
    public function getaccountDetails()
    {
        //for each login user account details

        $acc_details = Tbl_Accounts::where('uid', auth()->user()->id)->get();

        return response()->json(['Account_Details' => $acc_details], $this->successStatus);
    }

    public function getleadDetails()
    {
        //for each login user account details

        $lead_details = Tbl_leads::where('uid', auth()->user()->id)->get();

        return response()->json(['Lead_Details' => $lead_details], $this->successStatus);
    }
    /**
     * @return AccountResourceCollection
     */
    public function getaccountLists()
    {
        // $acc_lists = Tbl_Accounts::where('active','=',1)->distinct()->pluck('name');
        // return response()->json(['tbl_accounts'=>$acc_lists_all], $this->successStatus);

        return new AccountResourceCollection(Tbl_Accounts::paginate(10));
    }

    /**
     * @return LeadResourceCollection
     */
    public function getleadLists()
    {
        // $acc_lists = Tbl_Accounts::where('active','=',1)->distinct()->pluck('name');
        // $lead_lists_all = Tbl_leads::get();
        // return response()->json(['tbl_leads'=>$lead_lists_all], $this->successStatus);

        return new AccountResourceCollection(Tbl_leads::paginate(10));
    }
}

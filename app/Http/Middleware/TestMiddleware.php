<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Support\Facades\DB;

//  Models
use App\User;
use App\Admin;
use App\Tbl_Admin_Permissions;
use App\Tbl_Permissions;
use App\Tbl_features;

class TestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,  $permit)
    {
        // echo 'Permission : ' . $permit;
        // exit();

        $admin_id = Auth::user()->id;
        $user_status = Auth::user()->user_status;


        if (($user_status == 0) || ($user_status == 1)) {

            $feature = Tbl_features::where($permit, 1)->where('uid', $admin_id)->first();
            // echo json_encode($feature);
            // exit();

            if ($feature != '') {
                return $next($request);
            } else {
                return redirect('dashboard')->with('error', "You don't have access...");
            }
        } else if ($user_status == 2) { //$adDetails->user_status

            // echo $adDetails->user_status;
            // exit();
            return $next($request);
        } else if ($user_status == 3) {    //adDetails->user_status
            $adDetails = Admin::find($admin_id);
            $permits = is_array($permit)
                ? $permit
                : explode('|', $permit);

            // echo json_encode($permits);
            // exit();

            $permissions = Tbl_Permissions::where('name', $permits)->pluck('permission_id')->first();
            // echo $permissions;
            // exit();

            if ($permissions != '') {

                $adpermissions = Tbl_Admin_Permissions::where('admin_id', $admin_id)->where('permission_id', $permissions)->first();
                // echo json_encode($adpermissions);
                // exit();

                if ($adpermissions != '') {
                    return $next($request);
                } else {
                    return redirect('admin/dashboard')->with('error', "You don't have access...");
                }
            } else {
                return $next($request);
            }
        } else {
            return redirect('admin/dashboard')->with('error', "Please contact administrator...");
        }
        // return $next($request);
    }
}

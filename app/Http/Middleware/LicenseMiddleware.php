<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Tbl_config;

class LicenseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    // public function handle($request, Closure $next)
    // {

    //     $host = $_SERVER['HTTP_HOST'];
    //     // echo $host;
    //     // exit();

    //     if ($host == 'cricketu.com') {
    //         return $next($request);
    //     }else if($host == 'localhost'){
    //         return $next($request);
    //     } else {
    //         //echo "You are not allowed to use";
    //         return redirect('https://digitalcrm.com/');
    //     }
    // }

    public function handle($request, Closure $next)
    {

        $details = Tbl_config::first();

        $host = $_SERVER['HTTP_HOST'];
		
		//echo $host;
		//exit();

        $word = 'localhost';

        //($host == 'localhost') 
        //if(strpos($mystring, $word) !== false)

        if (strpos($word,$host ) !== false) {
            return $next($request);
        } else {

            // if ($host == 'cricketu.com') 

            $domain = $details->domain;
            $key = $details->secret_key;
            $validation = $this->apiValidation($domain, $key);

            // echo $validation;

            if ($validation == 'TRUE') {
                return $next($request);
            } else {
                // echo 'else';
                return redirect('https://digitalcrm.com/');
            }
        }

        // else {
        //     return redirect('https://digitalcrm.com/');
        // }
    }


    function curl_get_file_contents($URL)
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $URL);
        $contents = curl_exec($c);
        curl_close($c);

        return $contents;
    }

    public function apiValidation($domain, $key) //$id
    {
        $result = FALSE;
        if (($domain != '') && ($key != '')) {
            $url = "http://delhihouse.com/domain-validation/validation.php?domain=" . $domain . "&secret_key=" . $key;
            $result =  $this->curl_get_file_contents($url);
        }

        return $result;
    }
}

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//  Models
use App\Tbl_leads;
use App\Tbl_fb_leads;
use App\Tbl_test_leads;

class LeadApiController extends Controller {

    //
//  Request $request
    public function storeFbLead(Request $request) {

        //  tbl_test Users        
        $fblead = $request->input();
//        $res = Tbl_test_leads::create(array('data' => json_encode($fblead)));
//        if ($res->tlid > 0) {
//            return 'success';
//        } else {
//            return 'error';
//        }


        $email = (isset($fblead['email'])) ? $fblead['email'] : '';
        $full_name = (isset($fblead['full_name'])) ? $fblead['full_name'] : '';
        $phone_number = (isset($fblead['phone_number'])) ? $fblead['phone_number'] : '';
        $id = (isset($fblead['id'])) ? $fblead['id'] : '';
        $created_time = (isset($fblead['created_time'])) ? $fblead['created_time'] : '';
        $ad_id = (isset($fblead['ad_id'])) ? $fblead['ad_id'] : '';
        $ad_name = (isset($fblead['ad_name'])) ? $fblead['ad_name'] : '';
        $adset_id = (isset($fblead['adset_id'])) ? $fblead['adset_id'] : '';
        $adset_name = (isset($fblead['adset_name'])) ? $fblead['adset_name'] : '';
        $campaign_id = (isset($fblead['campaign_id'])) ? $fblead['campaign_id'] : '';
        $campaign_name = (isset($fblead['campaign_name'])) ? $fblead['campaign_name'] : '';
        $form_id = (isset($fblead['form_id'])) ? $fblead['form_id'] : '';
        $form_name = (isset($fblead['form_name'])) ? $fblead['form_name'] : '';
        $is_organic = (isset($fblead['is_organic'])) ? $fblead['is_organic'] : '';
        $platform = (isset($fblead['platform'])) ? $fblead['platform'] : '';
        $city = (isset($fblead['city'])) ? $fblead['city'] : '';
        $uid = 0;
        $uploaded_by = 3;
        $assigned = 0;
        $file_id = 0;

        $formdata = array(
            'email' => $email,
            'full_name' => $full_name,
            'phone_number' => $phone_number,
            'id' => $id,
            'created_time' => $created_time,
            'ad_id' => $ad_id,
            'ad_name' => $ad_name,
            'adset_id' => $adset_id,
            'adset_name' => $adset_name,
            'campaign_id' => $campaign_id,
            'campaign_name' => $campaign_name,
            'form_id' => $form_id,
            'form_name' => $form_name,
            'is_organic' => $is_organic,
            'platform' => $platform,
            'city' => $city,
            'uid' => $uid,
            'uploaded_by' => $uploaded_by,
            'assigned' => $assigned,
            'file_id' => $file_id,
        );

        $res = Tbl_fb_leads::create($formdata);

//        $leaddata = array(
//            'uid' => 0,
//            'first_name' => $full_name,
//            'city' => $city,
//            'mobile' => $phone_number,
//            'uploaded_from' => 4,
//            'uploaded_id' => $id
//        );
//
//        //  Table leads
//        Tbl_leads::create($leaddata);

        if ($res->fblead_id > 0) {
            return 'success';
        } else {
            return 'error';
        }
    }

}

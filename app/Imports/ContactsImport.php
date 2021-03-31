<?php

namespace App\Imports;

use App\Tbl_contacts;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Validator;

use Auth;
use App\Tbl_leadsource;
use App\Tbl_Accounts;
use App\Tbl_countries;
use App\Tbl_states;

class ContactsImport implements ToModel, WithHeadingRow //, WithValidation
{
    protected $id;

    function __construct($id)
    {
        $this->id = $id;
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        $validator = Validator::make($row, [
            'first_name' => ['required'],
            'email' => ['required'],
        ])->validate();

        $uid = $this->id;

        $acc_id = 0;
        $country = 0;
        $state = 0;
        $ldsrc_id = 0;
        $google_id = '';

        if ((isset($row['country'])) && ($row['country'] != '')) {
            $countrys = Tbl_countries::where(strtolower('name'), strtolower($row['country']))->first();
            if ($countrys != '') {
                $country = $countrys->id;
            }
        }

        if ((isset($row['state'])) && ($row['state'] != '')) {
            $states = Tbl_states::where(strtolower('name'), strtolower($row['state']))->first();
            if ($states != '') {
                $state = $states->id;
            }
        }

        if ((isset($row['account'])) && ($row['account'] != '')) {
            $accounts = Tbl_Accounts::where(strtolower('name'), strtolower($row['account']))->first();
            if ($accounts != '') {
                $acc_id = $accounts->acc_id;
            }
        }

        if ((isset($row['leadsource'])) && ($row['leadsource'] != '')) {
            $ldsrc = Tbl_leadsource::where(strtolower('leadsource'), strtolower($row['leadsource']))->first();
            if ($ldsrc != '') {
                $ldsrc_id = $ldsrc->ldsrc_id;
            }
        }

        $first_name = ((isset($row['first_name'])) && $row['first_name'] != '') ? $row['first_name'] : '';
        $last_name = ((isset($row['last_name'])) && $row['last_name'] != '') ? $row['last_name'] : '';
        $email = ((isset($row['email'])) && $row['email'] != '') ? $row['email'] : '';

        $phone = ((isset($row['phone'])) && $row['phone'] != '') ? $row['phone'] : '';
        $mobile = ((isset($row['mobile'])) && $row['mobile'] != '') ? $row['mobile'] : '';
        $website = ((isset($row['website'])) && $row['website'] != '') ? $row['website'] : '';

        $street = ((isset($row['street'])) && $row['street'] != '') ? $row['street'] : '';
        $city = ((isset($row['city'])) && $row['city'] != '') ? $row['city'] : '';
        $zip = ((isset($row['zip'])) && $row['zip'] != '') ? $row['zip'] : '';

        $google_id = ((isset($row['google_id'])) && $row['google_id'] != '') ? $row['google_id'] : '';
        $facebook_id = ((isset($row['facebook_id'])) && $row['facebook_id'] != '') ? $row['facebook_id'] : '';
        $twitter_id = ((isset($row['twitter_id'])) && $row['twitter_id'] != '') ? $row['twitter_id'] : '';
        $linkedin_id = ((isset($row['linkedin_id'])) && $row['linkedin_id'] != '') ? $row['linkedin_id'] : '';

        return new Tbl_contacts([
            'uid' => $uid,      //Auth::user()->id,
            'first_name' => $first_name, // $row['first_name'],
            'last_name' => $last_name,  // $row['last_name'],
            'email' => $email,  // $row['email'],
            'phone' => $phone,  // $row['phone'],
            'mobile' => $mobile, // $row['mobile'],
            'website' => $website, // $row['website'],
            // 'notes' => $row['notes'],
            'country' => $country,
            'state' => $state,
            'street' => $street,    //  $row['street']
            'city' => $city,    //  $row['city']
            'zip' => $zip,  //  $row['zip']
            'acc_id' => $acc_id,
            'ldsrc_id' => $ldsrc_id,
            'google_id' => $google_id,  //  $row['google_id']
            'facebook_id' => $facebook_id,  //  $row['facebook_id']
            'twitter_id' => $twitter_id,    //  $row['twitter_id']
            'linkedin_id' => $linkedin_id,  //  $row['linkedin_id']
        ]);
    }

    // public function  rules(): array
    // {
    //     return [
    //         'first_name' => 'required',
    //         'email' => 'required',
    //         'mobile' => 'required',
    //     ];
    // }
}

<?php

namespace App\Imports;

use App\Tbl_leads;
use App\Tbl_countries;
use App\Tbl_states;
use App\Tbl_leadsource;
use App\Tbl_leadstatus;
use App\Tbl_Accounts;
use App\Tbl_industrytypes;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Auth;

class LeadsImport implements ToModel, WithHeadingRow
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
            'last_name' => ['required'],
            'email' => ['required'],
        ])->validate();


        // echo json_encode($)

        $uid = $this->id;

        $country = 0;
        if ((isset($row['country'])) && ($row['country'] != '')) {
            $countrys = Tbl_countries::where(strtolower('name'), strtolower($row['country']))->first();
            if ($countrys != '') {
                $country = $countrys->id;
            }
        }

        $state = 0;
        if ((isset($row['state'])) && ($row['state'] != '')) {
            $states = Tbl_states::where(strtolower('name'), strtolower($row['state']))->first();
            if ($states != '') {
                $state = $states->id;
            }
        }

        $ldsrc_id = 0;
        if ((isset($row['leadsource'])) && ($row['leadsource'] != '')) {
            $ldsrc = Tbl_leadsource::where(strtolower('leadsource'), strtolower($row['leadsource']))->first();
            if ($ldsrc != '') {
                $ldsrc_id = $ldsrc->ldsrc_id;
            }
        }

        $ldstatus_id = 0;
        if ((isset($row['leadstatus'])) && ($row['leadstatus'] != '')) {
            $ldstatus = Tbl_leadstatus::where(strtolower('status'), strtolower($row['leadstatus']))->first();
            if ($ldstatus != '') {
                $ldstatus_id = $ldstatus->ldstatus_id;
            }
        }

        $acc_id = 0;
        if ((isset($row['account'])) && ($row['account'] != '')) {
            $accounts = Tbl_Accounts::where(strtolower('name'), strtolower($row['account']))->first();
            if ($accounts != '') {
                $acc_id = $accounts->acc_id;
            }
        }

        $intype_id = 0;
        if ((isset($row['industry_type'])) && ($row['industry_type'] != '')) {
            $intypes = Tbl_industrytypes::where(strtolower('type'), strtolower($row['industry_type']))->first();
            if ($intypes != '') {
                $intype_id = $intypes->intype_id;
            }
        }

        $city = ((isset($row['city'])) && $row['city'] != '') ? $row['city'] : '';
        $zip = ((isset($row['zip'])) && $row['zip'] != '') ? $row['zip'] : '';

        $phone = ((isset($row['phone'])) && $row['phone'] != '') ? $row['phone'] : '';
        $mobile = ((isset($row['mobile'])) && $row['mobile'] != '') ? $row['mobile'] : '';

        $website = ((isset($row['website'])) && $row['website'] != '') ? $row['website'] : '';
        $notes = ((isset($row['notes'])) && $row['notes'] != '') ? $row['notes'] : '';

        $street = ((isset($row['street'])) && $row['street'] != '') ? $row['street'] : '';

        return new Tbl_leads([
            'uid' => $uid,  //Auth::user()->id
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'email' => $row['email'],
            'phone' => $phone,
            'mobile' => $mobile,
            'website' => $website,
            'notes' => $notes,
            'country' => $country,
            'street' => $street,
            'state' => $state,
            'city' => $city,
            'zip' => $zip,
            'acc_id' => $acc_id,
            'ldsrc_id' => $ldsrc_id,
            'ldstatus_id' => $ldstatus_id,
            'intype_id' => $intype_id,
        ]);
    }
}

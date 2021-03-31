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

        // echo json_encode($)

        $uid = $this->id;

        $country = 0;
        if ($row['country'] != '') {
            $countrys = Tbl_countries::where(strtolower('name'), strtolower($row['country']))->first();
            if ($countrys != '') {
                $country = $countrys->id;
            }
        }

        $state = 0;
        if ($row['state'] != '') {
            $states = Tbl_states::where(strtolower('name'), strtolower($row['state']))->first();
            if ($states != '') {
                $state = $states->id;
            }
        }

        $ldsrc_id = 0;
        if ($row['leadsource'] != '') {
            $ldsrc = Tbl_leadsource::where(strtolower('leadsource'), strtolower($row['leadsource']))->first();
            if ($ldsrc != '') {
                $ldsrc_id = $ldsrc->ldsrc_id;
            }
        }

        $ldstatus_id = 0;
        if ($row['leadstatus'] != '') {
            $ldstatus = Tbl_leadstatus::where(strtolower('status'), strtolower($row['leadstatus']))->first();
            if ($ldstatus != '') {
                $ldstatus_id = $ldstatus->ldstatus_id;
            }
        }

        $acc_id = 0;
        if ($row['account'] != '') {
            $accounts = Tbl_Accounts::where(strtolower('name'), strtolower($row['account']))->first();
            if ($accounts != '') {
                $acc_id = $accounts->acc_id;
            }
        }

        $intype_id = 0;
        if ($row['industry_type'] != '') {
            $intypes = Tbl_industrytypes::where(strtolower('type'), strtolower($row['industry_type']))->first();
            if ($intypes != '') {
                $intype_id = $intypes->intype_id;
            }
        }

        return new Tbl_leads([
            'uid' => $uid,  //Auth::user()->id
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'email' => $row['email'],
            'phone' => $row['phone'],
            'mobile' => $row['mobile'],
            'website' => $row['website'],
            'notes' => $row['notes'],
            'country' => $country,
            'street' => $row['street'],
            'state' => $state,
            'city' => $row['city'],
            'zip' => $row['zip'],
            'acc_id' => $acc_id,
            'ldsrc_id' => $ldsrc_id,
            'ldstatus_id' => $ldstatus_id,
            'intype_id' => $intype_id,
        ]);
    }
}

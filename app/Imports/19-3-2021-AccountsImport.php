<?php

namespace App\Imports;

use App\Tbl_Accounts;
use App\Tbl_leads;
use App\Tbl_countries;
use App\Tbl_states;
use App\Tbl_industrytypes;
use App\Tbl_accounttypes;
use App\Tbl_deals;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth;

class AccountsImport implements ToModel, WithHeadingRow
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
        $uid = $this->id;

        $actype_id = 0;
        $intype_id = 0;
        $country = 0;
        $state = 0;

        if ($row['account_type'] != '') {
            $actypes = Tbl_accounttypes::where(strtolower('account_type'), strtolower($row['account_type']))->first();
            if ($actypes != '') {
                $actype_id = $actypes->actype_id;
            }
        }

        if ($row['industry_type'] != '') {
            $intypes = Tbl_industrytypes::where(strtolower('type'), strtolower($row['industry_type']))->first();
            if ($intypes != '') {
                $intype_id = $intypes->intype_id;
            }
        }

        if ($row['country'] != '') {
            $countrys = Tbl_countries::where(strtolower('name'), strtolower($row['country']))->first();
            if ($countrys != '') {
                $country = $countrys->id;
            }
        }

        if ($row['state'] != '') {
            $states = Tbl_states::where(strtolower('name'), strtolower($row['state']))->first();
            if ($states != '') {
                $state = $states->id;
            }
        }

        return new Tbl_Accounts([
            'uid' => $uid,  //Auth::user()->id,
            'name' => $row['name'],
            'email' => $row['email'],
            'phone' => $row['phone'],
            'mobile' => $row['mobile'],
            'company' => $row['company'],
            'description' => $row['description'],
            'country' => $country,
            'state' => $state,
            'city' => $row['city'],
            'zip' => $row['zip'],
            'actype_id' => $actype_id,
            'intype_id' => $intype_id,
        ]);
    }
}

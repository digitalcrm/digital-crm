<?php

namespace App\Imports;

use App\Tbl_deals;
use App\Tbl_Accounts;
use App\Tbl_leads;
use App\Tbl_lossreasons;
use App\Tbl_salesfunnel;
use App\Tbl_leadsource;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth;


class DealsImport implements ToModel, WithHeadingRow
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
        // $acc_id = 0;
        $ld_id = 0;
        $sfun_id = 0;
        $deal_status = 0;
        $ldsrc_id = 0;
        // $uid = Auth::user()->id;

        $uid = $this->id;


        $sql = "SELECT `ld_id` FROM `tbl_leads` WHERE uid =" . $uid . " and CONCAT(`first_name`,' ',`last_name`) ='" . $row['lead'] . "'";
        //                    echo $sql.'<br>';
        $exist = DB::select($sql);
        if (count($exist) > 0) {

            foreach ($exist as $leadId) {
                $ld_id = $leadId->ld_id;
            }

            // if ($row['account'] != '') {
            //     $accounts = Tbl_Accounts::where(strtolower('name'), strtolower($row['account']))->first();
            //     if ($accounts != '') {
            //         $acc_id = $accounts->acc_id;
            //     }
            // }

        } else {
            $lead = Tbl_leads::create(['uid' => $uid, 'first_name' => $row['lead']]);
            $ld_id = $lead->ld_id;
        }

        if ($row['salesfunnel'] == 'Won') {
            $deal_status = 1;
        }

        if ($row['salesfunnel'] == 'Lost') {
            $deal_status = 2;
        }

        if ($row['leadsource'] != '') {
            $ldsrc = Tbl_leadsource::where(strtolower('leadsource'), strtolower($row['leadsource']))->first();
            if ($ldsrc != '') {
                $ldsrc_id = $ldsrc->ldsrc_id;
            }
        }

        if ($row['salesfunnel'] != '') {
            $sfun = Tbl_salesfunnel::where(strtolower('salesfunnel'), strtolower($row['salesfunnel']))->first();
            if ($sfun != '') {
                $sfun_id = $sfun->sfun_id;
            }
        }

        return new Tbl_deals([
            //
            'uid' => $uid,
            'ld_id' => $ld_id,
            'sfun_id' => $sfun_id,
            'ldsrc_id' => $ldsrc_id,
            'deal_status' => $deal_status,
            'name' => $row['name'],
            'value' => $row['value'],
            'closing_date' => date('Y-m-d', strtotime(str_replace('/', '-', $row['closing_date']))),
            'notes' => $row['notes'],
        ]);
    }
}

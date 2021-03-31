<?php

namespace App\Imports;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth;

// Models
use App\Tbl_deals;
use App\Tbl_Accounts;
use App\Tbl_leads;
use App\Tbl_lossreasons;
use App\Tbl_salesfunnel;
use App\Tbl_leadsource;

class CustomersImport implements ToModel, WithHeadingRow
{
    //
    protected $id;

    function __construct($id)
    {
        $this->id = $id;
    }

    public function model(array $row)
    {
        $uid = $this->id;

        // $acc_id = 0;
        $ld_id = 0;
        $sfun_id = 5;
        $deal_status = 1;
        $ldsrc_id = 0;


        $sql = "SELECT `ld_id` FROM `tbl_leads` WHERE `uid` =" . $uid . " and CONCAT(`first_name`,' ',`last_name`) ='" . $row['customer'] . "'";
        // echo $sql . '<br>';
        // exit();
        $exist = DB::select($sql);

        // echo json_encode($exist);
        // exit();

        if (count($exist) > 0) {

            foreach ($exist as $leadId) {
                $ld_id = $leadId->ld_id;
            }
        } else {
            $lead = Tbl_leads::create(['uid' => $uid, 'first_name' => $row['customer']]);
            $ld_id = $lead->ld_id;
        // echo $ld_id . '<br>';
        // exit();
        
        }

        // if ($row['account'] != '') {
        //     $accounts = Tbl_Accounts::where(strtolower('name'), strtolower($row['account']))->first();
        //     if ($accounts != '') {
        //         $acc_id = $accounts->acc_id;
        //     }
        // }

        // if ($row['leadsource'] != '') {
        //     $ldsrc = Tbl_leadsource::where(strtolower('leadsource'), strtolower($row['leadsource']))->first();
        //     if ($ldsrc != '') {
        //         $ldsrc_id = $ldsrc->ldsrc_id;
        //     }
        // }

        // if ($row['salesfunnel'] != '') {
        //     $sfun = Tbl_salesfunnel::where(strtolower('salesfunnel'), strtolower($row['salesfunnel']))->first();
        //     if ($sfun != '') {
        //         $sfun_id = $sfun->sfun_id;
        //     }
        // }


        return new Tbl_deals([
            //
            'uid' => $uid,
            'ld_id' => $ld_id,
            'sfun_id' => $sfun_id,
            'ldsrc_id' => $ldsrc_id,
            'deal_status' => $deal_status,
            'name' => $row['name'],
            'value' => $row['amount'],
            'closing_date' => date('Y-m-d', strtotime(str_replace('/', '-', $row['closing_date']))),
            'notes' => $row['notes'],
        ]);
    }
}

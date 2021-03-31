<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

use Auth;
use App\Tbl_deals;

class CustomersExport implements FromCollection, WithHeadings
{
    //
    protected $id;

    function __construct($id)
    {
        $this->id = $id;
    }
    public function collection()
    {
        $uid = $this->id;
        $deals = DB::table('tbl_deals')
            ->where('tbl_deals.uid', $uid)
            ->where('tbl_deals.sfun_id', 5)
            ->leftJoin('tbl_leads', 'tbl_leads.ld_id', '=', 'tbl_deals.ld_id')
            ->leftJoin('tbl_accounts', 'tbl_accounts.acc_id', '=', 'tbl_deals.acc_id')
            ->select([
                DB::raw('CONCAT(tbl_leads.first_name," ",tbl_leads.last_name) as customer'),
                'tbl_deals.deal_id',
                'tbl_deals.uid',
                'tbl_deals.name',
                'tbl_deals.value',
                DB::raw('DATE_FORMAT(tbl_deals.closing_date,"%d-%m-%Y") as closing_date'),
                'tbl_deals.notes',
                'tbl_accounts.name as account',
            ])->get();

        return $deals;
    }


    public function headings(): array
    {
        return [
            'Id',
            'Uid',
            'Name',
            'Value',
            'Closing Date',
            'Deal Stage',
            'Notes',
            'Leadsource',
            'Lead',
        ];
    }
}

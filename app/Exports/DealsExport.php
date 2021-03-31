<?php

namespace App\Exports;

use App\Tbl_deals;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use Auth;

class DealsExport implements FromCollection, WithHeadings
{
    protected $id;

    function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // return Tbl_deals::all();
        // $uid = Auth::user()->id;
        $uid = $this->id;

        $deals = DB::table('tbl_deals')
            ->where('tbl_deals.uid', $uid)
            ->leftJoin('tbl_salesfunnel', 'tbl_salesfunnel.sfun_id', '=', 'tbl_deals.sfun_id')
            ->leftJoin('tbl_leads', 'tbl_leads.ld_id', '=', 'tbl_deals.ld_id')
            ->leftJoin('tbl_accounts', 'tbl_accounts.acc_id', '=', 'tbl_deals.acc_id')
            ->leftJoin('tbl_leadsource', 'tbl_leadsource.ldsrc_id', '=', 'tbl_deals.ldsrc_id')
            ->select([
                'tbl_deals.deal_id',
                'tbl_deals.uid',
                'tbl_deals.name',
                'tbl_deals.value',
                DB::raw('DATE_FORMAT(tbl_deals.closing_date,"%d-%m-%Y") as closing_date'),
                'tbl_salesfunnel.salesfunnel as salesfunnel',
                'tbl_deals.notes',
                'tbl_leadsource.leadsource as leadsource',
                DB::raw('CONCAT(tbl_leads.first_name," ",tbl_leads.last_name) as lead'),
            ])->get();

        //'tbl_accounts.name as account',

        // echo json_encode($deals);
        // exit();

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

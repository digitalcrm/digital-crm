<?php

namespace App\Exports;

use App\Tbl_leads;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use Auth;

class LeadsExport implements FromCollection, WithHeadings
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
        // return Tbl_leads::all();
        // $uid = Auth::user()->id;
        $uid = $this->id;
        
        $leads = DB::table('tbl_leads')
            ->where('tbl_leads.uid', $uid)
            ->where('tbl_leads.active', 1)
            ->leftJoin('tbl_states', 'tbl_states.id', '=', 'tbl_leads.state')
            ->leftJoin('tbl_countries', 'tbl_countries.id', '=', 'tbl_leads.country')
            ->leftJoin('tbl_industrytypes', 'tbl_industrytypes.intype_id', '=', 'tbl_leads.intype_id')
            ->leftJoin('tbl_leadsource', 'tbl_leadsource.ldsrc_id', '=', 'tbl_leads.ldsrc_id')
            ->leftJoin('tbl_leadstatus', 'tbl_leadstatus.ldstatus_id', '=', 'tbl_leads.ldstatus_id')
            ->leftJoin('tbl_accounts', 'tbl_accounts.acc_id', '=', 'tbl_leads.acc_id')
            ->select([
                'tbl_leads.ld_id',
                'tbl_leads.uid',
                'tbl_leads.first_name',
                'tbl_leads.last_name',
                'tbl_leads.email',
                'tbl_leads.mobile',
                'tbl_leads.phone',
                'tbl_leads.notes',
                'tbl_leads.website',
                'tbl_leads.city',
                'tbl_leads.street',
                'tbl_leads.zip',
                'tbl_states.name as state',
                'tbl_countries.name as country',
                'tbl_accounts.name as account',
                'tbl_industrytypes.type as industry_type',
                'tbl_leadstatus.status as leadstatus',
                'tbl_leadsource.leadsource as leadsource'
            ])
            ->get();    //'tbl_leads.message',
        return $leads;
    }

    public function headings(): array
    {
        return [
            'Id',
            'Uid',
            'First Name',
            'Last Name',
            'Email',
            'Mobile',
            'Phone',
            'Notes',
            'Website',
            'City',
            'Street',
            'Zip',
            'State',
            'Country',
            'Account',
            'Industry Type',
            'Leadstatus',
            'Leadsource',
        ];
    }
}

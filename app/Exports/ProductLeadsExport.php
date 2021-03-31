<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Auth;
//  Models

use App\User;
use App\Tbl_products;
use App\Tbl_leads;
use App\Company;
use App\Tbl_industrytypes;
use App\Tbl_leadsource;
use App\Tbl_leadstatus;

class ProductLeadsExport implements FromCollection, WithHeadings
{

    protected $id;
    protected $proId;

    function __construct($id, $proId)
    {
        $this->id = $id;
        $this->proId = $proId;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        //
        $query = DB::table('tbl_leads')->where('tbl_leads.uid', $this->id)->where('tbl_leads.pro_id', $this->proId)->where('tbl_leads.active', 1)->where('tbl_leads.leadtype', 2);
        $query->leftJoin('tbl_leadstatus', 'tbl_leads.ldstatus_id', '=', 'tbl_leadstatus.ldstatus_id');
        $query->leftJoin('tbl_leadsource', 'tbl_leads.ldsrc_id', '=', 'tbl_leadsource.ldsrc_id');
        $query->leftJoin('tbl_products', 'tbl_leads.pro_id', '=', 'tbl_products.pro_id');
        $query->orderBy('tbl_leads.ld_id', 'desc');
        $query->select(
            'tbl_leads.ld_id',
            'tbl_leads.uid',
            'tbl_leads.first_name',
            'tbl_leads.last_name',
            'tbl_leads.email',
            'tbl_leads.mobile',
            'tbl_leads.notes',
            'tbl_leadstatus.status as leadstatus',
            'tbl_leadsource.leadsource as leadsource',
            'tbl_products.name as product'
        );
        $leads = $query->get();
        return $leads;
    }

    public function headings(): array
    {
        return [
            'Id',
            'Uid',
            'Name',
            'Last Name',
            'Email',
            'Mobile',
            'Description',
            'Leadstatus',
            'Leadsource',
            'Product',
        ];
    }
}

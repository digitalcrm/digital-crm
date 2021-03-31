<?php

namespace App\Exports;

use App\Tbl_Accounts;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use Auth;

class AccountsExport implements FromCollection, WithHeadings
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
        // return Tbl_Accounts::all();
        // $uid = Auth::user()->id;
        $uid = $this->id;

        // echo $uid;
        // exit();

        $accounts = DB::table('tbl_accounts')
            ->where('uid', $uid)
            ->where('active', 1)
            ->leftJoin('tbl_states', 'tbl_states.id', '=', 'tbl_accounts.state')
            ->leftJoin('tbl_countries', 'tbl_countries.id', '=', 'tbl_accounts.country')
            ->leftJoin('tbl_industrytypes', 'tbl_industrytypes.intype_id', '=', 'tbl_accounts.intype_id')
            ->leftJoin('tbl_accounttypes', 'tbl_accounttypes.actype_id', '=', 'tbl_accounts.actype_id')
            ->select([
                'tbl_accounts.acc_id',
                'tbl_accounts.uid',
                'tbl_accounts.name',
                'tbl_accounts.email',
                'tbl_accounts.mobile',
                'tbl_accounts.phone',
                'tbl_accounts.company',
                'tbl_accounts.website',
                'tbl_accounts.description',
                'tbl_accounts.city',
                'tbl_accounts.street',
                'tbl_accounts.zip',
                'tbl_states.name as state',
                'tbl_countries.name as country',
                'tbl_industrytypes.type as industry_type',
                'tbl_accounttypes.account_type as account_type'
            ])
            ->get();

        // echo json_encode($accounts);
        // exit();

        return $accounts;
    }

    public function headings(): array
    {
        return [
            'Id',
            'Uid',
            'Name',
            'Email',
            'Mobile',
            'Phone',
            'Company',
            'Website',
            'Description',
            'City',
            'Street',
            'Zip',
            'State',
            'Country',
            'Industry Type',
            'Account Type',
        ];
    }
}

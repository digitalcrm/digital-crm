<?php

namespace App\Exports;

use App\Tbl_contacts;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use Auth;

class ContactsExport implements FromCollection, WithHeadings
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
        // return Tbl_contacts::all();
        // $uid = Auth::user()->id;
        $uid = $this->id;
        
        $contacts = DB::table('tbl_contacts')
            ->where('tbl_contacts.uid', $uid)
            ->where('tbl_contacts.active', 1)
            ->leftJoin('tbl_states', 'tbl_states.id', '=', 'tbl_contacts.state')
            ->leftJoin('tbl_countries', 'tbl_countries.id', '=', 'tbl_contacts.country')
            ->leftJoin('tbl_leadsource', 'tbl_leadsource.ldsrc_id', '=', 'tbl_contacts.ldsrc_id')
            ->leftJoin('tbl_accounts', 'tbl_accounts.acc_id', '=', 'tbl_contacts.acc_id')
            ->select([
                'tbl_contacts.cnt_id',
                'tbl_contacts.uid',
                'tbl_contacts.first_name',
                'tbl_contacts.last_name',
                'tbl_contacts.email',
                'tbl_contacts.mobile',
                'tbl_contacts.phone',
                'tbl_contacts.company',
                'tbl_contacts.website',
                'tbl_contacts.notes',
                'tbl_contacts.google_id',
                'tbl_contacts.facebook_id',
                'tbl_contacts.twitter_id',
                'tbl_contacts.linkedin_id',
                'tbl_contacts.city',
                'tbl_contacts.street',
                'tbl_contacts.zip',
                'tbl_states.name as state',
                'tbl_countries.name as country',
                'tbl_leadsource.leadsource as leadsource',
                'tbl_accounts.name as account'
            ])
            ->get();
        return $contacts;
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
            'Company',
            'Website',
            'Notes',
            'Google Id',
            'Facebook Id',
            'Twitter Id',
            'Linkedin Id',
            'City',
            'Street',
            'Zip',
            'State',
            'Country',
            'Leadsource',
            'Account',
        ];
    }
}

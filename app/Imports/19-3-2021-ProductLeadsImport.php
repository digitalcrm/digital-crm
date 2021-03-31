<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth;
//  Models

use App\User;
use App\Tbl_products;
use App\Tbl_leads;
use App\Company;
use App\Tbl_industrytypes;
use App\Tbl_leadsource;
use App\Tbl_leadstatus;

class ProductLeadsImport implements ToModel, WithHeadingRow //  ToCollection
{


    protected $id;
    protected $proId;

    function __construct($id, $proId)
    {
        $this->id = $id;
        $this->proId = $proId;
    }

    /**
     * @param Collection $collection
     */
    // public function collection(Collection $collection)
    public function model(array $row)
    {
        //
        $product = Tbl_products::find($this->proId);
        $price = $product;

        $filename = '';

        return new Tbl_leads([
            'uid' => $this->id,
            'first_name' => $row['name'],
            'last_name' => '',
            'email' => $row['email'],
            'picture' => $filename,
            'mobile' => $row['mobile'],
            'phone' => '',
            'ldsrc_id' => 0,
            'ldstatus_id' => 0,
            'intype_id' => 0,
            'acc_id' => 0,
            'notes' => $row['message'],
            'website' => '',
            'country' => 0,
            'state' => 0,
            'city' => '',
            'street' => '',
            'zip' => '',
            'company' => '',
            'sal_id' => 0,
            'designation' => '',
            'pro_id' => $this->proId,
            'leadtype' => 2,
        ]);
    }
}

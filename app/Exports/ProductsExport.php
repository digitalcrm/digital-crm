<?php

namespace App\Exports;

use App\Tbl_products;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Auth;


class ProductsExport implements FromCollection, WithHeadings
{
    protected $id;
    protected $user_type;

    function __construct($id, $user_type)
    {
        $this->id = $id;
        $this->user_type = $user_type;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // return Tbl_products::all();
        $uid = $this->id;
        $user_type = $this->user_type;
        // echo $uid;
        // exit();

        $products = DB::table('tbl_products')
            ->where('uid', $uid)
            ->where('user_type', $user_type)
            ->where('active', 1)
            ->leftJoin('tbl_units', 'tbl_units.unit_id', '=', 'tbl_products.unit')
            ->leftJoin('tbl_product_category', 'tbl_product_category.procat_id', '=', 'tbl_products.procat_id')
            ->leftJoin('tbl_product_subcategory', 'tbl_product_subcategory.prosubcat_id', '=', 'tbl_products.prosubcat_id')
            ->select([
                'tbl_products.pro_id',
                'tbl_products.uid',
                'tbl_products.name',
                'tbl_products.description',
                'tbl_products.size',
                'tbl_products.price',
                'tbl_products.vendor',
                'tbl_products.tags',
                'tbl_units.sortname as units',
                'tbl_product_category.category as product_category',
                'tbl_product_subcategory.category as product_subcategory',
                DB::raw('(CASE 
                        WHEN tbl_products.store = "1" THEN "Yes" 
                        ELSE "No" 
                        END) AS store')
            ])
            ->get();

        // echo json_encode($products);
        // exit();

        return $products;
    }

    public function headings(): array
    {
        return [
            'Id',
            'Uid',
            'Name',
            'Description',
            'Size',
            'Price',
            'Vendor',
            'Tags',
            'Units',
            'Product Category',
            'Product Sub Category',
            'Store'
        ];
    }
}

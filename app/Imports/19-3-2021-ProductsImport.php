<?php

namespace App\Imports;

use App\Tbl_products;
use App\Tbl_units;
use App\Tbl_productcategory;
use App\Tbl_product_subcategory;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth;


class ProductsImport implements ToModel, WithHeadingRow
{
    protected $id;
    protected $user_type;

    function __construct($id, $user_type)
    {
        $this->id = $id;
        $this->user_type = $user_type;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        // echo json_encode($row);
        // exit();

        $uid = $this->id;
        $user_type = $this->user_type;

        // echo $uid . ' ' . $user_type;
        // exit();

        $procat_id = 0;
        $prosubcat_id = 0;
        $unit_id = 0;

        if (strtolower($row['product_category']) != '') {
            $actypes = Tbl_productcategory::where(strtolower('category'), strtolower($row['product_category']))->first();
            if ($actypes != '') {
                $procat_id = $actypes->procat_id;
            }
        }

        if (strtolower($row['product_sub_category']) != '') {
            $intypes = Tbl_product_subcategory::where(strtolower('category'), strtolower($row['product_sub_category']))->first();
            if ($intypes != '') {
                $prosubcat_id = $intypes->prosubcat_id;
            }
        }

        if (strtolower($row['units']) != '') {
            $countrys = Tbl_units::where(strtolower('name'), strtolower($row['units']))->orWhere(strtolower('sortname'), strtolower($row['units']))->first();
            if ($countrys != '') {
                $unit_id = $countrys->unit_id;
            }
        }

        $store = (strtolower($row['store']) == 'yes') ? 1 : 0;
        $slug = $this->createSlug($row['name']);

        return new Tbl_products([
            'uid' => $uid,
            'name' => $row['name'],
            'price' => $row['price'],
            'unit' => $unit_id,
            'size' => $row['size'],
            'description' => $row['description'],
            'procat_id' => $procat_id,
            'prosubcat_id' => $prosubcat_id,
            'user_type' => $user_type,
            'store' => $store,
            'slug' => $slug
        ]);
    }
    
    //For Generating Unique Slug Our Custom function
    public function createSlug($title, $id = 0)
    {
        // Normalize the title
        $slug = str_slug($title);
        // Get any that could possibly be related.
        // This cuts the queries down by doing it once.
        $allSlugs = $this->getRelatedSlugs($slug, $id);
        // If we haven't used it before then we are all good.
        if (!$allSlugs->contains('slug', $slug)) {
            return $slug;
        }
        // Just append numbers like a savage until we find not used.
        for ($i = 1; $i <= 10; $i++) {
            $newSlug = $slug . '-' . $i;
            if (!$allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }
        throw new \Exception('Can not create a unique slug');
    }

    protected function getRelatedSlugs($slug, $id = 0)
    {
        return Tbl_products::select('slug')->where('slug', 'like', $slug . '%')
            ->where('pro_id', '<>', $id)
            ->get();
    }

    
}

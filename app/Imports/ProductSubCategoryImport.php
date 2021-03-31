<?php

namespace App\Imports;

use App\Tbl_product_subcategory;
use App\Tbl_productcategory;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth;

use function PHPUnit\Framework\isEmpty;

class ProductSubCategoryImport implements ToModel, WithHeadingRow
{
    protected $id;

    function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $procat_id = $this->id;

        $category = $row['category'];

        $slug = $this->createSlug($category);

        $duplicate = $this->checkDuplicateCategory($category);

        // echo json_encode($duplicate);

        // exit();

        // echo json_encode([
        //     'procat_id' => $procat_id,
        //     'category' => $category,
        //     'slug' => $slug,
        // ]);
        // exit();


        if ($duplicate == '') {
            return new Tbl_product_subcategory([
                'procat_id' => $procat_id,
                'category' => $category,
                'slug' => $slug,
            ]);
        }
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
        return Tbl_product_subcategory::select('slug')->where('slug', 'like', $slug . '%')
            ->where('prosubcat_id', '<>', $id)
            ->get();
    }

    public function checkDuplicateCategory($category)
    {
        return Tbl_product_subcategory::select('category')->where('category', 'like', $category . '%')
            ->first();
    }
}

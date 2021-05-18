<?php

namespace App\Imports;

use App\ServSubcategory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ServiceSubcategoryImport implements
    ToModel,
    // WithHeadingRow,
    // WithValidation,
    SkipsOnError,
    SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    protected $category_id;

    function __construct($category_id)
    {
        $this->category_id = $category_id;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // dd($row[0]);
        return new ServSubcategory([
            'name' => $row[0],
            'servcategory_id' => $this->category_id,
        ]);
    }

    // public function rules(): array
    // {
    //     return [
    //         '*.name' => ['name', 'required|string|max:255'],
    //     ];
    // }
}

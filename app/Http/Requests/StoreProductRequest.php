<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'price' => 'nullable|numeric|',
            'description' => 'required|string',
            'procat_id' => 'required|numeric|not_in:0',
            'prosubcat_id' => 'required|numeric|not_in:0',
            'company' => 'required|not_in:0',
            'vendor' => 'nullable',
            'tags' => 'nullable',
            'location' => 'nullable',
            'min_quantity' => 'not_in:0',
            'picture' => 'required|image|max:1024',
            'unit' => 'nullable|numeric',
        ];
    }
}

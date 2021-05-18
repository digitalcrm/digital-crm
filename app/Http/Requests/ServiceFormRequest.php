<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceFormRequest extends FormRequest
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
            'title' => 'required|max:255',
            'price' => 'numeric|',
            'servcategory_id' => 'required|numeric|not_in:0',
            'serv_subcategory_id' => 'required|not_in:0',
            'company_id' => 'required|not_in:0',
            'image' => 'nullable|image|max:2024',
            'description' => 'required|string',
            'brand' => 'nullable',
            'tags' => 'nullable',
        ];
    }
}

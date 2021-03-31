<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTicketRequest extends FormRequest
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
            'ticket_number' => 'unique:tickets',
            'name' => 'required|max:100',
            'description' => 'required|max:255',
            'start_date' =>'required|',
            'ticket_image' => 'image|mimes:jpeg,jpg,png|max:2048',
            'priority_id'=> 'required|not_in:0',
            'contact_id'=> 'required|not_in:0',
            'product_id'=> 'required|not_in:0',
            'status_id'=> 'required|not_in:0',
            'type_id' => 'required|not_in:0',
            'user_id' => 'not_in:0',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
    return [
        'name.required' => 'A ticket title is required',
        'description.required'  => 'A description is required',
        'status_id.required'  => 'select the ticket status',
        'type_id.required'  => 'select the ticket type',
        'contact_id.required'  => 'select contact for ticket',
    ];
    }
}

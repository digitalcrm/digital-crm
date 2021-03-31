<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentStoreRequest extends FormRequest
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
            'message' => $this->action === 'close' ? 'max:255' : 'required|min:1|max:255',
            'user_id' => 'not_in:0',
            'admin_id' => 'not_in:0'
        ];
        /*
        $rules = [];
        $rules['user_id'] = 'not_in:0';
        $rules['admin_id'] = 'not_in:0';

        // if (request('action') === 'close') {
        //     $rules['message'] = '|max:255';
        // } else {
        //     $rules['message'] = 'required|min:1|max:255';
        // }

        # Refactor above if condtion
        $rules['message'] = (request('action') === 'close') ? '|max:255' : 'required|min:1|max:255' ;

        return $rules;
        */
    }
}

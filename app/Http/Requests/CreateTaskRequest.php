<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTaskRequest extends FormRequest
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
            'title' => 'required|max:30',
            'description' => 'max:255',
            'started_at' =>'required|',
            'due_time' =>'required|',
            'priority'=> 'required|not_in:0',
            'outcome_id'=> 'required|not_in:0',
            'project_id'=> 'not_in:0',
            'tasktype_id' => 'required|not_in:0',
            'todoable_id' => 'not_in:0',
        ];
    }
}

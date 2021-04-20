<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
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
            'c_name' => 'required|max:55|',
            'c_mobileNum' => 'required|digits_between:10,15|unique:companies,c_mobileNum,',
            'c_whatsappNum' => 'required|digits_between:10,15|unique:companies,c_whatsappNum,',
            'c_bio' => 'required|min:15',
            'c_email' => 'required|email',
            'personal_name' => 'nullable|max:25',
            'category_id' => 'nullable|not_in:0', //business type
            'actype_id' => 'nullable|not_in:0', // company type
            'position' => 'nullable|max:45',
            'c_logo' => 'nullable|image|max:1024',
            'document' => 'nullable|mimes:jpg,bmp,png,pdf,docx,doc,ppt,pptx|max:2024',
            'c_cover' => 'nullable|image|max:1024',
            'c_webUrl' => 'nullable|',
            'c_gstNumber' => 'nullable|digits_between:12,15',
            'employees' => 'nullable|min:0',
            'google_map_url' => 'nullable|max:500',
            'yt_video_link' => 'nullable',
            'fb_link' => 'nullable|string',
            'tw_link' => 'nullable|string',
            'yt_link' => 'nullable|string',
            'linkedin_link' => 'nullable|string',
            'country_id' => 'nullable|not_in:0',
            'state_id' => 'nullable|not_in:0',
            'address' => 'nullable|max:255',
            'city' => 'nullable|max:45',
            'zipcode' => 'nullable|digits_between:5,6',
            'showLive' => 'accepted',
            'termsAccept' => 'accepted',
        ];
    }
}

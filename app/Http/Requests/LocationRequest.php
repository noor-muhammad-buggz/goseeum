<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocationRequest extends FormRequest
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
            'location_name' => 'required|min:8',
            'location_lat' => 'required',
            'location_lang' => 'required',
            'location_address' => 'required',
            'location_type' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'location_name.required' => "Please enter location name",
            'location_name.min' => "Please enter minimum 8 characters for location name",
            'location_lat.required' => "Please enter location latitude",
            'location_lang.required' => "Please enter location longitude",
            'location_address.required' => "Please enter location address",
            'location_type.required' => "Please select location type",
        ];
    }
}

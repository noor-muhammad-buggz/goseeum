<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserProfileSettings extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'string|max:255',
            'dob' => 'required|date_format:"d/m/Y"',
            'gender' => 'required',
        ];
    }

    public function messages() {
        return [
            'first_name.required' => "Please enter first name",
            'first_name.max' => "Please enter maximum 255 characters for first name",
            'last_name.max' => "Please enter maximum 255 characters for last name",
            'dob.required' => "Please enter date of birth",
            'dob.date_format' => "Please enter date of birth with correct format e.g DD/MM/YYYY",
            'gender.required' => "Please select gender",
        ];
    }
}

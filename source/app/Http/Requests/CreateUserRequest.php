<?php

namespace App\Http\Requests;

use App\Rules\GooglePlaceExists;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'address' => 'required|string',
            'place_id' => [new GooglePlaceExists],
            'phone' => 'required|max:20|phone:GB,mobile,fixed_line|unique:users',
            'password' => 'required|string|min:8'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'place_id' => 'company address',
            'phone' => 'phone number',
        ];
    }
}

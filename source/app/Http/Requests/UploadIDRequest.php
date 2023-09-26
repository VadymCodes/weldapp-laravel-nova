<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadIDRequest extends FormRequest
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
            'type' => 'required|string',
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg|max:20480'
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MainCategoryRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'photo' => ['mimes:jpg,jpeg,png'],
            'category' => ['required', 'array', 'min:1'],
            'category.*.name' => ['required'],
            'category.*.abbr' => ['required'],
            'category.*.active' => ['in:0,1'],
        ];
    }

    public function messages()
    {
        return [
            'required' => "هذا الحقل مطلوب",
        ];
    }
}
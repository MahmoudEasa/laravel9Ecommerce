<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LanguageRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100'],
            'abbr' => ['required', 'string', 'max:10'],
            'active' => ['in:0,1'],
            'direction' => ['required', 'in:ltr,rtl'],
        ];
    }

    public function messages()
    {
        return [
            'required' => "هذا الحقل مطلوب",
            'max' => "الحقل يجب أن لا يزيد عن :max أحرف",
            'in' => "الحقل يجب أن يكون أحد العناصر التالية :values",
        ];
    }
}
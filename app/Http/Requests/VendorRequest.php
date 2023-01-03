<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorRequest extends FormRequest
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
            'name' => ['required', 'max:100'],
            'logo' => ['required_without:id', 'mimes:jpg,jpeg,png'],
            'mobile' => [
                'required',
                'unique:vendors,mobile,'.$this->id,
                'numeric',
                'digits:11'
            ],
            'address' => ['required'],
            'email' => [
                'required',
                'unique:vendors,email,'.$this->id,
                'email'
            ],
            'password' => ['required_without:id', 'string', 'nullable', 'min:6'],
            'category_id' => ['required', 'exists:main_categories,id'],
        ];
    }

    public function messages()
    {
        return [
            'required' => "هذا الحقل مطلوب.",
            'required_without' => "هذا الحقل مطلوب.",
            'max' => "هذا الحقل يجب أن لا يزيد عن :max أحرف.",
            'digits' => "هذا الحقل يجب أن يكون :digits رقم.",
            'min' => "هذا الحقل يجب أن لا يقل عن :min أحرف.",
            'email.email' => 'أدخل عنوان بريد إلكتروني صالح.',
            'mimes' => 'الحقل يجب أن يكون أحد الأمتدادات التالية :values.',
            'numeric' => 'الحقل يجب أن يكون رقم.',
            'exists' => 'هذا القسم غير موجود.',
            'unique' => 'هذه البيانات موجوده بالفعل الرجاء إدخال بيانات أخرى.',
            'string' => 'يجب أن يكون حروف أو حروف وأرقام.',
        ];
    }
}
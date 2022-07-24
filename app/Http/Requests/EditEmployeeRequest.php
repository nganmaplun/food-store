<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditEmployeeRequest extends FormRequest
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
            'full_name' => 'required',
            'phone' => 'required|min:10',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return void
     */
    public function messages()
    {
        return [
            'full_name.required' => "Hãy điền tên đầy đủ nhân viên",
            'phone.required' => "Hãy điền số điện thoại",
            'phone.min' => "Số điện thoại phải có 10 chữ số",
        ];
    }
}

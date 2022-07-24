<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEmployeeRequest extends FormRequest
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
            'username' => 'required',
            'full_name' => 'required',
            'password' => 'required|min:8',
            'phone' => 'required|min:10',
            'role' => 'required',
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
            'username.required' => "Hãy điền tài khoản đăng nhập",
            'full_name.required' => "Hãy điền tên nhân viên",
            'password.required' => "Hãy điền mật khẩu",
            'password.min' => "Mật khẩu phải có 8 kí tự",
            'phone.required' => "Hãy điền số điện thoại",
            'phone.min' => "Số điện thoại phải có 10 chữ số",
            'role.required' => "Hãy chọn role cho nhân viên",
        ];
    }
}

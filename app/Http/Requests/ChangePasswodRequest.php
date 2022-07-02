<?php

namespace App\Http\Requests;

use App\Rules\CheckUserLoginRule;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswodRequest extends FormRequest
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
            'old_password' => ['required', new CheckUserLoginRule()],
            'password' => 'required|min:8',
            'password_confirm' => 'same:password',
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
            'old_password.required' => "Hãy điền mật khẩu hiện tại",
            'password.required' => "Hãy điền mật khẩu",
            'password.min' => "Mật khẩu phải có 8 kí tự",
            'password_confirm.same' => "Hãy xác nhận mật khẩu mới",
        ];
    }
}

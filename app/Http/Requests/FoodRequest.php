<?php

namespace App\Http\Requests;

use App\Constants\FoodConstant;
use Illuminate\Foundation\Http\FormRequest;

class FoodRequest extends FormRequest
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
            FoodConstant::VIETNAMESE_NAME_FIELD => 'required',
            FoodConstant::JAPANESE_NAME_FIELD => 'required',
            FoodConstant::ENGLISH_NAME_FIELD => 'required',
            FoodConstant::SHORT_NAME_FIELD => 'required',
            FoodConstant::PRICE_FIELD => 'required',
            FoodConstant::CATEGORY_FIELD => 'required',
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
            FoodConstant::VIETNAMESE_NAME_FIELD . '.required' => "Hãy điền tên tiếng việt",
            FoodConstant::JAPANESE_NAME_FIELD . '.required' => "Hãy điền tên tiếng nhật",
            FoodConstant::ENGLISH_NAME_FIELD . '.required' => "Hãy điền tên tiếng anh",
            FoodConstant::SHORT_NAME_FIELD . '.required' => "Hãy điền tên rút gọn",
            FoodConstant::PRICE_FIELD . '.required' => "Hãy điền giá món",
            FoodConstant::CATEGORY_FIELD . '.required' => "Hãy chọn danh mục món",
        ];
    }
}

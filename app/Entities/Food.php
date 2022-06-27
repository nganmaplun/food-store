<?php

namespace App\Entities;

use App\Constants\FoodConstant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Food.
 *
 * @package namespace App\Entities;
 */
class Food extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    protected $table = FoodConstant::TABLE_NAME;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        FoodConstant::VIETNAMESE_NAME_FIELD,
        FoodConstant::JAPANESE_NAME_FIELD,
        FoodConstant::ENGLISH_NAME_FIELD,
        FoodConstant::SHORT_NAME_FIELD,
        FoodConstant::PRICE_FIELD,
        FoodConstant::CATEGORY_FIELD,
        FoodConstant::IMAGE_FIELD,
        FoodConstant::RECIPE_FIELD,
        FoodConstant::DESCRIPTION_FIELD,
    ];

}

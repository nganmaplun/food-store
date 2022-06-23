<?php

namespace App\Entities;

use App\Constants\FoodDayConstant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class FoodDay.
 *
 * @package namespace App\Entities;
 */
class FoodDay extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    protected $table = 'food_day';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        FoodDayConstant::FOOD_ID_FIELD,
        FoodDayConstant::NUMBER_FIELD,
        FoodDayConstant::DATE_FIELD,
    ];

}

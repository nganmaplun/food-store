<?php

namespace App\Entities;

use App\Constants\AggDayConstant;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class AggMonth extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'agg_month';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        AggDayConstant::TOTAL_FOOD_FIELD,
        AggDayConstant::TOTAL_PRICE_FIELD,
        AggDayConstant::ORDER_DATE_FIELD,
        AggDayConstant::VIETNAMESE_GUEST_FIELD,
        AggDayConstant::JAPANESE_GUEST_FIELD,
        AggDayConstant::ENGLISH_GUEST_FIELD,
    ];
}

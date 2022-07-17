<?php

namespace App\Entities;

use App\Constants\FoodOrderConstant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class FoodOrder.
 *
 * @package namespace App\Entities;
 */
class FoodOrder extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = FoodOrderConstant::TABLE_NAME;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        FoodOrderConstant::ORDER_ID_FIELD,
        FoodOrderConstant::FOOD_ID_FIELD,
        FoodOrderConstant::ORDER_NUM_FIELD,
        FoodOrderConstant::NOTE_FIELD,
        FoodOrderConstant::IS_DELIVERED_FIELD,
        FoodOrderConstant::IS_COMPLETED_FIELD,
        FoodOrderConstant::IS_NEW_FIELD,
        FoodOrderConstant::IS_SENT_FIELD,
        FoodOrderConstant::ORDER_TIME_FIELD,
    ];

    /**
     * @return void
     */
    protected static function booted(): void
    {
        parent::boot();

        self::creating(function ($model) {
            $model[FoodOrderConstant::ORDER_TIME_FIELD] = Carbon::now()->toTimeString();
        });
    }

}

<?php

namespace App\Entities;

use App\Constants\BaseConstant;
use App\Constants\OrderConstant;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Order.
 *
 * @package namespace App\Entities;
 */
class Order extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string
     */
    protected $table = OrderConstant::TABLE_NAME;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        OrderConstant::TABLE_NAME,
        OrderConstant::EMPLOYEE_ID_FIELD,
        OrderConstant::TABLE_ID_FIELD,
        OrderConstant::CUSTOMER_TYPE_FIELD,
        OrderConstant::NUMBER_OF_CUSTOMER_FIELD,
        OrderConstant::TOTAL_PRICE_FIELD,
        OrderConstant::IS_PAID_FIELD,
        OrderConstant::DESCRIPTION_FIELD,
        BaseConstant::STATUS_FIELD
    ];

}

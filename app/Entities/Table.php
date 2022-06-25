<?php

namespace App\Entities;

use App\Constants\BaseConstant;
use App\Constants\TableConstant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Table.
 *
 * @package namespace App\Entities;
 */
class Table extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        TableConstant::NAME_FIELD,
        TableConstant::MAX_SEAT_FIELD,
        TableConstant::FLOOR_FIELD,
        TableConstant::DESCRIPTION_FIELD,
        BaseConstant::STATUS_FIELD
    ];

}

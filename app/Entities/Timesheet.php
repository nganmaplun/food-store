<?php

namespace App\Entities;

use App\Constants\BaseConstant;
use App\Constants\TimesheetConstant;
use App\Constants\UserConstant;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Timesheet.
 *
 * @package namespace App\Entities;
 */
class Timesheet extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        TimesheetConstant::EMPLOYEE_ID_FIELD,
        TimesheetConstant::CHECKIN_TIME_FIELD,
        TimesheetConstant::CHECKOUT_TIME_FIELD,
        TimesheetConstant::WORKING_DATE_FIELD,
        TimesheetConstant::TOTAL_HOURS_FIELD,
        TimesheetConstant::IS_APPROVED_FIELD,
        BaseConstant::STATUS_FIELD,
    ];

    /**
     * @return void
     */
    protected static function booted(): void
    {
        parent::boot();

        self::updating(function ($model) {
            $seconds =
                strtotime($model[TimesheetConstant::CHECKOUT_TIME_FIELD])
                -
                strtotime($model[TimesheetConstant::CHECKIN_TIME_FIELD]);
            $model[TimesheetConstant::TOTAL_HOURS_FIELD] = $seconds / 3600;
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function employee()
    {
        return $this->hasMany(
            User::class,
            BaseConstant::ID_FIELD,
            TimesheetConstant::EMPLOYEE_ID_FIELD
        );
    }
}

<?php

namespace App\Entities;

use App\Constants\BaseConstant;
use App\Constants\TimesheetConstant;
use App\Constants\UserConstant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class User.
 *
 * @package namespace App\Entities;
 */
class User extends Authenticatable implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        UserConstant::USERNAME_FIELD,
        UserConstant::PASSWORD_FIELD,
        UserConstant::FULLNAME_FIELD,
        UserConstant::PHONE_FIELD,
        UserConstant::AVATAR_FIELD,
        UserConstant::ROLE_FIELD,
        BaseConstant::STATUS_FIELD,
    ];

    /**
     * @var array
     */
    protected $hidden = [
        UserConstant::PASSWORD_FIELD
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function timesheet()
    {
        return $this->hasOne(Timesheet::class, TimesheetConstant::EMPLOYEE_ID_FIELD, BaseConstant::ID_FIELD)
                ->ofMany(BaseConstant::ID_FIELD, 'max');
    }
}

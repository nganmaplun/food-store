<?php

namespace App\Entities;

use App\Constants\BaseConstant;
use App\Constants\UserConstant;
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

    protected $hidden = [
        UserConstant::PASSWORD_FIELD
    ];
}

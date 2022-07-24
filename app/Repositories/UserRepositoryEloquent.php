<?php

namespace App\Repositories;

use App\Constants\BaseConstant;
use App\Constants\TimesheetConstant;
use App\Constants\UserConstant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\UserRepository;
use App\Entities\User;
use App\Validators\UserValidator;

/**
 * Class UserRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class UserRepositoryEloquent extends BaseRepository implements UserRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * list employee for timesheet
     * @param string $today
     * @param array $request
     * @return mixed
     */
    public function getListEmployee(string $today, array $request): mixed
    {
        $select = [
            UserConstant::FULLNAME_FIELD,
            TimesheetConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD,
            TimesheetConstant::CHECKIN_TIME_FIELD,
            TimesheetConstant::CHECKOUT_TIME_FIELD,
            TimesheetConstant::IS_APPROVED_FIELD,
            TimesheetConstant::TABLE_NAME . '.' . BaseConstant::STATUS_FIELD,
            UserConstant::ROLE_FIELD
        ];
        return $this->select($select)
            ->leftJoin(
                TimesheetConstant::TABLE_NAME,
                TimesheetConstant::EMPLOYEE_ID_FIELD,
                BaseConstant::EQUAL,
                UserConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD
            )->where([
                TimesheetConstant::WORKING_DATE_FIELD => $today
            ])->whereNotIn(
                UserConstant::ROLE_FIELD, [BaseConstant::ADMIN_ROLE]
            )->paginate(BaseConstant::DEFAULT_LIMIT);
    }

    /**
     * @param $id
     * @param array $request
     * @return mixed
     */
    public function changePassword($id, array $request)
    {
        try {
            return $this->update([
                UserConstant::PASSWORD_FIELD => Hash::make($request['password'])
            ], $id);
        } catch (\Exception $e) {
            Log::channel('customError')->error($e->getMessage());
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function listAllEmployee()
    {
        $select = [
            BaseConstant::ID_FIELD,
            UserConstant::FULLNAME_FIELD,
            UserConstant::PHONE_FIELD,
            UserConstant::ROLE_FIELD,
            BaseConstant::STATUS_FIELD,
        ];
        return $this->select($select)->get();
    }


    /**
     * @param $id
     * @return mixed
     */
    public function getDetailEmployee($id)
    {
        $select = [
            BaseConstant::ID_FIELD,
            UserConstant::FULLNAME_FIELD,
            UserConstant::PHONE_FIELD,
            UserConstant::ROLE_FIELD,
        ];
        return $this->select($select)
            ->where(BaseConstant::ID_FIELD, $id)
            ->first();
    }

    /**
     * @param array $request
     * @param $id
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function updateEmployee(array $request, $id)
    {
        try {
            $dataUpdate = [
                UserConstant::FULLNAME_FIELD => $request[UserConstant::FULLNAME_FIELD],
                UserConstant::PHONE_FIELD => $request[UserConstant::PHONE_FIELD],
                UserConstant::ROLE_FIELD => $request[UserConstant::ROLE_FIELD],
            ];
            if (isset($request[UserConstant::PASSWORD_FIELD]) &&
                !empty($request[UserConstant::PASSWORD_FIELD])
            ) {
                $dataUpdate[UserConstant::PASSWORD_FIELD] = Hash::make($request[UserConstant::PASSWORD_FIELD]);
            }
            return $this->update($dataUpdate, $id);
        } catch (\Exception $e) {
            Log::channel('customError')->error($e->getMessage());
            return false;
        }
    }

    /**
     * @param array $request
     * @return mixed
     */
    public function createEmployee(array $request)
    {
        try {
            $dataCreate = [
                UserConstant::USERNAME_FIELD => $request[UserConstant::USERNAME_FIELD],
                UserConstant::FULLNAME_FIELD => $request[UserConstant::FULLNAME_FIELD],
                UserConstant::PASSWORD_FIELD => Hash::make($request[UserConstant::PASSWORD_FIELD]),
                UserConstant::PHONE_FIELD => $request[UserConstant::PHONE_FIELD],
                UserConstant::ROLE_FIELD => $request[UserConstant::ROLE_FIELD],
            ];
            return $this->create($dataCreate);
        } catch (\Exception $e) {
            Log::channel('customError')->error($e->getMessage());
            return false;
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteEmployee($id)
    {
        try {
            return $this->delete($id);
        } catch (\Exception $e) {
            Log::channel('customError')->error($e->getMessage());
            return false;
        }
    }
}

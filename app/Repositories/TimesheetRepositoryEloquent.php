<?php

namespace App\Repositories;

use App\Constants\BaseConstant;
use App\Constants\TimesheetConstant;
use App\Constants\UserConstant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TimesheetRepository;
use App\Entities\Timesheet;
use App\Validators\TimesheetValidator;

/**
 * Class TimesheetRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TimesheetRepositoryEloquent extends BaseRepository implements TimesheetRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Timesheet::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @param $user
     * @return false|void
     */
    public function createCheckin($user)
    {
        try {

            $userTimesheet = $this->findWhere([
                TimesheetConstant::EMPLOYEE_ID_FIELD => $user[BaseConstant::ID_FIELD],
                TimesheetConstant::WORKING_DATE_FIELD => Carbon::now()->toDateString(),
                BaseConstant::STATUS_FIELD => true
            ])->first();
            if (!$userTimesheet) {
                $this->create([
                    TimesheetConstant::EMPLOYEE_ID_FIELD => $user[BaseConstant::ID_FIELD],
                    TimesheetConstant::CHECKIN_TIME_FIELD => date('H:i'),
                    TimesheetConstant::WORKING_DATE_FIELD => Carbon::now()->toDateString(),
                    BaseConstant::STATUS_FIELD => true
                ]);
            }
        } catch (\Exception $e) {
            Log::channel('customError')->error($e->getMessage());
            return false;
        }
    }


    /**
     * @param $user
     * @return false|void
     */
    public function createCheckout($user)
    {
        try {
            $userTimesheet = $this->findWhere([
                TimesheetConstant::EMPLOYEE_ID_FIELD => $user[BaseConstant::ID_FIELD],
                TimesheetConstant::WORKING_DATE_FIELD => Carbon::now()->toDateString(),
                BaseConstant::STATUS_FIELD => true
            ])->first();
            $this->update([
                TimesheetConstant::CHECKOUT_TIME_FIELD => date('H:i')
            ], $userTimesheet[BaseConstant::ID_FIELD]);
        } catch (\Exception $e) {
            Log::channel('customError')->error($e->getMessage());
            return false;
        }
    }

    /**
     * @param $today
     * @param $request
     * @return mixed
     */
    public function getListEmployee($today, $request): mixed
    {

        return $this->with(['employee' => function ($query) {
            $query->where(UserConstant::ROLE_FIELD, BaseConstant::DIFFERENCE, BaseConstant::ADMIN_ROLE);
            }])->where([
                TimesheetConstant::WORKING_DATE_FIELD => $today,
            ])->paginate(BaseConstant::DEFAULT_LIMIT);
    }

    /**
     * @param $index
     * @return mixed
     */
    public function approveTimesheet($index, $type): mixed
    {
        try {
            $timesheet = $this->findWhere([BaseConstant::ID_FIELD => $index])->first();
            return $this->update([
                TimesheetConstant::CHECKOUT_TIME_FIELD => $type !== 'approved' ? $timesheet[TimesheetConstant::CHECKIN_TIME_FIELD] : null,
                TimesheetConstant::IS_APPROVED_FIELD => $type == 'approved' ? true : false,
                BaseConstant::STATUS_FIELD =>  $type == 'approved' ? true : false,
            ], $timesheet[BaseConstant::ID_FIELD]);
        } catch (\Exception $e) {
            Log::channel('customError')->error($e->getMessage());
            return false;
        }
    }
}

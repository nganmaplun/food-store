<?php

namespace App\Repositories;

use App\Constants\BaseConstant;
use App\Constants\TimesheetConstant;
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
            $this->create([
                TimesheetConstant::EMPLOYEE_ID_FIELD => $user[BaseConstant::ID_FIELD],
                TimesheetConstant::CHECKIN_TIME_FIELD => date('H:i'),
                TimesheetConstant::WORKING_DATE_FIELD => Carbon::now()->toDateString(),
                BaseConstant::STATUS_FIELD => false
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
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
                BaseConstant::STATUS_FIELD => false
            ])->first();
            $this->update([
                TimesheetConstant::CHECKOUT_TIME_FIELD => date('H:i'),
                BaseConstant::STATUS_FIELD => true
            ], $userTimesheet[BaseConstant::ID_FIELD]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
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

        return $this->with('employee')
            ->where([
                TimesheetConstant::WORKING_DATE_FIELD => $today
            ])->paginate(BaseConstant::DEFAULT_LIMIT);
    }

    /**
     * @param $index
     * @return mixed
     */
    public function approveTimesheet($index): mixed
    {
        try {
            $timesheet = $this->findWhere([BaseConstant::ID_FIELD => $index])->first();
            if (!$timesheet[TimesheetConstant::CHECKOUT_TIME_FIELD]) {
                return false;
            }
            return $this->update([
                TimesheetConstant::IS_APPROVED_FIELD => true
            ], $timesheet[BaseConstant::ID_FIELD]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }
}

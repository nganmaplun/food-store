<?php

namespace App\Repositories;

use App\Constants\BaseConstant;
use App\Constants\FoodConstant;
use App\Constants\FoodDayConstant;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\AggDayRepository;
use App\Entities\FoodDay;
use App\Validators\FoodDayValidator;

/**
 * Class FoodDayRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class FoodDayRepositoryEloquent extends BaseRepository implements FoodDayRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return FoodDay::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @param $request
     * @return mixed
     */
    public function setFoodNumberToday($request): mixed
    {
        try {
            $food = $this->where([
                BaseConstant::ID_FIELD => $request['fid'],
                FoodDayConstant::DATE_FIELD => $request['today']
            ])->first();
            if (!empty($food)) {
                return $this->update([
                    FoodDayConstant::NUMBER_FIELD => $request['setNumber'],
                ], $food[BaseConstant::ID_FIELD]);
            }
            return $this->create([
                FoodDayConstant::FOOD_ID_FIELD => $request['index'],
                FoodDayConstant::NUMBER_FIELD => $request['setNumber'],
                FoodDayConstant::DATE_FIELD => $request['today']
            ]);
        } catch (\Exception $e) {
            Log::channel('customError')->error($e->getMessage());
            return false;
        }
    }

    /**
     * @param $id
     * @param $today
     * @return mixed
     */
    public function checkFoodRemain($id,$today)
    {
        return $this->select([
                FoodConstant::VIETNAMESE_NAME_FIELD,
                FoodDayConstant::NUMBER_FIELD
            ])->leftJoin(
                FoodConstant::TABLE_NAME,
                FoodConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD,
                BaseConstant::EQUAL,
                FoodDayConstant::FOOD_ID_FIELD
            )->where(FoodDayConstant::FOOD_ID_FIELD, $id)
            ->where(FoodDayConstant::DATE_FIELD, $today)
            ->first();
    }

    /**
     * @param array $request
     * @return mixed
     */
    public function deleteSetFoodToday(array $request)
    {
        try {
            return $this->delete($request['fid']);
        } catch (\Exception $e) {
            Log::channel('customError')->error($e->getMessage());
            return false;
        }
    }
}

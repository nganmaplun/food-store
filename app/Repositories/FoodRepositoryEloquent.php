<?php

namespace App\Repositories;

use App\Constants\BaseConstant;
use App\Constants\FoodConstant;
use App\Constants\FoodDayConstant;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\FoodRepository;
use App\Entities\Food;
use App\Validators\FoodValidator;

/**
 * Class FoodRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class FoodRepositoryEloquent extends BaseRepository implements FoodRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Food::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @param null $today
     * @return mixed
     */
    public function getListFoods($today = null): mixed
    {
        $result = $this->select([
                FoodConstant::TABLE_NAME . '.' . '*',
                FoodDayConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD . ' AS fid',
                FoodDayConstant::TABLE_NAME . '.' . FoodDayConstant::NUMBER_FIELD,
                FoodDayConstant::TABLE_NAME . '.' . FoodDayConstant::FOOD_ID_FIELD,
            ])
            ->leftJoin(
                FoodDayConstant::TABLE_NAME, function ($join) use ($today) {
                    $join->on(
                        FoodDayConstant::TABLE_NAME . '.' . FoodDayConstant::FOOD_ID_FIELD,
                        BaseConstant::EQUAL,
                        FoodConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD
                    );
                    if ($today) {
                        $join->where(
                            FoodDayConstant::TABLE_NAME . '.' . FoodDayConstant::DATE_FIELD,
                            BaseConstant::EQUAL,
                            $today
                        );
                    }
                }
            );

        return $result->get();
    }
}

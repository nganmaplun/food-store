<?php

namespace App\Repositories;

use App\Constants\BaseConstant;
use App\Constants\FoodConstant;
use App\Constants\FoodDayConstant;
use App\Traits\CustomValidateTrait;
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
    use CustomValidateTrait;

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
    public function getListFoods($condition = null): mixed
    {
        $result = $this->select([
                FoodConstant::TABLE_NAME . '.' . '*',
                FoodDayConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD . ' AS fid',
                FoodDayConstant::TABLE_NAME . '.' . FoodDayConstant::NUMBER_FIELD,
                FoodDayConstant::TABLE_NAME . '.' . FoodDayConstant::FOOD_ID_FIELD,
            ])
            ->leftJoin(
                FoodDayConstant::TABLE_NAME, function ($join) use ($condition) {
                    $join->on(
                        FoodDayConstant::TABLE_NAME . '.' . FoodDayConstant::FOOD_ID_FIELD,
                        BaseConstant::EQUAL,
                        FoodConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD
                    );
                    if ($condition && $this->checkValidDate($condition)) {
                        $join->where(
                            FoodDayConstant::TABLE_NAME . '.' . FoodDayConstant::DATE_FIELD,
                            BaseConstant::EQUAL,
                            $condition
                        );
                    }
                }
            );
        // if we have new condition, create new validate in trait
        if ($condition && !$this->checkValidDate($condition)) {
            $result->where([FoodConstant::CATEGORY_FIELD => $condition]);
        }
        if ($condition && $this->checkValidDate($condition)) {
            $result->whereNotNull(FoodDayConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD);
        }

        return $result->get();
    }

    /**
     * @param null $today
     * @return mixed
     */
    public function getListFoodsMenu(): mixed
    {
        return $this->get();
    }


    /**
     * @return mixed
     */
    public function listAllFoodName()
    {
        $results = $this->select(FoodConstant::VIETNAMESE_NAME_FIELD)
            ->get();
        $lstFoods = [];
        foreach ($results as $result) {
            $lstFoods[] = $result[FoodConstant::VIETNAMESE_NAME_FIELD];
        }
        return json_encode($lstFoods);
    }

    /**
     * @param array $request
     * @return mixed
     */
    public function getFoodIdByName(array $request)
    {
        return $this->select(BaseConstant::ID_FIELD)
            ->where(FoodConstant::VIETNAMESE_NAME_FIELD, $request['fid'])
            ->first();
    }
}

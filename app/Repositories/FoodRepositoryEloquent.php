<?php

namespace App\Repositories;

use App\Constants\BaseConstant;
use App\Constants\FoodConstant;
use App\Constants\FoodDayConstant;
use App\Traits\CustomValidateTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
     * @param null $condition
     * @param null $foodName
     * @return mixed
     */
    public function getListFoods($condition = null, $foodName = null): mixed
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
        if ($foodName) {
            $result->where(function ($model) use($foodName) {
                $model->where(FoodConstant::VIETNAMESE_NAME_FIELD, 'LIKE', '%' . $foodName . '%');
                $model->orWhere(FoodConstant::JAPANESE_NAME_FIELD, 'LIKE', '%' . $foodName . '%');
                $model->orWhere(FoodConstant::ENGLISH_NAME_FIELD, 'LIKE', '%' . $foodName . '%');
            });
        }

        return $result->get();
    }

    /**
     * @param null $foodName
     * @return mixed
     */
    public function getListFoodsMenu($foodName = null): mixed
    {
        if ($foodName) {
            return $this->where(function ($model) use($foodName) {
                $model->where(FoodConstant::VIETNAMESE_NAME_FIELD, 'LIKE', '%' . $foodName . '%');
                $model->orWhere(FoodConstant::JAPANESE_NAME_FIELD, 'LIKE', '%' . $foodName . '%');
                $model->orWhere(FoodConstant::ENGLISH_NAME_FIELD, 'LIKE', '%' . $foodName . '%');
            })->get();
        }
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

    /**
     * @return mixed
     */
    public function listAllFoods()
    {
        $select = [
            BaseConstant::ID_FIELD,
            FoodConstant::VIETNAMESE_NAME_FIELD,
            FoodConstant::JAPANESE_NAME_FIELD,
            FoodConstant::ENGLISH_NAME_FIELD,
            FoodConstant::PRICE_FIELD,
            FoodConstant::CATEGORY_FIELD,
        ];
        return $this->select($select)->get();
    }

    /**
     * @param array $request
     * @return mixed
     */
    public function createFood(array $request)
    {
        try {
            $dataCreate = [
                FoodConstant::VIETNAMESE_NAME_FIELD => $request[FoodConstant::VIETNAMESE_NAME_FIELD],
                FoodConstant::JAPANESE_NAME_FIELD => $request[FoodConstant::JAPANESE_NAME_FIELD],
                FoodConstant::ENGLISH_NAME_FIELD => $request[FoodConstant::ENGLISH_NAME_FIELD],
                FoodConstant::SHORT_NAME_FIELD => $request[FoodConstant::SHORT_NAME_FIELD],
                FoodConstant::PRICE_FIELD => $request[FoodConstant::PRICE_FIELD],
                FoodConstant::CATEGORY_FIELD => $request[FoodConstant::CATEGORY_FIELD],
                FoodConstant::RECIPE_FIELD => $request[FoodConstant::RECIPE_FIELD] ?? '',
                FoodConstant::DESCRIPTION_FIELD => $request[FoodConstant::DESCRIPTION_FIELD] ?? '',
                FoodConstant::IMAGE_FIELD => $request[FoodConstant::IMAGE_FIELD] ?? 'logo.png',
            ];
            if (isset($request[FoodConstant::IMAGE_FIELD]) && !empty($request[FoodConstant::IMAGE_FIELD])) {
                $result = Storage::disk('public')->put('', $request[FoodConstant::IMAGE_FIELD]);
                if ($result) {
                    $dataCreate[FoodConstant::IMAGE_FIELD] = $result;
                }
            }
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
    public function getDetailFood($id)
    {
        return $this->where(BaseConstant::ID_FIELD, $id)->first();
    }

    /**
     * @param array $request
     * @param $id
     * @return mixed
     */
    public function updateFood(array $request, $id)
    {
        try {
            $food = $this->where(BaseConstant::ID_FIELD, $id)->first();
            $oldImageName = $food[FoodConstant::IMAGE_FIELD];
            $dataUpdate = [
                FoodConstant::VIETNAMESE_NAME_FIELD => $request[FoodConstant::VIETNAMESE_NAME_FIELD],
                FoodConstant::JAPANESE_NAME_FIELD => $request[FoodConstant::JAPANESE_NAME_FIELD],
                FoodConstant::ENGLISH_NAME_FIELD => $request[FoodConstant::ENGLISH_NAME_FIELD],
                FoodConstant::SHORT_NAME_FIELD => $request[FoodConstant::SHORT_NAME_FIELD],
                FoodConstant::PRICE_FIELD => $request[FoodConstant::PRICE_FIELD],
                FoodConstant::CATEGORY_FIELD => $request[FoodConstant::CATEGORY_FIELD],
                FoodConstant::RECIPE_FIELD => $request[FoodConstant::RECIPE_FIELD] ?? '',
                FoodConstant::DESCRIPTION_FIELD => $request[FoodConstant::DESCRIPTION_FIELD] ?? '',
                FoodConstant::IMAGE_FIELD => $request[FoodConstant::IMAGE_FIELD] ?? $oldImageName,
            ];
            if (isset($request[FoodConstant::IMAGE_FIELD]) && !empty($request[FoodConstant::IMAGE_FIELD])) {
                $result = Storage::disk('public')->put('', $request[FoodConstant::IMAGE_FIELD]);
                if ($result) {
                    $dataUpdate[FoodConstant::IMAGE_FIELD] = $result;
                }
            }
            return $this->update($dataUpdate, $id);
        } catch (\Exception $e) {
            Log::channel('customError')->error($e->getMessage());
            return false;
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteFood($id)
    {
        try {
            return $this->delete($id);
        } catch (\Exception $e) {
            Log::channel('customError')->error($e->getMessage());
            return false;
        }
    }
}

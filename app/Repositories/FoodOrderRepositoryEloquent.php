<?php

namespace App\Repositories;

use App\Constants\FoodOrderConstant;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\FoodOrderRepository;
use App\Entities\FoodOrder;
use App\Validators\FoodOrderValidator;

/**
 * Class FoodOrderRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class FoodOrderRepositoryEloquent extends BaseRepository implements FoodOrderRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return FoodOrder::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @param array $request
     * @return mixed
     */
    public function addFoodToOrder(array $request)
    {
        try {
            $data = [
                FoodOrderConstant::ORDER_ID_FIELD => $request['orderId'],
                FoodOrderConstant::FOOD_ID_FIELD => $request['foodId'],
                FoodOrderConstant::ORDER_NUM_FIELD => $request['orderNum'],
                FoodOrderConstant::NOTE_FIELD => $request['note'],
            ];
            return $this->create($data);
        } catch (\Exception $e) {
            Log::channel('customError')->error($e->getMessage());
            return false;
        }
    }
}

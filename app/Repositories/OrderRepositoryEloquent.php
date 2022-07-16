<?php

namespace App\Repositories;

use App\Constants\BaseConstant;
use App\Constants\FoodConstant;
use App\Constants\FoodOrderConstant;
use App\Constants\OrderConstant;
use App\Constants\TableConstant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\OrderRepository;
use App\Entities\Order;
use App\Validators\OrderValidator;

/**
 * Class OrderRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class OrderRepositoryEloquent extends BaseRepository implements OrderRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Order::class;
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
     * @param $userId
     * @return mixed
     */
    public function createOrder(array $request, $userId)
    {
        try {
            $data = [
                OrderConstant::EMPLOYEE_ID_FIELD => $userId,
                OrderConstant::TABLE_ID_FIELD => $request[BaseConstant::ID_FIELD],
                OrderConstant::PARENT_TABLE_ID_FIELD => $request['subIndex'] ?? null,
                OrderConstant::CUSTOMER_TYPE_FIELD => $request['guestType'],
                OrderConstant::NUMBER_OF_CUSTOMER_FIELD => $request['guestNum'],
                OrderConstant::DESCRIPTION_FIELD => $request['otherNote'],
                OrderConstant::TOTAL_PRICE_FIELD => 0,
                OrderConstant::ORDER_DATE_FIELD => Carbon::now()->toDateString(),
                OrderConstant::IS_PAID_FIELD => false,
                BaseConstant::STATUS_FIELD => 0,
            ];
            return $this->create($data);
        } catch (\Exception $e) {
            Log::channel('customError')->error($e->getMessage());
            return false;
        }
    }

    /**
     * @param $orderId
     * @param null $foodId
     * @return mixed
     */
    public function getListFoodsInOrder($orderId, $foodId = null)
    {
        $select = [
            FoodConstant::VIETNAMESE_NAME_FIELD,
            FoodConstant::CATEGORY_FIELD,
            FoodOrderConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD,
            FoodOrderConstant::ORDER_NUM_FIELD,
            FoodOrderConstant::IS_DELIVERED_FIELD,
            FoodOrderConstant::IS_COMPLETED_FIELD,
            FoodOrderConstant::IS_NEW_FIELD,
        ];
        $result = $this->select($select)
                ->leftJoin(
                    FoodOrderConstant::TABLE_NAME,
                    FoodOrderConstant::TABLE_NAME . '.' . FoodOrderConstant::ORDER_ID_FIELD,
                    BaseConstant::EQUAL,
                    OrderConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD
                )
                ->leftJoin(
                    FoodConstant::TABLE_NAME,
                    FoodConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD,
                    BaseConstant::EQUAL,
                    FoodOrderConstant::TABLE_NAME . '.' . FoodOrderConstant::FOOD_ID_FIELD
                )
                ->where(OrderConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD, $orderId)
                ->whereNotNull(FoodOrderConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD)
                ->where(OrderConstant::ORDER_DATE_FIELD, Carbon::now()->toDateString())
                ->whereIn(OrderConstant::TABLE_NAME . '.' . BaseConstant::STATUS_FIELD, [0, 1, 2]);
        if ($foodId) {
            $result->where(FoodOrderConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD, $foodId);
        }

        return $result->get();
    }

    /**
     * @param $category
     * @return mixed
     */
    public function getFoodsByCategory($category)
    {
        $select = [
            FoodOrderConstant::TABLE_NAME .  '.' . BaseConstant::ID_FIELD,
            TableConstant::TABLE_NAME .  '.' . BaseConstant::ID_FIELD . ' AS tblId',
            TableConstant::NAME_FIELD,
            FoodConstant::VIETNAMESE_NAME_FIELD,
            FoodConstant::CATEGORY_FIELD,
            FoodConstant::RECIPE_FIELD,
            FoodOrderConstant::ORDER_ID_FIELD,
            FoodOrderConstant::ORDER_NUM_FIELD,
            FoodOrderConstant::NOTE_FIELD,
            FoodOrderConstant::IS_DELIVERED_FIELD,
        ];

        return $this->select($select)
            ->leftJoin(
                FoodOrderConstant::TABLE_NAME,
                FoodOrderConstant::TABLE_NAME . '.' . FoodOrderConstant::ORDER_ID_FIELD,
                BaseConstant::EQUAL,
                OrderConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD
            )
            ->leftJoin(
                FoodConstant::TABLE_NAME,
                FoodConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD,
                BaseConstant::EQUAL,
                FoodOrderConstant::TABLE_NAME . '.' . FoodOrderConstant::FOOD_ID_FIELD
            )
            ->leftJoin(
                TableConstant::TABLE_NAME,
                TableConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD,
                BaseConstant::EQUAL,
                OrderConstant::TABLE_NAME . '.' . OrderConstant::TABLE_ID_FIELD
            )
            ->whereNotNull(FoodOrderConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD)
            ->where(FoodConstant::CATEGORY_FIELD, $category)
            ->where(FoodOrderConstant::IS_COMPLETED_FIELD, false)
            ->where(FoodOrderConstant::IS_DELIVERED_FIELD, false)
            ->where(OrderConstant::ORDER_DATE_FIELD, Carbon::now()->toDateString())
            ->where(OrderConstant::TABLE_NAME . '.' . BaseConstant::STATUS_FIELD, 1)
            ->get();
    }

    /**
     * @param $orderId
     * @param string $type
     * @return void
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function updateOrderStatus($orderId, string $type)
    {
        $status = match ($type) {
            BaseConstant::SEND_CHEF => 1,
            BaseConstant::SEND_CASHIER => 2
        };
        $this->update([
            BaseConstant::STATUS_FIELD => $status
        ], $orderId);
    }

    /**
     * @return mixed
     */
    public function getToPaidOrder()
    {
        return $this->where(OrderConstant::IS_PAID_FIELD, false)
            ->where(BaseConstant::STATUS_FIELD, 2)
            ->get();
    }

    /**
     * @param $orderId
     * @return mixed
     */
    public function getDetailOrder($orderId)
    {
        $select = [
            FoodConstant::VIETNAMESE_NAME_FIELD,
            FoodConstant::PRICE_FIELD,
            FoodOrderConstant::ORDER_NUM_FIELD,
            OrderConstant::TABLE_NAME . '.' . OrderConstant::DESCRIPTION_FIELD
        ];
        return $this->select($select)
            ->leftJoin(
                FoodOrderConstant::TABLE_NAME,
                FoodOrderConstant::TABLE_NAME . '.' . FoodOrderConstant::ORDER_ID_FIELD,
                BaseConstant::EQUAL,
                OrderConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD
            )
            ->leftJoin(
                FoodConstant::TABLE_NAME,
                FoodConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD,
                BaseConstant::EQUAL,
                FoodOrderConstant::TABLE_NAME . '.' . FoodOrderConstant::FOOD_ID_FIELD
            )
            ->where(OrderConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD, $orderId)
            ->whereNotNull(FoodOrderConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD)
            ->where(OrderConstant::ORDER_DATE_FIELD, Carbon::now()->toDateString())
            ->where(OrderConstant::TABLE_NAME . '.' . BaseConstant::STATUS_FIELD, 2)
            ->get();
    }

    /**
     * @param $orderId
     * @param mixed $totalPrice
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function updateFinalOrder($orderId, $totalPrice)
    {
        return $this->update([
            OrderConstant::TOTAL_PRICE_FIELD => $totalPrice,
            OrderConstant::IS_PAID_FIELD => true,
        ], $orderId);
    }

    /**
     * @param $orderId
     * @return mixed
     */
    public function detailOrder($orderId)
    {
        return $this->where(BaseConstant::ID_FIELD, $orderId)->first();
    }
}

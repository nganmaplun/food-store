<?php

namespace App\Repositories;

use App\Constants\BaseConstant;
use App\Constants\FoodConstant;
use App\Constants\FoodOrderConstant;
use App\Constants\OrderConstant;
use App\Constants\TableConstant;
use App\Constants\UserConstant;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
            FoodConstant::JAPANESE_NAME_FIELD,
            FoodConstant::ENGLISH_NAME_FIELD,
            FoodConstant::SHORT_NAME_FIELD,
            FoodConstant::CATEGORY_FIELD,
            FoodOrderConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD,
            FoodOrderConstant::ORDER_NUM_FIELD,
            FoodOrderConstant::NOTE_FIELD,
            FoodOrderConstant::IS_DELIVERED_FIELD,
            FoodOrderConstant::IS_COMPLETED_FIELD,
            FoodOrderConstant::IS_NEW_FIELD,
            FoodOrderConstant::IS_SENT_FIELD,
            FoodOrderConstant::ORDER_TIME_FIELD,
            FoodOrderConstant::IS_CANCEL_FIELD,
            FoodOrderConstant::IS_COOKING_FIELD,
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
                ->whereNull(FoodOrderConstant::TABLE_NAME . '.' . BaseConstant::DELETEDAT_FIELD)
                ->whereIn(OrderConstant::TABLE_NAME . '.' . BaseConstant::STATUS_FIELD, [0, 1]);
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
            FoodConstant::JAPANESE_NAME_FIELD,
            FoodConstant::ENGLISH_NAME_FIELD,
            FoodConstant::SHORT_NAME_FIELD,
            FoodConstant::CATEGORY_FIELD,
            FoodConstant::RECIPE_FIELD,
            FoodOrderConstant::ORDER_ID_FIELD,
            FoodOrderConstant::ORDER_NUM_FIELD,
            FoodOrderConstant::NOTE_FIELD,
            FoodOrderConstant::IS_DELIVERED_FIELD,
            FoodOrderConstant::ORDER_TIME_FIELD,
            FoodOrderConstant::IS_COOKING_FIELD,
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
            ->where(FoodOrderConstant::IS_CANCEL_FIELD, BaseConstant::DIFFERENCE, 1)
            ->whereNull(FoodOrderConstant::TABLE_NAME . '.' . BaseConstant::DELETEDAT_FIELD)
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
        try {
            $status = match ($type) {
                BaseConstant::SEND_CHEF => 1,
                BaseConstant::SEND_CASHIER => 2
            };
            return $this->update([
                BaseConstant::STATUS_FIELD => $status
            ], $orderId);
        } catch (\Exception $e) {
            return false;
        }
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
            FoodConstant::JAPANESE_NAME_FIELD,
            FoodConstant::ENGLISH_NAME_FIELD,
            FoodConstant::SHORT_NAME_FIELD,
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
    public function updateFinalOrder($orderId, $totalPrice, $note, $paidType, $discount, $otherMoney, $userId)
    {
        return $this->update([
            OrderConstant::TOTAL_PRICE_FIELD => $totalPrice,
            OrderConstant::DESCRIPTION_FIELD => $note,
            OrderConstant::IS_PAID_FIELD => true,
            OrderConstant::PAID_TYPE_FIELD => $paidType,
            OrderConstant::DISCOUNT_FIELD => $discount,
            OrderConstant::OTHER_MONEY_FIELD => $otherMoney,
            OrderConstant::CASHIER_ID_FIELD => $userId,
        ], $orderId);
    }

    /**
     * @param $orderId
     * @return mixed
     */
    public function detailOrder($orderId)
    {
        $select = [
            TableConstant::TABLE_NAME . '.' . TableConstant::NAME_FIELD,
            UserConstant::FULLNAME_FIELD,
            OrderConstant::TABLE_NAME . '.*',
        ];
        return $this->select($select)
            ->leftJoin(
                TableConstant::TABLE_NAME,
                TableConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD,
                BaseConstant::EQUAL,
                OrderConstant::TABLE_ID_FIELD
            )
            ->leftJoin(
                UserConstant::TABLE_NAME,
                UserConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD,
                BaseConstant::EQUAL,
                OrderConstant::EMPLOYEE_ID_FIELD
            )
            ->where(OrderConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD, $orderId)->first();
    }

    /**
     * @param $condition
     * @return mixed
     */
    public function aggOrder($condition)
    {
        $orderDate = match ($condition['type']) {
            'day' => 'DATE_FORMAT(order_date, "%Y-%m-%d")',
            'month' => 'DATE_FORMAT(order_date, "%Y-%m")',
            'year' => 'DATE_FORMAT(order_date, "%Y")',
        };
        $select2 = [
            OrderConstant::CUSTOMER_TYPE_FIELD,
            DB::raw('sum(number_of_customers) as cus_num'),
        ];
        $select = [
            DB::raw('sum(order_num) AS total_food'),
            DB::raw('sum(total_price) as total_price'),
            DB::raw($orderDate . ' AS order_date_s'),
        ];
        $others = $this->select($select)
            ->leftJoin(
                FoodOrderConstant::TABLE_NAME,
                OrderConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD,
                BaseConstant::EQUAL,
                FoodOrderConstant::ORDER_ID_FIELD
            )
            ->where(OrderConstant::IS_PAID_FIELD, true)
            ->groupBy([DB::raw('order_date_s')]);
        if (isset($condition['from']) && !empty($condition['from'])) {
            $others->where(OrderConstant::ORDER_DATE_FIELD, BaseConstant::GREATER_AND_EQUAL_THAN, $condition['from']);
        }
        if (isset($condition['to']) && !empty($condition['to'])) {
            $others->where(OrderConstant::ORDER_DATE_FIELD, BaseConstant::LESS_AND_EQUAL_THAN, $condition['to']);
        }
        $others = $others->get();
        $arrFinal = [];
        $i = 0;
        foreach ($others as $oth) {
            $customers = $this->select($select2)
                ->where(OrderConstant::IS_PAID_FIELD, true)
                ->where(DB::raw($orderDate), $oth['order_date_s'])
                ->groupBy([DB::raw('customer_type')])
                ->get();
            $arrFinal[$i]['total_food'] = $oth['total_food'];
            $arrFinal[$i]['total_price'] = $oth['total_price'];
            $arrFinal[$i]['order_date'] = $oth['order_date_s'];
            foreach ($customers as $cus) {
                if ($cus[OrderConstant::CUSTOMER_TYPE_FIELD] == 'V') {
                    $arrFinal[$i]['vietnamese_guest'] = $cus['cus_num'];
                }
                if ($cus[OrderConstant::CUSTOMER_TYPE_FIELD] == 'J') {
                    $arrFinal[$i]['japanese_guest'] = $cus['cus_num'];
                }
                if ($cus[OrderConstant::CUSTOMER_TYPE_FIELD] == 'E') {
                    $arrFinal[$i]['other_guest'] = $cus['cus_num'];
                }
            }
            $i++;
        }
        return $arrFinal;
    }

    /**
     * @param mixed $orderId
     * @return mixed
     */
    public function checkOrderStatus(mixed $orderId)
    {
        $orderStatus = $this->select(BaseConstant::STATUS_FIELD)
            ->where(BaseConstant::ID_FIELD, $orderId)
            ->first();

        return $orderStatus[BaseConstant::STATUS_FIELD];
    }

    /**
     * @param $orderId
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function updateFinal($orderId)
    {
        try {
            return $this->update([
                OrderConstant::DRAFT_FIELD => false
            ], $orderId);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param array $lstId
     * @param string $today
     * @return mixed
     */
    public function getListCheckoutOrder(array $lstId, string $today)
    {
        $tableOrder = [];
        foreach ($lstId as $id) {
            $result = $this->select(BaseConstant::ID_FIELD, BaseConstant::STATUS_FIELD, OrderConstant::DRAFT_FIELD)
                ->where(OrderConstant::ORDER_DATE_FIELD, $today)
                ->where(OrderConstant::TABLE_ID_FIELD, $id)
                ->orderBy(OrderConstant::TABLE_NAME . '.' . BaseConstant::CREATEDAT_FIELD, 'DESC')
                ->first();
            $tableOrder[$id]['order_id'] = $result[BaseConstant::ID_FIELD] ?? '';
            $tableOrder[$id]['order_status'] = $result[BaseConstant::STATUS_FIELD] ?? '';
            $tableOrder[$id]['order_draft'] = $result[OrderConstant::DRAFT_FIELD] ?? '';
        }
        return $tableOrder;
    }
}

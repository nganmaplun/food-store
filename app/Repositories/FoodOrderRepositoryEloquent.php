<?php

namespace App\Repositories;

use App\Constants\BaseConstant;
use App\Constants\FoodConstant;
use App\Constants\FoodOrderConstant;
use App\Constants\OrderConstant;
use App\Constants\TableConstant;
use Illuminate\Support\Facades\DB;
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
            $foodOrder = $this->getOldFoodOrder($request['orderId'], $request['foodId']);
            if (!empty($foodOrder)) {
                return $this->update([
                    FoodOrderConstant::ORDER_NUM_FIELD => $foodOrder[FoodOrderConstant::ORDER_NUM_FIELD] + $request['orderNum'],
                    FoodOrderConstant::NOTE_FIELD => $request['note']
                ], $foodOrder[BaseConstant::ID_FIELD]);
            }

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


    public function getOldFoodOrder($orderId, $foodId)
    {
        return $this->where(FoodOrderConstant::ORDER_ID_FIELD, $orderId)
            ->where(FoodOrderConstant::FOOD_ID_FIELD, $foodId)
            ->where(FoodOrderConstant::IS_DELIVERED_FIELD, false)
            ->where(FoodOrderConstant::IS_SENT_FIELD, false)
            ->first();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function removeOrderFood($id)
    {
        try {
            return $this->delete($id);
        } catch (\Exception $e) {
            Log::channel('customError')->error($e->getMessage());
            return false;
        }
    }

    /**
     * @param $orderId
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function updateToOldOrder($orderId)
    {
        return $this->where(FoodOrderConstant::ORDER_ID_FIELD, $orderId)
            ->update([
                FoodOrderConstant::IS_NEW_FIELD => false,
                FoodOrderConstant::IS_SENT_FIELD => true
            ]);
    }

    /**
     * @param $orderId
     * @param $foodId
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function updateToCompletedFood($orderId, $foodId)
    {
        return $this->where(FoodOrderConstant::ORDER_ID_FIELD, $orderId)
            ->where(BaseConstant::ID_FIELD, $foodId)
            ->where(FoodOrderConstant::IS_DELIVERED_FIELD, false)
            ->where(FoodOrderConstant::IS_COMPLETED_FIELD, false)
            ->update([
                FoodOrderConstant::IS_COMPLETED_FIELD => true
            ]);
    }

    /**
     * @param mixed $index
     * @return mixed
     */
    public function changeToTableStatus($index)
    {
        return $this->where(BaseConstant::ID_FIELD, $index)
            ->where(FoodOrderConstant::IS_DELIVERED_FIELD, false)
            ->where(FoodOrderConstant::IS_COMPLETED_FIELD, true)
            ->update([
                FoodOrderConstant::IS_DELIVERED_FIELD => true
            ]);
    }

    /**
     * @param array $lstId
     * @param string $today
     * @return mixed
     */
    public function getListCountOrder(array $lstId, string $today)
    {
        $select = [
            DB::raw('SUM(order_num) AS total_order')
        ];
        $totalOrder = [];
        $totalCompleted = [];
        $totalInOrder = [];
        $tableOrder = [];
        foreach ($lstId as $id) {
            $lstOrderId = DB::table(OrderConstant::TABLE_NAME)
                ->leftJoin(
                    TableConstant::TABLE_NAME,
                    TableConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD,
                    BaseConstant::EQUAL,
                    OrderConstant::TABLE_ID_FIELD
                )
                ->select(OrderConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD)
                ->where(TableConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD, $id)
                ->whereIn(TableConstant::TABLE_NAME . '.' . BaseConstant::STATUS_FIELD, [1, 2])
                ->where(OrderConstant::ORDER_DATE_FIELD, $today)
                ->whereIn(OrderConstant::TABLE_NAME . '.' . BaseConstant::STATUS_FIELD, [0, 1, 2])
                ->orderBy(OrderConstant::TABLE_NAME . '.' . BaseConstant::CREATEDAT_FIELD, 'DESC')
                ->limit(1);
            $result = $this->select($select)
                ->leftJoinSub($lstOrderId, 'odr', function($join) {
                    $join->on(
                        'odr.id',
                        BaseConstant::EQUAL,
                        FoodOrderConstant::ORDER_ID_FIELD
                    );
                })
                ->whereNotNull('odr.id')
                ->first();
            $totalOrder[$id] = $result['total_order'] ?? 0;
            $result = $this->select($select)
                ->leftJoinSub($lstOrderId, 'odr', function($join) {
                    $join->on(
                        'odr.id',
                        BaseConstant::EQUAL,
                        FoodOrderConstant::ORDER_ID_FIELD
                    );
                })
                ->whereNotNull('odr.id')
                ->where(FoodOrderConstant::IS_COMPLETED_FIELD, true)
                ->where(FoodOrderConstant::IS_DELIVERED_FIELD, true)
                ->first();
            $totalCompleted[$id] = $result['total_order'] ?? 0;
            $result = $this->select($select)
                ->leftJoinSub($lstOrderId, 'odr', function($join) {
                    $join->on(
                        'odr.id',
                        BaseConstant::EQUAL,
                        FoodOrderConstant::ORDER_ID_FIELD
                    );
                })
                ->whereNotNull('odr.id')
                ->where(FoodOrderConstant::IS_COMPLETED_FIELD, false)
                ->where(FoodOrderConstant::IS_DELIVERED_FIELD, false)
                ->first();
            $totalInOrder[$id] = $result['total_order'] ?? 0;
            $tableOrder[$id] = $lstOrderId->first() ? $lstOrderId->first()->id : null;
        }

        return [
            'total_order' => $totalOrder,
            'total_completed' => $totalCompleted,
            'total_inorder' => $totalInOrder,
            'table_order' => $tableOrder
        ];
    }

    public function getAllFinishFood($today)
    {
        $select = [
            TableConstant::TABLE_NAME . '.' . TableConstant::NAME_FIELD,
            FoodOrderConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD,
            FoodOrderConstant::ORDER_NUM_FIELD,
            FoodConstant::TABLE_NAME . '.' . FoodConstant::VIETNAMESE_NAME_FIELD
        ];

        return $this->select($select)
            ->leftJoin(
                FoodConstant::TABLE_NAME,
                FoodConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD,
                BaseConstant::EQUAL,
                FoodOrderConstant::FOOD_ID_FIELD
            )
            ->leftJoin(
                OrderConstant::TABLE_NAME,
                OrderConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD,
                BaseConstant::EQUAL,
                FoodOrderConstant::ORDER_ID_FIELD
            )
            ->leftJoin(
                TableConstant::TABLE_NAME,
                TableConstant::TABLE_NAME . '.' . BaseConstant::ID_FIELD,
                BaseConstant::EQUAL,
                OrderConstant::TABLE_ID_FIELD
            )
            ->where(FoodOrderConstant::IS_COMPLETED_FIELD, true)
            ->where(FoodOrderConstant::IS_DELIVERED_FIELD, false)
            ->where(OrderConstant::ORDER_DATE_FIELD, $today)
            ->where(TableConstant::TABLE_NAME . '.' . BaseConstant::STATUS_FIELD, 1)
            ->get();
    }

    /**
     * @param array $request
     * @return mixed
     */
    public function cancelCooking(array $request)
    {
        try {
            return $this->update([
                FoodOrderConstant::IS_CANCEL_FIELD => true
            ], $request['foodOrderId']);
        } catch (\Exception $e) {
            Log::channel('customError')->error($e->getMessage());
            return false;
        }
    }

    /**
     * @param array $request
     * @return mixed
     */
    public function acceptCooking(array $request)
    {
        try {
            return $this->update([
                FoodOrderConstant::IS_COOKING_FIELD => true
            ], $request['foodOrderId']);
        } catch (\Exception $e) {
            Log::channel('customError')->error($e->getMessage());
            return false;
        }
    }
}

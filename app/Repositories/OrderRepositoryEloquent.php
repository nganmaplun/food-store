<?php

namespace App\Repositories;

use App\Constants\BaseConstant;
use App\Constants\OrderConstant;
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
                OrderConstant::CUSTOMER_TYPE_FIELD => $request['guestType'],
                OrderConstant::NUMBER_OF_CUSTOMER_FIELD => $request['guestNum'],
                OrderConstant::DESCRIPTION_FIELD => $request['otherNote'],
                OrderConstant::TOTAL_PRICE_FIELD => 0,
                OrderConstant::IS_PAID_FIELD => false,
                BaseConstant::STATUS_FIELD => false,
            ];
            return $this->create($data);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }
}

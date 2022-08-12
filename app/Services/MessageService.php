<?php

namespace App\Services;

use App\Constants\BaseConstant;
use App\Constants\FoodConstant;
use App\Repositories\FoodOrderRepository;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;

class MessageService
{
    /**
     * @var array
     */
    private array $options;

    /**
     * @var OrderRepository
     */
    private OrderRepository $orderRepository;

    /**
     * @var FoodOrderRepository
     */
    private FoodOrderRepository $foodOrderRepository;

    /**
     *
     */
    public function __construct(
        OrderRepository $orderRepository,
        FoodOrderRepository $foodOrderRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->foodOrderRepository = $foodOrderRepository;
        $this->options = [
            'cluster' => 'ap1',
            'encrypted' => true
        ];
    }

    /**
     * @param $tableName
     * @param $orderId
     * @param $data
     * @param $type
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Pusher\ApiErrorException
     * @throws \Pusher\PusherException
     */
    public function sendNotify($tableName, $orderId, $data, $type, $tableId = null, $foodId = null, $createTable = false)
    {
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $this->options
        );
        Log::channel('customInfo')->info('tableName : ', [$tableName]);
        Log::channel('customInfo')->info('data : ', [$data]);
        Log::channel('customInfo')->info('type : ', [$type]);
        $sendSalad = true;
        $sendGrill = true;
        $sendSteam = true;
        $sendDrying = true;
        $sendDrink = true;
        switch ($type) {
            case BaseConstant::SEND_CHEF:
                foreach ($data as $food) {
                    if ($food[FoodConstant::CATEGORY_FIELD] == BaseConstant::FOOD_SALAD) {
                        if (!$sendSalad) continue;
                        $pusher->trigger('NotifyChefSalad', BaseConstant::CHEF_SALAD_CHANNEL, $tableName);
                        $sendSalad = false;
                    }
                    if ($food[FoodConstant::CATEGORY_FIELD] == BaseConstant::FOOD_DRYING) {
                        if (!$sendDrying) continue;
                        $pusher->trigger('NotifyChefDrying', BaseConstant::CHEF_DRYING_CHANNEL, $tableName);
                        $sendDrying = false;
                    }
                    if ($food[FoodConstant::CATEGORY_FIELD] == BaseConstant::FOOD_GRILL) {
                        if (!$sendGrill) continue;
                        $pusher->trigger('NotifyChefGrill', BaseConstant::CHEF_GRILL_CHANNEL, $tableName);
                        $sendGrill = false;
                    }
                    if ($food[FoodConstant::CATEGORY_FIELD] == BaseConstant::FOOD_STEAM) {
                        if (!$sendSteam) continue;
                        $pusher->trigger('NotifyChefSteam', BaseConstant::CHEF_STEAM_CHANNEL, $tableName);
                        $sendSteam = false;
                    }
                    if ($food[FoodConstant::CATEGORY_FIELD] == BaseConstant::FOOD_DRINK) {
                        if (!$sendDrink) continue;
                        $pusher->trigger('NotifyChefDrink', BaseConstant::CHEF_DRINK_CHANNEL, $tableName);
                        $sendDrink = false;
                    }
                }
                $this->orderRepository->updateOrderStatus($orderId, BaseConstant::SEND_CHEF);
                $this->foodOrderRepository->updateToOldOrder($orderId, $foodId);
                break;

            case BaseConstant::SEND_WAITER:
                $info = [
                    'tableId' => $tableId ?? null,
                    'orderId' => $orderId,
                    'createTable' => $createTable
                ];
                $pusher->trigger('NotifyWaiter', BaseConstant::WAITER_CHANNEL, $info);
                $this->foodOrderRepository->updateToCompletedFood($orderId, $foodId);
                break;

            case BaseConstant::SEND_CASHIER:
                $info = [
                    'createTable' => $createTable
                ];
                $pusher->trigger('NotifyCashier', BaseConstant::CASHIER_CHANNEL, $info);
                if (!$createTable) {
                    $this->orderRepository->updateOrderStatus($orderId, BaseConstant::SEND_CASHIER);
                }
                break;

            case BaseConstant::SEND_WAITER_BACK:
                $info = [
                    'tableId' => $tableId ?? null,
                    'orderId' => $orderId,
                    'paid' => true
                ];
                $pusher->trigger('NotifyWaiter', BaseConstant::WAITER_CHANNEL, $info);
                break;
        }

    }
}

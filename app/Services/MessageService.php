<?php

namespace App\Services;

use App\Constants\BaseConstant;
use App\Constants\FoodConstant;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;

class MessageService
{
    /**
     * @var array
     */
    private array $options;

    /**
     *
     */
    public function __construct()
    {
        $this->options = [
            'cluster' => 'ap1',
            'encrypted' => true
        ];
    }

    /**
     * @throws \Pusher\PusherException
     * @throws \Pusher\ApiErrorException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendNotify($tableName, $data, $type)
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
        switch ($type) {
            case BaseConstant::SEND_CHEF:
                foreach ($data as $food) {
                    switch ($food[FoodConstant::CATEGORY_FIELD]) {
                        case BaseConstant::FOOD_SALAD:
                            $pusher->trigger('NotifyChefSalad', BaseConstant::CHEF_SALAD_CHANNEL, $tableName);
                            break;
                        case BaseConstant::FOOD_GRILL:
                            $pusher->trigger('NotifyChefGrill', BaseConstant::CHEF_GRILL_CHANNEL, $tableName);
                            break;
                        case BaseConstant::FOOD_STEAM:
                            $pusher->trigger('NotifyChefSteam', BaseConstant::CHEF_STEAM_CHANNEL, $tableName);
                            break;
                        case BaseConstant::FOOD_DRYING:
                            $pusher->trigger('NotifyChefDrying', BaseConstant::CHEF_DRYING_CHANNEL, $tableName);
                            break;
                        case BaseConstant::FOOD_DRINK:
                            $pusher->trigger('NotifyChefDrink', BaseConstant::CHEF_DRINK_CHANNEL, $tableName);
                            break;
                    }
                }
                break;

            case BaseConstant::SEND_WAITER:
                $pusher->trigger('NotifyWaiter', BaseConstant::WAITER_CHANNEL, $tableName);
                break;

            case BaseConstant::SEND_CASHIER:
                $pusher->trigger('NotifyCashier', BaseConstant::CASHIER_CHANNEL, $tableName);
                break;
        }

    }
}

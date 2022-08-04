<?php

namespace App\Http\Controllers\Chef;

use App\Constants\BaseConstant;
use App\Http\Controllers\Controller;
use App\Repositories\FoodOrderRepository;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;

class ChefController extends Controller
{
    /**
     * @var OrderRepository
     */
    private OrderRepository $orderRepository;

    /**
     * @var FoodOrderRepository
     */
    private FoodOrderRepository $foodOrderRepository;

    /**
     * @param OrderRepository $orderRepository
     * @param FoodOrderRepository $foodOrderRepository
     */
    public function __construct(
        OrderRepository $orderRepository,
        FoodOrderRepository $foodOrderRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->foodOrderRepository = $foodOrderRepository;
    }

    /**
     * @param $category
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function dashboard($category)
    {
        $event = match ($category) {
            BaseConstant::FOOD_SALAD => 'NotifyChefSalad',
            BaseConstant::FOOD_STEAM => 'NotifyChefSteam',
            BaseConstant::FOOD_GRILL => 'NotifyChefGrill',
            BaseConstant::FOOD_DRYING => 'NotifyChefDrying',
            default => 'NotifyChefDrink',
        };
        $channel = match ($category) {
            BaseConstant::FOOD_SALAD => BaseConstant::CHEF_SALAD_CHANNEL,
            BaseConstant::FOOD_STEAM => BaseConstant::CHEF_STEAM_CHANNEL,
            BaseConstant::FOOD_GRILL => BaseConstant::CHEF_GRILL_CHANNEL,
            BaseConstant::FOOD_DRYING => BaseConstant::CHEF_DRYING_CHANNEL,
            default => BaseConstant::CHEF_DRINK_CHANNEL,
        };
        $listFoods = $this->orderRepository->getFoodsByCategory($category);

        return view('chef.dashboard', [
            'category' => $category,
            'channel' => $channel,
            'event' => $event,
            'listFoods' => $listFoods
        ]);
    }

    public function cancelCooking(Request $request)
    {
        $request = $request->all();
        $result = $this->foodOrderRepository->cancelCooking($request);
        if ($result) {
            return response()->json([
                'code' => '222',
                'message' => 'Đã hủy món',
            ]);
        }

        return response()->json([
            'code' => '333',
            'message' => 'Lỗi hệ thông, vui lòng thử lại'
        ]);
    }
}

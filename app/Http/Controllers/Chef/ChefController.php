<?php

namespace App\Http\Controllers\Chef;

use App\Constants\BaseConstant;
use App\Http\Controllers\Controller;
use App\Repositories\OrderRepository;

class ChefController extends Controller
{
    /**
     * @var OrderRepository
     */
    private OrderRepository $orderRepository;

    public function __construct(
        OrderRepository $orderRepository
    ) {
        $this->orderRepository = $orderRepository;
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
}

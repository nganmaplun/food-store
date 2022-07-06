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

        $listFoods = $this->orderRepository->getFoodsByCategory($category);

        return view('chef.dashboard', [
            'category' => $category,
            'channel' => BaseConstant::CHEF_SALAD_CHANNEL,
            'event' => $event,
            'listFoods' => $listFoods
        ]);
    }
}

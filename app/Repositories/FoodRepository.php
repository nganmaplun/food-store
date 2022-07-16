<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface FoodRepository.
 *
 * @package namespace App\Repositories;
 */
interface FoodRepository extends RepositoryInterface
{
    /**
     * @param null $today
     * @return mixed
     */
    public function getListFoods($today = null, $foodName = null): mixed;

    /**
     * @return mixed
     */
    public function getListFoodsMenu($foodName = null);

    /**
     * @return mixed
     */
    public function listAllFoodName();

    /**
     * @param array $request
     * @return mixed
     */
    public function getFoodIdByName(array $request);
}

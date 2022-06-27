<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface FoodOrderRepository.
 *
 * @package namespace App\Repositories;
 */
interface FoodOrderRepository extends RepositoryInterface
{
    /**
     * @param array $request
     * @return mixed
     */
    public function addFoodToOrder(array $request);
}

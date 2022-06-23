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
    public function getListFoods($today = null): mixed;
}

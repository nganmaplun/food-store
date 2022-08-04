<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface FoodDayRepository.
 *
 * @package namespace App\Repositories;
 */
interface FoodDayRepository extends RepositoryInterface
{
    /**
     * @param $request
     * @return mixed
     */
    public function setFoodNumberToday($request): mixed;

    /**
     * @param $id
     * @return mixed
     */
    public function checkFoodRemain($id, $today);

    /**
     * @param array $request
     * @return mixed
     */
    public function deleteSetFoodToday(array $request);
}

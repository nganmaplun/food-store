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

    /**
     * @param $id
     * @return mixed
     */
    public function removeOrderFood($id);

    /**
     * @param $orderId
     * @return mixed
     */
    public function updateToOldOrder($orderId);

    /**
     * @param $orderId
     * @param $foodId
     * @return mixed
     */
    public function updateToCompletedFood($orderId, $foodId);

    /**
     * @param mixed $index
     * @return mixed
     */
    public function changeToTableStatus($index);

    /**
     * @param array $lstId
     * @param string $today
     * @return mixed
     */
    public function getListCountOrder(array $lstId, string $today);

    public function getAllFinishFood($today);
}

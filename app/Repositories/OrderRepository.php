<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface OrderRepository.
 *
 * @package namespace App\Repositories;
 */
interface OrderRepository extends RepositoryInterface
{
    /**
     * @param array $request
     * @param $userId
     * @return mixed
     */
    public function createOrder(array $request, $userId);

    /**
     * @param $orderId
     * @return mixed
     */
    public function getListFoodsInOrder($orderId, $foodId = null);

    /**
     * @param $category
     * @return mixed
     */
    public function getFoodsByCategory($category);

    /**
     * @param $orderId
     * @param string $type
     */
    public function updateOrderStatus($orderId, string $type);

    /**
     * @return mixed
     */
    public function getToPaidOrder();

    /**
     * @param $orderId
     * @return mixed
     */
    public function getDetailOrder($orderId);

    /**
     * @param $orderId
     * @param mixed $totalPrice
     * @return mixed
     */
    public function updateFinalOrder($orderId, $totalPrice);

    /**
     * @param $orderId
     * @return mixed
     */
    public function detailOrder($orderId);
}

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
    public function getListFoodsInOrder($orderId);
}

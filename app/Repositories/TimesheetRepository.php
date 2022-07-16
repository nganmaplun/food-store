<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface TimesheetRepository.
 *
 * @package namespace App\Repositories;
 */
interface TimesheetRepository extends RepositoryInterface
{
    /**
     * @param $user
     * @return false|void
     */
    public function createCheckin($user);

    /**
     * @param $user
     * @return false|void
     */
    public function createCheckout($user);

    /**
     * @param $today
     * @param $request
     * @return mixed
     */
    public function getListEmployee($today, $request): mixed;

    /**
     * @param $index
     * @return mixed
     */
    public function approveTimesheet($index, $type): mixed;
}

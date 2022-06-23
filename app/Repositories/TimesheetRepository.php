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
     * @return mixed
     */
    public function createCheckin($user): mixed;

    /**
     * @param $user
     * @return mixed
     */
    public function createCheckout($user): mixed;

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
    public function approveTimesheet($index): mixed;
}

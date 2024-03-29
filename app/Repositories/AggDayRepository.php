<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface FoodDayRepository.
 *
 * @package namespace App\Repositories;
 */
interface AggDayRepository extends RepositoryInterface
{
    /**
     * @return mixed
     */
    public function getAll();

    /**
     * @param array $aggBy
     * @return mixed
     */
    public function customReport(array $aggBy);
}

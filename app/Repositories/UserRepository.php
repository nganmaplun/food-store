<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface UserRepository.
 *
 * @package namespace App\Repositories;
 */
interface UserRepository extends RepositoryInterface
{
    /**
     * @param string $today
     * @param array $request
     * @return mixed
     */
    public function getListEmployee(string $today, array $request);

    /**
     * @param $id
     * @param array $request
     * @return mixed
     */
    public function changePassword($id, array $request);
}

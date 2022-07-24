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

    /**
     * @return mixed
     */
    public function listAllEmployee();

    /**
     * @param $id
     * @return mixed
     */
    public function getDetailEmployee($id);

    /**
     * @param array $request
     * @param $id
     * @return mixed
     */
    public function updateEmployee(array $request, $id);

    /**
     * @param array $request
     * @return mixed
     */
    public function createEmployee(array $request);

    /**
     * @param $id
     * @return mixed
     */
    public function deleteEmployee($id);
}

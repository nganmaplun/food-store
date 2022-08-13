<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface TableRepository.
 *
 * @package namespace App\Repositories;
 */
interface TableRepository extends RepositoryInterface
{
    /**
     * @param null $floor
     * @return mixed
     */
    public function getListTables($floor = null): mixed;

    /**
     * @return mixed
     */
    public function getListFloors(): mixed;

    /**
     * @param $id
     * @return mixed
     */
    public function changeTableStatus($id): mixed;

    /**
     * @param $id
     * @return mixed
     */
    public function checkTableStatus($id): mixed;

    /**
     * @param $id
     * @return mixed
     */
    public function getTableName($id): mixed;

    /**
     * @return mixed
     */
    public function getListTable();

    /**
     * @param $tableId
     * @return mixed
     */
    public function updateTableStatus($tableId);

    /**
     * @return mixed
     */
    public function resetTable();

    /**
     * @param mixed $tableId
     * @return mixed
     */
    public function updateTableStatusToWaiter(mixed $tableId);
}

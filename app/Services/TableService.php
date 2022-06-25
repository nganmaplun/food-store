<?php

namespace App\Services;

use App\Repositories\TableRepository;

class TableService
{
    /**
     * @var TableRepository
     */
    private TableRepository $tableRepository;

    /**
     * @param TableRepository $tableRepository
     */
    public function __construct(
        TableRepository $tableRepository
    ){
        $this->tableRepository = $tableRepository;
    }

    /**
     * @param $floor
     * @return mixed
     */
    public function listTables($floor = null): mixed
    {
        return $this->tableRepository->getListTables($floor);
    }
}

<?php

namespace App\Repositories;

use App\Constants\BaseConstant;
use App\Constants\TableConstant;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TableRepository;
use App\Entities\Table;
use App\Validators\TableValidator;

/**
 * Class TableRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TableRepositoryEloquent extends BaseRepository implements TableRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Table::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @param $floor
     * @return mixed
     */
    public function getListTables($floor = null): mixed
    {
        if ($floor && $floor !== BaseConstant::ALL_TABLE) {
            return $this->where(TableConstant::FLOOR_FIELD, $floor)->paginate(BaseConstant::DEFAULT_LIMIT);
        }

        return $this->paginate(BaseConstant::DEFAULT_LIMIT);
    }

    /**
     * @return mixed
     */
    public function getListFloors(): mixed
    {
        return $this->select(TableConstant::FLOOR_FIELD)
            ->distinct()->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function changeTableStatus($id): mixed
    {
        try {
            $table = $this->findByField(BaseConstant::ID_FIELD, $id)->first();
            if ($table[BaseConstant::STATUS_FIELD] == BaseConstant::TABLE_AVAILABLE) {
                return $this->update([
                    BaseConstant::STATUS_FIELD => BaseConstant::TABLE_ORDERED
                ], $table[BaseConstant::ID_FIELD]);
            }
            if ($table[BaseConstant::STATUS_FIELD] == BaseConstant::TABLE_ORDERED) {
                return $this->update([
                    BaseConstant::STATUS_FIELD => BaseConstant::TABLE_PAID
                ], $table[BaseConstant::ID_FIELD]);
            }
            if ($table[BaseConstant::STATUS_FIELD] == BaseConstant::TABLE_PAID) {
                return $this->update([
                    BaseConstant::STATUS_FIELD => BaseConstant::TABLE_AVAILABLE
                ], $table[BaseConstant::ID_FIELD]);
            }
        } catch (\Exception $e) {
            Log::channel('customError')->error($e->getMessage());
            return false;
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function checkTableStatus($id): mixed
    {
        $tableStatus = $this->select(BaseConstant::STATUS_FIELD)
            ->where(BaseConstant::ID_FIELD, $id)
            ->first();

        return $tableStatus[BaseConstant::STATUS_FIELD];
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getTableName($id): mixed
    {
        return $this->select([BaseConstant::ID_FIELD, TableConstant::NAME_FIELD])
            ->where(BaseConstant::ID_FIELD, $id)
            ->first();
    }

    /**
     * @return mixed
     */
    public function getListTable()
    {
        $results = $this->select([BaseConstant::ID_FIELD, TableConstant::NAME_FIELD])
            ->get();
        $lstTables = [];
        foreach ($results as $result) {
            $lstTables[$result[BaseConstant::ID_FIELD]] = $result[TableConstant::NAME_FIELD];
        }
        return $lstTables;
    }
}

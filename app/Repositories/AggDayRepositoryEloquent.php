<?php

namespace App\Repositories;

use App\Constants\AggDayConstant;
use App\Constants\BaseConstant;
use App\Entities\AggDay;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Validators\FoodDayValidator;

/**
 * Class FoodDayRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class AggDayRepositoryEloquent extends BaseRepository implements AggDayRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return AggDay::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->all();
    }

    /**
     * @param array $aggBy
     * @return mixed
     */
    public function customReport(array $aggBy)
    {
        $result = $this;
        if (isset($aggBy['from']) && !empty($aggBy['from'])) {
            $result = $result->where(AggDayConstant::ORDER_DATE_FIELD, BaseConstant::GREATER_AND_EQUAL_THAN, $aggBy['from']);
        }
        if (isset($aggBy['to']) && !empty($aggBy['to'])) {
            $result = $result->where(AggDayConstant::ORDER_DATE_FIELD, BaseConstant::LESS_AND_EQUAL_THAN, $aggBy['to']);
        }

        return $result->get();
    }
}

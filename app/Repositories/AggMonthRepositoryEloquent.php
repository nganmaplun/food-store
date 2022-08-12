<?php

namespace App\Repositories;

use App\Entities\AggMonth;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Validators\FoodDayValidator;

/**
 * Class FoodDayRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class AggMonthRepositoryEloquent extends BaseRepository implements AggMonthRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return AggMonth::class;
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
}

<?php

namespace App\Repositories;

use App\Entities\AggYear;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Validators\FoodDayValidator;

/**
 * Class FoodDayRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class AggYearRepositoryEloquent extends BaseRepository implements AggYearRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return AggYear::class;
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

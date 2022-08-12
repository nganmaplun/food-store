<?php

namespace App\Repositories;

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
}

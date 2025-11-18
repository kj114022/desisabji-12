<?php

namespace App\Repositories\Eloquent;

use App\Models\State;
use App\Repositories\StateRepositoryInterface;

/**
 * Class StateRepository
 * @package App\Repositories
 * @version March 25, 2020, 9:48 am UTC
 *
 * @method State findWithoutFail($id, $columns = ['*'])
 * @method State find($id, $columns = ['*'])
 * @method State first($columns = ['*'])
*/
class StateRepository extends BaseRepository implements StateRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'country_code'
    ];
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(State $model)
    {
        $this->model = $model;
    }
    /**
     * Configure the Model
     **/
    public function model()
    {
        return State::class;
    }
}

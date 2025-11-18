<?php

namespace App\Repositories\Eloquent;

use App\Models\City;
use App\Repositories\CityRepositoryInterface;

/**
 * Class CityRepository
 * @package App\Repositories
 * @version March 25, 2020, 9:48 am UTC
 *
 * @method City findWithoutFail($id, $columns = ['*'])
 * @method City find($id, $columns = ['*'])
 * @method City first($columns = ['*'])
*/
class CityRepository extends BaseRepository implements CityRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'state',
        'status'
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
    public function __construct(City $model)
    {
        $this->model = $model;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return City::class;
    }
}

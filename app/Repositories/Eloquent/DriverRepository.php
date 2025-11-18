<?php

namespace App\Repositories\Eloquent;

use App\Models\Driver;
use App\Repositories\DriverRepositoryInterface;
/**
 * Class DriverRepository
 * @package App\Repositories
 * @version March 25, 2020, 9:47 am UTC
 *
 * @method Driver findWithoutFail($id, $columns = ['*'])
 * @method Driver find($id, $columns = ['*'])
 * @method Driver first($columns = ['*'])
*/
class DriverRepository extends BaseRepository implements DriverRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'delivery_fee',
        'total_orders',
        'earning',
        'available'
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
    public function __construct(Driver $model)
    {
        $this->model = $model;
    }
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Driver::class;
    }
}

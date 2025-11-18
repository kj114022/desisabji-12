<?php

namespace App\Repositories\Eloquent;

use App\Models\DriversPayout;
use App\Repositories\DriversPayoutRepositoryInterface;

/**
 * Class DriversPayoutRepository
 * @package App\Repositories
 * @version March 25, 2020, 9:48 am UTC
 *
 * @method DriversPayout findWithoutFail($id, $columns = ['*'])
 * @method DriversPayout find($id, $columns = ['*'])
 * @method DriversPayout first($columns = ['*'])
*/
class DriversPayoutRepository extends BaseRepository implements DriversPayoutRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'method',
        'amount',
        'paid_date',
        'note'
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
    public function __construct(DriversPayout $model)
    {
        $this->model = $model;
    }
    /**
     * Configure the Model
     **/
    public function model()
    {
        return DriversPayout::class;
    }
}

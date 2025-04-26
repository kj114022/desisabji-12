<?php

namespace App\Repositories;

use App\Models\DriversPayout;


/**
 * Class DriversPayoutRepository
 * @package App\Repositories
 * @version March 25, 2020, 9:48 am UTC
 *
 * @method DriversPayout findWithoutFail($id, $columns = ['*'])
 * @method DriversPayout find($id, $columns = ['*'])
 * @method DriversPayout first($columns = ['*'])
*/
class DriversPayoutRepository extends BaseRepository
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
     * Get searchable fields
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return DriversPayout::class;
    }
}

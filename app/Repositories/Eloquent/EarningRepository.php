<?php

namespace App\Repositories\Eloquent;

use App\Models\Earning;
use App\Repositories\EarningRepositoryInterface;

/**
 * Class EarningRepository
 * @package App\Repositories
 * @version March 25, 2020, 9:48 am UTC
 *
 * @method Earning findWithoutFail($id, $columns = ['*'])
 * @method Earning find($id, $columns = ['*'])
 * @method Earning first($columns = ['*'])
*/
class EarningRepository extends BaseRepository implements EarningRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'market_id',
        'total_orders',
        'total_earning',
        'admin_earning',
        'market_earning',
        'delivery_fee',
        'tax'
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
    public function __construct(Earning $model)
    {
        $this->model = $model;
    }
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Earning::class;
    }
}

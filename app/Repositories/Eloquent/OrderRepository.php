<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Repositories\OrderRepositoryInterface;

/**
 * Class OrderRepository
 * @package App\Repositories
 * @version August 31, 2019, 11:11 am UTC
 *
 * @method Order findWithoutFail($id, $columns = ['*'])
 * @method Order find($id, $columns = ['*'])
 * @method Order first($columns = ['*'])
*/
class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'order_status_id',
        'tax',
        'hint',
        'payment_id',
        'delivery_address_id',
        'active',
        'driver_id',
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
    public function __construct(Order $model)
    {
        $this->model = $model;
    }
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Order::class;
    }
}

<?php

namespace App\Repositories\Eloquent;

use App\Models\OrderStatus;
use App\Repositories\OrderStatusRepositoryInterface;
/**
 * Class OrderStatusRepository
 * @package App\Repositories
 * @version August 29, 2019, 9:38 pm UTC
 *
 * @method OrderStatus findWithoutFail($id, $columns = ['*'])
 * @method OrderStatus find($id, $columns = ['*'])
 * @method OrderStatus first($columns = ['*'])
*/
class OrderStatusRepository extends BaseRepository implements OrderStatusRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
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
    public function __construct(OrderStatus $model)
    {
        $this->model = $model;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return OrderStatus::class;
    }
}

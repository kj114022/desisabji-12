<?php

namespace App\Repositories\Eloquent;

use App\Models\ProductOrder;
use App\Repositories\ProductOrderRepositoryInterface;

/**
 * Class ProductOrderRepository
 * @package App\Repositories
 * @version August 31, 2019, 11:18 am UTC
 *
 * @method ProductOrder findWithoutFail($id, $columns = ['*'])
 * @method ProductOrder find($id, $columns = ['*'])
 * @method ProductOrder first($columns = ['*'])
*/
class ProductOrderRepository extends BaseRepository implements ProductOrderRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'price',
        'quantity',
        'product_id',
        'order_id'
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
    public function __construct(ProductOrder $model)
    {
        $this->model = $model;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ProductOrder::class;
    }
}

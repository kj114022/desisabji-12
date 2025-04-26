<?php

namespace App\Repositories;

use App\Models\ProductOrder;


/**
 * Class ProductOrderRepository
 * @package App\Repositories
 * @version August 31, 2019, 11:18 am UTC
 *
 * @method ProductOrder findWithoutFail($id, $columns = ['*'])
 * @method ProductOrder find($id, $columns = ['*'])
 * @method ProductOrder first($columns = ['*'])
*/
class ProductOrderRepository extends BaseRepository
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
        return ProductOrder::class;
    }
}

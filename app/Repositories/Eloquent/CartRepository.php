<?php

namespace App\Repositories\Eloquent;

use App\Models\Cart;
use App\Repositories\CartRepositoryInterface;

/**
 * Class CartRepository
 * @package App\Repositories
 * @version September 4, 2019, 3:38 pm UTC
 *
 * @method Cart findWithoutFail($id, $columns = ['*'])
 * @method Cart find($id, $columns = ['*'])
 * @method Cart first($columns = ['*'])
*/
class CartRepository extends BaseRepository implements CartRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'product_id',
        'user_id',
        'quantity',
        'city_id',
        'product_city_id',
        'row_id',
        'price',
        'product_name',
        'ip_address'
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
    public function __construct(Cart $model)
    {
        $this->model = $model;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Cart::class;
    }
}

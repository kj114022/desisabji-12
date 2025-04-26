<?php

namespace App\Repositories;

use App\Models\Cart;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class CartRepository
 * @package App\Repositories
 * @version September 4, 2019, 3:38 pm UTC
 *
 * @method Cart findWithoutFail($id, $columns = ['*'])
 * @method Cart find($id, $columns = ['*'])
 * @method Cart first($columns = ['*'])
*/
class CartRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'product_id',
        'city_id',
        'user_id',
        'quantity',
        'row_id',
        'price',
        'product_name',
        'ip_address'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Cart::class;
    }
}

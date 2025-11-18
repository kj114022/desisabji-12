<?php

namespace App\Repositories\Eloquent;

use App\Models\Coupon;
use App\Repositories\CouponRepositoryInterface;

/**
 * Class CouponRepository
 * @package App\Repositories
 * @version August 23, 2020, 6:10 pm UTC
 *
 * @method Coupon findWithoutFail($id, $columns = ['*'])
 * @method Coupon find($id, $columns = ['*'])
 * @method Coupon first($columns = ['*'])
*/
class CouponRepository extends BaseRepository implements CouponRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'code',
        'discount',
        'discount_type',
        'description',
        'product_id',
        'market_id',
        'category_id',
        'expires_at',
        'enabled',
        'active'
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
    public function __construct(Coupon $model)
    {
        $this->model = $model;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Coupon::class;
    }
}

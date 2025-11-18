<?php

namespace App\Repositories\Eloquent;

use App\Models\CouponRedeem;
use App\Repositories\CouponRedeemRepositoryInterface;

/**
 * Class CouponRedeemRedeemRepository
 * @package App\Repositories
 * @version August 23, 2020, 6:10 pm UTC
 *
 * @method CouponRedeem findWithoutFail($id, $columns = ['*'])
 * @method CouponRedeem find($id, $columns = ['*'])
 * @method CouponRedeem first($columns = ['*'])
*/
class CouponRedeemRepository extends BaseRepository implements CouponRedeemRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'coupon_code',
        'order_id',
        'user_id',
        'redeem_amount',
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
    public function __construct(CouponRedeem $model)
    {
        $this->model = $model;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CouponRedeem::class;
    }
}

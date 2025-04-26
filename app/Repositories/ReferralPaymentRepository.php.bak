<?php

namespace App\Repositories;

use App\Models\ReferralPayment;


/**
 * Class ReferralPaymentRepository
 * @package App\Repositories
 *
 * @method ReferralPayment findWithoutFail($id, $columns = ['*'])
 * @method ReferralPayment find($id, $columns = ['*'])
 * @method ReferralPayment first($columns = ['*'])
*/
class ReferralPaymentRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'order_id',
        'referral_id',
        'payment_date',
        'payment_amt'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ReferralPayment::class;
    }
}

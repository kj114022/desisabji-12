<?php

namespace App\Repositories;

use App\Models\Referral;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class ReferralRepository
 * @package App\Repositories
 *
 * @method Referral findWithoutFail($id, $columns = ['*'])
 * @method Referral find($id, $columns = ['*'])
 * @method Referral first($columns = ['*'])
*/
class ReferralRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'customer_no',
        'referral_no',
        'referral_type',
        'referral_amt',
        'payment_status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Referral::class;
    }
}

<?php

namespace App\Repositories;

use App\Models\Referral;


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
        return Referral::class;
    }
}

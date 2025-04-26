<?php

namespace App\Repositories;

use App\Models\WalletPayment;


/**
 * Class WalletPaymentRepository
 * @package App\Repositories
 *
 * @method WalletPayment findWithoutFail($id, $columns = ['*'])
 * @method WalletPayment find($id, $columns = ['*'])
 * @method WalletPayment first($columns = ['*'])
*/
class WalletPaymentRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'order_id',
        'wallet_id',
        'payment_date',
        'payment_amt'
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
        return WalletPayment::class;
    }
}

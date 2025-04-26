<?php

namespace App\Repositories;

use App\Models\Earning;


/**
 * Class EarningRepository
 * @package App\Repositories
 * @version March 25, 2020, 9:48 am UTC
 *
 * @method Earning findWithoutFail($id, $columns = ['*'])
 * @method Earning find($id, $columns = ['*'])
 * @method Earning first($columns = ['*'])
*/
class EarningRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'market_id',
        'total_orders',
        'total_earning',
        'admin_earning',
        'market_earning',
        'delivery_fee',
        'tax'
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
        return Earning::class;
    }
}

<?php

namespace App\Repositories;

use App\Models\MarketReview;


/**
 * Class MarketReviewRepository
 * @package App\Repositories
 * @version August 29, 2019, 9:39 pm UTC
 *
 * @method MarketReview findWithoutFail($id, $columns = ['*'])
 * @method MarketReview find($id, $columns = ['*'])
 * @method MarketReview first($columns = ['*'])
*/
class MarketReviewRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'review',
        'rate',
        'user_id',
        'market_id'
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
        return MarketReview::class;
    }
}

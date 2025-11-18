<?php

namespace App\Repositories\Eloquent;

use App\Models\MarketReview;
use App\Repositories\MarketReviewRepositoryInterface;
/**
 * Class MarketReviewRepository
 * @package App\Repositories
 * @version August 29, 2019, 9:39 pm UTC
 *
 * @method MarketReview findWithoutFail($id, $columns = ['*'])
 * @method MarketReview find($id, $columns = ['*'])
 * @method MarketReview first($columns = ['*'])
*/
class MarketReviewRepository extends BaseRepository implements MarketReviewRepositoryInterface
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
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(MarketReview $model)
    {
        $this->model = $model;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return MarketReview::class;
    }
}

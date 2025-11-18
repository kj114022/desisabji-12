<?php

namespace App\Repositories\Eloquent;

use App\Models\ProductReview;
use App\Repositories\ProductReviewRepositoryInterface;

/**
 * Class ProductReviewRepository
 * @package App\Repositories
 * @version August 29, 2019, 9:38 pm UTC
 *
 * @method ProductReview findWithoutFail($id, $columns = ['*'])
 * @method ProductReview find($id, $columns = ['*'])
 * @method ProductReview first($columns = ['*'])
*/
class ProductReviewRepository extends BaseRepository implements ProductReviewRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'review',
        'rate',
        'user_id',
        'product_id'
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
    public function __construct(ProductReview $model)
    {
        $this->model = $model;
    }
    /**
     * Configure the Model
     **/
    public function model()
    {
        return ProductReview::class;
    }
}

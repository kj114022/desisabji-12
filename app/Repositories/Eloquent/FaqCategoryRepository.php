<?php

namespace App\Repositories\Eloquent;

use App\Models\FaqCategory;
use App\Repositories\FaqCategoryRepositoryInterface;
/**
 * Class FaqCategoryRepository
 * @package App\Repositories
 * @version August 29, 2019, 9:38 pm UTC
 *
 * @method FaqCategory findWithoutFail($id, $columns = ['*'])
 * @method FaqCategory find($id, $columns = ['*'])
 * @method FaqCategory first($columns = ['*'])
*/
class FaqCategoryRepository extends BaseRepository implements FaqCategoryRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'
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
    public function __construct(FaqCategory $model)
    {
        $this->model = $model;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return FaqCategory::class;
    }
}

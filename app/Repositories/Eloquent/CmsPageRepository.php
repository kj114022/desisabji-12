<?php

namespace App\Repositories\Eloquent;

use App\Models\CmsPage;
use App\Repositories\CmsPageRepositoryInterface;

/**
 * Class CmsPageRepository
 * @package App\Repositories
 * @version April 11, 2020, 1:57 pm UTC
 *
 * @method CmsPage findWithoutFail($id, $columns = ['*'])
 * @method CmsPage find($id, $columns = ['*'])
 * @method CmsPage first($columns = ['*'])
*/
class CmsPageRepository extends BaseRepository implements CmsPageRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'description',
        'page_slug',
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
    public function __construct(CmsPage $model)
    {
        $this->model = $model;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CmsPage::class;
    }
}

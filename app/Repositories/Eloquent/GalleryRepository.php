<?php

namespace App\Repositories\Eloquent;

use App\Models\Gallery;
use App\Repositories\GalleryRepositoryInterface;
/**
 * Class GalleryRepository
 * @package App\Repositories
 * @version August 29, 2019, 9:38 pm UTC
 *
 * @method Gallery findWithoutFail($id, $columns = ['*'])
 * @method Gallery find($id, $columns = ['*'])
 * @method Gallery first($columns = ['*'])
*/
class GalleryRepository extends BaseRepository implements GalleryRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'description',
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
    public function __construct(Gallery $model)
    {
        $this->model = $model;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Gallery::class;
    }
}

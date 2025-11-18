<?php

namespace App\Repositories\Eloquent;

use App\Models\Favorite;
use App\Repositories\FavoriteRepositoryInterface;
/**
 * Class FavoriteRepository
 * @package App\Repositories
 * @version August 30, 2019, 3:29 pm UTC
 *
 * @method Favorite findWithoutFail($id, $columns = ['*'])
 * @method Favorite find($id, $columns = ['*'])
 * @method Favorite first($columns = ['*'])
*/
class FavoriteRepository extends BaseRepository implements FavoriteRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'product_id',
        'user_id'
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
    public function __construct(Favorite $model)
    {
        $this->model = $model;
    }


    /**
     * Configure the Model
     **/
    public function model()
    {
        return Favorite::class;
    }
}

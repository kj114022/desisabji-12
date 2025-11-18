<?php

namespace App\Repositories\Eloquent;

use App\Models\Field;
use App\Repositories\FieldRepositoryInterface;
/**
 * Class FieldRepository
 * @package App\Repositories
 * @version April 11, 2020, 1:57 pm UTC
 *
 * @method Field findWithoutFail($id, $columns = ['*'])
 * @method Field find($id, $columns = ['*'])
 * @method Field first($columns = ['*'])
*/
class FieldRepository extends BaseRepository implements FieldRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description'
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
    public function __construct(Field $model)
    {
        $this->model = $model;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Field::class;
    }
}

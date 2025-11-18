<?php

namespace App\Repositories\Eloquent;

use App\Models\Option;
use App\Repositories\OptionRepositoryInterface;
/**
 * Class OptionRepository
 * @package App\Repositories
 * @version April 6, 2020, 10:56 am UTC
 *
 * @method Option findWithoutFail($id, $columns = ['*'])
 * @method Option find($id, $columns = ['*'])
 * @method Option first($columns = ['*'])
*/
class OptionRepository extends BaseRepository implements OptionRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'price',
        'product_id',
        'option_group_id'
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
    public function __construct(Option $model)
    {
        $this->model = $model;
    }
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Option::class;
    }
}

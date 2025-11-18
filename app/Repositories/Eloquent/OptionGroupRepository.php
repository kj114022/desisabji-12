<?php

namespace App\Repositories\Eloquent;

use App\Models\OptionGroup;
use App\Repositories\OptionGroupRepositoryInterface;

/**
 * Class OptionGroupRepository
 * @package App\Repositories
 * @version April 6, 2020, 10:47 am UTC
 *
 * @method OptionGroup findWithoutFail($id, $columns = ['*'])
 * @method OptionGroup find($id, $columns = ['*'])
 * @method OptionGroup first($columns = ['*'])
*/
class OptionGroupRepository extends BaseRepository implements OptionGroupRepositoryInterface
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
    public function __construct(Option $model)
    {
        $this->model = $model;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return OptionGroup::class;
    }
}

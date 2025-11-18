<?php

namespace App\Repositories\Eloquent;

use App\Models\CustomField;
use App\Repositories\CustomFieldRepositoryInterface;

/**
 * Class CustomFieldRepository
 * @package App\Repositories
 * @version July 24, 2018, 9:13 pm UTC
 *
 * @method CustomField findWithoutFail($id, $columns = ['*'])
 * @method CustomField find($id, $columns = ['*'])
 * @method CustomField first($columns = ['*'])
*/
class CustomFieldRepository extends BaseRepository implements CustomFieldRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'type',
        'disabled',
        'required',
        'in_table',
        'bootstrap_column',
        'order',
        'custom_field_model'
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
    public function __construct(CustomField $model)
    {
        $this->model = $model;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CustomField::class;
    }
}

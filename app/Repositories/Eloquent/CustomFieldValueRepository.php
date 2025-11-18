<?php

namespace App\Repositories\Eloquent;

use App\Models\CustomFieldValue;
use App\Repositories\CustomFieldValueRepositoryInterface;

/**
 * Class CustomFieldValueRepository
 * @package App\Repositories
 * @version July 24, 2018, 9:13 pm UTC
 *
 * @method CustomFieldValue findWithoutFail($id, $columns = ['*'])
 * @method CustomFieldValue find($id, $columns = ['*'])
 * @method CustomFieldValue first($columns = ['*'])
*/
class CustomFieldValueRepository extends BaseRepository implements CustomFieldValueRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'custom_field_id',
        'customizable_type'
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
    public function __construct(CustomFieldValue $model)
    {
        $this->model = $model;
    }
    /**
     * Configure the Model
     **/
    public function model()
    {
        return CustomFieldValue::class;
    }
}

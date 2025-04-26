<?php

namespace App\Repositories;

use App\Models\OptionGroup;


/**
 * Class OptionGroupRepository
 * @package App\Repositories
 * @version April 6, 2020, 10:47 am UTC
 *
 * @method OptionGroup findWithoutFail($id, $columns = ['*'])
 * @method OptionGroup find($id, $columns = ['*'])
 * @method OptionGroup first($columns = ['*'])
*/
class OptionGroupRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'
    ];

    /**
     * Get searchable fields
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return OptionGroup::class;
    }
}

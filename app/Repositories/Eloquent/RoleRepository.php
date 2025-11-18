<?php

namespace App\Repositories\Eloquent;

use Spatie\Permission\Models\Role;
use App\Repositories\RoleRepositoryInterface;

/**
 * Class RoleRepository
 * @package App\Repositories
 * @version May 29, 2018, 5:23 pm UTC
 *
 * @method Role findWithoutFail($id, $columns = ['*'])
 * @method Role find($id, $columns = ['*'])
 * @method Role first($columns = ['*'])
*/
class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'guard_name'
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
    public function __construct(Role $model)
    {
        $this->model = $model;
    }


    /**
     * Configure the Model
     **/
    public function model()
    {
        return Role::class;
    }
}

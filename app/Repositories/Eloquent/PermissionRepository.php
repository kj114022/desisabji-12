<?php

namespace App\Repositories\Eloquent;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Repositories\PermissionRepositoryInterface;

/**
 * Class PermissionRepository
 * @package App\Repositories
 * @version May 29, 2018, 5:54 am UTC
 *
 * @method Permission findWithoutFail($id, $columns = ['*'])
 * @method Permission find($id, $columns = ['*'])
 * @method Permission first($columns = ['*'])
 */
class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
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
    public function __construct(Permission $model)
    {
        $this->model = $model;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Permission::class;
    }

    public function givePermissionToRole(array $input){
        $role = Role::findOrfail($input['roleId']);
        $role->givePermissionTo($input['permission']);
    }

    public function revokePermissionToRole(array $input){
        $role = Role::findOrfail($input['roleId']);
        $role->revokePermissionTo($input['permission']);
    }

    public function roleHasPermission(array $input){
        $role = Role::findOrfail($input['roleId']);
        if($role->hasPermissionTo($input['permission'])){
            return ['result'=>1];
        }
        return ['result'=>0];
    }
}

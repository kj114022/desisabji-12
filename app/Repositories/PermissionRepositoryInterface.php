<?php

namespace App\Repositories;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


/**
 * Class PermissionRepository
 * @package App\Repositories
 * @version May 29, 2018, 5:54 am UTC
 *
 * @method Permission findWithoutFail($id, $columns = ['*'])
 * @method Permission find($id, $columns = ['*'])
 * @method Permission first($columns = ['*'])
 */
interface PermissionRepositoryInterface extends BaseRepositoryInterface
{
    public function givePermissionToRole(array $input);
    public function revokePermissionToRole(array $input);
    public function roleHasPermission(array $input);
}

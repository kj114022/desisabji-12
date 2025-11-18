<?php

namespace App\Repositories;

use App\Models\Cart;
/**
 * Class CartRepository
 * @package App\Repositories
 * @version September 4, 2019, 3:38 pm UTC
 *
 * @method Cart findWithoutFail($id, $columns = ['*'])
 * @method Cart find($id, $columns = ['*'])
 * @method Cart first($columns = ['*'])
*/
interface CartRepositoryInterface extends BaseRepositoryInterface 
{
   //public function getCartByUserId($userId);
}

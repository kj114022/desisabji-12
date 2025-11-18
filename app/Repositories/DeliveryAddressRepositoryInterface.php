<?php

namespace App\Repositories;

use App\Models\DeliveryAddress;
use Illuminate\Support\Facades\Log;
/**
 * Class DeliveryAddressRepository
 * @package App\Repositories
 * @version December 6, 2019, 1:57 pm UTC
 *
 * @method DeliveryAddress findWithoutFail($id, $columns = ['*'])
 * @method DeliveryAddress find($id, $columns = ['*'])
 * @method DeliveryAddress first($columns = ['*'])
*/
interface DeliveryAddressRepositoryInterface extends BaseRepositoryInterface
{
   public function initIsDefault($userId);
   public function getAddressesByUserId($userId = '');
}

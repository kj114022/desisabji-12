<?php

namespace App\Repositories\Eloquent;

use App\Models\DeliveryAddress;
use App\Repositories\DeliveryAddressRepositoryInterface;
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
class DeliveryAddressRepository extends BaseRepository implements DeliveryAddressRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'description',
        'address',
        'zipcode',
        'latitude',
        'longitude',
        'is_default',
        'user_id'
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
    public function __construct(DeliveryAddress $model)
    {
        $this->model = $model;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return DeliveryAddress::class;
    }
    public function initIsDefault($userId){
        DeliveryAddress::where("user_id",$userId)->where("is_default",true)->update(["is_default"=>false]);
    }
    
    public function getAddressesByUserId($userId = '')
    {

        $addresses = DeliveryAddress::query()->where('user_id', $userId)->where('status', 1)->get();

      /*  foreach ($addresses as $address) {
            Log::debug('getAddressesByUserId:: '.$address);
        }*/
        return $addresses;
    }
}

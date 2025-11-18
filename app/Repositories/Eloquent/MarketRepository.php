<?php

namespace App\Repositories\Eloquent;

use App\Models\Market;
use App\Repositories\MarketRepositoryInterface;

/**
 * Class MarketRepository
 * @package App\Repositories
 * @version August 29, 2019, 9:38 pm UTC
 *
 * @method Market findWithoutFail($id, $columns = ['*'])
 * @method Market find($id, $columns = ['*'])
 * @method Market first($columns = ['*'])
 */
class MarketRepository extends BaseRepository implements MarketRepositoryInterface
{

  
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'address',
        'latitude',
        'longitude',
        'phone',
        'mobile',
        'information',
        'delivery_fee',
        'default_tax',
        'delivery_range',
        'available_for_delivery',
        'closed',
        'admin_commission',
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
    public function __construct(Market $model)
    {
        $this->model = $model;
    }
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Market::class;
    }

    /**
     * get my markets
     */

    public function myMarkets()
    {
        return Market::join("user_markets", "market_id", "=", "markets.id")
            ->where('user_markets.user_id', auth()->id())->get();
    }

    public function myActiveMarkets()
    {
        return Market::join("user_markets", "market_id", "=", "markets.id")
            ->where('user_markets.user_id', auth()->id())
            ->where('markets.active','=','1')->get();
    }  

}

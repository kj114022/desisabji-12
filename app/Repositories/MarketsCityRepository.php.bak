<?php

namespace App\Repositories;

use App\Models\MarketsCity;
use App\Models\City;


/**
 * Class MarketsCityRepository
 * @package App\Repositories
 * @version March 25, 2020, 9:48 am UTC
 *
 * @method MarketsCity findWithoutFail($id, $columns = ['*'])
 * @method MarketsCity find($id, $columns = ['*'])
 * @method MarketsCity first($columns = ['*'])
*/
class MarketsCityRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'market_id',
        'city_id',
        'closed',
        'active',
        'available_for_delivery',
        'available_pickup_store'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return MarketsCity::class;
    }

  
    public function city($cityId)
    {
        return City::where('id', $cityId)->get();
    }

    public function marketsForCity($cityId)
    {
       
        return $this->where("city_id",$cityId)->get();       
    }

    public function marketOperateCities()
    {
        $cities = [];
        foreach ($this->all() as $model) {
            if(!empty($model->city_id)){
              $cities[$model->city_id] = $this->city($model->city_id);
            }
        }
        return $cities;
    }
}

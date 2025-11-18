<?php

namespace App\Repositories\Eloquent;

use App\Models\MarketsCity;
use App\Models\City;
use App\Repositories\MarketsCityRepositoryInterface;

/**
 * Class MarketsCityRepository
 * @package App\Repositories
 * @version March 25, 2020, 9:48 am UTC
 *
 * @method MarketsCity findWithoutFail($id, $columns = ['*'])
 * @method MarketsCity find($id, $columns = ['*'])
 * @method MarketsCity first($columns = ['*'])
*/
class MarketsCityRepository extends BaseRepository implements MarketsCityRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'market_id',
        'city_id',
        'closed',
        'active',
        'available_for_delivery'
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
    public function __construct(MarketsCity $model)
    {
        $this->model = $model;
    }

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
        $markets = [];
        $marketsForCity = $this->where("city_id",$cityId)->get();
        //die($marketsForCity);
        foreach ($marketsForCity as $model) {
            if(!empty($model->market_id)){
              $markets[$model->market_id] = $model->market;
            }
        }
        return $markets;
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

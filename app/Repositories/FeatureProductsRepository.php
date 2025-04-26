<?php

namespace App\Repositories;

use App\Models\FeatureProducts;
use App\Models\City;


/**
 * Class FeatureCityRepository
 * @package App\Repositories
 * @version March 25, 2020, 9:48 am UTC
 *
 * @method feature findWithoutFail($id, $columns = ['*'])
 * @method feature find($id, $columns = ['*'])
 * @method feature first($columns = ['*'])
*/
class FeatureProductsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'product_id',
        'feature_id',
        'city_id',
        'active'
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
        return FeatureProducts::class;
    }

  
    public function city($cityId)
    {
        return City::where('id', $cityId)->get();
    }

    public function featureProductsForCity($featureId,$cityId)
    {
        $products = [];
        $featureProductsForCity = $this->where("city_id",$cityId)->where("feature_id",$featureId)->get();
        foreach ($featureProductsForCity as $model) {
            if(!empty($model->product_id)){
              $products[$model->product_id] = $model->product;
            }
        }
        return $products;
    }    
}

<?php

namespace App\Repositories;

use App\Models\ProductsCity;
use App\Models\City;


/**
 * Class ProductsCityRepository
 * @package App\Repositories
 * @version March 25, 2020, 9:48 am UTC
 *
 * @method ProductsCity findWithoutFail($id, $columns = ['*'])
 * @method ProductsCity find($id, $columns = ['*'])
 * @method ProductsCity first($columns = ['*'])
*/
class ProductsCityRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'product_id',
        'city_id',
        'market_id',
        'price',
        'discount_price',
        'deliverable'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ProductsCity::class;
    }


    public function city($cityId)
    {
        return City::where('id', $cityId)->get();
    }

    public function productsForCity($cityId)
    {
        $products = [];
        $productsForCity = $this->where("city_id",$cityId)->where("deliverable",'1')->get();
        //die($productsForCity);
        foreach ($productsForCity as $model) {
            if(!empty($model->product_id)){
              $products[$model->product_id] = $model->product;
            }
        }
        return $products;
    }    
}

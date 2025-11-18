<?php

namespace App\Repositories\Eloquent;

use App\Models\ProductsCity;
use App\Models\City;
use App\Repositories\ProductsCityRepositoryInterface;

/**
 * Class ProductsCityRepository
 * @package App\Repositories
 * @version March 25, 2020, 9:48 am UTC
 *
 * @method ProductsCity findWithoutFail($id, $columns = ['*'])
 * @method ProductsCity find($id, $columns = ['*'])
 * @method ProductsCity first($columns = ['*'])
*/
class ProductsCityRepository extends BaseRepository implements ProductsCityRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'product_id',
        'city_id',
        'price',
        'discount_price',
        'deliverable'
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
    public function __construct(ProductsCity $model)
    {
        $this->model = $model;
    }
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
        $productsForCity = $this->where("city_id",$cityId)->get();
        //die($productsForCity);
        foreach ($productsForCity as $model) {
            if(!empty($model->product_id)){
              $products[$model->product_id] = $model->product;
            }
        }
        return $products;
    }
    
}

<?php
/**
 * File name: ProductsCity.php
 * Last modified: 2020.04.30 at 06:29:44

 *
 */

namespace App\Models;

use Eloquent as Model;
use \App\Models\Product;
/**
 * Class FeatureProductsCity
 * @package App\Models
 * @version March 25, 2020, 9:48 am UTC
 *
 * @property integer product_id
 * @property integer city_id
 * @property integer feature_id
 * @property boolean active
 */
class FeatureProducts extends Model 
{
   
    public $table = 'feature_products';    


    public $fillable = [
        'product_id',
        'feature_id',
        'city_id',
        'active'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
        'feature_id' => 'integer',        
        'product_id' => 'integer',
        'city_id' => 'integer'
    ];
    

   

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function feature()
    {
        return $this->belongsTo(\App\Models\Feature::class, 'features', 'feature_id');
    }

   

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function product()
    {
        return $this->belongsToMany(\App\Models\Product::class, 'feature_products', 'product_id');
    }
    
    public function city()
    {
        return $this->belongsToMany(\App\Models\City::class, 'product_cities','city_id')->distinct();
    }

    
    
}

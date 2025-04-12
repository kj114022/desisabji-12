<?php
/**
 * File name: ProductsCity.php
 * Last modified: 2020.04.30 at 06:29:44

 *
 */

namespace App\Models;

use Eloquent as Model;
/**
 * Class ProductsCity
 * @package App\Models
 * @version March 25, 2020, 9:48 am UTC
 *
 * @property integer product_id
 * @property integer city_id
 * @property boolean price
 * @property boolean discount_price
 * @property boolean deliverable
 */
class ProductsCity extends Model 
{
   
    public $table = 'product_cities';    


    public $fillable = [
        'category_id',
        'product_id',
        'city_id',
        'market_id',
        'price',
        'discount_price',
        'deliverable'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'category_id'=> 'string',
        'product_id'  => 'string',
        'city_id'  => 'string',
        'market_id'  => 'string',
        'price' => 'double',
        'discount_price' => 'double',
        'deliverable' => 'boolean'
    ];

      /**
     * New Attributes
     *
     * @var array
     */
    protected $appends = [
     //   'product',
   //     'city',
           'market'

    ];

    public function getProductAttribute()
    {

        return $this->product()->first(['id', 'code', 'name', 'meta_keyword', 'product_slug','description','capacity','unit']);
    }


    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->discount_price > 0 ? $this->discount_price : $this->price;
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }

    public function getCityAttribute()
    {

        return $this->city()->get();
    }


    public function city()
    {
        return $this->belongsTo(\App\Models\City::class)->distinct();
    }

     public function getMarketAttribute()
    {

        return $this->market()->first();
    }

    public function market()
    {
        return $this->belongsTo(\App\Models\Market::class)->distinct();
    }

    /**
     * @return float
     */
    public function applyCoupon($coupon): float
    {
        $price = $this->getPrice();
        if(isset($coupon) && count($this->discountables) + count($this->category->discountables) + count($this->market->discountables) > 0){
            if ($coupon->discount_type == 'fixed') {
                $price -= $coupon->discount;
            } else {
                $price = $price - ($price * $coupon->discount / 100);
            }
            if ($price < 0) $price = 0;
        }
        return $price;
    }
    
}

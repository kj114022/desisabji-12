<?php
/**
 * File name: MarketsCity.php
 * Last modified: 2020.04.30 at 06:29:44

 *
 */

namespace App\Models;

use Eloquent as Model;

/**
 * Class MarketsCity
 * @package App\Models
 * @version March 25, 2020, 9:48 am UTC
 *
 * @property \App\Models\Market market
 * @property integer market_id
 * @property integer city_id
 * @property boolean closed
 * @property boolean active
 * @property boolean available_for_delivery
 * @property boolean available_pickup_store
 */
class MarketsCity extends Model
{

    public $table = 'market_cities';
    


    public $fillable = [
        'market_id',
        'city_id',
        'closed',
        'active',
        'available_for_delivery',
        'available_pickup_store'
    ];

    
    /**
     * New Attributes
     *
     * @var array
     */
    protected $appends = [
        'custom_fields',
        'market',
        'city'
    ];

    public function customFieldsValues()
    {
        return $this->morphMany('App\Models\CustomFieldValue', 'customizable');
    }

    public function getCustomFieldsAttribute()
    {
        $hasCustomField = in_array(static::class,setting('custom_field_models',[]));
        if (!$hasCustomField){
            return [];
        }
        $array = $this->customFieldsValues()
            ->join('custom_fields','custom_fields.id','=','custom_field_values.custom_field_id')
            ->where('custom_fields.in_table','=',true)
            ->get()->toArray();

        return convertToAssoc($array,'name');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function market()
    {
        return $this->belongsTo(\App\Models\Market::class, 'market_id', 'id');
    }

    
    /**
     * get market attribute
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\BelongsTo|object|null
     */
    public function getMarketAttribute()
    {
        return $this->market()->first(['name', 'delivery_fee', 'address', 'phone','default_tax','minimum_order']);
    }

    public function city()
    {
        return $this->belongsTo(\App\Models\City::class, 'city_id','id');
    }
    
    
    /**
     * get market attribute
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\BelongsTo|object|null
     */
    public function getCityAttribute()
    {
        return $this->city()->first(['name', 'state', 'status']);
    }
}

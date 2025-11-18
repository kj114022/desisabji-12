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
 */
class MarketsCity extends Model
{

    public $table = 'market_cities';
    


    public $fillable = [
        'market_id',
        'city_id',
        'closed',
        'active',
        'available_for_delivery'
    ];

    
    /**
     * New Attributes
     *
     * @var array
     */
    protected $appends = [
        'custom_fields',
        
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
        return $this->belongsToMany(\App\Models\Market::class, 'market_cities', 'market_id');
    }
    
    public function city()
    {
        return $this->belongsToMany(\App\Models\City::class, 'market_cities','city_id')->distinct();
    }
    
}

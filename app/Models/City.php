<?php
/**
 * File name: City.php
 * Last modified: 2020.08.23 at 19:56:12

 */

namespace App\Models;

use Eloquent as Model;

/**
 * Class City
 * @package App\Models
 * @version August 23, 2020, 6:10 pm UTC
 *
 * @property string name
 * @property string state
 * @property boolean status
 */
class City extends Model
{

    public $table = 'cities';
    


    public $fillable = [
        'name',
        'state',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'state' => 'string',
        'status' => 'boolean'
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

    public function cities()
    {
        return $this->hasMany(\App\Models\State::class, 'state');
    }

    public function markets()
    {
        return $this->hasMany(\App\Models\Market::class);
    }
    
}

<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class DeliveryAddress
 * @package App\Models
 * @version December 6, 2019, 1:57 pm UTC
 *
 * @property \App\Models\User user
 * @property string description
 * @property string address
 * @property integer zipcode
 * @property string latitude
 * @property string longitude
 * @property boolean is_default
 * @property integer user_id
 */
class DeliveryAddress extends Model
{
    use HasFactory;

    public $table = 'delivery_addresses';
    


    public $fillable = [
        'name',
        'first_name',
        'last_name',
        'city',
        'state',
        'country',
        'mobile',
        'email',
        'city_id',
        'description',
        'address',
        'landmark',
        'is_billing',
        'zipcode',
        'latitude',
        'longitude',
        'is_default',
        'user_id',
        'status',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'description' => 'string',
        'address' => 'string',
        'zipcode' => 'integer',
        'latitude' => 'double',
        'longitude' => 'double',
        'is_default' => 'boolean',
        'user_id' => 'integer',
        'name' => 'string',
        'first_name' => 'string',   
        'last_name' => 'string',
        'city' => 'string',
        'state' => 'string',
        'country' => 'string',
        'mobile' => 'string',
        'email' => 'string',
        'city_id' => 'integer',
        'landmark' => 'string',
        'is_billing' => 'boolean',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'description' => 'nullable|string|max:255',
        'address' => 'required',
        'zipcode' => 'required',
        'user_id' => 'required|exists:users,id',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'is_default' => 'nullable|boolean',
        'name' => 'required|string|max:255',
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'state' => 'required|string|max:255',
        'country' => 'required|string|max:255',
        'mobile' => 'required|string|max:255',
        'email' => 'nullable|string|max:255',
        'city_id' => 'required|integer',
        'landmark' => 'nullable|string|max:255',
        'is_billing' => 'nullable|boolean',
        'status' => 'nullable|string|max:255',
        'created_at' => 'nullable|datetime',
        'updated_at' => 'nullable|datetime',

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
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }
    
}

<?php
/**
 * File name: Coupon.php
 * Last modified: 2020.08.23 at 19:56:12

 */

namespace App\Models;

use Eloquent as Model;

/**
 * Class CouponRedeem
 * @package App\Models
 * @version August 23, 2020, 6:10 pm UTC
 *
 * @property string code
 * @property double discount
 * @property string discount_type
 * @property string description
 * @property dateTime expires_at
 * @property boolean enabled
 */
class CouponRedeem extends Model
{

    public $table = 'coupon_redeems';


    public $fillable = [
        'coupon_code',
        'order_id',
        'user_id',
        'redeem_amount',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'coupon_code' => 'string',
        'order_id' => 'integer',
        'user_id' => 'integer',
        'redeem_amount' => 'double',
        'status' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'coupon_code' => 'required|unique:coupons|max:50',
        'order_id' => 'required|integer',
        'user_id' => 'required|integer',
        'redeem_amount' => 'required|numeric|min:0',
        'status' => 'required|string'
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
    
}

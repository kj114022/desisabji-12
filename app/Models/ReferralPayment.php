<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class ReferralPayment.
 * @package App\Models
 *
 * @property string order_id
 * @property string referral_id
 * @property dateTime payment_date
 * @property double payment_amt
 */
class ReferralPayment extends Model
{

    public $table = 'referral_payments';
    


    public $fillable = [
        'order_id',
        'referral_id',
        'payment_date',
        'payment_amt'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'order_id' => 'string',
        'referral_id' => 'string',
        'payment_date' => 'datetime',
        'payment_amt' => 'double'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'referral_id' => 'required',
        'payment_date' => 'required',
        'payment_amt' => 'required'
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
    public function referral()
    {
        return $this->belongsTo(\App\Models\Referral::class, 'referral_id', 'id');
    }
    
}

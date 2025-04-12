<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Referral
 * @package App\Models
 *
 * @property string customer_no
 * @property string referral_no
 * @property string referral_type
 * @property double referral_amt
 * @property string payment_status
 */
class Referral extends Model
{

    public $table = 'referrals';
    


    public $fillable = [
        'customer_no',
        'referral_no',
        'referral_type',
        'referral_amt',
        'payment_status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'customer_no' => 'string',
        'referral_no' => 'string',
        'referral_type' => 'string',
        'referral_amt' => 'double',
        'payment_status' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'customer_no' => 'required',
        'referral_no' => 'required',
        'referral_type' => 'required',
        'referral_amt' => 'required|numeric|min:0'
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

    public function referralPayments()
    {
        return $this->hasMany(\App\Models\ReferralPayment::class, 'referral_id');
    }
    
}

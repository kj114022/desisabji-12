<?php
namespace App\Models;

use Eloquent as Model;

/**
 * Class Wallet
 * @package App\Models
 *
 * @property string customer_id
 * @property double wallet_amt
 * @property string active
 */
class Wallet extends Model
{

    public $table = 'wallets';



    public $fillable = [
        'customer_id',
        'wallet_amt',
        'active'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'customer_id' => 'string',
        'wallet_amt' => 'double',
        'active' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'customer_id' => 'required',
        'wallet_amt' => 'required|numeric|min:0'
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

    public function walletPayments()
    {
        return $this->hasMany(\App\Models\WalletPayment::class, 'wallet_id');
    }
    
}

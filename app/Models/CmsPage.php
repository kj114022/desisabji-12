<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Cart
 * @package App\Models
 * @version September 4, 2019, 3:38 pm UTC
 *
 * @property \App\Models\CmsPage CmsPage
 * @property \App\Models\User user
 * @property \Illuminate\Database\Eloquent\Collection options
 * @property integer product_id
 * @property integer user_id
 * @property integer quantity
 */
class CmsPage extends Model
{

    public $table = 'cms_pages';
    


    public $fillable = [
        'id',
        'title',
        'description',
        'page_slug',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'page_slug' => 'string',
        'status' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'required|exists:carts,id',
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'page_slug' => 'required|string|max:255',
        'status' => 'required|boolean'
    ];

    /**
     * New Attributes
     *
     * @var array
     */
    protected $appends = [
        'custom_fields'
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


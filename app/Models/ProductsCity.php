<?php

namespace App\Models;

use Eloquent as Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia as HasMediaTrait;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
/**
 * Class ProductCity
 * @package App\Models
 * @version March 25, 2020, 9:48 am UTC
 *
 * @property \App\Models\Product product
 * @property integer product_id
 * @property integer city_id
 * @property boolean price
 * @property boolean discount_price
 * @property boolean deliverable
 */
class ProductsCity extends Model implements HasMedia
{
    use HasMediaTrait {
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }

    public $table = 'product_cities';    


    public $fillable = [
        'id',
        'product_id',
        'city_id',
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
        'price' => 'double',
        'discount_price' => 'double'
    ];
    /**
     * New Attributes
     *
     * @var array
     */
    protected $appends = [
        'custom_fields',
        'has_media',
        'market'
    ];

    /**
     * @param Media|null $media
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CROP, 200, 200)
            ->sharpen(10);

        $this->addMediaConversion('icon')
            ->fit(Manipulations::FIT_CROP, 100, 100)
            ->sharpen(10);
    }

    /**
     * to generate media url in case of fallback will
     * return the file type icon
     * @param string $conversion
     * @return string url
     */
    public function getFirstMediaUrl($collectionName = 'default', $conversion = '')
    {
        $url = $this->getFirstMediaUrlTrait($collectionName);
        $array = explode('.', $url);
        $extension = strtolower(end($array));
        if (in_array($extension, config('medialibrary.extensions_has_thumb'))) {
            return asset($this->getFirstMediaUrlTrait($collectionName, $conversion));
        } else {
            return asset(config('medialibrary.icons_folder') . '/' . $extension . '.png');
        }
    }

    public function getCustomFieldsAttribute()
    {
        $hasCustomField = in_array(static::class, setting('custom_field_models', []));
        if (!$hasCustomField) {
            return [];
        }
        $array = $this->customFieldsValues()
            ->join('custom_fields', 'custom_fields.id', '=', 'custom_field_values.custom_field_id')
            ->where('custom_fields.in_table', '=', true)
            ->get()->toArray();

        return convertToAssoc($array, 'name');
    }

    public function customFieldsValues()
    {
        return $this->morphMany('App\Models\CustomFieldValue', 'customizable');
    }

    /**
     * Add Media to api results
     * @return bool
     */
    public function getHasMediaAttribute()
    {
        return $this->hasMedia('image') ? true : false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class, 'category_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function options()
    {
        return $this->hasMany(\App\Models\Option::class, 'product_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function optionGroups()
    {
        return $this->belongsToMany(\App\Models\OptionGroup::class,'options');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function productReviews()
    {
        return $this->hasMany(\App\Models\ProductReview::class, 'product_id');
    }

    /**
     * get market attribute
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\BelongsTo|object|null
     */
    public function getMarketAttribute()
    {
        return $this->market()->first(['id', 'name', 'delivery_fee', 'address', 'phone','default_tax']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function market()
    {
        return $this->belongsTo(\App\Models\Market::class, 'market_id', 'id');
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
        return $this->belongsToMany(\App\Models\Product::class, 'product_cities', 'product_id');
    }
    
    public function city()
    {
        return $this->belongsToMany(\App\Models\City::class, 'product_cities','city_id')->distinct();
    }
    
}

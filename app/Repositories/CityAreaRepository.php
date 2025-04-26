<?php

namespace App\Repositories;

use App\Models\CityArea;


/**
 * Class CityAreaRepository
 * @package App\Repositories
 * @version March 25, 2020, 9:48 am UTC
 *
 * @method CityArea findWithoutFail($id, $columns = ['*'])
 * @method CityArea find($id, $columns = ['*'])
 * @method CityArea first($columns = ['*'])
*/
class CityAreaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'state',
        'status'
    ];

    /**
     * Get searchable fields
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CityArea::class;
    }

    public function city($cityId)
    {
        return City::where('id', $cityId)->get();
    }

    public function areasForCity($cityId)
    {
        return $this->where("city_id",$cityId)->get();
       
    }

   
}

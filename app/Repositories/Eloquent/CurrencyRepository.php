<?php

namespace App\Repositories\Eloquent;

use App\Models\Currency;
use App\Repositories\CurrencyRepositoryInterface;

/**
 * Class CurrencyRepository
 * @package App\Repositories
 * @version October 22, 2019, 2:46 pm UTC
 *
 * @method Currency findWithoutFail($id, $columns = ['*'])
 * @method Currency find($id, $columns = ['*'])
 * @method Currency first($columns = ['*'])
*/
class CurrencyRepository extends BaseRepository implements CurrencyRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'symbol',
        'code',
        'decimal_digits',
        'rounding'
    ];

    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Currency $model)
    {
        $this->model = $model;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Currency::class;
    }
}

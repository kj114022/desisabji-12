<?php

namespace App\Repositories\Eloquent;

use App\Models\MarketsPayout;
use App\Repositories\MarketsPayoutRepositoryInterface;

/**
 * Class MarketsPayoutRepository
 * @package App\Repositories
 * @version March 25, 2020, 9:48 am UTC
 *
 * @method MarketsPayout findWithoutFail($id, $columns = ['*'])
 * @method MarketsPayout find($id, $columns = ['*'])
 * @method MarketsPayout first($columns = ['*'])
*/
class MarketsPayoutRepository extends BaseRepository implements MarketsPayoutRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'market_id',
        'method',
        'amount',
        'paid_date',
        'note'
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
    public function __construct(MarketsPayout $model)
    {
        $this->model = $model;
    }
    /**
     * Configure the Model
     **/
    public function model()
    {
        return MarketsPayout::class;
    }
}

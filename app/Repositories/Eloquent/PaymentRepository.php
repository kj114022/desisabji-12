<?php

namespace App\Repositories\Eloquent;

use App\Models\Payment;
use App\Repositories\PaymentRepositoryInterface;

/**
 * Class PaymentRepository
 * @package App\Repositories
 * @version August 29, 2019, 9:39 pm UTC
 *
 * @method Payment findWithoutFail($id, $columns = ['*'])
 * @method Payment find($id, $columns = ['*'])
 * @method Payment first($columns = ['*'])
*/
class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'price',
        'description',
        'user_id'
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
    public function __construct(Payment $model)
    {
        $this->model = $model;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Payment::class;
    }
}

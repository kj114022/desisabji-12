<?php
/**
 * File name: OrdersOfUserCriteria.php
 * Last modified: 2020.04.30 at 08:21:08

 *
 */

namespace App\Criteria\Payments;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Facades\Log;

/**
 * Class PaymentsOfUserCriteria.
 *
 * @package namespace App\Criteria\Payments;
 */
class PaymentsOfUserCriteria implements CriteriaInterface
{
    /**
     * @var User
     */
    private $request;

    /**
     * CartsOfUserCriteria constructor.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply criteria in query repository
     *
     * @param string $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        if (!$this->request->has('user_id')) {
            return $model;
        } else {
            $id = $this->request->get('user_id');
            Log::debug('PaymentsOfUserCriteria applied for user_id: ' . $id);
            return $model->join('users', 'users.id', '=', 'payments.user_id')
                ->where('users.id', $id)
                ->groupBy('payments.id')
                ->select('payments.*');

        }
    }
}

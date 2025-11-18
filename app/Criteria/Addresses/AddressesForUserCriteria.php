<?php
/**
 * File name: OrdersOfUserCriteria.php
 * Last modified: 2020.04.30 at 08:21:08

 *
 */

namespace App\Criteria\Addresses;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Facades\Log;

/**
 * Class AddressesForUserCriteria.
 *
 * @package namespace App\Criteria\Addresses;
 */
class AddressesForUserCriteria implements CriteriaInterface
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
            Log::debug('AddressesForUserCriteria applied for user_id: ' . $id);
            return $model->where('user_id', $id)
                ->where('status', 1)
                ->orderBy('created_at', 'desc');
        }
    }
}

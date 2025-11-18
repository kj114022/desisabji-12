<?php
/**
 * File name: OrdersOfUserCriteria.php
 * Last modified: 2020.04.30 at 08:21:08

 *
 */

namespace App\Criteria\Carts;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Facades\Log;

/**
 * Class CartsOfUserCriteria.
 *
 * @package namespace App\Criteria\Carts;
 */
class CartsOfUserCriteria implements CriteriaInterface
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
            Log::debug('CartsOfUserCriteria applied for user_id: ' . $id);
            return $model->join('users', 'users.id', '=', 'carts.user_id')
                ->where('users.id', $id)
                ->groupBy('carts.id')
                ->join('products AS p', 'p.id', '=', 'carts.product_id')
                ->join('product_cities AS pc', 'pc.id', '=', 'carts.product_city_id')
                ->select('p.name AS product_name', 'p.product_icon AS product_image', 'pc.price AS city_price', 'pc.discount_price AS city_discount', 'pc.deliverable AS city_deliverable', 'carts.*');

        }
    }
}

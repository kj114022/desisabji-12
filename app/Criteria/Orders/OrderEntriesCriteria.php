<?php
/**
 * File name: OrdersOfUserCriteria.php
 * Last modified: 2020.04.30 at 08:21:08

 *
 */

namespace App\Criteria\Orders;


use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class OrderEntriesCriteria.
 *
 * @package namespace App\Criteria\Orders;
 */
class OrderEntriesCriteria implements CriteriaInterface
{
    /**
     * @var User
     */
    private $request;

    /**
     * OrdersOfUserCriteria constructor.
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
        Log::debug('OrderEntriesCriteria applied');

        if (!$this->request->has('order_id')) {
            return $model;
        } else {
            Log::debug('OrderEntriesCriteria applied for order_id: ' . $this->request->get('order_id'));
            // If the request has 'order_id', we will filter the entries based on that
            $id = $this->request->get('order_id');
            return $model->join("orders", "orders.id", "=", "product_orders.order_id")
                ->join("products", "products.id", "=", "product_orders.product_id")
                ->where('product_orders.order_id', $this->request->get('order_id'))
                ->groupBy('orders.id')
                ->select('product_orders.*');

       }
    }
}

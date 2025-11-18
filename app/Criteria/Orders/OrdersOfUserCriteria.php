<?php
/**
 * File name: OrdersOfUserCriteria.php
 * Last modified: 2020.04.30 at 08:21:08

 *
 */

namespace App\Criteria\Orders;

use App\Models\User;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Facades\Log;
/**
 * Class OrdersOfUserCriteria.
 *
 * @package namespace App\Criteria\Orders;
 */
class OrdersOfUserCriteria implements CriteriaInterface
{
    /**
     * @var User
     */
    private $userId;

    /**
     * OrdersOfUserCriteria constructor.
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
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
        Log::info('Applying OrdersOfUserCriteria', ['user_id' => $this->userId]);
        // Check if the user has a specific role and apply the criteria accordingly
        // If the user is an admin, return all orders
        // If the user is a manager, filter orders by the user's market
        // If the user is a client, filter orders by the user's ID
        // If the user is a driver, filter orders by the driver's ID
        // Otherwise, return the model without any modifications
        if (auth()->user()->hasRole('admin')) {
            return $model;
        } else if (auth()->user()->hasRole('manager')) {
            return $model->join("product_orders", "orders.id", "=", "product_orders.order_id")
                ->join("products", "products.id", "=", "product_orders.product_id")
                ->join("user_markets", "user_markets.market_id", "=", "products.market_id")
                ->where('user_markets.user_id', $this->userId)
                ->groupBy('orders.id')
                ->select('orders.*');

        } else if (auth()->user()->hasRole('client')) {
             return $model->newQuery()
                ->join('users', 'users.id', '=', 'orders.user_id')
                ->join('delivery_addresses', 'delivery_addresses.id', '=', 'orders.delivery_address_id')
                ->join('order_statuses', 'order_statuses.id', '=', 'orders.order_status_id')
                ->where('orders.user_id', $this->userId)
                ->groupBy('orders.id')
                ->select(['orders.id as order_id', 'orders.code as order_code', 'orders.total_price as order_total_price', 'orders.created_at as order_date', 'users.name as user_name', 'users.email as user_email', 'delivery_addresses.address as delivery_address', 'delivery_addresses.city as delivery_city', 'delivery_addresses.zipcode as delivery_zipcode', 'order_statuses.status as order_status']);
        } else if (auth()->user()->hasRole('driver')) {
            return $model->newQuery()->where('orders.driver_id', $this->userId)
                ->groupBy('orders.id');
        } else {
            return $model;
        }
    }
}

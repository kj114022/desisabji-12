<?php
/**
 * File name: OrderAPIController.php
 * Last modified: 2020.05.31 at 19:34:40

 *
 */

namespace App\Http\Controllers\API;


use App\Criteria\Orders\OrdersOfStatusesCriteria;
use App\Criteria\Orders\OrdersOfUserCriteria;
use App\Events\OrderChangedEvent;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Notifications\AssignedOrder;
use App\Notifications\NewOrder;
use App\Notifications\StatusChangedOrder;
use App\Repositories\CartRepositoryInterface;
use App\Repositories\NotificationRepositoryInterface;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\PaymentRepositoryInterface;
use App\Repositories\ProductOrderRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;
use Stripe\Token;

/**
 * Class OrderController
 * @package App\Http\Controllers\API
 */
class OrderAPIController extends Controller
{
    /** @var  OrderRepository */
    private $orderRepository;
    /** @var  ProductOrderRepository */
    private $productOrderRepository;
    /** @var  CartRepository */
    private $cartRepository;
    /** @var  UserRepository */
    private $userRepository;
    /** @var  PaymentRepository */
    private $paymentRepository;
    /** @var  NotificationRepository */
    private $notificationRepository;

    /**
     * OrderAPIController constructor.
     * @param OrderRepository $orderRepo
     * @param ProductOrderRepository $productOrderRepository
     * @param CartRepository $cartRepo
     * @param PaymentRepository $paymentRepo
     * @param NotificationRepository $notificationRepo
     * @param UserRepository $userRepository
     */
    public function __construct(OrderRepositoryInterface $orderRepo, ProductOrderRepositoryInterface $productOrderRepository, CartRepositoryInterface $cartRepo, PaymentRepositoryInterface $paymentRepo, NotificationRepositoryInterface $notificationRepo, UserRepositoryInterface $userRepository)
    {
        $this->orderRepository = $orderRepo;
        $this->productOrderRepository = $productOrderRepository;
        $this->cartRepository = $cartRepo;
        $this->userRepository = $userRepository;
        $this->paymentRepository = $paymentRepo;
        $this->notificationRepository = $notificationRepo;
    }

    /**
     * Display a listing of the Order.
     * GET|HEAD /orders
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        Log::info("OrderAPIController :: index with request data: " . json_encode($request->all()));
       
        try {
            $this->orderRepository->pushCriteria(new RequestCriteria($request));
            $this->orderRepository->pushCriteria(new OrdersOfStatusesCriteria($request));
            $this->orderRepository->pushCriteria(new OrdersOfUserCriteria($request->get('user_id', null)));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $orders = $this->orderRepository->all();

        return $this->sendResponse($orders->toArray(), 'Orders retrieved successfully');
    }

    /**
     * Display the specified Order.
     * GET|HEAD /orders/{id}
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
           Log::info("OrderAPIController :: show for ID: " . $id);
        /** @var Order $order */
        if (!empty($this->orderRepository)) {
            try {
                $this->orderRepository->pushCriteria(new RequestCriteria($request));
            } catch (RepositoryException $e) {
                return $this->sendError($e->getMessage());
            }
            $order = $this->orderRepository->findWithoutFail($id);
            $response = $this->orderRepository->getModel()->with(['user', 'payment', 'orderStatus', 'productOrders.product', 'productOrders.product.market'])->find($id);
            if ($response) {    
                $order = $response;
            } else {
                Log::error("OrderAPIController :: show - Order not found for ID: " . $id);
                return $this->sendError('Order not found');
            }
        }

        if (empty($order)) {
            return $this->sendError('Order not found');
        }

        return $this->sendResponse($order->toArray(), 'Order retrieved successfully');


    }

    /**
     * Store a newly created Order in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
         Log::info("OrderAPIController :: store with request data: " . json_encode($request->all()));
        if (!$request->has('user_id')) {
            return $this->sendError('User ID is required');
        }   
        
        $payment = $request->only('payment');
        if (isset($payment['payment']) && $payment['payment']['method']) {
            if ($payment['payment']['method'] == "Credit Card (Stripe Gateway)") {
                return $this->stripPayment($request);
            } else {
                return $this->cashPayment($request);

            }
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    private function stripPayment(Request $request)
    {
        Log::info("OrderAPIController :: stripPayment");
        $input = $request->all();
        $amount = 0;
        try {
            $user = $this->userRepository->findWithoutFail($input['user_id']);
            if (empty($user)) {
                return $this->sendError('User not found');
            }
            $stripeToken = Token::create(array(
                "card" => array(
                    "number" => $input['stripe_number'],
                    "exp_month" => $input['stripe_exp_month'],
                    "exp_year" => $input['stripe_exp_year'],
                    "cvc" => $input['stripe_cvc'],
                    "name" => $user->name,
                )
            ));
            if ($stripeToken->created > 0) {
                if (empty($input['delivery_address_id'])) {
                    $order = $this->orderRepository->create(
                        $request->only('user_id', 'order_status_id', 'tax', 'hint')
                    );
                } else {
                    $order = $this->orderRepository->create(
                        $request->only('user_id', 'order_status_id', 'tax', 'delivery_address_id', 'delivery_fee', 'hint','total_price','total_discount')
                    );
                }
                foreach ($input['products'] as $productOrder) {
                    $productOrder['order_id'] = $order->id;
                    $amount += $productOrder['price'] * $productOrder['quantity'];
                    $this->productOrderRepository->create($productOrder);
                }
                $amount += $order->delivery_fee;
                $amountWithTax = $amount + ($amount * $order->tax / 100);
                $charge = $user->charge((int)($amountWithTax * 100), ['source' => $stripeToken]);
                $payment = $this->paymentRepository->create([
                    "user_id" => $input['user_id'],
                    "description" => trans("lang.payment_order_done"),
                    "price" => $amountWithTax,
                    "status" => $charge->status, // $charge->status
                    "method" => $input['payment']['method'],
                ]);
                 $orderNo = "".$user->city."".$user->id."".date('YmdHis');
                 $order = $this->orderRepository->update(['payment_id' => $payment->id,'code' => $orderNo,'order_status_id'=> 1,'delivery_date'=> date('Y-m-d', strtotime (' +1 day')),'coupon_code' => $input['coupon_code']], $order->id);

                $this->cartRepository->deleteWhere(['user_id' => $order->user_id]);

                Notification::send($order->productOrders[0]->product->market->users, new NewOrder($order));
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($order->toArray(), __('lang.saved_successfully', ['operator' => __('lang.order')]));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    private function cashPayment(Request $request)
    {
        Log::info("OrderAPIController :: cashPayment");
        $input = $request->all();
        Log::info("OrderAPIController :: coupon code". $input['coupon_code']);
        $amount = 0;
        try {
            $user = $this->userRepository->findWithoutFail($input['user_id']);
            if (empty($user)) {
                return $this->sendError('User not found');
            }
            $order = $this->orderRepository->create(
                $request->only('user_id', 'order_status_id', 'tax', 'delivery_address_id', 'delivery_fee', 'hint','total_price','total_discount')
            );
           foreach ($input['products'] as $productOrder) {
                $productOrder['order_id'] = $order->id;
                $amount += $productOrder['price'] * $productOrder['quantity'];
                $this->productOrderRepository->create($productOrder);
            }
            $amount += $order->delivery_fee;
            $amountWithTax = $amount + ($amount * $order->tax / 100);
            $payment = $this->paymentRepository->create([
                "user_id" => $input['user_id'],
                "description" => trans("lang.payment_order_waiting"),
                "price" => $amountWithTax,
                "status" => 'Waiting for Client',
                "method" => $input['payment']['method'],
            ]);

             $orderNo = $user->city."".$user->id."".date('YmdHis');
                Log::info("OrderAPIController :: orderNo: " . $orderNo);
              $order =  $this->orderRepository->update(['payment_id' => $payment->id,'code' => $orderNo,'order_status_id'=> 1,'delivery_date'=> date('Y-m-d', strtotime(' +1 day')),'coupon_code' => $input['coupon_code']], $order->id);

            $this->cartRepository->deleteWhere(['user_id' => $order->user_id]);

         //   Notification::send($order->productOrders[0]->product->market->users, new NewOrder($order));

        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($order->toArray(), __('lang.saved_successfully', ['operator' => __('lang.order')]));
    }

    /**
     * Update the specified Order in storage.
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $oldOrder = $this->orderRepository->findWithoutFail($id);
        if (empty($oldOrder)) {
            return $this->sendError('Order not found');
        }
        $oldStatus = $oldOrder->payment->status;
        $input = $request->all();

        try {
            $order = $this->orderRepository->update($input, $id);
            if (isset($input['order_status_id']) && $input['order_status_id'] == 5 && !empty($order)) {
                $this->paymentRepository->update(['status' => 'Paid'], $order['payment_id']);
            }
            event(new OrderChangedEvent($oldStatus, $order));

            if (setting('enable_notifications', false)) {
                if (isset($input['order_status_id']) && $input['order_status_id'] != $oldOrder->order_status_id) {
                    Notification::send([$order->user], new StatusChangedOrder($order));
                }

                if (isset($input['driver_id']) && ($input['driver_id'] != $oldOrder['driver_id'])) {
                    $driver = $this->userRepository->findWithoutFail($input['driver_id']);
                    if (!empty($driver)) {
                        Notification::send([$driver], new AssignedOrder($order));
                    }
                }
            }

        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($order->toArray(), __('lang.saved_successfully', ['operator' => __('lang.order')]));
    }

    /**
     * Remove the specified Order from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $order = $this->orderRepository->findWithoutFail($id);
        if (empty($order)) {
            return $this->sendError('Order not found');
        }

        try {
            $this->orderRepository->delete($id);
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($order->toArray(), __('lang.deleted_successfully', ['operator' => __('lang.order')]));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $this->orderRepository->pushCriteria(new RequestCriteria($request));
        $this->orderRepository->pushCriteria(new OrderSearchCriteria($request));

        $orders = $this->orderRepository->all();

        return $this->sendResponse($orders->toArray(), 'Orders retrieved successfully');
    }

}

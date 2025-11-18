<?php


/**
 * File name: RazorPayController.php
 * Last modified: 2020.10.29 at 17:03:55

 *
 */

namespace App\Http\Controllers;

use App\Models\DeliveryAddress;
use App\Models\Payment;
use App\Models\User;
use App\Repositories\DeliveryAddressRepositoryInterface;
use Illuminate\Http\Request;
use Flash;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;
class RazorPayController extends ParentOrderController
{

    /**
     * @var Api
     */
    private $api;
    private $currency;
    /** @var DeliveryAddressRepository
     *
     */
    private $deliveryAddressRepo;

    public function __init()
    {
        $this->api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );
        $this->currency = setting('default_currency_code', 'INR');
        $this->deliveryAddressRepo = new DeliveryAddressRepository(app());
    }


    public function index()
    {
        return view('welcome');
    }


    public function checkout(Request $request)
    {
          Log::info("OrderAPIController :: rozar Pay coupon ::".$request->get('coupon_code'));
        try{
            $user = $this->userRepository->findByField('api_token', $request->get('api_token'))->first();
            $coupon = $this->couponRepository->findByField('code', $request->get('coupon_code'))->first();
            $deliveryId = $request->get('delivery_address_id');
            $deliveryAddress = $this->deliveryAddressRepo->findWithoutFail($deliveryId);
            if (!empty($user)) {
                $this->order->user = $user;
                $this->order->user_id = $user->id;
                $this->order->delivery_address_id = $deliveryId;
                $this->coupon = $coupon;
                $razorPayCart = $this->getOrderData();

                $razorPayOrder = $this->api->order->create($razorPayCart);
                $fields = $this->getRazorPayFields($razorPayOrder, $user, $deliveryAddress);
                //url-ify the data for the POST
                $fields_string = http_build_query($fields);

                //open connection
                $ch = curl_init();

                //set the url, number of POST vars, POST data
                curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/checkout/embedded');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
                $result = curl_exec($ch);
                if($result === true){
                    die();
                }
            }else{
                Flash::error("Error processing RazorPay user not found");
                return redirect(route('payments.failed'));
            }
        }catch (\Exception $e){
            Flash::error("Error processing RazorPay payment for your order :" . $e->getMessage());
            return redirect(route('payments.failed'));
        }
    }


    /**
     * @param int $userId
     * @param int $deliveryAddressId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function paySuccess(Request $request, int $userId, int $deliveryAddressId,string $couponCode = null)
    {
        $data = $request->all();

        $description = $this->getPaymentDescription($data);

        $this->order->user_id = $userId;
        $this->order->user = $this->userRepository->findWithoutFail($userId);
        $this->coupon = $this->couponRepository->findByField('code', $couponCode)->first();
        $this->order->delivery_address_id = $deliveryAddressId;


        if ($request->hasAny(['razorpay_payment_id','razorpay_signature'])) {

            $this->order->payment = new Payment();
            $this->order->payment->status = trans('lang.order_paid');
            $this->order->payment->method = 'RazorPay';
            $this->order->payment->description = $description;

            $this->createOrder();

            return redirect(url('payments/razorpay'));
        }else{
            Flash::error("Error processing RazorPay payment for your order");
            return redirect(route('payments.failed'));
        }

    }

    /**
     * Set cart data for processing payment on PayPal.
     *
     *
     * @return array
     */
    private function getOrderData()
    {
        $data = [];
        $this->calculateTotal();
        $amountINR = $this->total;
        if ($this->currency !== 'INR') {
            // Using exchangerate-api.com (free tier, no API key required)
            // Fallback to 1:1 if API fails (should be configured properly in production)
            try {
                $url = "https://api.exchangerate-api.com/v4/latest/INR";
                $context = stream_context_create([
                    'http' => [
                        'timeout' => 5,
                        'ignore_errors' => true
                    ]
                ]);
                $exchange = json_decode(@file_get_contents($url, false, $context), true);
                if ($exchange && isset($exchange['rates'][$this->currency])) {
                    $amountINR = $this->total / $exchange['rates'][$this->currency];
                } else {
                    // Fallback: log error and use 1:1 conversion (should be fixed in production)
                    \Log::warning("Currency conversion failed for {$this->currency}, using 1:1 rate");
                    $amountINR = $this->total; // 1:1 fallback
                }
            } catch (\Exception $e) {
                \Log::error("Currency conversion error: " . $e->getMessage());
                $amountINR = $this->total; // 1:1 fallback
            }
        }
        $order_id = $this->paymentRepository->all()->count() + 1;
        $data['amount'] = (int)($amountINR * 100);
        $data['payment_capture'] = 1;
        $data['currency'] = 'INR';
        $data['receipt'] = $order_id . '_' . date("Y_m_d_h_i_sa");

        return $data;
    }

    /**
     * @param $razorPayOrder
     * @param User $user
     * @param DeliveryAddress $deliveryAddress
     * @return array
     */
    private function getRazorPayFields($razorPayOrder, User $user, DeliveryAddress $deliveryAddress): array
    {
        $market = $this->order->user->cart[0]->product->market;

        $fields = array(
            'key_id' => config('services.razorpay.key', ''),
            'order_id' => $razorPayOrder['id'],
            'name' => $market->name,
            'description' => count($this->order->user->cart) ." items",
            'image' => $this->order->user->cart[0]->product->market->getFirstMedia('image')->getUrl('thumb'),
            'prefill' => [
                'name' => $user->name,
                'email' => $user->email,
                'contact' => $user->custom_fields['phone']['value'],
            ],
            'callback_url' => url('payments/razorpay/pay-success',['user_id'=>$user->id,'delivery_address_id'=>$deliveryAddress->id]),

        );

        if (isset($this->coupon)){
            $fields['callback_url'] = url('payments/razorpay/pay-success',['user_id'=>$user->id,'delivery_address_id'=>$deliveryAddress->id, 'coupon_code' => $this->coupon->code]);
        }

        if (!empty($deliveryAddress)) {
            $fields ['notes'] = [
                'delivery_address' => $deliveryAddress->address,
            ];
        }


        if ($this->currency !== 'INR') {
            $fields['display_amount'] = $this->total;
            $fields['display_currency'] = $this->currency;
        }
        return $fields;
    }

    /**
     * @param array $data
     * @return string
     */
    private function getPaymentDescription(array $data): string
    {
        $description = "Id: " . $data['razorpay_payment_id'] . "</br>";
        $description .= trans('lang.order').": " . $data['razorpay_order_id'];
        return $description;
    }

}

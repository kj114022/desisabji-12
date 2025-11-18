<?php

namespace App\Http\Controllers\API;


use App\Models\Payment;
use App\Repositories\PaymentRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Support\Facades\Response;
use Prettus\Repository\Exceptions\RepositoryException;
use Illuminate\Support\Facades\Log;
use Flash;
use App\Criteria\Payments\PaymentsOfUserCriteria;

/**
 * Class PaymentController
 * @package App\Http\Controllers\API
 */
class PaymentAPIController extends Controller
{
    /** @var  PaymentRepository */
    private $paymentRepository;

    public function __construct(PaymentRepositoryInterface $paymentRepo)
    {
        $this->paymentRepository = $paymentRepo;
    }

    /**
     * Display a listing of the Payment.
     * GET|HEAD /payments
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        Log::info("PaymentAPIController :: index with request data: " . json_encode($request->all()));
        try {
            $this->paymentRepository->pushCriteria(new RequestCriteria($request));
            $this->paymentRepository->pushCriteria(new PaymentsOfUserCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $payments = $this->paymentRepository->all();

        return $this->sendResponse($payments->toArray(), 'Payments retrieved successfully');
    }

    /**
     * Display the specified Payment.
     * GET|HEAD /payments/{id}
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
         Log::info("PaymentAPIController :: show for ID: " . $id);
        /** @var Payment $payment */
        if (!empty($this->paymentRepository)) {
            $payment = $this->paymentRepository->findWithoutFail($id);
        }

        if (empty($payment)) {
            return $this->sendError('Payment not found');
        }

        return $this->sendResponse($payment->toArray(), 'Payment retrieved successfully');
    }

    public function byMonth()
    {
        Log::info("PaymentAPIController :: byMonth");
        $payments = [];
        if (!empty($this->paymentRepository)) {
            $payments = $this->paymentRepository->orderBy("created_at",'asc')->all()->map(function ($row) {
                $row['month'] = $row['created_at']->format('M');
                return $row;
            })->groupBy('month')->map(function ($row) {
                return $row->sum('price');
            });
        }
        return $this->sendResponse([array_values($payments->toArray()),array_keys($payments->toArray())], 'Payment retrieved successfully');
    }
}

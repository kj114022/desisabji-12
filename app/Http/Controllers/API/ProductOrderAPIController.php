<?php

namespace App\Http\Controllers\API;


use App\Models\ProductOrder;
use App\Repositories\ProductOrderRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Support\Facades\Response;
use Prettus\Repository\Exceptions\RepositoryException;
use App\Criteria\Orders\OrderEntriesCriteria;
use Illuminate\Support\Facades\Log;
use Flash;

/**
 * Class ProductOrderController
 * @package App\Http\Controllers\API
 */

class ProductOrderAPIController extends Controller
{
    /** @var  ProductOrderRepository */
    private $productOrderRepository;

    public function __construct(ProductOrderRepositoryInterface $productOrderRepo)
    {
        $this->productOrderRepository = $productOrderRepo;
    }

    /**
     * Display a listing of the ProductOrder.
     * GET|HEAD /productOrders
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try{
            $this->productOrderRepository->pushCriteria(new RequestCriteria($request));
            $this->productOrderRepository->pushCriteria(new OrderEntriesCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $productOrders = $this->productOrderRepository->all();

        return $this->sendResponse($productOrders->toArray(), 'Product Orders retrieved successfully');
    }

    /**
     * Display the specified ProductOrder.
     * GET|HEAD /productOrders/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var ProductOrder $productOrder */
        if (!empty($this->productOrderRepository)) {
            $productOrder = $this->productOrderRepository->findWithoutFail($id);
        }

        if (empty($productOrder)) {
            return $this->sendError('Product Order not found');
        }

        return $this->sendResponse($productOrder->toArray(), 'Product Order retrieved successfully');
    }
}

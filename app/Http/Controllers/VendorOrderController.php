<?php

namespace App\Http\Controllers;

use App\Models\VendorOrder;
use App\Http\Requests\StoreVendorOrderRequest;
use App\Http\Requests\UpdateVendorOrderRequest;
use App\Interfaces\VendorOrderRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VendorOrderController extends Controller
{
    private VendorOrderRepositoryInterface $orderRepository;

    public function __construct(VendorOrderRepositoryInterface $orderRepository) 
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse 
    {
        return response()->json([
            'data' => $this->orderRepository->getAllVendorOrders()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreVendorOrderRequest  $request
     * @return \Illuminate\Http\Response
     */
    /*public function store(StoreVendorOrderRequest $request)
    {
        //
    }*/

    public function store(Request $request): JsonResponse 
    {
        $orderDetails = $request->only([
            'client',
            'details'
        ]);

        return response()->json(
            [
                'data' => $this->orderRepository->createVendorOrder($orderDetails)
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VendorOrder  $vendorOrder
     * @return \Illuminate\Http\Response
     */
   /* public function show(VendorOrder $vendorOrder)
    {
        //
    }
*/

    public function show(Request $request): JsonResponse 
    {
        $orderId = $request->route('id');

        return response()->json([
            'data' => $this->orderRepository->getVendorOrderById($orderId)
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VendorOrder  $vendorOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(VendorOrder $vendorOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateVendorOrderRequest  $request
     * @param  \App\Models\VendorOrder  $vendorOrder
     * @return \Illuminate\Http\Response
     */
    /*public function update(UpdateVendorOrderRequest $request, VendorOrder $vendorOrder)
    {
        //
    }*/

    public function update(Request $request): JsonResponse 
    {
        $orderId = $request->route('id');
        $orderDetails = $request->only([
            'client',
            'details'
        ]);

        return response()->json([
            'data' => $this->orderRepository->updateVendorOrder($orderId, $orderDetails)
        ]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VendorOrder  $vendorOrder
     * @return \Illuminate\Http\Response
     */
   /* public function destroy(VendorOrder $vendorOrder)
    {
        //
    }*/

    public function destroy(Request $request): JsonResponse 
    {
        $orderId = $request->route('id');
        $this->orderRepository->deleteVendorOrder($orderId);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}

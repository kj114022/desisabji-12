<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Models\DeliveryAddress;
use App\Repositories\DeliveryAddressRepositoryInterface;
use Flash;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Support\Facades\Log;
use App\Criteria\Addresses\AddressesForUserCriteria;

/**
 * Class DeliveryAddressController
 * @package App\Http\Controllers\API
 */
class DeliveryAddressAPIController extends Controller
{
    /** @var  DeliveryAddressRepository */
    private $deliveryAddressRepository;

    public function __construct(DeliveryAddressRepositoryInterface $deliveryAddressRepo)
    {
        $this->deliveryAddressRepository = $deliveryAddressRepo;
    }

    /**
     * Display a listing of the DeliveryAddress.
     * GET|HEAD /deliveryAddresses
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        Log::info('Fetching delivery addresses for user with request data: ' . json_encode($request->all()));
        try {
            $this->deliveryAddressRepository->pushCriteria(new RequestCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $this->deliveryAddressRepository->pushCriteria(new AddressesForUserCriteria($request));
        $deliveryAddresses = $this->deliveryAddressRepository->all();
        return $this->sendResponse($deliveryAddresses->toArray(), 'Delivery Addresses retrieved successfully');
    }

    /**
     * Display the specified DeliveryAddress.
     * GET|HEAD /deliveryAddresses/{id}
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var DeliveryAddress $deliveryAddress */
        Log::info('Attempting to retrieve delivery address with ID: ' . $id);
        if (!empty($this->deliveryAddressRepository)) {
            $deliveryAddress = $this->deliveryAddressRepository->findWithoutFail($id);
        }

        if (empty($deliveryAddress)) {
            return $this->sendError('Delivery Address not found');
        }

        return $this->sendResponse($deliveryAddress->toArray(), 'Delivery Address retrieved successfully');
    }

    /**
     * Store a newly created DeliveryAddress in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        Log::info('Attempting to store a new delivery address with request data: ' . json_encode($request->all()));
        $uniqueInput = [];
        $otherInput = [];
        $this->validate($request, DeliveryAddress::$rules); 
        if ($request->has('is_default') && $request->get('is_default') == true) {
            $this->deliveryAddressRepository->initIsDefault($request->get('user_id'));
        }   
        // Extract unique and other input fields
        // This assumes 'address' is the unique field and others are additional fields
        if ($request->has('address')) {
            $uniqueInput = $request->only("address");
        } else {
            return $this->sendError('Address is required');
        }
        $otherInput = $request->except("address");
        // Attempt to create or update the delivery address
        try {
            $deliveryAddress = $this->deliveryAddressRepository->updateOrCreate($uniqueInput, $otherInput);

        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($deliveryAddress->toArray(), __('lang.saved_successfully', ['operator' => __('lang.delivery_address')]));
    }

    /**
     * Update the specified DeliveryAddress in storage.
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        Log::info('Attempting to update delivery address with ID: ' . $id . ' and request data: ' . json_encode($request->all()));
        $this->validate($request, DeliveryAddress::$rules);
        if ($request->has('is_default') && $request->get('is_default') == true) {
            $this->deliveryAddressRepository->initIsDefault($request->get('user_id'));
        }   
        // Find the delivery address by ID
        $deliveryAddress = $this->deliveryAddressRepository->findWithoutFail($id);

        if (empty($deliveryAddress)) {
            return $this->sendError('Delivery Address not found');
        }
        $input = $request->all();
        // Attempt to update the delivery address
        if ($request->has('address')) {
            $uniqueInput = $request->only("address");
        } else {
            return $this->sendError('Address is required');
        }
        try {
            $deliveryAddress = $this->deliveryAddressRepository->update($input, $id);
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($deliveryAddress->toArray(), __('lang.updated_successfully', ['operator' => __('lang.delivery_address')]));

    }

    /**
     * Remove the specified Address from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        Log::info('Attempting to delete delivery address with ID: ' . $id);
        // Find the delivery address by ID

        $existingAddress = $this->deliveryAddressRepository->findWhere([
            'id' => $id
        ])->first();

        if (!$existingAddress) {
            Log::error('Address not found', [
                'id' => $id
            ]);
            return response()->json(['error' => 'Address not found for id :' . $id], 400);
        }else{
            $existingAddress->status=0;
            $this->deliveryAddressRepository->update($existingAddress->toArray(), $id);
            Log::info('Address marked as inactive', [
                'id' => $id
            ]);
        }
      
        $response = [
            'success' => true,
            'message' => "Delivery Address deleted successfully for id: " . $id
        ];
        return $response;

    }
    
    public function getAddressesByUser($userId)
    {
        log::info('Attempting to retrieve delivery addresses for user with ID: ' . $userId);
        if (empty($userId)) {
            return $this->sendError('User ID is required');
        } 
        if (!empty($this->deliveryAddressRepository)) {
            $deliveryAddress = $this->deliveryAddressRepository->getAddressesByUserId($userId);
        }
    
        if (empty($deliveryAddress)) {
            return $this->sendError('Delivery Address not found for user ID: ' . $userId);
        }

        return $this->sendResponse($deliveryAddress, 'Delivery Address retrieved successfully');
    }

}
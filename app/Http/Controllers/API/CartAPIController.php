<?php

namespace App\Http\Controllers\API;


use App\Http\Requests\CreateCartRequest;
use App\Http\Requests\CreateFavoriteRequest;
use App\Models\Cart;
use App\Repositories\CartRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Support\Facades\Response;
use Prettus\Repository\Exceptions\RepositoryException;
use Flash;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Criteria\Carts\CartsOfUserCriteria;
use Illuminate\Support\Facades\Validator;

/**
 * Class CartController
 * @package App\Http\Controllers\API
 */

class CartAPIController extends Controller
{
    /** @var  CartRepository */
    private $cartRepository;

    public function __construct(CartRepositoryInterface $cartRepo)
    {
        $this->cartRepository = $cartRepo;
    }

    /**
     * Display a listing of the Cart.
     * GET|HEAD /carts
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        Log::info('Fetching carts Request data: ' . json_encode($request->all()));
        if (empty($this->cartRepository)) {
            Log::error('Cart repository is not initialized');
            $response = [
                'message' => 'Cart repository is not initialized',
                'status_code' => 500
            ];
            return $response;
        }
        try{
            $this->cartRepository->pushCriteria(new RequestCriteria($request));
            $this->cartRepository->pushCriteria(new CartsOfUserCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $carts = $this->cartRepository->all();
        if ($carts->isEmpty()) {
            Log::info('No carts found for the user');
            $response = [
                'message' => 'No carts found',
                'status_code' => 404
            ];
            return $response;
        }

        return $this->sendResponse($carts->toArray(), 'Carts retrieved successfully');
    }

    /**
     * Display a listing of the Cart.
     * GET|HEAD /carts
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function count(Request $request)
    {
        Log::info('Attempting to count carts for user with request data: ' . json_encode($request->all()));
        $count = 0;
        try{
            $this->cartRepository->pushCriteria(new RequestCriteria($request));
            $carts = $this->cartRepository->pushCriteria(new CartsOfUserCriteria($request));
            $carts = $carts->all();
            $count = $carts->count();
            Log::info('Carts count retrieved successfully: ' . $count);
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
       
        if ($count === 0) {
            Log::info('No carts found for the user');
            $response = [
                'message' => 'No carts found',
                'status_code' => 404
            ];
            return $response;
        }
        
        // Return the count as a response
        return $this->sendResponse($count, 'Carts count retrieved successfully');
    }

    /**
     * Display the specified Cart.
     * GET|HEAD /carts/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var Cart $cart */
        Log::info('Attempting to retrieve cart with ID: ' . $id);
        $cart = null;
        if (!empty($this->cartRepository)) {
                 $cart = $this->cartRepository->findWhere([
                'id' => $id
                 ])->first();
        }

        if (empty($cart)) {
            Log::error('Cart not found', ['id' => $id]);
            $response = [
                'message' => 'Cart not found',
                'status_code' => 404
            ];
            return $this->sendError($response, 404);
        }

        return $this->sendResponse($cart->toArray(), 'Cart retrieved successfully');
    }
    /**
     * Store a newly created Cart in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        Log::info('Attempting to store cart with request data: ' . json_encode($request->all()));
        // Validate required fields

        $data = $request->validate([
             'user_id' => 'required|exists:users,id',
             'product_id' => 'required',
             'quantity' => 'required|integer',
             'price' => 'required|numeric',
             'city_id' => 'required|exists:cities,id',
             'product_city_id' => 'required|exists:cities,id',
        ]);

        Log::debug('Validated data: ' . json_encode($data));
           // Check if the cart already exists for the user and product
        $existingCart = $this->cartRepository->findWhere([
            'user_id' => $data['user_id'],
            'product_id' => $data['product_id']
        ])->first();

        if ($existingCart) {

            Log::info('Cart already exists for user and product', [
                'user_id' => $data['user_id'],
                'product_id' => $data['product_id'],
                'cart_id' => $existingCart->id
             ]);
            $newQuantity = $existingCart->quantity + $data['quantity'];
            $existingCart->quantity = $newQuantity;
            $existingCart->price += (double) $data['price'];
            $existingCart->product_city_id = $data['product_city_id'];
            $existingCart->save();
            $response = [
                'message' => 'Cart already exists for user and product',
                'user_id' => $data['user_id'],
                'product_id' => $data['product_id'],
                'id' => $existingCart->id,
                'quantity' => $existingCart->quantity,
                'price' => $existingCart->price,
                'status_code' => 200
            ];
            return response()->json($response, 200);
        }
        // Generate a unique row_id for the cart item
        $input = $request->all();
        if (!isset($input['id'])) {
            $id = Cart::max('id') + 1;
            Log::debug('No ID provided, generating new ID: ' . $id);
            $rowid = md5($id);
            $input['row_id'] = $rowid;
            $input['id'] = $id; // Set the id to the next available id
        }else {
            $rowid = md5($input[ 'id' ]);
            $input['row_id'] = $rowid;
        }
        
        try {
            if(isset($input['reset']) && $input['reset'] == '1'){
                // delete all items in the cart of current user
                $this->cartRepository->deleteWhere(['user_id'=> $input['user_id']]);
            }
            $cart = $this->cartRepository->create($input);
            if (isset($input['options']) && is_array($input['options'])) {
                $cart->options = $input['options'];
                $cart->save();
            }
           
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }
        $cart = $this->cartRepository->findWhere(['row_id' => $rowid])->first();
        return $this->sendResponse($cart, __('lang.saved_successfully',['operator' => __('lang.cart')]));
    }

    /**
     * Update the specified Cart in storage.
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        Log::info('Attempting to update cart with ID: ' . $id);
        if (empty($id)) {
            Log::error('Cart ID is empty');
            $response = [
                'message' => 'Cart ID is required',
                'status_code' => 400
            ];
            return $response;
        }
        // Validate required fields
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer',
        ]);
        if($validator->fails()) {
            Log::error('Validation failed: ' . $validator->errors()->first());
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

         // Check if the cart exists
        $existingCart = $this->cartRepository->findWhere([
            'id' => $id
        ])->first();
        if (!$existingCart) {
            Log::error('Cart not found for update', ['id' => $id]);
            $response = [
                'message' => 'Cart not found',
                'status_code' => 404
            ];
            return $response;
        }
       
        $input = $request->all();

        try {
           
            $cart = $this->cartRepository->update($input, $id);

        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        if (isset($input['options']) && is_array($input['options'])) {
            $cart->options = $input['options'];
            $cart->save();
        }
        $cart = $this->cartRepository->findWithoutFail($id);      
        return $this->sendResponse($cart, 'Cart updated successfully');
    }

    /**
     * Remove the specified Favorite from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        Log::info('Attempting to delete cart with ID: ' . $id);
        if (empty($id)) {
            Log::error('Cart ID is empty');
            $response = [
                'message' => 'Cart ID is required',
                'status_code' => 400
            ];
            return $response;
        }
        $existingCart = $this->cartRepository->findWhere([
            'id' => $id
        ])->first();
        if (!$existingCart) {
            Log::error('Cart not found for deletion', ['id' => $id]);
            $response = [
                'message' => 'Cart not found',
                'status_code' => 404
            ];
            return $this->sendError($response, 404);
        }
       // Check if the cart exists

        $cart = $this->cartRepository->deleteById($id);
        $response = [
            'message' => 'Cart deleted successfully',
            'status_code' => 200
        ];
        return $response;
    }


    public function resetCart(Request $request)
    {
        $userId = $request->input('user_id');
        if (empty($userId)) {
            return response()->json(['error' => 'User ID is required'], 400);
        }

        // Delete all items in the cart for the specified user
        $this->cartRepository->deleteWhere(['user_id' => $userId]);

        return response()->json(['message' => 'Cart reset successfully for user ID: ' . $userId], 200);
    }

}

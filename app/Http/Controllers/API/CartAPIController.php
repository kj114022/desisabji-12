<?php

namespace App\Http\Controllers\API;


use App\Http\Requests\CreateCartRequest;
use App\Http\Requests\CreateFavoriteRequest;
use App\Models\Cart;
use App\Models\ProductsCity;
use App\Repositories\CartRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criteria\Carts\ProductsOfCartCriteria;
use Illuminate\Support\Facades\Response;
use Prettus\Repository\Exceptions\RepositoryException;
use Flash;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class CartController
 * @package App\Http\Controllers\API
 */

class CartAPIController extends Controller
{
    /** @var  CartRepository */
    private $cartRepository;
    private $productRepository;
    public function __construct(CartRepository $cartRepo, ProductRepository $productRepo)
    {
        $this->cartRepository = $cartRepo;
        $this->productRepository = $productRepo;
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

        try{
            $this->cartRepository->pushCriteria(new ProductsOfCartCriteria($request));
            $this->cartRepository->pushCriteria(new RequestCriteria($request));
            $this->cartRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
      $carts = $this->cartRepository->all();
       foreach($carts as $item){
            Log::debug('single saleable product in cart :'. $item->product->id);
            if($item->product != null && !$product->singleSaleable){
             $item->product->quantity = 1;
            }
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
        try{
            $this->cartRepository->pushCriteria(new RequestCriteria($request));
            $this->cartRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $count = $this->cartRepository->count();

        return $this->sendResponse($count, 'Count retrieved successfully');
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
        if (!empty($this->cartRepository)) {
            $cart = $this->cartRepository->findWithoutFail($id);
        }

        if (empty($cart)) {
            return $this->sendError('Cart not found');
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
        $input = $request->all();
        $rowid = md5($input[ 'id' ]);
          $input['row_id'] = $rowid;
        try {
            if(isset($input['reset']) && $input['reset'] == '1'){
                // delete all items in the cart of current user
                $this->cartRepository->deleteWhere(['user_id'=> $input['user_id']]);
            }
			$cart = $this->cartRepository->findByField('product_id',$input['product_id']);
			Log::info('before add cart::'.json_encode($cart));
			if($cart != null && !$product->singleSaleable){
				 $cart = $this->cartRepository->update($input, $cart->id);
			}else{
				 $cart = $this->cartRepository->create($input);
                 $cart->quantity=1;
			}
           
        //    $cart->row_id =$rowid ;
        //    $cart->update();

        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($cart->toArray(), __('lang.saved_successfully',['operator' => __('lang.cart')]));
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
        $cart = $this->cartRepository->findWithoutFail($id);
         Log::info('before update cart::'.json_encode($cart));
        if (empty($cart)) {
            return $this->sendError('Cart not found');
        }
        $input = $request->all();

        try {
//            $input['options'] = isset($input['options']) ? $input['options'] : [];
             $product = $this->productRepository->findWithoutFail($cart->product_id);
			 if(!$product->singleSaleable){                 
				 $cart = $this->cartRepository->update($input, $id);
			 }else{
                   $cart->quantity=1;
				  return $this->sendResponse($cart->toArray(),'Only one quantity is allowed for this product');
			 }
            
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }
       Log::info('after update cart::'.json_encode($cart));
        return $this->sendResponse($cart->toArray(), __('lang.saved_successfully',['operator' => __('lang.cart')]));
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
        $cart = $this->cartRepository->findWithoutFail($id);

        if (empty($cart)) {
            return $this->sendError('Cart not found');

        }

        $cart = $this->cartRepository->delete($id);

        return $this->sendResponse($cart, __('lang.deleted_successfully',['operator' => __('lang.cart')]));

    }
     public function cleanCart($id)
        {
            $carts = $this->cartRepository->findWhere([

                                                           'user_id'=>$id
                                                       ]);

            if (empty($carts)) {
                return $this->sendError('Cart not found');

            }
            foreach($carts as $cart){
               $cart = $this->cartRepository->delete($cart->id);
            }
            return $this->sendResponse($carts, __('lang.deleted_successfully',['operator' => __('lang.cart')]));

        }

}

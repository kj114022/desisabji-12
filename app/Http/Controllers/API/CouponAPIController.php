<?php

namespace App\Http\Controllers\API;


use App\Criteria\Coupons\ValidCriteria;
use App\Models\Coupon;
use App\Models\CouponRedeem;
use App\Repositories\CouponRepositoryInterface;
use App\Repositories\CouponRedeemRepositoryInterface;
use App\Repositories\CustomFieldRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Support\Facades\Response;
use Prettus\Repository\Exceptions\RepositoryException;
use Illuminate\Support\Facades\Log;

/**
 * Class CouponController
 * @package App\Http\Controllers\API
 */

class CouponAPIController extends Controller
{
    /** @var  CouponRepository */
    private $couponRepository;

    private $couponRedeemRepository;

    public function __construct(CouponRepositoryInterface $couponRepo, CouponRedeemRepositoryInterface $couponRedeemRepo        )
    {
        parent::__construct();
        $this->couponRepository = $couponRepo;
        $this->couponRedeemRepository = $couponRedeemRepo;
    } 

    /**
     * Display a listing of the Coupon.
     * GET|HEAD /coupons
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
         Log::debug('coupon api :: ');
        /** @var Coupon $coupon */
        try{
            $this->couponRepository->pushCriteria(new RequestCriteria($request));
            //$this->couponRepository->pushCriteria(new LimitOffsetCriteria($request));
            $this->couponRepository->pushCriteria(new ValidCriteria());
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $coupons = $this->couponRepository->all();
            foreach ($coupons as $coupon) {
                 Log::debug('Inside coupon name '.$coupon);
            }

        return $this->sendResponse($coupons->toArray(), 'Coupons retrieved successfully');
    }

    /**
     * Display the specified Coupon.
     * GET|HEAD /coupons/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
         Log::debug('coupon api show :: '.$id);
        /** @var Coupon $coupon */
        $coupon = null;
        if (!empty($this->couponRepository)) {
            // Apply the ValidCriteria to ensure only valid coupons are fetched
            $coupon = $this->couponRepository->where('code',$id)->where('enabled', 1)->first();
        }

        if (empty($coupon)) {
            return $this->sendError('Coupon not found');
        }
        $response = [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'discount' => $coupon->discount,
            'description' => $coupon->description,
            'enabled' => $coupon->enabled,
            'expires_at' => $coupon->expires_at,
            'discount_type' => $coupon->discount_type,
            'max_discount' => $coupon->max_discount,
        ];
        return $this->sendResponse($response, 'Coupon retrieved successfully');
    }

    /**
     * Store a newly created Coupon in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        Log::debug('coupon redeem :: '. json_encode($request->all()));
        $input = $request->all();
        /** @var Coupon $coupon */
        $coupon = null;
        if (!empty($this->couponRepository)) {
            // Apply the ValidCriteria to ensure only valid coupons are fetched
            $coupon = $this->couponRepository->where('code',$input['coupon_code'])->where('enabled', 1)->first();
        }

        if (empty($coupon)) {
            return $this->sendError('Coupon not found');
        }

        // Redeem the coupon logic here
        // Save the coupon redeem record        
        $couponRedeem = $this->couponRedeemRepository->create([
            'coupon_code' => $coupon->code,
            'user_id' => $request->user()->id, // Assuming user is authenticated
            'redeem_amount' => $coupon->discount, // Assuming discount is the amount to redeem
            'order_id' => $request->input('order_id'), // Assuming order_id is passed in the request
            'status' => '1', // Assuming '1' means redeemed
        ]);
       if (!$couponRedeem->save()) {
            return $this->sendError('Failed to redeem coupon');
        }
        $response= [
            'coupon_code' => $couponRedeem->coupon_code,
            'user_id' => $couponRedeem->user_id,
            'redeem_amount' => $couponRedeem->redeem_amount,
            'order_id' => $couponRedeem->order_id,
            'status' => 'redeemed',
        ];
        return $this->sendResponse($response, 'Coupon redeemed successfully');
    }
}

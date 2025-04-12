<?php

namespace App\Http\Controllers\API;


use App\Criteria\Wallets\ValidCriteria;
use App\Models\Wallet;
use App\Repositories\WalletRepository;
use App\Models\WalletPayment;
use App\Repositories\WalletPaymentRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Support\Facades\Response;
use Prettus\Repository\Exceptions\RepositoryException;
use Flash;
use Illuminate\Support\Facades\Log;

/**
 * Class WalletAPIController
 * @package App\Http\Controllers\API
 */

class WalletAPIController extends Controller
{
    /** @var  WalletRepository */
    private $walletRepository;

    /** @var  WalletPaymentRepository */
    private $walletPaymentRepository;

    public function __construct(WalletRepository $walletRepo,WalletPaymentRepository $walletPaymentRepo)
    {
        $this->walletRepository = $walletRepo;
        $this->walletPaymentRepository = $walletPaymentRepo;
    }

    /**
     * Display a listing of the Wallet.
     * GET|HEAD /wallets
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
         Log::debug('wallet api :: ');
        /** @var Wallet $wallet */
        try{
            $this->walletRepository->pushCriteria(new RequestCriteria($request));
            $this->walletRepository->pushCriteria(new LimitOffsetCriteria($request));
            $this->walletRepository->pushCriteria(new ValidCriteria());
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $wallets = $this->walletRepository->all();
            foreach ($wallets as $wallet) {
                 Log::debug('Inside wallet name '.$wallet);
            }

        return $this->sendResponse($wallets->toArray(), 'wallets retrieved successfully');
    }

    /**
     * Display the specified wallet.
     * GET|HEAD /wallets/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
         Log::debug('wallet api show :: '.$id);
        /** @var Wallet $wallet */
        if (!empty($this->walletRepository)) {
            $wallet = $this->walletRepository->findWithoutFail($id);
        }

        if (empty($wallet)) {
            return $this->sendError('wallet not found');
        }
        
         $wallet->active = true;
          Log::debug('Wallet :: '.$wallet->toArray());
        return $this->sendResponse($wallet->toArray(), 'Wallet retrieved successfully');
    }


    
    /**
     * Display the specified wallet.
     * GET|HEAD /wallets/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userWallet($id)
    {
         Log::debug('wallet userWallet  :: '.$id);
        /** @var Wallet $wallet */
        if (!empty($this->walletRepository)) {
            $wallet = $this->walletRepository->findWithoutFail($id,'customer_id');
        }

        if (empty($wallet)) {
            return $this->sendError('wallet not found');
        }
        
         $wallet->active = true;
          Log::debug('Wallet :: '.$wallet->toArray());
        return $this->sendResponse($wallet->toArray(), 'Wallet retrieved successfully');
    }
}

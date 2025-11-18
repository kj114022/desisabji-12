<?php

namespace App\Http\Controllers;

use App\Repositories\OrderRepositoryInterface;
use App\Repositories\PaymentRepositoryInterface;
use App\Repositories\MarketRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{

    /** @var  OrderRepositoryInterface */
    private $orderRepository;


    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /** @var  MarketRepositoryInterface */
    private $marketRepository;
    /** @var  PaymentRepositoryInterface */
    private $paymentRepository;

    public function __construct(OrderRepositoryInterface $orderRepo, UserRepositoryInterface $userRepo, PaymentRepositoryInterface $paymentRepo, MarketRepositoryInterface $marketRepo)
    {
    
        $this->orderRepository = $orderRepo;
        $this->userRepository = $userRepo;
        $this->marketRepository = $marketRepo;
        $this->paymentRepository = $paymentRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Log::info('inside DashboardController@index');
        $ordersCount = $this->orderRepository->count();
        $membersCount = $this->userRepository->count();
        $marketsCount = $this->marketRepository->count();
        $markets = $this->marketRepository->limit(4)->get(4);
        $earning = $this->paymentRepository->all()->sum('price');
        $ajaxEarningUrl = route('payments.byMonth',['api_token'=>auth()->user()->api_token]);
       
        return view('dashboard.index')
            ->with("ajaxEarningUrl", $ajaxEarningUrl)
            ->with("ordersCount", $ordersCount)
            ->with("marketsCount", $marketsCount)
            ->with("markets", $markets)
            ->with("membersCount", $membersCount)
            ->with("earning", $earning);
    }
}

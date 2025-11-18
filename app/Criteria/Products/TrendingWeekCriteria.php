<?php
/**
 * File name: TrendingWeekCriteria.php
 * Last modified: 2020.05.04 at 09:04:18

 *
 */

namespace App\Criteria\Products;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Facades\Log;
/**
 * Class TrendingWeekCriteria.
 *
 * @package namespace App\Criteria\Products;
 */
class TrendingWeekCriteria implements CriteriaInterface
{
    /**
     * @var array
     */
    private $request;

    /**
     * TrendingWeekCriteria constructor.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply criteria in query repository
     *
     * @param string $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        
      /*  return  $model
            ->select('products.*', DB::raw('count(products.id) as product_count'))
            ->join('feature_products', 'products.id', '=', 'feature_products.product_id')
            ->get();*/
        return $model->join('feature_products', 'products.id', '=', 'feature_products.product_id')
                -> join('product_cities', 'product_cities.product_id', '=', 'products.id')
                ->groupBy('products.id')
                ->orderBy('product_count', 'desc')
                ->select("product_cities.price as city_price","product_cities.discount_price as city_discount","product_cities.deliverable as city_deliverable",'products.*', DB::raw('count(products.id) as product_count'))
                ->where('feature_products.active','1');
       
          /* 
       if ($this->request->has(['myLon', 'myLat', 'areaLon', 'areaLat'])) {

            $myLat = $this->request->get('myLat', 0);
            $myLon = $this->request->get('myLon', 0);
            $areaLat = $this->request->get('areaLat', 0);
            $areaLon = $this->request->get('areaLon', 0);

            return $model->join('markets', 'markets.id', '=', 'products.market_id')->select(DB::raw("SQRT(
            POW(69.1 * (markets.latitude - $myLat), 2) +
            POW(69.1 * ($myLon - markets.longitude) * COS(markets.latitude / 57.3), 2)) AS distance, SQRT(
            POW(69.1 * (markets.latitude - $areaLat), 2) +
            POW(69.1 * ($areaLon - markets.longitude) * COS(markets.latitude / 57.3), 2)) AS area, count(products.id) as product_count"), 'products.*')
                ->join('product_orders', 'products.id', '=', 'product_orders.product_id')
                ->whereBetween('product_orders.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->where('markets.active','1')
                ->orderBy('product_count', 'desc')
                ->orderBy('area')
                ->groupBy('products.id');
        } else {
            return $model->join('product_orders', 'products.id', '=', 'product_orders.product_id')
                ->join('markets', 'markets.id', '=', 'products.market_id')
                ->whereBetween('product_orders.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->where('markets.active','1')
                ->groupBy('products.id')
                ->orderBy('product_count', 'desc')
                ->select('products.*', DB::raw('count(products.id) as product_count'));
        }*/
    }
}

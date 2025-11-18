<?php
namespace App\Criteria\Products;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Facades\Log;
/**
 * Class ProductsOfCityCriteria.
 *
 * @package namespace App\Criteria\Products;
 */
class ProductPriceForCityCriteria implements CriteriaInterface
{
    /**
     * @var array
     */
    private $request;

    /**
     * NearCriteria constructor.
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
        Log::info('Applying ProductPriceForCityCriteria', ['request' => $this->request->all()]);
        // Check if the request has 'city_id'
        if ($this->request->has('city_id') && $this->request->has('product_id')) {
            $cityId = $this->request->get('city_id');
            $productId = $this->request->get('product_id');
            Log::info('Apply ProductPriceForCityCriteria', ['city_id' => $cityId, 'product_id' => $productId]);
            return $model->where('product_cities.city_id', $cityId)
                ->where('product_cities.product_id', $productId);
        } else {
            return $model;
        }
    }
}


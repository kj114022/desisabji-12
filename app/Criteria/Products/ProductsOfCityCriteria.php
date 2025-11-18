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
class ProductsOfCityCriteria implements CriteriaInterface
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
        Log::info('Applying ProductsOfCityCriteria', ['request' => $this->request->all()]);
        // Check if the request has 'city_id'
        if ($this->request->has('city_id') && $this->request->has('category')) {
            Log::debug('City ID and category provided, filtering products accordingly');
            // Get the city_id and category from the request
            $cityId = $this->request->get('city_id');
            $category = array($this->request->get('category'));
            Log::info('Apply ProductsOfCategoriesCriteria', ['category' => $category]);
            if (in_array('0', $category)) { // means all fields
                return $model;
            }
            Log::debug('city for product :: '. $cityId );
            return $model->join('product_cities', 'product_cities.product_id', '=', 'products.id')
                ->where('product_cities.city_id', $cityId)
                ->groupBy("products.id")
                ->select("product_cities.id as product_city_id","product_cities.price as city_price","product_cities.discount_price as city_discount","product_cities.deliverable as city_deliverable", "products.*")
                ->whereIn('products.category_id', $category);
              ///  ->orderBy('closed');
        } else if ($this->request->has('city_id') && !$this->request->has('category')) {
            Log::debug('No category provided, returning all products for city_id: ' . $this->request->get('city_id'));
            return $model->join('product_cities', 'product_cities.product_id', '=', 'products.id')
                ->where('product_cities.city_id', $this->request->get('city_id'))
                ->groupBy("products.id")
                ->select("product_cities.id as product_city_id","product_cities.price as city_price","product_cities.discount_price as city_discount","product_cities.deliverable as city_deliverable", "products.*");
        } else {
            Log::debug('No city_id or category provided, returning all products');
            return $model->select('products.*');
        }
    }
}

<?php

namespace App\Criteria\Products;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Facades\Log;

/**
 * Class ProductSearchCriteria.
 *
 * @package namespace App\Criteria\Products;
 */
class ProductSearchCriteria implements CriteriaInterface
{
    /**
     * @var array
     */
    private $request;

    /**
     * ProductSearchCriteria constructor.
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
        Log::info('Applying ProductSearchCriteria', ['request' => $this->request->all()]);
        if (!$this->request->has('term')) {
            return [];
        } else {
            $term = $this->request->get('term');
            Log::info('Apply ProductSearchCriteria', ['term' => $term]);

            if ($this->request->has('city_id')) {
            Log::debug('No category provided, returning all products for city_id: ' . $this->request->get('city_id'));
            return $model->join('product_cities', 'product_cities.product_id', '=', 'products.id')
                ->where('product_cities.city_id', $this->request->get('city_id'))
                ->groupBy("products.id")
                ->select("product_cities.id as product_city_id","product_cities.price as city_price","product_cities.discount_price as city_discount","product_cities.deliverable as city_deliverable", "products.*")
                ->where(function ($query) use ($term) {
                $query->where('products.name', 'LIKE', '%' . $term . '%')
                    ->orWhere('products.description', 'LIKE', '%' . $term . '%');
                   /*
                    ->orWhereHas('custom_fields', function ($q) use ($term) {
                        $q->where('value', 'LIKE', '%' . $term . '%');
                    });
                   */
            })->where('status', 1);
            }
            else {
                return $model->where(function ($query) use ($term) {
                    $query->where('name', 'LIKE', '%' . $term . '%')
                        ->orWhere('description', 'LIKE', '%' . $term . '%');
                    /*
                        ->orWhereHas('custom_fields', function ($q) use ($term) {
                            $q->where('value', 'LIKE', '%' . $term . '%');
                        });
                    */
                })->where('status', 1);
            }
         

        }
    }
}

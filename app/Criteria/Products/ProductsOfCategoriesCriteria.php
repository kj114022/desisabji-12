<?php

namespace App\Criteria\Products;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Facades\Log;

/**
 * Class ProductsOfCategoriesCriteria.
 *
 * @package namespace App\Criteria\Products;
 */
class ProductsOfCategoriesCriteria implements CriteriaInterface
{
    /**
     * @var array
     */
    private $request;

    /**
     * ProductsOfFieldsCriteria constructor.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        Log::info('Applying ProductsOfCategoriesCriteria', ['request' => $this->request->all()]);
      
        if (!$this->request->has('category')) {
            return $model;
        } else {
            $category = array($this->request->get('category'));
           
            Log::info('Apply ProductsOfCategoriesCriteria', ['category' => $category]);
            if (in_array('0', $category)) { // means all fields
                return $model;
            }
            return $model->whereIn('category_id', $category);
        }
    }
}

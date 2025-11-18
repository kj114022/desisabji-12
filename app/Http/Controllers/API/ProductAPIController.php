<?php
/**
 * File name: ProductAPIController.php
 * Last modified: 2020.05.04 at 09:04:19

 *
 */

namespace App\Http\Controllers\API;


use App\Criteria\Products\NearCriteria;
use App\Criteria\Products\ProductsOfCategoriesCriteria;
use App\Criteria\Products\ProductsOfFieldsCriteria;
use App\Criteria\Products\ProductsOfCityCriteria;
use App\Criteria\Products\TrendingWeekCriteria;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Repositories\CustomFieldRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\UploadRepositoryInterface;
use Flash;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Support\Facades\Log;
use App\Criteria\Products\ProductPriceForCityCriteria;
use App\Repositories\ProductsCityRepositoryInterface;
use App\Criteria\Products\ProductSearchCriteria;

/**
 * Class ProductController
 * @package App\Http\Controllers\API
 */
class ProductAPIController extends Controller
{
    /** @var  ProductRepository */
    private $productRepository;
    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;
    /**
     * @var UploadRepository
     */
    private $uploadRepository;

    private $productsCityRepository;


    public function __construct(ProductRepositoryInterface $productRepo, CustomFieldRepositoryInterface $customFieldRepo, UploadRepositoryInterface $uploadRepo, ProductsCityRepositoryInterface $productsCityRepo)
    {
        parent::__construct();
        $this->productRepository = $productRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
        $this->productsCityRepository = $productsCityRepo;
    }

    /**
     * Display a listing of the Product.
     * GET|HEAD /products
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        log::debug('ProductAPIController::index' . json_encode($request->all()));
        // Apply criteria for products
        try{
            $this->productRepository->pushCriteria(new RequestCriteria($request));
            $this->productRepository->pushCriteria(new ProductsOfFieldsCriteria($request));
          
            if ($request->get('trending', null) == 'week') {
                $this->productRepository->pushCriteria(new TrendingWeekCriteria($request));
            } else {
              //  $this->productRepository->pushCriteria(new NearCriteria($request));
                  $this->productRepository->pushCriteria(new ProductsOfCityCriteria($request));
            }

//            $this->productRepository->orderBy('closed');
//            $this->productRepository->orderBy('area');
              $products = $this->productRepository->all();
           
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($products->toArray(), 'Products retrieved successfully');
    }

    /**
     * Display a listing of the Product.
     * GET|HEAD /products/categories
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function categories(Request $request)
    {
        Log::debug('ProductAPIController::categories'   . json_encode($request->all()));
        // Apply criteria for categories
        try{
            $this->productRepository->pushCriteria(new RequestCriteria($request));
        //    $this->productRepository->pushCriteria(new ProductsOfFieldsCriteria($request));
           if (!$request->has('city_id')) {
                $this->productRepository->pushCriteria(new ProductsOfCategoriesCriteria($request));
            } else {
              //  $this->productRepository->pushCriteria(new NearCriteria($request));
                  $this->productRepository->pushCriteria(new ProductsOfCityCriteria($request));
            }
           
          
            $products = $this->productRepository->all();

        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($products->toArray(), 'Products retrieved successfully');
    }

    /**
     * Display the specified Product.
     * GET|HEAD /products/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        Log::debug('ProductAPIController::show '.$id);
        /** @var Product $product */
        
        if (!empty($this->productRepository)) {
            
            $cityId = $request->query("city_id");
            $product = $this->productRepository->productByCity($id,$cityId);           
           //$product = $this->productRepository->findWithoutFail($id);
        }

        if (empty($product)) {
            return $this->sendError('Product not found');
        }

        return $this->sendResponse($product, 'Product retrieved successfully');
    }

    /**
     * Store a newly created Product in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->productRepository->model());
        try {
            $product = $this->productRepository->create($input);
            $product->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));
            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($product, 'image');
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($product->toArray(), __('lang.saved_successfully', ['operator' => __('lang.product')]));
    }

    /**
     * Update the specified Product in storage.
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $product = $this->productRepository->findWithoutFail($id);

        if (empty($product)) {
            return $this->sendError('Product not found');
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->productRepository->model());
        try {
            $product = $this->productRepository->update($input, $id);

            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($product, 'image');
            }
            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $product->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($product->toArray(), __('lang.updated_successfully', ['operator' => __('lang.product')]));

    }

    /**
     * Remove the specified Product from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $product = $this->productRepository->findWithoutFail($id);

        if (empty($product)) {
            return $this->sendError('Product not found');
        }

        $product = $this->productRepository->delete($id);

        return $this->sendResponse($product, __('lang.deleted_successfully', ['operator' => __('lang.product')]));

    }

       /**
     * Display the specified Product.
     * GET|HEAD /products/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function productById(Request $request, $id)
    {
        Log::debug('ProductAPIController::productById '.$id);
        /** @var Product $product */
        
        if (!empty($this->productRepository)) {
                     
           $cityId = $request->query("city_id");
           if (!empty($cityId)) {
               $product = $this->productRepository->productByCity($id,$cityId);
           }else {
               $product = $this->productRepository->findWithoutFail($id);
           }
        }

        if (empty($product)) {
            return $this->sendError('Product not found');
        }

        return $this->sendResponse($product, 'Product retrieved successfully');
    }

    public function productPriceForCity(Request $request)
    {
        Log::debug('ProductAPIController::productPriceForCity ' . json_encode($request->all()));
        /** @var Product $product */
        $products = null;
        $response = [];
        if (!empty($this->productsCityRepository)) {
           $products= $this->productsCityRepository->pushCriteria(new ProductPriceForCityCriteria($request));
           $productForCity = $products->first();
           $response = [
            'product_city_id' => $productForCity->id,
            'city_price' => $productForCity->price,
            'city_discount' => $productForCity->discount_price,
            'city_deliverable' => $productForCity->deliverable,
            'product_id' => $productForCity->product_id,
            'city_id' => $productForCity->city_id,
           ];
           
         }

        if (empty($products)) {
            return $this->sendError('Product not found');
        }
       
        return $this->sendResponse($response, 'Product retrieved successfully');
    }

    /**
     * Display a listing of the Product.
     * GET|HEAD /products/search
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        Log::debug('ProductAPIController::search ' . json_encode($request->all()));
        $products = $this->productRepository->pushCriteria(new ProductSearchCriteria($request))->all();
      
        if (empty($products)) {
            return $this->sendError('No products found');
        }

        return $this->sendResponse($products, 'Products retrieved successfully');
    }   
}

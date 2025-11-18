<?php
/**
 * File name: ProductRepository.php
 * Last modified: 2020.06.07 at 07:02:57

 *
 */

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\ProductRepositoryInterface;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Container\Container;

/**
 * Class ProductRepository
 * @package App\Repositories
 * @version August 29, 2019, 9:38 pm UTC
 *
 * @method Product findWithoutFail($id, $columns = ['*'])
 * @method Product find($id, $columns = ['*'])
 * @method Product first($columns = ['*'])
 */
class ProductRepository extends BaseRepository implements 
    ProductRepositoryInterface
{

    use CacheableRepository;
    protected $app;

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'price',
        'discount_price',
        'description',
        'capacity',
        'package_items_count',
        'deliverable',
        'unit',
        'featured',
        'market_id',
        'category_id',
        'custom_fields' // Added for custom fields search
    ];

    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Product $model)
    {
        $this->model = $model;
        $this->app = Container::getInstance();
        Log::info('ProductRepository initialized', ['version' => $this->app->version()]);
        Log::info('App initialized', ['version' => config('app.version')]);
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Product::class;
    }

    /**
     * get my products
     **/
    public function myProducts()
    {
        return Product::join("user_markets", "user_markets.market_id", "=", "products.market_id")
            ->where('user_markets.user_id', auth()->id())->get();
    }

    public function groupedByMarkets()
    {
        Log::debug('productrepository.groupedByMarkets ');
        $products = [];
        foreach ($this->all() as $model) {
            Log::debug('product market '.$model->market);
            if(!empty($model->market)){
            $products[$model->market->name][$model->id] = $model->name;
           }
        }
        return $products;
    }

    public function productByCity($productId,$cityId){

        $productDetail = Product::join('product_cities', 'product_cities.product_id', '=', 'products.id')->select("product_cities.id as product_city_id", "product_cities.price as city_price","product_cities.discount_price as city_discount","product_cities.deliverable as city_deliverable", "products.*")
        ->where('product_cities.product_id',$productId)
        ->where('product_cities.city_id',$cityId)->get(0);
        if ($productDetail == null) {
            return $model;
        }
        return $productDetail;

    }

    public function productByCategory($productId,$categoryId){

        $productDetail = Product::join('product_categories', 'product_categories.product_id', '=', 'products.id')
            ->select("product_categories.category_id", "products.*")
            ->where('product_categories.product_id', $productId)
            ->where('product_categories.category_id', $categoryId)->get(0);
        if ($productDetail != null) {
            $product = new Product();
            foreach ($productDetail as $item) {
                $product = $item;
            }
            return $product;
        }

        return $productDetail;
    }

}

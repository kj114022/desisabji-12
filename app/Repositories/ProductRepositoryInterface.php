<?php
/**
 * File name: ProductRepository.php
 * Last modified: 2020.06.07 at 07:02:57

 *
 */

namespace App\Repositories;

use App\Models\Product;

use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
/**
 * Class ProductRepository
 * @package App\Repositories
 * @version August 29, 2019, 9:38 pm UTC
 *
 * @method Product findWithoutFail($id, $columns = ['*'])
 * @method Product find($id, $columns = ['*'])
 * @method Product first($columns = ['*'])
 */
interface ProductRepositoryInterface extends BaseRepositoryInterface, CacheableInterface
{
    public function productByCity($productId,$cityId);
    public function productByCategory($productId,$categoryId);
    public function groupedByMarkets();
    public function myProducts();
    
}

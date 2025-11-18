<?php
/**
 * File name: DiscountableRepository.php
 * Last modified: 2019.08.27 at 15:37:12

 */

namespace App\Repositories\Eloquent;

use App\Models\Discountable;
use App\Repositories\DiscountableRepositoryInterface;
/**
 * Class DiscountableRepository
 * @package App\Repositories
 * @version July 24, 2018, 9:13 pm UTC
 *
 * @method Discountable findWithoutFail($id, $columns = ['*'])
 * @method Discountable find($id, $columns = ['*'])
 * @method Discountable first($columns = ['*'])
*/
class DiscountableRepository extends BaseRepository implements DiscountableRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'custom_field_id',
        'customizable_type'
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
    public function __construct(Discountable $model)
    {
        $this->model = $model;
    }
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Discountable::class;
    }
}

<?php
/**
 * File name: SlideRepository.php
 * Last modified: 2020.09.12 at 20:01:58

 *
 */

namespace App\Repositories\Eloquent;

use App\Models\Slide;
use App\Repositories\SlideRepositoryInterface;

/**
 * Class SlideRepository
 * @package App\Repositories
 * @version September 1, 2020, 7:27 pm UTC
 *
 * @method Slide findWithoutFail($id, $columns = ['*'])
 * @method Slide find($id, $columns = ['*'])
 * @method Slide first($columns = ['*'])
*/
class SlideRepository extends BaseRepository implements SlideRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'order',
        'text',
        'button',
        'text_position',
        'text_color',
        'button_color',
        'background_color',
        'indicator_color',
        'image_fit',
        'product_id',
        'market_id',
        'enabled'
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
    public function __construct(Slide $model)
    {
        $this->model = $model;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Slide::class;
    }
}

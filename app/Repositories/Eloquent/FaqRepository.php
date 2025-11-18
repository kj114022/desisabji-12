<?php

namespace App\Repositories\Eloquent;

use App\Models\Faq;
use App\Repositories\FaqRepositoryInterface;
/**
 * Class FaqRepository
 * @package App\Repositories
 * @version August 29, 2019, 9:39 pm UTC
 *
 * @method Faq findWithoutFail($id, $columns = ['*'])
 * @method Faq find($id, $columns = ['*'])
 * @method Faq first($columns = ['*'])
*/
class FaqRepository extends BaseRepository implements FaqRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'question',
        'answer',
        'faq_category_id'
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
    public function __construct(Faq $model)
    {
        $this->model = $model;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Faq::class;
    }
}

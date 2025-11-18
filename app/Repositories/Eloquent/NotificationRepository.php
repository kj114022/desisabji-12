<?php

namespace App\Repositories\Eloquent;

use App\Models\Notification;
use App\Repositories\NotificationRepositoryInterface;

/**
 * Class NotificationRepository
 * @package App\Repositories
 * @version September 4, 2019, 10:30 am UTC
 *
 * @method Notification findWithoutFail($id, $columns = ['*'])
 * @method Notification find($id, $columns = ['*'])
 * @method Notification first($columns = ['*'])
 */
class NotificationRepository extends BaseRepository implements NotificationRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type',
        'read_at'
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
    public function __construct(Notification $model)
    {
        $this->model = $model;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Notification::class;
    }
}

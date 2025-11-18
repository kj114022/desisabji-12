<?php

namespace App\Criteria\Users;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class DriverOfUserCriteria.
 *
 * @package namespace App\Criteria;
 */
class FilterByUserCriteria implements CriteriaInterface
{
    private $userId;

    /**
     * DriverOfUserCriteria constructor.
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
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
        Log::info('Applying FilterByUserCriteria', ['user_id' => $this->userId]);
        return $model->where(["user_id"=>$this->userId]);
    }
}

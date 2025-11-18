<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Eloquent\BaseRepository as Base;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Log;

abstract class BaseRepository extends Base implements BaseRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Collection of Criteria
     *
     * @var Collection
     */
    protected $criteria;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    protected $app;

    public function __construct(Container $app)
        {
            parent::__construct($app);
            $this->app = $app;
            Log::info('BaseRepository initialized', ['version' => $this->app->version()]);
            Log::info('App initialized', ['version' => config('app.version')]);
            //var_dump($this->app);
        }

 

    /**
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function allWithRelations(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->with($relations)->get($columns);
    }

    /**
     * Get all trashed models.
     *
     * @return Collection
     */
    public function allTrashed(): Collection
    {
        return $this->model->onlyTrashed()->get();
    }

    /**
     * Find model by id.
     *
     * @param int $modelId
     * @param array $columns
     * @param array $relations
     * @param array $appends
     * @return Model
     */
    public function findById(
        int $modelId,
        array $columns = ['*'],
        array $relations = [],
        array $appends = []
    ): ?Model {
        return $this->model->select($columns)->with($relations)->findOrFail($modelId)->append($appends);
    }

    /**
     * Find trashed model by id.
     *
     * @param int $modelId
     * @return Model
     */
    public function findTrashedById(int $modelId): ?Model
    {
        return $this->model->withTrashed()->findOrFail($modelId);
    }

    /**
     * Find only trashed model by id.
     *
     * @param int $modelId
     * @return Model
     */
    public function findOnlyTrashedById(int $modelId): ?Model
    {
        return $this->model->onlyTrashed()->findOrFail($modelId);
    }

    /**
     * Create a model.
     *
     * @param array $payload
     * @return Model
     */
    public function create(array $payload): ?Model
    {
        $model = $this->model->create($payload);

        return $model->fresh();
    }

    /**
     * Update existing model.
     *
     * @param int $modelId
     * @param array $payload
     * @return bool
     */
    public function updatePayload(int $modelId, array $payload): bool
    {
        $model = $this->findById($modelId);

        return $model->update($payload);
    }

    /**
     * Delete model by id.
     *
     * @param int $modelId
     * @return bool
     */
    public function deleteById(int $modelId): bool
    {
        return $this->findById($modelId)->delete();
    }

    /**
     * Restore model by id.
     *
     * @param int $modelId
     * @return bool
     */
    public function restoreById(int $modelId): bool
    {
        return $this->findOnlyTrashedById($modelId)->restore();
    }

    /**
     * Permanently delete model by id.
     *
     * @param int $modelId
     * @return bool
     */
    public function permanentlyDeleteById(int $modelId): bool
    {
        return $this->findTrashedById($modelId)->forceDelete();
    }

        /**
     * @return Model
     * @throws RepositoryException
     */
    public function makeModel()
    {
       // var_dump($this->app->make($this->model()));

        return $this->model;
    }

    public function findWithoutFail($id, $columns = ['*'])
    {
        try {
            return $this->find($id, $columns);
        } catch (Exception $e) {
            Log::error('Error finding model without fail', ['id' => $id, 'error' => $e->getMessage()]);
            return;
        }
    }
    
     public function boot()
    {
        //
    }
   
}
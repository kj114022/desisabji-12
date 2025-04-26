#!/bin/bash
# filepath: fix_repositories.sh

echo "🔍 Starting repository refactoring process..."

# Create a backup directory
BACKUP_DIR="repository_backups_$(date +%Y%m%d%H%M%S)"
mkdir -p $BACKUP_DIR
echo "📁 Created backup directory: $BACKUP_DIR"

# Check if BaseRepository exists, if not, create it
if [ ! -f "app/Repositories/BaseRepository.php" ]; then
  echo "🔧 Creating custom BaseRepository class..."
  cat > app/Repositories/BaseRepository.php << 'EOL'
<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Container\Container as Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

abstract class BaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var array
     */
    protected $fieldSearchable = [];

    /**
     * Constructor
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    /**
     * Get model class name
     * @return string
     */
    abstract public function model();

    /**
     * Make Model instance
     * @return Model
     * @throws \Exception
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new \Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    /**
     * Retrieve all records
     * @param array $columns
     * @return Collection
     */
    public function all($columns = ['*'])
    {
        return $this->model->all($columns);
    }

    /**
     * Find model record by id
     * @param int $id
     * @param array $columns
     * @return Model
     */
    public function find($id, $columns = ['*'])
    {
        return $this->model->find($id, $columns);
    }

    /**
     * Find model record by id or throw an exception
     * @param int $id
     * @param array $columns
     * @return Model
     */
    public function findOrFail($id, $columns = ['*'])
    {
        return $this->model->findOrFail($id, $columns);
    }

    /**
     * Find model record by id or return fresh instance
     * @param int $id
     * @param array $columns
     * @return Model
     */
    public function findWithoutFail($id, $columns = ['*'])
    {
        return $this->find($id, $columns) ?? $this->model;
    }

    /**
     * Get first record
     * @param array $columns
     * @return Model
     */
    public function first($columns = ['*'])
    {
        return $this->model->first($columns);
    }

    /**
     * Create model record
     * @param array $input
     * @return Model
     */
    public function create($input)
    {
        $model = $this->model->newInstance($input);
        $model->save();
        return $model;
    }

    /**
     * Update model record
     * @param array $input
     * @param int $id
     * @return Model
     */
    public function update($input, $id)
    {
        $model = $this->find($id);
        $model->fill($input);
        $model->save();
        return $model;
    }

    /**
     * Delete model record
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $model = $this->find($id);
        return $model->delete();
    }

    /**
     * Find by condition
     * @param array $where
     * @param array $columns
     * @return Collection
     */
    public function findWhere(array $where, $columns = ['*'])
    {
        return $this->model->where($where)->get($columns);
    }

    /**
     * Find by condition (first match)
     * @param array $where
     * @param array $columns
     * @return Model
     */
    public function findWhereFirst(array $where, $columns = ['*'])
    {
        return $this->model->where($where)->first($columns);
    }

    /**
     * Get searchable fields
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Paginate records
     * @param int $perPage
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function paginate($perPage = 25, $columns = ['*'])
    {
        return $this->model->paginate($perPage, $columns);
    }

    /**
     * Order by specific field
     * @param string $column
     * @param string $direction
     * @return $this
     */
    public function orderBy($column, $direction = 'asc')
    {
        $this->model = $this->model->orderBy($column, $direction);
        return $this;
    }
}
EOL
  echo "✅ Custom BaseRepository created successfully!"
fi

# Find all repository files that use InfyOm
echo "🔍 Finding repository files that use InfyOm BaseRepository..."
repo_files=$(grep -l "use InfyOm\\\\Generator\\\\Common\\\\BaseRepository" app/Repositories/*.php)

# Count the number of files to process
file_count=$(echo "$repo_files" | wc -l | tr -d '[:space:]')
if [ "$file_count" -eq 0 ]; then
  echo "ℹ️ No files found that use InfyOm BaseRepository."
  exit 0
fi

echo "🔄 Found $file_count repository files to update."
echo ""

# Process each file
current=0
for file in $repo_files; do
  current=$((current + 1))
  echo "[$current/$file_count] 🔄 Processing $file"
  
  # Create backup
  cp "$file" "$BACKUP_DIR/$(basename $file)"
  
  # Remove InfyOm import
  sed -i "s/use InfyOm\\\\Generator\\\\Common\\\\BaseRepository;//g" "$file"
  
  # Replace title_case with ucwords
  sed -i "s/title_case(/ucwords(/g" "$file"
  
  # Check if file has model method, add one if missing
  if ! grep -q "public function model()" "$file"; then
    # Extract class name to determine model
    classname=$(basename "$file" .php)
    modelname=${classname%Repository}
    
    # Find the position to add the model method (after fieldSearchable)
    position=$(grep -n "protected \$fieldSearchable" "$file" | cut -d ':' -f1)
    position=$((position + 3)) # Add a few lines to get past the fieldSearchable array
    
    model_method="
    /**
     * Configure the Model
     **/
    public function model()
    {
        return \\App\\Models\\${modelname}::class;
    }"
    
    # Insert the model method at the right position
    sed -i "${position}i\\${model_method}" "$file"
    
    echo "   📝 Added missing model method for $modelname"
  fi
  
  echo "   ✅ Updated $file"
done

echo ""
echo "�� Repository refactoring completed successfully!"
echo "📁 Backups saved in $BACKUP_DIR"
echo ""
echo "🧹 Clearing Laravel caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo "✅ Caches cleared!"

echo ""
echo "💡 Next steps:"
echo "  1. Test your application to ensure all repositories work correctly"
echo "  2. Run 'php artisan route:list' to verify routes are working properly"
echo "  3. Check if your API controllers need updates as well"

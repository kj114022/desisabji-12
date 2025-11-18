<?php

namespace App\Repositories;

use App\Models\Media;
use App\Models\Upload;
use Illuminate\Support\Facades\Log;

/**
 * Class UploadRepository
 * @package App\Repositories
 * @version June 12, 2018, 11:30 am UTC
 *
 * @method Upload findWithoutFail($id, $columns = ['*'])
 * @method Upload find($id, $columns = ['*'])
 * @method Upload first($columns = ['*'])
 */
interface UploadRepositoryInterface extends BaseRepositoryInterface
{
    
}

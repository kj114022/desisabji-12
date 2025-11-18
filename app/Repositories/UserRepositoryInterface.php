<?php

namespace App\Repositories;

use App\Models\User;

/**
 * Class UserRepository
 * @package App\Repositories
 * @version July 10, 2018, 11:44 am UTC
 *
 * @method User findWithoutFail($id, $columns = ['*'])
 * @method User find($id, $columns = ['*'])
 * @method User first($columns = ['*'])
*/
interface UserRepositoryInterface extends BaseRepositoryInterface
{
   public function generateNumericOTP($length, $mobile, User $user): string;
   public function sendSms($mobile, $message): bool;
   public function sendEmail($data): void;
}

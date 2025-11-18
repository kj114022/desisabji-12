<?php

namespace App\Repositories;

use App\Models\Registration;

/**
 * Class RegisterRepository
 * @package App\Repositories
 * @version July 10, 2018, 11:44 am UTC
 *
 * @method Registration findWithoutFail($id, $columns = ['*'])
 * @method Registration find($id, $columns = ['*'])
 * @method Registration first($columns = ['*'])
*/
interface RegisterRepositoryInterface extends BaseRepositoryInterface
{
   public function generateNumericOTP($length, $mobile, Registration $registration): string;
   public function sendSms($mobile, $message): bool;
   public function sendEmail($data): void;
}

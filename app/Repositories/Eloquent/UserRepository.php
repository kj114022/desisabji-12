<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;

/**
 * Class UserRepository
 * @package App\Repositories
 * @version July 10, 2018, 11:44 am UTC
 *
 * @method User findWithoutFail($id, $columns = ['*'])
 * @method User find($id, $columns = ['*'])
 * @method User first($columns = ['*'])
*/
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'name',
        'email',
        'mobile',
        'city_id',
        'city',
        'state',
        'password',
        'api_token',
        'profile_image',
        'role_id',
        'remember_token',
        'login_id',
        'customer_id',
        'mobile_otp',
        'otp_expires_at',
        'is_active',
        'gender',
        'first_name',
        'last_name',
        'dob',
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
    public function __construct(User $model)
    {
        $this->model = $model;
    }
    /**
     * Configure the Model
     **/
    public function model()
    {
        return User::class;
    }

    public function generateNumericOTP($length, $mobile, User $user): string
    {
        // Generate a numeric OTP of specified length
        $otp = rand(pow(10, $length - 1), pow(10, $length) - 1);
        // Store the OTP in the user's model or session as needed
        $user->mobile_otp = $otp;
        $newTime = strtotime('+2 minutes');
        $user->otp_expires_at = date('Y-m-d H:i:s', $newTime);
        $user->save();
        return (string)$otp;
    }

    public function sendSms($mobile, $message): bool
    {
        // Implement SMS sending logic here
        // For example, using a third-party service like Twilio or Nexmo
        // Return true if the SMS was sent successfully, false otherwise
        // This is a placeholder implementation
        return true;
    }

    
    /**
     * Send email to the user
     *
     * @param string $otp
     * @param Registration $registration
     * @return void
     */
    public function sendEmail($data): void
    {
        // Implement email sending logic here
        // For example, using a service like Mailgun or SendGrid

         Mail::to('algoliveindia@gmail.com')->send(new SendEmail($data));
    }
}
